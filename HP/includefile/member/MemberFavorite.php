<?php

require_once __DIR__ . '/MemberGirlCard.php';

class MemberFavorite
{
    /** @var MemberDb */
    private $mdb;

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    /**
     * @param int $girlsId
     * @return array|null
     */
    public function findGirl($girlsId)
    {
        $girlsId = (int)$girlsId;
        $clubId = (int)MEMBER_CLUB_ID;
        return $this->mdb->fetchOne(
            "SELECT id, no, name, age, cast_id, image1, status FROM girls_data"
            . " WHERE id = {$girlsId} AND club_id = {$clubId} LIMIT 1"
        );
    }

    /**
     * @param int $memberId
     * @param int $page
     * @param int $perPage
     * @return array{items:array,total:int}
     */
    public function list($memberId, $page = 1, $perPage = 20)
    {
        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        $page = max(1, (int)$page);
        $perPage = min(50, max(1, (int)$perPage));
        $offset = ($page - 1) * $perPage;

        $countRow = $this->mdb->fetchOne(
            "SELECT COUNT(*) AS cnt FROM customers_favorites WHERE member_id = {$memberId} AND club_id = {$clubId}"
        );
        $total = $countRow ? (int)$countRow['cnt'] : 0;

        $sql = "SELECT f.girls_id, f.created_at,"
            . " g.no, g.name, g.name_kana, g.name_romaji, g.age, g.height, g.bust, g.cup,"
            . " g.waist, g.hip, g.newface, g.image1, g.status AS girl_status"
            . " FROM customers_favorites f"
            . " LEFT JOIN girls_data g ON g.id = f.girls_id AND g.club_id = f.club_id"
            . " WHERE f.member_id = {$memberId} AND f.club_id = {$clubId}"
            . " ORDER BY f.created_at DESC, f.id DESC"
            . " LIMIT {$offset}, {$perPage}";

        $rows = $this->mdb->fetchAll($sql);

        $rowsByGid = array();
        foreach ($rows as $row) {
            $rowsByGid[(int)$row['girls_id']] = $row;
        }
        $cards = MemberGirlCard::enrichByGirlsIds($this->mdb, $rowsByGid);

        $items = array();
        foreach ($rows as $row) {
            $gid = (int)$row['girls_id'];
            $item = isset($cards[$gid]) ? $cards[$gid] : MemberGirlCard::build($row);
            $item['created_at'] = $row['created_at'];
            $items[] = $item;
        }

        return array('items' => $items, 'total' => $total);
    }

    /**
     * @param int $memberId
     * @return int[]
     */
    public function getIds($memberId)
    {
        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        $sql = "SELECT girls_id FROM customers_favorites WHERE member_id = {$memberId} AND club_id = {$clubId} ORDER BY created_at DESC";
        $ids = array();
        foreach ($this->mdb->fetchAll($sql) as $row) {
            $ids[] = (int)$row['girls_id'];
        }
        return $ids;
    }

    /**
     * @param int $memberId
     * @param int $girlsId
     * @return array{status:int,message:string}
     */
    public function add($memberId, $girlsId)
    {
        $girl = $this->findGirl($girlsId);
        if ($girl === null) {
            return array('status' => -2, 'message' => '女の子が見つかりません');
        }
        if ((int)$girl['status'] !== 1) {
            return array('status' => -2, 'message' => '掲載中の女の子のみお気に入りに追加できます');
        }

        $memberId = (int)$memberId;
        $girlsId = (int)$girlsId;
        $clubId = (int)MEMBER_CLUB_ID;

        $exists = $this->mdb->fetchOne(
            "SELECT id FROM customers_favorites WHERE member_id = {$memberId} AND girls_id = {$girlsId} LIMIT 1"
        );
        if ($exists !== null) {
            return array('status' => 0, 'message' => '既にお気に入りに登録されています');
        }

        $sql = "INSERT INTO customers_favorites (club_id, member_id, girls_id) VALUES ({$clubId}, {$memberId}, {$girlsId})";
        if (!$this->mdb->query($sql)) {
            return array('status' => -1, 'message' => 'お気に入りの登録に失敗しました');
        }

        $this->mdb->audit($memberId, 'favorite_add', 'girls_id=' . $girlsId);
        return array('status' => 0, 'message' => 'お気に入りに追加しました');
    }

    /**
     * @param int $memberId
     * @param int $girlsId
     * @return array{status:int,message:string}
     */
    public function remove($memberId, $girlsId)
    {
        $memberId = (int)$memberId;
        $girlsId = (int)$girlsId;
        $clubId = (int)MEMBER_CLUB_ID;

        $sql = "DELETE FROM customers_favorites WHERE member_id = {$memberId} AND girls_id = {$girlsId} AND club_id = {$clubId}";
        if (!$this->mdb->query($sql)) {
            return array('status' => -1, 'message' => 'お気に入りの解除に失敗しました');
        }

        $this->mdb->audit($memberId, 'favorite_remove', 'girls_id=' . $girlsId);
        return array('status' => 0, 'message' => 'お気に入りを解除しました');
    }
}
