# FAQ回答文の文字サイズ修正と本番確認

- History: [`20261008_CREATE_first-deliveryhealth-girl-choice-blog.md`](../20261008_CREATE_first-deliveryhealth-girl-choice-blog.md)
- Record Date: 2026-10-08
- Sequence: 8
- Status: Completed

## 記録

### 確認済み事実

- 通常本文は16px指定だったが、FAQ回答は `div.faq-answer` のため通常本文の指定対象外となり、小さい継承値で表示されていた。

### 対応

- FAQ回答だけに通常本文と同じ16pxを指定し、PCの行間を32px、スマートフォンの行間を30.4pxへ揃えた。
- CSS SHA-256先頭8文字 `3a728f66` を本文HTMLのCSS参照クエリへ設定した。
- コミット `44449543647ae3620c4f8c9a1935071b4a1e08a3` を `main` へPushした。
- GitHub Actions `CANDY Production Deploy` の実行 `37754692874` で、CSSと本文HTMLの2件だけを本番へ配信した。

### 結果

- GitHub Actionsは成功し、配信対象2件はサーバー上のSHA-256照合まで完了した。削除は0件だった。
- 本番URLはHTTP 200を返し、本番CSSのSHA-256 `3a728f66a2895bb02b950477ceb65c26d995de9b355840105f1fd461a1edfb95` は公開コミットと一致した。
- 本番PCではFAQ回答と通常本文がともに16px・32px行間、本番390×844pxではともに16px・30.4px行間だった。
- PC・スマートフォンとも横方向の表示超過は0で、ブラウザエラーおよび警告は0件だった。
- 質問見出し、他の本文、画像、CTA、リンク先は変更していない。

## 現在

- Remaining Work: None
- Next Action: None
