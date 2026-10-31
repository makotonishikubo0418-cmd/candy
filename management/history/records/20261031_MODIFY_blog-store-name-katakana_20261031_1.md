# ブログ店舗名のローカル統一と検証

- History: [20261031_MODIFY_blog-store-name-katakana.md](../20261031_MODIFY_blog-store-name-katakana.md)
- Record Date: 2026-10-31
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 作業先は C:/Codex/FSG/Candy の main。ローカルとライブGitHub mainはともに 24b1f868586806919685286f5693cee934110a73、ahead/behindは0/0。作業開始時の未追跡画像 HP/imgHtml/new_202601/blog/kagoshima-deliveryhealth-blog-fun-conversation_1.jpg は保持した。管理専用ブランチは存在しない。
- 現行ブログ18記事のHTML・公開PHP・dataset・管理台帳の集合が一致。対象外6記事に今回の置換対象はなかった。
- 短時間バイト記事のFAQ検査は変更前からISSUE。今回の表記変更では検査結果を変更していない。

### 対応

- 記事12ファイルの239箇所（alt 23箇所を含む）、一覧とTOPの各5箇所、合計249箇所を変更した。対象slugは autumn-winter-cuddle、beginner-job、candy_dominant_girls、course-time、double-work、girl-choice、maimai-profile、private-waiting-room、retort-curry、short-time-job、tokyo-souvenir、waiting-room-facilities。
- Text_blog_data直下の現行原稿 maimai-profile.txt、retort-curry.txt、waiting-room-facilities.txt、鹿児島_デリヘル_個室待機スペース.txt、鹿児島_デリヘル_女の子の選び方.txt、鹿児島_デリヘル_東京土産.txt の71箇所を同じ表記へ変更した。過去の原稿・履歴・バックアップを置換していない。
- HTMLを再生成せず、解析で特定した表示テキスト・メタ情報・JSON-LDの文字列・altのみを置換した。英語デザイン表記20箇所と共通著作権表記を保持した。
- 生成管理情報8ファイルを同期。既存のUpdatedとコード参照台帳のサイズ特例説明は再生成後に保持した。サイトマップ更新日は変更0件であり、サイトマップ自体は変更していない。

### 結果

- 保存した作業直前のバイト列と照合し、指定の置換以外の差分がないことを確認。タグ構造、コメント、改行、太字、表、CTA構造、URL、画像参照、ID、classを保持した。対象外のコード・原稿867ファイルはSHA-256一致。
- 対象表示領域のCandy残存0。JSON-LD 35ブロックの構文と変更前後の意味上の差分を検査し、表示文字列の置換以外は不変。SEO台帳の判定列はすべて変更前と一致し、更新された列はtitle・description・H1のみ。
- ブログ一覧18件、TOP最新15件のURL・表示名・順序が一致。
- ローカル静的HTMLプレビューをブラウザーで確認。12記事すべてで幅1440px・390pxの横スクロールなし、本文の対象英字残存なし。本文画像33枚はスクロール後にcurrentSrcと正の自然寸法を確認した。代表記事のPC・スマートフォン画面も目視確認した。PHP・DBを実行する本番動作は今回の確認範囲外。
- 再生成直後のsite-state検査は10文書一致。既存管理メタデータの復元後も、自動生成本文が期待値と完全一致することを確認した。生成器がUpdated・特例説明を出力しない既存制約は保持し、生成器自体は修正していない。
- Gitのステージ・コミット・Push、本番公開は実施していない。

## 現在

- Remaining Work: None（依頼されたローカル修正・検証の範囲）
- Next Action: None（GitHub保存・本番公開は別の明示指示が必要）
