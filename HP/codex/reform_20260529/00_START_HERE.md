# FSG改修案ベース CANDY SEO 改修準備

作成日: 2026-06-09
対象サイト: `H:\Data\01_CTI\candy_HP`
管理資料: `H:\Data\01_CTI\candy_HP\codex\reform_20260529`
参照元改修案: `H:\Data\01_CTI\FSG企画 - 改修案_20260529.md`

## 結論

現時点ではHP本体を修正せず、FSG改修案をCANDY SEOサイト改修に使える形へ分解・整理する段階。

FSG改修案のCANDYサイト側への主要影響は、キャンディマイページ、AI受付連携、女の子・出勤・料金・ホテル・エリア・問い合わせ経路などの共通データ整理、スマホ表示、公開ページ導線である。

## 今回作成した資料

| ファイル | 役割 |
|---|---|
| `00_START_HERE.md` | この改修準備資料の入口 |
| `01_FSG_SCOPE_SUMMARY.md` | FSG改修案からCANDYに関係する範囲を抽出 |
| `02_SITE_CURRENT_MAP.md` | CANDYサイトの現在構成を整理 |
| `03_REFORM_TO_SITE_IMPACT_MAP.md` | 改修テーマと影響ファイルの対応表 |
| `04_PRE_EDIT_BACKLOG.md` | 本体修正前に行う確認タスク |
| `05_OPEN_ITEMS_AND_DECISIONS.md` | 未決事項・判断待ち事項 |

## 厳守ルール

| ルール | 内容 |
|---|---|
| HP本体修正禁止 | `candy_HP` 直下の公開PHP、`source`、`includefile`、`css`、`js`、画像、設定ファイルは触らない |
| 管理資料のみ可 | `H:\Data\01_CTI\candy_HP\codex` 配下のみ作成・更新可 |
| 事実と推測を分離 | 確認済み、未確認、推測、提案を混在させない |
| FSG改修案は上位仕様扱い | そのままCANDY公開サイト改修仕様とはみなさない |
| 実装前に影響範囲確認 | 1ページ修正でも、共通ヘッダー・フッター・dataset・DB・SEOへの波及を確認する |

## 確認済みの前提

| 項目 | 確認結果 |
|---|---|
| CANDYサイト本体 | `H:\Data\01_CTI\candy_HP` が存在 |
| CANDY管理用データ | `H:\Data\01_CTI\candy_HP\codex` が存在 |
| 最新候補のFSG改修案 | `FSG企画 - 改修案_20260529.md` が 20260527 / 20260528 より新しい日付ファイルとして存在 |
| CANDY既存Codex資料 | `codex\docs` と `codex\area` が存在 |
| 今回のHP本体変更 | 未実施 |

## この資料の使い方

次の順で読む。

1. `01_FSG_SCOPE_SUMMARY.md` で、FSG改修案のどこがCANDYに関係するか確認する。
2. `02_SITE_CURRENT_MAP.md` で、現在のCANDYサイトの構成とファイル群を確認する。
3. `03_REFORM_TO_SITE_IMPACT_MAP.md` で、改修テーマごとの影響ファイルを確認する。
4. `04_PRE_EDIT_BACKLOG.md` のタスクを上から潰す。
5. 実装判断が必要なものは `05_OPEN_ITEMS_AND_DECISIONS.md` に追記してから進める。

## 現時点の判断

FSG改修案に基づいてCANDY SEO側の改修準備は進められる。

ただし、FSG改修案は業務システム・マイページ・AI受付まで含む広い仕様であり、公開HPの文言・SEO・画面デザインをそのまま決める原稿ではない。

そのため、今すぐHP本体を編集するのではなく、以下を先に固める必要がある。

1. CANDY公開サイトとして改修する範囲
2. CANDYマイページとして新規・既存どちらを扱うか
3. AI受付連携を公開HP側に出す範囲
4. CTI/DB側のデータを公開HPに反映する範囲
5. noindex解除やsitemap更新を行うタイミング

