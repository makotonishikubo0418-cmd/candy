# Phase6 API（会員情報仕様拡張）

> Source-attached technical reference. Parent technical index: [`MEMBER_ARCHITECTURE.md`](MEMBER_ARCHITECTURE.md). This file is outside the formal management-document tree. Actual code and SQL take precedence; live database migration state, AI-project integration, deployment, and production behavior remain `UNVERIFIED` until checked through the applicable routed operation.

## 前提

- `sql/006_phase6_member_profile.sql` を Phase1〜5 投入後に実行すること
- `customers_phones` テーブル・`customers_accounts.birthday` カラムが必要
- 本登録（fno=002）完了後は **自動ログインしない** → `member_login.php?registered=1` へ遷移

## DB変更概要

| 対象 | 内容 |
|------|------|
| `customers_accounts` | `birthday` DATE NULL 追加 |
| `customers_phones` | 電話最大3件、主電話1件（`is_primary`） |
| `customers_sms_codes.purpose` | `phone_add` を追加 |
| データ移行 | 既存 `customers_accounts.phone` を主電話として `customers_phones` へ INSERT |

## fno=101 会員情報取得（拡張レスポンス）

**POST** `member/api.php?fno=101`（要ログイン）

```json
{
  "nickname": "たろう",
  "nickname_display": "たろう",
  "phones": [
    { "id": 1, "phone_masked": "090****5678", "is_primary": true }
  ],
  "phone_slots_remaining": 2,
  "birthday": "1990-01-15",
  "birthday_display": "1990-01-15",
  "email": "u***@example.com",
  "has_email": true,
  "notify_mypage_info": true,
  "password_display": "********"
}
```

## fno=102 プロフィール更新

```json
{ "nickname": "任意", "birthday": "1990-01-15" }
```

- ニックネームは空欄可（未登録扱い）
- 誕生日は空欄でクリア可
- バリデーションエラー時 `field_errors` を返却

## fno=109 パスワード変更

```json
{
  "current_password": "現在のPW",
  "new_password": "新しいPW",
  "password_confirm": "新しいPW"
}
```

- 半角英数字 **8〜20文字**（`MEMBER_PASSWORD_MAX_LEN`）
- 成功後 `data.require_relogin: true` → 全セッション無効化

## fno=110 メールアドレス削除

- メールを NULL にし、お知らせ配信を自動 OFF

## fno=105 / 106 電話番号 SMS認証

**105 送信**

```json
{ "phone": "09012345678", "action": "add" }
```

```json
{ "phone": "09012345678", "action": "change_primary" }
```

**106 確定**

```json
{ "phone": "09012345678", "code": "123456", "action": "add" }
```

- `add`: 最大3件まで追加（副電話）
- `change_primary`: 主電話番号の番号変更 → 成功後再ログイン必須

## fno=111 電話番号削除

```json
{ "phone_id": 2 }
```

- 登録1件のみの場合は削除不可
- 主電話削除時は別番号を自動で主に昇格 → 再ログイン必須

## fno=112 主電話切替

```json
{ "phone_id": 2 }
```

- 既存の副電話を主電話に昇格 → 再ログイン必須

## fno=502 通知設定更新（レスポンス拡張）

- 成功時 `memberPayload` 全体を `data` に返却（Phase5 から変更）
- メール未登録で ON にすると `field_errors.notify_mypage_info`

## 共通: field_errors

```json
{
  "status": -2,
  "message": "入力内容に不備があります",
  "field_errors": {
    "nickname": "…",
    "birthday": "…",
    "notify_mypage_info": "…"
  }
}
```

## AI受付との連携（別プロジェクト）

`MemberProfile` クラス（`includefile/member/MemberProfile.php`）を include し、
`buildPayload` / `updateBasic` 等を AI 側から呼び出す想定。
Web API 経由の場合は fno=101/102 を Bearer セッションで利用可能。

## fno=104 アカウント削除

**POST** `member/api.php?fno=104`（要ログイン）

```json
{ "password": "現在のパスワード" }
```

- パスワード確認後、`customers_accounts` を物理削除（関連データは FK CASCADE）
- 削除後は同じ電話番号で再登録可能
- 監査ログに `account_delete` を記録

## デプロイ順

1. `sql/006_phase6_member_profile.sql` を本番 DB へ投入
2. `includefile/member/*`・`js/member.js`・`source/member_*.html`・`css/member_site.css` をアップロード
3. 登録 → ログイン → マイページで電話・誕生日・PW変更を確認
