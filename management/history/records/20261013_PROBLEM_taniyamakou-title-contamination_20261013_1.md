# 谷山港の原稿・生成処理・タイトル検査の修正結果

- History: [20261013_PROBLEM_taniyamakou-title-contamination.md](../20261013_PROBLEM_taniyamakou-title-contamination.md)
- Record Date: 2026-10-13
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 正規原稿 `Text_area_data/分類_20260716_115215/01_間違い無し/谷山港_テンプレート.txt` の18行目で、区切り線の末尾に余分な `s` が存在した。厳密な区切り判定に一致せず、title取得が次のdescription項目と説明文を吸収していた。
- 本文向けの改行変換処理がtitleにも使われ、生成HTMLのtitleでは `<br>`、og:titleでは実改行として混入した。既存検査は不正な原稿との一致や値の存在だけを確認していたため通過していた。
- 原稿の同形の区切り線異常は、調べた全158件中、谷山港の1件だった。

### 対応

- 原稿の余分な `s` を除去し、修正原稿から正規テンプレートで谷山港HTMLを再生成した。生成前後の差分はtitleとog:titleの2箇所だけで、本文・画像・店舗・リンク・JSON-LDは一致した。両タイトルは `鹿児島市谷山港で呼べるデリヘル｜対応店舗・ホテル情報`。
- `candy_area_page.py` はヘッダーの不正区切りと複数行・不正titleを生成前に拒否する。title出力は本文の改行変換から切り離した。生成済み検査はtitleとog:titleの内容と原稿一致を独立確認する。
- `candy_page_common.py` と `candy_site_state.py` で、タイトルの個数・空値・HTMLやコメント・文字参照で符号化されたタグ・区切り線・原稿項目名の混入を検出する。既存の動的値と検査適用除外は維持した。
- `.github/scripts/candy_release_check.py` でも同種の混入を検出し、HTML解析時に消えていたtitle内タグ・コメント・処理命令を異常として保持する。空タグを含むog:titleの重複も検出する。
- 既存の3試験ファイルに回帰ケースを追加し、エリア生成仕様と共通SEO仕様の該当箇所を更新した。谷山港URLだけのsitemap lastmodを2026-10-13へ同期し、正規生成処理で管理資料10件中7件を更新した。

### 結果

- 谷山港 `candy-area check --require-php`: `CHECK_OK`、`PHP_LINT=PASSED`。
- 修正HTMLを公開SEO検査関数へ渡すオフライン検証: 問題0件。実際の本番HTTP応答の検証ではない。
- 全158エリア原稿の新しいtitle入力検査: 問題0件。稼働分類のローカル256ソースに対する共通タイトル検査: 問題0件。
- `test_candy_release_check.py`: タイトル、全件検査への伝播、公開入口・保護経路の回帰試験成功。
- `test_candy_site_state_metadata.py`: 成功。titleとog:titleの独立検出、動的プロフィールと既存の適用除外を確認。
- `test_candy_tooling_recovery.py`: 27試験中26成功。今回追加した3試験は成功。既存の `test_real_build_and_repeat_in_isolated_non_git_fixture` のホテルサブケース1件は掲載順の期待値で失敗した。
- 上記失敗は変更前のHEAD `d7b3ff7c0492529c437ceddc39788bd62171cc60` の共通・エリア・台帳・試験コードをメモリ上で読み込み、同じ1試験だけ実行して同じ結果を再現した。既存アトリエの再生成後もホテル一覧末尾は `hoteltaisei` だが、試験は `businesshotelatelier` を期待している。ホテル生成処理・一覧・トップ・当該原稿はHEADと一致しており、今回の変更に起因する失敗ではない。対象外のため修正していない。
- `candy-site-state check`: `CHECK=OK documents=10`。生成TSVの実データ差分はSEO表の谷山港タイトル1件で、ページ台帳262行の変更は検証元メタデータ更新のみ。
- 独立レビューで重大な指摘なし。本文の改行動作を維持し、不正な原稿と出力が一致していても拒否することを確認した。
- Gitのステージ・コミット・Push・ブランチ変更、DB操作、本番公開は実施していない。本番への修正反映は未実施であり、本記録の完了はローカルの修正・検証範囲を指す。

## 現在

- Remaining Work: None（本案件のローカル修正範囲）。本番反映と既存ホテル試験の修正は対象外。
- Next Action: None
