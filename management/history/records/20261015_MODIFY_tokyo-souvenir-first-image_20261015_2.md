# 1枚目の修正版画像の本番公開完了

- History: [20261015_MODIFY_tokyo-souvenir-first-image.md](../20261015_MODIFY_tokyo-souvenir-first-image.md)
- Record Date: 2026-10-15
- Sequence: 2
- Status: Completed

## 記録

### 結果

- 公開コミット3cc045fa866f7217cc6a4190db9df627eeb555d4をGitHub mainへPushし、ライブSHA一致を確認。
- [Actions 38654502850](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38654502850)成功。主画像と記事HTMLの2ファイル・410645 bytesを配信。2/2 SHA256検証、バックアップ整理を含む配信処理、ENTRY_CONTRACT_OKを確認。削除0件。
- 実配信トークン: 3cf7e817fa235a08ba1fc29f0642aef48ecea8679bb2fb8702715f849be20a6f。
- [公開ページ](https://www.55810.com/kagoshima-deliveryhealth-blog-tokyo-souvenir.php)はHTTP 200。本文・OGP・JSON-LDの3参照が?v=35c02055を含む。
- 画像の正規URL・版指定URLの両方がHTTP 200、image/jpeg、392065 bytes。両方のSHA256は35c02055d4379c49b8ff9b1b19d16fcddfed5dc79bb88f0c5d28ff69f2cae433で原本と一致。
- 本番PC幅1440pxとスマートフォン幅390pxで修正版を目視。img.complete=true、自然寸法1000×750、横はみ出しなし。スマートフォン表示390×292.5、コンソールエラー0件。
- HTMLは画像URL3箇所だけの変更、画像2・3は前コミットとバイト一致。既存の別作業中candy_dominant_girls本文も保存前バイトと一致。
- 生成管理書を更新。対象tokyo-souvenirはstructure=COMPLETE、seo=OK、images=OK、issues=NONE。全体チェックは既存candy_dominant_girlsのsitemap_lastmod_or_structure指摘が残り、全体成功とは扱っていない。

## 現在

- Remaining Work: None
- Next Action: None
