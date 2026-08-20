<?php



class MemberCti

{

    private static $conn = null;



    private static function connection()

    {

        if (self::$conn !== null) {

            return self::$conn;

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
        self::$conn = $ok ? $conn : null;
        return self::$conn;

    }



    /**

     * CTI guests.tel0/tel1/tel2 から数字のみを抽出する SQL 式

     */

    private static function sqlDigitsOnly($column)

    {

        $cols = array($column);

        foreach (array('-', '－', ' ', '　', '.', '(', ')', '+') as $ch) {

            $next = array();

            foreach ($cols as $c) {

                $esc = str_replace("'", "''", $ch);

                $next[] = "REPLACE({$c}, '{$esc}', '')";

            }

            $cols = $next;

        }

        return $cols[0];

    }



    private static function phoneMatchVariants($phone)

    {

        $normalized = MemberUtil::normalizePhone($phone);

        if ($normalized === '') {

            return array();

        }

        $variants = array($normalized);

        if (strlen($normalized) === 11 && $normalized[0] === '0') {

            $variants[] = '81' . substr($normalized, 1);

        }

        if (strlen($normalized) >= 10) {

            $variants[] = substr($normalized, -10);

        }

        return array_values(array_unique($variants));

    }



    /**

     * @return array{status:int,guest_id:?int,message:string}

     */

    public static function linkGuestByPhone($phone, MemberDb $mdb, $memberId = null)

    {

        $conn = self::connection();

        if (!$conn) {

            $mdb->audit($memberId, 'guest_link_error', 'CTI DB connection failed');

            return array('status' => 0, 'guest_id' => null, 'message' => 'cti_unavailable');

        }



        $variants = self::phoneMatchVariants($phone);

        if (empty($variants)) {

            return array('status' => 0, 'guest_id' => null, 'message' => 'invalid_phone');

        }



        $ids = array();

        foreach ($variants as $variant) {

            $phoneEsc = mysqli_real_escape_string($conn, $variant);

            $d0 = self::sqlDigitsOnly('tel0');

            $d1 = self::sqlDigitsOnly('tel1');

            $d2 = self::sqlDigitsOnly('tel2');

            $sql = "SELECT id FROM guests WHERE"

                . " {$d0} = '{$phoneEsc}'"

                . " OR {$d1} = '{$phoneEsc}'"

                . " OR {$d2} = '{$phoneEsc}'"

                . " OR RIGHT({$d0}, 10) = '{$phoneEsc}'"

                . " OR RIGHT({$d1}, 10) = '{$phoneEsc}'"

                . " OR RIGHT({$d2}, 10) = '{$phoneEsc}'"

                . " LIMIT 2";

            $result = mysqli_query($conn, $sql);

            if (!$result) {

                $mdb->audit($memberId, 'guest_link_error', mysqli_error($conn));

                return array('status' => 0, 'guest_id' => null, 'message' => 'query_failed');

            }

            while ($row = mysqli_fetch_assoc($result)) {

                $ids[(int)$row['id']] = true;

            }

            mysqli_free_result($result);

            if (count($ids) > 1) {

                break;

            }

        }



        $idList = array_keys($ids);

        if (count($idList) === 0) {

            return array('status' => 0, 'guest_id' => null, 'message' => 'not_found');

        }

        if (count($idList) > 1) {

            $mdb->audit($memberId, 'guest_link_duplicate', 'phone=' . $phone . ' ids=' . implode(',', $idList));

            return array('status' => -8, 'guest_id' => null, 'message' => 'duplicate_guest');

        }



        return array('status' => 0, 'guest_id' => $idList[0], 'message' => 'linked');

    }

}


