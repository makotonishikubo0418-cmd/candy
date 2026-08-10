<?php

class MemberMail
{
    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @return bool
     */
    public static function send($to, $subject, $body)
    {
        $to = trim($to);
        if ($to === '') {
            return false;
        }

        if (defined('MEMBER_MAIL_MOCK') && MEMBER_MAIL_MOCK) {
            return self::logMock($to, $subject, $body);
        }

        $from = defined('MEMBER_MAIL_FROM') ? MEMBER_MAIL_FROM : 'noreply@localhost';
        $headers = "From: " . $from . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (function_exists('mb_send_mail')) {
            $prevLang = mb_language();
            mb_language('Japanese');
            mb_internal_encoding('UTF-8');
            $ok = @mb_send_mail($to, $subject, $body, $headers);
            mb_language($prevLang);
            return $ok;
        }

        return @mail($to, $subject, $body, $headers);
    }

    private static function logMock($to, $subject, $body)
    {
        $dir = defined('MEMBER_MAIL_LOG_DIR') ? MEMBER_MAIL_LOG_DIR : (CANDY_NEW_ROOT . '/log/member_mail');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file = rtrim($dir, '/\\') . '/mail_' . date('Y-m-d') . '.log';
        $line = '[' . date('Y-m-d H:i:s') . "] TO: {$to}\nSUBJECT: {$subject}\n{$body}\n---\n";
        return (bool)@file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    }

    /**
     * @param MemberDb $mdb
     * @param int $memberId
     * @param string $email
     * @return array{status:int,message:string,mock_code?:string}
     */
    public static function sendVerificationCode(MemberDb $mdb, $memberId, $email)
    {
        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        $email = MemberUtil::normalizeEmail($email);
        if (!MemberUtil::isValidEmail($email)) {
            return array('status' => -2, 'message' => 'メールアドレスの形式が正しくありません');
        }

        $emailEsc = $mdb->escape($email);
        $dup = $mdb->fetchOne(
            "SELECT id FROM customers_accounts WHERE club_id = {$clubId} AND email = '{$emailEsc}'"
            . " AND id != {$memberId} AND status = 'active' LIMIT 1"
        );
        if ($dup !== null) {
            return array('status' => -4, 'message' => 'このメールアドレスは既に使用されています');
        }

        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $codeEsc = $mdb->escape($code);
        $ttl = defined('MEMBER_EMAIL_TTL') ? (int)MEMBER_EMAIL_TTL : 600;
        $sql = "INSERT INTO customers_email_codes (club_id, member_id, email, code, purpose, expires_at)"
            . " VALUES ({$clubId}, {$memberId}, '{$emailEsc}', '{$codeEsc}', 'email_set', DATE_ADD(NOW(), INTERVAL {$ttl} SECOND))";
        if (!$mdb->query($sql)) {
            return array('status' => -1, 'message' => '認証コードの発行に失敗しました');
        }

        $clubName = MEMBER_CLUB_NAME;
        $subject = "【{$clubName}】メールアドレス認証コード";
        $body = "{$clubName} 会員マイページのメール認証コードです。\n\n認証コード: {$code}\n\n有効期限は約" . (int)($ttl / 60) . "分です。";
        self::send($email, $subject, $body);

        $out = array('status' => 0, 'message' => '認証コードを送信しました');
        if (defined('MEMBER_MAIL_MOCK') && MEMBER_MAIL_MOCK) {
            $out['mock_code'] = $code;
        }
        return $out;
    }

    /**
     * @param MemberDb $mdb
     * @param int $memberId
     * @param string $email
     * @param string $code
     * @return bool
     */
    public static function verifyCode(MemberDb $mdb, $memberId, $email, $code)
    {
        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        $email = MemberUtil::normalizeEmail($email);
        $emailEsc = $mdb->escape($email);
        $codeEsc = $mdb->escape(trim($code));
        $maxAttempts = defined('MEMBER_EMAIL_MAX_ATTEMPTS') ? (int)MEMBER_EMAIL_MAX_ATTEMPTS : 5;

        $row = $mdb->fetchOne(
            "SELECT * FROM customers_email_codes"
            . " WHERE club_id = {$clubId} AND member_id = {$memberId} AND email = '{$emailEsc}'"
            . " AND purpose = 'email_set' AND used_at IS NULL AND expires_at > NOW()"
            . " ORDER BY id DESC LIMIT 1"
        );
        if ($row === null) {
            return false;
        }
        if ((int)$row['attempts'] >= $maxAttempts) {
            return false;
        }
        if ($row['code'] !== trim($code)) {
            $id = (int)$row['id'];
            $mdb->query("UPDATE customers_email_codes SET attempts = attempts + 1 WHERE id = {$id}");
            return false;
        }

        $id = (int)$row['id'];
        $mdb->query("UPDATE customers_email_codes SET used_at = NOW() WHERE id = {$id}");
        return true;
    }

    /**
     * @param string $email
     * @param array $infoRow
     * @return bool
     */
    public static function sendMypageInfoNotification($email, $infoRow)
    {
        $clubName = MEMBER_CLUB_NAME;
        $title = isset($infoRow['title']) ? $infoRow['title'] : 'お知らせ';
        $bodyText = isset($infoRow['body']) ? $infoRow['body'] : '';
        $subject = "【{$clubName}】{$title}";
        $body = "{$clubName} 会員マイページに新しいお知らせがあります。\n\n";
        $body .= "■ {$title}\n\n";
        $body .= $bodyText . "\n\n";
        $body .= "マイページで詳細をご確認ください。\n";
        return self::send($email, $subject, $body);
    }
}
