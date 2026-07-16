# コード・フォルダ構成

- 目的: 作業対象の大枠と正本フォルダを示す
- 状態: 正本
- 更新日: 2026-07-16

## 1. 正本フォルダ

| 種別 | パス | 扱い |
|---|---|---|
| 共有正本 | `\\192.168.1.3\disk1\FSG_SEO\candy` | ユーザー指定の正フォルダ。素材、退避、外側入口を含む |
| Git作業場 | `\\192.168.1.3\disk1\FSG_SEO\candy\HP` | GitHub管理対象。管理文書の正本もここに置く |
| HP実サイト配下 | `\\192.168.1.3\disk1\FSG_SEO\candy\HP\HP` | PHP/HTML/画像/生成ツール |
| 退避 | `\\192.168.1.3\disk1\FSG_SEO\candy\除外リスト` | Git作業場外。ここへ移したGit管理ファイルは削除表示になる |
| 旧ローカル | `C:\Codex\candy` | 明示指示なしに作業場へ使わない |

## 2. 主な領域

| 領域 | 内容 | 入口 |
|---|---|---|
| 管理体制 | AGENTS、README、予約、履歴、現在地 | `AGENTS.md`, `README.md`, `管理体制/` |
| HP公開ファイル | PHP、source HTML、dataset、画像 | `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` |
| HP生成ツール | area/blog/hotel生成スクリプト | `HP/codex/scripts/` |
| HP管理資料 | runbook、仕様、インベントリ | `HP/codex/docs/` |
| 入力txt | area/hotel/blogの元データ | `HP/Text_*_data/` |
| 作成用画像素材 | ページ作成時に使う画像 | `HP/imgHtml/new_202601/` ほか実対象を確認 |

## 3. 注意

- `HP` がGit作業場で、その中の `HP` が実サイト配下。
- `\\192.168.1.3\disk1\FSG_SEO\candy` 直下の `.git` は作業前に機能確認が必要。現状の実作業は `candy\HP` 側で確認済み。
- 外側入口とGit管理正本を混同しない。
- パスを省略して報告しない。
- 生成・公開・管理資料は同じものとして扱わない。