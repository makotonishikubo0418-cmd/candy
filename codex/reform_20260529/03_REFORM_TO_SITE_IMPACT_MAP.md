# FSG改修案とCANDY HP影響対応表

> **旧資料:** 2026-05-29時点の検討記録であり、現行正本ではありません。現在の作業判断は `HP/codex/docs/CANDY_MASTER_DOC_INDEX.md` から選んだ正本と実ファイルを使用してください。

作成日: 2026-06-09
状態: 管理用整理のみ。HP本体は未修正。

## 結論

FSG改修案をCANDY SEOサイトへ反映する場合、最初に扱うべきは「公開HPの見た目」ではなく、FSGの業務要件をCANDY側の既存ページ・既存dataset・新規マイページ要件へ分けること。

## 影響対応表

| FSGテーマ | CANDY側の解釈 | 影響候補ファイル | 現時点の対応 |
|---|---|---|---|
| キャンディマイページ | 会員登録、ログイン、会員情報、利用履歴、評価、お気に入り、お知らせ | `mypage.php`、`source\mypage.html`、`includefile\dataset_mypage.php` | 既存実装の用途確認が先。未修正 |
| AI受付連携 | 会員登録/ログイン情報、評価傾向、問い合わせ経路、女の子案内への連携 | 入口未確認。候補は `source\index.html`、`source\contact.html`、ナビ周辺 | 公開HPに出す導線を決めるまで未修正 |
| 女の子情報 | 一覧、詳細、プロフィール、評価、お気に入り、NG等との連携 | `girls_list.php`、`girls.php`、`source\girls_list.html`、`source\girls.html`、`dataset_girls_list.php`、`dataset_girls.php` | DB項目と表示項目の棚卸しが必要 |
| 出勤情報 | 出勤予定、通知、お気に入り女の子出勤通知との関係 | `schedule.php`、`source\schedule.html`、`dataset_schedule.php` | 表示元DBと通知要件の確認が必要 |
| 料金/コース/オプション | コース、オプション、割引、支払方法を整理 | `system.php`、`source\system.html`、`dataset_system.php` | 現行料金とFSGマスター案の照合が必要 |
| ホテル/案内先 | ホテルカテゴリ、GoogleマップURL、案内先情報 | `hotel.php`、`source\hotel.html`、ホテル詳細HTML、ホテルdataset | 現行掲載情報とCTI案内先マスターの対応確認が必要 |
| エリア | 対応エリア、エリア詳細、SEO記事 | `area.php`、`source\area.html`、`kagoshima-deliveryhealth-area-*.php/html`、`dataset_base.php` | 既存のエリア管理資料を併用 |
| 問い合わせ経路 | 電話、Web問い合わせ、AI受付、媒体経路 | `source\index.html`、`source\contact.html`、全ページ共通ヘッダー/フッター | 入口を統一する前に現行導線棚卸しが必要 |
| お知らせ | 会員向けお知らせ、公開NEWS、管理画面登録 | `news.php`、`source\news.html`、`dataset_news.php`、`mypage` 関連 | 公開NEWSと会員向けお知らせを分ける必要あり |
| スマホ表示 | スマホ/タブレットでの導線最適化 | `css\default.css`、`source\style.css`、全 `source\*.html`、`js\common.js` | 共通部複製のため一括影響確認が必要 |
| SEO公開管理 | title、description、canonical、robots、JSON-LD、sitemap | 全 `source\*.html`、`makeSitemap.php`、`sitemap.xml` | 公開対象確定後に対応 |

## 改修テーマ別の最初の調査対象

### 1. CANDYマイページ

最初に見るファイル:

| ファイル | 確認内容 |
|---|---|
| `H:\Data\01_CTI\candy_HP\mypage.php` | 公開入口としての実体 |
| `H:\Data\01_CTI\candy_HP\source\mypage.html` | 現在の画面内容 |
| `H:\Data\01_CTI\candy_HP\includefile\dataset_mypage.php` | DB/Cookie/お気に入り等の処理有無 |

確認すること:

1. 既存 `mypage` が会員マイページなのか、お気に入り等の簡易ページなのか。
2. FSG要件の会員登録・ログイン・利用履歴・評価・お気に入り・お知らせを既存ページに載せるのか、新規機能として分けるのか。
3. AI受付とログイン状態を共通管理する場合、認証情報をどこに持つのか。

### 2. 公開HPの共通導線

最初に見るファイル:

| ファイル | 確認内容 |
|---|---|
| `source\index.html` | TOPの主要導線 |
| `source\girls_list.html` | 女の子一覧への導線 |
| `source\schedule.html` | 出勤導線 |
| `source\system.html` | 料金・予約前説明 |
| `source\area.html` | エリア一覧 |
| `source\hotel.html` | ホテル一覧 |
| `source\contact.html` | 問い合わせ候補。ただしplaceholder確認済み |

確認すること:

1. 電話導線、予約導線、AI受付導線をどう並べるか。
2. CANDYマイページへの入口をナビに置くか。
3. 共通ヘッダー/フッターがどのHTMLに複製されているか。

### 3. データ整合

最初に見るファイル:

| ファイル | 確認内容 |
|---|---|
| `dataset_girls_list.php` | 女の子一覧のDB表示 |
| `dataset_girls.php` | 女の子詳細のDB表示 |
| `dataset_schedule.php` | 出勤情報のDB表示 |
| `dataset_system.php` | 料金/システム表示の動的部分 |
| `dataset_base.php` | ページとdatasetの紐付け |

確認すること:

1. 女の子・出勤・料金・ホテル・エリアの表示元が、手書きHTMLかDB由来か。
2. FSGのマスター整理とCANDY公開表示のどこが一致/不一致か。
3. 既存DB情報を公開HPに反映する範囲。

### 4. SEO/公開制御

最初に見るファイル:

| ファイル | 確認内容 |
|---|---|
| 全 `source\*.html` | `robots`、`title`、`description`、canonical、JSON-LD |
| `sitemap.xml` | 公開対象URL |
| `makeSitemap.php` | sitemap生成方法 |
| `.htaccess` | CORS、リダイレクト、サーバー挙動 |

確認すること:

1. 公開対象ページと非公開ページを分ける。
2. `noindex` を外すページと残すページを決める。
3. placeholderページを公開対象から除外するか、完成させるか。
4. sitemap更新のタイミングを決める。

## 優先順位

| 優先 | 作業 | 理由 |
|---|---|---|
| 1 | 既存 `mypage` の現行用途確認 | FSGのキャンディマイページ要件と衝突しやすい |
| 2 | 共通導線の棚卸し | 電話、予約、AI受付、マイページの入口整理に必要 |
| 3 | 女の子/出勤/料金の表示元確認 | FSGの共通データ整理と直結 |
| 4 | placeholder/noindexの整理 | 公開可否判断に必要 |
| 5 | sitemap/canonical/JSON-LD確認 | SEO公開作業の直前に必要 |
