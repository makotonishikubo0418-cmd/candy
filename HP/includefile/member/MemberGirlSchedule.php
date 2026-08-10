<?php

class MemberGirlSchedule
{
    private static $cupMap = array(
        1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E',
        6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J',
    );

    /**
     * @return array{y:int,m:int,d:int,now:int,days:array<int,array{0:int,1:int,2:int}>}
     */
    public static function businessContext()
    {
        $newday = defined('NEWDAY_TIME') ? (int)NEWDAY_TIME : 6;
        if ((int)date('G') < $newday) {
            $baseTs = strtotime('-1 day');
            $now = (int)date('Gi') + 2400;
        } else {
            $baseTs = time();
            $now = (int)date('Gi');
        }

        $y = (int)date('Y', $baseTs);
        $m = (int)date('n', $baseTs);
        $d = (int)date('j', $baseTs);

        $days = array();
        for ($i = 0; $i < 7; $i++) {
            $ts = strtotime('+' . $i . ' day', mktime(0, 0, 0, $m, $d, $y));
            $days[$i] = array((int)date('Y', $ts), (int)date('n', $ts), (int)date('j', $ts));
        }

        return array('y' => $y, 'm' => $m, 'd' => $d, 'now' => $now, 'days' => $days);
    }

    /**
     * @param MemberDb $mdb
     * @param int[] $girlsIds
     * @return array<int,array<int,array>>
     */
    public static function loadForGirls(MemberDb $mdb, array $girlsIds)
    {
        $girlsIds = array_values(array_unique(array_filter(array_map('intval', $girlsIds))));
        if (empty($girlsIds)) {
            return array();
        }

        $ctx = self::businessContext();
        $clubId = (int)MEMBER_CLUB_ID;
        $idList = implode(',', $girlsIds);

        $dateConds = array();
        foreach ($ctx['days'] as $day) {
            $dateConds[] = '(year = ' . $day[0] . ' AND month = ' . $day[1] . ' AND day = ' . $day[2] . ')';
        }

        $sql = 'SELECT * FROM girls_schedule'
            . ' WHERE club_id = ' . $clubId
            . ' AND girls_id IN (' . $idList . ')'
            . " AND (type = '0' OR type = '1' OR type = '6')"
            . ' AND (' . implode(' OR ', $dateConds) . ')'
            . " AND views >= '0' AND status = '1'"
            . ' ORDER BY year, month, day, open_ji, open_fun, end_ji, end_fun, id DESC';

        $map = array();
        foreach ($mdb->fetchAll($sql) as $row) {
            $gid = (int)$row['girls_id'];
            $weekId = self::weekIdForRow($ctx['days'], $row);
            if ($weekId === null) {
                continue;
            }
            if (!isset($map[$gid])) {
                $map[$gid] = array();
            }
            $map[$gid][$weekId] = $row;
        }

        return $map;
    }

    /**
     * @param array $days
     * @param array $row
     * @return int|null
     */
    private static function weekIdForRow($days, $row)
    {
        $y = (int)$row['year'];
        $m = (int)$row['month'];
        $d = (int)$row['day'];
        foreach ($days as $weekId => $day) {
            if ($day[0] === $y && $day[1] === $m && $day[2] === $d) {
                return (int)$weekId;
            }
        }
        return null;
    }

    /**
     * @param array<int,array>|null $girlSchedules weekId => row
     * @param array $ctx
     * @return array{schedule_code:string,schedule_label:string,schedule_time:string,schedule_next:array|null}
     */
    public static function resolveDisplay($girlSchedules, $ctx)
    {
        $today = (is_array($girlSchedules) && isset($girlSchedules[0])) ? $girlSchedules[0] : null;
        $scheType = self::computeScheType($today, $ctx['now']);

        $result = array(
            'schedule_code' => 'no_schedule',
            'schedule_label' => 'CLOSED TODAY',
            'schedule_time' => '',
            'schedule_next' => null,
        );

        if ($scheType === 1) {
            $time = self::formatTimeRange($today);
            $result['schedule_code'] = 'working';
            $result['schedule_label'] = $time;
            $result['schedule_time'] = $time;
        } elseif ($scheType === 6) {
            $result['schedule_code'] = 'tel_check';
            $result['schedule_label'] = 'TEL確認';
        } elseif ($scheType === 8) {
            $result['schedule_code'] = 'closed_today';
            $result['schedule_label'] = '案内終了';
        }

        if ($scheType === 9 || $scheType === 8) {
            $next = self::findNextSchedule($girlSchedules, $ctx, 1);
            if ($next !== null) {
                $result['schedule_next'] = $next;
            }
        }

        return $result;
    }

    /**
     * @param array|null $row
     * @param int $now
     * @return int
     */
    private static function computeScheType($row, $now)
    {
        if ($row === null) {
            return 9;
        }

        $type = isset($row['type']) ? (int)$row['type'] : 0;
        $type2 = isset($row['type2']) ? (int)$row['type2'] : 0;

        if ($type2 === 6) {
            return 6;
        }
        if ($type2 === 3 || $type === 6) {
            return 8;
        }

        if ($type === 0 || $type === 1) {
            $endFun = (int)$row['end_fun'];
            $endTime = ($endFun < 10)
                ? (int)($row['end_ji'] . '0' . $endFun)
                : (int)($row['end_ji'] . $endFun);
            if ($endTime < $now) {
                return 8;
            }
            return 1;
        }

        return 9;
    }

    /**
     * @param array<int,array>|null $girlSchedules
     * @param array $ctx
     * @param int $fromWeekId
     * @return array{date:string,time:string,label:string}|null
     */
    private static function findNextSchedule($girlSchedules, $ctx, $fromWeekId)
    {
        if (!is_array($girlSchedules)) {
            return null;
        }

        for ($w = $fromWeekId; $w <= 6; $w++) {
            if (!isset($girlSchedules[$w])) {
                continue;
            }
            $row = $girlSchedules[$w];
            $type = isset($row['type']) ? (int)$row['type'] : -1;
            if ($type !== 0 && $type !== 1 && $type !== 6) {
                continue;
            }
            $day = $ctx['days'][$w];
            $date = $day[1] . '/' . $day[2];
            $time = self::formatTimeRange($row);
            return array(
                'date' => $date,
                'time' => $time,
                'label' => $date . ' ' . $time,
            );
        }

        return null;
    }

    /**
     * @param array|null $row
     * @return string
     */
    public static function formatTimeRange($row)
    {
        if ($row === null) {
            return '';
        }

        if ((int)$row['open_ji'] === 100) {
            $vtime = '日の出';
        } else {
            $ofun = ((int)$row['open_fun'] === 0) ? '00' : (string)$row['open_fun'];
            $vtime = $row['open_ji'] . ':' . $ofun;
        }

        $vtime .= '~';

        if ((int)$row['end_ji'] === 99) {
            return $vtime;
        }

        $efun = ((int)$row['end_fun'] === 0) ? '00' : (string)$row['end_fun'];
        $vtime .= $row['end_ji'] . ':' . $efun;

        return $vtime;
    }

    /**
     * @param int $girlStatus
     * @param int $newface
     * @return string
     */
    public static function enrollmentStatus($girlStatus, $newface)
    {
        if ((int)$girlStatus !== 1) {
            return '掲載終了';
        }
        $nf = (int)$newface;
        if ($nf === 1) {
            return '体験入店';
        }
        if ($nf === 2) {
            return '新人';
        }
        return '在籍';
    }

    /**
     * @param array $row
     * @return string
     */
    public static function formatSizeDisplay($row)
    {
        $parts = array();
        if (isset($row['age']) && $row['age'] !== '' && $row['age'] !== null) {
            $parts[] = 'AGE' . (int)$row['age'];
        }
        if (!empty($row['height'])) {
            $parts[] = 'T' . $row['height'];
        }
        if (!empty($row['bust'])) {
            $bust = 'B' . $row['bust'];
            $cup = self::cupLabel(isset($row['cup']) ? $row['cup'] : 0);
            if ($cup !== '') {
                $bust .= '-' . $cup;
            }
            $parts[] = $bust;
        }
        if (!empty($row['waist'])) {
            $parts[] = 'W' . $row['waist'];
        }
        if (!empty($row['hip'])) {
            $parts[] = 'H' . $row['hip'];
        }
        return implode(' ', $parts);
    }

    /**
     * @param int|string $cup
     * @return string
     */
    public static function cupLabel($cup)
    {
        $cup = (int)$cup;
        return isset(self::$cupMap[$cup]) ? self::$cupMap[$cup] : '';
    }
}
