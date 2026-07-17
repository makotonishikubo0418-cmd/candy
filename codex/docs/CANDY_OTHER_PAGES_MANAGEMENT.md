# CANDY その他ページ管理書

## 1. 目的

area、hotel、blog以外のページについて、役割、内部構成、同時更新範囲、検証、STOP条件を一元管理する。

この資料は現在値の固定台帳ではない。作業開始時に対象実ファイル、件数、Git状態を再確認する。

## 2. 対象

2026-07-15時点の母集団は、ルート直下PHP 15件と `sitemap.xml` 1件。

| 区分 | 対象 | 件数 |
|---|---|---:|
| 公開入口 | `contact.php`、`girls.php`、`girls_list.php`、`index.php`、`main.php`、`movie.php`、`movie_iframe.php`、`mypage.php`、`news.php`、`page.php`、`schedule.php`、`system.php`、`test.php` | 13 |
| 管理・生成 | `create.php`、`makeSitemap.php` | 2 |
| 公開生成物 | `sitemap.xml` | 1 |

除外:

- `area.php` とarea詳細
- `hotel.php` とhotel詳細
- `blog.php` とblog詳細
- CSS、JavaScript、画像、fontの全件台帳
- `log/`、`movie/` の内容

除外対象でも、変更ページから参照される場合は影響範囲として確認する。

## 3. 基本内部構成

通常の公開入口は次の順で出力する。

```text
ルートPHP
  -> includefile/dataset_base.php
      -> 外部session・設定・DB接続
      -> includefile/class.hpgcoder2.php
      -> includefile/funcs.php
      -> source/同名.html の存在確認
      -> includefile/dataset_同名.php
      -> placeholder変換
      -> HTML出力
```

重要:

- ルートPHPはほぼ `dataset_base.php` を読むだけで、表示内容はsourceとdataset側にある。
- `dataset_base.php` は全ページ共通であり、1ページだけのつもりでも全体障害を起こし得る。
- `main.php`、`page.php`、`test.php` はルートPHPがあるが、現在のリポジトリには対応sourceがない。
- `create.php` と `makeSitemap.php` は上記の通常経路を使わない。

## 4. ページ別管理表

| ページ | 役割 | 内部構成・入力 | 同時確認先 | 現在状態 |
|---|---|---|---|---|
| `index.php` | トップ、全主要導線、カテゴリ入口 | `source/index.html` + `dataset_index.php`。女の子、出勤、バナー、動画、店舗表示をDBから生成 | area/blog/hotel欄、共通ナビ、画像、JSON-LD、`sitemap.xml` | 通常経路あり。本番 `index.php` 反映は事前承認必須 |
| `girls_list.php` | 女の子一覧 | `source/girls_list.html` + `dataset_girls_list.php`。女の子、画像、出勤、並び順、Cookieお気に入り | `girls.php?no=...`、画像、出勤、共通ナビ | 通常経路あり |
| `girls.php` | 女の子プロフィール | `source/girls.html` + `dataset_girls.php`。GETの女の子番号、女の子、画像、動画、出勤、Cookieお気に入り | 一覧・出勤・動画への戻り、canonical/構造化データ、画像/動画 | 通常経路あり。GET値と0件時を検証 |
| `schedule.php` | 本日・週間出勤 | `source/schedule.html` + `dataset_schedule.php`。女の子、画像、出勤、日付切替、Cookieお気に入り | 女の子詳細、日付タブ、0件表示、共通ナビ | 通常経路あり |
| `news.php` | お知らせ一覧 | `source/news.html` + `dataset_news.php`。`newstopics` を表示 | 日付、画像、0件表示、共通ナビ | 通常経路あり |
| `system.php` | 料金・システム・規約 | `source/system.html` + `dataset_system.php`。ホテルクーポン表示と外部決済フォームを含む | 料金、規約、外部送信先、hidden値、共通ナビ | 通常経路あり。外部送信・認証値は変更ゲート対象 |
| `movie.php` | 店舗・女の子動画一覧 | `source/movie.html` + `dataset_movie.php`。店舗動画、女の子動画、端末別表示、iframeリンク | `movie_iframe.php`、動画ファイル、サムネイル、0件表示 | 通常経路あり |
| `movie_iframe.php` | 動画再生用iframe | `source/movie_iframe.html` + `dataset_movie_iframe.php`。GET値から店舗/女の子動画を選択 | 呼出元 `movie.php`、動画形式、無効GET、直接表示 | 通常経路あり。共通ナビからの直接導線なし |
| `mypage.php` | お気に入り女の子の確認 | `source/mypage.html` + `dataset_mypage.php`。Cookie、女の子、画像、出勤、マイページ情報 | お気に入り登録/解除、Cookieなし・期限切れ、女の子詳細 | 通常経路あり。会員ID/PW方式ではなくCookie依存が中心 |
| `contact.php` | 現在は問い合わせ名の汎用コンテンツ入口 | `source/contact.html` + 小規模な `dataset_contact.php`。フォーム送信処理はない | source内リンク、問い合わせ先、title/H1、遷移先 | sourceにplaceholderが残り、問い合わせフォームもない。完成ページ扱い禁止 |
| `main.php` | `dataset_base.php`のコメント上は年齢認証後のメイン候補 | `dataset_index.php` を使う分岐はあるが `source/main.html` がない | `index.php`との役割、外部導線、`sitemap.xml` | リポジトリ構成ではsource存在確認で停止する |
| `page.php` | 汎用ページ用の旧scaffold候補 | `dataset_page.php` はあるが `source/page.html` がない | 外部導線、`sitemap.xml`、用途確定 | リポジトリ構成ではsource存在確認で停止する |
| `test.php` | テスト用scaffold候補 | `dataset_test.php` はあるが `source/test.html` がない | 公開要否、noindex、削除可否 | リポジトリ構成ではsource存在確認で停止する |
| `create.php` | 認証付きページ生成機能 | standalone。POSTでページ名を受け、ルートPHP・dataset・sourceを作成し、`dataset_base.php`へcaseと置換を追記 | 認証、生成3ファイル、共有PHP差分、rollback、noindex | 通常制作では使用禁止。認証値は資料・ログへ転記禁止 |
| `makeSitemap.php` | サイト内リンクの再帰収集とXML応答 | 現在ホストを起点にリンクを取得し、test時はdump、通常時はXMLをHTTP出力 | seed、404判定、外部除外、無限巡回、SSL、出力差分 | `sitemap.xml`へ保存しない。通常更新手段として使用禁止 |
| `sitemap.xml` | 検索エンジン向け公開URL一覧 | 静的XML。2026-07-15確認時は56 URL | 新規・URL変更・廃止、canonical、HTTP状態、index可否 | `main.php`・`page.php`を含む一方、`news.php`・`movie_iframe.php`等は含まない。修正前に意図確認 |

## 5. 共通ナビの影響

2026-07-15確認時、`source/*.html` 101件のうち99件が次の共通導線を個別HTML内に持つ。

- `girls_list.php`
- `schedule.php`
- `system.php`
- `movie.php`
- `mypage.php`
- `news.php`

ページ本文だけの変更では99件を更新しない。URL、ナビ名称、共通header/footerを変える場合だけ、全sourceを母集団として参照件数、変更件数、除外、失敗を集計する。

## 6. 変更単位

### 6.1 既存静的内容

対象:

- `source/対象.html`
- 必要な場合だけ `includefile/dataset_対象.php`
- 変更したリンク、画像、構造化データの参照先

ルートPHPと `dataset_base.php` は、経路変更がなければ変更しない。

### 6.2 動的表示

対象:

- `source/対象.html` のplaceholder枠
- `includefile/dataset_対象.php` の取得・並び・0件処理
- GET、Cookie、日付、端末、外部フォーム等の入力条件
- 詳細/一覧/iframeなど対になるページ

DB書込、認証、決済、外部送信の変更は通常ページ修正に含めず停止する。

### 6.3 新規URL・URL変更

原則として次を同時確認する。

1. ルートPHP
2. `source/同名.html`
3. `includefile/dataset_同名.php`
4. `includefile/dataset_base.php` のcase
5. HTML内 `.html` から `.php` への置換
6. 入口ページと共通ナビ
7. canonical、構造化データ
8. `sitemap.xml`
9. 本番HTTPと旧URL

`create.php`で自動生成しない。既存の専用カテゴリツール対象でない新規ページは、影響表とstage許可表を先に作る。

### 6.4 トップ変更

`source/index.html` と `dataset_index.php` の対象欄を限定する。`index.php`の本番反映、転送、年齢認証、root URLの変更は事前承認なしに行わない。

### 6.5 sitemap変更

`makeSitemap.php`の出力をそのまま `sitemap.xml` にしない。既存56 URLとの差分を取り、追加・維持・削除を1 URLずつ分類し、HTTP、canonical、index可否を確認する。

## 7. 検証

対象に応じて必要な項目だけ実行し、同じ検査を重ねない。

| 種別 | 必須確認 |
|---|---|
| 全変更 | 対象限定diff、`git diff --check`、削除/rename/許可外混入なし |
| PHP | 変更PHPのlint、include先、未定義変数、0件・不正入力 |
| source | title、H1、canonical、robots、内部リンク、画像、PC/SP |
| dataset | 対応case、placeholder数、DB 0件、並び、escape、Cookie/GET |
| 一覧/詳細 | 一覧から詳細、詳細から一覧、存在しないID、画像/動画欠損 |
| 外部送信 | action、送信項目、認証値非露出、失敗時表示。送信テストは別承認 |
| sitemap | XML妥当性、URL重複、対象HTTP、canonical、意図しない管理URLなし |
| 本番 | Actions成功後、対象URL、関連URL、asset、console、HTTPを確認 |

## 8. 変更ゲート

事前承認が必要:

- `create.php`
- `index.php` の本番反映
- 認証、DB書込、決済、外部送信、noindex/index
- `.htaccess`、`log/`、`.well-known/`
- ファイル削除、移動、rename

影響範囲を示してから変更:

- `includefile/dataset_base.php`
- `includefile/class.hpgcoder2.php`
- `includefile/funcs.php`
- 各 `includefile/dataset_*.php`
- `source/system.html`
- `css/default.css`、`js/common.js`
- `makeSitemap.php`、`sitemap.xml`

## 9. STOP

- 対象ページの役割、URL、公開要否を実ファイルから確定できない
- `main.php`、`page.php`、`test.php`をsource未作成のまま正常ページとして扱う必要がある
- `contact.php`を現在のplaceholder状態のまま完成ページとして公開する必要がある
- 共有PHP、認証、DB、決済、外部送信、本番 `index.php` の変更承認がない
- 共通ナビ変更なのに全sourceの母集団と差分を確定できない
- sitemapからの削除、URL廃止、redirectが必要だが承認がない
- 既存dirtyと対象変更が重なり、安全に分離できない

## 10. 作業手順

1. root `AGENTS.md` と `HP/AGENTS.md` を読む。
2. `CANDY_MASTER_DOC_INDEX.md` からこの資料へ入る。
3. Git branch、remote、statusを確認する。
4. 対象PHP、source、dataset、`dataset_base.php` case、参照元、sitemapを実物確認する。
5. ページ別管理表から役割と変更単位を決める。
6. 対象だけ変更し、検証表を実行する。
7. 「アップしろ」の明示がある場合だけ対象限定Commit、main Push、Actions、本番HTTPまで行う。

## 11. 完了報告

```text
対象ページ:
役割:
変更ファイル:
同時確認先:
検証結果:
Commit:
Push:
Actions:
本番URL:
未確認・未実施:
```

実施していないCommit、Push、Actions、本番確認は、実施済みと書かない。
