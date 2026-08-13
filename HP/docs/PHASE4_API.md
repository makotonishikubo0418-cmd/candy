# Phase4 API（お知らせ）

- Purpose: Preserve the Phase 4 member-mypage information implementation contract
- Parent / Owner: [`MEMBER_ARCHITECTURE.md`](MEMBER_ARCHITECTURE.md)
- Scope: Phase 4 API and implementation reference only
- Status / Lifecycle: Implementation Reference / Active
- Source of Truth Responsibility: Phase 4 intended API implementation reference; actual code and verified environment take precedence
- Related Documents: [`CANDY_OTHER_PAGES_MANAGEMENT.md`](../../codex/docs/CANDY_OTHER_PAGES_MANAGEMENT.md) and [`MEMBER_ARCHITECTURE.md`](MEMBER_ARCHITECTURE.md)
- Related Implementation Files: `HP/member/api.php`, `HP/includefile/member/MemberMypageInfo.php`, member UI files, and Phase 4 SQL under `HP/sql/`; external Control implementation is outside this repository
- Verification boundary: Live database state, Control behavior, permissions, deployment, and production behavior are `UNVERIFIED` until checked through the applicable routed operation

Phase1 の認証（セッション Cookie）が必要です。

## 前提

- `sql/004_phase4_member.sql` を Phase1 後に実行
- 任意: `sql/004_seed_candy_info.sql` で初期お知らせ
- 管理は `control_260616/club/information/member_mypage_info.php`（本番: `group/control/club/information/...`）
- メニュー非掲載・URL直アクセスのみ（開発・運用向け）

## 公開条件

`customers_mypage_info` で以下をすべて満たすもののみ会員に表示:

- `status = 1`
- `publish_from` が NULL または現在以前
- `publish_to` が NULL または現在以降

## fno=401 お知らせ一覧

**POST** `member/api.php?fno=401`

```json
{ "page": 1, "per_page": 20 }
```

| キー | 説明 |
|------|------|
| items | 一覧（title, excerpt, is_read 等） |
| total | 件数 |
| unread_count | 未読数 |

## fno=402 お知らせ詳細

```json
{ "info_id": 1 }
```

- 本文 `body` を返す
- **閲覧時に自動既読**（`customers_mypage_info_read` に記録）

## fno=403 未読件数

未読件数のみ返します（バッジ表示用）。

## 管理画面（開発者向け・URL直アクセス）

`group/control/club/information/member_mypage_info.php?club=2`

- サイドメニュー・店舗マスタメニューには未掲載
- マイページインフォメーション（`shop_mypage.php`）と同じメニュー権限 `MENUCODE=301001`

## 注意

- 本文はプレーンテキスト表示（HTML 非対応）
- お知らせを更新しても既読は維持（再通知したい場合は新規レコード推奨）
