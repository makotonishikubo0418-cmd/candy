# HP/AGENTS.md

## 1. 適用範囲

このファイルは `HP/` 配下の追加ルールである。必ずリポジトリ直下 `AGENTS.md` と併用する。矛盾する場合は勝手に優先順位を決めず停止する。

詳細仕様、調査結果、変動する件数はここへ複製しない。`HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` から作業に対応する正本資料を選ぶ。

## 2. HP 作業開始時の必須確認

1. root `AGENTS.md` とこのファイルを読む。
2. 作業別正本資料を選ぶ。
3. 対象ページの公開 PHP、source HTML、dataset PHP、共通処理、CSS/JS/画像、リンク元を確認する。
4. 既存変更と今回の対象が重なるか確認する。
5. 本番に関係する場合は本番移行資料、workflow、deploy script の現物を確認する。

一ファイルだけを見て HP 全体の仕様を断定しない。

## 3. HP の生成構造

確認済みの基本経路:

```text
HP 直下の公開入口 PHP
  → HP/includefile/dataset_base.php
  → HP/source/対応する HTML
  → HP/includefile/dataset_*.php
  → HP/includefile/class.hpgcoder2.php
  → 公開 HTML 出力
```

ページごとに例外がある。DB、静的 HTML、置換トークン、外部連携のどれが値を供給するかを確認する。

## 4. 通常ページ生成の絶対ルール

### 4.1 元データとテンプレート

- `HP/Text_area_data` → area
- `HP/Text_blog_data` → blog
- `HP/Text_hotel_data` → hotel
- `template_*.html` はテンプレート
- 対応関係は area → area、blog → blog、hotel → hotel

`HP/create.php` は旧来の Web 生成手段であり、通常の Codex 制作では使用しない。使用・変更は事前承認を必要とする。

### 4.2 一ページの完了範囲

通常ページ生成では、必要なものを全て一つの制作範囲として扱う。

1. 元 Text データ
2. 同カテゴリの完成例と例外
3. 対応 template
4. `HP/source/*.html`
5. `HP/includefile/dataset_*.php`
6. HP 直下の公開 PHP
7. 共通 dataset への登録・変換経路
8. カテゴリ一覧、index、関連ページ、内部リンク
9. sitemap 反映要否
10. 画像の存在、配置、参照
11. 構文、リンク、画像、PC/SP、ブラウザの必要な検証

HTML だけ、PHP だけ、dataset だけを作って「ページ完成」と報告しない。

### 4.3 通常制作と開発改修を分ける

- 通常制作: 既存の生成経路と完成例に従って新しいページ一式を作る。
- 開発改修: 共通処理、generator、CSS/JS、DB、既存機能を変更する。

開発改修では通常制作の手順を機械的に当てはめず、影響範囲と承認対象を先に確認する。

### 4.4 カテゴリ別ルール

- area: 完成済みの全パターンを比較し、基本形と例外を判断する。105ページ制作の運用は area runbook を正本とする。
- hotel: 情報量によりページ内容が変わる。存在しない情報の見出し、空欄、仮文を機械的に出さない。
- blog: 複数の規則と例外がある。単一の完成例だけで仕様を決めない。
- 固定のファイル数上限は設けない。必要なら 100 ファイル以上を一つの明示された範囲として扱う。

### 4.5 画像

- area の準備画像は `画像データ/area_img` を確認する。
- 使用済み画像と今後使用する画像を、参照元と実ファイルで判定する。
- 新規ページ用画像がない場合、無断生成、無断流用、仮画像追加をしない。
- 既存の画像なし表示規則を確認し、判断が必要なら制作前に報告する。
- ページ本体だけでなく、一覧画像、関連リンク、画像参照先も検査する。

## 5. 本番とテストの絶対条件

| 用途 | サーバーパス |
|---|---|
| 本番 | `/public_html/group/candy/` |
| テスト | `/public_html/group_test/candy/` |

- 移行中、本番 `index.php` はシティヘブンへの転送を維持する。
- 最新 `HP/index.php` の本番反映は最終公開切替であり、ユーザーの明示指示を必要とする。
- `index.php` 以外を先に更新できるが、それぞれのアップロードと HTTP/表示確認は別に行う。
- deploy対象を含む `main` Pushは、安全検査後に本番反映を自動実行する。
- 「アップしろ」は関連 `.md` の整合、Commit、Push、自動本番Actions、本番URL確認までの一括指示であり、途中で追加承認を求めない。
- Actionsは対象SHA、対象件数、`PLAN_TOKEN`、上限を自動生成・照合し、不一致ならFTP接続前に停止する。
- 一回の本番deployは最大25ファイルとし、超える場合は小バッチへ分割する。
- GitHub Actions の除外を推測しない。workflow と deploy script の実物で確認する。
- サーバーだけにあるファイルを一括削除しない。
- 一時・backup ファイルは作成規則、削除規則、途中失敗時の残存確認を持たせる。
- 本番作業は preview、対象一覧、少数試行、実測速度、進捗、終了コードを確認する。
- Actionsの起動・監視はGitHub APIを通常経路とし、ブラウザUI操作を前提にしない。

本番の詳細は `HP/codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` を正本とする。

## 6. 変更ゲート

### 6.1 変更前にユーザー承認が必要

- `HP/create.php`
- `HP/log/`
- root/HP の `.well-known/`
- root/HP の `.htaccess`
- `HP/movie/` の削除・置換
- noindex/index 設定
- 認証、DB、決済、本番設定
- `HP/index.php` の本番反映

### 6.2 影響範囲を示してから変更

- `HP/includefile/dataset_base.php`
- `HP/includefile/class.hpgcoder2.php`
- `HP/includefile/funcs.php`
- `HP/includefile/dataset_*.php`
- `HP/source/system.html`
- `HP/css/default.css`
- `HP/js/common.js`
- `HP/makeSitemap.php`
- `HP/sitemap.xml`

対象ページ制作で dataset 等の変更が必要な場合も、関連ページへの影響を先に示す。

## 7. 作業別ルーター

| 作業 | 読む資料 |
|---|---|
| 今日の全経緯・事故・改善 | `HP/codex/docs/CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md` |
| 通常ページ生成 | `HP/codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` |
| area 105ページ・画像・スタッフ運用 | `HP/codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` |
| blog 仕様 | master index が指定する blog 正本 |
| hotel 仕様 | master index が指定する hotel 正本 |
| 構造・ファイル役割 | `CANDY_HP_STRUCTURE_MAP.md`、`CANDY_FOLDER_ROLE_MAP.md`、`CANDY_CODE_FILE_STRUCTURE.md` |
| 全件・リンク・画像検査 | `HP/codex/docs/CANDY_VERIFICATION_PLAN.md` |
| 本番移行 | `HP/codex/docs/CANDY_PRODUCTION_MIGRATION_MASTER.md` |
| 通常運用 | `HP/codex/docs/CANDY_OPERATION_BASICS.md` |

資料名、追加の正本、読む順番は `CANDY_MASTER_DOC_INDEX.md` を優先する。

## 8. HP の完了判定

作業に応じ、次を分けて報告する。

- ファイル作成・修正
- PHP/HTML/JavaScript 構文
- 置換トークンと dataset 対応
- 内部リンクと参照画像
- PC 表示
- SP 表示
- JavaScript console
- Commit
- Push
- Actions
- 本番ファイル
- HTTP 応答
- ブラウザ表示

実行していない項目は未確認・未実施と書く。「ページ完成」は、今回合意した完了範囲を全て満たした場合だけ使用する。

Push、Actions、本番反映、ページ公開を報告する場合は、対応する確認用URLを同じ報告内に記載する。PushはCommit URL、ActionsはRun URL、本番反映・ページ公開は対象ページごとの本番URLを使う。複数ページを代表URL一件で済ませない。実在確認していないURLは推測で記載せず、未取得と報告する。
