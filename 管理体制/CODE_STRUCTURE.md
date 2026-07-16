# コード・フォルダ構成

- 目的: 作業対象の大枠と正本フォルダを示す
- 状態: 正本
- 更新日: 2026-07-16

## 1. 正本フォルダ

| 種別 | パス | 扱い |
|---|---|---|
| 共有正本 / GitHub作業場 | `\\192.168.1.3\disk1\FSG_SEO\candy` | 管理正本とGitHub `makotonishikubo0418-cmd/candy` の作業場 |
| HP実サイト配下 | `\\192.168.1.3\disk1\FSG_SEO\candy\HP` | PHP/HTML/画像/生成ツール。中身は旧 `HP\HP` から移動済み |
| 退避 | `\\192.168.1.3\disk1\FSG_SEO\candy\除外リスト` | 退避、衝突回避、削除対象候補の保管場所 |
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

- GitHub作業場は `\\192.168.1.3\disk1\FSG_SEO\candy`。
- HP実サイト配下は `\\192.168.1.3\disk1\FSG_SEO\candy\HP`。
- `HP\HP` は作らない。
- `HP` 直下には実サイト配下の中身だけを置く。
- 管理正本は外側 `管理体制/` へ置く。`HP/管理体制/` は作らない。
- パスを省略して報告しない。
- 生成・公開・管理資料は同じものとして扱わない。
