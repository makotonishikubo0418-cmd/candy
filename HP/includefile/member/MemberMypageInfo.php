<?php

class MemberMypageInfo
{
    /** @var MemberDb */
    private $mdb;

    /** FSG: 一覧最大5件 */
    const LIST_LIMIT = 5;

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    /**
     * @return string
     */
    private function publishedSqlWhere($alias = 'i')
    {
        $clubId = (int)MEMBER_CLUB_ID;
        return "{$alias}.club_id = {$clubId}"
            . " AND {$alias}.status = 1"
            . " AND ({$alias}.publish_from IS NULL OR {$alias}.publish_from <= NOW())"
            . " AND ({$alias}.publish_to IS NULL OR {$alias}.publish_to >= NOW())";
    }

    /**
     * @param mixed $date
     * @return string
     */
    public static function formatDisplayDate($date)
    {
        if ($date === null || $date === '') {
            return '';
        }
        $ts = strtotime((string)$date);
        if ($ts === false) {
            return (string)$date;
        }
        return date('Y年m月d日', $ts);
    }

    /**
     * @param int $memberId
     * @param int $page
     * @param int $perPage
     * @return array{items:array,total:int,unread_count:int}
     */
    public function listForMember($memberId, $page = 1, $perPage = null)
    {
        $memberId = (int)$memberId;
        $page = max(1, (int)$page);
        if ($perPage === null || (int)$perPage <= 0) {
            $perPage = self::LIST_LIMIT;
        }
        // FSG: 最大5件（ページングしても先頭5件のみ）
        $perPage = min(self::LIST_LIMIT, max(1, (int)$perPage));
        $offset = ($page - 1) * $perPage;
        if ($offset >= self::LIST_LIMIT) {
            return array('items' => array(), 'total' => min(self::LIST_LIMIT, $this->publishedTotal()), 'unread_count' => $this->unreadCount($memberId));
        }
        if ($offset + $perPage > self::LIST_LIMIT) {
            $perPage = self::LIST_LIMIT - $offset;
        }
        $where = $this->publishedSqlWhere('i');

        $totalAll = $this->publishedTotal();
        $total = min(self::LIST_LIMIT, $totalAll);
        $unread = $this->unreadCount($memberId);

        $sql = "SELECT i.*,"
            . " (r.id IS NOT NULL) AS is_read"
            . " FROM customers_mypage_info i"
            . " LEFT JOIN customers_mypage_info_read r ON r.info_id = i.id AND r.member_id = {$memberId}"
            . " WHERE {$where}"
            . " ORDER BY i.created_at DESC, i.id DESC"
            . " LIMIT {$offset}, {$perPage}";

        $rows = $this->mdb->fetchAll($sql);
        // display_date がある場合は表示日優先で並べ替え（PHP側）
        usort($rows, function ($a, $b) {
            $da = !empty($a['display_date']) ? $a['display_date'] : (isset($a['created_at']) ? $a['created_at'] : '');
            $db = !empty($b['display_date']) ? $b['display_date'] : (isset($b['created_at']) ? $b['created_at'] : '');
            return strcmp((string)$db, (string)$da);
        });

        $items = array();
        foreach ($rows as $row) {
            $items[] = $this->formatListItem($row);
        }

        return array('items' => $items, 'total' => $total, 'unread_count' => $unread);
    }

    private function publishedTotal()
    {
        $where = $this->publishedSqlWhere('i');
        $countRow = $this->mdb->fetchOne("SELECT COUNT(*) AS cnt FROM customers_mypage_info i WHERE {$where}");
        return $countRow ? (int)$countRow['cnt'] : 0;
    }

    /**
     * @param array $row
     */
    private function formatListItem($row)
    {
        $displayRaw = !empty($row['display_date']) ? $row['display_date'] : (isset($row['created_at']) ? $row['created_at'] : '');
        return array(
            'info_id' => (int)$row['id'],
            'title' => $row['title'],
            'category' => $row['category'],
            'display_date' => $displayRaw,
            'display_date_label' => self::formatDisplayDate($displayRaw),
            'is_read' => !empty($row['is_read']),
            'publish_from' => isset($row['publish_from']) ? $row['publish_from'] : null,
            'publish_to' => isset($row['publish_to']) ? $row['publish_to'] : null,
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        );
    }

    /**
     * 許可タグのみ残す簡易サニタイズ
     */
    public static function sanitizeHtmlComment($html)
    {
        $html = (string)$html;
        if ($html === '') {
            return '';
        }
        $allowed = '<p><br><br/><b><strong><i><em><u><a><ul><ol><li><span><div>';
        return strip_tags($html, $allowed);
    }

    /**
     * @param int $memberId
     * @param int $infoId
     * @return array|null
     */
    public function getDetailForMember($memberId, $infoId, $markRead = true)
    {
        $memberId = (int)$memberId;
        $infoId = (int)$infoId;
        $where = $this->publishedSqlWhere('i');

        $row = $this->mdb->fetchOne(
            "SELECT i.* FROM customers_mypage_info i WHERE i.id = {$infoId} AND {$where} LIMIT 1"
        );
        if ($row === null) {
            return null;
        }

        if ($markRead) {
            $this->markRead($memberId, $infoId);
        }

        $readRow = $this->mdb->fetchOne(
            "SELECT read_at FROM customers_mypage_info_read WHERE member_id = {$memberId} AND info_id = {$infoId} LIMIT 1"
        );

        $displayRaw = !empty($row['display_date']) ? $row['display_date'] : (isset($row['created_at']) ? $row['created_at'] : '');

        return array(
            'info_id' => (int)$row['id'],
            'title' => $row['title'],
            'body' => $row['body'],
            'image_url' => isset($row['image_url']) ? $row['image_url'] : '',
            'video_url' => isset($row['video_url']) ? $row['video_url'] : '',
            'html_comment' => self::sanitizeHtmlComment(isset($row['html_comment']) ? $row['html_comment'] : ''),
            'category' => $row['category'],
            'display_date' => $displayRaw,
            'display_date_label' => self::formatDisplayDate($displayRaw),
            'is_read' => $readRow !== null,
            'read_at' => $readRow ? $readRow['read_at'] : null,
            'publish_from' => $row['publish_from'],
            'publish_to' => $row['publish_to'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        );
    }

    /**
     * @param int $memberId
     * @param int $infoId
     */
    public function markRead($memberId, $infoId)
    {
        $memberId = (int)$memberId;
        $infoId = (int)$infoId;
        $where = $this->publishedSqlWhere('i');
        $exists = $this->mdb->fetchOne("SELECT id FROM customers_mypage_info i WHERE i.id = {$infoId} AND {$where} LIMIT 1");
        if ($exists === null) {
            return false;
        }

        $read = $this->mdb->fetchOne(
            "SELECT id FROM customers_mypage_info_read WHERE member_id = {$memberId} AND info_id = {$infoId} LIMIT 1"
        );
        if ($read !== null) {
            return true;
        }

        return (bool)$this->mdb->query(
            "INSERT INTO customers_mypage_info_read (member_id, info_id) VALUES ({$memberId}, {$infoId})"
        );
    }

    /**
     * @param int $memberId
     */
    public function unreadCount($memberId)
    {
        $memberId = (int)$memberId;
        $where = $this->publishedSqlWhere('i');
        $row = $this->mdb->fetchOne(
            "SELECT COUNT(*) AS cnt FROM customers_mypage_info i"
            . " LEFT JOIN customers_mypage_info_read r ON r.info_id = i.id AND r.member_id = {$memberId}"
            . " WHERE {$where} AND r.id IS NULL"
        );
        return $row ? (int)$row['cnt'] : 0;
    }
}
