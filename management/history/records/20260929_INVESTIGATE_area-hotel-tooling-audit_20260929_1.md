# ホテル・エリア制作スクリプトの現行稼働監査結果

- History: [20260929_INVESTIGATE_area-hotel-tooling-audit.md](../20260929_INVESTIGATE_area-hotel-tooling-audit.md)
- Record Date: 2026-09-29
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 対象は `C:\Codex\FSG\Candy` のローカルファイル。実行入口は `management/scripts/`。
- 作業ブランチは既存の `main`。ローカルHEADと履歴記録前に読み取ったGitHub `main` は、ともに `b1c8b3fc1190c7119aaa55fd84367aee3bd52c92`。管理専用として指定されたブランチは `NOT_PRESENT`。ブランチ作成・切替はしていない。
- GitHubにはローカルに存在しない `feature/member-loyalty-mypage`（`933bb909c54f6fdb688dea69a2b9e505606bd0c0`）もあるが、今回の監査・履歴記録対象ではない。
- 開始時から存在する `AGENTS.md` と `management/INDEX.md` の変更は保持した。
- `codex/docs/`、`codex/data/`、`codex/scripts/` は存在しない。現行の対応先は `management/specs/`、`management/data/`、`management/scripts/`。

### 実行結果

以下の短縮コマンド名は `management/scripts/` 内の入口を指す。試験結果は今回のローカル環境に限定する。

| 対象 | 結果 | 判定の範囲 |
|---|---|---|
| `candy-hotel.cmd --help` / `candy-area.cmd --help` | 正常終了 | 入口の起動と基本モジュールの読込のみ |
| ホテル `audit-inputs` | 72件を分類。既存23、画像なし48、管理用1 | 画像判定の不足は別途確認。分類終了を全入力の使用可能判定としない |
| ホテル `audit-existing` | 既存23件。ファイル・共有登録・画像の存在項目は成立 | 本文や生成仕様との一致までは保証しない |
| ホテル既存23件の `run_check`（`--require-php` 相当） | 18件合格、5件不一致 | 合格18件はPHP構文検査も成功 |
| 同23原稿の `run_build`（`--dry-run` 相当） | 22件合格、1件停止 | メモリ内生成と登録更新の試験。ファイル書込・公開は未実施 |
| ホテル `self-test` | `SELF_TEST_OK` | 生成、可変項目、異常入力、登録整合の内蔵試験 |
| ホテル `legacy-self-test` | `LEGACY_TEXT_SELF_TEST=PASS` | 一時領域の旧形式変換試験。実原稿は変更していない |
| ホテル `image-self-test`（通常CMD入口） | `Pillow is required` で停止 | CMDが優先するローカルPython 3.12にPillowがない |
| 同画像試験（既存の同梱Pythonを直接指定） | `HOTEL_IMAGE_SELF_TEST=PASS` | 同梱Python 3.12.14 / Pillow 12.3.0では一時画像の生成・検査が成功。実画像は変更していない |
| エリア `audit-inputs --include-completion --render` | `INPUTS=0`、`RENDER_TARGETS=0`、正常終了 | 実際には分類サブフォルダにTextが158件。全件監査として使用不可 |
| エリア `target-next` | `no eligible new area page target`、`CHECKED_COUNT=0` | 旧パスの制作順リストを読めず候補なしになる。現行リストには `READY_CANDIDATE` 行が53件あるが、53件の実制作適格性を認定したものではない |
| エリア `related-check` | `FileNotFoundError` | 旧 `codex/data/CANDY_AREA_RELATED_LINKS.json` を参照 |
| 高麗町原稿のエリア `build --dry-run` | 同じ旧JSON参照で停止 | 生成開始前の読込で失敗 |
| 共通 `audit` | `AUDIT=OK`、148ページを集計 | 集計処理は動く。監査対象全体の適合を保証する表示ではない |
| 共通 `check` | 10文書すべて `content_drift` | 存在しない旧 `codex/docs/generated/` と比較しており、現行10文書の整合性を検査できていない |
| `.github/scripts/test_candy_site_state_metadata.py` | `ModuleNotFoundError: candy_site_state` | テストの読込先が旧 `codex/scripts/` |

### 原因と影響

1. **エリア監査の全件漏れ**: [candy_area_page.py](../../scripts/candy_area_page.py) の `run_audit_inputs` は入力直下と任意の `Completion/` しか走査しない。現状は直下0件、分類サブフォルダを含めると158件であり、空の対象を成功扱いする。
2. **エリア生成・選定の旧パス**: 同ファイルの `RELATED_LINKS_PATH` と、[candy_area_target_gate.py](../../scripts/candy_area_target_gate.py) の `QUEUE_PATH` が現行配置に対応していない。リスト欠落時も明示的なファイル不足ではなく空リストへ変換される。
3. **共通管理資料の誤った参照・出力先**: [candy_page_common.py](../../scripts/candy_page_common.py) の `DOCS_DIR` は旧 `codex/docs`。共通 `check` の比較先だけでなく `write` の出力先にも使われる。コード上、`write` は親ディレクトリを作成するため、実行すると旧配置を再作成する。`write` は今回実行していない。
4. **ホテル候補の画像判定不足**: 48件すべてについて、指定ファイル名の画像2枚が `Text_hotel_data/画像データ/` に実在する一方、公開側コピーはない。詳細と既存案件の現在状態は [採用元画像経路の再確認](20260920_PROBLEM_hotel-accepted-image-gap_20260929_1.md) に記録した。
5. **ホテル画像入口の環境選択**: [candy-hotel.cmd](../../scripts/candy-hotel.cmd) はPillowの有無を確認せずローカルPythonを優先する。同梱環境では画像自己テストが通るため、画像実装そのものが使用不能と断定しない。
6. **公開・回帰テストの旧パス**: [公開ワークフロー](../../../.github/workflows/candy-production-deploy.yml) のpreview/deploy両方の検証工程に、存在しない `codex/scripts/` のコンパイルと実行がある。[画像置換テスト](../../../.github/scripts/test_candy_area_image_replace.py) の対象パスも旧配置。現行チェックアウトを対象にすると検証工程を完了できない構成である。Actions自体は起動していない。

### ホテル既存ページとの不一致

| 原稿 / slug | 既存ページ検査の主な不一致 | 書込なし生成 |
|---|---|---|
| Hotel M / `hotelm` | 終端ボタン、見出し連番、スポット注意文、構造化データ、店舗ブロックの読取 | 合格 |
| HOTEL SERA / `hotelsera` | H1のホテル名強調 | 合格 |
| Hotel クキタ / `hotelkukita` | H1のホテル名強調 | 合格 |
| グリーンリッチホテル鹿児島天文館 / `greenrichkagoshimatenmonkan` | 終端ボタン、店舗ブロックの読取、一覧構造化データ | 合格 |
| ヴィラコスタ500 / `villacosta500` | 終端ボタン、スポット注意文、構造化データ、店舗ブロックの読取 | 一覧の既存項目を置換する位置が0件で停止 |

不一致5件を、そのまま本番ページ故障と断定しない。例えば店舗順の検査は `<!-- candy -->` などのコメントに依存しており、コメント形式が違う既存HTMLでも店舗自体は存在する。現行生成仕様と旧構造の扱いを区別する必要がある。既存ページの再生成・修正は行っていない。

### 結果

ホテルの生成・旧原稿変換など一部の中核機能は動くが、現行配置のまま制作・全件監査・公開を一貫運用できる状態ではない。使用再開には、旧パス、入力探索範囲、画像候補判定、Python環境選択、既存HTMLとの検査互換性、公開ワークフロー・テストの参照先を、それぞれ承認された修正範囲で扱う必要がある。

監査コマンド実行後、履歴記録前のファイル追加・削除は0件。通常ファイルのサイズ・更新時刻に変化はなく、隠しファイルは別途存在確認しGit差分に変化がないことを確認した。既存2ファイルの変更以外にGit差分は生じていない。今回の永続的な書き込みは、この監査と関連する履歴記録および案件一覧に限定した。

未実行: 実ページの書込生成、実画像の作成・置換・設置、`site-state write`、publish/resume、Git更新、Actions起動、FTP、本番HTTP・画面、DB。外部環境を含む正常稼働や公開完了は認定していない。

## 現在

- Remaining Work: None（依頼されたローカル稼働監査の範囲。検出した不具合の修正は含まない）
- Next Action: None（修正・公開は別の明示指示が必要）
