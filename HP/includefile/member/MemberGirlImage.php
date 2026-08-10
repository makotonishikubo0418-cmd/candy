<?php

class MemberGirlImage
{
    /**
     * 一覧サムネ用ファイル名（girls_images type=2・横画像・先頭1枚）
     *
     * @param MemberDb $mdb
     * @param int[] $girlsIds
     * @return array<int,string>
     */
    public static function loadListThumbFiles(MemberDb $mdb, array $girlsIds)
    {
        $girlsIds = array_values(array_unique(array_filter(array_map('intval', $girlsIds))));
        if (empty($girlsIds)) {
            return array();
        }

        $clubId = (int)MEMBER_CLUB_ID;
        $idList = implode(',', $girlsIds);
        $sql = "SELECT girls_id, filename FROM girls_images"
            . " WHERE club_id = {$clubId} AND type = '2' AND status = 1"
            . " AND girls_id IN ({$idList})"
            . " ORDER BY sort, id DESC";

        $map = array();
        foreach ($mdb->fetchAll($sql) as $row) {
            $gid = (int)$row['girls_id'];
            if (!isset($map[$gid]) && !empty($row['filename'])) {
                $map[$gid] = $row['filename'];
            }
        }

        return $map;
    }

    /**
     * girls_list.php / dataset_girls_list.php と同じ URL 生成
     *
     * @param string $filename
     * @param int $size
     * @return string|null
     */
    public static function buildListThumbUrl($filename, $size = 120)
    {
        if (!defined('IMG_HOME')) {
            return null;
        }

        $size = (int)$size;
        if ($filename === null || $filename === '') {
            return self::buildDmyThumbUrl($size);
        }

        return self::buildThumbUrl($filename, $size, 'w', defined('UP_DIR_W') ? UP_DIR_W : 'gl_w/');
    }

    /**
     * @param int $size
     * @return string|null
     */
    private static function buildDmyThumbUrl($size)
    {
        $imgfile = defined('DAMMY_IMG_SQ_w') ? DAMMY_IMG_SQ_w : 'dmy_w.jpg';
        return self::buildThumbUrl($imgfile, $size, 'dmy', 'dmy/');
    }

    /**
     * @param string $imgfile
     * @param int $size
     * @param string $imgtype
     * @param string $dirSegment club_id 以降のディレクトリ（末尾スラッシュ付き）
     * @return string
     */
    private static function buildThumbUrl($imgfile, $size, $imgtype, $dirSegment)
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $plus = '_' . (int)$size . '_0_0_0';
        $fname = self::filenamePlus($imgfile, $plus);
        $imgUri = $clubId . '/' . $dirSegment . 'thumb/' . $fname;

        if (defined('UP_DIR')) {
            $imgPath = UP_DIR . $imgUri;
            if (is_file($imgPath)) {
                return IMG_HOME . $imgUri;
            }
        }

        $query = http_build_query(array(
            'club' => $clubId,
            'j' => $imgfile,
            'size' => (int)$size,
            'type' => $imgtype,
        ));

        return IMG_HOME . 'resizeimg.php?' . $query;
    }

    /**
     * @param string $filename
     * @param string $plus
     * @return string
     */
    private static function filenamePlus($filename, $plus)
    {
        if (function_exists('FilenamePlus')) {
            return FilenamePlus($filename, $plus);
        }

        $pos = strrpos($filename, '.');
        if ($pos === false) {
            return $filename . $plus;
        }

        return substr($filename, 0, $pos) . $plus . substr($filename, $pos);
    }
}
