<?php

/**
 * 会員プロフィール — マイページ・AI受付などから共通利用する読み取り／更新ロジック
 */
class MemberProfile
{
    /** @var MemberDb */
    private $mdb;
    /** @var MemberAuth */
    private $auth;
    /** @var MemberPhones */
    private $phones;

    public function __construct(MemberDb $mdb, MemberAuth $auth)
    {
        $this->mdb = $mdb;
        $this->auth = $auth;
        $this->phones = new MemberPhones($mdb);
    }

    /**
     * @param array $member customers_accounts row
     * @return array
     */
    public function buildPayload($member)
    {
        $memberId = (int)$member['id'];
        $notify = new MemberNotification($this->mdb);
        $settings = $notify->getSettings($memberId);
        $email = isset($member['email']) ? $member['email'] : null;
        $nickname = isset($member['nickname']) ? $member['nickname'] : '';
        $birthday = isset($member['birthday']) ? $member['birthday'] : null;

        return array(
            'id' => $memberId,
            'club_id' => (int)$member['club_id'],
            'nickname' => ($nickname === '') ? null : $nickname,
            'nickname_display' => ($nickname === '') ? '未登録' : $nickname,
            'phones' => $this->phones->listForMember($memberId),
            'phone_slots_remaining' => max(0, MemberPhones::MAX_PHONES - $this->phones->countForMember($memberId)),
            'birthday' => ($birthday === null || $birthday === '' || $birthday === '0000-00-00') ? null : $birthday,
            'birthday_display' => ($birthday === null || $birthday === '' || $birthday === '0000-00-00') ? '未登録' : $birthday,
            'email' => ($email === null || $email === '') ? null : MemberUtil::maskEmail($email),
            'has_email' => ($email !== null && $email !== ''),
            'notify_mypage_info' => $settings['notify_mypage_info'],
            'password_set' => true,
            'password_display' => '********',
            'guest_id' => ($member['guest_id'] === null || $member['guest_id'] === '') ? null : (int)$member['guest_id'],
            'has_cti_history' => !empty($member['guest_id']),
            'created_at' => $member['created_at'],
            'last_login_at' => $member['last_login_at'],
        );
    }

    /**
     * @return array{status:int,message:string,data?:array,field_errors?:array}
     */
    public function updateBasic($member, $input)
    {
        $memberId = (int)$member['id'];
        $fieldErrors = array();
        $updates = array();

        if (array_key_exists('nickname', $input)) {
            $nickname = trim((string)$input['nickname']);
            if ($nickname !== '' && !MemberUtil::isValidNickname($nickname)) {
                $fieldErrors['nickname'] = 'ニックネームは日本語・カタカナ・英数字で入力してください';
            } else {
                $updates['nickname'] = $this->mdb->escape($nickname);
            }
        }

        if (array_key_exists('birthday', $input)) {
            $birthday = trim((string)$input['birthday']);
            if ($birthday === '') {
                $updates['birthday'] = 'NULL';
            } elseif (!MemberUtil::isValidBirthday($birthday)) {
                $fieldErrors['birthday'] = '誕生日は西暦年月日（YYYY-MM-DD）で入力してください';
            } else {
                $updates['birthday'] = "'" . $this->mdb->escape($birthday) . "'";
            }
        }

        if (!empty($fieldErrors)) {
            return array('status' => -2, 'message' => '入力内容に不備があります', 'field_errors' => $fieldErrors);
        }
        if (empty($updates)) {
            return array('status' => -2, 'message' => '更新する項目がありません');
        }

        $sets = array();
        foreach ($updates as $col => $val) {
            $sets[] = "{$col} = {$val}";
        }
        $sets[] = 'updated_at = NOW()';
        $this->mdb->query('UPDATE customers_accounts SET ' . implode(', ', $sets) . " WHERE id = {$memberId}");
        $this->mdb->audit($memberId, 'profile_update', implode(',', array_keys($updates)));

        $member = $this->mdb->findMemberById($memberId);
        return array('status' => 0, 'message' => '会員情報を更新しました', 'data' => $this->buildPayload($member));
    }

    /**
     * @return array{status:int,message:string,data?:array,field_errors?:array}
     */
    public function changePassword($member, $input)
    {
        $memberId = (int)$member['id'];
        $current = isset($input['current_password']) ? (string)$input['current_password'] : '';
        $newPass = isset($input['new_password']) ? (string)$input['new_password'] : '';
        $confirm = isset($input['password_confirm']) ? (string)$input['password_confirm'] : '';
        $fieldErrors = array();

        if ($current === '') {
            $fieldErrors['current_password'] = '現在のパスワードを入力してください';
        } elseif (!password_verify($current, $member['password_hash'])) {
            $fieldErrors['current_password'] = '現在のパスワードが正しくありません';
        }
        if (!MemberUtil::isValidPassword($newPass)) {
            $fieldErrors['new_password'] = 'パスワードは半角英数字8〜20文字で入力してください';
        }
        if ($newPass !== $confirm) {
            $fieldErrors['password_confirm'] = '新しいパスワードと確認用が一致しません';
        }
        if (!empty($fieldErrors)) {
            return array('status' => -2, 'message' => 'パスワードを変更できません', 'field_errors' => $fieldErrors);
        }

        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $hashEsc = $this->mdb->escape($hash);
        $this->mdb->query("UPDATE customers_accounts SET password_hash = '{$hashEsc}', updated_at = NOW() WHERE id = {$memberId}");
        $this->auth->revokeAllSessions($memberId);
        $this->mdb->audit($memberId, 'password_change', '');

        return array('status' => 0, 'message' => 'パスワードを変更しました。再度ログインしてください', 'require_relogin' => true);
    }

    /**
     * @return array{status:int,message:string,data?:array}
     */
    public function deleteEmail($member)
    {
        $memberId = (int)$member['id'];
        $this->mdb->query("UPDATE customers_accounts SET email = NULL, updated_at = NOW() WHERE id = {$memberId}");
        $notify = new MemberNotification($this->mdb);
        $notify->updateSettings($memberId, false, false);
        $this->mdb->audit($memberId, 'email_delete', '');
        $member = $this->mdb->findMemberById($memberId);
        return array('status' => 0, 'message' => 'メールアドレスを削除しました', 'data' => $this->buildPayload($member));
    }

    /**
     * アカウント完全削除（パスワード確認必須・関連データは FK CASCADE で削除）
     *
     * @return array{status:int,message:string,field_errors?:array}
     */
    public function deleteAccount($member, $input)
    {
        $memberId = (int)$member['id'];
        $password = isset($input['password']) ? (string)$input['password'] : '';
        $fieldErrors = array();

        if ($password === '') {
            $fieldErrors['delete_password'] = 'パスワードを入力してください';
        } elseif (!password_verify($password, $member['password_hash'])) {
            $fieldErrors['delete_password'] = 'パスワードが正しくありません';
        }
        if (!empty($fieldErrors)) {
            return array('status' => -2, 'message' => 'アカウントを削除できません', 'field_errors' => $fieldErrors);
        }

        $this->auth->revokeAllSessions($memberId);
        $this->mdb->audit($memberId, 'account_delete', 'member_id=' . $memberId);
        if (!$this->mdb->query("DELETE FROM customers_accounts WHERE id = {$memberId}")) {
            return array('status' => -1, 'message' => 'アカウントの削除に失敗しました');
        }

        return array('status' => 0, 'message' => 'アカウントを削除しました');
    }
}
