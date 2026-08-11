# QRcode Entry System

QRコードで入場・支払を管理できる簡易システム。

元の **Laravel(PHP)+ jQuery + Materialize** 構成から
**SvelteKit(Svelte 5)+ Bun + SQLite + DaisyUI + Biome** へ全面的に書き換えたものです。

## 技術スタック

| 領域           | 使用技術                                        |
| -------------- | ----------------------------------------------- |
| ランタイム     | Bun 1.3+                                        |
| フレームワーク | SvelteKit(Svelte 5 / runes)+ adapter-node     |
| データベース   | SQLite(`bun:sqlite`、生SQL)                   |
| UI             | Tailwind CSS v4 + DaisyUI v5                    |
| 認証           | サーバーサイドセッション + `Bun.password`       |
| Lint/Format    | Biome                                           |
| QR             | `qrcode`(クライアントで生成)                  |

実行時依存はゼロ(すべて devDependencies でビルドに焼き込み)。

## 画面フロー

- `/` — 参加者の入場登録(名前・連絡先・住所)→ 9桁シークレット+QRコード発行。
  シークレットからの QR 再表示も可能
- `/status?secret=…`(要スタッフログイン)— 登録内容とステータス表示、
  「支払切替 / 入場切替 / 支払+入場」ボタンで更新(QR の読み取り先)
- `/list`(要スタッフログイン)— 全登録の一覧
- ステータスはビットフラグ(1=入場, 2=支払)で元実装と互換

## セットアップ

```shell
bun install
bun run db:seed   # スタッフユーザー作成(既定: admin@qes.local / adminpassword)
bun run dev       # http://localhost:5173
```

`ADMIN_EMAIL` / `ADMIN_PASSWORD` / `DATABASE_URL`(既定 `./data/qes.db`)で変更できます。
テーブルは初回接続時に自動作成されます。

## 本番

```shell
bun run build
DATABASE_URL=./data/qes.db ORIGIN=https://your-domain bun ./build/index.js
```

`bun:sqlite` を使うため必ず Bun で起動してください。リバースプロキシ配下では `ORIGIN` の設定が必要です。

## 開発コマンド

| コマンド          | 内容                              |
| ----------------- | --------------------------------- |
| `bun run check`   | 型チェック(svelte-check + tsgo) |
| `bun run lint`    | Biome チェック                    |
| `bun run format`  | Biome 整形                        |
| `bun run db:seed` | スタッフユーザー作成              |
