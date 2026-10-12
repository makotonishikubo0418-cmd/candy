# 東京土産ブログのGitHub同期と本番公開確認

- History: [20261013_CREATE_tokyo-souvenir-blog.md](../20261013_CREATE_tokyo-souvenir-blog.md)
- Record Date: 2026-10-13
- Sequence: 2
- Status: Verification Pending

## 記録

### 結果

- 公開Commit `76652ab9caf4814b3a13200f1647f3cb9f209a2e` をmainへPushし、ローカルとlive GitHubのSHA一致、ahead/behind=0/0、ルート作業ツリーが空であることを確認した。
- [Actions 38309584419](https://github.com/makotonishikubo0418-cmd/candy/actions/runs/38309584419) は成功。対象HPファイル11件を配信し、全件のサーバーSHA-256照合が成功した。削除0件。公開PHP、専用dataset、共通dataset、本文、専用CSS、画像3点、一覧、トップページ、サイトマップが対象。
- [公開記事](https://www.55810.com/kagoshima-deliveryhealth-blog-tokyo-souvenir.php) はHTTP 200。公開本文のタイトル、H1=1、本文節=4、記事画像=3を確認した。
- トップページとブログ一覧はいずれもHTTP 200。現行全9記事のURL、表示名、順序が一致し、東京土産の記事は各1件。サイトマップ登録も1件。
- 公開専用CSSは改行形式を除いてローカルと一致。公開画像3点はバイト単位でローカルと一致し、SHA-256は以下のとおり。
  - `_1.jpg`: `e991bb9a98bf29a87e2a0bd4b4b492d9b9db16801b973d2bb7f61dd836c09b00`
  - `_2.jpg`: `8db3f83d4e1e040db8d7c6948cbd270fa5cf671b3b64273bfcce4367c4fefa2d`
  - `_3.jpg`: `9a0cbf8a61cdf2597742901d26a13a7333ea12144de38ca2440082210d167a80`
- 本番入口のHTTP 200、title・canonical・H1、公開indexability、index.php・HTTP・非wwwの正規URLリダイレクト、直接ホストのnoindex検査に合格した。
- Chromeタブ作成を実行した結果は `Browser is not available: chrome`。接続ブラウザ一覧には内蔵ブラウザとMCP Appsだけがあり、Chrome接続はなかった。Chrome利用可能とする過去の回答には確認根拠がなかった。

## 現在

- Remaining Work: ChromeによるPC/SP実表示と実DOM画像寸法の確認。現時点で表示品質の検証完了とは扱わない。
- Next Action: Chrome接続が利用可能になった時点で、公開記事の表示確認を行う。
