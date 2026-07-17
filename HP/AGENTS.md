# HP/AGENTS.md

## 1. 適用

- `HP/` 配下はroot `AGENTS.md` とこのファイルを適用する。
- 通常area制作は追加で `codex/docs/CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md` だけを読む。
- 通常hotel制作は追加で `codex/docs/CANDY_HOTEL_STAFF_PRODUCTION_RUNBOOK.md` だけを読む。
- 通常blog制作は追加で `codex/docs/CANDY_PAGE_GENERATION_GOVERNANCE.md` と `codex/docs/CANDY_BLOG_PAGE_GENERATION_SPEC.md` だけを読む。
- 未知例外や別作業だけ `codex/docs/CANDY_MASTER_DOC_INDEX.md` を使う。

## 2. HP構造

```text
公開PHP
  → includefile/dataset_base.php
  → source/対応HTML
  → includefile/dataset_*.php
  → includefile/class.hpgcoder2.php
```

ページごとの例外は実ファイルで確認する。

## 3. 通常area・hotel・blog制作（現在は実行停止）

`codex/scripts/` への移動後も、スクリプト内部に旧階層を前提とするパス計算と入力制限が残っている。内部修正とdry-run検証が完了するまで、下記コマンドは実行しない。

移行完了後の標準入口:

```powershell
codex\scripts\candy-area.cmd publish-next
```

area対象指定:

```powershell
codex\scripts\candy-area.cmd publish --input "Text_area_data/対象.txt"
```

area本番操作なし:

```powershell
codex\scripts\candy-area.cmd build --input "Text_area_data/対象.txt"
codex\scripts\candy-area.cmd check --input "Text_area_data/対象.txt"
```

通常hotel制作:

```powershell
codex\scripts\candy-hotel.cmd publish --input "Text_hotel_data/対象ホテル.txt"
```

未公開の完全なhotel入力を自動選択する場合:

```powershell
codex\scripts\candy-hotel.cmd publish-next
```

通常blog制作:

```powershell
codex\scripts\candy-blog.cmd publish --input "Text_blog_data/対象記事.txt"
codex\scripts\candy-blog.cmd build --input "Text_blog_data/対象記事.txt"
codex\scripts\candy-blog.cmd check --input "Text_blog_data/対象記事.txt"
```

移行完了後、ツールは生成、検証、対象限定stage、1 Commit、1 Push、Actions、本番HTTP、URL出力を行う。公開後の資料専用Commit・Pushは行わない。

## 4. 制作ルール

- areaは `Text_area_data` と `source/template_kagoshima-deliveryhealth-area.html` を使う。
- blog、hotelも同カテゴリのTextとtemplateを対応させる。
- `create.php` は通常制作に使用しない。
- Textの店舗、移動時間、交通費を最優先する。
- 未指定時だけ、店舗組合せ頻度、地図座標、近隣完成ページを使用する。
- 関連記事は実リンク設定まで予約ダミー8件を残す。
- 各項目の件数は原則固定しない。店舗、通常記事scene、FAQ、基本情報の任意行、料金行、アクセス、周辺スポットは入力の完成ブロック数へ合わせる。
- 通常記事scene、FAQ、料金、アクセス、周辺スポットは0件を許容し、空セクションを生成しない。hotelの店舗は1件以上を必要とする。
- hotelの旧optionは入力に3項目がそろう場合だけ独立表示し、通常sceneへ混ぜない。
- 固定数の例外は明示仕様だけとし、関連記事ダミー8件を維持する。
- 元データにない値、画像、URLを推測しない。

通常areaの変更単位:

- 公開PHP
- source HTML
- dataset PHP
- dataset_base登録
- area一覧
- sitemap
- 制作キュー1行

通常hotelの変更単位:

- 公開PHP
- source HTML
- dataset PHP
- dataset_base登録
- hotel一覧
- sitemap

通常blogの変更単位:

- 公開PHP
- source HTML
- dataset PHP
- dataset_base登録
- blog一覧
- indexのblog欄
- sitemap

検証は専用ツールを正本とし、同じ検査を手作業で重ねない。

## 5. 公開安全条件

- Commit前にstage対象を許可表と照合する。
- 削除、rename、copy、type変更、許可外ファイルがあれば停止する。
- ActionsはFTP前に対象PHPをlintする。
- 複数ファイル反映の途中失敗は、同じ実行の反映済み対象をrollbackする。
- 一回の本番deployは最大25ファイル。
- 本番確認は対象ページ、必要画像、対象カテゴリ一覧、sitemap、転送をHTTPで行う。
- Actions状態確認はAPIを使い、通常経路でブラウザUIを操作しない。

## 6. 変更ゲート

事前承認が必要:

- `create.php`、`log/`、`.well-known/`、`.htaccess`
- `movie/` の削除・置換
- noindex/index、認証、DB、決済、本番設定
- `index.php` の本番反映

影響範囲を示してから変更:

- `includefile/dataset_base.php`
- `includefile/class.hpgcoder2.php`
- `includefile/funcs.php`
- `includefile/dataset_*.php`
- `source/system.html`
- `css/default.css`、`js/common.js`
- `makeSitemap.php`、`sitemap.xml`

通常area・hotel・blog一括ツールが対象限定で扱う `dataset_base.php`、カテゴリ一覧、indexの対象カテゴリ欄、sitemapは、実行指示の範囲内とする。

## 7. STOP

- main以外、remote不一致、fast-forward不可、競合
- 入力不足、画像不足、slug不一致、既存ファイル競合
- 共有登録重複、旧slug・誤記の自動置換が必要
- JSON、PHP、stage許可表、Actions、本番HTTP検証失敗
- root `AGENTS.md` のSTOP条件
