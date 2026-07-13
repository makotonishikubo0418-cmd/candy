# CANDY 通常運用の基本

## 1. 役割

既存 HP の調査・修正・確認で共通使用する短い手順である。新規ページ生成は `CANDY_PAGE_GENERATION_GOVERNANCE.md`、本番作業は `CANDY_PRODUCTION_MIGRATION_MASTER.md` を優先する。

## 2. 開始前

1. root `AGENTS.md`、`HP/AGENTS.md` を読む。
2. `CANDY_MASTER_DOC_INDEX.md` で今回の正本資料を選ぶ。
3. Git ルート、branch、remote、status を確認する。
4. 対象ファイルと既存変更の重なりを確認する。
5. やること、やらないこと、完了証拠を短く示す。

```powershell
git remote -v
git branch --show-current
git status --short --branch
```

Fetch/Pull は作業ツリーと履歴が安全な場合だけ行う。Commit/Push は作業終了時の自動手順ではなく、ユーザーの明示指示がある場合だけ行う。

## 3. 調査の基本単位

必要に応じ、次を一組として確認する。

- HP 直下の公開 PHP
- `HP/source/` の対応 HTML
- `HP/includefile/dataset_*.php`
- `dataset_base.php`、`class.hpgcoder2.php`、`funcs.php`
- CSS、JavaScript、画像、動画
- Text 元データ
- 一覧、関連ページ、内部リンク、sitemap
- DB、session、外部連携の有無

ファイル数や参照数は変化するため、この資料の固定値を使わず実ファイルから数える。一ファイルだけを見て仕様を断定しない。

## 4. 変更前

- 結論と変更理由
- 変更ファイルと変更しないファイル
- 影響するページ、PC/SP、共通処理
- DB、本番、秘密値、ログ、決済への影響
- 検証方法
- 未確認とユーザー判断が必要な点

を確認し、`AGENTS.md` の変更ゲートに該当する場合は承認を得る。

Git 管理中ファイルへ機械的な `.before` コピーを作らない。Git と明示された本番ロールバック方式を使用する。未追跡資産や本番ファイルの保全が必要な場合は、対象、保存先、復元方法を決めてから実施する。

## 5. 変更中

- 指定範囲だけを変更する。
- 既存変更を上書きしない。
- 置換トークン、dataset、include、リンク、画像参照を同時に確認する。
- 固定のファイル数上限は設けない。
- 共通処理の変更は、対象外ページへの影響も確認する。
- 認証値、DB 接続値、決済値、ログ本文、個人情報を転記しない。

## 6. 変更後

最低限:

```powershell
git status --short
git diff --stat
git diff --check
```

作業に応じて次を追加する。

- PHP/HTML/JavaScript 構文
- 生成結果
- 内部リンクと参照画像
- PC/SP 表示
- JavaScript console
- DB・session・外部サービス
- HTTP 応答

実行していない検査は未確認と書く。

## 7. 本番・テスト

| 用途 | パス |
|---|---|
| 本番 | `/public_html/group/candy/` |
| テスト | `/public_html/group_test/candy/` |

- テスト環境の存在は確認済み。
- 段階移行中、本番 `index.php` はシティヘブンへの 301 転送を維持する。
- 最新 `HP/index.php` の本番反映は最終公開切替であり、明示承認が必要。
- `main` へのPushだけでは本番反映しない。Commit、Push、preview、deployを別操作として扱う。
- 本番は手動previewで対象SHA・対象一覧・件数・`PLAN_TOKEN`を取得し、同じ値を指定した手動deployだけを許可する。
- 一回のdeployは最大25ファイル。full deploy、自動削除、rename反映は行わない。
- workflow と deploy script の実物を見ずに反映対象・除外を断定しない。
- サーバー変更、削除、Actions preview、Actions deployは、それぞれ明示指示を必要とする。

詳細は `CANDY_PRODUCTION_MIGRATION_MASTER.md` を確認する。

## 8. 未確認の扱い

本番 PHP バージョン、Web サーバー種別、DB 実体、外部サービス設定等は、実際に再確認するまで未確認とする。古い資料のパス候補や値を現在値として断定しない。秘密値に近い内容は値を出さず、所在だけを報告する。

## 9. 完了報告

次を必要な範囲だけ分ける。

- ローカル変更
- 構文・静的検証
- Commit
- Push
- Actions
- 本番ファイル
- HTTP
- ブラウザ表示

「完了」だけで済ませず、対象、件数、失敗、未確認、未実施を示す。
