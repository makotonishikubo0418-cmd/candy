<?php

require_once __DIR__ . '/MemberGirlSchedule.php';
require_once __DIR__ . '/MemberGirlImage.php';

class MemberGirlCard
{
    /**
     * cast_id => girls_data 行（掲載中・最新 id 優先）
     *
     * @param MemberDb $mdb
     * @param int[] $castIds
     * @return array<int,array>
     */
    public static function loadByCastIds(MemberDb $mdb, array $castIds)
    {
        $castIds = array_values(array_unique(array_filter(array_map('intval', $castIds))));
        if (empty($castIds)) {
            return array();
        }

        $clubId = (int)MEMBER_CLUB_ID;
        $idList = implode(',', $castIds);
        $sql = 'SELECT id, cast_id, no, name, name_kana, name_romaji, age, height, bust, cup,'
            . ' waist, hip, newface, status'
            . ' FROM girls_data'
            . " WHERE club_id = {$clubId} AND cast_id IN ({$idList}) AND status = 1"
            . ' ORDER BY id DESC';

        $map = array();
        foreach ($mdb->fetchAll($sql) as $row) {
            $cid = (int)$row['cast_id'];
            if (!isset($map[$cid])) {
                $map[$cid] = $row;
            }
        }

        return $map;
    }

    /**
     * girls_data 行配列をお気に入り一覧と同形式のカードに変換
     *
     * @param MemberDb $mdb
     * @param array<int,array> $rowsByGirlsId
     * @return array<int,array>
     */
    public static function enrichByGirlsIds(MemberDb $mdb, array $rowsByGirlsId)
    {
        if (empty($rowsByGirlsId)) {
            return array();
        }

        $girlsIds = array_map('intval', array_keys($rowsByGirlsId));
        $scheduleMap = array();
        $ctx = MemberGirlSchedule::businessContext();
        try {
            $scheduleMap = MemberGirlSchedule::loadForGirls($mdb, $girlsIds);
        } catch (Throwable $e) {
            $scheduleMap = array();
        }

        $imageMap = MemberGirlImage::loadListThumbFiles($mdb, $girlsIds);
        $cards = array();

        foreach ($rowsByGirlsId as $gid => $row) {
            $gid = (int)$gid;
            $girlSchedules = isset($scheduleMap[$gid]) ? $scheduleMap[$gid] : null;
            $imgFile = isset($imageMap[$gid]) ? $imageMap[$gid] : '';
            $cards[$gid] = self::build($row, $girlSchedules, $ctx, $imgFile);
        }

        return $cards;
    }

    /**
     * @param array $row girls_data または JOIN 行（girl_status 可）
     * @param array<int,array>|null $girlSchedules
     * @param array|null $ctx
     * @param string $imgFile
     * @return array
     */
    public static function build($row, $girlSchedules = null, $ctx = null, $imgFile = '')
    {
        $girlsId = isset($row['girls_id']) ? (int)$row['girls_id'] : (int)$row['id'];
        $girlStatus = isset($row['girl_status']) ? (int)$row['girl_status'] : (int)$row['status'];
        $active = $girlStatus === 1;
        $no = isset($row['no']) ? $row['no'] : '';
        $newface = isset($row['newface']) ? (int)$row['newface'] : 0;

        if ($ctx === null) {
            $ctx = MemberGirlSchedule::businessContext();
        }
        $schedule = MemberGirlSchedule::resolveDisplay($girlSchedules, $ctx);

        return array(
            'girls_id' => $girlsId,
            'no' => $no,
            'name' => isset($row['name']) ? $row['name'] : null,
            'name_kana' => isset($row['name_kana']) ? $row['name_kana'] : null,
            'name_romaji' => (!empty($row['name_romaji'])) ? strtoupper((string)$row['name_romaji']) : null,
            'age' => isset($row['age']) ? (int)$row['age'] : null,
            'height' => isset($row['height']) ? $row['height'] : null,
            'bust' => isset($row['bust']) ? $row['bust'] : null,
            'cup' => MemberGirlSchedule::cupLabel(isset($row['cup']) ? $row['cup'] : 0),
            'waist' => isset($row['waist']) ? $row['waist'] : null,
            'hip' => isset($row['hip']) ? $row['hip'] : null,
            'size_display' => MemberGirlSchedule::formatSizeDisplay($row),
            'newface' => $newface,
            'enrollment_status' => MemberGirlSchedule::enrollmentStatus($girlStatus, $newface),
            'schedule_code' => $schedule['schedule_code'],
            'schedule_label' => $schedule['schedule_label'],
            'schedule_time' => $schedule['schedule_time'],
            'schedule_next' => $schedule['schedule_next'],
            'active' => $active,
            'profile_url' => ($active && $no !== '') ? 'girls.php?no=' . rawurlencode($no) : null,
            'image_url' => MemberGirlImage::buildListThumbUrl($imgFile, 120),
        );
    }
}
