# CANDY 本番移行・手動反映 正本

## 1. 目的

最新 `HP/` を KAGOYA 本番へ段階的に反映し、表示崩れ、リンク切れ、転送解除、不要ファイル残存を防ぐ。

この資料は本番移行の判断基準を扱う。2026-07-13 の詳しい経緯と事故記録は `CANDY_20260713_CONTEXT_AND_IMPROVEMENT.md`、全件検証は `CANDY_VERIFICATION_PLAN.md` を参照する。

## 2. 対象環境

| 用途 | パス・場所 | 扱い |
|---|---|---|
| 最新ローカル | `HP/` | 今後反映する開発・制作データ |
| 本番スナップショット | `HP_旧データ/` | ユーザーが本番から再ダウンロードした取得時点の旧データ |
| 本番サーバー | `/public_html/group/candy/` | 実際の公開先 |
| テストサーバー | `/public_html/group_test/candy/` | ユーザーが制作時に設けたテスト版 |

ローカルパスは PC ごとに変わる。作業時の Git ルートを基準にする。本番とテストを混同しない。

## 3. 最重要の公開切替ルール

### 3.1 本番 index.php

- 本番スナップショットの `index.php` はシティヘブンへ `301` 転送する。
- 最新 `HP/index.php` は新サイトの入口であり、役割が異なる。
- 段階移行中は本番の転送用 `index.php` を維持する。
- 最新 `HP/index.php` はPush、preview、deployのすべてで対象外にする。
- 全準備完了後、ユーザーが最終公開切替を明示指示した場合だけ、最新 `HP/index.php` を単独反映する。
- 反映後はトップの転送終了、HTTP、画面、PC/SP、主要導線を確認する。

### 3.2 index 以外

PHP、include、source、CSS、JavaScript、画像、動画等は、転送用 index を維持したまま先行更新できる。ただし「転送が維持された」ことと「他ページが正常」の確認は別である。

## 4. GitHub Actions の現行設計

対象 workflow:

```text
.github/workflows/candy-production-deploy.yml
```

対象 deploy script:

```text
.github/scripts/candy_ftp_deploy.py
```

### 4.1 起動条件

- `main` へのPushでは本番処理を起動しない。
- 本番処理は手動 `workflow_dispatch` だけで起動する。
- `preview` と `deploy` は別操作であり、それぞれユーザーの明示指示を必要とする。
- `preview` はFTP秘密値を使用せず、FTP接続もサーバー変更も行わない。
- `deploy` jobには `candy-production` environmentを指定する。
- previewは5分、deployは10分でtimeoutする。
- concurrencyにより同時deployを禁止する。

### 4.2 二段階承認

previewは次を出力する。

- 比較元の40文字commit SHA
- 反映対象の40文字commit SHA
- deploy対象一覧と除外一覧
- 削除・renameの有無
- deploy対象件数
- 各対象内容のSHA256を含む `PLAN_TOKEN`

deployは次がpreviewと完全一致する場合だけFTP接続へ進む。

- 比較元SHAと対象SHA
- 対象件数
- `PLAN_TOKEN`
- 確認文言 `DEPLOY-CANDY-PRODUCTION`

一項目でも不一致ならFTP接続前に失敗させる。Push指示、preview指示、deploy指示を相互に流用しない。

### 4.3 強制上限と禁止経路

- 一回のdeployは最大25ファイル、合計50MiB以下。
- 26ファイル以上または50MiB超はpreviewできてもdeployできない。小バッチへ分割する。
- full deploy経路は持たない。
- Git上の削除・renameを検出した場合はdeploy全体を停止する。
- サーバーだけに存在するファイルを削除しない。
- 対象SHAはcheckout中のHEADと完全一致させる。
- 比較元SHAは対象SHAのancestorでなければならない。

### 4.4 保護・除外

workflow/scriptの実物で確認する主な除外:

- `HP/index.php`
- `HP/.htaccess`
- `HP/AGENTS.md`
- `HP/codex/`
- `HP/log/`
- `HP/Text_area_data/`
- `HP/Text_blog_data/`
- `HP/Text_hotel_data/`
- `HP/.well-known/`
- Markdown
- `.env`
- `.bak`、`.backup`、`.zip`
- `.candy-backup-*`、`.candy-upload-*`

除外一覧は将来のworkflowへ推測で適用せず、previewの実出力で再確認する。

## 5. FTP デプロイの安全要件

FTP接続前に、40文字SHA、ancestor、checkout HEAD、対象件数、最大25件、合計50MiB、`PLAN_TOKEN`、確認文言を検証する。検証失敗時はFTP秘密値を使わず停止する。

対象ファイルごとに次を完結させる。

1. 一時名で upload
2. 一時ファイルを download して SHA256 照合
3. 既存ファイルがあれば一時 backup 名へ rename
4. 一時ファイルを正式名へ promote
5. 正式名を download して SHA256 再照合
6. 成功した対象の backup を削除
7. `現在件数/総件数` を即時出力

失敗時:

- 失敗中の対象だけを rollback する。
- すでに成功・完結した対象は反映済みとして残る。
- 失敗位置、対象、rollback の成否を報告する。
- 一時・backup ファイルが残っていないか実サーバーで確認する。

注意: 手動二段階workflowと対象ごとに完結するscriptはローカルの未Commit差分である。Commit・Push・Actions preview成功を確認するまでGitHub上の現行動作になったと断定しない。旧workflowがGitHubに残る間は、HP変更を含むPushを行わない。

## 6. 2026-07-13 の本番作業結果

### 6.1 自動 full deploy（失敗・廃止）

- 多数ファイルを一括処理する旧方式が長時間化した。
- backup・一時ファイルが多数残り、実進捗と報告が一致しなかった。
- controlled な完了を確認できず、ユーザーが WinSCP で直下 PHP を手動反映した。
- したがって当該 Actions を「正常な全件反映完了」の証拠として使わない。

### 6.2 cleanup

ユーザーの手動反映後、確実に不要と確認できた次を本番から削除した。

| 対象 | 件数 |
|---|---:|
| `.candy-backup-*` | 319 |
| サーバー上の `.gitignore` | 1 |
| FTP smoke test | 1 |
| 合計 | 321 |

削除後:

- 本番ルート PHP: 100
- 本番 `index.php`: 転送用を維持
- 本番 inventory: 1,428 ファイル、29ディレクトリ

inventory の全ファイル SHA256 同一性まで確認した記録ではない。必要時は再取得する。

### 6.3 HTTP

- 公開 PHP 100 件のうち 99 件が `200`
- `index.php` は意図した `301`
- 想定外 PHP status は 0

これは 2026-07-13 のスナップショットであり、現在確認には再検査が必要。

## 7. 2026-07-13 のローカル移行状態

記録時点:

| 対象 | 状態 |
|---|---|
| HP 直下 PHP | 100 |
| 本番 `group/candy` include 参照 | 97 |
| テスト `group_test/candy` include 参照 | 2 |
| dataset include を持たない PHP | `makeSitemap.php` |

テスト参照が残る入口:

- `HP/kagoshima-deliveryhealth-petitegirl.php`
- `HP/kagoshima-deliveryhealth-slendergirl.php`

この二件を意図した例外か未移行か確認せず、一括置換しない。`dataset_base.php` 等の絶対パス、session、control、source 変換も対象の実物で再確認する。

## 8. 反映前の必須手順

1. root/HP `AGENTS.md` を確認する。
2. branch、remote、status、HEAD、`origin/main`を確認する。
3. 対象変更と既存変更の重なりを確認する。
4. workflowとdeploy scriptの構文・self-test・`test_candy_ftp_deploy.py`統合テストを行う。
5. workflowにpush triggerとfull deployが存在しないことを確認する。
6. Commit対象を限定し、ユーザーの明示指示後だけCommitする。
7. Push対象を再確認し、ユーザーの別の明示指示後だけPushする。
8. Push完了だけでは本番反映しない。
9. ユーザーの別の明示指示後、Actions previewを実行する。
10. previewの対象SHA、対象一覧、除外、削除・rename、件数、`PLAN_TOKEN`を記録する。
11. 対象件数が25以下、合計が50MiB以下であることを確認する。26以上または50MiB超ならCommitを分けず、SHA差分を小さく作り直して再previewする。
12. 本番 `index.php` と保護対象が除外されていることを確認する。
13. ユーザーへpreview結果と本番影響を報告し、deployの別の明示指示を得る。
14. 同じSHA、件数、`PLAN_TOKEN`、確認文言で手動deployを実行する。

## 9. 反映中

- Actions Run 番号と commit SHA を記録する。
- 実ログの `DEPLOYED 現在/総数`、失敗対象、終了コードを確認する。
- 根拠のない残り時間を報告しない。実測速度と残件数がある場合だけ推定する。
- ユーザーが停止を命じた場合は、停止操作と実際の停止状態を確認する。
- GUI を見ているだけで「監視中」と報告しない。

## 10. 反映後

1. Actions の最終結果を確認する。
2. 対象ファイルが本番に存在し、SHA256 が一致することを確認する。
3. 一時・backup ファイルの残存を確認する。
4. 本番 `index.php` の転送維持を確認する。
5. 対象 PHP の HTTP を確認する。
6. CSS/JS/画像、内部リンク、ブラウザ PC/SP を必要範囲で確認する。
7. ローカル、GitHub、本番の結果を分けて報告する。

## 11. rollback

- deploy 中の単一対象失敗は script の対象別 rollback を使う。
- Actions 完了後の rollback は、戻す commit/file、対象サーバーパス、index 影響、DB/外部依存を確認して別作業として行う。
- サーバーの一括削除や旧 snapshot の一括上書きを、根拠なしに行わない。
- `HP_旧データ` は取得時点の本番比較資料であり、現在本番と常に同一とはみなさない。

## 12. 現在の未完了事項

- 手動二段階workflowと承認ゲートをreview、Commit、Pushし、Actions previewの成功を確認すること
- GitHub environment `candy-production` に必要な保護ルールが設定されているか確認すること
- 残るテスト include 二件の意図確認
- 本番 inventory と Git 管理対象の完全な hash 照合
- `CANDY_VERIFICATION_PLAN.md` に記録された不足内部リンク・画像・外部 URL の修正判断
- 最終公開切替日と最新 `HP/index.php` の単独反映手順の承認

未完了が残る間、「本番移行100%完了」と報告しない。
