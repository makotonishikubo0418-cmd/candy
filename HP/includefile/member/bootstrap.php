<?php
require_once __DIR__ . '/config.php';

$memberSessionFiles = array(
    '/home/firststar/public_html/group/control/includefile/setting_session_vv.php',
    '/home/firststar/public_html/group_test/control/includefile/setting_session_vv.php',
);
foreach ($memberSessionFiles as $f) {
    if (file_exists($f)) {
        require_once $f;
        break;
    }
}

$memberIncFiles = array(
    '/home/firststar/public_html/group/control/includefile/incfiles_vv.php',
    '/home/firststar/public_html/group_test/control/includefile/incfiles_vv.php',
);
foreach ($memberIncFiles as $f) {
    if (file_exists($f)) {
        require_once $f;
        break;
    }
}

if (!isset($DSN)) {
    http_response_code(500);
    exit('Database configuration not found.');
}

$Database = new Database($DSN);

require_once MEMBER_INCLUDE_DIR . '/MemberUtil.php';
require_once MEMBER_INCLUDE_DIR . '/MemberDb.php';
require_once MEMBER_INCLUDE_DIR . '/MemberSms.php';
require_once MEMBER_INCLUDE_DIR . '/MemberCti.php';
require_once MEMBER_INCLUDE_DIR . '/MemberPhones.php';
require_once MEMBER_INCLUDE_DIR . '/MemberNotification.php';
require_once MEMBER_INCLUDE_DIR . '/MemberMail.php';
require_once MEMBER_INCLUDE_DIR . '/MemberProfile.php';
require_once MEMBER_INCLUDE_DIR . '/MemberAuth.php';
require_once MEMBER_INCLUDE_DIR . '/MemberHistory.php';
require_once MEMBER_INCLUDE_DIR . '/MemberLoyalty.php';
require_once MEMBER_INCLUDE_DIR . '/MemberEvaluation.php';
require_once MEMBER_INCLUDE_DIR . '/MemberGirlSchedule.php';
require_once MEMBER_INCLUDE_DIR . '/MemberGirlImage.php';
require_once MEMBER_INCLUDE_DIR . '/MemberGirlCard.php';
require_once MEMBER_INCLUDE_DIR . '/MemberFavorite.php';
require_once MEMBER_INCLUDE_DIR . '/MemberFavoriteNotify.php';
require_once MEMBER_INCLUDE_DIR . '/MemberMypageInfo.php';
require_once MEMBER_INCLUDE_DIR . '/MemberApi.php';
require_once MEMBER_INCLUDE_DIR . '/layout.php';
