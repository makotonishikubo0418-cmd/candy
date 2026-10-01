# 店長おすすめ 本番での段階進行と専用2表の作成確認

- History: [20260929_INVESTIGATE_manager-recommendation-targets.md](../20260929_INVESTIGATE_manager-recommendation-targets.md)
- Record Date: 2026-09-30
- Sequence: 1
- Status: Waiting for Response

## 記録

### 確認済み事実

- ユーザー提示の本番確認結果で、`candy_recommendations` と `candy_recommendation_settings` の2表が存在し、両方ともInnoDB・utf8mb4_unicode_ciであることを確認した。設定行は1行で `club_id=2, schema_version=1, migration_ready=0, revision=0`、おすすめ登録件数は0。前記録10で提示した確認SQLの期待値に一致する。
- この結果はユーザーのphpMyAdmin出力に基づく。全列・全インデックス、トリガー、アプリ保存・表示、画像保存・公開、同時更新の正常性を確認したものではない。認証トークン等を含む画面リンクは保存しない。
- ローカルの既存抽出スクリプト `control/codex/project_management/investigation/candy_manager_recommendation/export_legacy.py` をDB接続なしで実行し、`Candy/HP/source/index.html` の12カードとローカル24画像の存在検証が成功した。掲載順の公開番号は `827, 106, 1361, 1164, 1380, 30, 101, 110, 9, 1248, 69, 1016`。本番HTMLが現在も同じ12件であることや、人物IDとの対応はこの確認に含まれない。
- 両リポジトリのローカル `candy_recommendation_config.php` は `enabled=false` のまま。

### ユーザーの決定と適用範囲

- 検証先として `fsg_db_test` を使用する提案に対し、ユーザーは「いいえ、本番で進めます」と指示した。`fsg_db_test` は利用せず、対象を本番 `fsg_db` として段階的に準備・確認する。
- DB操作はユーザー自身がSQLで実行し、アシスタントは提示・結果確認を担当する分担を維持する。本指示をアシスタントによるDB接続・変更、Git操作、配置・公開、架空データ追加、実在の女の子の非公開・削除試験の許可として拡張しない。
- 別DBでの試験を実施しない方針を、解除処理・混在engine・並行更新・復旧の試験済み／正常確認済みと読み替えない。これらの未確認は残す。
- 次は初期移行候補の人物対応を本番で読み取る。表示切替・自動解除トリガーの投入・データ移行は今回のSQLに含めない。新設2表を再作成せず、`migration_ready=0` を維持する。

### 次に提示する本番読取SQL

```sql
SELECT
    g.no AS public_no,
    g.name AS girl_name,
    g.id AS girls_id,
    g.cast_id,
    g.status AS girl_status,
    c.status AS cast_status,
    COALESCE(p.publish_status, 0) AS page_published,
    CASE
        WHEN TRIM(COALESCE(p.profile_hobby, '')) = '' THEN 0
        ELSE 1
    END AS hobby_registered
FROM `fsg_db`.`girls_data` AS g
LEFT JOIN `fsg_db`.`cast_mast` AS c
    ON c.id = g.cast_id
LEFT JOIN `fsg_db`.`girls_candy_page_content` AS p
    ON p.girls_id = g.id AND p.club_id = 2
WHERE g.club_id = 2
  AND g.no IN (827,106,1361,1164,1380,30,101,110,9,1248,69,1016)
ORDER BY FIELD(g.no,827,106,1361,1164,1380,30,101,110,9,1248,69,1016),
         g.id;
```

- 取得対象はCANDYの上記公開番号に対応する行だけ。公開用掲載名、ID、掲載状態、趣味の登録有無を取得し、実名・連絡先・パスワード・趣味本文は取得しない。SQLはSELECTのみで、状態による行除外は行わず、非公開・人物欠落・重複も確認対象とする。
- 12件に満たない場合は欠落、同じ公開番号が複数あれば重複を疑い、受領結果を照合する。件数だけで人物対応の正常性を確定しない。趣味の登録有無と公開状態は別々の列として確認する。

### 実施結果とGit確認

- 本ターンは必要なローカル資料・コードの読取、既存抽出スクリプトの実行、履歴1ファイルの新規作成のみ。アプリ・SQL原本・既存履歴は変更せず、DB接続・SQL実行・Git状態変更・公開は行っていない。
- 本日初回の検証で対象リポジトリは `C:\Codex\FSG\Candy` と `C:\Codex\FSG\control` の2つ。各配下のGitルートはそれぞれ1つだった。既存の未コミット改修・未追跡ファイルを保持し、継続対象の既存mainから切り替えていない。
- Candy: originは `https://github.com/makotonishikubo0418-cmd/candy.git`。ローカル唯一のmainとGitHub mainは `11fdcd1d3f6e53e6800457d06ec7f61d547058d1` で一致（ahead/behind 0/0）。GitHubのみの `feature/member-loyalty-mypage` は `933bb909c54f6fdb688dea69a2b9e505606bd0c0`。managementブランチはNOT_PRESENT。
- Control: originは `https://github.com/makotonishikubo0418-cmd/fsg_control.git`。ローカル・GitHubともmainのみで `4d2f74444ab2ecf06fea2712fe57751072dfe92b` に一致（ahead/behind 0/0）。managementブランチはNOT_PRESENT。
- 記録前に同日既存記録がないことを確認し、連番1を使用した。

## 現在

- Remaining Work: 上記SELECT結果の受領と人物・公開番号・掲載状態・趣味の照合。本番HTMLとの初期移行候補の整合、解除処理の本番適用前審査と復旧手順、未実施の混在engine・並行更新等の検証の扱い、画像処理・保存・公開経路、初期移行、両側配置・切替・回帰確認、正式監査・Git公開も未了。テストDB利用はユーザーが採用しなかったため、提案を既定手順として再要求しない。
- Next Action: ユーザーによる上記SELECTの実行結果を受け取り、初期移行に使用する本人対応と不足項目を確定する。新機能はまだ有効化しない。
