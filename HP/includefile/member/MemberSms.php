<?php

class MemberSms
{
    /** @var MemberDb */
    private $mdb;

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    /**
     * 認証コードを発行し、設定に応じて SMS 送信する。
     *
     * @param string $phone 正規化済み電話番号（数字のみ）
     * @param string $purpose
     * @return string|null 成功時はコード、送信失敗時は null
     */
    public function sendCode($phone, $purpose, $verifyUrlTemplate = '')
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $phoneEsc = $this->mdb->escape($phone);
        $purposeEsc = $this->mdb->escape($purpose);
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $codeEsc = $this->mdb->escape($code);
        $sql = "INSERT INTO customers_sms_codes (club_id, phone, code, purpose, expires_at) "
            . "VALUES ({$clubId}, '{$phoneEsc}', '{$codeEsc}', '{$purposeEsc}', DATE_ADD(NOW(), INTERVAL "
            . (int)MEMBER_SMS_TTL . " SECOND))";
        $this->mdb->query($sql);

        $verifyUrl = '';
        if ($verifyUrlTemplate !== '') {
            $verifyUrl = str_replace('{code}', $code, $verifyUrlTemplate);
        }

        if (defined('MEMBER_SMS_MOCK') && MEMBER_SMS_MOCK) {
            return $code;
        }

        if (!$this->deliverViaConsoleApi($phone, $code, $verifyUrl)) {
            return null;
        }

        return $code;
    }

    /**
     * Media SMS CONSOLE 個別送信 API
     * @see https://www.sms-console.jp/api/
     */
    private function deliverViaConsoleApi($phone, $code, $verifyUrl = '')
    {
        $username = defined('MEMBER_SMS_USERNAME') ? (string)MEMBER_SMS_USERNAME : '';
        $password = defined('MEMBER_SMS_PASSWORD') ? (string)MEMBER_SMS_PASSWORD : '';
        $url = defined('MEMBER_SMS_API_URL') ? (string)MEMBER_SMS_API_URL : 'https://www.sms-console.jp/api/';

        if ($username === '' || $password === '') {
            $this->logSms('SMS認証情報が未設定です（config.sms.local.php）');
            return false;
        }

        if (!function_exists('curl_init')) {
            $this->logSms('curl 拡張が利用できません');
            return false;
        }

        $template = defined('MEMBER_SMS_BODY_TEMPLATE')
            ? (string)MEMBER_SMS_BODY_TEMPLATE
            : "【CANDY】認証コードは {code} です。有効期限は10分です。";
        if ($verifyUrl === '') {
            $verifyUrl = '（認証コードを登録画面に入力）';
        }
        $body = str_replace(array('{code}', '{url}'), array($code, $verifyUrl), $template);

        $payload = array(
            'username' => $username,
            'password' => $password,
            'mobilenumber' => preg_replace('/\D+/', '', $phone),
            'smstext' => $body,
        );

        $ch = curl_init($url);
        $opts = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => true,
        );

        $caBundle = CANDY_NEW_ROOT . '/includefile/member/cacert.pem';
        if (is_file($caBundle)) {
            $opts[CURLOPT_CAINFO] = $caBundle;
        }

        curl_setopt_array($ch, $opts);
        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($raw === false) {
            $this->logSms("cURL error ({$errno}): {$errmsg}");
            return false;
        }

        $httpCode = isset($info['http_code']) ? (int)$info['http_code'] : 0;
        $headerSize = isset($info['header_size']) ? (int)$info['header_size'] : 0;
        $respBody = substr($raw, $headerSize);

        if ($httpCode !== 200) {
            $this->logSms("SMS API HTTP {$httpCode}: " . trim((string)$respBody));
            return false;
        }

        return true;
    }

    private function logSms($message)
    {
        $dir = CANDY_NEW_ROOT . '/log/member_sms';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file = $dir . '/sms_' . date('Y-m-d') . '.log';
        $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n";
        @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    }

    public function verifyCode($phone, $purpose, $code)
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $phoneEsc = $this->mdb->escape($phone);
        $purposeEsc = $this->mdb->escape($purpose);
        $codeEsc = $this->mdb->escape($code);
        $sql = "SELECT * FROM customers_sms_codes"
            . " WHERE club_id = {$clubId} AND phone = '{$phoneEsc}' AND purpose = '{$purposeEsc}'"
            . " AND used_at IS NULL AND expires_at > NOW()"
            . " ORDER BY id DESC LIMIT 1";
        $row = $this->mdb->fetchOne($sql);
        if ($row === null) {
            return false;
        }
        if ((int)$row['attempts'] >= (int)MEMBER_SMS_MAX_ATTEMPTS) {
            return false;
        }
        if ($row['code'] !== $code) {
            $id = (int)$row['id'];
            $this->mdb->query("UPDATE customers_sms_codes SET attempts = attempts + 1 WHERE id = {$id}");
            return false;
        }
        $id = (int)$row['id'];
        $this->mdb->query("UPDATE customers_sms_codes SET used_at = NOW() WHERE id = {$id}");
        return true;
    }
}
