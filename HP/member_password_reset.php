<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
require_once __DIR__ . '/includefile/member/bootstrap.php';

$mdb = new MemberDb($Database);
$auth = new MemberAuth($mdb);
member_require_guest($auth);

member_page_header('パスワード再設定');
?>
<div id="memberMsg" class="member-msg"></div>
<form id="memberResetForm">
  <div id="resetStep1" class="member-step active">
    <p style="font-size:14px;color:#ccc;margin:0 0 16px;">登録済みの電話番号に認証コードを送信します。</p>
    <div class="member-field">
      <label for="resetPhone">電話番号</label>
      <input type="tel" id="resetPhone" name="phone" required placeholder="09012345678">
    </div>
    <button type="button" id="resetSendSms" class="member-btn">認証コードを送信</button>
    <p id="resetMockCode" class="member-mock-code"></p>
  </div>
  <div id="resetStep2" class="member-step">
    <div class="member-field">
      <label for="resetCode">認証コード（6桁）</label>
      <input type="text" id="resetCode" name="code" maxlength="6" inputmode="numeric" required>
    </div>
    <button type="button" id="resetVerify" class="member-btn">認証する</button>
  </div>
  <div id="resetStep3" class="member-step">
    <div class="member-field">
      <label for="resetPassword">新しいパスワード（8文字以上）</label>
      <input type="password" id="resetPassword" name="password" minlength="8" required autocomplete="new-password">
    </div>
    <div class="member-field">
      <label for="resetPasswordConfirm">新しいパスワード（確認）</label>
      <input type="password" id="resetPasswordConfirm" name="password_confirm" minlength="8" required autocomplete="new-password">
    </div>
    <input type="hidden" id="resetToken" name="reset_token" value="">
    <button type="submit" class="member-btn">パスワードを変更</button>
  </div>
</form>
<p class="member-links"><a href="member_login.php">ログインに戻る</a></p>
<?php
member_page_footer();
