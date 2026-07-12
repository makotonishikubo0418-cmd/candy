# CANDY BLOG PAGE GENERATION SPEC

更新日: 2026-07-12
対象: 鹿児島キャンディのblog詳細ページをCodexが通常運用で新規生成する場合

## 1. 目的と適用範囲

blogページを元テキストから壊さず生成するための正本仕様です。通常の新規ページ生成に使用し、不具合修正、既存機能変更、共通処理変更、リファクタには使用しません。

共通の入力不足・可変構造・停止条件は `CANDY_PAGE_GENERATION_GOVERNANCE.md` を先に適用します。

## 2. 絶対ルール

- 元データは `HP/Text_blog_data` の対象txtを使用する
- HTMLテンプレートは `HP/source/template_kagoshima-deliveryhealth-blog.html` を使用する
- 公開入口PHP、source HTML、ページ別dataset PHP、`dataset_base.php`登録を1セットとする
- HTMLだけを生成して完了としてはいけない
- `create.php`は通常のCodexページ生成では原則使用しない
- 目次、通常scene、女の子紹介、お客様の声、FAQ、まとめを元データの件数に合わせて調整する
- JSON-LD、本文、目次の内容と件数を一致させる

## 3. 全件確認結果

2026-07-12にblog関連の実ファイルを全件確認しました。

| 対象 | 件数 | 備考 |
|---|---:|---|
| `Text_blog_data` txt | 3 | ぽっちゃり、素人、長身 |
| blog source HTML | 6 | 全件placeholderなし |
| blog公開入口PHP | 6 | 全件同じ基本形式 |
| blogページ別dataset PHP | 6 | 1件だけ処理差分あり |
| `dataset_base.php` の現行blog名case | 0 | 6件すべて未登録 |
| `dataset_base.php` の現行blogリンク変換 | 0 | 6件すべて未登録 |

現在のsource HTML:

```text
glamourgirl
petitegirl
poccharigirl
shiroutogirl
slendergirl
tallbeautygirl
```

元テキストが現在残るのは `poccharigirl`、`shiroutogirl`、`tallbeautygirl` の3件です。他3件は完成HTMLを参考資料として使用できますが、元テキストは未確認です。

## 4. 必須ファイル構成

```text
HP/Text_blog_data/<対象記事>.txt
HP/source/template_kagoshima-deliveryhealth-blog.html

HP/kagoshima-deliveryhealth-blog-<slug>.php
HP/source/kagoshima-deliveryhealth-blog-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-blog-<slug>.php
HP/includefile/dataset_base.php
```

slugは元テキストのcanonical、画像名、ファイル名を照合して決定します。

## 5. 元データからHTMLへの対応

| 元データ項目 | HTML反映先 |
|---|---|
| title | title、必要に応じてOGP title |
| description | meta description、OGP description |
| canonical | canonical、OGP URL |
| img_1 | メイン画像、OGP image、alt |
| page_title_h1 | パンくず、h1 |
| subtitle_h1 | `subtitle_h1` |
| description_h1 | `description_h1` |
| img_2 | 記事画像、alt |
| 通常記事 | scene、subtitle、description |
| 店長おすすめ | 女の子紹介ブロック |
| お客様の声 | scene配下の複数項目 |
| FAQ | FAQ複数項目 |
| まとめ | 最終scene、subtitle、description |
| ページ全体 | 目次、JSON-LD 3ブロック |

本文は元データに改行指定がない場合、不要な表示改行を追加しません。

## 6. sceneと目次

完成6ページではscene数は8または9です。固定数ではありません。

基本要素:

1. 通常記事scene群
2. 店長おすすめの女の子
3. お客様の声
4. FAQ
5. まとめ

並び順は固定しません。元データに記載された表示順を優先し、h2を実際の表示順に採番します。既存 `petitegirl` はFAQの後にお客様の声があるため、「お客様の声→FAQ」の固定順を前提にしてはいけません。

ルール:

- h2を上から `scene1` ～ `sceneN` で連番にする
- 通常本文は `subtitle_N`、`description_N` とする
- 目次は全h2を掲載し、`href="#sceneN"` と実際のh2 IDを一致させる
- sceneの増減後は後続番号をすべて振り直す
- お客様の声は、親sceneをSとして `sceneS_1`、`subtitle_S_1`、`description_S_1` の形式にする
- FAQは、親sceneをSとして `subtitle_S_1`、`description_S_1` の形式にする
- 重複ID、欠番、目次だけに存在するsceneを禁止する

### 6.1 blogで同時に同期するもの

blogは1ブロックの増減が複数箇所へ影響します。次を必ず同時に更新します。

- h2のmain scene番号
- お客様の声のscene/subtitle/description子番号
- FAQのsubtitle/description子番号
- 目次の文言・順番・href
- FAQPage JSON-LDの質問・回答・順番・件数
- 女の子紹介ItemListの名前・画像・URL・position・件数
- まとめsceneの番号

完成6ページでは、main scene数は8または9、FAQ数は5～9、お客様の声は4または5、女の子ItemListは5件でした。これらは既存実績であり、新規ページの固定値ではありません。

## 7. 可変ブロック

完成ページではFAQ数が5～9件で変動しています。お客様の声、通常記事、FAQ、目次は元データに合わせて追加・削除します。

女の子紹介ブロックは、テンプレート構造と現行完成ページを比較し、指定された女の子だけを掲載します。名前、画像、`girls.php?no=`、JSON-LD ItemListを一致させます。女の子情報を推測で作成しません。

## 8. JSON-LD

完成blog 6ページはすべてJSON-LDが3ブロックあり、構文解析できました。

主な構造:

- BreadcrumbList
- FAQPage
- 女の子紹介ItemList系

生成時は次を確認します。

- breadcrumbとcanonicalの一致
- FAQ本文とFAQPageの質問・回答・件数の一致
- 女の子紹介本文とItemListの名前・画像・URL・件数の一致
- placeholderが0件
- JSON構文が正常

## 9. 公開入口PHPとdataset PHP

公開入口PHPはareaと同じ基本形で、`dataset_base.php`を読み込みます。

通常のblog dataset PHPは次の処理です。

```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

例外として `dataset_kagoshima-deliveryhealth-blog-slendergirl.php` は `$waku0` の置換がなく、source読込だけです。新規ページでは標準形を使用し、この差分を通常生成へ引き継ぎません。既存例外の修正は開発改修として別に扱います。

## 10. dataset_base.phpへの登録

新規ページでは必ず次を追加します。

```php
case 'kagoshima-deliveryhealth-blog-<slug>.html':
    include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-blog-<slug>.php');
    break;
```

```php
$source = str_replace(
    'kagoshima-deliveryhealth-blog-<slug>.html',
    'kagoshima-deliveryhealth-blog-<slug>.php',
    $source
);
```

現在の `dataset_base.php` には `blog-` を含まない旧名のcaseと旧dataset参照が残っていますが、現行の `kagoshima-deliveryhealth-blog-*` 6ページは登録されていません。未登録でもdefault処理で表示できる可能性はありますが、通常生成では登録省略を許可しません。

## 11. 基本生成アルゴリズム

1. Git状態と対象範囲を確認する
2. 元txtの必須項目、canonical、slug、画像を確認する
3. 同名3ファイルとdataset_base登録の有無を確認する
4. blogテンプレートと完成ページを比較する
5. source HTMLを生成し、SEO、OGP、h1、本文、画像を反映する
6. 通常sceneを元データに合わせて構成する
7. 女の子紹介、お客様の声、FAQを件数に合わせて構成する
8. sceneを上から採番し、目次を同期する
9. JSON-LD 3ブロックを本文に同期する
10. 公開入口PHPとdataset PHPを生成する
11. dataset_baseのcaseとリンク変換を登録する
12. placeholder、重複ID、欠番、目次不一致を検査する
13. canonical、画像、内部リンク、女の子番号を検査する
14. `source/blog.html` の一覧リンク・JSON-LDと `sitemap.xml` への登録要否を確認する
15. PHP構文、JSON構文、差分を確認する
16. ブラウザ未確認の場合は明記する
17. 明示指示がある場合だけCommit・Pushする

## 12. 例外・注意事項

- sceneは8または9であり固定しない
- FAQ、お客様の声、通常記事の件数をテンプレートの初期数に固定しない
- 元テキストがない完成ページは、構造参考に限定する
- `Text_blog_data`内の元テキストは存在しない `Cursor用更新手順.txt` を参照しているため、その参照だけで手順確認済みとしてはいけない
- 現行blog 6ページはdataset_baseの現行名登録がない
- slendergirlのdataset PHPだけ標準形と異なる
- 既存例外の修正は新規ページ生成へ混ぜない
- `glamourgirl` のまとめ見出しには別テーマ名が残る既存不整合があるため、文言の複製元にしない
- FAQとお客様の声の表示順は元データに従い、既存ページの順番を機械的にコピーしない

## 13. 完了チェック

- [ ] 元テキスト、slug、canonicalが確定している
- [ ] 公開PHP、source HTML、dataset PHPが存在する
- [ ] dataset_baseのcaseとリンク変換が存在する
- [ ] placeholderが0件
- [ ] sceneと目次が一致する
- [ ] 元データのh2順と実際のscene順が一致する
- [ ] お客様の声とFAQの採番が正しい
- [ ] お客様の声・FAQ・女の子紹介の本文件数とJSON-LD件数が一致する
- [ ] FAQ本文とFAQPage JSON-LDが一致する
- [ ] 女の子本文とItemList JSON-LDが一致する
- [ ] 画像が実在する
- [ ] canonical、OGP、内部リンクが正しい
- [ ] robotsが公開方針と一致する
- [ ] blog一覧とsitemapへの登録要否を確認した
- [ ] 重複IDがない
- [ ] PHP構文、JSON構文、`git diff --check`を確認した

## 14. 変更していないもの

この調査では、既存blogページ、PHP、dataset PHP、`dataset_base.php`、画像、元テキストを変更していません。
