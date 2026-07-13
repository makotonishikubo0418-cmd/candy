# CANDY AREA STAFF PRODUCTION RUNBOOK

更新日: 2026-07-14
対象: スタッフが別のCodexへ依頼し、未作成areaページを通常生成する作業

## 1. この資料の役割

この資料は、別PC・別タスクのCodexでも同じ手順でareaページを制作し、途中再開と進捗確認ができるようにする実行手順書です。

この資料だけで作業を開始してはいけません。必ず次を順番に読みます。

1. リポジトリ直下 `AGENTS.md`
2. `HP/AGENTS.md`
3. `CANDY_PAGE_GENERATION_GOVERNANCE.md`
4. `CANDY_AREA_PAGE_GENERATION_SPEC.md`
5. `CANDY_AREA_IMAGE_ASSET_MANAGEMENT.md`
6. `CANDY_AREA_105_PAGE_QUEUE.md`

矛盾がある場合は作業を停止し、ユーザーへ報告します。

## 2. 作業単位

- 初回は2ページだけを試験制作する
- 初回の差分・ブラウザ表示・リンクをユーザーが確認した後は、原則5ページずつ制作する
- 同時に複数のCodexへ別バッチを担当させない
- 1バッチをCommit・Pushし、次のPCがPullしてから次バッチへ進む
- 105ページを一括生成しない
- バッチ途中で1ページにSTOP条件が発生した場合、そのページだけを停止し、他ページを続けるかはユーザー確認を得る

理由: 各ページ固有3ファイルに加えて、`dataset_base.php`、`source/area.html`、`sitemap.xml`という共有ファイルを更新するため、並行作業は競合・登録漏れ・重複を起こします。

## 3. スタッフがCodexへ渡す指示

```text
CANDYのareaページを通常生成してください。
対象バッチ:
- 地域名 / slug
- 地域名 / slug

必ず CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md と関連正本を読み、公開PHP、source HTML、dataset PHP、dataset_base、area一覧、内部リンク、sitemap、画像、JSON-LDまで確認してください。
画像不足・入力不足・slug不一致・既存ファイル競合があれば、そのページは停止して報告してください。
制作後に「アップしろ」と指示されたら、関連管理 `.md` の整合、Commit、Push、自動本番Actions、本番URL確認まで途中質問なしで実施してください。
```

地域名だけを指示せず、`CANDY_AREA_105_PAGE_QUEUE.md`に記載されたslugも併記します。

「アップしろ」は単なるGitHub Pushではありません。今回バッチの進行記録更新、本番自動反映、全対象ページの本番URL報告までを含みます。通常の1ページ公開は5分以内を運用目標とし、ActionsはGitHub APIで追跡します。ブラウザUI操作は通常経路に使用しません。

## 4. 作業開始前の必須確認

Codexは変更前に次を確認して短く報告します。

- 適用するAGENTSルール
- 作業種別: `New feature implementation` + `Verification`
- 対象地域名とslug
- 変更予定ファイル
- 変更しないファイル
- DB、ログ、決済、本番への影響なし
- 現在のbranch、remote、worktree
- `fetch` と `pull --ff-only` の結果

STOP:

- worktreeがcleanでない。ただしユーザーが内容を確認し、今回作業との共存を明示承認した場合を除く
- `main`以外のbranch
- remoteが指定GitHubと異なる
- fast-forward Pullできない
- 対象slugがキュー、txt canonical、画像名で一致しない
- 同名ファイルが存在し、新規作成か既存修正か判定できない

現在の105件には、3ファイルすべて未作成の通常新規候補96件と、公開PHP・dataset PHPが既にある既存不整合9件が含まれます。9件は通常新規バッチへ入れず、`CANDY_AREA_105_PAGE_QUEUE.md`の区分に従います。

## 5. 1ページの入力確認

対象txtはフォルダ名だけで完成判定しません。ファイル本文を読み、次を確認します。

- title、description、canonical、image
- 地域名、page title、パンくず
- `img_1`、`img_2`
- scene、subtitle、description
- 対応店舗、交通費、移動時間
- ホテル、スポット、地図、住所など存在する全項目
- 未入力、仮文字、placeholder、説明文の欠落
- canonicalから抽出したslug

不足が1件でもあれば推測で補完せず、そのページを停止して不足項目を報告します。

## 6. 画像確認

各ページで次の2枚が公開用正本に実在することを確認します。

```text
HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-<slug>_1.jpg
HP/imgHtml/new_202601/area/kagoshima-deliveryhealth-area-<slug>_2.jpg
```

- canonical slugと画像slugを完全一致させる
- JPGとして読み込めること、サイズ、重複を確認する
- 既存画像の無断流用、ダミー画像、画像名の推測を禁止する
- 画像がなければページを作らず、正式ファイル名を示して提供を依頼する
- `画像データ/area_img`をHTMLから直接参照しない

## 7. 1ページで作成する3ファイル

```text
HP/kagoshima-deliveryhealth-area-<slug>.php
HP/source/kagoshima-deliveryhealth-area-<slug>.html
HP/includefile/dataset_kagoshima-deliveryhealth-area-<slug>.php
```

- `source/template_kagoshima-deliveryhealth-area.html`を基準にする
- 同カテゴリの現行完成ページも比較する
- 公開PHPとdataset PHPは現行完成ページの形式に合わせる
- 開発改修、構造変更、短縮タグ変更を通常生成へ混ぜない
- 既存同名ファイルを無断上書きしない

## 8. source HTML反映

元txtの情報量に合わせて構造を調整します。

- SEO、canonical、OGP、パンくず、h1、地域名を一致させる
- 画像src、altを一致させる
- 店舗、ホテル、スポットの件数を固定しない
- scene、subtitle、descriptionを上から再採番する
- FAQ最終項目だけ `bd_tb`、それ以外は `bd_t`にする
- 本文とJSON-LDの店舗数・順序・URL・説明を一致させる
- BreadcrumbListとItemList系JSON-LDを構文解析する
- 元txtにない情報を推測で追加しない
- placeholderを0件にする

## 9. 共有ファイルへの必須登録

### 9.1 dataset_base.php

各slugについて次の2箇所を登録します。

- area datasetのcase振り分け
- `.html`から`.php`へのリンク変換

同一登録が既にある場合は重複追加しません。別slug・誤記候補がある場合は自動統合せず停止します。

### 9.2 source/area.html

area一覧は未作成ページへの先行リンクや表記差が存在するため、単純追記を禁止します。

各ページについて次を確認します。

- 正式slugのリンクが既にあるか
- 地域名が正しいか
- 旧slug、類似slug、誤記リンクがないか
- 同一地域の重複リンクがないか
- 一覧本文と一覧側JSON-LDの両方が一致するか

正式リンクがなければ所定位置へ追加します。誤記・別slugの置換や削除が必要な場合は、対象と影響を報告してユーザー承認を得ます。

### 9.3 関連内部リンク

- 新規ページ本文からのリンクが公開PHPを指すことを確認する
- 既存関連ページへ新規ページのリンクを追加する必要があるか実ファイルで確認する
- 関連性を推測して大量の相互リンクを追加しない
- 元データまたは既存の明確なリンク構造に基づく変更だけを行う

### 9.4 sitemap.xml

- 正式URLが既に登録されているか確認する
- 未登録なら既存形式・位置・日付運用を確認して追加する
- 重複URLを作らない
- 旧slugや誤記URLの削除・置換はユーザー承認なしに行わない
- `makeSitemap.php`を勝手に実行しない

`dataset_base.php`、`source/area.html`、`sitemap.xml`は共有重要ファイルです。バッチ開始時の指示範囲に含まれることを確認し、差分を最小化します。

## 10. バッチ検証

ページごと:

- 3ファイルが正式slugで存在する
- PHP構文確認
- placeholder 0件
- canonical、OGP、h1、パンくず、画像slug一致
- scene、ID、subtitle、descriptionに重複・欠番なし
- FAQ件数、最終class、JSON-LD件数一致
- JSON-LD構文成功
- 画像2枚実在・読込可能
- 内部リンクが公開PHPを指す

共有登録:

- dataset caseが各slugに1件
- HTML→PHP変換が各slugに1件
- area一覧の正式リンクが各地域1件
- area一覧JSON-LDと本文が一致
- sitemapの正式URLが各slug1件
- 旧slug・誤記候補を無断変更していない

バッチ全体:

- `git status --short`
- `git diff --stat`
- `git diff --check`
- 変更ファイルが指示範囲内
- 既存ファイルの意図しない削除、移動、リネームなし
- 準備画像フォルダやバックアップZIPをステージしていない

実ブラウザ、PC、SP、JavaScript console、画像HTTP、リンク遷移を確認していない場合は、それぞれ未確認と報告します。

PHP構文確認は利用可能なPHP CLIで各変更PHPへ `php -l` を実行します。PHP CLIがない環境では、構文確認済みと報告してはいけません。その場合はPHP構文未確認として停止し、別環境での検証またはユーザー判断を求めます。2026-07-13時点の現在PCでは `php` コマンドは確認できていません。

## 11. 完了の定義

次の全条件を満たしたページだけを「ローカル制作完了」とします。

- 入力不足なし
- 画像2枚確認済み
- 3ファイル生成済み
- dataset_base 2登録済み
- area一覧・一覧JSON-LD確認済み
- 内部リンク確認済み
- sitemap確認済み
- PHP・JSON・静的検査成功
- 差分検査成功

ローカル制作完了、Commit済み、Push済み、本番反映済み、ブラウザ確認済みは別状態です。実施していない状態を完了報告へ混ぜません。

## 12. 進捗台帳更新

`CANDY_AREA_105_PAGE_QUEUE.md`の状態は、検証結果に合わせて更新します。

- `READY_CANDIDATE`: ファイル・画像の存在確認だけが済んだ制作候補
- `IN_PROGRESS`: 現在の1バッチに含まれる
- `LOCAL_COMPLETE`: 第11章の全条件を満たした
- `COMMITTED`: 対象Commitを記録済み
- `PUSHED`: origin/mainへの反映確認済み
- `BLOCKED`: 理由を記録して停止

存在確認だけで `LOCAL_COMPLETE` に変更してはいけません。各バッチの担当Codex、日付、対象slug、Commit hash、未確認事項を台帳へ残します。

## 13. Commit・Push・次バッチ

- ユーザーの明示指示がある場合だけCommitする
- `git add -A`を使わず、対象ファイルだけを指定してstageする
- Push前にfetchし、origin/main更新を確認する
- ユーザーの明示指示がある場合だけPushする
- 次のスタッフ・PCは作業開始前にstatusとfetchを確認し、作業ツリーと履歴が安全な場合だけ`pull --ff-only`する
- 1バッチのPush確認前に次バッチを開始しない

## 14. STOP条件

- 入力不足、画像不足、slug不一致
- 同名ファイルの競合
- 旧slug、誤記、重複リンクの扱いに判断が必要
- dataset_base、area一覧、sitemapの既存構造と仕様が一致しない
- JSON-LDを本文と一致させられない
- PHP・JSON・差分検査が失敗
- 指示外ファイルの変更が必要
- コンフリクト、リモート更新、秘密値、ログ、DB、本番作業が関係する

停止時は、対象ページ、確認済み、未完了、変更済みファイル、判断が必要な点を短く報告します。
