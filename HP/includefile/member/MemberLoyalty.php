<?php

/**
 * CTI 会員ランク・ポイント・クーポン（マイページ表示用・参照のみ）
 */
class MemberLoyalty
{
    /** @var array<string,string> */
    private static $DEFAULT_RANK_NAMES = array(
        'bronze' => 'ブロンズ',
        'silver' => 'シルバー',
        'gold' => 'ゴールド',
        'platinum' => 'プラチナ',
        'diamond' => 'ダイヤモンド',
    );

    /**
     * @return mysqli|null
     */
    private static function ctiConnection()
    {
        if (!function_exists('mysqli_init')) {
            return null;
        }
        $conn = mysqli_init();
        if (!$conn) {
            return null;
        }
        @mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 3);
        if (defined('MYSQLI_OPT_READ_TIMEOUT')) {
            @mysqli_options($conn, MYSQLI_OPT_READ_TIMEOUT, 8);
        }
        $ok = @mysqli_real_connect(
            $conn,
            MEMBER_CTI_HOST,
            MEMBER_CTI_USER,
            MEMBER_CTI_PASS,
            MEMBER_CTI_DB
        );
        if (!$ok) {
            return null;
        }
        @mysqli_set_charset($conn, 'utf8mb4');
        return $conn;
    }

    /**
     * @param int $guestId
     * @param int $clubId
     * @return array{status:int,message:string,data?:array}
     */
    public static function getSummary($guestId, $clubId)
    {
        $guestId = (int)$guestId;
        $clubId = (int)$clubId;
        if ($guestId <= 0) {
            return array(
                'status' => 0,
                'message' => '',
                'data' => self::emptyPayload(false, 'cti_not_linked'),
            );
        }

        $conn = self::ctiConnection();
        if (!$conn) {
            return array(
                'status' => 0,
                'message' => '',
                'data' => self::emptyPayload(false, 'cti_unavailable'),
            );
        }

        $member = self::fetchGuestMember($conn, $guestId);
        $rankDefs = self::fetchRankDefs($conn);
        $shop = self::fetchShopSettings($conn, $clubId);

        $held = self::balancePoints($conn, $guestId);
        $soonP = self::pointsExpiringSoon($conn, $guestId, 10);
        $couponHeld = self::couponBalanceYen($conn, $guestId);
        $soonC = self::couponsExpiringSoonYen($conn, $guestId, 10);

        mysqli_close($conn);

        $rankCode = isset($member['rank_code']) ? (string)$member['rank_code'] : 'bronze';
        if ($rankCode === '') {
            $rankCode = 'bronze';
        }
        $rankName = self::resolveRankName($rankCode, $rankDefs);
        $visitCount = isset($member['visit_count']) ? (int)$member['visit_count'] : 0;
        $lastVisitAt = !empty($member['last_visit_at']) ? (string)$member['last_visit_at'] : null;

        $usable = $held >= 1000 ? $held : 0;
        $shortfall = $held < 1000 ? (1000 - $held) : 0;
        $pointMax = (int)$shop['point_use_max'];
        $couponMax = (int)$shop['coupon_use_max'];

        $demotion = self::buildDemotionInfo($member, $shop);
        $returnInfo = self::buildReturnInfo($member, $shop, $rankDefs);

        return array(
            'status' => 0,
            'message' => '',
            'data' => array(
                'available' => true,
                'guest_linked' => true,
                'link_message' => '',
                'rank' => array(
                    'code' => $rankCode,
                    'name' => $rankName,
                    'visit_count' => $visitCount,
                    'last_visit_at' => $lastVisitAt,
                    'last_visit_display' => $lastVisitAt ? self::formatDateJp($lastVisitAt) : '—',
                    'demotion' => $demotion,
                    'return' => $returnInfo,
                ),
                'points' => array(
                    'held' => $held,
                    'held_display' => number_format($held) . 'P',
                    'usable' => $usable,
                    'usable_display' => number_format($usable) . 'P',
                    'shortfall' => $shortfall,
                    'shortfall_display' => number_format($shortfall) . 'P',
                    'use_max' => $pointMax,
                    'use_max_display' => number_format($pointMax) . 'P',
                    'usable_now' => min($usable, $pointMax),
                    'usable_now_display' => number_format(min($usable, $pointMax)) . 'P',
                    'expiring_soon' => $soonP,
                    'expiring_soon_display' => number_format($soonP) . 'P',
                    'show_shortfall' => $held < 1000,
                ),
                'coupons' => array(
                    'held_yen' => $couponHeld,
                    'held_display' => number_format($couponHeld) . '円',
                    'usable_yen' => $couponHeld,
                    'usable_display' => number_format($couponHeld) . '円',
                    'use_max' => $couponMax,
                    'use_max_display' => number_format($couponMax) . '円',
                    'usable_now_yen' => min($couponHeld, $couponMax),
                    'usable_now_display' => number_format(min($couponHeld, $couponMax)) . '円',
                    'expiring_soon_yen' => $soonC,
                    'expiring_soon_display' => number_format($soonC) . '円',
                ),
            ),
        );
    }

    /**
     * @param bool $available
     * @param string $linkMessage
     * @return array
     */
    private static function emptyPayload($available, $linkMessage)
    {
        return array(
            'available' => (bool)$available,
            'guest_linked' => false,
            'link_message' => (string)$linkMessage,
            'rank' => null,
            'points' => null,
            'coupons' => null,
        );
    }

    /**
     * @param mysqli $conn
     * @param int $guestId
     * @return array
     */
    private static function fetchGuestMember($conn, $guestId)
    {
        $guestId = (int)$guestId;
        $sql = "SELECT * FROM guest_member WHERE guest_id = {$guestId} LIMIT 1";
        $res = @mysqli_query($conn, $sql);
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            mysqli_free_result($res);
            return $row;
        }
        if ($res) {
            mysqli_free_result($res);
        }
        return array(
            'guest_id' => $guestId,
            'rank_code' => 'bronze',
            'visit_count' => 0,
            'last_visit_at' => null,
            'demotion_base_at' => null,
            'prev_rank_code' => null,
            'return_visit_count' => 0,
        );
    }

    /**
     * @param mysqli $conn
     * @return list<array>
     */
    private static function fetchRankDefs($conn)
    {
        $rows = array();
        $res = @mysqli_query($conn, 'SELECT * FROM member_rank_defs ORDER BY sort_order ASC, id ASC');
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysqli_free_result($res);
        }
        return $rows;
    }

    /**
     * @param mysqli $conn
     * @param int $clubId
     * @return array
     */
    private static function fetchShopSettings($conn, $clubId)
    {
        $defaults = array(
            'point_use_max' => 3000,
            'coupon_use_max' => 3000,
            'demotion_months' => 6,
            'return_needed_visits' => 10,
            'demotion_notice_days' => 30,
        );
        $clubId = (int)$clubId;
        $res = @mysqli_query($conn, "SELECT * FROM loyalty_shop_settings WHERE club_id = {$clubId} LIMIT 1");
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            mysqli_free_result($res);
            return array_merge($defaults, $row);
        }
        if ($res) {
            mysqli_free_result($res);
        }
        return $defaults;
    }

    /**
     * @param mysqli $conn
     * @param int $guestId
     * @return int
     */
    private static function balancePoints($conn, $guestId)
    {
        $guestId = (int)$guestId;
        $sql = "SELECT COALESCE(SUM(amount_remaining), 0) AS s FROM point_lots"
            . " WHERE guest_id = {$guestId} AND amount_remaining > 0"
            . " AND (expires_at IS NULL OR expires_at >= CURDATE())";
        $res = @mysqli_query($conn, $sql);
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            $n = (int)$row['s'];
            mysqli_free_result($res);
            return $n;
        }
        if ($res) {
            mysqli_free_result($res);
        }
        return 0;
    }

    /**
     * @param mysqli $conn
     * @param int $guestId
     * @param int $withinDays
     * @return int
     */
    private static function pointsExpiringSoon($conn, $guestId, $withinDays)
    {
        $guestId = (int)$guestId;
        $withinDays = (int)$withinDays;
        $sql = "SELECT COALESCE(SUM(amount_remaining), 0) AS s FROM point_lots"
            . " WHERE guest_id = {$guestId} AND amount_remaining > 0"
            . " AND expires_at IS NOT NULL"
            . " AND expires_at >= CURDATE()"
            . " AND expires_at <= DATE_ADD(CURDATE(), INTERVAL {$withinDays} DAY)";
        $res = @mysqli_query($conn, $sql);
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            $n = (int)$row['s'];
            mysqli_free_result($res);
            return $n;
        }
        if ($res) {
            mysqli_free_result($res);
        }
        return 0;
    }

    /**
     * @param mysqli $conn
     * @param int $guestId
     * @return int
     */
    private static function couponBalanceYen($conn, $guestId)
    {
        $guestId = (int)$guestId;
        $sql = "SELECT COALESCE(SUM(amount_yen), 0) AS s FROM coupon_tickets"
            . " WHERE guest_id = {$guestId} AND status = 'unused'"
            . " AND (expires_at IS NULL OR expires_at >= CURDATE())";
        $res = @mysqli_query($conn, $sql);
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            $n = (int)$row['s'];
            mysqli_free_result($res);
            return $n;
        }
        if ($res) {
            mysqli_free_result($res);
        }
        return 0;
    }

    /**
     * @param mysqli $conn
     * @param int $guestId
     * @param int $withinDays
     * @return int
     */
    private static function couponsExpiringSoonYen($conn, $guestId, $withinDays)
    {
        $guestId = (int)$guestId;
        $withinDays = (int)$withinDays;
        $sql = "SELECT COALESCE(SUM(amount_yen), 0) AS s FROM coupon_tickets"
            . " WHERE guest_id = {$guestId} AND status = 'unused'"
            . " AND expires_at IS NOT NULL"
            . " AND expires_at >= CURDATE()"
            . " AND expires_at <= DATE_ADD(CURDATE(), INTERVAL {$withinDays} DAY)";
        $res = @mysqli_query($conn, $sql);
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            $n = (int)$row['s'];
            mysqli_free_result($res);
            return $n;
        }
        if ($res) {
            mysqli_free_result($res);
        }
        return 0;
    }

    /**
     * @param string $code
     * @param list<array> $defs
     * @return string
     */
    private static function resolveRankName($code, $defs)
    {
        foreach ($defs as $r) {
            if ((string)$r['rank_code'] === (string)$code) {
                return (string)$r['rank_name'];
            }
        }
        $key = strtolower((string)$code);
        if (isset(self::$DEFAULT_RANK_NAMES[$key])) {
            return self::$DEFAULT_RANK_NAMES[$key];
        }
        return (string)$code;
    }

    /**
     * @param array $member
     * @param array $shop
     * @return array|null
     */
    private static function buildDemotionInfo($member, $shop)
    {
        $months = (int)$shop['demotion_months'];
        if ($months <= 0) {
            return null;
        }
        $base = !empty($member['demotion_base_at'])
            ? $member['demotion_base_at']
            : (isset($member['last_visit_at']) ? $member['last_visit_at'] : null);
        if (!$base) {
            return null;
        }
        $ts = strtotime((string)$base . ' +' . $months . ' months');
        if ($ts === false) {
            return null;
        }
        $due = date('Y-m-d', $ts);
        $daysLeft = (int)floor(($ts - time()) / 86400);
        $notice = (int)$shop['demotion_notice_days'];
        if ($daysLeft > $notice) {
            return null;
        }
        return array(
            'due_date' => $due,
            'due_date_display' => self::formatDateJp($due),
            'days_left' => max(0, $daysLeft),
            'message' => '会員ランク降格予定 : ' . self::formatDateJp($due)
                . ' / 残り' . max(0, $daysLeft) . '日間',
        );
    }

    /**
     * @param array $member
     * @param array $shop
     * @param list<array> $rankDefs
     * @return array|null
     */
    private static function buildReturnInfo($member, $shop, $rankDefs)
    {
        if (empty($member['prev_rank_code'])) {
            return null;
        }
        $prevCode = (string)$member['prev_rank_code'];
        $need = (int)$shop['return_needed_visits'];
        $have = isset($member['return_visit_count']) ? (int)$member['return_visit_count'] : 0;
        $remaining = max(0, $need - $have);
        $prevName = self::resolveRankName($prevCode, $rankDefs);
        return array(
            'prev_rank_code' => $prevCode,
            'prev_rank_name' => $prevName,
            'needed' => $need,
            'current' => $have,
            'remaining' => $remaining,
            'message' => $prevName . 'へ復帰まであと' . $remaining . '回の利用が必要です',
        );
    }

    /**
     * @param string $date
     * @return string
     */
    private static function formatDateJp($date)
    {
        $date = trim((string)$date);
        if ($date === '') {
            return '—';
        }
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})/', $date, $m)) {
            return sprintf('%d年%02d月%02d日', (int)$m[1], (int)$m[2], (int)$m[3]);
        }
        $ts = strtotime($date);
        if ($ts === false) {
            return $date;
        }
        return date('Y年m月d日', $ts);
    }
}
