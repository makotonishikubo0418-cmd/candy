<?php

class MemberPhones
{
    const MAX_PHONES = 3;

    /** @var MemberDb */
    private $mdb;

    public function __construct(MemberDb $mdb)
    {
        $this->mdb = $mdb;
    }

    public function listForMember($memberId)
    {
        $memberId = (int)$memberId;
        $rows = $this->mdb->fetchAll(
            "SELECT id, phone, is_primary, created_at FROM customers_phones"
            . " WHERE member_id = {$memberId} ORDER BY is_primary DESC, id ASC"
        );
        $items = array();
        foreach ($rows as $row) {
            $items[] = array(
                'id' => (int)$row['id'],
                'phone' => $row['phone'],
                'phone_masked' => MemberUtil::maskPhone($row['phone']),
                'is_primary' => ((int)$row['is_primary'] === 1),
            );
        }
        return $items;
    }

    public function countForMember($memberId)
    {
        $memberId = (int)$memberId;
        $row = $this->mdb->fetchOne("SELECT COUNT(*) AS c FROM customers_phones WHERE member_id = {$memberId}");
        return $row ? (int)$row['c'] : 0;
    }

    public function findByPhoneClub($phone, $excludeMemberId = null)
    {
        $clubId = (int)MEMBER_CLUB_ID;
        $phoneEsc = $this->mdb->escape($phone);
        $sql = "SELECT p.* FROM customers_phones p"
            . " INNER JOIN customers_accounts a ON a.id = p.member_id AND a.status = 'active'"
            . " WHERE p.club_id = {$clubId} AND p.phone = '{$phoneEsc}'";
        if ($excludeMemberId !== null) {
            $sql .= ' AND p.member_id != ' . (int)$excludeMemberId;
        }
        $sql .= ' LIMIT 1';
        return $this->mdb->fetchOne($sql);
    }

    public function getById($memberId, $phoneId)
    {
        $memberId = (int)$memberId;
        $phoneId = (int)$phoneId;
        return $this->mdb->fetchOne(
            "SELECT * FROM customers_phones WHERE id = {$phoneId} AND member_id = {$memberId} LIMIT 1"
        );
    }

    public function getPrimaryPhone($memberId)
    {
        $memberId = (int)$memberId;
        $row = $this->mdb->fetchOne(
            "SELECT phone FROM customers_phones WHERE member_id = {$memberId} AND is_primary = 1 LIMIT 1"
        );
        if ($row !== null) {
            return $row['phone'];
        }
        $member = $this->mdb->findMemberById($memberId);
        return $member ? $member['phone'] : '';
    }

    public function syncAccountPrimary($memberId)
    {
        $phone = $this->getPrimaryPhone($memberId);
        if ($phone === '') {
            return;
        }
        $memberId = (int)$memberId;
        $phoneEsc = $this->mdb->escape($phone);
        $this->mdb->query("UPDATE customers_accounts SET phone = '{$phoneEsc}', updated_at = NOW() WHERE id = {$memberId}");
    }

    /**
     * @return array{status:int,message:string,phone_id?:int}
     */
    public function addPhone($memberId, $phone)
    {
        $memberId = (int)$memberId;
        $clubId = (int)MEMBER_CLUB_ID;
        if ($this->countForMember($memberId) >= self::MAX_PHONES) {
            return array('status' => -2, 'message' => '電話番号は最大3件まで登録できます');
        }
        if ($this->findByPhoneClub($phone, $memberId) !== null) {
            return array('status' => -4, 'message' => 'この電話番号は既に登録されています');
        }
        if ($this->mdb->fetchOne("SELECT id FROM customers_phones WHERE member_id = {$memberId} AND phone = '"
            . $this->mdb->escape($phone) . "' LIMIT 1") !== null) {
            return array('status' => -4, 'message' => 'この電話番号は既に登録されています');
        }

        $phoneEsc = $this->mdb->escape($phone);
        $isPrimary = ($this->countForMember($memberId) === 0) ? 1 : 0;
        $sql = "INSERT INTO customers_phones (club_id, member_id, phone, is_primary)"
            . " VALUES ({$clubId}, {$memberId}, '{$phoneEsc}', {$isPrimary})";
        if (!$this->mdb->query($sql)) {
            return array('status' => -1, 'message' => '電話番号の登録に失敗しました');
        }
        $row = $this->mdb->fetchOne(
            "SELECT id FROM customers_phones WHERE member_id = {$memberId} AND phone = '{$phoneEsc}' ORDER BY id DESC LIMIT 1"
        );
        $this->syncAccountPrimary($memberId);
        return array('status' => 0, 'message' => '電話番号を登録しました', 'phone_id' => (int)$row['id']);
    }

    /**
     * @return array{status:int,message:string}
     */
    public function changePrimaryNumber($memberId, $newPhone)
    {
        $memberId = (int)$memberId;
        $primary = $this->mdb->fetchOne(
            "SELECT * FROM customers_phones WHERE member_id = {$memberId} AND is_primary = 1 LIMIT 1"
        );
        if ($primary === null) {
            return $this->addPhone($memberId, $newPhone);
        }
        if ($primary['phone'] === $newPhone) {
            return array('status' => -2, 'message' => '現在と同じ電話番号です');
        }
        if ($this->findByPhoneClub($newPhone, $memberId) !== null) {
            return array('status' => -4, 'message' => 'この電話番号は既に使用されています');
        }

        $phoneEsc = $this->mdb->escape($newPhone);
        $pid = (int)$primary['id'];
        $this->mdb->query("UPDATE customers_phones SET phone = '{$phoneEsc}', updated_at = NOW() WHERE id = {$pid}");
        $this->syncAccountPrimary($memberId);
        return array('status' => 0, 'message' => '主電話番号を変更しました');
    }

    /**
     * @return array{status:int,message:string,promoted?:bool}
     */
    public function deletePhone($memberId, $phoneId)
    {
        $memberId = (int)$memberId;
        $phoneId = (int)$phoneId;
        $row = $this->getById($memberId, $phoneId);
        if ($row === null) {
            return array('status' => -2, 'message' => '電話番号が見つかりません');
        }
        $count = $this->countForMember($memberId);
        if ($count <= 1) {
            return array('status' => -2, 'message' => '登録電話番号が1件のみのため削除できません');
        }

        $wasPrimary = ((int)$row['is_primary'] === 1);
        $this->mdb->query("DELETE FROM customers_phones WHERE id = {$phoneId} AND member_id = {$memberId}");

        if ($wasPrimary) {
            $next = $this->mdb->fetchOne(
                "SELECT id FROM customers_phones WHERE member_id = {$memberId} ORDER BY id ASC LIMIT 1"
            );
            if ($next !== null) {
                $nid = (int)$next['id'];
                $this->mdb->query("UPDATE customers_phones SET is_primary = 0 WHERE member_id = {$memberId}");
                $this->mdb->query("UPDATE customers_phones SET is_primary = 1 WHERE id = {$nid}");
            }
        }
        $this->syncAccountPrimary($memberId);
        return array(
            'status' => 0,
            'message' => '電話番号を削除しました',
            'promoted' => $wasPrimary,
        );
    }

    /**
     * @return array{status:int,message:string}
     */
    public function setPrimary($memberId, $phoneId)
    {
        $memberId = (int)$memberId;
        $row = $this->getById($memberId, $phoneId);
        if ($row === null) {
            return array('status' => -2, 'message' => '電話番号が見つかりません');
        }
        if ((int)$row['is_primary'] === 1) {
            return array('status' => 0, 'message' => '既に主電話番号です');
        }
        $this->mdb->query("UPDATE customers_phones SET is_primary = 0 WHERE member_id = {$memberId}");
        $this->mdb->query("UPDATE customers_phones SET is_primary = 1 WHERE id = " . (int)$phoneId);
        $this->syncAccountPrimary($memberId);
        return array('status' => 0, 'message' => '主電話番号を切り替えました');
    }
}
