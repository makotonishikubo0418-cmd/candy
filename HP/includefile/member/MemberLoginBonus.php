<?php

/**
 * ログイン特典（1日1回のルーレット）と連続ログインボーナス
 * 設定は CTI の login_bonus_settings（店舗ごとの JSON）、記録は CTI の login_bonus_plays。
 * 付与は CTI の point_lots / point_ledger / coupon_tickets / coupon_ledger へ書き込む。
 */
class MemberLoginBonus
{
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
     * 設定。未保存・表が無い場合は null
     * @return array|null
     */
    private static function config($conn, $clubId)
    {
        $clubId = (int)$clubId;
        $res = @mysqli_query($conn, "SELECT config_json FROM login_bonus_settings WHERE club_id = {$clubId} LIMIT 1");
        if (!$res) {
            return null;
        }
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        if (!$row) {
            return null;
        }
        $cfg = json_decode((string)$row['config_json'], true);
        if (!is_array($cfg)) {
            return null;
        }
        $daily = isset($cfg['daily']) && is_array($cfg['daily']) ? $cfg['daily'] : array();
        $streak = isset($cfg['streak']) && is_array($cfg['streak']) ? $cfg['streak'] : array();
        return array(
            'daily' => array(
                'enabled' => !empty($daily['enabled']),
                'require_visits' => isset($daily['require_visits']) ? max(0, (int)$daily['require_visits']) : 1,
                'coupon_expire_days' => isset($daily['coupon_expire_days']) ? (int)$daily['coupon_expire_days'] : 30,
                'point_expire_days' => isset($daily['point_expire_days']) ? (int)$daily['point_expire_days'] : 90,
                'prizes' => isset($daily['prizes']) && is_array($daily['prizes']) ? $daily['prizes'] : array(),
            ),
            'streak' => array(
                'enabled' => !empty($streak['enabled']),
                'expire_days' => isset($streak['expire_days']) ? (int)$streak['expire_days'] : 90,
                'rules' => isset($streak['rules']) && is_array($streak['rules']) ? $streak['rules'] : array(),
            ),
        );
    }

    private static function tableReady($conn)
    {
        $res = @mysqli_query($conn, "SHOW TABLES LIKE 'login_bonus_plays'");
        if (!$res) {
            return false;
        }
        $ok = mysqli_num_rows($res) > 0;
        mysqli_free_result($res);
        return $ok;
    }

    private static function prizeList(array $cfg)
    {
        $out = array();
        foreach ($cfg['daily']['prizes'] as $p) {
            $rank = isset($p['rank']) ? (int)$p['rank'] : 0;
            $amount = isset($p['amount']) ? (int)$p['amount'] : 0;
            if ($rank < 1 || $rank > 4) {
                continue;
            }
            $type = $rank === 1 ? 'coupon' : 'point';
            $out[] = array(
                'rank' => $rank,
                'type' => $type,
                'amount' => $amount,
                'prob' => isset($p['prob']) ? (float)$p['prob'] : 0.0,
                'display' => self::prizeDisplay($type, $amount),
            );
        }
        usort($out, function ($a, $b) {
            return $a['rank'] - $b['rank'];
        });
        return $out;
    }

    private static function prizeDisplay($type, $amount)
    {
        return $type === 'coupon' ? ('クーポン' . number_format((int)$amount) . '円') : (number_format((int)$amount) . 'P');
    }

    private static function visitCount($conn, $guestId)
    {
        $guestId = (int)$guestId;
        $res = @mysqli_query($conn, "SELECT visit_count FROM guest_member WHERE guest_id = {$guestId} LIMIT 1");
        if (!$res) {
            return 0;
        }
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        return $row ? (int)$row['visit_count'] : 0;
    }

    /**
     * 今日のログインを記録し、連続日数を返す
     */
    private static function recordLogin($conn, $clubId, $guestId, $memberId, $today)
    {
        $clubId = (int)$clubId;
        $guestId = (int)$guestId;
        $memberSql = $memberId ? (string)(int)$memberId : 'NULL';
        @mysqli_query($conn, "INSERT IGNORE INTO login_bonus_plays (club_id, guest_id, member_account_id, play_date, kind, streak_days, created_at)"
            . " VALUES ({$clubId}, {$guestId}, {$memberSql}, '{$today}', 'login', 0, NOW())");
        $res = @mysqli_query($conn, "SELECT play_date FROM login_bonus_plays"
            . " WHERE club_id = {$clubId} AND guest_id = {$guestId} AND kind = 'login'"
            . " AND play_date > DATE_SUB('{$today}', INTERVAL 400 DAY) ORDER BY play_date DESC");
        if (!$res) {
            return 0;
        }
        $days = 0;
        $expect = strtotime($today);
        while ($row = mysqli_fetch_assoc($res)) {
            if (strtotime((string)$row['play_date']) !== $expect) {
                break;
            }
            $days++;
            $expect = strtotime('-1 day', $expect);
        }
        mysqli_free_result($res);
        return $days;
    }

    private static function expiresAt($days)
    {
        $days = (int)$days;
        return $days < 0 ? null : date('Y-m-d', strtotime('+' . max(1, $days) . ' days'));
    }

    /**
     * CTI のポイント付与（ロット＋台帳）。呼び出し側でトランザクションを持つ
     * @return int point_lots.id
     */
    public static function grantPoints($conn, $guestId, $clubId, $points, $sourceType, $label, $expiresAt)
    {
        $guestId = (int)$guestId;
        $clubId = (int)$clubId;
        $points = (int)$points;
        $labelEsc = mysqli_real_escape_string($conn, $label);
        $typeEsc = mysqli_real_escape_string($conn, $sourceType);
        $expSql = $expiresAt === null ? 'NULL' : ("'" . mysqli_real_escape_string($conn, $expiresAt) . "'");
        if (!mysqli_query($conn, "INSERT INTO point_lots"
            . " (guest_id, amount_original, amount_remaining, expires_at, source_type, source_label, task_id, club_id, created_at)"
            . " VALUES ({$guestId}, {$points}, {$points}, {$expSql}, '{$typeEsc}', '{$labelEsc}', NULL, {$clubId}, NOW())")) {
            throw new RuntimeException('point lot insert failed');
        }
        $lotId = (int)mysqli_insert_id($conn);
        $res = mysqli_query($conn, "SELECT COALESCE(SUM(amount_remaining), 0) AS s FROM point_lots"
            . " WHERE guest_id = {$guestId} AND amount_remaining > 0 AND (expires_at IS NULL OR expires_at >= CURDATE())");
        $balance = 0;
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            $balance = $row ? (int)$row['s'] : 0;
            mysqli_free_result($res);
        }
        $today = date('Y-m-d');
        if (!mysqli_query($conn, "INSERT INTO point_ledger"
            . " (guest_id, occurred_on, item_code, item_label, points, balance_after, task_id, lot_id, club_id, note, created_at)"
            . " VALUES ({$guestId}, '{$today}', '{$typeEsc}', '{$labelEsc}', {$points}, {$balance}, NULL, {$lotId}, {$clubId}, '', NOW())")) {
            throw new RuntimeException('point ledger insert failed');
        }
        return $lotId;
    }

    private static function grantCoupon($conn, $guestId, $clubId, $amountYen, $sourceType, $label, $expiresAt)
    {
        $guestId = (int)$guestId;
        $clubId = (int)$clubId;
        $amountYen = (int)$amountYen;
        $labelEsc = mysqli_real_escape_string($conn, $label);
        $typeEsc = mysqli_real_escape_string($conn, $sourceType);
        $expSql = $expiresAt === null ? 'NULL' : ("'" . mysqli_real_escape_string($conn, $expiresAt) . "'");
        if (!mysqli_query($conn, "INSERT INTO coupon_tickets"
            . " (guest_id, amount_yen, status, expires_at, source_type, source_label, task_id, club_id, created_at)"
            . " VALUES ({$guestId}, {$amountYen}, 'unused', {$expSql}, '{$typeEsc}', '{$labelEsc}', NULL, {$clubId}, NOW())")) {
            throw new RuntimeException('coupon insert failed');
        }
        $ticketId = (int)mysqli_insert_id($conn);
        $today = date('Y-m-d');
        if (!mysqli_query($conn, "INSERT INTO coupon_ledger"
            . " (guest_id, occurred_on, op, item_code, item_label, amount_yen, status, expires_at, ticket_id, task_id, club_id, note, created_at)"
            . " VALUES ({$guestId}, '{$today}', 'grant', '{$typeEsc}', '{$labelEsc}', {$amountYen}, 'unused', {$expSql}, {$ticketId}, NULL, {$clubId}, '', NOW())")) {
            throw new RuntimeException('coupon ledger insert failed');
        }
        return $ticketId;
    }

    /**
     * 達成日数ちょうどの連続ログインボーナスを付与（同じ日・同じ達成日数は1回だけ）
     * @return list<array>
     */
    private static function grantStreak($conn, array $cfg, $clubId, $guestId, $memberId, $today, $streakDays)
    {
        $granted = array();
        if (!$cfg['streak']['enabled']) {
            return $granted;
        }
        $clubId = (int)$clubId;
        $guestId = (int)$guestId;
        $memberSql = $memberId ? (string)(int)$memberId : 'NULL';
        foreach ($cfg['streak']['rules'] as $rule) {
            $days = isset($rule['days']) ? (int)$rule['days'] : 0;
            $points = isset($rule['points']) ? (int)$rule['points'] : 0;
            if ($days < 2 || $points <= 0 || $days !== (int)$streakDays) {
                continue;
            }
            mysqli_begin_transaction($conn);
            try {
                if (!mysqli_query($conn, "INSERT IGNORE INTO login_bonus_plays"
                    . " (club_id, guest_id, member_account_id, play_date, kind, streak_days, prize_type, amount, created_at)"
                    . " VALUES ({$clubId}, {$guestId}, {$memberSql}, '{$today}', 'streak', {$days}, 'point', {$points}, NOW())")) {
                    throw new RuntimeException('streak insert failed');
                }
                if (mysqli_affected_rows($conn) !== 1) {
                    mysqli_rollback($conn);
                    continue;
                }
                $playId = (int)mysqli_insert_id($conn);
                $lotId = self::grantPoints($conn, $guestId, $clubId, $points, 'login_streak',
                    '連続ログイン' . $days . '日ボーナス', self::expiresAt($cfg['streak']['expire_days']));
                mysqli_query($conn, "UPDATE login_bonus_plays SET point_lot_id = {$lotId} WHERE id = {$playId}");
                mysqli_commit($conn);
                $granted[] = array('days' => $days, 'points' => $points, 'display' => number_format($points) . 'P');
            } catch (Throwable $e) {
                @mysqli_rollback($conn);
            }
        }
        return $granted;
    }

    private static function todayDaily($conn, $clubId, $guestId, $today)
    {
        $clubId = (int)$clubId;
        $guestId = (int)$guestId;
        $res = @mysqli_query($conn, "SELECT prize_rank, prize_type, amount FROM login_bonus_plays"
            . " WHERE club_id = {$clubId} AND guest_id = {$guestId} AND play_date = '{$today}' AND kind = 'daily' LIMIT 1");
        if (!$res) {
            return null;
        }
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        if (!$row) {
            return null;
        }
        return array(
            'rank' => (int)$row['prize_rank'],
            'type' => (string)$row['prize_type'],
            'amount' => (int)$row['amount'],
            'display' => self::prizeDisplay((string)$row['prize_type'], (int)$row['amount']),
        );
    }

    private static function nextStreakRule(array $cfg, $streakDays)
    {
        $next = null;
        foreach ($cfg['streak']['rules'] as $rule) {
            $days = isset($rule['days']) ? (int)$rule['days'] : 0;
            if ($days > (int)$streakDays && ($next === null || $days < $next['days'])) {
                $next = array('days' => $days, 'points' => (int)$rule['points'], 'remaining' => $days - (int)$streakDays);
            }
        }
        return $next;
    }

    /**
     * 会員ページを開いたときに呼ぶ。今日のログインを記録し、連続ログインボーナスの付与とルーレットの可否を返す
     * @return array{status:int,message:string,data?:array}
     */
    public static function status($guestId, $clubId, $memberId)
    {
        $none = array('status' => 0, 'message' => '', 'data' => array('available' => false));
        $guestId = (int)$guestId;
        if ($guestId <= 0) {
            return $none;
        }
        $conn = self::ctiConnection();
        if (!$conn) {
            return $none;
        }
        try {
            $cfg = self::config($conn, $clubId);
            if ($cfg === null || (!$cfg['daily']['enabled'] && !$cfg['streak']['enabled']) || !self::tableReady($conn)) {
                mysqli_close($conn);
                return $none;
            }
            $today = date('Y-m-d');
            $streakDays = self::recordLogin($conn, $clubId, $guestId, $memberId, $today);
            $streakGranted = self::grantStreak($conn, $cfg, $clubId, $guestId, $memberId, $today, $streakDays);

            $visits = self::visitCount($conn, $guestId);
            $need = (int)$cfg['daily']['require_visits'];
            $played = self::todayDaily($conn, $clubId, $guestId, $today);
            $eligible = $cfg['daily']['enabled'] && $visits >= $need;
            $reason = '';
            if (!$cfg['daily']['enabled']) {
                $reason = 'disabled';
            } elseif ($visits < $need) {
                $reason = 'need_visits';
            } elseif ($played !== null) {
                $reason = 'played_today';
            }
            $prizes = array();
            foreach (self::prizeList($cfg) as $p) {
                $prizes[] = array('rank' => $p['rank'], 'type' => $p['type'], 'amount' => $p['amount'], 'display' => $p['display']);
            }
            mysqli_close($conn);
            return array('status' => 0, 'message' => '', 'data' => array(
                'available' => true,
                'daily' => array(
                    'enabled' => $cfg['daily']['enabled'],
                    'eligible' => $eligible,
                    'can_play' => $eligible && $played === null,
                    'reason' => $reason,
                    'need_visits' => $need,
                    'visit_count' => $visits,
                    'played_today' => $played,
                    'prizes' => $prizes,
                ),
                'streak' => array(
                    'enabled' => $cfg['streak']['enabled'],
                    'days' => $streakDays,
                    'next' => $cfg['streak']['enabled'] ? self::nextStreakRule($cfg, $streakDays) : null,
                    'granted_today' => $streakGranted,
                ),
            ));
        } catch (Throwable $e) {
            @mysqli_close($conn);
            return $none;
        }
    }

    /**
     * ルーレットを回す（1日1回・サーバー側で抽選）
     * @return array{status:int,message:string,data?:array}
     */
    public static function draw($guestId, $clubId, $memberId)
    {
        $guestId = (int)$guestId;
        $clubId = (int)$clubId;
        if ($guestId <= 0) {
            return array('status' => -2, 'message' => 'CTI顧客と未連携です');
        }
        $conn = self::ctiConnection();
        if (!$conn) {
            return array('status' => -1, 'message' => '接続できませんでした');
        }
        try {
            $cfg = self::config($conn, $clubId);
            if ($cfg === null || !$cfg['daily']['enabled'] || !self::tableReady($conn)) {
                mysqli_close($conn);
                return array('status' => -3, 'message' => '現在ログイン特典は実施していません');
            }
            if (self::visitCount($conn, $guestId) < (int)$cfg['daily']['require_visits']) {
                mysqli_close($conn);
                return array('status' => -4, 'message' => 'ログイン特典はご利用' . (int)$cfg['daily']['require_visits'] . '回以上の会員様が対象です');
            }
            $prizes = self::prizeList($cfg);
            $prize = self::pick($prizes);
            if ($prize === null) {
                mysqli_close($conn);
                return array('status' => -1, 'message' => '特典の設定が不正です');
            }
            $today = date('Y-m-d');
            $memberSql = $memberId ? (string)(int)$memberId : 'NULL';
            $typeEsc = $prize['type'] === 'coupon' ? 'coupon' : 'point';
            mysqli_begin_transaction($conn);
            if (!mysqli_query($conn, "INSERT IGNORE INTO login_bonus_plays"
                . " (club_id, guest_id, member_account_id, play_date, kind, streak_days, prize_rank, prize_type, amount, created_at)"
                . " VALUES ({$clubId}, {$guestId}, {$memberSql}, '{$today}', 'daily', 0, {$prize['rank']}, '{$typeEsc}', {$prize['amount']}, NOW())")) {
                throw new RuntimeException('daily insert failed');
            }
            if (mysqli_affected_rows($conn) !== 1) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                return array('status' => -5, 'message' => '本日のログイン特典は受け取り済みです');
            }
            $playId = (int)mysqli_insert_id($conn);
            if ($prize['type'] === 'coupon') {
                $ticketId = self::grantCoupon($conn, $guestId, $clubId, $prize['amount'], 'login_bonus', 'ログイン特典',
                    self::expiresAt($cfg['daily']['coupon_expire_days']));
                mysqli_query($conn, "UPDATE login_bonus_plays SET coupon_ticket_id = {$ticketId} WHERE id = {$playId}");
            } else {
                $lotId = self::grantPoints($conn, $guestId, $clubId, $prize['amount'], 'login_bonus', 'ログイン特典',
                    self::expiresAt($cfg['daily']['point_expire_days']));
                mysqli_query($conn, "UPDATE login_bonus_plays SET point_lot_id = {$lotId} WHERE id = {$playId}");
            }
            mysqli_commit($conn);
            mysqli_close($conn);
            return array('status' => 0, 'message' => '', 'data' => array(
                'rank' => $prize['rank'],
                'type' => $prize['type'],
                'amount' => $prize['amount'],
                'display' => $prize['display'],
            ));
        } catch (Throwable $e) {
            @mysqli_rollback($conn);
            @mysqli_close($conn);
            return array('status' => -1, 'message' => 'ログイン特典の処理に失敗しました');
        }
    }

    /**
     * 確率（%）で1件選ぶ。金額0・確率0の賞は対象外
     * @return array|null
     */
    private static function pick(array $prizes)
    {
        $pool = array();
        $total = 0;
        foreach ($prizes as $p) {
            $units = (int)round($p['prob'] * 100);
            if ($units <= 0 || $p['amount'] <= 0) {
                continue;
            }
            $pool[] = array($p, $units);
            $total += $units;
        }
        if ($total <= 0) {
            return null;
        }
        $r = random_int(1, $total);
        $acc = 0;
        foreach ($pool as $entry) {
            $acc += $entry[1];
            if ($r <= $acc) {
                return $entry[0];
            }
        }
        return $pool[count($pool) - 1][0];
    }
}
