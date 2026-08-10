<?php

class MemberAuth
{
    /** @var MemberDb */
    private $mdb;

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    public function createSession($memberId, $ttl = null, $setCookie = true)
    {
        $token = MemberUtil::generateToken();
        $hash = MemberUtil::hashToken($token);
        $memberId = (int)$memberId;
        $ttl = ($ttl === null) ? (int)MEMBER_SESSION_TTL : (int)$ttl;
        $hashEsc = $this->mdb->escape($hash);
        $ip = $this->mdb->escape(MemberUtil::clientIp());
        $ua = $this->mdb->escape(isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 512) : '');
        $sql = "INSERT INTO customers_sessions (member_id, session_token, expires_at, ip_address, user_agent) "
            . "VALUES ({$memberId}, '{$hashEsc}', DATE_ADD(NOW(), INTERVAL {$ttl} SECOND), '{$ip}', '{$ua}')";
        $this->mdb->query($sql);
        if ($setCookie) {
            MemberUtil::setCookie(MemberUtil::cookieName('session'), $token, time() + $ttl);
        }
        return $token;
    }

    public function createRememberToken($memberId)
    {
        $token = MemberUtil::generateToken();
        $hash = MemberUtil::hashToken($token);
        $memberId = (int)$memberId;
        $days = (int)MEMBER_REMEMBER_DAYS;
        $hashEsc = $this->mdb->escape($hash);
        $sql = "INSERT INTO customers_remember_tokens (member_id, token_hash, expires_at) "
            . "VALUES ({$memberId}, '{$hashEsc}', DATE_ADD(NOW(), INTERVAL {$days} DAY))";
        $this->mdb->query($sql);
        MemberUtil::setCookie(MemberUtil::cookieName('remember'), $token, time() + ($days * 86400));
        return $token;
    }

    public function revokeAllSessions($memberId)
    {
        $memberId = (int)$memberId;
        $this->mdb->query("UPDATE customers_sessions SET revoked_at = NOW() WHERE member_id = {$memberId} AND revoked_at IS NULL");
        $this->mdb->query("UPDATE customers_remember_tokens SET revoked_at = NOW() WHERE member_id = {$memberId} AND revoked_at IS NULL");
        MemberUtil::clearCookie(MemberUtil::cookieName('session'));
        MemberUtil::clearCookie(MemberUtil::cookieName('remember'));
    }

    public function revokeSessionByTokenHash($hash)
    {
        $hashEsc = $this->mdb->escape($hash);
        $this->mdb->query("UPDATE customers_sessions SET revoked_at = NOW() WHERE session_token = '{$hashEsc}' AND revoked_at IS NULL");
    }

    public function getCurrentMember()
    {
        $sessionCookie = isset($_COOKIE[MemberUtil::cookieName('session')]) ? $_COOKIE[MemberUtil::cookieName('session')] : '';
        if ($sessionCookie !== '') {
            $member = $this->memberFromSessionToken($sessionCookie);
            if ($member !== null) {
                return $member;
            }
        }

        $rememberCookie = isset($_COOKIE[MemberUtil::cookieName('remember')]) ? $_COOKIE[MemberUtil::cookieName('remember')] : '';
        if ($rememberCookie !== '') {
            $member = $this->memberFromRememberToken($rememberCookie);
            if ($member !== null) {
                $this->createSession((int)$member['id']);
                return $member;
            }
        }

        $authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        if (preg_match('/Bearer\s+(\S+)/i', $authHeader, $m)) {
            $member = $this->memberFromSessionToken($m[1]);
            if ($member !== null) {
                return $member;
            }
        }

        return null;
    }

    private function memberFromSessionToken($rawToken)
    {
        $hash = MemberUtil::hashToken($rawToken);
        $hashEsc = $this->mdb->escape($hash);
        $clubId = (int)MEMBER_CLUB_ID;
        $sql = "SELECT a.* FROM customers_sessions s"
            . " INNER JOIN customers_accounts a ON a.id = s.member_id"
            . " WHERE s.session_token = '{$hashEsc}' AND s.revoked_at IS NULL AND s.expires_at > NOW()"
            . " AND a.club_id = {$clubId} AND a.status = 'active' LIMIT 1";
        return $this->mdb->fetchOne($sql);
    }

    private function memberFromRememberToken($rawToken)
    {
        $hash = MemberUtil::hashToken($rawToken);
        $hashEsc = $this->mdb->escape($hash);
        $clubId = (int)MEMBER_CLUB_ID;
        $sql = "SELECT a.* FROM customers_remember_tokens r"
            . " INNER JOIN customers_accounts a ON a.id = r.member_id"
            . " WHERE r.token_hash = '{$hashEsc}' AND r.revoked_at IS NULL AND r.expires_at > NOW()"
            . " AND a.club_id = {$clubId} AND a.status = 'active' LIMIT 1";
        return $this->mdb->fetchOne($sql);
    }

    public function memberFromResetToken($rawToken)
    {
        return $this->memberFromSessionToken($rawToken);
    }

    public function linkCtiGuest($memberId, $phone)
    {
        $link = MemberCti::linkGuestByPhone($phone, $this->mdb, $memberId);
        if ($link['status'] === 0 && $link['guest_id'] !== null) {
            $this->mdb->updateGuestId($memberId, $link['guest_id']);
            $this->mdb->audit($memberId, 'guest_link', 'guest_id=' . $link['guest_id']);
        } elseif ($link['status'] === -8) {
            return $link;
        }
        return $link;
    }

    /**
     * 登録済み電話番号を順に照合して CTI guest を紐づける
     *
     * @return array{status:int,guest_id:?int,message:string}
     */
    public function linkCtiGuestForMember($memberId)
    {
        $memberId = (int)$memberId;
        $member = $this->mdb->findMemberById($memberId);
        if ($member === null) {
            return array('status' => 0, 'guest_id' => null, 'message' => 'member_not_found');
        }
        if (!empty($member['guest_id'])) {
            return array('status' => 0, 'guest_id' => (int)$member['guest_id'], 'message' => 'already_linked');
        }

        $phones = new MemberPhones($this->mdb);
        $tried = array();
        $lastLink = array('status' => 0, 'guest_id' => null, 'message' => 'not_found');

        foreach ($phones->listForMember($memberId) as $row) {
            $phone = $row['phone'];
            if ($phone === '' || isset($tried[$phone])) {
                continue;
            }
            $tried[$phone] = true;
            $lastLink = $this->linkCtiGuest($memberId, $phone);
            if ($lastLink['status'] === -8) {
                return $lastLink;
            }
            $member = $this->mdb->findMemberById($memberId);
            if ($member !== null && !empty($member['guest_id'])) {
                return $lastLink;
            }
        }

        if (!empty($member['phone']) && !isset($tried[$member['phone']])) {
            $lastLink = $this->linkCtiGuest($memberId, $member['phone']);
            if ($lastLink['status'] === -8) {
                return $lastLink;
            }
        }

        $member = $this->mdb->findMemberById($memberId);
        if ($member !== null && !empty($member['guest_id'])) {
            return $lastLink;
        }
        return $lastLink;
    }

    public function login($phone, $password, $rememberMe)
    {
        $member = $this->mdb->findMemberByPhone($phone);
        if ($member === null) {
            return array('status' => -6);
        }
        if ($member['status'] === 'withdrawn') {
            return array('status' => -7);
        }
        if (!password_verify($password, $member['password_hash'])) {
            return array('status' => -6);
        }

        $memberId = (int)$member['id'];
        $this->mdb->query("UPDATE customers_accounts SET last_login_at = NOW() WHERE id = {$memberId}");
        $link = $this->linkCtiGuestForMember($memberId);
        $sessionToken = $this->createSession($memberId);
        if ($rememberMe) {
            $this->createRememberToken($memberId);
        }
        $this->mdb->audit($memberId, 'login', 'remember=' . ($rememberMe ? '1' : '0'));

        $member = $this->mdb->findMemberById($memberId);
        return array(
            'status' => ($link['status'] === -8) ? -8 : 0,
            'session_token' => $sessionToken,
            'member' => $member,
            'guest_linked' => !empty($member['guest_id']),
        );
    }

    public function logout($member)
    {
        if ($member !== null) {
            $this->revokeAllSessions((int)$member['id']);
            $this->mdb->audit((int)$member['id'], 'logout', '');
        } else {
            MemberUtil::clearCookie(MemberUtil::cookieName('session'));
            MemberUtil::clearCookie(MemberUtil::cookieName('remember'));
        }
        return array('status' => 0);
    }

    public function register($phone, $password, $nickname, $termsAgreed, $privacyAgreed, $rememberMe)
    {
        $clubId = (int)MEMBER_CLUB_ID;
        if ($this->mdb->phoneExists($phone)) {
            return array('status' => -4);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $hashEsc = $this->mdb->escape($hash);
        $phoneEsc = $this->mdb->escape($phone);
        $nickEsc = $this->mdb->escape($nickname);
        $termsVer = $this->mdb->escape($this->mdb->getCurrentTermsVersion());

        $sql = "INSERT INTO customers_accounts (club_id, phone, password_hash, nickname, status, terms_agreed_at, privacy_agreed_at, terms_version) "
            . "VALUES ({$clubId}, '{$phoneEsc}', '{$hashEsc}', '{$nickEsc}', 'active', NOW(), NOW(), '{$termsVer}')";
        if (!$this->mdb->query($sql)) {
            return array('status' => -1);
        }

        $member = $this->mdb->findMemberByPhone($phone);
        if ($member === null) {
            return array('status' => -1);
        }

        $memberId = (int)$member['id'];
        $phones = new MemberPhones($this->mdb);
        $phones->addPhone($memberId, $phone);
        $phones->syncAccountPrimary($memberId);

        $link = $this->linkCtiGuestForMember($memberId);
        // 本登録完了時は自動ログインしない（仕様）
        $this->mdb->audit($memberId, 'register', '');

        $member = $this->mdb->findMemberById($memberId);
        return array(
            'status' => ($link['status'] === -8) ? -8 : 0,
            'session_token' => null,
            'member' => $member,
            'guest_linked' => !empty($member['guest_id']),
        );
    }

    public function memberPayload($member)
    {
        $profile = new MemberProfile($this->mdb, $this);
        return $profile->buildPayload($member);
    }
}
