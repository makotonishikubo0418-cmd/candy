# ギャップ萌えブログ見出し接頭辞の本番反映

- History: [`20261106_MODIFY_gap-moe-heading.md`](../20261106_MODIFY_gap-moe-heading.md)
- Record Date: 2026-11-06
- Sequence: 2
- Status: Completed

## 記録

### 確認済み事実

- Commit `65872f6915dc827b16a8bcc009b6ed1f1e3077d0` を `main` へ push し、GitHub `main` の SHA と一致した。
- Actions run `42196806830` は success。比較対象は対象HTML 1件、削除0件、除外なし。`PLAN_TOKEN` は `16dbe11a91218f5e4a6e4fbb4349ea41f38bdcd25a64bf5e3d81dceac0a0a76f`。`VERIFIED 1/1` のあと SHA-256 検証済みで `SUCCESS`。入口契約は `ENTRY_CONTRACT_OK`。https://github.com/makotonishikubo0418-cmd/candy/actions/runs/42196806830
- 本番記事は HTTP 200。H2 と目次は修正後の文言で各1件、`導入｜` は0件。目次のリンク先は `#scene1`。
- 本番の headless Chrome 計測では、幅1280と390で見出しは H2、文字色は `rgb(53, 21, 35)`、見出し内の横あふれは0、ページの横スクロールは0。

### 結果

- 公開URL https://www.55810.com/kagoshima-deliveryhealth-blog-gap-moe.php の対象見出しから「導入｜」が削除された状態を確認した。

## 現在

- Remaining Work: None
- Next Action: None
