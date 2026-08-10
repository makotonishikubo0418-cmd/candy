<?php

class MemberHistory
{
    /**
     * CTI tasks.start（分）を HH:MM に変換（6時起点）
     */
    public static function min2hm($min)
    {
        if ($min === null || $min === '') {
            return null;
        }
        $min = (int)$min;
        $h = (int)($min / 60) + 6;
        $m = $min % 60;
        return sprintf('%02d:%02d', $h, $m);
    }

    /**
     * YYYY-MM-DD / YYYY/MM/DD → YYYY年MM月DD日
     */
    public static function formatDateJp($date)
    {
        $date = str_replace('-', '/', (string)$date);
        $parts = explode('/', $date);
        if (count($parts) < 3) {
            return (string)$date;
        }
        $y = (int)$parts[0];
        if ($y < 100) {
            $y += 2000;
        }
        return sprintf('%d年%02d月%02d日', $y, (int)$parts[1], (int)$parts[2]);
    }

    /**
     * 日付+開始時刻 → YYYY年MM月DD日 HH:MM
     */
    public static function formatDateTimeJp($date, $startHm)
    {
        $d = self::formatDateJp($date);
        $t = $startHm ? (string)$startHm : '';
        return $t !== '' ? ($d . ' ' . $t) : $d;
    }

    /**
     * @return mysqli|null
     */
    private static function ctiConnection()
    {
        $conn = @mysqli_connect(
            MEMBER_CTI_HOST,
            MEMBER_CTI_USER,
            MEMBER_CTI_PASS,
            MEMBER_CTI_DB
        );
        return $conn ? $conn : null;
    }

    /**
     * @param int $guestId
     * @param int $page
     * @param int $perPage
     * @return array{status:int,items:array,total:int,message:string}
     */
    public static function getHistoryList($guestId, $page = 1, $perPage = 20)
    {
        $conn = self::ctiConnection();
        if (!$conn) {
            return array('status' => -1, 'items' => array(), 'total' => 0, 'message' => 'CTI DBに接続できません');
        }

        $guestId = (int)$guestId;
        $clubId = (int)MEMBER_CLUB_ID;
        $page = max(1, (int)$page);
        $perPage = min(50, max(1, (int)$perPage));
        $offset = ($page - 1) * $perPage;

        $where = "t.guest_id = {$guestId}"
            . " AND t.club_id = {$clubId}"
            . " AND (t.end_stat IS NULL OR t.end_stat NOT IN (1, 2, 3))"
            . " AND NOT (t.stat = 0 AND t.end_stat = 1)";

        $countSql = "SELECT COUNT(*) AS cnt FROM tasks t WHERE {$where}";
        $countRes = mysqli_query($conn, $countSql);
        $total = 0;
        if ($countRes && ($row = mysqli_fetch_assoc($countRes))) {
            $total = (int)$row['cnt'];
        }
        if ($countRes) {
            mysqli_free_result($countRes);
        }

        // 拡張列が無い環境でも動くよう SELECT * を使用
        $sql = "SELECT t.* FROM tasks t WHERE {$where}"
            . " ORDER BY t.date DESC, t.start DESC, t.id DESC"
            . " LIMIT {$offset}, {$perPage}";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            mysqli_close($conn);
            return array('status' => -1, 'items' => array(), 'total' => 0, 'message' => '履歴の取得に失敗しました');
        }

        $items = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = self::formatTaskItem($row);
        }
        mysqli_free_result($result);
        mysqli_close($conn);

        return array('status' => 0, 'items' => $items, 'total' => $total, 'message' => '');
    }

    /**
     * @param array $row
     * @return array
     */
    public static function formatTaskItem($row)
    {
        $startHm = self::min2hm(isset($row['start']) ? $row['start'] : null);
        $endHm = self::min2hm(isset($row['end']) ? $row['end'] : null);
        $date = isset($row['date']) ? $row['date'] : '';
        $nominate = isset($row['nominate']) ? (int)$row['nominate'] : 0;

        $options = self::parseNamedList(isset($row['options_json']) ? $row['options_json'] : null);
        $discounts = self::parseNamedList(isset($row['discounts_json']) ? $row['discounts_json'] : null);

        $coursePrice = isset($row['course_price']) ? (int)$row['course_price'] : 0;
        $optionsTotal = isset($row['options_total']) ? (int)$row['options_total'] : 0;
        $discountTotal = isset($row['discount_total']) ? (int)$row['discount_total'] : 0;
        $pointsUsed = isset($row['points_used']) ? (int)$row['points_used'] : 0;
        $nominateFee = isset($row['nominate_fee']) ? (int)$row['nominate_fee'] : 0;
        $trafficFare = isset($row['traffic_fare']) ? (int)$row['traffic_fare'] : 0;
        $totalPrice = isset($row['total_price']) ? (int)$row['total_price'] : 0;
        if ($totalPrice <= 0 && ($coursePrice > 0 || $optionsTotal > 0)) {
            $totalPrice = max(0, $coursePrice + $optionsTotal + $nominateFee + $trafficFare - $discountTotal - $pointsUsed);
        }

        $payment = isset($row['payment_method']) ? (string)$row['payment_method'] : '';
        $paymentLabel = ($payment === 'credit') ? 'クレジットカード' : (($payment === 'cash' || $payment === '') ? '現金' : $payment);

        $destType = isset($row['destination_type']) ? (string)$row['destination_type'] : '';
        $destText = isset($row['destination_text']) ? (string)$row['destination_text'] : '';
        $destinationDisplay = self::formatDestination($destType, $destText);

        $nominateLabel = 'なし';
        if ($nominate === 1) {
            $nominateLabel = '本指名';
        } elseif ($nominate === 2) {
            $nominateLabel = '特別指名';
        }

        $courseMin = isset($row['course']) ? (int)$row['course'] : 0;

        return array(
            'task_id' => (int)$row['id'],
            'date' => $date,
            'date_display' => self::formatDateTimeJp($date, $startHm),
            'start' => $startHm,
            'end' => $endHm,
            'course' => $courseMin,
            'course_label' => $courseMin > 0 ? ($courseMin . '分コース') : '',
            'cast_id' => (int)$row['cast_id'],
            'stat' => (int)$row['stat'],
            'nominate' => $nominate,
            'nominate_label' => $nominateLabel,
            'end_stat' => (!isset($row['end_stat']) || $row['end_stat'] === null) ? null : (int)$row['end_stat'],
            'can_evaluate' => self::canEvaluateTask($row),
            'total_price' => $totalPrice,
            'total_price_display' => number_format($totalPrice),
            'options' => $options,
            'options_text' => count($options) ? implode('、', $options) : 'なし',
            'discounts' => $discounts,
            'discounts_text' => count($discounts) ? implode('、', $discounts) : 'なし',
            'points_used' => $pointsUsed,
            'points_used_display' => number_format($pointsUsed) . 'ポイント',
            'payment_method' => $payment,
            'payment_label' => $paymentLabel,
            'destination_type' => $destType,
            'destination_display' => $destinationDisplay,
            'price_breakdown' => array(
                'course_price' => $coursePrice,
                'options_total' => $optionsTotal,
                'nominate_fee' => $nominateFee,
                'traffic_fare' => $trafficFare,
                'discount_total' => $discountTotal,
                'points_used' => $pointsUsed,
                'total_price' => $totalPrice,
            ),
        );
    }

    /**
     * options_json / discounts_json → 名前配列
     * @param mixed $json
     * @return string[]
     */
    private static function parseNamedList($json)
    {
        if ($json === null || $json === '') {
            return array();
        }
        $data = json_decode((string)$json, true);
        if (!is_array($data)) {
            return array();
        }
        $names = array();
        foreach ($data as $row) {
            if (is_array($row)) {
                if (!empty($row['name'])) {
                    $names[] = (string)$row['name'];
                } elseif (!empty($row['title'])) {
                    $names[] = (string)$row['title'];
                }
            } elseif (is_string($row) && $row !== '') {
                $names[] = $row;
            }
        }
        return $names;
    }

    /**
     * 自宅派遣は番地以降を隠す／ホテルは名称表示
     */
    private static function formatDestination($type, $text)
    {
        $text = trim((string)$text);
        if ($type === 'hotel') {
            return $text !== '' ? ('ホテル: ' . $text) : 'ホテル';
        }
        if ($type === 'address' || $type === 'home') {
            if ($text === '') {
                return '自宅派遣';
            }
            // 丁目・番地・号より前まで（簡易マスク）
            $masked = preg_replace('/[0-9０-９]+.*$/u', '', $text);
            $masked = preg_replace('/[‐\-－ー−].*$/u', '', (string)$masked);
            $masked = trim((string)$masked);
            if ($masked === '') {
                $masked = mb_substr($text, 0, 12);
            }
            return '自宅派遣: ' . $masked;
        }
        return $text !== '' ? $text : '—';
    }

    /**
     * @param array $taskRow
     */
    public static function canEvaluateTask($taskRow)
    {
        // 完了タスク（stat=0）のみ評価可。予約中(stat=2)は不可
        if ((int)$taskRow['stat'] !== 0) {
            return false;
        }
        $endStat = isset($taskRow['end_stat']) ? $taskRow['end_stat'] : null;
        if ($endStat !== null && in_array((int)$endStat, array(1, 2, 3), true)) {
            return false;
        }
        return true;
    }

    /**
     * @param int $guestId
     * @param int $taskId
     * @return array|null
     */
    public static function getTaskForGuest($guestId, $taskId)
    {
        $conn = self::ctiConnection();
        if (!$conn) {
            return null;
        }
        $guestId = (int)$guestId;
        $taskId = (int)$taskId;
        $clubId = (int)MEMBER_CLUB_ID;
        $sql = "SELECT t.* FROM tasks t"
            . " WHERE t.id = {$taskId} AND t.guest_id = {$guestId} AND t.club_id = {$clubId}"
            . " LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
        if ($result) {
            mysqli_free_result($result);
        }
        mysqli_close($conn);
        return $row;
    }
}
