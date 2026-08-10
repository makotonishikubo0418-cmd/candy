<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
require_once __DIR__ . '/includefile/member/bootstrap.php';

$mdb = new MemberDb($Database);
$row = $mdb->getLegalDocument('privacy');

member_page_header('プライバシーポリシー');
if ($row === null) {
    echo '<p>プライバシーポリシーは準備中です。</p>';
} else {
    echo '<p class="member-mock-code">版: ' . htmlspecialchars($row['version'], ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<div class="member-legal">' . nl2br(htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8')) . '</div>';
}
echo '<p class="member-links"><a href="member_register.php">会員登録に戻る</a></p>';
member_page_footer();
