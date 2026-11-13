# 本番全階層の列挙とローカルHPの内容比較結果

- History: [20261113_INVESTIGATE_server-local-file-comparison.md](../20261113_INVESTIGATE_server-local-file-comparison.md)
- Record Date: 2026-11-13
- Sequence: 1
- Status: Completed

## 記録

### 確認済み事実

- 比較取得時間: 2026-11-13 10:15:25～10:16:35 JST。実質内容差15件は、その後の詳細取得時にもSHA-256を照合した。
- 本番ホストは `firststar.kir.jp`、対象は `/firststar/public_html/group/candy`。ローカル対象は `C:\Codex\FSG\Candy\HP`。
- ローカルは `main`、HEADは `65a83e7d59f15a85a16214c0bb0e54503e2e4db1`。調査開始時のルート全体に未コミット変更はなかった。今回、Git参照・履歴の変更操作は行っていない。
- 本番1,748通常ファイル・25ディレクトリ、ローカル1,757通常ファイル・25ディレクトリ。シンボリックリンクは双方0件。
- 同名の通常ファイル1,736件をすべてSHA-256で比較。1,308件はバイト一致、413件はCRLF/LFの違いのみ、15件はCRLFをLFへ統一しても内容が異なる。
- 本番のみ12ファイル、ローカルのみ21ファイル。ファイル比較の未確認・取得不能・内容除外は0件。
- `includefile/member/cacert.pem` は公開CAルート証明書の束と確認した。秘密鍵ではなく、比較結果は改行形式のみの差。ログ・認証情報・DB接続値の収集は行っていない。
- 比較前後の相対パス・種類・サイズ・更新時刻等の一覧に変化はなかった。日時の新旧だけで内容の優劣を判定していない。

### 実質内容が異なる15ファイル

以下の相対パスは本番Candy直下とローカルHP直下に対応する。

| 相対パス | 確認した主な差分 |
|---|---|
| `.htaccess` | ローカルにある `customers/index.php` 等の開発用リダイレクトへの `noindex, nofollow` 付与ルール5行が、本番にはない。 |
| `includefile/candy_recommendation_config.php` | `enabled` が本番 `true`、ローカル `false`。画像URLは同じ。 |
| `css/member_site.css` | 本番に会員画面の追加スタイルがある。 |
| `includefile/member/MemberApi.php` | 本番にloyalty・ログインボーナス・ガチャのAPI処理がある。 |
| `includefile/member/MemberAuth.php` | アイドルタイムアウト、セッション更新、永続性引数などが異なる。 |
| `includefile/member/MemberEvaluation.php` | 評価処理に差分がある。 |
| `includefile/member/MemberFavoriteNotify.php` | 本番に `siteBaseUrl()` 等の変更がある。 |
| `includefile/member/MemberHistory.php` | 本番に利用履歴の日時正規化、売上・コース情報の対応、並べ替え等の変更がある。 |
| `includefile/member/MemberMypageInfo.php` | 表示日判定、リンクと属性のサニタイズ処理等が異なる。 |
| `includefile/member/MemberProfile.php` | プロフィール処理に差分がある。 |
| `includefile/member/bootstrap.php` | 読み込む会員機能の構成等が異なる。 |
| `js/member.js` | 本番にloyalty・ログインボーナス・ガチャの画面処理、利用履歴表示等の変更がある。 |
| `source/member_login.html` | 会員ログイン画面に差分がある。 |
| `source/member_mypage.html` | 会員マイページに差分がある。 |
| `source/member_register.html` | 会員登録画面に差分がある。 |

### 片側のみのファイルとディレクトリ

本番のみ12ファイル:

- `includefile/member/MemberBirthday.php`
- `includefile/member/MemberGacha.php`
- `includefile/member/MemberLoginBonus.php`
- `includefile/member/MemberLoyalty.php`
- `.well-known/acme-challenge/` 内の8ファイル。通常デプロイ対象外の証明書検証用パスであり、内容の収集や削除は行っていない。

ローカルのみ21ファイル:

- `.gitignore`
- `.well-known/.htaccess`
- `docs/MEMBER_ARCHITECTURE.md`
- `docs/PHASE1_API.md`～`docs/PHASE6_API.md` の6ファイル。
- `sql/001_phase1_member.sql`
- `sql/002_phase2_member.sql`
- `sql/002_phase2plus_member.sql`
- `sql/003_phase3_member.sql`
- `sql/003_seed_candy_legal.sql`
- `sql/004_phase4_member.sql`
- `sql/004_seed_candy_info.sql`
- `sql/005_phase5_member.sql`
- `sql/006_phase6_member_profile.sql`
- `sql/007_fsg_eval_multi.sql`
- `sql/008_fsg_info_media.sql`
- `sql/009_fsg_favorite_schedule_notice.sql`

本番のみのディレクトリは `.well-known/acme-challenge` と `preview`、ローカルのみは `docs` と `sql`。`preview` は空。いずれもファイル件数とは別に集計した。

### 対応

- 専用READ-ONLY接続の `Files` は直下一覧までのため、全階層の収集には同一サーバーの既存通常SSH接続を使用した。厳格な既知ホスト鍵検証、非対話認証、転送禁止を維持し、Candyの指定ルートだけを参照した。鍵内容や認証値は取得・保存していない。
- 本番の列挙・SHA-256・改行正規化比較を読み取り処理だけで実行した。PHP実行・DB接続・サイトファイルの書換えは行っていない。
- バイト差428件をそのまま実質変更とせず、413件の改行差と15件の実質内容差に分離した。
- 完全な両側一覧、パス別の差分CSV、15件のテキスト差分を、調査PCの `C:\Users\teamp\.codex\artifacts\candy-server-local-20261113\` に保存した。これは調査PC上の添付証跡であり、Git管理されたサイトファイルや本番バックアップではない。

### 結果と未確認範囲

- 依頼された全階層のファイル比較は完了し、本番とローカルの不一致を確認した。
- 本番とローカルで会員機能の構成および一部設定が異なる。一括上書きの可否や、どちらを正本にするかは今回決定していない。
- 差分の導入経緯、各機能の動作、DBの適用状態は今回の比較では検証していない。SQLがローカルだけにあることはDB未適用の証拠ではない。
- 同期、復元、削除、デプロイ、Googleアナリティクス設定変更は行っていない。今回のリポジトリ変更は本案件の履歴3ファイルのみ。

## 現在

- Remaining Work: None（指示されたファイル差分調査）
- Next Action: None
