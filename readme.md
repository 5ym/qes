# QRcode Entry System
laravelの勉強用にQRcodeで入場支払い管理できる簡易的なものを作成してみました

## 動作環境
- PHP 8.3 以上
- Laravel 13
- Node.js 20 以上（アセットビルド用）

## セットアップ
```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

npm install
npm run build     # 開発中は npm run dev
```

ビルド成果物（`public/build/`）はリポジトリにコミットしています。
フロントエンドの依存を更新したら `npm run build` の結果も一緒にコミットしてください。

## テスト
```sh
php artisan test
```
