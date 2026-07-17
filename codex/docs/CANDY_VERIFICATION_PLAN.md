# CANDY 全件検証計画

更新日: 2026-07-14
適用範囲: `HP`、生成元データ、テスト環境、本番環境
位置づけ: リンク・画像・外部 URL・placeholder・HTTP 状態を確認する正本手順

## 1. 「全件確認」の定義

「全件確認」は、対象を全列挙し、基本・例外・動的生成・未完成データを分け、実確認の結果を記録した状態を指す。件数に上限を設けない。100ファイル以上、1,000 URL以上でも全件を対象とする。

次の場合は全件確認済み・100%・完了と報告してはいけない。

- 対象件数を数えていない
- PHP、HTML、CSS等の一部しか見ていない
- 静的存在確認だけで生成後 HTML を確認していない
- テンプレートと本番表示を区別していない
- 403、429、タイムアウト、TLSエラーを404と同じ扱いにした
- 動的 URL を機械判定だけでリンク切れとした
- 未確認、判定保留、手動確認待ちを隠した
- 修正後の再確認をしていない

## 2. 検証対象

毎回、実ファイルと実サーバーから最新件数を取得する。過去資料の固定件数だけを使わない。

### 2.1 公開・生成コード

- `HP` 直下の公開入口 `*.php`
- `HP/source/*.html`
- `HP/includefile/*.php`
- `HP/css/*.css`
- `HP/js/*.js`
- inline style、JSON-LD、canonical、OGP

### 2.2 公開資産

- `HP/imgHtml`
- `HP/imgCss`
- `HP/font`
- `HP/movie`
- その他、公開ページから参照される画像・動画・フォント・アイコン

### 2.3 生成元データ

- `Text_area_data`
- `Text_blog_data`
- `Text_hotel_data`

生成元は、現在公開中のリンクと、今後使用する準備リンクを分ける。未生成ページ用 URL は現在の公開リンク切れと混同せず「生成前要確認」とする。

### 2.4 環境

- ローカル `HP`
- テスト `/public_html/group_test/candy/`
- 本番 `/public_html/group/candy/`
- 実公開 URL

ローカルに存在するだけで本番確認済みとしない。FTPに存在するだけでHTTP表示確認済みとしない。

### 2.5 対象外

- `log` 本文
- 認証値、DB接続値、決済 hidden 値
- `.git`、GitHub管理情報
- 管理Markdown内の説明用URL

対象外範囲と理由は記録する。秘密値・ログ本文を報告へ転記しない。

## 3. 必須確認順序

### 3.1 全列挙

1. ローカル対象を相対パスで全列挙する
2. サーバー対象を相対パスで全列挙する
3. 公開入口PHPを全列挙する
4. 各ファイルから参照先を抽出する
5. 抽出総数、重複除外後件数、対象外件数を記録する

抽出属性は `href`、`src`、`srcset`、`action`、`poster`、CSS/inline styleの`url()`、canonical、OGP、JSON-LD、JavaScript生成URL、Textデータ内URLとする。

### 3.2 公開入口PHP

公開入口PHPを1件ずつHTTP確認する。

- 200: 表示応答あり
- 301、302、307、308: 転送先と意図を確認
- 404、410: リンク切れ候補
- 500以上: 実行エラーまたは一時障害
- 接続拒否、DNS、TLS、タイムアウト: 未確認

`index.php` のシティヘブン転送は、意図した転送として他ページの200確認と分ける。

### 3.3 生成後HTML

PHPのHTTP応答から生成後HTMLを取得し、ブラウザへ渡る内部PHP、画像、動画、フォント、CSS、JS、アンカー、外部URL、form action、canonical、OGP、JSON-LD、placeholderを抽出する。`source/*.html` の静的確認だけで代用しない。

### 3.4 内部リンク

- queryとfragmentを分離して相対パスを正規化する
- `./`、`../`、先頭`/`を解決する
- 大文字小文字を区別する本番サーバーを基準にする
- ファイル存在とHTTP応答を分ける
- 同名HTMLと公開PHPを混同しない
- `dataset_base.php` のHTML→PHP変換結果を確認する
- アンカーはリンク先の`id`または`name`と照合する

### 3.5 画像・動画・フォント・CSS

- HTML参照を実ファイルと照合する
- CSS `url()` はCSSファイル位置を基準に解決する
- `?`と`#`以降を除いて実ファイルを照合する
- `srcset`は候補ごとに確認する
- PC/SP、通常/retina、背景画像を分ける
- 使用中CSSと未使用CSSを分ける
- 未使用CSSの切れも「非稼働参照」として記録する

フォントの`?#iefix`やSVGの`#id`をファイル名の一部として誤判定しない。

### 3.6 外部URL

リダイレクトを追跡し、最終応答を確認する。

| 結果 | 判定 |
|---|---|
| 200～399 | 到達可能。転送時は最終URLも記録 |
| 400、404、410 | 切れ候補。用途を確認 |
| 401、403 | アクセス制限。切れと断定しない |
| 405 | GET不可の可能性。form action等を確認 |
| 429 | レート制限。時間を空けて再確認 |
| 500以上 | 相手側障害候補。再確認 |
| DNS、TLS、接続拒否、タイムアウト | 未確認。別手段または手動確認 |

form actionがGETで400を返しただけではリンク切れとしない。Google Maps等の429も切れと断定しない。

### 3.7 テンプレート・動的生成・placeholder

次を分ける。

- `template_*.html` の置換予定文字列
- Textデータの将来URL
- JavaScript文字列連結で完成するURL
- `rep...eot`等の置換トークン
- `aaaaaaaa...`等のplaceholder

テンプレート内だけなら現在の公開リンク切れと分ける。生成後HTML・公開ページに残った場合は不具合とする。JavaScriptの`' + variable + '`をそのままURLとして判定しない。

### 3.8 本番反映経路

本番workflowとdeploy scriptは、次を同じ基準で検査する。

ローカル再現コマンドは `python .github/scripts/candy_ftp_deploy.py --self-test` と `python .github/scripts/test_candy_ftp_deploy.py`。Actionsでもpreview/deployの前に両方を実行する。

1. workflowに `main` のdeploy対象変更だけを受ける `push` triggerがあり、管理資料・元Text等がpathsで除外される。
2. full deploy経路がない。
3. Push後のplan生成と手動previewがFTP秘密値を受け取らず、FTP接続しない。
4. deployが40文字SHA、ancestor、checkout HEAD、対象件数、`PLAN_TOKEN`、確認文言を同一Run内で自動生成・照合し、FTP接続前に検証する。
5. 一回のdeployが最大25ファイル・合計50MiB以下に制限される。
6. `index.php`、`.htaccess`、管理資料、元Text、秘密値候補、backup類が除外される。
7. 削除・renameが検出された場合、全deployが停止する。
8. ファイル単位でupload、一時SHA256、backup、promote、最終SHA256を行い、全対象の検証完了までbackupを保持する。途中失敗時は同じ実行で反映済みの全対象を逆順rollbackし、全件成功後だけbackupを削除する。
9. previewは5分、deployは10分でtimeoutする。
10. 「アップしろ」がroot `AGENTS.md` 第3.6節の定義を参照し、正常時に途中再承認を要求しない。

ローカル検査、GitHub上のworkflow構文、Actions preview、Actions deploy、本番SHA256、HTTP、ブラウザを別結果として記録する。ローカル差分だけで本番安全化済みとしない。

## 4. 結果分類

| 状態 | 意味 |
|---|---|
| `OK` | 実確認で正常 |
| `EXPECTED_REDIRECT` | 意図した転送 |
| `BROKEN_INTERNAL` | 内部リンク先がない、または404/410 |
| `BROKEN_ASSET` | 画像・動画・フォント等がない |
| `BROKEN_EXTERNAL` | 外部URLが確認済み404/410等 |
| `LIVE_PLACEHOLDER` | 公開出力に未置換文字列が残る |
| `TEMPLATE_ONLY` | テンプレート内だけの置換予定値 |
| `FUTURE_DATA` | 未生成ページ向け準備データ |
| `RESTRICTED` | 401/403/405/429等で自動確認不能 |
| `TRANSIENT_ERROR` | 500以上等の一時障害候補 |
| `UNVERIFIED` | DNS、TLS、タイムアウト等で未確認 |
| `FALSE_POSITIVE` | 動的URL、fragment等の機械誤検出 |

## 5. 記録必須項目

- 検証日時
- 対象環境、基準URL、サーバールート
- GitブランチとHEAD
- 対象ファイル数、抽出リンク数
- 参照元、参照先
- HTTP状態または存在確認結果
- 結果分類
- 自動確認か手動確認か
- 修正対象、将来生成対象、対象外の別
- 再確認結果

値を確認していない欄を推測で埋めない。

## 6. 修正優先順

1. 公開中の内部リンク・画像・CSS・JS
2. 公開出力のplaceholder・未置換トークン
3. 公開中の外部404/410
4. canonical、OGP、JSON-LD
5. 生成元の将来リンク
6. 未使用CSS・テンプレートだけの参照

調査指示だけなら変更しない。削除、置換、Commit、Push、本番反映は明示承認なしに行わない。「アップしろ」を受けた場合はroot `AGENTS.md` 第3.6節の範囲だけを明示承認として扱い、途中で再承認を求めない。

## 7. 修正後再確認

- 対象ページのHTTP応答
- 同じ共通テンプレートを使う全ページ
- 画像、CSS、JSの実取得
- ページ内アンカー
- canonical、OGP、JSON-LD
- PC/SP表示
- JavaScript console
- 本番またはテストへの反映状態

ローカル修正だけで本番修正済みと報告しない。

## 8. 完了条件

- 対象・対象外を明示した
- 対象を全列挙した
- 静的参照と生成後参照を確認した
- 内部、外部、資産、アンカー、placeholderを分類した
- 機械誤検出を除外した根拠がある
- 判定保留を未確認として明示した
- 修正した場合は再確認した
- 未実施のブラウザ、DB、外部サービス確認を完了扱いにしていない

判定保留が残る場合は「全件を走査したが未確認が残る」と報告する。未確認0件になるまで「すべて正常」と報告しない。

## 9. 2026-07-13 全件調査記録

対象: 本番 `/public_html/group/candy/`、ローカル `HP`、生成元3フォルダ。

確認済み:

- 本番公開入口PHP: 100件。99件が200、`index.php`は意図した301、異常HTTPは0件
- 本番台帳: 1,428ファイル、29フォルダ
- 生成後内部参照: 752種類
- ページ内アンカー: 767参照、欠落0件
- 公開コード・生成後出力の外部URL: 623種類
- 生成元: 175ファイル中、URLを含む173ファイルから1,229種類

要対応:

- 生成後内部参照の欠落: 機械誤検出を除外後155種類
- `area.php` のarea公開PHP: 194件中137件が未存在
- 公開areaページの画像: 14ファイルが未存在
- `shopinfo.php`: 未存在だがarea・blog・contact・hotelから参照
- 公開出力のplaceholderリンク: area 27ページ、hotel一覧の未完成行
- 稼働CSSの欠落資産: `img/dummy.gif`、`imgHtml/cdBgGirl.png`
- 非稼働`YTPlayer.css`の欠落資産: フォント2件、raster画像4件
- 公開ページ・コード内でHTTP 4xxとなる参照: 26種類。決済form actionのGET 400は切れと断定しない
- 現在公開出力で確認した外部404: FC2のSNS、旧diary、神之川温泉、ローソン、パークホテル鹿児島
- `www.55810.com`の404: placeholder、canonical、OGP、画像を含む20種類
- 生成元の`www.55810.com` URL: 346種類中228正常、404が118種類。内訳はPHP 102、画像16

判定保留:

- 生成元の外部URL 883種類中、430正常、450アクセス制限、3接続不能
- 制限450の主因はGoogle Maps短縮URLの429が439件
- 制限・接続不能は404と断定せず、生成時に手動再確認する

この記録は調査結果であり、修正済み記録ではない。2026-07-13時点でファイル修正、本番修正、Commit、Pushは未実施。
