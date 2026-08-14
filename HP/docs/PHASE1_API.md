# 会員マイページ Phase1 — API 一覧（店舗横展開共通）

> Source-attached technical reference. Parent technical index: [`MEMBER_ARCHITECTURE.md`](MEMBER_ARCHITECTURE.md). This file is outside the formal management-document tree. Actual code and SQL take precedence; live database schema, executed migrations, credentials, CTI connection, SMS delivery, deployment, and production behavior remain `UNVERIFIED` until checked through the applicable routed operation.

初回実装: `candy_new`（`MEMBER_CLUB_ID = 2`）  
店舗DB: `fsg_db`（`customers_*` テーブル）  
CTI DB: 本番 `cti`（参照のみ）

横展開設計: [MEMBER_ARCHITECTURE.md](./MEMBER_ARCHITECTURE.md)

---

## 共通仕様

### 店舗スコープ

- すべての API は **リクエスト元サイトの `MEMBER_CLUB_ID`** でスコープされる
- クライアントから `club_id` を送らない（改ざん防止）
- 電話番号重複チェック: `(club_id, phone)` 単位

### ベースURL（candy_new 想定）

| 種別 | パス |
|------|------|
| 画面 | `/member_login.php`, `/member_register.php`, `/member_mypage.php` |
| API | `/member/api.php` |

### レスポンス形式（JSON）

```json
{
  "status": 0,
  "message": "",
  "data": { }
}
```

| status | 意味 |
|--------|------|
| 0 | 成功 |
| -1 | 汎用エラー |
| -2 | バリデーションエラー |
| -3 | 認証エラー |
| -4 | 電話番号重複（同一 club_id 内） |
| -5 | SMSコード不正・期限切れ |
| -6 | ログイン失敗 |
| -7 | 退会済み |
| -8 | CTI顧客複数ヒット |

### 認証 Cookie（`MEMBER_COOKIE_PREFIX` 使用）

| Cookie | 用途 |
|--------|------|
| `{prefix}_member_session` | セッション |
| `{prefix}_member_remember` | 30日維持（CANDY: `candy_member_session`） |

---

## CTI 顧客紐づけ

ログイン・登録完了時に毎回実行。

```
1. customers_accounts.phone を正規化
2. CTI guests で tel0/tel1/tel2 照合
3. 0件 → guest_id NULL
4. 1件 → guest_id UPDATE
5. 複数 → status=-8、guest_id は更新しない
```

---

## Phase1 API 一覧

### 認証不要

| fno | 名称 | 概要 |
|-----|------|------|
| 001 | register_send_sms | 登録用SMS（mock） |
| 002 | register | SMS検証 + 会員登録 |
| 003 | login | ログイン |
| 004 | password_reset_send_sms | PW再設定SMS |
| 005 | password_reset_verify | SMS検証 → reset_token |
| 006 | password_reset_confirm | 新PW（自動ログインなし） |
| 007 | terms | 利用規約（`customers_legal_documents`） |
| 008 | privacy | プライバシーポリシー |

### 認証必須

| fno | 名称 | 概要 |
|-----|------|------|
| 101 | me | 会員情報 |
| 102 | me_update | ニックネーム等更新 |
| 103 | logout | ログアウト |
| 104 | withdraw | アカウント削除（パスワード必須・物理削除） |
| 105 | phone_change_send_sms | 電話変更SMS |
| 106 | phone_change_confirm | 電話変更確定 |

---

## API 詳細（抜粋）

### fno=001 register_send_sms

```json
{ "phone": "09012345678" }
```

- `customers_accounts` で `(club_id, phone)` 重複 → -4
- `customers_sms_codes` INSERT（`club_id = MEMBER_CLUB_ID`）
- Phase1: `data.mock_code` を返す

### fno=002 register

```json
{
  "phone": "09012345678",
  "code": "123456",
  "password": "secret123",
  "password_confirm": "secret123",
  "nickname": "太郎",
  "terms_agreed": true,
  "privacy_agreed": true,
  "remember_me": false
}
```

- `customers_accounts` INSERT（`club_id`, `terms_version` は現行規約から取得）
- CTI guest 紐づけ

### fno=003 login

- `(club_id, phone)` + password 照合
- CTI guest 紐づけ（毎回）
- remember_me: 30日 / セッションのみ

### fno=007 / 008 terms / privacy

`customers_legal_documents` から `club_id = MEMBER_CLUB_ID` かつ `status=1` の現行版を返す。

---

## 利用履歴（Phase2・CTI 読取）

```sql
SELECT t.id, t.date, t.start, t.end, t.course, t.cast_id
FROM tasks t
WHERE t.guest_id = :guest_id
  AND t.club_id = :member_club_id
  AND (t.end_stat IS NULL OR t.end_stat NOT IN (1, 2, 3))
  AND NOT (t.stat = 0 AND t.end_stat = 1)
ORDER BY t.date DESC, t.start DESC;
```

---

## 実装ファイル

| ファイル | 内容 |
|----------|------|
| `includefile/member/config.php` | `MEMBER_CLUB_ID` 等 |
| `includefile/member/MemberAuth.php` | 認証 |
| `includefile/member/MemberCti.php` | CTI接続 |
| `includefile/member/MemberSms.php` | SMS mock |
| `member/api.php` | API入口 |

---

## 実装順序

1. `sql/001_phase1_member.sql` を fsg_db に実行
2. `sql/003_seed_candy_legal.sql` を実行（CANDY 規約・PP）
3. 以下が Phase1 実装済み:
   - `includefile/member/*` … 認証・API・CTI連携
   - `member/api.php` … JSON API
   - `member_login.php` / `member_register.php` / `member_mypage.php` / `member_password_reset.php`
   - `mypage.php` … 旧URL互換（ログイン状態で振り分け）
   - `css/member.css` / `js/member.js`
   - `dataset_base.php` … 全ページヘッダーに「ログイン」+ 新マイページリンクを自動挿入

---

## Phase 分割

| Phase | 内容 |
|-------|------|
| **1** | 登録・ログイン・会員情報・規約PP・CTI紐づけ |
| 2 | 利用履歴・評価 |
| 3 | お気に入り |
| 4 | お知らめ（control・`club_id` 管理） |
| 5 | メール登録・お知らせメール通知 |
