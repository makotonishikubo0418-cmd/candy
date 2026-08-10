<?php

function member_page_header($title)
{
    $t = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    echo '<!DOCTYPE html><html lang="ja"><head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<meta name="robots" content="noindex">';
    echo '<title>' . $t . ' | ' . htmlspecialchars(MEMBER_CLUB_NAME, ENT_QUOTES, 'UTF-8') . '</title>';
    echo '<link href="./css/member.css" rel="stylesheet" type="text/css">';
    echo '</head><body class="member-page">';
    echo '<header class="member-header"><a href="./index.php" class="member-logo">' . htmlspecialchars(MEMBER_CLUB_NAME, ENT_QUOTES, 'UTF-8') . ' 会員</a>';
    echo '<a href="./member_login.php" class="member-header-login">ログイン</a>';
    echo '</header>';
    echo '<main class="member-main"><div class="member-card"><h1 class="member-title">' . $t . '</h1>';
}

function member_page_footer()
{
    echo '</div></main>';
    echo '<footer class="member-footer"><a href="./terms.php">利用規約</a> · <a href="./privacy.php">プライバシーポリシー</a></footer>';
    echo '<script src="./js/member.js"></script>';
    echo '</body></html>';
}

function member_require_guest($auth)
{
    if ($auth->getCurrentMember() !== null) {
        header('Location: member_mypage.php');
        exit;
    }
}

function member_require_login($auth)
{
    if ($auth->getCurrentMember() === null) {
        header('Location: member_login.php');
        exit;
    }
}
