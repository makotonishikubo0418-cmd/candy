<?php

class MemberDb
{
    /** @var Database */
    private $db;

    public function __construct($Database)
    {
        $this->db = $Database;
    }

    public function escape($value)
    {
        return mysqli_real_escape_string($this->db->Conn, (string)$value);
    }

    public function query($sql)
    {
        return $this->db->Query($sql);
    }

    public function fetchOne($sql)
    {
        $result = $this->query($sql);
        if (!$result || $this->db->Num_Rows($result) === 0) {
            return null;
        }
        return $this->db->Fetch_Array($result);
    }

    public function fetchAll($sql)
    {
        $result = $this->query($sql);
        if (!$result) {
            return array();
        }
        $rows = array();
        while ($row = $this->db->Fetch_Array($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function audit($memberId, $action, $detail = '')
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $mid = ($memberId === null) ? 'NULL' : (int)$memberId;
        $detailEsc = $this->escape($detail);
        $ip = $this->escape(MemberUtil::clientIp());
        $actionEsc = $this->escape($action);
        $sql = "INSERT INTO customers_audit_logs (club_id, member_id, action, detail, ip_address) "
            . "VALUES ({$clubId}, {$mid}, '{$actionEsc}', '{$detailEsc}', '{$ip}')";
        $this->query($sql);
    }

    public function findMemberByPhone($phone, $clubId = null)
    {
        $clubId = ($clubId === null) ? (int)MEMBER_CLUB_ID : (int)$clubId;
        $phoneEsc = $this->escape($phone);
        $sql = "SELECT a.* FROM customers_phones p"
            . " INNER JOIN customers_accounts a ON a.id = p.member_id"
            . " WHERE p.club_id = {$clubId} AND p.phone = '{$phoneEsc}' AND a.status = 'active'"
            . " LIMIT 1";
        $row = $this->fetchOne($sql);
        if ($row !== null) {
            return $row;
        }
        $sql = "SELECT * FROM customers_accounts WHERE club_id = {$clubId} AND phone = '{$phoneEsc}' AND status = 'active' LIMIT 1";
        return $this->fetchOne($sql);
    }

    public function findMemberById($id)
    {
        $id = (int)$id;
        $clubId = (int)MEMBER_CLUB_ID;
        $sql = "SELECT * FROM customers_accounts WHERE id = {$id} AND club_id = {$clubId} LIMIT 1";
        return $this->fetchOne($sql);
    }

    public function phoneExists($phone, $excludeMemberId = null)
    {
        $phones = new MemberPhones($this);
        return $phones->findByPhoneClub($phone, $excludeMemberId) !== null;
    }

    public function updateGuestId($memberId, $guestId)
    {
        $memberId = (int)$memberId;
        $guestSql = ($guestId === null) ? 'NULL' : (int)$guestId;
        $sql = "UPDATE customers_accounts SET guest_id = {$guestSql}, updated_at = NOW() WHERE id = {$memberId}";
        return $this->query($sql);
    }

    public function getLegalDocument($docType)
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $typeEsc = $this->escape($docType);
        $sql = "SELECT * FROM customers_legal_documents"
            . " WHERE club_id = {$clubId} AND doc_type = '{$typeEsc}' AND status = 1"
            . " ORDER BY effective_at DESC, id DESC LIMIT 1";
        return $this->fetchOne($sql);
    }

    public function getCurrentTermsVersion()
    {
        $row = $this->getLegalDocument('terms');
        return ($row && isset($row['version'])) ? $row['version'] : '1.0';
    }
}
