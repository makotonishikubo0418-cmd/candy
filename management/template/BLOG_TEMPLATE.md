# ブログテンプレート作成管理

Updated: 2026-10-28

親管理書: [INDEX.md](../INDEX.md)
ページ仕様: [CANDY_BLOG_PAGE_GENERATION_SPEC.md](../specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md)

## 1. 目的と責任

URLとタイトルを指定した「ブログのテンプレを作成して」という依頼から、ユーザーが後で記事HTMLを作成・差し替えできるローカル一式を準備する。本書は作成コードの正本と実行手順を管理する。作成ツールは本書のコードブロックを直接読み込む。

完成原稿から記事を作る経路は [BLOG_CREATION.md](../specs/BLOG_CREATION.md)。従来の [編集用スタッフブログテンプレート](../../HP/source/template_kagoshima-deliveryhealth-blog.html) は完成HTMLの部品・デザイン参考として維持する。本書の経路とは入力条件が異なる。

## 2. 入力と出力

必須入力は次の2項目のみ。

- URL: `https://www.55810.com/kagoshima-deliveryhealth-blog-<slug>.php`
- タイトル: ユーザー指定の記事タイトル

slugは英小文字・数字・ハイフン・アンダースコアを許可する。クエリ、フラグメント、別ホスト、別カテゴリ、パス階層は使用しない。URLからファイル名を決定し、タイトルをtitle・H1・OGP・パンくず・Article・リンク表示名へ反映する。

| 対象 | 処理 |
|---|---|
| `HP/kagoshima-deliveryhealth-blog-<slug>.php` | 公開入口PHPを新規作成 |
| `HP/source/kagoshima-deliveryhealth-blog-<slug>.html` | 本書のHTMLコードから新規作成 |
| `HP/includefile/dataset_kagoshima-deliveryhealth-blog-<slug>.php` | 記事用datasetを新規作成 |
| `HP/includefile/dataset_base.php` | case、include、HTML→PHP変換を各1件追加 |
| `HP/source/blog.html` | 一覧末尾にURL・タイトルを1件登録 |
| `HP/source/index.html` | ブログ一覧の末尾15件に同期 |
| `HP/sitemap.xml` | 新URLを1件追加し、変更した記事・一覧・TOPのlastmodを更新 |

一覧・TOP・サイトマップの関係は [共通生成規則第10.1節](../specs/CANDY_PAGE_GENERATION_GOVERNANCE.md#101-integration-into-public-routes)、更新日は [SEO仕様第11章](../specs/CANDY_SEO_SPEC.md#11-sitemap-legacy-urls-and-duplicates) に従う。登録コードは [candy_page_common.py](../scripts/candy_page_common.py)、統合処理は [candy_blog_template.py](../scripts/candy_blog_template.py)。

## 3. 本文未作成の扱い

完了は `SCAFFOLD_OK`（ローカル一式準備済み）であり、記事完成・本番公開ではない。原稿、記事画像、著者、FAQ、女の子情報を推測しない。本文には準備中表示と `CANDY_BLOG_BODY_PENDING` を置く。

- title・H1・canonicalなど確定できる項目は作成時に反映する。
- descriptionとog:descriptionは指定タイトルを暫定使用し、完成HTMLで本文に合う説明へ差し替える。
- 記事画像は作らない。初期OGPは既存のサイト共通画像 `imgHtml/new_202601/sample.jpg`。記事画像・alt・Article.imageは完成内容に合わせる。
- 初期JSON-LDはタイトル・URLのArticleと、表示に一致するBreadcrumbList。本文にないFAQ・人物・著者・公開日は出力しない。
- `robots=index`、一覧・サイトマップ登録は公開時の構成をローカルで準備するもの。準備中HTMLを完成記事として公開しない。
- 同一URLのファイル・登録が1つでもある場合は停止する。再実行で編集済みHTMLを上書きしない。

## 4. 実行手順

1. プロジェクト指示とINDEXから本書へ到達し、URL・タイトルを確定する。
2. 同一URLの衝突、既存記事のPHP・HTML・dataset、一覧・TOP・サイトマップの整合と参照資産を確認する。
3. 次のdry-runで作成予定7ファイルとPHP構文を検証する。引数を実際の指定へ置き換える。

```powershell
management\scripts\candy-blog-template.cmd create --url "https://www.55810.com/kagoshima-deliveryhealth-blog-<slug>.php" --title "<タイトル>" --dry-run
```

4. 成功した場合、同じコマンドから `--dry-run` を外して実行する。書込後の検証失敗時は、この実行による変更を元のバイトへ戻す。他の作業による変更は保持する。
5. 作成後は以下で一式の整合を確認する。

```powershell
management\scripts\candy-blog-template.cmd check --url "https://www.55810.com/kagoshima-deliveryhealth-blog-<slug>.php" --title "<タイトル>"
```

6. HP変更後は [既存サイト運用第6章](../specs/CANDY_OPERATION_BASICS.md#6-after-a-change) に従い、サイトマップ更新日・生成管理書を同期して検証する。
7. 作成ファイル、差し替えるHTMLの場所、本文未作成、本番反映の有無を報告し、[HISTORY.md](../HISTORY.md) に従い記録する。

既存不整合、slug衝突、PHP CLI不足、資産不足は書込前に停止する。既存不整合を別の記事作成のついでに修正しない。Git・DB・本番の権限は [AGENTS.md](../../AGENTS.md) と担当管理書に従う。

## 5. 完成HTMLの差し替えとJSON-LD

編集対象は `HP/source/kagoshima-deliveryhealth-blog-<slug>.html`。公開URLは対応するPHPであり、source HTMLの直接URLではない。

`CANDY_BLOG_BODY_START` から `CANDY_BLOG_BODY_END` の間に本文・画像・目次・必要なFAQなどを設置し、準備中表示と `CANDY_BLOG_BODY_PENDING` を除去する。HTML全体を差し替える場合も、URL・タイトル、共通CSS/JS、ヘッダー・フッター、JSON-LDを保持・同期する。記事別CSSは作らず [ブログCSS仕様](../specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md#22-blog-css-ownership) に従う。

| 表示内容 | JSON-LDの処理 |
|---|---|
| 記事・画像・著者・日付 | Articleの対応項目を実際の内容から更新。未確定値は省略 |
| パンくず | BreadcrumbListの名称・URL・順序を一致させる |
| FAQあり | FAQPageを追加し、質問・回答・順序・件数を一致させる |
| FAQなし | FAQPageを出力しない |
| 女の子紹介あり | [女の子情報管理](../specs/CANDY_GIRL_INFORMATION_MANAGEMENT.md) に従いPUBLIC情報のItemListを追加 |
| 女の子紹介なし | 人物ItemListを出力しない |

現行ブログ一覧JSON-LDはBreadcrumbListとWebPageで、記事配列を持たないため、新規記事ごとのJSON追加は不要。将来ItemListやBlog.blogPostを導入した場合は、作成処理も更新して可視一覧と一致させる。未対応の構造は停止する。

完成後の本文・目次・画像・JSON-LD・関連リンク・PC/SP表示・本番確認は [ブログ仕様](../specs/CANDY_BLOG_PAGE_GENERATION_SPEC.md) と [作成手順](../specs/BLOG_CREATION.md) に従う。`check` は配線・メタ情報・構文の確認であり、文章の意味や公開適否を判定しない。

初回公開では上表7ファイルと必要な資産を反映する。PHP等が未反映ならHTMLだけでは公開URLは成立しない。PHP等が反映済みなら同名HTMLを差し替える。[本番移行管理](../specs/CANDY_PRODUCTION_MIGRATION_MASTER.md) に従う。

## 6. 作成コード

`code:` マーカーはツールが使用する。HTML用値はHTMLエスケープ、JSON用値はJSON文字列化を行う。`BLOG_CSS_VERSION` は実ファイルのSHA-256先頭8文字、`RELATED_HTML` は有効なブログ3件・エリア3件から生成する。

### 6.1 HTML全文

<!-- code:source-html -->
```html
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="index">
	<meta name="description" content="{{TITLE_HTML}}">
	<title>{{TITLE_HTML}}</title>
	<link href="./source/style.css?v=85d0c90" rel="stylesheet" type="text/css">
	<link href="./css/default.css" rel="stylesheet" type="text/css">
	<link href="./css/blog.css?v={{BLOG_CSS_VERSION}}" rel="stylesheet" type="text/css">
	<link rel="canonical" href="{{URL_HTML}}">
	<link rel="icon" href="./favicon.ico" type="image/x-icon">
	<meta property="og:title" content="{{TITLE_HTML}}">
	<meta property="og:type" content="article">
	<meta property="og:url" content="{{URL_HTML}}">
	<meta property="og:image" content="https://www.55810.com/imgHtml/new_202601/sample.jpg">
	<meta property="og:description" content="{{TITLE_HTML}}">
	<meta property="og:site_name" content="鹿児島 デリヘル キャンディ">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" defer></script>
	<script type="text/javascript" src="./js/amadare_webapp2.4.php" defer></script>
	<script type="text/javascript" src="./js/common.js?v=4ea93bb" defer></script>
<!-- Google tag (gtag.js) START -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0VBTBPHDD2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-0VBTBPHDD2');
</script>
<!-- Google tag (gtag.js) END -->
<!-- 構造化データ（JSON-LD） START -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Article","headline":{{TITLE_JSON}},"url":{{URL_JSON}},"mainEntityOfPage":{"@type":"WebPage","@id":{{URL_JSON}}},"inLanguage":"ja"}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[
{"@type":"ListItem","position":1,"name":"TOP","item":"https://www.55810.com/"},
{"@type":"ListItem","position":2,"name":"ブログ一覧","item":"https://www.55810.com/blog.php"},
{"@type":"ListItem","position":3,"name":{{TITLE_JSON}},"item":{{URL_JSON}}}]}
</script>
<!-- 構造化データ（JSON-LD） END -->
</head>
<body>
<div id="loading" aria-label="読み込み中"></div>
<div id="index">
<div class="wrap">
<!-- メニュー START -->
		<div class="header">
			<a href="tel:0992266956" onClick="amadareAcRec('0992266956');" class="tel spOnly"><div class="cssSprite headTel vCenter"></div></a>
			<a href="./" class="cssSprite headLogo" aria-label="鹿児島デリヘルキャンディトップページへ"></a>
			<div class="headNavi pcOnly" id="headNavi">
				<div class="cssSprite headLogo2">鹿児島 デリヘル キャンディ メインメニュー</div>
			</div>
			<div class="menuBtn spOnly" id="menuOpen" onclick="WAtoggle('-menuOpen,+menuClose,+headMenu');">
				<div class="cssSprite headMenu vCenter"></div>
			</div>
			<div class="menuBtn spOnly is-hidden" id="menuClose" onclick="WAtoggle('-menuClose,-headMenu,+menuOpen');">
				<div class="cssSprite headMenu vCenter"></div>
			</div>
			<ul class="menu pcOnly">
				<li><a href="./" title="鹿児島デリヘルキャンディトップページ" class="fade">TOP</a></li>
				<li><a href="./news.php" title="鹿児島デリヘルキャンディからのお知らせ" class="fade">お知らせ</a></li>
				<li><a href="./girls_list.php" title="鹿児島デリヘルキャンディに在籍している女の子一覧" class="fade">女の子一覧</a></li>
				<li><a href="./schedule.php" title="鹿児島デリヘルキャンディの本日の出勤情報" class="fade">出勤情報</a></li>
				<li><a href="./system.php" title="鹿児島デリヘルキャンディの料金・システム情報" class="fade">料金</a></li>
				<li><a href="./movie.php" title="鹿児島デリヘルキャンディの動画情報" class="fade">動画</a></li>
				<li><a href="./mypage.php" title="鹿児島デリヘルキャンディ会員専用マイページ" class="fade">マイページ</a></li>
				<li><a href="https://kyusyu-okinawa.qzin.jp/candy98/?v=official" title="バニラ内キャンディ高収入求人専用サイト" class="fade">求人情報</a></li>
				<li><a href="https://www.cityheaven.net/kagoshima/A4601/A460102/newcandy/diarylist/?of=y" title="シティヘブンネット内キャンディ写メ日記" target="_blank" rel="noopener noreferrer" class="fade">写メ日記</a></li>
				<li><a href="https://candykagoshima.blog.fc2.com/" title="鹿児島デリヘルキャンディのSNS" target="_blank" rel="noopener noreferrer" class="fade">SNS</a></li>
				<li><div class="tel">099.226.6956</div></li>
			</ul>
		</div>
		<ul id="headMenu" class="headMenu spOnly is-hidden">
			<li><a href="./" class="fade">TOP</a></li>
				<li><a href="./news.php" title="鹿児島デリヘルキャンディのお知らせ" class="fade">お知らせ</a></li>
				<li><a href="./girls_list.php" title="鹿児島デリヘルキャンディの在籍一覧" class="fade">女の子一覧</a></li>
				<li><a href="./schedule.php" title="鹿児島デリヘルキャンディの本日の出勤スケジュール" class="fade">出勤情報</a></li>
				<li><a href="./system.php" title="鹿児島デリヘルキャンディの料金" class="fade">料金</a></li>
				<li><a href="./movie.php" title="鹿児島デリヘルキャンディの動画" class="fade">動画</a></li>
				<li><a href="./mypage.php" title="鹿児島デリヘルキャンディのマイページ" class="fade">マイページ</a></li>
				<li><a href="https://kyusyu-okinawa.qzin.jp/candy98/?v=official" title="鹿児島デリヘルキャンディのバニラ求人サイト" class="fade">求人情報</a></li>
				<li><a href="https://www.cityheaven.net/kagoshima/A4601/A460102/newcandy/diarylist/?of=y" target="_blank" rel="noopener noreferrer" class="fade">写メ日記</a></li>
				<li><a href="https://candykagoshima.blog.fc2.com/" target="_blank" rel="noopener noreferrer" class="fade">SNS</a></li>
		</ul>
<!-- メニュー END -->
<!-- メインコンテンツ START -->
<main id="main" class="main blog-page blog-theme-deep-pink">
<div class="bg_g3"><div class="pcOnly lm_0_auto w_1000 lp_13_0 fc_g">
<nav aria-label="パンくずリスト"><ol class="bread_list f_xxs">
<li><a href="./" class="fc_b">TOP</a></li><li class="lm_0_10">&gt;</li>
<li><a href="./blog.php" class="fc_b">ブログ一覧</a></li><li class="lm_0_10">&gt;</li>
<li><span>{{TITLE_HTML}}</span></li>
</ol></nav></div></div>
<div class="blog-article">
<header class="blog-intro"><p class="blog-eyebrow">CANDY BLOG</p><h1 id="page_title_h1">{{TITLE_HTML}}</h1></header>
<!-- CANDY_BLOG_BODY_START -->
<!-- CANDY_BLOG_BODY_PENDING -->
<div class="blog-lead"><p>記事本文は準備中です。</p></div>
<!-- CANDY_BLOG_BODY_END -->
{{RELATED_HTML}}
<div class="lm_40_0_75 center"><a href="./" class="bt-pk-xl">HOME</a></div>
</div>
</main>
<!-- メインコンテンツ END -->
<!-- フッター START -->
	  <div class="footer">
		  <div class="cssSprite footLogo" aria-label="鹿児島のデリヘルキャンディ ロゴ">
				<span class="sr-only">鹿児島のデリヘルキャンディ</span>
		  </div>
			<nav aria-label="フッターメニュー">
				<ul class="menu pcOnly">
					<li><a href="./" title="鹿児島デリヘルキャンディトップページ" class="fade">TOP</a></li>
					<li><a href="./news.php" title="鹿児島デリヘルキャンディからのお知らせ" class="fade">お知らせ</a></li>
					<li><a href="./girls_list.php" title="鹿児島デリヘルキャンディに在籍している女の子一覧" class="fade">女の子一覧</a></li>
					<li><a href="./schedule.php" title="鹿児島デリヘルキャンディの本日の出勤情報" class="fade">出勤情報</a></li>
					<li><a href="./system.php" title="鹿児島デリヘルキャンディの料金・システム情報" class="fade">料金</a></li>
					<li><a href="./movie.php" title="鹿児島デリヘルキャンディの動画情報" class="fade">動画</a></li>
					<li><a href="./mypage.php" title="鹿児島デリヘルキャンディ会員専用マイページ" class="fade">マイページ</a></li>
					<li><a href="https://kyusyu-okinawa.qzin.jp/candy98/?v=official" title="バニラ内キャンディ高収入求人専用サイト" class="fade">求人情報</a></li>
					<li><a href="https://www.cityheaven.net/kagoshima/A4601/A460102/newcandy/diarylist/?of=y" title="シティヘブンネット内キャンディ写メ日記" target="_blank" rel="noopener noreferrer" class="fade">写メ日記</a></li>
					<li><a href="https://candykagoshima.blog.fc2.com/" title="鹿児島デリヘルキャンディのSNS" target="_blank" rel="noopener noreferrer" class="fade">SNS</a></li>
				</ul>
			</nav>
			<dl class="info pcOnly">
				<dt>099.226.6956</dt>
				<dd>OPEN FROM <span>9:00 am - 6:00 am</span></dd>
			</dl>
			<dl class="info spOnly">
				<dt>OPEN FROM</dt>
				<dd>9:00 am - 6:00 am</dd>
			</dl>
			<a href="tel:0992266956" onClick="amadareAcRec('0992266956');" class="tel spOnly">
				<div class="cssSprite footTel vCenter spOnly"></div>099.226.6956
			</a>
			<div class="copy">COPYRIGHT (C) 2025 Candy All RIGHTS RESERVED.</div>
		</div>
<!-- フッター END -->
	</div>
	</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var faqItems = document.querySelectorAll('.faq-item');
	faqItems.forEach(function(item) {
		var question = item.querySelector('.faq-question');
		question.addEventListener('click', function() {
			item.classList.toggle('active');
		});
	});
});
</script>
</body>
</html>
```

### 6.2 公開入口PHP全文

<!-- code:entry-php -->
```php
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//データセット基本ファイル読込
include("/home/firststar/public_html/group/candy/includefile/dataset_base.php");


?>
```

### 6.3 記事用dataset PHP全文

<!-- code:dataset-php -->
```php
<?
$source = file_get_contents($source_file);
$source = str_replace($waku0, $waku_html, $source);
?>
```

### 6.4 共通PHP・一覧・サイトマップ

以下は共通ヘルパーが挿入する形。共通PHP全体は置換せず、switch内とリンク変換部分へ各1件登録する。

```php
case 'kagoshima-deliveryhealth-blog-<slug>.html':
    include(INCLUDE_DIR . 'dataset_kagoshima-deliveryhealth-blog-<slug>.php');
    break;

$source = str_replace('kagoshima-deliveryhealth-blog-<slug>.html', 'kagoshima-deliveryhealth-blog-<slug>.php', $source);
```

```html
<div class="lp_20_0 fs_md3 bd_t"><a href="./kagoshima-deliveryhealth-blog-<slug>.php" class="fade">HTMLエスケープ済みタイトル</a></div>
```

```xml
<url>
  <loc>https://www.55810.com/kagoshima-deliveryhealth-blog-<slug>.php</loc>
  <lastmod>YYYY-MM-DD</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
```

説明用XMLをそのまま追加しない。作成処理が確定URLとAsia/Tokyoの日付を適用し、既存URL・changefreq・priorityを保持する。一覧とTOPの行スタイル・最新15件は共通ヘルパーに従う。

### 6.5 本文に応じて追加するJSON-LDコード

以下は本文完成時の追加用コード。初期HTMLへ無条件には追加しない。置換値は表示中の確定情報を配列にしてJSONシリアライズし、`<` を `\u003c`、`>` を `\u003e`、`&` を `\u0026` に置換する。HTMLエスケープした文字列をJSONへ流用しない。

FAQ配列の各要素は `{"@type":"Question","name":質問,"acceptedAnswer":{"@type":"Answer","text":回答}}`。0件ならブロック全体を省略する。

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": {{FAQ_ITEMS_JSON}}
}
</script>
```

女の子紹介配列の各要素は `{"@type":"ListItem","position":表示順,"item":{"@type":"Person","name":名前,"url":プロフィールURL,"image":公開画像URL}}`。実際に表示していないURL等はその項目を省略する。PUBLIC情報の選択と可視内容との一致は第5章の管理先に従う。

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": {{GIRL_SECTION_TITLE_JSON}},
  "numberOfItems": {{GIRL_COUNT}},
  "itemListElement": {{GIRL_ITEMS_JSON}}
}
</script>
```

## 7. 環境の検証

```powershell
management\scripts\candy-python.cmd blog-template --help
management\scripts\candy-python.cmd blog-template doctor
```

回帰テスト: [test_candy_blog_template.py](../scripts/test_candy_blog_template.py)。一時フォルダ内で一式生成、JSON・PHP構文、最新15件、サイトマップ、上書き防止、既存不整合による停止、書込失敗時の復元を確認する。Git変更・DB操作・本番通信を伴わない。
