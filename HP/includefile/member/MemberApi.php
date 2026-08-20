<?php

class MemberApi
{
    /** @var MemberDb */
    private $mdb;
    /** @var MemberAuth */
    private $auth;
    /** @var MemberSms */
    private $sms;

    public function __construct($Database)
    {
        $this->mdb = new MemberDb($Database);
        $this->auth = new MemberAuth($this->mdb);
        $this->sms = new MemberSms($this->mdb);
    }

    public function handle($fno)
    {
        $json = MemberUtil::readJsonInput();
        $input = MemberUtil::mergeInput($json, $_POST);

        switch ($fno) {
            case '001':
                $this->registerSendSms($input);
                break;
            case '002':
                $this->register($input);
                break;
            case '003':
                $this->login($input);
                break;
            case '004':
                $this->passwordResetSendSms($input);
                break;
            case '005':
                $this->passwordResetVerify($input);
                break;
            case '006':
                $this->passwordResetConfirm($input);
                break;
            case '007':
                $this->legalDocument('terms');
                break;
            case '008':
                $this->legalDocument('privacy');
                break;
            case '101':
                $this->me();
                break;
            case '102':
                $this->meUpdate($input);
                break;
            case '103':
                $this->logout();
                break;
            case '104':
                $this->withdraw($input);
                break;
            case '105':
                $this->phoneChangeSendSms($input);
                break;
            case '106':
                $this->phoneChangeConfirm($input);
                break;
            case '109':
                $this->passwordChange($input);
                break;
            case '110':
                $this->emailDelete();
                break;
            case '111':
                $this->phoneDelete($input);
                break;
            case '112':
                $this->phoneSetPrimary($input);
                break;
            case '107':
                $this->emailSendCode($input);
                break;
            case '108':
                $this->emailConfirm($input);
                break;
            case '201':
                $this->historyList($input);
                break;
            case '202':
                $this->evaluationSave($input);
                break;
            case '203':
                $this->loyaltySummary();
                break;
            case '301':
                $this->favoriteList($input);
                break;
            case '302':
                $this->favoriteAdd($input);
                break;
            case '303':
                $this->favoriteRemove($input);
                break;
            case '304':
                $this->favoriteIds();
                break;
            case '305':
                $this->favoriteScheduleNoticeUnread();
                break;
            case '306':
                $this->favoriteScheduleNoticeMarkRead();
                break;
            case '307':
                $this->guidanceContext();
                break;
            case '401':
                $this->infoList($input);
                break;
            case '402':
                $this->infoDetail($input);
                break;
            case '403':
                $this->infoUnreadCount();
                break;
            case '501':
                $this->notificationGet();
                break;
            case '502':
                $this->notificationUpdate($input);
                break;
            default:
                MemberUtil::jsonResponse(-1, 'Unknown fno');
        }
    }

    private function registerSendSms($input)
    {
        $phone = MemberUtil::normalizePhone(isset($input['phone']) ? $input['phone'] : '');
        if (!MemberUtil::isValidPhone($phone)) {
            MemberUtil::jsonResponse(-2, '電話番号の形式が正しくありません');
        }
        if ($this->mdb->phoneExists($phone)) {
            MemberUtil::jsonResponse(-4, 'この電話番号は既に登録されています');
        }
        $verifyTpl = '';
        if (!empty($_SERVER['HTTP_HOST'])) {
            $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            $scheme = $https ? 'https' : 'http';
            $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
            $base = preg_replace('#/member/api\.php$#', '', $script);
            $verifyTpl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $base . '/member_register.php?sms_code={code}';
        }
        $code = $this->sms->sendCode($phone, 'register', $verifyTpl);
        if ($code === null) {
            MemberUtil::jsonResponse(-1, '認証コードの送信に失敗しました。時間をおいて再度お試しください');
        }
        $payload = array(
            'expires_in' => (int)MEMBER_SMS_TTL,
        );
        if (defined('MEMBER_SMS_MOCK') && MEMBER_SMS_MOCK) {
            $payload['mock_code'] = $code;
        }
        MemberUtil::jsonResponse(0, '', $payload);
    }

    private function register($input)
    {
        $phone = MemberUtil::normalizePhone(isset($input['phone']) ? $input['phone'] : '');
        $code = isset($input['code']) ? trim($input['code']) : '';
        $password = isset($input['password']) ? $input['password'] : '';
        $passwordConfirm = isset($input['password_confirm']) ? $input['password_confirm'] : '';
        $nickname = isset($input['nickname']) ? trim($input['nickname']) : '';
        $termsAgreed = !empty($input['terms_agreed']);
        $privacyAgreed = !empty($input['privacy_agreed']);
        $rememberMe = !empty($input['remember_me']);

        if (!MemberUtil::isValidPhone($phone)) {
            MemberUtil::jsonResponse(-2, '電話番号の形式が正しくありません');
        }
        if (!$this->sms->verifyCode($phone, 'register', $code)) {
            MemberUtil::jsonResponse(-5, '認証コードが正しくないか、期限切れです');
        }
        if (!MemberUtil::isValidPassword($password)) {
            MemberUtil::jsonResponse(-2, 'パスワードは半角英数字8〜20文字で入力してください');
        }
        if ($password !== $passwordConfirm) {
            MemberUtil::jsonResponse(-2, 'パスワードが一致しません');
        }
        if ($nickname === '' || !MemberUtil::isValidNickname($nickname)) {
            MemberUtil::jsonResponse(-2, 'ニックネームを正しく入力してください');
        }
        if (!$termsAgreed || !$privacyAgreed) {
            MemberUtil::jsonResponse(-2, '利用規約とプライバシーポリシーに同意してください');
        }

        $result = $this->auth->register($phone, $password, $nickname, $termsAgreed, $privacyAgreed, $rememberMe);
        if ($result['status'] !== 0 && $result['status'] !== -8) {
            MemberUtil::jsonResponse($result['status'], '登録に失敗しました');
        }

        MemberUtil::jsonResponse($result['status'], $result['status'] === -8 ? 'CTI顧客が複数見つかりました。スタッフにお問い合わせください' : '会員登録が完了しました。ログインしてください', array(
            'member_id' => (int)$result['member']['id'],
            'guest_linked' => $result['guest_linked'],
        ));
    }

    private function login($input)
    {
        $phone = MemberUtil::normalizePhone(isset($input['phone']) ? $input['phone'] : '');
        $password = isset($input['password']) ? $input['password'] : '';
        $rememberMe = !empty($input['remember_me']);

        if (!MemberUtil::isValidPhone($phone) || $password === '') {
            MemberUtil::jsonResponse(-2, '電話番号とパスワードを入力してください');
        }

        $result = $this->auth->login($phone, $password, $rememberMe);
        if ($result['status'] === -6) {
            MemberUtil::jsonResponse(-6, '電話番号またはパスワードが正しくありません');
        }
        if ($result['status'] === -7) {
            MemberUtil::jsonResponse(-7, '退会済みのアカウントです');
        }

        $payload = $this->auth->memberPayload($result['member']);
        $payload['session_token'] = $result['session_token'];
        $msg = ($result['status'] === -8) ? 'CTI顧客が複数見つかりました。スタッフにお問い合わせください' : '';
        MemberUtil::jsonResponse($result['status'], $msg, $payload);
    }

    private function passwordResetSendSms($input)
    {
        $phone = MemberUtil::normalizePhone(isset($input['phone']) ? $input['phone'] : '');
        if (!MemberUtil::isValidPhone($phone)) {
            MemberUtil::jsonResponse(-2, '電話番号の形式が正しくありません');
        }
        $member = $this->mdb->findMemberByPhone($phone);
        if ($member === null || $member['status'] !== 'active') {
            // 存在有無を秘匿
            MemberUtil::jsonResponse(0, '', array('expires_in' => (int)MEMBER_SMS_TTL));
        }
        $code = $this->sms->sendCode($phone, 'password_reset');
        if ($code === null) {
            MemberUtil::jsonResponse(-1, '認証コードの送信に失敗しました。時間をおいて再度お試しください');
        }
        $payload = array(
            'expires_in' => (int)MEMBER_SMS_TTL,
        );
        if (defined('MEMBER_SMS_MOCK') && MEMBER_SMS_MOCK) {
            $payload['mock_code'] = $code;
        }
        MemberUtil::jsonResponse(0, '', $payload);
    }

    private function passwordResetVerify($input)
    {
        $phone = MemberUtil::normalizePhone(isset($input['phone']) ? $input['phone'] : '');
        $code = isset($input['code']) ? trim($input['code']) : '';
        if (!$this->sms->verifyCode($phone, 'password_reset', $code)) {
            MemberUtil::jsonResponse(-5, '認証コードが正しくないか、期限切れです');
        }
        $member = $this->mdb->findMemberByPhone($phone);
        if ($member === null || $member['status'] !== 'active') {
            MemberUtil::jsonResponse(-2, 'アカウントが見つかりません');
        }
        $resetToken = $this->auth->createSession((int)$member['id'], (int)MEMBER_RESET_TTL, false);
        MemberUtil::jsonResponse(0, '', array(
            'reset_token' => $resetToken,
            'expires_in' => (int)MEMBER_RESET_TTL,
        ));
    }

    private function passwordResetConfirm($input)
    {
        $resetToken = isset($input['reset_token']) ? trim($input['reset_token']) : '';
        $password = isset($input['password']) ? $input['password'] : '';
        $passwordConfirm = isset($input['password_confirm']) ? $input['password_confirm'] : '';

        if (strlen($password) < (int)MEMBER_PASSWORD_MIN_LEN) {
            MemberUtil::jsonResponse(-2, 'パスワードは' . MEMBER_PASSWORD_MIN_LEN . '文字以上にしてください');
        }
        if (!MemberUtil::isValidPassword($password)) {
            MemberUtil::jsonResponse(-2, 'パスワードは半角英数字8〜20文字で入力してください');
        }
        if ($password !== $passwordConfirm) {
            MemberUtil::jsonResponse(-2, 'パスワードが一致しません');
        }

        $member = $this->auth->memberFromResetToken($resetToken);
        if ($member === null) {
            MemberUtil::jsonResponse(-3, '再設定トークンが無効です');
        }

        $memberId = (int)$member['id'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $hashEsc = $this->mdb->escape($hash);
        $this->mdb->query("UPDATE customers_accounts SET password_hash = '{$hashEsc}', updated_at = NOW() WHERE id = {$memberId}");
        $this->auth->revokeAllSessions($memberId);
        $this->mdb->audit($memberId, 'password_reset', '');
        MemberUtil::jsonResponse(0, 'パスワードを変更しました。再度ログインしてください');
    }

    private function legalDocument($docType)
    {
        $row = $this->mdb->getLegalDocument($docType);
        if ($row === null) {
            MemberUtil::jsonResponse(-1, 'ドキュメントが見つかりません');
        }
        MemberUtil::jsonResponse(0, '', array(
            'title' => $row['title'],
            'version' => $row['version'],
            'body' => $row['body'],
            'effective_at' => $row['effective_at'],
        ));
    }

    private function requireMember()
    {
        $member = $this->auth->getCurrentMember();
        if ($member === null) {
            MemberUtil::jsonResponse(-3, 'ログインが必要です');
        }
        return $member;
    }

    private function me()
    {
        $member = $this->requireMember();
        $this->auth->linkCtiGuestForMember((int)$member['id']);
        $member = $this->mdb->findMemberById((int)$member['id']);
        MemberUtil::jsonResponse(0, '', $this->auth->memberPayload($member));
    }

    private function meUpdate($input)
    {
        $member = $this->requireMember();
        $profile = new MemberProfile($this->mdb, $this->auth);
        $result = $profile->updateBasic($member, $input);
        if ($result['status'] !== 0) {
            if (!empty($result['field_errors'])) {
                MemberUtil::fieldErrorResponse($result['message'], $result['field_errors']);
            }
            MemberUtil::jsonResponse($result['status'], $result['message']);
        }
        MemberUtil::jsonResponse(0, $result['message'], $result['data']);
    }

    private function passwordChange($input)
    {
        $member = $this->requireMember();
        $profile = new MemberProfile($this->mdb, $this->auth);
        $result = $profile->changePassword($member, $input);
        if ($result['status'] !== 0) {
            if (!empty($result['field_errors'])) {
                MemberUtil::fieldErrorResponse($result['message'], $result['field_errors']);
            }
            MemberUtil::jsonResponse($result['status'], $result['message']);
        }
        MemberUtil::jsonResponse(0, $result['message'], array('require_relogin' => true));
    }

    private function emailDelete()
    {
        $member = $this->requireMember();
        $profile = new MemberProfile($this->mdb, $this->auth);
        $result = $profile->deleteEmail($member);
        MemberUtil::jsonResponse($result['status'], $result['message'], isset($result['data']) ? $result['data'] : null);
    }

    private function logout()
    {
        $member = $this->auth->getCurrentMember();
        $this->auth->logout($member);
        MemberUtil::jsonResponse(0, 'ログアウトしました');
    }

    private function withdraw($input)
    {
        $member = $this->requireMember();
        $profile = new MemberProfile($this->mdb, $this->auth);
        $result = $profile->deleteAccount($member, $input);
        if ($result['status'] !== 0) {
            if (!empty($result['field_errors'])) {
                MemberUtil::fieldErrorResponse($result['message'], $result['field_errors']);
            }
            MemberUtil::jsonResponse($result['status'], $result['message']);
        }
        MemberUtil::jsonResponse(0, $result['message']);
    }

    private function phoneChangeSendSms($input)
    {
        $member = $this->requireMember();
        $newPhone = MemberUtil::normalizePhone(isset($input['phone']) ? $input['phone'] : '');
        $action = isset($input['action']) ? trim($input['action']) : 'add';
        if (!MemberUtil::isValidPhone($newPhone)) {
            MemberUtil::fieldErrorResponse('電話番号の形式が正しくありません', array('phone' => '電話番号の形式が正しくありません'));
        }

        $phones = new MemberPhones($this->mdb);
        $memberId = (int)$member['id'];
        $primary = $phones->getPrimaryPhone($memberId);

        if ($action === 'change_primary') {
            if ($newPhone === $primary) {
                MemberUtil::fieldErrorResponse('現在と同じ電話番号です', array('phone' => '現在と同じ電話番号です'));
            }
        } else {
            if ($phones->countForMember($memberId) >= MemberPhones::MAX_PHONES) {
                MemberUtil::fieldErrorResponse('電話番号は最大3件まで登録できます', array('phone' => '電話番号は最大3件まで登録できます'));
            }
            if ($phones->findByPhoneClub($newPhone, $memberId) !== null) {
                MemberUtil::fieldErrorResponse('この電話番号は既に使用されています', array('phone' => 'この電話番号は既に使用されています'));
            }
        }

        if ($phones->findByPhoneClub($newPhone, $memberId) !== null && $action === 'change_primary') {
            MemberUtil::fieldErrorResponse('この電話番号は既に使用されています', array('phone' => 'この電話番号は既に使用されています'));
        }

        $purpose = ($action === 'change_primary') ? 'phone_change' : 'phone_add';
        $code = $this->sms->sendCode($newPhone, $purpose);
        if ($code === null) {
            MemberUtil::jsonResponse(-1, '認証コードの送信に失敗しました。時間をおいて再度お試しください');
        }
        $payload = array(
            'expires_in' => (int)MEMBER_SMS_TTL,
            'action' => $action,
        );
        if (defined('MEMBER_SMS_MOCK') && MEMBER_SMS_MOCK) {
            $payload['mock_code'] = $code;
        }
        MemberUtil::jsonResponse(0, '', $payload);
    }

    private function phoneChangeConfirm($input)
    {
        $member = $this->requireMember();
        $newPhone = MemberUtil::normalizePhone(isset($input['phone']) ? $input['phone'] : '');
        $code = isset($input['code']) ? trim($input['code']) : '';
        $action = isset($input['action']) ? trim($input['action']) : 'add';
        $purpose = ($action === 'change_primary') ? 'phone_change' : 'phone_add';

        if (!$this->sms->verifyCode($newPhone, $purpose, $code)) {
            MemberUtil::fieldErrorResponse('認証コードが正しくないか、期限切れです', array('code' => '認証コードが正しくないか、期限切れです'));
        }

        $memberId = (int)$member['id'];
        $phones = new MemberPhones($this->mdb);
        $requireRelogin = false;

        if ($action === 'change_primary') {
            $result = $phones->changePrimaryNumber($memberId, $newPhone);
            $requireRelogin = true;
        } else {
            $result = $phones->addPhone($memberId, $newPhone);
        }

        if ($result['status'] !== 0) {
            $fe = array('phone' => $result['message']);
            MemberUtil::fieldErrorResponse($result['message'], $fe);
        }

        $this->auth->linkCtiGuest($memberId, $newPhone);
        if ($requireRelogin) {
            $this->auth->revokeAllSessions($memberId);
            $this->mdb->audit($memberId, 'phone_change', 'new_phone=' . MemberUtil::maskPhone($newPhone));
            MemberUtil::jsonResponse(0, '電話番号を変更しました。再度ログインしてください', array('require_relogin' => true));
        }

        $this->mdb->audit($memberId, 'phone_add', 'phone=' . MemberUtil::maskPhone($newPhone));
        $member = $this->mdb->findMemberById($memberId);
        MemberUtil::jsonResponse(0, $result['message'], $this->auth->memberPayload($member));
    }

    private function phoneDelete($input)
    {
        $member = $this->requireMember();
        $phoneId = isset($input['phone_id']) ? (int)$input['phone_id'] : 0;
        if ($phoneId <= 0) {
            MemberUtil::jsonResponse(-2, '電話番号を指定してください');
        }
        $phones = new MemberPhones($this->mdb);
        $memberId = (int)$member['id'];
        $result = $phones->deletePhone($memberId, $phoneId);
        if ($result['status'] !== 0) {
            MemberUtil::fieldErrorResponse($result['message'], array('phone' => $result['message']));
        }
        if (!empty($result['promoted'])) {
            $this->auth->revokeAllSessions($memberId);
            MemberUtil::jsonResponse(0, $result['message'] . ' 再度ログインしてください', array('require_relogin' => true));
        }
        $member = $this->mdb->findMemberById($memberId);
        MemberUtil::jsonResponse(0, $result['message'], $this->auth->memberPayload($member));
    }

    private function phoneSetPrimary($input)
    {
        $member = $this->requireMember();
        $phoneId = isset($input['phone_id']) ? (int)$input['phone_id'] : 0;
        if ($phoneId <= 0) {
            MemberUtil::jsonResponse(-2, '電話番号を指定してください');
        }
        $phones = new MemberPhones($this->mdb);
        $memberId = (int)$member['id'];
        $result = $phones->setPrimary($memberId, $phoneId);
        if ($result['status'] !== 0) {
            MemberUtil::fieldErrorResponse($result['message'], array('phone' => $result['message']));
        }
        $this->auth->revokeAllSessions($memberId);
        $this->mdb->audit($memberId, 'phone_set_primary', 'phone_id=' . $phoneId);
        MemberUtil::jsonResponse(0, $result['message'] . ' 再度ログインしてください', array('require_relogin' => true));
    }

    private function historyList($input)
    {
        try {
            $member = $this->requireMember();
            $this->auth->linkCtiGuestForMember((int)$member['id']);
            $member = $this->mdb->findMemberById((int)$member['id']);

            if (empty($member['guest_id'])) {
                MemberUtil::jsonResponse(0, '', array(
                    'items' => array(),
                    'total' => 0,
                    'page' => 1,
                    'per_page' => 20,
                    'guest_linked' => false,
                    'link_message' => 'cti_not_linked',
                ));
            }

            $page = isset($input['page']) ? (int)$input['page'] : 1;
            $perPage = isset($input['per_page']) ? (int)$input['per_page'] : 20;
            $result = MemberHistory::getHistoryList((int)$member['guest_id'], $page, $perPage);
            if ($result['status'] !== 0) {
                MemberUtil::jsonResponse($result['status'], $result['message']);
            }

            $eval = new MemberEvaluation($this->mdb);
            $castIds = array();
            $taskIds = array();
            $historyGirlsIds = array();
            foreach ($result['items'] as $item) {
                if (!empty($item['cast_id'])) {
                    $castIds[] = (int)$item['cast_id'];
                }
                if (!empty($item['task_id'])) {
                    $taskIds[] = (int)$item['task_id'];
                }
                if (!empty($item['girls_id'])) {
                    $historyGirlsIds[] = (int)$item['girls_id'];
                }
            }
            $girlsMap = MemberGirlCard::loadByCastIds($this->mdb, $castIds);
            $rowsByGid = array();
            foreach ($girlsMap as $girlRow) {
                $rowsByGid[(int)$girlRow['id']] = $girlRow;
            }
            // history.girls_id のみ残っているケース用
            $missingGids = array();
            foreach ($historyGirlsIds as $gid) {
                if (!isset($rowsByGid[$gid])) {
                    $missingGids[] = $gid;
                }
            }
            if (!empty($missingGids)) {
                $clubId = (int)MEMBER_CLUB_ID;
                $idList = implode(',', array_values(array_unique($missingGids)));
                $extraSql = 'SELECT id, cast_id, no, name, name_kana, name_romaji, age, height, bust, cup,'
                    . ' waist, hip, newface, status'
                    . ' FROM girls_data'
                    . " WHERE club_id = {$clubId} AND id IN ({$idList})";
                foreach ($this->mdb->fetchAll($extraSql) as $girlRow) {
                    $rowsByGid[(int)$girlRow['id']] = $girlRow;
                }
            }
            $girlCards = MemberGirlCard::enrichByGirlsIds($this->mdb, $rowsByGid);
            $evalMap = $eval->getEvaluationsByTaskIds((int)$member['id'], $taskIds);

            $items = array();
            foreach ($result['items'] as $item) {
                $cid = (int)$item['cast_id'];
                $girlRow = isset($girlsMap[$cid]) ? $girlsMap[$cid] : null;
                $girlCard = null;
                if ($girlRow !== null) {
                    $gid = (int)$girlRow['id'];
                    $girlCard = isset($girlCards[$gid]) ? $girlCards[$gid] : null;
                } elseif (!empty($item['girls_id']) && isset($girlCards[(int)$item['girls_id']])) {
                    $girlCard = $girlCards[(int)$item['girls_id']];
                    $girlRow = isset($rowsByGid[(int)$item['girls_id']]) ? $rowsByGid[(int)$item['girls_id']] : null;
                }
                if ($girlCard === null && !empty($item['girl_name'])) {
                    $girlCard = array(
                        'girls_id' => !empty($item['girls_id']) ? (int)$item['girls_id'] : null,
                        'name' => (string)$item['girl_name'],
                        'age' => null,
                    );
                }
                $taskId = !empty($item['task_id']) ? (int)$item['task_id'] : 0;
                $ev = ($taskId > 0 && isset($evalMap[$taskId])) ? $evalMap[$taskId] : null;
                $items[] = array_merge($item, array(
                    'girls_id' => $girlCard && !empty($girlCard['girls_id'])
                        ? (int)$girlCard['girls_id']
                        : (!empty($item['girls_id']) ? (int)$item['girls_id'] : null),
                    'girl' => $girlCard,
                    'can_evaluate' => !empty($item['can_evaluate']) && $taskId > 0 && $girlRow !== null && $ev === null,
                    'evaluation' => $ev ? MemberEvaluation::formatEvaluation($ev) : null,
                ));
            }

            MemberUtil::jsonResponse(0, '', array(
                'items' => $items,
                'total' => $result['total'],
                'page' => max(1, $page),
                'per_page' => min(50, max(1, $perPage)),
                'guest_linked' => true,
            ));
        } catch (Throwable $e) {
            MemberUtil::jsonResponse(-1, '利用履歴の取得に失敗しました');
        }
    }

    private function evaluationSave($input)
    {
        $member = $this->requireMember();
        $taskId = isset($input['task_id']) ? (int)$input['task_id'] : 0;
        $comment = isset($input['comment']) ? (string)$input['comment'] : '';

        // 互換: 旧クライアントの rating 単体も総合として受け付ける
        $ratings = array(
            'rating' => isset($input['rating']) ? (int)$input['rating'] : 0,
            'rating_service' => isset($input['rating_service']) ? (int)$input['rating_service'] : 0,
            'rating_friendliness' => isset($input['rating_friendliness']) ? (int)$input['rating_friendliness'] : 0,
            'rating_cleanliness' => isset($input['rating_cleanliness']) ? (int)$input['rating_cleanliness'] : 0,
            'rating_match' => isset($input['rating_match']) ? (int)$input['rating_match'] : 0,
            'rating_repeat' => isset($input['rating_repeat']) ? (int)$input['rating_repeat'] : 0,
        );

        if ($taskId <= 0) {
            MemberUtil::jsonResponse(-2, '利用履歴を指定してください');
        }

        $this->auth->linkCtiGuestForMember((int)$member['id']);
        $member = $this->mdb->findMemberById((int)$member['id']);
        if (empty($member['guest_id'])) {
            MemberUtil::jsonResponse(-2, 'CTI顧客と連携されていないため評価できません');
        }

        $taskRow = MemberHistory::getTaskForGuest((int)$member['guest_id'], $taskId);
        if ($taskRow === null) {
            MemberUtil::jsonResponse(-2, '指定の利用履歴が見つかりません');
        }

        $eval = new MemberEvaluation($this->mdb);
        $saveResult = $eval->save((int)$member['id'], $taskId, $ratings, $comment, $taskRow);
        if ($saveResult['status'] !== 0) {
            MemberUtil::jsonResponse($saveResult['status'], $saveResult['message']);
        }

        MemberUtil::jsonResponse(0, $saveResult['message'], array(
            'task_id' => $taskId,
            'ratings' => $ratings,
        ));
    }

    private function loyaltySummary()
    {
        try {
            $member = $this->requireMember();
            $this->auth->linkCtiGuestForMember((int)$member['id']);
            $member = $this->mdb->findMemberById((int)$member['id']);

            if (empty($member['guest_id'])) {
                MemberUtil::jsonResponse(0, '', array(
                    'available' => false,
                    'guest_linked' => false,
                    'link_message' => 'cti_not_linked',
                    'rank' => null,
                    'points' => null,
                    'coupons' => null,
                ));
            }

            $result = MemberLoyalty::getSummary((int)$member['guest_id'], (int)MEMBER_CLUB_ID);
            if ($result['status'] !== 0) {
                MemberUtil::jsonResponse($result['status'], $result['message']);
            }
            MemberUtil::jsonResponse(0, '', $result['data']);
        } catch (Throwable $e) {
            MemberUtil::jsonResponse(-1, '会員ランク・ポイント情報の取得に失敗しました');
        }
    }

    private function favoriteList($input)
    {
        try {
            $member = $this->requireMember();
            $page = isset($input['page']) ? (int)$input['page'] : 1;
            $perPage = isset($input['per_page']) ? (int)$input['per_page'] : 20;
            $fav = new MemberFavorite($this->mdb);
            $result = $fav->list((int)$member['id'], $page, $perPage);
            MemberUtil::jsonResponse(0, '', array(
                'items' => $result['items'],
                'total' => $result['total'],
                'page' => max(1, $page),
                'per_page' => min(50, max(1, $perPage)),
            ));
        } catch (Throwable $e) {
            MemberUtil::jsonResponse(-1, 'お気に入りの取得に失敗しました');
        }
    }

    private function favoriteAdd($input)
    {
        $member = $this->requireMember();
        $girlsId = isset($input['girls_id']) ? (int)$input['girls_id'] : 0;
        if ($girlsId <= 0) {
            MemberUtil::jsonResponse(-2, '女の子を指定してください');
        }
        $fav = new MemberFavorite($this->mdb);
        $result = $fav->add((int)$member['id'], $girlsId);
        MemberUtil::jsonResponse($result['status'], $result['message'], array('girls_id' => $girlsId));
    }

    private function favoriteRemove($input)
    {
        $member = $this->requireMember();
        $girlsId = isset($input['girls_id']) ? (int)$input['girls_id'] : 0;
        if ($girlsId <= 0) {
            MemberUtil::jsonResponse(-2, '女の子を指定してください');
        }
        $fav = new MemberFavorite($this->mdb);
        $result = $fav->remove((int)$member['id'], $girlsId);
        MemberUtil::jsonResponse($result['status'], $result['message'], array('girls_id' => $girlsId));
    }

    private function favoriteIds()
    {
        $member = $this->requireMember();
        $fav = new MemberFavorite($this->mdb);
        MemberUtil::jsonResponse(0, '', array('girls_ids' => $fav->getIds((int)$member['id'])));
    }

    private function favoriteScheduleNoticeUnread()
    {
        $member = $this->requireMember();
        $svc = new MemberFavoriteNotify($this->mdb);
        $items = $svc->listUnread((int)$member['id']);
        $out = array();
        foreach ($items as $row) {
            $out[] = array(
                'id' => (int)$row['id'],
                'girls_id' => (int)$row['girls_id'],
                'girl_name' => isset($row['girl_name']) ? $row['girl_name'] : '',
                'girl_no' => isset($row['girl_no']) ? $row['girl_no'] : '',
                'schedule_date' => $row['schedule_date'],
                'schedule_label' => $row['schedule_label'],
                'created_at' => $row['created_at'],
            );
        }
        MemberUtil::jsonResponse(0, '', array(
            'unread_count' => $svc->unreadCount((int)$member['id']),
            'items' => $out,
        ));
    }

    private function favoriteScheduleNoticeMarkRead()
    {
        $member = $this->requireMember();
        $svc = new MemberFavoriteNotify($this->mdb);
        $svc->markAllRead((int)$member['id']);
        MemberUtil::jsonResponse(0, '既読にしました', array('unread_count' => 0));
    }

    /**
     * AI受付向け: お気に入り・評価傾向の参照データ
     */
    private function guidanceContext()
    {
        $member = $this->requireMember();
        $memberId = (int)$member['id'];
        $fav = new MemberFavorite($this->mdb);
        $eval = new MemberEvaluation($this->mdb);
        $favorites = $fav->list($memberId, 1, 50);
        $evaluations = $eval->listRecent($memberId, 50);

        $prefer = array();
        $avoid = array();
        foreach ($evaluations as $e) {
            $gid = (int)$e['girls_id'];
            if ($gid <= 0) {
                continue;
            }
            $match = isset($e['rating_match']) ? (int)$e['rating_match'] : 0;
            $repeat = isset($e['rating_repeat']) ? (int)$e['rating_repeat'] : 0;
            $overall = isset($e['rating']) ? (int)$e['rating'] : 0;
            if ($repeat >= 4 || $match >= 4 || $overall >= 4) {
                $prefer[$gid] = array(
                    'girls_id' => $gid,
                    'girl_name' => $e['girl_name'],
                    'rating' => $overall,
                    'rating_match' => $match,
                    'rating_repeat' => $repeat,
                );
            }
            if ($repeat <= 2 || $match <= 2 || $overall <= 2) {
                $avoid[$gid] = array(
                    'girls_id' => $gid,
                    'girl_name' => $e['girl_name'],
                    'rating' => $overall,
                    'rating_match' => $match,
                    'rating_repeat' => $repeat,
                );
            }
        }

        MemberUtil::jsonResponse(0, '', array(
            'favorites' => isset($favorites['items']) ? $favorites['items'] : array(),
            'favorite_girls_ids' => $fav->getIds($memberId),
            'evaluations' => $evaluations,
            'prefer_girls' => array_values($prefer),
            'avoid_girls' => array_values($avoid),
        ));
    }

    private function infoList($input)
    {
        $member = $this->requireMember();
        $page = isset($input['page']) ? (int)$input['page'] : 1;
        $perPage = isset($input['per_page']) ? (int)$input['per_page'] : 20;
        $info = new MemberMypageInfo($this->mdb);
        $result = $info->listForMember((int)$member['id'], $page, $perPage);
        MemberUtil::jsonResponse(0, '', array(
            'items' => $result['items'],
            'total' => $result['total'],
            'unread_count' => $result['unread_count'],
            'page' => max(1, $page),
            'per_page' => min(50, max(1, $perPage)),
        ));
    }

    private function infoDetail($input)
    {
        $member = $this->requireMember();
        $infoId = isset($input['info_id']) ? (int)$input['info_id'] : 0;
        if ($infoId <= 0) {
            MemberUtil::jsonResponse(-2, 'お知らせを指定してください');
        }
        $info = new MemberMypageInfo($this->mdb);
        $row = $info->getDetailForMember((int)$member['id'], $infoId, true);
        if ($row === null) {
            MemberUtil::jsonResponse(-2, 'お知らせが見つかりません');
        }
        $this->mdb->audit((int)$member['id'], 'info_read', 'info_id=' . $infoId);
        MemberUtil::jsonResponse(0, '', $row);
    }

    private function infoUnreadCount()
    {
        $member = $this->requireMember();
        $info = new MemberMypageInfo($this->mdb);
        MemberUtil::jsonResponse(0, '', array('unread_count' => $info->unreadCount((int)$member['id'])));
    }

    private function emailSendCode($input)
    {
        $member = $this->requireMember();
        $email = isset($input['email']) ? (string)$input['email'] : '';
        $result = MemberMail::sendVerificationCode($this->mdb, (int)$member['id'], $email);
        $data = array('expires_in' => (int)MEMBER_EMAIL_TTL);
        if (isset($result['mock_code'])) {
            $data['mock_code'] = $result['mock_code'];
        }
        MemberUtil::jsonResponse($result['status'], $result['message'], $data);
    }

    private function emailConfirm($input)
    {
        $member = $this->requireMember();
        $email = isset($input['email']) ? (string)$input['email'] : '';
        $code = isset($input['code']) ? trim($input['code']) : '';
        $emailNorm = MemberUtil::normalizeEmail($email);

        if (!MemberMail::verifyCode($this->mdb, (int)$member['id'], $emailNorm, $code)) {
            MemberUtil::jsonResponse(-5, '認証コードが正しくないか、期限切れです');
        }

        $memberId = (int)$member['id'];
        $emailEsc = $this->mdb->escape($emailNorm);
        $clubId = (int)MEMBER_CLUB_ID;
        $dup = $this->mdb->fetchOne(
            "SELECT id FROM customers_accounts WHERE club_id = {$clubId} AND email = '{$emailEsc}'"
            . " AND id != {$memberId} AND status = 'active' LIMIT 1"
        );
        if ($dup !== null) {
            MemberUtil::jsonResponse(-4, 'このメールアドレスは既に使用されています');
        }

        $this->mdb->query("UPDATE customers_accounts SET email = '{$emailEsc}', updated_at = NOW() WHERE id = {$memberId}");
        $this->mdb->audit($memberId, 'email_set', MemberUtil::maskEmail($emailNorm));
        $member = $this->mdb->findMemberById($memberId);
        MemberUtil::jsonResponse(0, 'メールアドレスを登録しました', $this->auth->memberPayload($member));
    }

    private function notificationGet()
    {
        $member = $this->requireMember();
        $notify = new MemberNotification($this->mdb);
        $settings = $notify->getSettings((int)$member['id']);
        $email = isset($member['email']) ? $member['email'] : '';
        MemberUtil::jsonResponse(0, '', array_merge($settings, array(
            'has_email' => ($email !== null && $email !== ''),
        )));
    }

    private function notificationUpdate($input)
    {
        $member = $this->requireMember();
        $notifyMypageInfo = !empty($input['notify_mypage_info']);
        $hasEmail = !empty($member['email']);
        $notify = new MemberNotification($this->mdb);
        $result = $notify->updateSettings((int)$member['id'], $notifyMypageInfo, $hasEmail);
        if ($result['status'] !== 0) {
            if ($notifyMypageInfo && !$hasEmail) {
                MemberUtil::fieldErrorResponse($result['message'], array('notify_mypage_info' => $result['message']));
            }
            MemberUtil::jsonResponse($result['status'], $result['message']);
        }
        $this->mdb->audit((int)$member['id'], 'notification_update', 'notify_mypage_info=' . ($notifyMypageInfo ? '1' : '0'));
        $member = $this->mdb->findMemberById((int)$member['id']);
        MemberUtil::jsonResponse(0, $result['message'], $this->auth->memberPayload($member));
    }
}
