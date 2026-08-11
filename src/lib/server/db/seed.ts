/**
 * シードスクリプト — `bun run db:seed` で実行。
 * スタッフユーザーを作成する(SvelteKit エイリアス非依存で単体実行可能)。
 */
import { Database } from 'bun:sqlite';
import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import { ensureSchema } from './ddl';

const url = process.env.DATABASE_URL ?? './data/qes.db';
if (url !== ':memory:') mkdirSync(dirname(url), { recursive: true });

const db = new Database(url, { create: true });
db.exec('PRAGMA foreign_keys = ON;');
ensureSchema(db);

const email = process.env.ADMIN_EMAIL ?? 'admin@qes.local';
const password = process.env.ADMIN_PASSWORD ?? 'adminpassword';

const existing = db.query('SELECT id FROM users WHERE email = ?').get(email);
if (!existing) {
	const hash = await Bun.password.hash(password, 'argon2id');
	db.query('INSERT INTO users (email, password_hash) VALUES (?, ?)').run(email, hash);
	console.info(`[seed] created staff user: ${email} / ${password}`);
} else {
	console.info(`[seed] staff user already exists: ${email}`);
}
console.info('[seed] done.');
