<?php

class MemberUtil
{
    public static function jsonResponse($status, $message = '', $data = null)
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/json; charset=utf-8');
        $out = array('status' => (int)$status, 'message' => $message);
        if ($data !== null) {
            $out['data'] = self::sanitizeForJson($data);
        }
        $flags = JSON_UNESCAPED_UNICODE;
        if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
            $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
        }
        $json = json_encode($out, $flags);
        if ($json === false) {
            $out['data'] = null;
            $json = json_encode($out, $flags);
        }
        echo $json === false ? '{"status":-1,"message":"JSON encode error"}' : $json;
        exit;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function sanitizeForJson($value)
    {
        if (is_array($value)) {
            $out = array();
            foreach ($value as $k => $v) {
                $out[$k] = self::sanitizeForJson($v);
            }
            return $out;
        }
        if (is_string($value)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }
            return $value;
        }
        return $value;
    }

    public static function readJsonInput()
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || $raw === '') {
            return array();
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : array();
    }

    public static function normalizePhone($phone)
    {
        $digits = preg_replace('/\D+/', '', (string)$phone);
        if ($digits === '') {
            return '';
        }
        if (strlen($digits) === 10 && $digits[0] !== '0') {
            $digits = '0' . $digits;
        }
        return $digits;
    }

    public static function isValidPassword($password)
    {
        $len = strlen((string)$password);
        $min = defined('MEMBER_PASSWORD_MIN_LEN') ? (int)MEMBER_PASSWORD_MIN_LEN : 8;
        $max = defined('MEMBER_PASSWORD_MAX_LEN') ? (int)MEMBER_PASSWORD_MAX_LEN : 20;
        if ($len < $min || $len > $max) {
            return false;
        }
        return (bool)preg_match('/^[a-zA-Z0-9]+$/', $password);
    }

    public static function isValidNickname($nickname)
    {
        if ($nickname === '') {
            return true;
        }
        return (bool)preg_match('/^[\x{3040}-\x{30FF}\x{4E00}-\x{9FFF}\x{FF00}-\x{FFEF}a-zA-Z0-9\sー・]+$/u', $nickname);
    }

    public static function isValidBirthday($date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        $parts = explode('-', $date);
        return checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0]);
    }

    public static function fieldErrorResponse($message, $fieldErrors)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'status' => -2,
            'message' => $message,
            'field_errors' => $fieldErrors,
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function isValidPhone($phone)
    {
        return (bool)preg_match('/^0\d{9,10}$/', $phone);
    }

    public static function normalizeEmail($email)
    {
        return strtolower(trim((string)$email));
    }

    public static function isValidEmail($email)
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function maskEmail($email)
    {
        $email = (string)$email;
        if ($email === '' || strpos($email, '@') === false) {
            return $email;
        }
        list($local, $domain) = explode('@', $email, 2);
        $len = strlen($local);
        if ($len <= 1) {
            $masked = '*';
        } elseif ($len <= 3) {
            $masked = substr($local, 0, 1) . str_repeat('*', $len - 1);
        } else {
            $masked = substr($local, 0, 2) . str_repeat('*', max(1, $len - 3)) . substr($local, -1);
        }
        return $masked . '@' . $domain;
    }

    public static function maskPhone($phone)
    {
        $len = strlen($phone);
        if ($len <= 4) {
            return $phone;
        }
        return substr($phone, 0, 3) . str_repeat('*', max(0, $len - 7)) . substr($phone, -4);
    }

    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public static function hashToken($token)
    {
        return hash('sha256', $token);
    }

    public static function clientIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public static function cookieName($suffix)
    {
        return MEMBER_COOKIE_PREFIX . '_member_' . $suffix;
    }

    public static function setCookie($name, $value, $expires)
    {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        setcookie($name, $value, $expires, '/', '', $secure, true);
    }

    public static function clearCookie($name)
    {
        self::setCookie($name, '', time() - 3600);
    }

    public static function mergeInput($json, $post)
    {
        $merged = $post;
        foreach ($json as $k => $v) {
            $merged[$k] = $v;
        }
        return $merged;
    }
}
