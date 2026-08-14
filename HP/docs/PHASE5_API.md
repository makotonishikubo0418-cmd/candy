# Phase5 API（メール・通知）

> Source-attached technical reference. Parent technical index: [`MEMBER_ARCHITECTURE.md`](MEMBER_ARCHITECTURE.md). This file is outside the formal management-document tree. Actual code and SQL take precedence; live mail mode, scheduler, sendmail, database state, deployment, and production behavior remain `UNVERIFIED` until checked through the applicable routed operation.

## 前提

- `sql/005_phase5_member.sql` を Phase1 後に実行
- `customers_accounts.email` は認証完了後のみ設定
- Phase5 初期は **MEMBER_MAIL_MOCK=true**（`log/member_mail/` に出力）

## fno=107 メール認証コード送信

**POST** `member/api.php?fno=107`（要ログイン）

```json
{ "email": "user@example.com" }
```

- 開発時 `data.mock_code` を返す（SMS と同様）
- 他会員が使用中のメール → status=-4

## fno=108 メールアドレス登録確定

```json
{ "email": "user@example.com", "code": "123456" }
```

- 認証成功で `customers_accounts.email` を更新

## fno=501 通知設定取得

```json
{ "notify_mypage_info": true, "has_email": true }
```

## fno=502 通知設定更新

```json
{ "notify_mypage_info": true }
```

- メール未登録で ON にしようとすると -2

## お知らせメール配信（バッチ）

```bash
php member/cron_notify_info.php
```

- `notify_mypage_info=1` かつメール登録済み会員へ送信
- `customers_info_mail_log` で重複送信を防止
- cron 推奨: 15分〜1時間ごと

## 本番メール送信

`includefile/member/config.php`:

```php
define('MEMBER_MAIL_MOCK', false);
define('MEMBER_MAIL_FROM', 'noreply@55810.com');
```

サーバーで `mb_send_mail` または sendmail の設定が必要です。

## 注意

- メール本文はプレーンテキスト
- お知らせ更新の再通知は新規 `customers_mypage_info` レコードで対応（Phase4 と同様）
