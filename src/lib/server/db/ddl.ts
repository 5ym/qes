import type { Database } from 'bun:sqlite';

/**
 * 冪等なスキーマブートストラップ — テーブル定義の単一ソース。
 * 起動時に実行されるため、新しい SQLite ファイルでもマイグレーション不要で動く。
 */
export const DDL = `
CREATE TABLE IF NOT EXISTS entries (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	name TEXT NOT NULL,
	contact TEXT NOT NULL,
	address TEXT NOT NULL,
	secret TEXT NOT NULL UNIQUE,
	status INTEGER NOT NULL DEFAULT 0,
	created_at TEXT NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE IF NOT EXISTS users (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	email TEXT NOT NULL UNIQUE,
	password_hash TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions (
	id TEXT PRIMARY KEY,
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
	expires_at INTEGER NOT NULL
);
`;

export function ensureSchema(sqlite: Database): void {
	sqlite.exec(DDL);
}
