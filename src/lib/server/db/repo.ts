import { db } from './index';

export interface Entry {
	id: number;
	name: string;
	contact: string;
	address: string;
	secret: string;
	status: number;
	createdAt: string;
}

const ENTRY_COLS = 'id, name, contact, address, secret, status, created_at AS createdAt';

/** ステータスのビットフラグ(元実装と同じ)。 */
export const STATUS_ENTRY = 1;
export const STATUS_PAID = 2;

/** ステータス値を画面表示用ラベルにする(元実装のラベルを踏襲)。 */
export function statusLabel(status: number): string {
	switch (status) {
		case 0:
			return 'unpaid, unentry';
		case 1:
			return 'unpaid, entry';
		case 2:
			return 'paid, unentry';
		case 3:
			return 'paid, entry';
		default:
			return 'unknown';
	}
}

/** 重複しない 9 桁のシークレットを生成する(元実装と同じ範囲)。 */
export function generateSecret(): string {
	while (true) {
		const n = 100000000 + Math.floor(Math.random() * 900000000);
		const secret = String(n);
		if (!getEntryBySecret(secret)) return secret;
	}
}

export function createEntry(input: { name: string; contact: string; address: string }): Entry {
	const secret = generateSecret();
	return db
		.query(
			`INSERT INTO entries (name, contact, address, secret, status)
			 VALUES (?, ?, ?, ?, 0) RETURNING ${ENTRY_COLS}`,
		)
		.get(input.name, input.contact, input.address, secret) as Entry;
}

export function getEntryBySecret(secret: string): Entry | null {
	return (
		(db.query(`SELECT ${ENTRY_COLS} FROM entries WHERE secret = ?`).get(secret) as Entry | null) ??
		null
	);
}

export function getAllEntries(): Entry[] {
	return db.query(`SELECT ${ENTRY_COLS} FROM entries ORDER BY id`).all() as Entry[];
}

/** pay / entry はトグル、pe は両フラグを立てる(元実装と同じ挙動)。 */
export function toggleStatus(entry: Entry, action: 'pay' | 'entry' | 'pe'): Entry {
	let status = entry.status;
	if (action === 'pay') status ^= STATUS_PAID;
	else if (action === 'entry') status ^= STATUS_ENTRY;
	else status = STATUS_PAID | STATUS_ENTRY;
	return db
		.query(`UPDATE entries SET status = ? WHERE id = ? RETURNING ${ENTRY_COLS}`)
		.get(status, entry.id) as Entry;
}
