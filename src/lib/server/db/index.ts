import { Database } from 'bun:sqlite';
import { mkdirSync } from 'node:fs';
import { dirname } from 'node:path';
import { env } from '$env/dynamic/private';
import { ensureSchema } from './ddl';

const url = env.DATABASE_URL ?? './data/qes.db';

if (url !== ':memory:') {
	mkdirSync(dirname(url), { recursive: true });
}

export const db = new Database(url, { create: true });
db.exec('PRAGMA journal_mode = WAL;');
db.exec('PRAGMA foreign_keys = ON;');
ensureSchema(db);
