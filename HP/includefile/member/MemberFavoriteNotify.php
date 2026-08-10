<?php

/**
 * お気に入り女の子の出勤通知
 */
class MemberFavoriteNotify
{
    /** @var MemberDb */
    private $mdb;

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    /**
     * 本日出勤のお気に入りを検出し、通知レコード作成＋メール送信
     * @return array{created:int,mailed:int,errors:int}
     */
    public function processToday()
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $ctx = MemberGirlSchedule::businessContext();
        $y = (int)$ctx['y'];
        $m = (int)$ctx['m'];
        $d = (int)$ctx['d'];
        $today = sprintf('%04d-%02d-%02d', $y, $m, $d);
        $created = 0;
        $mailed = 0;
        $errors = 0;

        $schedules = $this->mdb->fetchAll(
            "SELECT * FROM girls_schedule"
            . " WHERE club_id = {$clubId}"
            . " AND year = {$y} AND month = {$m} AND day = {$d}"
            . " AND (type = '0' OR type = '1' OR type = '6')"
            . " AND views >= '0' AND status = '1'"
        );

        foreach ($schedules as $sch) {
            $girlsId = isset($sch['girls_id']) ? (int)$sch['girls_id'] : 0;
            if ($girlsId <= 0) {
                continue;
            }

            $disp = MemberGirlSchedule::resolveDisplay(array(0 => $sch), $ctx);
            $label = $disp['schedule_label'] !== '' ? $disp['schedule_label'] : '出勤';
            $labelEsc = $this->mdb->escape($label);

            $favs = $this->mdb->fetchAll(
                "SELECT f.member_id, a.email, COALESCE(s.notify_mypage_info, 0) AS notify_on"
                . " FROM customers_favorites f"
                . " INNER JOIN customers_accounts a ON a.id = f.member_id AND a.status = 1"
                . " LEFT JOIN customers_notification_settings s ON s.member_id = f.member_id"
                . " WHERE f.club_id = {$clubId} AND f.girls_id = {$girlsId}"
            );

            foreach ($favs as $fav) {
                $memberId = (int)$fav['member_id'];
                $exists = $this->mdb->fetchOne(
                    "SELECT id, mail_sent FROM customers_favorite_schedule_notices"
                    . " WHERE member_id = {$memberId} AND girls_id = {$girlsId} AND schedule_date = '{$today}' LIMIT 1"
                );
                if ($exists === null) {
                    $ok = $this->mdb->query(
                        "INSERT INTO customers_favorite_schedule_notices"
                        . " (club_id, member_id, girls_id, schedule_date, schedule_label, is_read, mail_sent)"
                        . " VALUES ({$clubId}, {$memberId}, {$girlsId}, '{$today}', '{$labelEsc}', 0, 0)"
                    );
                    if ($ok) {
                        $created++;
                        $exists = $this->mdb->fetchOne(
                            "SELECT id, mail_sent FROM customers_favorite_schedule_notices"
                            . " WHERE member_id = {$memberId} AND girls_id = {$girlsId} AND schedule_date = '{$today}' LIMIT 1"
                        );
                    } else {
                        $errors++;
                        continue;
                    }
                }

                if ($exists && (int)$exists['mail_sent'] === 0 && (int)$fav['notify_on'] === 1 && !empty($fav['email'])) {
                    $girl = $this->mdb->fetchOne("SELECT name, no FROM girls_data WHERE id = {$girlsId} LIMIT 1");
                    $girlName = $girl ? (string)$girl['name'] : ('ID:' . $girlsId);
                    $profileUrl = '';
                    if ($girl && !empty($girl['no'])) {
                        $profileUrl = 'girls.php?no=' . rawurlencode($girl['no']);
                    }
                    $subject = '【CANDY】お気に入りの女の子が出勤登録されました';
                    $body = $girlName . " さんが出勤登録されました。\n"
                        . "出勤情報: {$label}\n"
                        . ($profileUrl !== '' ? ("プロフィール: " . $profileUrl . "\n") : '');
                    if (MemberMail::send($fav['email'], $subject, $body)) {
                        $nid = (int)$exists['id'];
                        $this->mdb->query("UPDATE customers_favorite_schedule_notices SET mail_sent = 1 WHERE id = {$nid}");
                        $mailed++;
                    } else {
                        $errors++;
                    }
                }
            }
        }

        return array('created' => $created, 'mailed' => $mailed, 'errors' => $errors);
    }

    public function unreadCount($memberId)
    {
        $memberId = (int)$memberId;
        $row = $this->mdb->fetchOne(
            "SELECT COUNT(*) AS cnt FROM customers_favorite_schedule_notices"
            . " WHERE member_id = {$memberId} AND is_read = 0"
        );
        return $row ? (int)$row['cnt'] : 0;
    }

    public function listUnread($memberId, $limit = 20)
    {
        $memberId = (int)$memberId;
        $limit = min(50, max(1, (int)$limit));
        return $this->mdb->fetchAll(
            "SELECT n.*, g.name AS girl_name, g.no AS girl_no"
            . " FROM customers_favorite_schedule_notices n"
            . " LEFT JOIN girls_data g ON g.id = n.girls_id"
            . " WHERE n.member_id = {$memberId} AND n.is_read = 0"
            . " ORDER BY n.created_at DESC LIMIT {$limit}"
        );
    }

    public function markAllRead($memberId)
    {
        $memberId = (int)$memberId;
        return (bool)$this->mdb->query(
            "UPDATE customers_favorite_schedule_notices SET is_read = 1 WHERE member_id = {$memberId} AND is_read = 0"
        );
    }
}
