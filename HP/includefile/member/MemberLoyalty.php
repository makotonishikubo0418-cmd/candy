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
        $notices = self::recentGrantNotices($conn, $guestId, 14, 5);

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

        $demotion = self::buildDemotionInfo($member, $shop, $rankDefs);
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
                'notices' => $notices,
            ),
        );
    }

    /**
     * 直近に獲得した特典（再来店・連続ログイン・誕生日などのボーナス）のお知らせ
     * @param mysqli $conn
     * @return list<array>
     */
    private static function recentGrantNotices($conn, $guestId, $withinDays, $limit)
    {
        $guestId = (int)$guestId;
        $withinDays = (int)$withinDays;
        $limit = (int)$limit;
        $out = array();
        $rows = self::fetchRows($conn, "SELECT occurred_on, item_label, points FROM point_ledger"
            . " WHERE guest_id = {$guestId} AND points > 0 AND item_code NOT IN ('refund')"
            . " AND occurred_on >= DATE_SUB(CURDATE(), INTERVAL {$withinDays} DAY)"
            . " ORDER BY occurred_on DESC, id DESC LIMIT {$limit}");
        foreach ($rows as $r) {
            $out[] = array(
                'date' => (string)$r['occurred_on'],
                'date_display' => self::formatDateJp($r['occurred_on']),
                'message' => self::bonusLabel($r['item_label'], 'ポイント') . ' ' . number_format((int)$r['points']) . 'P を獲得しました',
            );
        }
        $rows = self::fetchRows($conn, "SELECT occurred_on, item_label, amount_yen FROM coupon_ledger"
            . " WHERE guest_id = {$guestId} AND op = 'grant' AND (note IS NULL OR note <> 'restore')"
            . " AND occurred_on >= DATE_SUB(CURDATE(), INTERVAL {$withinDays} DAY)"
            . " ORDER BY occurred_on DESC, id DESC LIMIT {$limit}");
        foreach ($rows as $r) {
            $out[] = array(
                'date' => (string)$r['occurred_on'],
                'date_display' => self::formatDateJp($r['occurred_on']),
                'message' => self::bonusLabel($r['item_label'], 'クーポンチケット') . ' ' . number_format((int)$r['amount_yen']) . '円分 を獲得しました',
            );
        }
        usort($out, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });
        return array_slice($out, 0, $limit);
    }

    private static function bonusLabel($label, $fallback)
    {
        $label = trim((string)$label);
        return $label !== '' ? $label : $fallback;
    }

    /**
     * @param mysqli $conn
     * @return list<array>
     */
    private static function fetchRows($conn, $sql)
    {
        $rows = array();
        $res = @mysqli_query($conn, $sql);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysqli_free_result($res);
        }
        return $rows;
    }

    /**
     * 失効日＝有効期限（最終の有効日）の翌日（CTI と同じ）
     */
    private static function expiredOn($expiresAt)
    {
        $ts = strtotime(substr((string)$expiresAt, 0, 10) . ' +1 day');
        return $ts === false ? (string)$expiresAt : date('Y-m-d', $ts);
    }

    private static function daysLeft($expiresAt)
    {
        if ($expiresAt === null || $expiresAt === '') {
            return null;
        }
        $ts = strtotime(substr((string)$expiresAt, 0, 10));
        if ($ts === false) {
            return null;
        }
        return (int)floor(($ts - strtotime(date('Y-m-d'))) / 86400);
    }

    private static function pageSlice(array $rows, $page, $perPage)
    {
        $page = max(1, (int)$page);
        $perPage = min(100, max(1, (int)$perPage));
        return array(
            'items' => array_slice($rows, ($page - 1) * $perPage, $perPage),
            'total' => count($rows),
            'page' => $page,
            'per_page' => $perPage,
        );
    }

    /**
     * ポイント履歴（獲得・利用・失効と各時点の残高）。CTI で失効処理がまだの期限切れ分も「失効」として含める
     * @return array{status:int,message:string,data?:array}
     */
    public static function getPointHistory($guestId, $page = 1, $perPage = 30)
    {
        $guestId = (int)$guestId;
        $conn = self::ctiConnection();
        if (!$conn) {
            return array('status' => -1, 'message' => 'ポイント情報の参照先に接続できませんでした');
        }
        $rows = array();
        foreach (self::fetchRows($conn, "SELECT id, occurred_on, item_code, item_label, points, balance_after FROM point_ledger"
            . " WHERE guest_id = {$guestId} ORDER BY occurred_on DESC, id DESC LIMIT 2000") as $r) {
            $rows[] = array(
                'sort' => (string)$r['occurred_on'] . sprintf('%012d', (int)$r['id']),
                'date' => (string)$r['occurred_on'],
                'date_display' => self::formatDateJp($r['occurred_on']),
                'kind' => self::pointKind((string)$r['item_code'], (int)$r['points']),
                'label' => self::pointItemLabel((string)$r['item_code'], (string)$r['item_label']),
                'points' => (int)$r['points'],
                'points_display' => ((int)$r['points'] > 0 ? '+' : '') . number_format((int)$r['points']) . 'P',
                'balance_after' => (int)$r['balance_after'],
                'balance_display' => number_format((int)$r['balance_after']) . 'P',
            );
        }
        foreach (self::fetchRows($conn, "SELECT id, amount_remaining, expires_at FROM point_lots"
            . " WHERE guest_id = {$guestId} AND amount_remaining > 0 AND expires_at IS NOT NULL AND expires_at < CURDATE()") as $r) {
            $on = self::expiredOn($r['expires_at']);
            $rows[] = array(
                'sort' => $on . '999999999999',
                'date' => $on,
                'date_display' => self::formatDateJp($on),
                'kind' => '失効',
                'label' => '有効期限切れ',
                'points' => -(int)$r['amount_remaining'],
                'points_display' => '-' . number_format((int)$r['amount_remaining']) . 'P',
                'balance_after' => null,
                'balance_display' => '—',
            );
        }
        mysqli_close($conn);
        usort($rows, function ($a, $b) {
            return strcmp($b['sort'], $a['sort']);
        });
        return array('status' => 0, 'message' => '', 'data' => self::pageSlice($rows, $page, $perPage));
    }

    private static function pointKind($code, $points)
    {
        if ($code === 'use') {
            return '利用';
        }
        if ($code === 'expire') {
            return '失効';
        }
        if ($code === 'revoke') {
            return '取消';
        }
        if ($code === 'adjust') {
            return '調整';
        }
        if ($code === 'refund') {
            return '返却';
        }
        return $points >= 0 ? '獲得' : '調整';
    }

    private static function pointItemLabel($code, $label)
    {
        $label = trim($label);
        if ($code === 'use') {
            return 'ご利用時に使用';
        }
        if ($code === 'expire') {
            return '有効期限切れ';
        }
        return $label !== '' ? $label : '—';
    }

    /**
     * クーポンチケット履歴（付与・利用済み・失効、内容・有効期限・現在の状態）
     * @return array{status:int,message:string,data?:array}
     */
    public static function getCouponHistory($guestId, $page = 1, $perPage = 30)
    {
        $guestId = (int)$guestId;
        $conn = self::ctiConnection();
        if (!$conn) {
            return array('status' => -1, 'message' => 'クーポン情報の参照先に接続できませんでした');
        }
        $rows = array();
        foreach (self::fetchRows($conn, "SELECT l.id, l.occurred_on, l.op, l.item_label, l.amount_yen, l.expires_at, l.note,"
            . " t.source_label AS ticket_label, t.status AS ticket_status, t.expires_at AS ticket_expires"
            . " FROM coupon_ledger l LEFT JOIN coupon_tickets t ON t.id = l.ticket_id"
            . " WHERE l.guest_id = {$guestId} ORDER BY l.occurred_on DESC, l.id DESC LIMIT 2000") as $r) {
            $op = (string)$r['op'];
            $kind = '付与';
            if ($op === 'use') {
                $kind = '利用済み';
            } elseif ($op === 'expire') {
                $kind = '失効';
            } elseif ((string)$r['note'] === 'restore') {
                $kind = '利用取消';
            }
            $label = trim((string)$r['ticket_label']);
            if ($label === '') {
                $label = trim((string)$r['item_label']);
            }
            $expires = $r['expires_at'] !== null && $r['expires_at'] !== '' ? $r['expires_at'] : $r['ticket_expires'];
            $rows[] = array(
                'sort' => (string)$r['occurred_on'] . sprintf('%012d', (int)$r['id']),
                'date' => (string)$r['occurred_on'],
                'date_display' => self::formatDateJp($r['occurred_on']),
                'kind' => $kind,
                'label' => $label !== '' ? $label : 'クーポンチケット',
                'amount_display' => number_format((int)$r['amount_yen']) . '円',
                'expires_display' => ($expires !== null && $expires !== '') ? self::formatDateJp($expires) : '無期限',
                'status_display' => self::couponStatusLabel((string)$r['ticket_status'], $r['ticket_expires']),
            );
        }
        foreach (self::fetchRows($conn, "SELECT id, amount_yen, expires_at, source_label FROM coupon_tickets"
            . " WHERE guest_id = {$guestId} AND status = 'unused' AND expires_at IS NOT NULL AND expires_at < CURDATE()") as $r) {
            $on = self::expiredOn($r['expires_at']);
            $label = trim((string)$r['source_label']);
            $rows[] = array(
                'sort' => $on . '999999999999',
                'date' => $on,
                'date_display' => self::formatDateJp($on),
                'kind' => '失効',
                'label' => $label !== '' ? $label : 'クーポンチケット',
                'amount_display' => number_format((int)$r['amount_yen']) . '円',
                'expires_display' => self::formatDateJp($r['expires_at']),
                'status_display' => '期限切れ',
            );
        }
        mysqli_close($conn);
        usort($rows, function ($a, $b) {
            return strcmp($b['sort'], $a['sort']);
        });
        return array('status' => 0, 'message' => '', 'data' => self::pageSlice($rows, $page, $perPage));
    }

    private static function couponStatusLabel($status, $expiresAt)
    {
        if ($status === 'used') {
            return '利用済み';
        }
        if ($status === 'expired') {
            return '期限切れ';
        }
        if ($status === 'unused') {
            $left = self::daysLeft($expiresAt);
            return ($left !== null && $left < 0) ? '期限切れ' : '未使用';
        }
        return '—';
    }

    /**
     * 失効予定（付与ごとの残り・有効期限・残り日数）と失効済みの一覧
     * @return array{status:int,message:string,data?:array}
     */
    public static function getExpiryList($guestId)
    {
        $guestId = (int)$guestId;
        $conn = self::ctiConnection();
        if (!$conn) {
            return array('status' => -1, 'message' => 'ポイント・クーポン情報の参照先に接続できませんでした');
        }
        $pointUpcoming = array();
        foreach (self::fetchRows($conn, "SELECT created_at, source_label, amount_original, amount_remaining, expires_at FROM point_lots"
            . " WHERE guest_id = {$guestId} AND amount_remaining > 0 AND (expires_at IS NULL OR expires_at >= CURDATE())"
            . " ORDER BY (expires_at IS NULL) ASC, expires_at ASC, id ASC LIMIT 200") as $r) {
            $left = self::daysLeft($r['expires_at']);
            $pointUpcoming[] = array(
                'granted_display' => self::formatDateJp($r['created_at']),
                'label' => self::bonusLabel($r['source_label'], 'ポイント'),
                'remaining_display' => number_format((int)$r['amount_remaining']) . 'P',
                'original_display' => number_format((int)$r['amount_original']) . 'P',
                'expires_display' => $r['expires_at'] ? self::formatDateJp($r['expires_at']) : '無期限',
                'days_left' => $left,
                'days_left_display' => $left === null ? '—' : ('残り' . $left . '日'),
                'soon' => $left !== null && $left <= 10,
            );
        }
        $pointExpired = array();
        foreach (self::fetchRows($conn, "SELECT occurred_on, points FROM point_ledger"
            . " WHERE guest_id = {$guestId} AND item_code = 'expire' ORDER BY occurred_on DESC, id DESC LIMIT 100") as $r) {
            $pointExpired[] = array(
                'sort' => (string)$r['occurred_on'],
                'expired_display' => self::formatDateJp($r['occurred_on']),
                'amount_display' => number_format(abs((int)$r['points'])) . 'P',
                'status_display' => '失効',
            );
        }
        foreach (self::fetchRows($conn, "SELECT amount_remaining, expires_at FROM point_lots"
            . " WHERE guest_id = {$guestId} AND amount_remaining > 0 AND expires_at IS NOT NULL AND expires_at < CURDATE()") as $r) {
            $on = self::expiredOn($r['expires_at']);
            $pointExpired[] = array(
                'sort' => $on,
                'expired_display' => self::formatDateJp($on),
                'amount_display' => number_format((int)$r['amount_remaining']) . 'P',
                'status_display' => '失効',
            );
        }
        usort($pointExpired, function ($a, $b) {
            return strcmp($b['sort'], $a['sort']);
        });

        $couponUpcoming = array();
        $couponExpired = array();
        foreach (self::fetchRows($conn, "SELECT created_at, source_label, amount_yen, status, expires_at FROM coupon_tickets"
            . " WHERE guest_id = {$guestId} AND status IN ('unused', 'expired')"
            . " ORDER BY (expires_at IS NULL) ASC, expires_at ASC, id ASC LIMIT 300") as $r) {
            $left = self::daysLeft($r['expires_at']);
            $isExpired = (string)$r['status'] === 'expired' || ($left !== null && $left < 0);
            $row = array(
                'granted_display' => self::formatDateJp($r['created_at']),
                'label' => self::bonusLabel($r['source_label'], 'クーポンチケット'),
                'amount_display' => number_format((int)$r['amount_yen']) . '円',
                'expires_display' => $r['expires_at'] ? self::formatDateJp($r['expires_at']) : '無期限',
            );
            if ($isExpired) {
                $on = $r['expires_at'] ? self::expiredOn($r['expires_at']) : '';
                $row['sort'] = $on;
                $row['expired_display'] = $on !== '' ? self::formatDateJp($on) : '—';
                $row['status_display'] = '期限切れ';
                $couponExpired[] = $row;
            } else {
                $row['days_left'] = $left;
                $row['days_left_display'] = $left === null ? '—' : ('残り' . $left . '日');
                $row['soon'] = $left !== null && $left <= 10;
                $couponUpcoming[] = $row;
            }
        }
        usort($couponExpired, function ($a, $b) {
            return strcmp($b['sort'], $a['sort']);
        });
        mysqli_close($conn);

        return array('status' => 0, 'message' => '', 'data' => array(
            'point_upcoming' => $pointUpcoming,
            'point_expired' => array_slice($pointExpired, 0, 100),
            'coupon_upcoming' => $couponUpcoming,
            'coupon_expired' => array_slice($couponExpired, 0, 100),
        ));
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
    /**
     * ランクごとの設定（未設定は店舗共通）。CTI の LoyaltyService::rankSetting と同じ判定
     * @param list<array> $rankDefs
     */
    private static function rankSetting($rankCode, $key, $shop, $rankDefs, $default)
    {
        foreach ($rankDefs as $d) {
            if ((string)$d['rank_code'] === (string)$rankCode && isset($d[$key]) && $d[$key] !== null && $d[$key] !== '') {
                return (int)$d[$key];
            }
        }
        return isset($shop[$key]) ? (int)$shop[$key] : (int)$default;
    }

    private static function buildDemotionInfo($member, $shop, $rankDefs)
    {
        $rank = isset($member['rank_code']) && $member['rank_code'] !== '' ? (string)$member['rank_code'] : 'bronze';
        if ($rank === 'bronze') {
            return null;
        }
        $months = self::rankSetting($rank, 'demotion_months', $shop, $rankDefs, 6);
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
        $need = self::rankSetting($prevCode, 'return_needed_visits', $shop, $rankDefs, 10);
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
