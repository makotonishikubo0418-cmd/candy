<?php

class MemberEvaluation
{
    /** @var MemberDb */
    private $mdb;

    /** FSG評価項目キー（rating = 総合満足度） */
    public static $RATING_KEYS = array(
        'rating',
        'rating_service',
        'rating_friendliness',
        'rating_cleanliness',
        'rating_match',
        'rating_repeat',
    );

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    /**
     * cast_id ごとに girls_data を1件取得（店舗内・status=1・最新id優先）
     * @param int[] $castIds
     * @return array<int,array>
     */
    public function getGirlsMapByCastIds($castIds)
    {
        $castIds = array_values(array_unique(array_filter(array_map('intval', $castIds))));
        if (count($castIds) === 0) {
            return array();
        }
        $clubId = (int)MEMBER_CLUB_ID;
        $idList = implode(',', $castIds);
        $sql = "SELECT id, cast_id, name, no FROM girls_data"
            . " WHERE club_id = {$clubId} AND cast_id IN ({$idList}) AND status = 1"
            . " ORDER BY id DESC";
        $map = array();
        foreach ($this->mdb->fetchAll($sql) as $row) {
            $cid = (int)$row['cast_id'];
            if (!isset($map[$cid])) {
                $map[$cid] = $row;
            }
        }
        return $map;
    }

    /**
     * @param int $memberId
     * @param int[] $taskIds
     * @return array<int,array>
     */
    public function getEvaluationsByTaskIds($memberId, $taskIds)
    {
        $taskIds = array_values(array_unique(array_filter(array_map('intval', $taskIds))));
        if (count($taskIds) === 0) {
            return array();
        }
        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        $idList = implode(',', $taskIds);
        $sql = "SELECT * FROM customers_girl_evaluations"
            . " WHERE member_id = {$memberId} AND club_id = {$clubId} AND task_id IN ({$idList}) AND status = 1";
        $map = array();
        foreach ($this->mdb->fetchAll($sql) as $row) {
            $map[(int)$row['task_id']] = $row;
        }
        return $map;
    }

    /**
     * API/画面用に評価行を整形
     * @param array $row
     * @return array
     */
    public static function formatEvaluation($row)
    {
        $out = array(
            'rating' => (int)$row['rating'],
            'rating_service' => isset($row['rating_service']) ? (int)$row['rating_service'] : null,
            'rating_friendliness' => isset($row['rating_friendliness']) ? (int)$row['rating_friendliness'] : null,
            'rating_cleanliness' => isset($row['rating_cleanliness']) ? (int)$row['rating_cleanliness'] : null,
            'rating_match' => isset($row['rating_match']) ? (int)$row['rating_match'] : null,
            'rating_repeat' => isset($row['rating_repeat']) ? (int)$row['rating_repeat'] : null,
            'comment' => isset($row['comment']) ? $row['comment'] : '',
            'created_at' => isset($row['created_at']) ? $row['created_at'] : null,
            'updated_at' => isset($row['updated_at']) ? $row['updated_at'] : null,
        );
        return $out;
    }

    /**
     * @param int $memberId
     * @param int $taskId
     * @param array $ratings 各キー 1-5
     * @param string $comment
     * @param array $taskRow CTI task row
     * @return array{status:int,message:string}
     */
    public function save($memberId, $taskId, $ratings, $comment, $taskRow)
    {
        if (!MemberHistory::canEvaluateTask($taskRow)) {
            return array('status' => -2, 'message' => 'この利用履歴は評価できません');
        }

        $memberId = (int)$memberId;
        $taskId = (int)$taskId;

        $existing = $this->mdb->fetchOne(
            "SELECT id FROM customers_girl_evaluations WHERE member_id = {$memberId} AND task_id = {$taskId} AND status = 1 LIMIT 1"
        );
        if ($existing !== null) {
            return array('status' => -2, 'message' => 'この利用は評価済みのため修正できません');
        }

        $normalized = array();
        foreach (self::$RATING_KEYS as $key) {
            $val = isset($ratings[$key]) ? (int)$ratings[$key] : 0;
            if ($val < 1 || $val > 5) {
                return array('status' => -2, 'message' => 'すべての評価項目を選択してください');
            }
            $normalized[$key] = $val;
        }

        $castId = (int)$taskRow['cast_id'];
        $girlsMap = $this->getGirlsMapByCastIds(array($castId));
        if (!isset($girlsMap[$castId])) {
            return array('status' => -2, 'message' => '女の子情報が見つかりません（掲載終了の可能性があります）');
        }

        $girlsId = (int)$girlsMap[$castId]['id'];
        $clubId = (int)MEMBER_CLUB_ID;
        $commentEsc = $this->mdb->escape(mb_substr(trim($comment), 0, 1000));

        $sql = "INSERT INTO customers_girl_evaluations ("
            . "club_id, member_id, girls_id, cast_id, task_id,"
            . " rating, rating_service, rating_friendliness, rating_cleanliness, rating_match, rating_repeat,"
            . " comment, status"
            . ") VALUES ("
            . "{$clubId}, {$memberId}, {$girlsId}, {$castId}, {$taskId},"
            . " {$normalized['rating']}, {$normalized['rating_service']}, {$normalized['rating_friendliness']},"
            . " {$normalized['rating_cleanliness']}, {$normalized['rating_match']}, {$normalized['rating_repeat']},"
            . " '{$commentEsc}', 1"
            . ")";

        if (!$this->mdb->query($sql)) {
            // カラム未追加環境向けフォールバック（総合のみ）
            $sqlLegacy = "INSERT INTO customers_girl_evaluations (club_id, member_id, girls_id, cast_id, task_id, rating, comment, status)"
                . " VALUES ({$clubId}, {$memberId}, {$girlsId}, {$castId}, {$taskId}, {$normalized['rating']}, '{$commentEsc}', 1)";
            if (!$this->mdb->query($sqlLegacy)) {
                return array('status' => -1, 'message' => '評価の保存に失敗しました（sql/007_fsg_eval_multi.sql の適用を確認してください）');
            }
        }

        $this->mdb->audit($memberId, 'evaluation_save', 'task_id=' . $taskId . ' rating=' . $normalized['rating']);
        return array('status' => 0, 'message' => '評価を保存しました');
    }

    /**
     * AI受付・CTI参照用: 会員の評価履歴（新しい順）
     * @param int $memberId
     * @param int $limit
     * @return array
     */
    public function listRecent($memberId, $limit = 50)
    {
        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        $limit = min(100, max(1, (int)$limit));
        $rows = $this->mdb->fetchAll(
            "SELECT e.*, g.name AS girl_name, g.no AS girl_no"
            . " FROM customers_girl_evaluations e"
            . " LEFT JOIN girls_data g ON g.id = e.girls_id"
            . " WHERE e.member_id = {$memberId} AND e.club_id = {$clubId} AND e.status = 1"
            . " ORDER BY e.created_at DESC LIMIT {$limit}"
        );
        $out = array();
        foreach ($rows as $row) {
            $item = self::formatEvaluation($row);
            $item['girls_id'] = (int)$row['girls_id'];
            $item['cast_id'] = (int)$row['cast_id'];
            $item['task_id'] = (int)$row['task_id'];
            $item['girl_name'] = isset($row['girl_name']) ? $row['girl_name'] : '';
            $item['girl_no'] = isset($row['girl_no']) ? $row['girl_no'] : '';
            $out[] = $item;
        }
        return $out;
    }
}
