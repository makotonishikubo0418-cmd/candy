# CANDY 運用基礎

改修前に必ず確認する運用情報です。不明な項目は未確認とし、確認方法を併記します。

## 0. 正本ルールとGit同期

- リポジトリ全体の正本ルールは `AGENTS.md`、HP配下の補足ルールは `HP/AGENTS.md`。
- 現在確認済みのリポジトリルートは `H:\Data\01_FSG\candy`。
- 作業開始前にFetchとPull、作業終了後に差分確認・Commit・Pushを行う。未コミット変更や未取得変更がある場合は上書きせず、コンフリクトは勝手に解消しない。
- `AGENTS.md` 自体も通常ファイルと同様にGitHubで同期する。

## 共通集計

集計時点: 2026-07-10 04:52

| 項目 | 件数 |
|---|---:|
| 全フォルダ | 37 |
| 全ファイル | 1683 |
| コード/設定ファイル | 328 |
| 非コードファイル | 1355 |
| codex配下ファイル | 34 |
| codex/docs配下ファイル | 13 |
| 管理MD(CANDY_*.md) | 13 |
| ルート直下PHP | 98 |
| source HTML | 89 |
| includefile PHP | 101 |
| dataset_*.php | 99 |
| Text_*_data配下ファイル | 175 |
| log配下ファイル | 74 |

注記: `全フォルダ` は `[root]` を除く実フォルダ数。フォルダ台帳は管理行として `[root]` を追加するため、台帳行数は `全フォルダ + 1`。

## 1. 公開方式

未確認 / 確認方法: オーナーまたはサーバー管理者に、現在のNASフォルダが直接公開なのか、別サーバーへFTP/rsync等でアップロードしているのかを確認する。

確認済みの事実: PHP内のinclude先にはサーバー上の絶対パスが使われているため、NASパスだけで公開方式は断定しない。

## 2. 本番URL / PHPバージョン / Webサーバー

| 項目 | 状態 | 確認方法 |
|---|---|---|
| 本番URL | 未確認 | オーナー確認、またはサーバー設定/公開中URLを確認 |
| PHPバージョン | 未確認 | 本番サーバーの管理画面またはphpinfo相当の安全な確認手段で確認 |
| Webサーバー種別 | 未確認 | 本番サーバーの管理画面、レスポンスヘッダ、契約情報で確認 |

## 3. 動作確認手順

未確認 / 確認方法: テスト環境の有無をオーナー確認する。テスト環境がある場合は、改修前バックアップ、差分確認、テスト環境反映、主要ページ表示確認、フォーム/動的ページ確認、ログ/DB値の非転記確認の順で行う。

テスト環境がない場合: 本番反映前にバックアップを取り、反映ファイルを限定し、反映直後にトップ、一覧、詳細、問い合わせ、サイトマップを確認する。

## 4. 変更禁止/要承認ファイル

| ファイル | 判定 | 理由 |
|---|---|---|
| `create.php` | 変更禁止 | 認証値とファイル生成処理に関わる。値は転記禁止。 |
| `includefile/dataset_base.php` | 要承認 | 全ページ生成の共通入口で、DB/外部設定読込にも関わる。 |
| `.htaccess` | 要承認 | 公開ルート設定。URL/アクセス制御へ影響する。 |
| `includefile/class.hpgcoder2.php` | 要承認 | 置換エンジン。多数ページに影響する。 |
| `includefile/dataset_*.php` | 要承認 | ページ別表示データ。変更範囲確認が必要。 |
| `source/system.html` | 要承認 | 決済/外部連携/hidden値を含む可能性。値は転記禁止。 |
| `log`配下 | 変更禁止 | ログ本文は転記禁止。削除も承認なし禁止。 |

## 5. DB接続定義の所在

所在のみ記載。値は記載しない。

| 種別 | パス | 状態 |
|---|---|---|
| require元 | `includefile/dataset_base.php` | 確認済み |
| 外部設定候補 | `/home/firststar/public_html/group/control/includefile/incfiles_vv.php` | 未確認 / 確認方法: 本番サーバー上で所在と役割を確認。値は転記しない。 |
| セッション設定候補 | `/home/firststar/public_html/group_test/control/includefile/setting_session_vv.php` | 未確認 / 確認方法: 本番サーバー上で所在と役割を確認。値は転記しない。 |

## 6. バックアップ手順

既存慣例: `HP/codex/area/backups` に、対象ファイル名 + 変更理由 + 日付の形で保存された実績あり。

推奨ルール: 改修前に `HP/codex/area/backups/<元ファイル名>.before-<作業名>-YYYYMMDD.<ext>` の形式で管理コピーを作る。

未確認 / 確認方法: 今後の正式な保管先を `HP/codex/area/backups` 継続でよいかオーナー確認する。

## 7. 管理資料の再生成手順

正本: `HP/codex/scripts/generate_candy_management_docs.py`

現在確認済みのローカル絶対パス: `H:\Data\01_FSG\candy\HP\codex\scripts\generate_candy_management_docs.py`

実行コマンド:

```powershell
python ".\HP\codex\scripts\generate_candy_management_docs.py"
```

Python未導入環境: PATH上の `python` または `py` を導入する。Codex環境では `Invoke-CandyDocsMaintenance.ps1` がCodexランタイムPythonへフォールバックする。
必要バージョン/依存: Python 3.9以上。標準ライブラリのみ使用し、外部パッケージ依存なし。
補足: `HP/codex/scripts/Invoke-CandyDocsMaintenance.ps1` は互換用ラッパーのみ。管理資料の生成・出力ロジックは正本Pythonに集約する。
