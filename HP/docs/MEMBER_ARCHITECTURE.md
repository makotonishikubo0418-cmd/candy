# 会員マイページ — 横展開アーキテクチャ

> Source-attached technical reference. The canonical management owner is [`CANDY_OTHER_PAGES_MANAGEMENT.md`](../../codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md). This file is the technical index for [`PHASE1_API.md`](PHASE1_API.md), [`PHASE2_API.md`](PHASE2_API.md), [`PHASE3_API.md`](PHASE3_API.md), [`PHASE4_API.md`](PHASE4_API.md), [`PHASE5_API.md`](PHASE5_API.md), and [`PHASE6_API.md`](PHASE6_API.md), and is outside the formal management-document tree.
>
> Actual code and SQL files are the source for static implementation behavior. Database migrations, live schemas, credentials, enabled integration state, scheduler state, server placement, and production behavior remain `UNVERIFIED` until checked through the applicable route in `codex/WORK_ROUTING.md`. Do not execute database, cron, deployment, or external-service operations from this reference alone.

## 方針

| 項目 | 内容 |
|------|------|
| DB | `fsg_db` に **店舗共通**テーブル（`customers_*`） |
| 店舗識別 | 全主要テーブルに `club_id` |
| 電話番号 | **店舗ごと**ユニーク `(club_id, phone)` |
| サイト | 店舗ごとに別ディレクトリ（例: `candy_new`, 将来 `after5_new`） |
| 共通ロジック | `includefile/member/` を各サイトから require（または symlinks） |
| CTI | 本番 `cti` DB 参照。履歴は `tasks.club_id = MEMBER_CLUB_ID` で絞る |

## 店舗ごとの設定（各サイト先頭で定義）

```php
// candy_new/includefile/member/config.php
define('MEMBER_CLUB_ID', 2);           // CANDY
define('MEMBER_CLUB_NAME', 'CANDY');
define('MEMBER_COOKIE_PREFIX', 'candy'); // Cookie名: candy_member_session 等
```

他店舗展開時は **MEMBER_CLUB_ID だけ変更**（テーブル・API fno は共通）。

## テーブル一覧

### Phase1（`sql/001_phase1_member.sql`）

| テーブル | 説明 |
|----------|------|
| `customers_accounts` | 会員マスタ |
| `customers_sms_codes` | SMS認証 |
| `customers_remember_tokens` | 30日ログイン維持 |
| `customers_sessions` | セッション |
| `customers_audit_logs` | 監査ログ |
| `customers_legal_documents` | 店舗別 規約・PP |

### Phase2以降（`sql/002_phase2plus_member.sql`）

| テーブル | Phase | 単体SQL |
|----------|-------|---------|
| `customers_girl_evaluations` | 2 | `sql/002_phase2_member.sql` |
| `customers_favorites` | 3 | `sql/003_phase3_member.sql` |
| `customers_mypage_info` / `_read` | 4 | `sql/004_phase4_member.sql` |
| `customers_email_codes` / `customers_notification_settings` / `customers_info_mail_log` | 5 | `sql/005_phase5_member.sql` |

## 横展開チェックリスト（新店舗追加時）

1. サイト用ディレクトリ作成（既存 candy をコピー）
2. `MEMBER_CLUB_ID` を設定
3. `customers_legal_documents` に当店舗の規約・PP を INSERT
4. control に当店舗用お知らめ管理（Phase4、`club_id` フィルタ）
5. ヘッダーにログイン・マイページリンク
6. この設計では共通テーブルを前提とする。対象環境でマイグレーションが不要かどうかは、実行前にライブスキーマと適用履歴を確認する

## Cookie / セッション

同一ドメインで複数店舗を運ぶ場合は `MEMBER_COOKIE_PREFIX` で衝突回避。  
店舗ごとに別ドメイン（`55810.com` 等）なら `member_session` 固定でも可。

## CTI guest 紐づけ

- 電話番号は CTI `guests` 全体で照合（グループ共通顧客DB）
- 会員アカウントは **店舗ごと**（同番号でも CANDY / 他店で別 `customers_accounts.id`）
- 利用履歴 API は常に `WHERE club_id = MEMBER_CLUB_ID`
