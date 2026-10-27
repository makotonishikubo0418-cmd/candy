<?php

/**
 * イベントガチャ（CTI DB・サーバー側抽選・クーポン付与）
 */
class MemberGacha
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
    public static function getStatus($guestId, $clubId)
    {
        $guestId = (int)$guestId;
        $clubId = (int)$clubId;
        if ($guestId <= 0) {
            return array(
                'status' => 0,
                'message' => '',
                'data' => array(
                    'available' => false,
                    'can_play' => false,
                    'reason' => 'cti_not_linked',
                    'message' => 'CTI顧客と未連携です',
                ),
            );
        }

        $conn = self::ctiConnection();
        if (!$conn) {
            return array(
                'status' => 0,
                'message' => '',
                'data' => array(
                    'available' => false,
                    'can_play' => false,
                    'reason' => 'cti_unavailable',
                    'message' => '接続できませんでした',
                ),
            );
        }

        try {
            $campaign = self::fetchLatestCampaign($conn, $clubId);
            if (!$campaign) {
                mysqli_close($conn);
                return array(
                    'status' => 0,
                    'message' => '',
                    'data' => array(
                        'available' => false,
                        'can_play' => false,
                        'reason' => 'no_campaign',
                        'message' => 'キャンペーンが未設定です（CTIでCANDY店舗にログインした状態で保存したか確認してください）',
                        'club_id' => $clubId,
                    ),
                );
            }
            if ((int)$campaign['active'] !== 1) {
                mysqli_close($conn);
                return array(
                    'status' => 0,
                    'message' => '',
                    'data' => array(
                        'available' => false,
                        'can_play' => false,
                        'reason' => 'not_published',
                        'message' => 'キャンペーンは保存済みですが「公開」がOFFです',
                        'campaign' => self::campaignBrief($campaign),
                        'club_id' => $clubId,
                    ),
                );
            }
            if (!self::isWithinPeriod($campaign)) {
                mysqli_close($conn);
                return array(
                    'status' => 0,
                    'message' => '',
                    'data' => array(
                        'available' => false,
                        'can_play' => false,
                        'reason' => 'out_of_period',
                        'message' => '開催期間外です（'
                            . substr((string)$campaign['starts_at'], 0, 16)
                            . ' 〜 '
                            . substr((string)$campaign['ends_at'], 0, 16)
                            . '）',
                        'campaign' => self::campaignBrief($campaign),
                        'club_id' => $clubId,
                    ),
                );
            }

            $campaignId = (int)$campaign['id'];
            $today = date('Y-m-d');
            $playsTotal = self::countPlays($conn, $campaignId, $guestId);
            $playsToday = self::countPlaysOnDate($conn, $campaignId, $guestId, $today);
            $maxMember = max(1, (int)$campaign['max_plays_per_member']);
            $maxDay = max(1, (int)$campaign['max_plays_per_day']);
            $canPlay = ($playsTotal < $maxMember && $playsToday < $maxDay);
            $reason = '';
            $msg = '';
            if (!$canPlay) {
                if ($playsTotal >= $maxMember) {
                    $reason = 'limit_campaign';
                    $msg = 'このキャンペーンの抽選上限に達しています';
                } else {
                    $reason = 'limit_day';
                    $msg = '本日の抽選上限に達しています';
                }
            }

            mysqli_close($conn);
            return array(
                'status' => 0,
                'message' => '',
                'data' => array(
                    'available' => true,
                    'can_play' => $canPlay,
                    'reason' => $reason,
                    'message' => $msg,
                    'campaign' => array(
                        'id' => $campaignId,
                        'name' => (string)$campaign['name'],
                        'starts_at' => (string)$campaign['starts_at'],
                        'ends_at' => (string)$campaign['ends_at'],
                        'expire_days' => (int)$campaign['expire_days'],
                    ),
                    'plays_total' => $playsTotal,
                    'plays_today' => $playsToday,
                    'max_plays_per_member' => $maxMember,
                    'max_plays_per_day' => $maxDay,
                ),
            );
        } catch (Throwable $e) {
            @mysqli_close($conn);
            return array('status' => -1, 'message' => 'ガチャ情報の取得に失敗しました');
        }
    }

    /**
     * @param int $guestId
     * @param int $clubId
     * @param int|null $memberAccountId
     * @return array{status:int,message:string,data?:array}
     */
    public static function draw($guestId, $clubId, $memberAccountId = null)
    {
        $guestId = (int)$guestId;
        $clubId = (int)$clubId;
        $memberAccountId = $memberAccountId !== null ? (int)$memberAccountId : null;
        if ($guestId <= 0) {
            return array('status' => -2, 'message' => 'CTI顧客と未連携です');
        }

        $conn = self::ctiConnection();
        if (!$conn) {
            return array('status' => -1, 'message' => '接続できませんでした');
        }

        try {
            mysqli_begin_transaction($conn);

            $campaign = self::fetchLatestCampaignForUpdate($conn, $clubId);
            if (!$campaign || !self::isActive($campaign)) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                return array('status' => -3, 'message' => '現在開催中のガチャはありません');
            }

            $campaignId = (int)$campaign['id'];
            $today = date('Y-m-d');
            $maxMember = max(1, (int)$campaign['max_plays_per_member']);
            $maxDay = max(1, (int)$campaign['max_plays_per_day']);
            $playsTotal = self::countPlays($conn, $campaignId, $guestId);
            $playsToday = self::countPlaysOnDate($conn, $campaignId, $guestId, $today);
            if ($playsTotal >= $maxMember) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                return array('status' => -4, 'message' => 'このキャンペーンの抽選上限に達しています');
            }
            if ($playsToday >= $maxDay) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                return array('status' => -5, 'message' => '本日の抽選上限に達しています');
            }

            $hasCaps = self::hasCapColumns($conn);
            $prizes = self::fetchPrizes($conn, $campaignId);
            if ($hasCaps) {
                $prizes = self::availablePrizes($conn, $campaign, $prizes);
            }
            $prize = self::drawPrize($prizes);
            if ($prize === null) {
                mysqli_rollback($conn);
                mysqli_close($conn);
                return array('status' => -6, 'message' => '賞品が上限に達したため、現在は抽選できません');
            }

            $amountYen = (int)$prize['amount_yen'];
            $isPoint = $hasCaps && isset($prize['prize_type']) && (string)$prize['prize_type'] === 'point';
            $expireDays = (int)$campaign['expire_days'];
            $expiresAt = $expireDays > 0 ? date('Y-m-d', strtotime('+' . $expireDays . ' days')) : null;
            $label = trim((string)$campaign['name']);
            if ($label === '') {
                $label = 'イベントガチャ';
            }

            $ticketId = null;
            $lotId = null;
            if ($isPoint) {
                $lotId = MemberLoginBonus::grantPoints($conn, $guestId, $clubId, $amountYen, 'event_gacha', $label, $expiresAt);
            } else {
                $ticketId = self::grantCoupon($conn, $guestId, $amountYen, $label, $expiresAt, $clubId);
            }
            $playId = self::insertPlay(
                $conn,
                $campaignId,
                $clubId,
                $guestId,
                $memberAccountId,
                (int)$prize['id'],
                (int)$prize['slot_no'],
                $amountYen,
                $ticketId,
                $today,
                $hasCaps ? ($isPoint ? 'point' : 'coupon') : null,
                $lotId
            );

            mysqli_commit($conn);
            mysqli_close($conn);

            return array(
                'status' => 0,
                'message' => '',
                'data' => array(
                    'play_id' => $playId,
                    'prize' => array(
                        'slot_no' => (int)$prize['slot_no'],
                        'prize_type' => $isPoint ? 'point' : 'coupon',
                        'amount_yen' => $amountYen,
                        'amount_display' => number_format($amountYen) . ($isPoint ? 'P' : '円'),
                    ),
                    'coupon_ticket_id' => $ticketId,
                    'expires_at' => $expiresAt,
                    'campaign_name' => $label,
                ),
            );
        } catch (Throwable $e) {
            @mysqli_rollback($conn);
            @mysqli_close($conn);
            return array('status' => -1, 'message' => '抽選処理に失敗しました');
        }
    }

    /**
     * @param mysqli $conn
     * @param int $clubId
     * @return array|null
     */
    private static function fetchLatestCampaign($conn, $clubId)
    {
        $clubId = (int)$clubId;
        $sql = "SELECT * FROM gacha_campaigns WHERE club_id = {$clubId} ORDER BY id DESC LIMIT 1";
        $res = @mysqli_query($conn, $sql);
        if (!$res) {
            throw new RuntimeException('gacha_campaigns query failed');
        }
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        return $row ? $row : null;
    }

    /**
     * @param mysqli $conn
     * @param int $clubId
     * @return array|null
     */
    private static function fetchLatestCampaignForUpdate($conn, $clubId)
    {
        $clubId = (int)$clubId;
        $sql = "SELECT * FROM gacha_campaigns WHERE club_id = {$clubId} ORDER BY id DESC LIMIT 1 FOR UPDATE";
        $res = mysqli_query($conn, $sql);
        if (!$res) {
            throw new RuntimeException('gacha lock failed');
        }
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        return $row ? $row : null;
    }

    /**
     * @param array $campaign
     * @return bool
     */
    private static function isWithinPeriod($campaign)
    {
        $start = strtotime((string)$campaign['starts_at']);
        $end = strtotime((string)$campaign['ends_at']);
        if ($start === false || $end === false) {
            return false;
        }
        $now = time();
        return ($now >= $start && $now <= $end);
    }

    /**
     * @param array $campaign
     * @return array<string, mixed>
     */
    private static function campaignBrief($campaign)
    {
        return array(
            'id' => (int)$campaign['id'],
            'name' => (string)$campaign['name'],
            'starts_at' => (string)$campaign['starts_at'],
            'ends_at' => (string)$campaign['ends_at'],
            'active' => (int)$campaign['active'],
        );
    }

    /**
     * @param array $campaign
     * @return bool
     */
    private static function isActive($campaign)
    {
        if ((int)$campaign['active'] !== 1) {
            return false;
        }
        return self::isWithinPeriod($campaign);
    }

    /**
     * @param mysqli $conn
     * @param int $campaignId
     * @param int $guestId
     * @return int
     */
    private static function countPlays($conn, $campaignId, $guestId)
    {
        $campaignId = (int)$campaignId;
        $guestId = (int)$guestId;
        $sql = "SELECT COUNT(*) AS c FROM gacha_plays WHERE campaign_id = {$campaignId} AND guest_id = {$guestId}";
        $res = mysqli_query($conn, $sql);
        if (!$res) {
            throw new RuntimeException('count plays failed');
        }
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        return (int)$row['c'];
    }

    /**
     * @param mysqli $conn
     * @param int $campaignId
     * @param int $guestId
     * @param string $playedOn
     * @return int
     */
    private static function countPlaysOnDate($conn, $campaignId, $guestId, $playedOn)
    {
        $campaignId = (int)$campaignId;
        $guestId = (int)$guestId;
        $playedOn = mysqli_real_escape_string($conn, $playedOn);
        $sql = "SELECT COUNT(*) AS c FROM gacha_plays
                WHERE campaign_id = {$campaignId} AND guest_id = {$guestId} AND played_on = '{$playedOn}'";
        $res = mysqli_query($conn, $sql);
        if (!$res) {
            throw new RuntimeException('count plays day failed');
        }
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        return (int)$row['c'];
    }

    /**
     * @param mysqli $conn
     * @param int $campaignId
     * @return list<array>
     */
    private static function fetchPrizes($conn, $campaignId)
    {
        $campaignId = (int)$campaignId;
        $sql = "SELECT * FROM gacha_prizes WHERE campaign_id = {$campaignId} ORDER BY sort_order ASC, slot_no ASC";
        $res = mysqli_query($conn, $sql);
        if (!$res) {
            throw new RuntimeException('prizes query failed');
        }
        $rows = array();
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysqli_free_result($res);
        return $rows;
    }

    /**
     * 当選上限・ポイント賞品の列（CTI の alter_event_gacha_caps.sql）があるか
     */
    private static function hasCapColumns($conn)
    {
        $res = @mysqli_query($conn, "SHOW COLUMNS FROM gacha_prizes LIKE 'max_wins'");
        if (!$res) {
            return false;
        }
        $ok = mysqli_num_rows($res) > 0;
        mysqli_free_result($res);
        return $ok;
    }

    /**
     * 当選総数の上限・総付与額の上限に達した賞品を除く（CTI の GachaService::availablePrizes と同じ判定）
     * @return list<array>
     */
    private static function availablePrizes($conn, $campaign, $prizes)
    {
        $campaignId = (int)$campaign['id'];
        $wins = array();
        $res = mysqli_query($conn, "SELECT slot_no, COUNT(*) AS c FROM gacha_plays WHERE campaign_id = {$campaignId} GROUP BY slot_no");
        if (!$res) {
            throw new RuntimeException('wins query failed');
        }
        while ($row = mysqli_fetch_assoc($res)) {
            $wins[(int)$row['slot_no']] = (int)$row['c'];
        }
        mysqli_free_result($res);
        $budget = (isset($campaign['budget_yen']) && $campaign['budget_yen'] !== null && $campaign['budget_yen'] !== '')
            ? (int)$campaign['budget_yen'] : null;
        $granted = 0;
        if ($budget !== null) {
            $res = mysqli_query($conn, "SELECT COALESCE(SUM(amount_yen), 0) AS s FROM gacha_plays WHERE campaign_id = {$campaignId}");
            if (!$res) {
                throw new RuntimeException('granted query failed');
            }
            $row = mysqli_fetch_assoc($res);
            $granted = $row ? (int)$row['s'] : 0;
            mysqli_free_result($res);
        }
        $out = array();
        foreach ($prizes as $p) {
            $max = (isset($p['max_wins']) && $p['max_wins'] !== null && $p['max_wins'] !== '') ? (int)$p['max_wins'] : null;
            if ($max !== null && (isset($wins[(int)$p['slot_no']]) ? $wins[(int)$p['slot_no']] : 0) >= $max) {
                continue;
            }
            if ($budget !== null && $granted + (int)$p['amount_yen'] > $budget) {
                continue;
            }
            $out[] = $p;
        }
        return $out;
    }

    /**
     * @param list<array> $prizes
     * @return array|null
     */
    private static function drawPrize($prizes)
    {
        $weighted = array();
        $total = 0.0;
        foreach ($prizes as $p) {
            $prob = (float)$p['probability'];
            $yen = (int)$p['amount_yen'];
            if ($prob <= 0 || $yen <= 0) {
                continue;
            }
            $weighted[] = $p;
            $total += $prob;
        }
        if ($total <= 0 || !$weighted) {
            return null;
        }
        $units = (int)round($total * 100);
        if ($units < 1) {
            return null;
        }
        $r = random_int(1, $units);
        $acc = 0;
        foreach ($weighted as $p) {
            $acc += (int)round(((float)$p['probability']) * 100);
            if ($r <= $acc) {
                return $p;
            }
        }
        return $weighted[count($weighted) - 1];
    }

    /**
     * @param mysqli $conn
     * @param int $guestId
     * @param int $amountYen
     * @param string $label
     * @param string|null $expiresAt
     * @param int $clubId
     * @return int
     */
    private static function grantCoupon($conn, $guestId, $amountYen, $label, $expiresAt, $clubId)
    {
        $guestId = (int)$guestId;
        $amountYen = (int)$amountYen;
        $clubId = (int)$clubId;
        $labelEsc = mysqli_real_escape_string($conn, $label);
        $expSql = $expiresAt === null ? 'NULL' : ("'" . mysqli_real_escape_string($conn, $expiresAt) . "'");
        $sql = "INSERT INTO coupon_tickets
            (guest_id, amount_yen, status, expires_at, source_type, source_label, task_id, club_id, created_at)
            VALUES ({$guestId}, {$amountYen}, 'unused', {$expSql}, 'event_gacha', '{$labelEsc}', NULL, {$clubId}, NOW())";
        if (!mysqli_query($conn, $sql)) {
            throw new RuntimeException('coupon insert failed');
        }
        $ticketId = (int)mysqli_insert_id($conn);
        $occurred = date('Y-m-d');
        $sql2 = "INSERT INTO coupon_ledger
            (guest_id, occurred_on, op, item_code, item_label, amount_yen, status, expires_at, ticket_id, task_id, club_id, note, created_at)
            VALUES ({$guestId}, '{$occurred}', 'grant', 'event_gacha', '{$labelEsc}', {$amountYen}, 'unused', {$expSql}, {$ticketId}, NULL, {$clubId}, '', NOW())";
        if (!mysqli_query($conn, $sql2)) {
            throw new RuntimeException('coupon ledger failed');
        }
        return $ticketId;
    }

    /**
     * @param mysqli $conn
     * @param int $campaignId
     * @param int $clubId
     * @param int $guestId
     * @param int|null $memberAccountId
     * @param int $prizeId
     * @param int $slotNo
     * @param int $amountYen
     * @param int $couponTicketId
     * @param string $playedOn
     * @return int
     */
    private static function insertPlay(
        $conn,
        $campaignId,
        $clubId,
        $guestId,
        $memberAccountId,
        $prizeId,
        $slotNo,
        $amountYen,
        $couponTicketId,
        $playedOn,
        $prizeType = null,
        $pointLotId = null
    ) {
        $campaignId = (int)$campaignId;
        $clubId = (int)$clubId;
        $guestId = (int)$guestId;
        $prizeId = (int)$prizeId;
        $slotNo = (int)$slotNo;
        $amountYen = (int)$amountYen;
        $ticketSql = $couponTicketId === null ? 'NULL' : (string)(int)$couponTicketId;
        $memberSql = $memberAccountId === null ? 'NULL' : (string)(int)$memberAccountId;
        $playedOn = mysqli_real_escape_string($conn, $playedOn);
        if ($prizeType !== null) {
            $typeSql = $prizeType === 'point' ? 'point' : 'coupon';
            $lotSql = $pointLotId === null ? 'NULL' : (string)(int)$pointLotId;
            $sql = "INSERT INTO gacha_plays
                (campaign_id, club_id, guest_id, member_account_id, prize_id, slot_no, prize_type,
                 amount_yen, coupon_ticket_id, point_lot_id, played_on, played_at)
                VALUES ({$campaignId}, {$clubId}, {$guestId}, {$memberSql}, {$prizeId}, {$slotNo}, '{$typeSql}',
                        {$amountYen}, {$ticketSql}, {$lotSql}, '{$playedOn}', NOW())";
        } else {
            $sql = "INSERT INTO gacha_plays
                (campaign_id, club_id, guest_id, member_account_id, prize_id, slot_no,
                 amount_yen, coupon_ticket_id, played_on, played_at)
                VALUES ({$campaignId}, {$clubId}, {$guestId}, {$memberSql}, {$prizeId}, {$slotNo},
                        {$amountYen}, {$ticketSql}, '{$playedOn}', NOW())";
        }
        if (!mysqli_query($conn, $sql)) {
            throw new RuntimeException('play insert failed');
        }
        return (int)mysqli_insert_id($conn);
    }
}
