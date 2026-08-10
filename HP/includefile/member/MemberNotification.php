<?php

class MemberNotification
{
    /** @var MemberDb */
    private $mdb;

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    /**
     * @param int $memberId
     * @return array{notify_mypage_info:bool}
     */
    public function getSettings($memberId)
    {
        $memberId = (int)$memberId;
        $row = $this->mdb->fetchOne(
            "SELECT notify_mypage_info FROM customers_notification_settings WHERE member_id = {$memberId} LIMIT 1"
        );
        return array(
            'notify_mypage_info' => ($row && (int)$row['notify_mypage_info'] === 1),
        );
    }

    /**
     * @param int $memberId
     * @param bool $notifyMypageInfo
     * @param bool $hasVerifiedEmail
     * @return array{status:int,message:string}
     */
    public function updateSettings($memberId, $notifyMypageInfo, $hasVerifiedEmail)
    {
        if ($notifyMypageInfo && !$hasVerifiedEmail) {
            return array('status' => -2, 'message' => 'メール通知にはメールアドレスの登録が必要です');
        }

        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        $flag = $notifyMypageInfo ? 1 : 0;

        $exists = $this->mdb->fetchOne(
            "SELECT member_id FROM customers_notification_settings WHERE member_id = {$memberId} LIMIT 1"
        );
        if ($exists !== null) {
            $sql = "UPDATE customers_notification_settings SET notify_mypage_info = {$flag}, updated_at = NOW() WHERE member_id = {$memberId}";
        } else {
            $sql = "INSERT INTO customers_notification_settings (member_id, club_id, notify_mypage_info)"
                . " VALUES ({$memberId}, {$clubId}, {$flag})";
        }

        if (!$this->mdb->query($sql)) {
            return array('status' => -1, 'message' => '通知設定の保存に失敗しました');
        }

        return array('status' => 0, 'message' => '通知設定を保存しました');
    }

    /**
     * 公開中お知らせの未送信分をメール通知
     * @return array{sent:int,skipped:int,errors:int}
     */
    public function sendPendingInfoMails()
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $where = "i.club_id = {$clubId} AND i.status = 1"
            . " AND (i.publish_from IS NULL OR i.publish_from <= NOW())"
            . " AND (i.publish_to IS NULL OR i.publish_to >= NOW())";

        $infos = $this->mdb->fetchAll("SELECT i.* FROM customers_mypage_info i WHERE {$where} ORDER BY i.id ASC");
        $sent = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($infos as $info) {
            $infoId = (int)$info['id'];
            $members = $this->mdb->fetchAll(
                "SELECT m.id, m.email FROM customers_accounts m"
                . " INNER JOIN customers_notification_settings s ON s.member_id = m.id AND s.notify_mypage_info = 1"
                . " WHERE m.club_id = {$clubId} AND m.status = 'active' AND m.email IS NOT NULL AND m.email != ''"
            );

            foreach ($members as $member) {
                $memberId = (int)$member['id'];
                $logged = $this->mdb->fetchOne(
                    "SELECT id FROM customers_info_mail_log WHERE member_id = {$memberId} AND info_id = {$infoId} LIMIT 1"
                );
                if ($logged !== null) {
                    $skipped++;
                    continue;
                }

                $ok = MemberMail::sendMypageInfoNotification($member['email'], $info);
                if ($ok) {
                    $this->mdb->query(
                        "INSERT INTO customers_info_mail_log (member_id, info_id) VALUES ({$memberId}, {$infoId})"
                    );
                    $sent++;
                } else {
                    $errors++;
                }
            }
        }

        return array('sent' => $sent, 'skipped' => $skipped, 'errors' => $errors);
    }
}
