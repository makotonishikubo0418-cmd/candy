<?php

/**
 * お誕生日特典（会員ランク別のクーポンチケット）
 * 設定は CTI の coupon_grant_rules（rule_type=birthday、rank_code 別、有効期限は共通）。
 * 会員ページを開いたときに、誕生日から GRACE_DAYS 日以内なら1年に1回だけ付与する（定期実行を使わない）。
 * 付与の記録は CTI の login_bonus_plays（kind=birthday、play_date=その年の誕生日）で二重付与を防ぐ。
 */
class MemberBirthday
{
    /** 誕生日を過ぎてから付与できる日数（この期間に会員ページを開かなければ、その年は付与しない） */
    const GRACE_DAYS = 30;

    /** 誕生日を変更して同じ年に何度も受け取れないよう、前回付与からこの日数は付与しない */
    const MIN_INTERVAL_DAYS = 330;

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
        $ok = @mysqli_real_connect($conn, MEMBER_CTI_HOST, MEMBER_CTI_USER, MEMBER_CTI_PASS, MEMBER_CTI_DB);
        if (!$ok) {
            return null;
        }
        @mysqli_set_charset($conn, 'utf8mb4');
        return $conn;
    }

    /**
     * 今日が付与期間なら、その年の誕生日（Y-m-d）を返す。期間外・誕生日未登録は null
     * @param string|null $birthday YYYY-MM-DD
     * @param string $today YYYY-MM-DD
     * @return string|null
     */
    public static function dueBirthdayDate($birthday, $today)
    {
        if (!is_string($birthday) || !preg_match('/^\d{4}-(\d{2})-(\d{2})$/', $birthday, $m) || $birthday === '0000-00-00') {
            return null;
        }
        $month = (int)$m[1];
        $day = (int)$m[2];
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return null;
        }
        $todayTs = strtotime($today);
        $year = (int)date('Y', $todayTs);
        // 2/29 生まれは、うるう年以外は 3/1 を誕生日として扱う（mktime の繰り上がり）
        $target = mktime(0, 0, 0, $month, $day, $year);
        if ($target > $todayTs) {
            $target = mktime(0, 0, 0, $month, $day, $year - 1);
        }
        if (($todayTs - $target) / 86400 > self::GRACE_DAYS) {
            return null;
        }
        return date('Y-m-d', $target);
    }

    /**
     * 会員ランクに合う誕生日クーポンの設定。店舗別の行を共通（club_id=0）より優先し、ランク指定の行をランク未指定の行より優先する
     * @return array|null {amount_yen, expire_days}
     */
    private static function rule($conn, $clubId, $rankCode)
    {
        $clubId = (int)$clubId;
        $res = @mysqli_query($conn, "SELECT club_id, rank_code, amount_yen, expire_days FROM coupon_grant_rules"
            . " WHERE rule_type = 'birthday' AND active = 1 AND (club_id = 0 OR club_id = {$clubId})"
            . " ORDER BY club_id DESC, sort_order ASC, id ASC");
        if (!$res) {
            // ランクの列（alter_loyalty_rules_visit_rank.sql）が無い環境は、全ランク共通の設定として読む
            $res = @mysqli_query($conn, "SELECT club_id, '' AS rank_code, amount_yen, expire_days FROM coupon_grant_rules"
                . " WHERE rule_type = 'birthday' AND active = 1 AND (club_id = 0 OR club_id = {$clubId})"
                . " ORDER BY club_id DESC, sort_order ASC, id ASC");
        }
        if (!$res) {
            return null;
        }
        $exact = null;
        $common = null;
        while ($row = mysqli_fetch_assoc($res)) {
            $code = trim((string)$row['rank_code']);
            if ($code === (string)$rankCode && $exact === null) {
                $exact = $row;
            } elseif ($code === '' && $common === null) {
                $common = $row;
            }
        }
        mysqli_free_result($res);
        $row = $exact !== null ? $exact : $common;
        if ($row === null || (int)$row['amount_yen'] <= 0) {
            return null;
        }
        return array('amount_yen' => (int)$row['amount_yen'], 'expire_days' => (int)$row['expire_days']);
    }

    private static function rankCode($conn, $guestId)
    {
        $guestId = (int)$guestId;
        $res = @mysqli_query($conn, "SELECT rank_code FROM guest_member WHERE guest_id = {$guestId} LIMIT 1");
        $code = '';
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            mysqli_free_result($res);
            $code = $row ? trim((string)$row['rank_code']) : '';
        }
        return $code !== '' ? $code : 'bronze';
    }

    private static function recentlyGranted($conn, $guestId, $today)
    {
        $guestId = (int)$guestId;
        $days = (int)self::MIN_INTERVAL_DAYS;
        $res = @mysqli_query($conn, "SELECT id FROM login_bonus_plays WHERE guest_id = {$guestId} AND kind = 'birthday'"
            . " AND created_at >= DATE_SUB('{$today}', INTERVAL {$days} DAY) LIMIT 1");
        if (!$res) {
            return true;
        }
        $found = mysqli_num_rows($res) > 0;
        mysqli_free_result($res);
        return $found;
    }

    /**
     * 会員ページを開いたときに呼ぶ。付与期間で未付与なら誕生日クーポンを付与する。
     * 失敗しても会員ページの表示は止めない（付与しなかった扱い）
     * @return array|null 付与した場合 {amount_yen, birthday}
     */
    public static function grantIfDue($guestId, $clubId, $birthday, $memberId)
    {
        $guestId = (int)$guestId;
        $clubId = (int)$clubId;
        $today = date('Y-m-d');
        $target = self::dueBirthdayDate($birthday, $today);
        if ($guestId <= 0 || $target === null) {
            return null;
        }
        $conn = self::ctiConnection();
        if (!$conn) {
            return null;
        }
        try {
            $res = @mysqli_query($conn, "SHOW TABLES LIKE 'login_bonus_plays'");
            $ready = $res && mysqli_num_rows($res) > 0;
            if ($res) {
                mysqli_free_result($res);
            }
            if (!$ready || self::recentlyGranted($conn, $guestId, $today)) {
                mysqli_close($conn);
                return null;
            }
            $rule = self::rule($conn, $clubId, self::rankCode($conn, $guestId));
            if ($rule === null) {
                mysqli_close($conn);
                return null;
            }
            $memberSql = $memberId ? (string)(int)$memberId : 'NULL';
            $amount = (int)$rule['amount_yen'];
            mysqli_begin_transaction($conn);
            if (!mysqli_query($conn, "INSERT IGNORE INTO login_bonus_plays"
                . " (club_id, guest_id, member_account_id, play_date, kind, streak_days, prize_type, amount, created_at)"
                . " VALUES ({$clubId}, {$guestId}, {$memberSql}, '{$target}', 'birthday', 0, 'coupon', {$amount}, NOW())")) {
                throw new RuntimeException('birthday insert failed');
            }
            if (mysqli_affected_rows($conn) !== 1) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                return null;
            }
            $playId = (int)mysqli_insert_id($conn);
            $expireDays = (int)$rule['expire_days'];
            $expiresAt = $expireDays < 0 ? null : date('Y-m-d', strtotime('+' . max(1, $expireDays) . ' days'));
            $ticketId = MemberLoginBonus::grantCoupon($conn, $guestId, $clubId, $amount, 'birthday', 'お誕生日特典', $expiresAt);
            mysqli_query($conn, "UPDATE login_bonus_plays SET coupon_ticket_id = {$ticketId} WHERE id = {$playId}");
            mysqli_commit($conn);
            mysqli_close($conn);
            return array('amount_yen' => $amount, 'birthday' => $target);
        } catch (Throwable $e) {
            @mysqli_rollback($conn);
            @mysqli_close($conn);
            return null;
        }
    }
}
