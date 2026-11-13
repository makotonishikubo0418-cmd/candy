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
     * YYYY-MM-DD / YYYY/MM/DD / yy/mm/dd … → YYYY-MM-DD
     * @return string|null
     */
    public static function normalizeDateYmd($date)
    {
        $date = trim((string)$date);
        if ($date === '') {
            return null;
        }
        // "26/08/13 水" のように曜日付きの場合は先頭の日付だけ使う
        if (preg_match('/^(\d{2,4})[\/\-](\d{1,2})[\/\-](\d{1,2})/', $date, $m)) {
            $y = (int)$m[1];
            if ($y < 100) {
                $y += 2000;
            }
            return sprintf('%04d-%02d-%02d', $y, (int)$m[2], (int)$m[3]);
        }
        return null;
    }

    /**
     * YYYY-MM-DD / YYYY/MM/DD → YYYY年MM月DD日
     */
    public static function formatDateJp($date)
    {
        $ymd = self::normalizeDateYmd($date);
        if ($ymd === null) {
            return (string)$date;
        }
        $parts = explode('-', $ymd);
        return sprintf('%d年%02d月%02d日', (int)$parts[0], (int)$parts[1], (int)$parts[2]);
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

        $taskWhere = "t.guest_id = {$guestId}"
            . " AND t.club_id = {$clubId}"
            . " AND (t.end_stat IS NULL OR t.end_stat NOT IN (1, 2, 3))"
            . " AND NOT (t.stat = 0 AND t.end_stat = 1)";

        $tasks = array();
        $taskSql = "SELECT t.* FROM tasks t WHERE {$taskWhere}"
            . " ORDER BY t.date DESC, t.start DESC, t.id DESC";
        $taskRes = mysqli_query($conn, $taskSql);
        if ($taskRes) {
            while ($row = mysqli_fetch_assoc($taskRes)) {
                $tasks[] = $row;
            }
            mysqli_free_result($taskRes);
        } else {
            mysqli_close($conn);
            return array('status' => -1, 'items' => array(), 'total' => 0, 'message' => '履歴の取得に失敗しました');
        }

        $historyRows = array();
        $histSql = "SELECT h.* FROM history h"
            . " WHERE h.guest_id = {$guestId} AND h.club_id = {$clubId}"
            . " ORDER BY h.id DESC";
        $histRes = mysqli_query($conn, $histSql);
        if ($histRes) {
            while ($row = mysqli_fetch_assoc($histRes)) {
                $historyRows[] = $row;
            }
            mysqli_free_result($histRes);
        }
        // history テーブルが無い／権限エラーでも tasks のみで継続

        $taskIds = array();
        foreach ($tasks as $task) {
            $taskIds[] = (int)$task['id'];
        }
        $salesByTask = self::loadSalesByTask($conn, $taskIds);
        $courseNames = self::loadCourseNames($conn, $clubId);

        mysqli_close($conn);

        $tasksByDate = array();
        foreach ($tasks as $task) {
            $ymd = self::normalizeDateYmd(isset($task['date']) ? $task['date'] : '');
            if ($ymd === null) {
                continue;
            }
            if (!isset($tasksByDate[$ymd])) {
                $tasksByDate[$ymd] = array();
            }
            $tasksByDate[$ymd][] = $task;
        }

        $usedTaskIds = array();
        $merged = array();

        foreach ($historyRows as $h) {
            $ymd = self::normalizeDateYmd(isset($h['date']) ? $h['date'] : '');
            $matched = null;
            if ($ymd !== null && isset($tasksByDate[$ymd])) {
                $matched = self::matchTaskForHistory(
                    $tasksByDate[$ymd],
                    $clubId,
                    (int)(isset($h['cast_id']) ? $h['cast_id'] : 0),
                    (string)(isset($h['course']) ? $h['course'] : ''),
                    $usedTaskIds
                );
            }

            if ($matched !== null) {
                $usedTaskIds[] = (int)$matched['id'];
                $item = self::formatTaskItem($matched, self::taskExtra($matched, $salesByTask, $courseNames));
                $item['history_id'] = (int)$h['id'];
                $item['source'] = 'task';
                if (empty($item['girls_id']) && !empty($h['girls_id'])) {
                    $item['girls_id'] = (int)$h['girls_id'];
                }
                if (!empty($h['girl_name']) && (string)$h['girl_name'] !== '0') {
                    $item['girl_name'] = (string)$h['girl_name'];
                }
                $merged[] = $item;
            } else {
                $merged[] = self::formatHistoryItem($h, $courseNames);
            }
        }

        // history 未登録の当日タスクなど
        foreach ($tasks as $task) {
            $tid = (int)$task['id'];
            if (in_array($tid, $usedTaskIds, true)) {
                continue;
            }
            $item = self::formatTaskItem($task, self::taskExtra($task, $salesByTask, $courseNames));
            $item['history_id'] = null;
            $item['source'] = 'task';
            $merged[] = $item;
        }

        usort($merged, array('MemberHistory', 'compareHistoryItems'));

        $total = count($merged);
        $items = array_slice($merged, $offset, $perPage);

        return array('status' => 0, 'items' => $items, 'total' => $total, 'message' => '');
    }

    /**
     * CTI 売上（確定時に保存した女の子名・コース名・お客様支払額）を予約ごとに取得
     * @param mysqli $conn
     * @param int[] $taskIds
     * @return array<int,array>
     */
    private static function loadSalesByTask($conn, array $taskIds)
    {
        $taskIds = array_values(array_unique(array_filter(array_map('intval', $taskIds))));
        if (empty($taskIds)) {
            return array();
        }
        $out = array();
        foreach (array_chunk($taskIds, 500) as $chunk) {
            $sql = 'SELECT id, task_id, cast_name, course_name, course_min, customer_pay, coupon_used_total, points_used'
                . ' FROM sales WHERE status = \'active\' AND task_id IN (' . implode(',', $chunk) . ')'
                . ' ORDER BY id ASC';
            $res = @mysqli_query($conn, $sql);
            if (!$res) {
                return $out;
            }
            while ($row = mysqli_fetch_assoc($res)) {
                $out[(int)$row['task_id']] = $row;
            }
            mysqli_free_result($res);
        }
        return $out;
    }

    /**
     * コース分数 => コース名（コース名に含まれる数字＝分数）
     * @param mysqli $conn
     * @return array<int,string>
     */
    private static function loadCourseNames($conn, $clubId)
    {
        $clubId = (int)$clubId;
        $out = array();
        $res = @mysqli_query($conn, "SELECT course_name FROM course_master WHERE shop_id = {$clubId} AND status = 1 ORDER BY sort_order ASC, id ASC");
        if (!$res) {
            return $out;
        }
        while ($row = mysqli_fetch_assoc($res)) {
            $name = trim((string)$row['course_name']);
            $min = (int)preg_replace('/\D+/', '', $name);
            if ($min > 0 && $name !== '' && !isset($out[$min])) {
                $out[$min] = $name;
            }
        }
        mysqli_free_result($res);
        return $out;
    }

    /**
     * @param array $task
     * @param array<int,array> $salesByTask
     * @param array<int,string> $courseNames
     * @return array
     */
    private static function taskExtra($task, array $salesByTask, array $courseNames)
    {
        $tid = (int)$task['id'];
        return array(
            'sale' => isset($salesByTask[$tid]) ? $salesByTask[$tid] : null,
            'course_names' => $courseNames,
        );
    }

    /**
     * 「コース名（N分）」。コース名が分からない場合は「N分コース」
     * @param array<int,string> $courseNames
     */
    private static function courseLabel($courseMin, $courseName, array $courseNames)
    {
        $courseMin = (int)$courseMin;
        $name = trim((string)$courseName);
        if ($name === '' && $courseMin > 0 && isset($courseNames[$courseMin])) {
            $name = $courseNames[$courseMin];
        }
        if ($name === '') {
            return $courseMin > 0 ? ($courseMin . '分コース') : '';
        }
        if ($courseMin > 0 && strpos($name, (string)$courseMin) === false) {
            return $name . '（' . $courseMin . '分）';
        }
        return $name;
    }

    /**
     * @param array $a
     * @param array $b
     * @return int
     */
    public static function compareHistoryItems($a, $b)
    {
        $da = self::normalizeDateYmd(isset($a['date']) ? $a['date'] : '');
        $db = self::normalizeDateYmd(isset($b['date']) ? $b['date'] : '');
        if ($da !== $db) {
            if ($da === null) {
                return 1;
            }
            if ($db === null) {
                return -1;
            }
            return strcmp($db, $da);
        }
        $sa = isset($a['start']) ? (string)$a['start'] : '';
        $sb = isset($b['start']) ? (string)$b['start'] : '';
        if ($sa !== $sb) {
            return strcmp($sb, $sa);
        }
        $ida = !empty($a['task_id']) ? (int)$a['task_id'] : (int)(isset($a['history_id']) ? $a['history_id'] : 0);
        $idb = !empty($b['task_id']) ? (int)$b['task_id'] : (int)(isset($b['history_id']) ? $b['history_id'] : 0);
        return $idb - $ida;
    }

    /**
     * @param list<array> $tasks
     * @param list<int> $usedTaskIds
     * @return array|null
     */
    private static function matchTaskForHistory(array $tasks, $shopId, $castId, $course, array $usedTaskIds)
    {
        $matched = array();
        foreach ($tasks as $task) {
            $taskId = (int)$task['id'];
            if (in_array($taskId, $usedTaskIds, true)) {
                continue;
            }
            if ((int)$task['club_id'] === (int)$shopId
                && (int)$task['cast_id'] === (int)$castId
                && (string)$task['course'] === (string)$course) {
                $matched[] = $task;
            }
        }
        if (count($matched) === 0) {
            foreach ($tasks as $task) {
                $taskId = (int)$task['id'];
                if (in_array($taskId, $usedTaskIds, true)) {
                    continue;
                }
                if ((int)$task['club_id'] === (int)$shopId
                    && (int)$task['cast_id'] === (int)$castId) {
                    $matched[] = $task;
                }
            }
        }
        if (count($matched) === 0) {
            return null;
        }
        // 同一候補が複数ある場合は id が大きい（新しい）ものを採用
        usort($matched, function ($x, $y) {
            return (int)$y['id'] - (int)$x['id'];
        });
        return $matched[0];
    }

    /**
     * history 行のみ（tasks 削除済み）
     * @param array $row
     * @return array
     */
    public static function formatHistoryItem($row, array $courseNames = array())
    {
        $dateRaw = isset($row['date']) ? $row['date'] : '';
        $ymd = self::normalizeDateYmd($dateRaw);
        $date = $ymd !== null ? $ymd : $dateRaw;
        $courseMin = isset($row['course']) ? (int)$row['course'] : 0;
        $girlName = isset($row['girl_name']) ? (string)$row['girl_name'] : '';
        if ($girlName === '0') {
            $girlName = '';
        }
        $girlsId = (!empty($row['girls_id'])) ? (int)$row['girls_id'] : null;

        return array(
            'task_id' => 0,
            'history_id' => (int)$row['id'],
            'source' => 'history',
            'date' => $date,
            'date_display' => self::formatDateJp($date),
            'start' => null,
            'end' => null,
            'course' => $courseMin,
            'course_label' => self::courseLabel($courseMin, '', $courseNames),
            'cast_id' => (int)(isset($row['cast_id']) ? $row['cast_id'] : 0),
            'girls_id' => $girlsId,
            'girl_name' => $girlName,
            'stat' => 0,
            'nominate' => 0,
            'nominate_label' => '—',
            'end_stat' => null,
            'can_evaluate' => false,
            'total_price' => 0,
            'total_price_display' => '—',
            'options' => array(),
            'options_text' => '—',
            'discounts' => array(),
            'discounts_text' => '—',
            'points_used' => 0,
            'points_used_display' => '—',
            'payment_method' => '',
            'payment_label' => '—',
            'destination_type' => '',
            'destination_display' => '—',
            'price_breakdown' => array(
                'course_price' => 0,
                'options_total' => 0,
                'nominate_fee' => 0,
                'traffic_fare' => 0,
                'discount_total' => 0,
                'points_used' => 0,
                'coupon_used' => 0,
                'total_price' => 0,
            ),
        );
    }

    /**
     * @param array $row
     * @return array
     */
    public static function formatTaskItem($row, array $extra = array())
    {
        $sale = isset($extra['sale']) ? $extra['sale'] : null;
        $courseNames = isset($extra['course_names']) ? $extra['course_names'] : array();
        $startHm = self::min2hm(isset($row['start']) ? $row['start'] : null);
        $endHm = self::min2hm(isset($row['end']) ? $row['end'] : null);
        $dateRaw = isset($row['date']) ? $row['date'] : '';
        $ymd = self::normalizeDateYmd($dateRaw);
        $date = $ymd !== null ? $ymd : $dateRaw;
        $nominate = isset($row['nominate']) ? (int)$row['nominate'] : 0;

        $options = self::parseNamedList(isset($row['options_json']) ? $row['options_json'] : null);
        $discounts = self::parseNamedList(isset($row['discounts_json']) ? $row['discounts_json'] : null);

        $coursePrice = isset($row['course_price']) ? (int)$row['course_price'] : 0;
        $optionsTotal = isset($row['options_total']) ? (int)$row['options_total'] : 0;
        $discountTotal = isset($row['discount_total']) ? (int)$row['discount_total'] : 0;
        $pointsUsed = isset($row['points_used']) ? (int)$row['points_used'] : 0;
        $nominateFee = isset($row['nominate_fee']) ? (int)$row['nominate_fee'] : 0;
        $trafficFare = isset($row['traffic_fare']) ? (int)$row['traffic_fare'] : 0;
        $couponUsed = isset($row['coupon_used_total']) ? (int)$row['coupon_used_total'] : 0;
        $totalPrice = isset($row['total_price']) ? (int)$row['total_price'] : 0;
        if ($sale !== null && $sale['customer_pay'] !== null && $sale['customer_pay'] !== '') {
            // 売上確定済みは確定時のお客様支払額（ポイント・クーポン控除後）
            $totalPrice = max(0, (int)$sale['customer_pay']);
        } elseif ($totalPrice <= 0 && $pointsUsed <= 0 && $couponUsed <= 0 && ($coursePrice > 0 || $optionsTotal > 0)) {
            // 合計未保存の古い予約のみ再計算する（ポイント・クーポンで0円になった予約は再計算しない）
            $totalPrice = max(0, $coursePrice + $optionsTotal + $nominateFee + $trafficFare - $discountTotal);
        }
        $saleCastName = ($sale !== null && isset($sale['cast_name'])) ? trim((string)$sale['cast_name']) : '';
        $saleCourseName = ($sale !== null && isset($sale['course_name'])) ? (string)$sale['course_name'] : '';

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
            'history_id' => null,
            'source' => 'task',
            'date' => $date,
            'date_display' => self::formatDateTimeJp($date, $startHm),
            'start' => $startHm,
            'end' => $endHm,
            'course' => $courseMin,
            'course_label' => self::courseLabel($courseMin, $saleCourseName, $courseNames),
            'cast_id' => (int)$row['cast_id'],
            'girls_id' => null,
            'girl_name' => ($saleCastName !== '' && $saleCastName !== '0') ? $saleCastName : '',
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
                'coupon_used' => $couponUsed,
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
            // 丁目・番地・号より前まで（簡易マスク）。漢数字の番地（十一番地・三丁目など）も省く
            $masked = preg_replace('/[0-9０-９]+.*$/u', '', $text);
            $masked = preg_replace('/[〇一二三四五六七八九十百千]+(丁目|番地|番|号|の)(?![町街通])[\s\S]*$/u', '', (string)$masked);
            $masked = preg_replace('/[‐\-－−].*$/u', '', (string)$masked);
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
