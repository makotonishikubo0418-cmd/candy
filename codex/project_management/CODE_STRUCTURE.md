# コード・フォルダ構成

- 目的: 作業対象の大枠、各フォルダの役割、現行正本を示す
- 状態: 正本
- 更新日: 2026-07-17

## 1. 正本と作業場所

| 種別 | パス | 扱い |
|---|---|---|
| ローカルGit作業場 | `C:\Codex\candy` | GitHubへ同期する唯一の作業用リポジトリルート |
| GitHub同期ハブ | `makotonishikubo0418-cmd/candy` | Codex間でCommitを共有する。Pushは明示指示時だけ行う |
| 共通ルール入口 | `C:\Codex\candy\AGENTS.md` | 全作業で最初に読む短い入口 |
| Codex管理正本 | `C:\Codex\candy\codex` | README、管理文書、HP仕様、スクリプトを置く |
| プロジェクト管理 | `C:\Codex\candy\codex\project_management` | ルール、現在地、予約、履歴、安全手順 |
| HP実サイト配下 | `C:\Codex\candy\HP` | 公開PHP、source、includefile、画像、log、movie |
| 制作入力 | ルート直下の `Text_area_data`、`Text_blog_data`、`Text_hotel_data` | ページ作成用の非公開元データ |
| NAS保管 | `\\192.168.1.3\disk1\FSG_SEO\candy` | `Backup/`・受入素材の保管専用。Git操作は禁止 |

## 2. 主な領域

| 領域 | 内容 | 入口 |
|---|---|---|
| 管理入口 | 正本一覧と読む順番 | `codex/README.md` |
| プロジェクト管理 | 文書ルール、状態、連絡、Task、安全 | `codex/project_management/` |
| HP制作仕様 | area・hotel・blogのrunbookと生成仕様 | `codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| HP生成ツール | area・hotel・blogの生成・検証・公開スクリプト | `codex/scripts/` |
| HP公開ファイル | PHP、source、dataset、画像、log、movie | `HP/` |
| area入力 | 地域別txtと分類結果 | `Text_area_data/` |
| area受入画像 | areaページ作成前のGit管理外素材 | NASの `Text_area_data/画像データ/` |
| blog入力 | 記事txt | `Text_blog_data/` |
| hotel入力 | ホテルtxtと分類結果 | `Text_hotel_data/` |
| バックアップ・受入素材 | 旧データ、除外済みデータ、過去資料、Git管理外の受入素材 | NASの `Backup/`・受入素材置場 |

## 3. 現在の注意

- `HP/` は実サイトデータ専用とし、Codex管理資料や制作入力を置かない。
- 管理正本は `codex/`、プロジェクト管理文書は `codex/project_management/` に一本化する。
- `Text_*_data/` はHPへ直接公開するデータではない。
- 作業開始時は `git fetch origin` と `git status --short --branch` を実行し、behindなら先にpullする。
- NASは `Backup/`・受入素材の保管専用であり、Git操作を行わない。
- NASの `Backup/` は参照用であり、現行仕様の根拠にしない。
- `codex/scripts/` は移動済みだが内部パス移行が未完了のため、修正・検証完了まで実行停止とする。
- `HP/HP/` を作らない。
- 生成、公開、入力、管理資料、バックアップを同じ役割として扱わない。
