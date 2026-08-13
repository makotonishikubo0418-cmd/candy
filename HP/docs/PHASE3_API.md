# Phase3 API（お気に入り）

- Purpose: Preserve the Phase 3 member-favorite implementation contract
- Parent / Owner: [`MEMBER_ARCHITECTURE.md`](MEMBER_ARCHITECTURE.md)
- Scope: Phase 3 API and implementation reference only
- Status / Lifecycle: Implementation Reference / Active
- Source of Truth Responsibility: Phase 3 intended API implementation reference; actual code and verified environment take precedence
- Related Documents: [`CANDY_OTHER_PAGES_MANAGEMENT.md`](../../codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md) and [`MEMBER_ARCHITECTURE.md`](MEMBER_ARCHITECTURE.md)
- Related Implementation Files: `HP/member/api.php`, `HP/includefile/member/MemberFavorite.php`, `HP/includefile/member/MemberGirlCard.php`, `HP/js/member_favorite.js`, and Phase 3 SQL under `HP/sql/`
- Verification boundary: Live database state, Cookie-to-account behavior, deployment, and production behavior are `UNVERIFIED` until checked through the applicable routed operation

Phase1 の認証（セッション Cookie）が必要です。

## 前提

- `sql/003_phase3_member.sql` を Phase1 テーブル投入後に実行
- 掲載中（`girls_data.status=1`）の女の子のみ追加可
- 既存の Cookie お気に入り（`candyfav`）とは別管理。会員ログイン時は女の子プロフィールで DB 連携

## fno=301 お気に入り一覧

**POST** `member/api.php?fno=301`

```json
{ "page": 1, "per_page": 20 }
```

**items 各要素**

| キー | 説明 |
|------|------|
| girls_id | girls_data.id |
| no, name, age | 表示用 |
| name_kana, name_romaji | カナ・英字名 |
| height, bust, cup, waist, hip | サイズ各項目 |
| size_display | `AGE23 T165 B85-C W58 H86` 形式 |
| newface, enrollment_status | 在籍ステータス（在籍 / 新人 / 体験入店 / 掲載終了） |
| schedule_code | `working` / `tel_check` / `closed_today` / `no_schedule` |
| schedule_label | 本日出勤表示（時間帯・TEL確認・案内終了・CLOSED TODAY） |
| schedule_time | 本日の時間帯（出勤中のみ） |
| schedule_next | 次回出勤 `{ date, time, label }`（本日休み・終了時） |
| active | 掲載中か |
| profile_url | `girls.php?no=...`（掲載中のみ） |
| image_url | サムネイル（掲載中のみ） |
| created_at | 登録日時 |

掲載終了の女の子も一覧に残ります（リンクなし）。

## fno=302 お気に入り追加

```json
{ "girls_id": 1234 }
```

## fno=303 お気に入り解除

```json
{ "girls_id": 1234 }
```

## fno=304 お気に入り ID 一覧

ログイン中会員の `girls_id` 配列を返します（女の子プロフィール UI 用）。

## 画面

- **マイページ** … お気に入り女の子管理（出勤・サイズ・カナ等の詳細一覧・解除）、利用履歴からの追加
- **girls.php** … ログイン中会員は `member_favorite.js` が Cookie 操作を API に差し替え

## 注意

- 未ログイン時は従来どおり Cookie `candyfav` が動作
- ログイン後に Cookie お気に入りを DB へ自動マージは **未実装**（必要なら別途対応）
