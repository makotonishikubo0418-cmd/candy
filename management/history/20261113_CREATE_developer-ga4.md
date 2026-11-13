# Candy開発側Googleアナリティクスの分離設置

- Type: CREATE
- Start Date: 2026-11-13

## 目的

Candyの開発側アクセス解析を設置し、GoogleアナリティクスでTeam PROとは別のプロパティとして閲覧できるようにする。

## 対象範囲

- Team PROアカウント内のCandy開発専用GA4プロパティとWebストリームの作成。
- Candy共通PHPからの開発用Googleタグ追加、必要な検証、GitHub保存と本番設置。
- 既存の運営者用測定先を維持する。Team PROの既存プロパティへのCandyデータ送信は行わない。

## 完了条件

- Candy開発専用プロパティをTeam PROと切り替えて表示できる。
- 本番の対象ページに開発用タグが1回出力され、既存の運営者用タグが維持されている。
- テスト環境・直接ホスト・非HTML応答に開発用タグを追加しない。
- GitHub、本番反映、計測受信の確認結果を記録する。

## 初期情報

- ユーザーは「設置を実行」「アナリティクス内でTeamPROと分けて見れるようにしろ」と指示した。
- 既存の運営者用測定IDは `G-0VBTBPHDD2`。Team PROプロパティは `426194011`、アカウントは `301356253`。
- 共通PHPは `HP/includefile/dataset_base.php`。既存タグはsource HTMLにある。
