(function () {
  'use strict';

  var API = './member/api.php';
  var favoriteIds = [];
  var favoritesPage = 1;

  function showMsg(el, text, type) {
    if (!el) return;
    el.textContent = text;
    el.className = 'member-msg ' + (type || 'error');
  }

  function hideMsg(el) {
    if (!el) return;
    el.className = 'member-msg';
    el.textContent = '';
  }

  function postJson(fno, body) {
    var ctrl = typeof AbortController !== 'undefined' ? new AbortController() : null;
    var timer = ctrl ? setTimeout(function () { ctrl.abort(); }, 12000) : null;
    var opts = {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify(body || {})
    };
    if (ctrl) opts.signal = ctrl.signal;
    return fetch(API + '?fno=' + encodeURIComponent(fno), opts).then(function (r) {
      if (timer) clearTimeout(timer);
      return r.text().then(function (text) {
        if (!text) {
          throw new Error('empty response');
        }
        try {
          return JSON.parse(text);
        } catch (e) {
          console.error('member api fno=' + fno + ' invalid JSON:', text.substring(0, 300));
          throw new Error('invalid json');
        }
      });
    }, function (err) {
      if (timer) clearTimeout(timer);
      if (err && err.name === 'AbortError') {
        throw new Error('timeout');
      }
      throw err;
    });
  }

  function apiErrorMessage(err, fallback) {
    if (err && err.message === 'invalid json') {
      return 'サーバー応答が不正です（APIエラー）。ファイルのアップロード漏れがないかご確認ください。';
    }
    if (err && err.message === 'timeout') {
      return '応答がありません。時間をおいて再度お試しください。';
    }
    return fallback || '通信エラーが発生しました';
  }

  function bindLogin() {
    var form = document.getElementById('memberLoginForm');
    if (!form) return;
    var msg = document.getElementById('memberMsg');
    var phoneEl = document.getElementById('loginPhone');
    var savePhoneEl = document.getElementById('loginSavePhone');
    var PHONE_KEY = 'candy_member_saved_phone';

    if (phoneEl) {
      try {
        var saved = localStorage.getItem(PHONE_KEY);
        if (saved) {
          phoneEl.value = saved;
          if (savePhoneEl) savePhoneEl.checked = true;
        }
      } catch (e) {}
    }

    if (msg && /[?&]registered=1/.test(window.location.search)) {
      showMsg(msg, '会員登録が完了しました。ログインしてください。', 'success');
    }
    if (msg && /[?&]deleted=1/.test(window.location.search)) {
      showMsg(msg, 'アカウントを削除しました。', 'success');
    }
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      hideMsg(msg);
      var fd = new FormData(form);
      var phone = fd.get('phone');
      try {
        if (fd.get('save_phone') === '1') {
          localStorage.setItem(PHONE_KEY, String(phone || ''));
        } else {
          localStorage.removeItem(PHONE_KEY);
        }
      } catch (e2) {}
      postJson('003', {
        phone: phone,
        password: fd.get('password'),
        remember_me: fd.get('remember_me') === '1'
      }).then(function (res) {
        if (res.status === 0 || res.status === -8) {
          window.location.href = 'member_mypage.php';
          return;
        }
        showMsg(msg, res.message || 'ログインに失敗しました', 'error');
      }).catch(function () {
        showMsg(msg, '通信エラーが発生しました', 'error');
      });
    });
  }

  function bindRegister() {
    var form = document.getElementById('memberRegisterForm');
    if (!form) return;
    var msg = document.getElementById('memberMsg');
    var stepInput = document.getElementById('regStepInput');
    var stepConfirm = document.getElementById('regStepConfirm');
    var stepCode = document.getElementById('regStepCode');
    var sendBtn = document.getElementById('regSendSms');
    var toConfirmBtn = document.getElementById('regToConfirm');
    var backBtn = document.getElementById('regBackInput');
    var mockEl = document.getElementById('regMockCode');

    function showStep(step) {
      [stepInput, stepConfirm, stepCode].forEach(function (el) {
        if (el) el.classList.remove('active');
      });
      if (step) step.classList.add('active');
    }

    if (toConfirmBtn) {
      toConfirmBtn.addEventListener('click', function () {
        hideMsg(msg);
        var phone = document.getElementById('regPhone').value;
        var nickname = document.getElementById('regNickname').value;
        var password = document.getElementById('regPassword').value;
        var passwordConfirm = document.getElementById('regPasswordConfirm').value;
        var terms = document.getElementById('regTerms').checked;
        var privacy = document.getElementById('regPrivacy').checked;
        if (!phone || !nickname || !password) {
          showMsg(msg, '必須項目を入力してください', 'error');
          return;
        }
        if (password !== passwordConfirm) {
          showMsg(msg, 'パスワードが一致しません', 'error');
          return;
        }
        if (!terms || !privacy) {
          showMsg(msg, '利用規約とプライバシーポリシーに同意してください', 'error');
          return;
        }
        document.getElementById('regConfirmPhone').textContent = phone;
        document.getElementById('regConfirmNickname').textContent = nickname;
        showStep(stepConfirm);
      });
    }

    if (backBtn) {
      backBtn.addEventListener('click', function () {
        hideMsg(msg);
        showStep(stepInput);
      });
    }

    if (sendBtn) {
      sendBtn.addEventListener('click', function () {
        hideMsg(msg);
        var phone = document.getElementById('regPhone').value;
        postJson('001', { phone: phone }).then(function (res) {
          if (res.status !== 0) {
            showMsg(msg, res.message || 'SMS送信に失敗しました', 'error');
            return;
          }
          if (mockEl && res.data && res.data.mock_code) {
            mockEl.textContent = '【テスト用】認証コード: ' + res.data.mock_code + '（SMSは未送信です）';
          }
          showStep(stepCode);
          var infoText = '認証コードを送信しました。SMS内の案内に従って認証を完了してください。';
          if (res.data && res.data.mock_code) {
            infoText += '（テスト用コード: ' + res.data.mock_code + '）';
          }
          showMsg(msg, infoText, 'info');
        }).catch(function () {
          showMsg(msg, '通信エラーが発生しました', 'error');
        });
      });
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      hideMsg(msg);
      var fd = new FormData(form);
      if (!fd.get('code')) {
        showMsg(msg, '認証コードを入力してください', 'error');
        return;
      }
      postJson('002', {
        phone: fd.get('phone'),
        code: fd.get('code'),
        password: fd.get('password'),
        password_confirm: fd.get('password_confirm'),
        nickname: fd.get('nickname'),
        terms_agreed: fd.get('terms_agreed') === '1',
        privacy_agreed: fd.get('privacy_agreed') === '1',
        remember_me: fd.get('remember_me') === '1'
      }).then(function (res) {
        if (res.status === 0 || res.status === -8) {
          if (res.status === -8) {
            alert(res.message || 'CTI顧客が複数見つかりました');
          }
          window.location.href = 'member_login.php?registered=1';
          return;
        }
        showMsg(msg, res.message || '登録に失敗しました', 'error');
      }).catch(function () {
        showMsg(msg, '通信エラーが発生しました', 'error');
      });
    });

    // SMS認証URL（?sms_code=）からコードを取り込み
    var smsCodeMatch = window.location.search.match(/[?&]sms_code=([^&]+)/);
    if (smsCodeMatch) {
      var codeEl = document.getElementById('regCode');
      if (codeEl) codeEl.value = decodeURIComponent(smsCodeMatch[1]);
      showStep(stepCode);
      showMsg(msg, '認証コードを取り込みました。内容を確認のうえ本登録してください。未入力の項目がある場合は「戻る」から入力してください。', 'info');
    }
  }

  function clearFieldErrors(prefix) {
    document.querySelectorAll('.member-field-error').forEach(function (el) {
      el.textContent = '';
    });
  }

  function applyFieldErrors(fieldErrors) {
    if (!fieldErrors) return;
    Object.keys(fieldErrors).forEach(function (key) {
      var map = {
        nickname: 'errNickname',
        birthday: 'errBirthday',
        email: 'errEmail',
        phone: 'errPhone',
        code: 'errPhone',
        notify_mypage_info: 'errNotify',
        current_password: 'errCurrentPassword',
        new_password: 'errNewPassword',
        password_confirm: 'errConfirmPassword',
        delete_password: 'errDeletePassword'
      };
      var id = map[key];
      if (id) {
        var el = document.getElementById(id);
        if (el) el.textContent = fieldErrors[key];
      }
    });
  }

  function handleRelogin(res) {
    if (res.data && res.data.require_relogin) {
      window.location.href = 'member_login.php';
      return true;
    }
    return false;
  }

  function renderPhoneList(phones, slotsRemaining) {
    var listEl = document.getElementById('memberPhoneList');
    if (!listEl) return;
    var count = phones ? phones.length : 0;
    var countNote = document.getElementById('memberPhoneCountNote');
    if (countNote) {
      countNote.textContent = count ? ('登録 ' + count + ' / 3件') : '';
    }
    var addBtn = document.getElementById('memberPhoneAdd');
    if (addBtn) {
      var canAdd = slotsRemaining === undefined ? count < 3 : slotsRemaining > 0;
      addBtn.style.display = canAdd ? '' : 'none';
    }
    if (!phones || !phones.length) {
      listEl.innerHTML = '<p class="member-note">未登録</p>';
      return;
    }
    listEl.innerHTML = phones.map(function (p) {
      var mainBadge = p.is_primary ? '<span class="member-phone-main-badge">メイン</span>' : '';
      var actions = '';
      if (!p.is_primary) {
        actions += '<button type="button" class="member-site-btn-secondary member-phone-set-primary" data-id="' + p.id + '">メインに変更</button> ';
        actions += '<button type="button" class="member-site-btn-secondary member-phone-delete" data-id="' + p.id + '">削除</button>';
      } else if (phones.length > 1) {
        actions += '<button type="button" class="member-site-btn-secondary member-phone-delete" data-id="' + p.id + '">削除</button>';
      }
      return '<div class="member-phone-row">' +
        '<div class="member-phone-number">' +
        '<span class="member-phone-masked">' + escapeHtml(p.phone_masked) + '</span>' +
        mainBadge +
        '</div>' +
        '<div class="member-phone-actions">' + actions + '</div></div>';
    }).join('');
  }

  function resetPhoneSmsForm() {
    var phoneEl = document.getElementById('addPhone');
    var codeEl = document.getElementById('addPhoneCode');
    var codeField = document.getElementById('memberPhoneCodeField');
    var confirmBtn = document.getElementById('memberPhoneConfirm');
    var mockEl = document.getElementById('memberPhoneMockCode');
    if (phoneEl) phoneEl.value = '';
    if (codeEl) codeEl.value = '';
    if (codeField) codeField.style.display = 'none';
    if (confirmBtn) confirmBtn.style.display = 'none';
    if (mockEl) mockEl.textContent = '';
  }

  function fillProfileForm(d) {
    var nick = document.getElementById('editNickname');
    if (nick) nick.value = d.nickname || '';
    var bd = document.getElementById('editBirthday');
    if (bd) bd.value = d.birthday || '';
    renderPhoneList(d.phones || [], d.phone_slots_remaining);
    refreshEmailStatus(d);
  }

  function refreshEmailStatus(d) {
    var statusEl = document.getElementById('memberEmailStatus');
    var notifyChk = document.getElementById('notifyMypageInfo');
    var delBtn = document.getElementById('memberEmailDelete');
    if (statusEl) {
      statusEl.textContent = d.has_email ? ('登録済み: ' + (d.email || '')) : '未登録';
    }
    if (notifyChk) {
      notifyChk.checked = !!d.notify_mypage_info;
    }
    if (delBtn) delBtn.style.display = d.has_email ? '' : 'none';
  }

  function bindMypage() {
    var msg = document.getElementById('memberMsg');
    if (!document.getElementById('memberProfileSection')) return;

    var phoneAction = 'change_primary';
    var pendingPhone = '';

    postJson('101', {}).then(function (res) {
      if (res.status !== 0) {
        window.location.href = 'member_login.php';
        return;
      }
      fillProfileForm(res.data);
      bindProfileEvents(msg, function () { return pendingPhone; }, function (v) { pendingPhone = v; }, function () { return phoneAction; }, function (v) { phoneAction = v; });
    });

    var profileForm = document.getElementById('memberProfileForm');
    if (profileForm) {
      profileForm.addEventListener('submit', function (e) {
      e.preventDefault();
      hideMsg(msg);
      clearFieldErrors();
      var body = {
        nickname: document.getElementById('editNickname').value,
        birthday: document.getElementById('editBirthday').value
      };
      postJson('102', body).then(function (res) {
        if (res.field_errors) {
          applyFieldErrors(res.field_errors);
          showMsg(msg, res.message || '入力内容に不備があります', 'error');
          return;
        }
        if (res.status !== 0) {
          showMsg(msg, res.message || '更新に失敗しました', 'error');
          return;
        }
        var notifyChk = document.getElementById('notifyMypageInfo');
        postJson('502', { notify_mypage_info: notifyChk && notifyChk.checked }).then(function (nres) {
          if (nres.field_errors) applyFieldErrors(nres.field_errors);
          if (nres.status !== 0) {
            showMsg(msg, nres.message || 'お知らせ配信設定を保存できません', 'error');
            return;
          }
          fillProfileForm(nres.data || res.data);
          showMsg(msg, '会員情報を更新しました', 'success');
        });
      });
      });
    }

    var deleteBtn = document.getElementById('memberAccountDelete');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', function () {
        hideMsg(msg);
        clearFieldErrors();
        var password = document.getElementById('deletePassword').value;
        if (!password) {
          var errEl = document.getElementById('errDeletePassword');
          if (errEl) errEl.textContent = 'パスワードを入力してください';
          showMsg(msg, 'パスワードを入力してください', 'error');
          return;
        }
        if (!confirm('アカウントを削除しますか？\n\n登録情報・お気に入りなどはすべて削除され、取り消せません。')) return;
        postJson('104', { password: password }).then(function (res) {
          if (res.field_errors) {
            applyFieldErrors(res.field_errors);
            showMsg(msg, res.message || '削除できません', 'error');
            return;
          }
          if (res.status === 0) {
            window.location.href = 'member_login.php?deleted=1';
            return;
          }
          showMsg(msg, res.message || 'アカウントの削除に失敗しました', 'error');
        }).catch(function () {
          showMsg(msg, '通信エラーが発生しました', 'error');
        });
      });
    }

    bindLoyalty(msg);
    loadFavoriteIds().then(function () {
      bindAnnouncements(msg);
      bindHistory(msg);
      bindFavorites(msg);
    });
  }

  function bindLoyalty(msg) {
    var bodyEl = document.getElementById('memberLoyaltyBody');
    var noteEl = document.getElementById('memberLoyaltyNote');
    if (!bodyEl) return;

    postJson('203', {}).then(function (res) {
      if (res.status !== 0) {
        bodyEl.textContent = res.message || '会員ランク・ポイントの取得に失敗しました';
        return;
      }
      var d = res.data || {};
      if (d.link_message === 'cti_unavailable') {
        if (noteEl) {
          noteEl.textContent = '会員ランク・ポイントの参照先に接続できませんでした。時間をおいて再度お試しください。';
        }
        bodyEl.textContent = '表示できません';
        return;
      }
      if (!d.guest_linked || !d.available || !d.rank) {
        if (noteEl) {
          noteEl.textContent = 'CTI顧客と未連携のため、会員ランク・ポイントを表示できません。会員登録の電話番号がCTI顧客マスタと一致しているかご確認ください。';
        }
        bodyEl.textContent = '表示できる情報がありません';
        return;
      }
      if (noteEl) noteEl.textContent = '';
      bodyEl.innerHTML = renderLoyalty(d);
    }).catch(function (err) {
      bodyEl.textContent = apiErrorMessage(err, '通信エラーが発生しました');
    });
  }

  function renderLoyalty(d) {
    var rank = d.rank || {};
    var points = d.points || {};
    var coupons = d.coupons || {};
    var html = '';

    html += '<div class="member-loyalty-block">';
    html += '<h4 class="member-settings-heading">会員ランク</h4>';
    html += '<dl class="member-loyalty-dl">';
    html += '<dt>現在のランク</dt><dd><span class="member-loyalty-rank">' + escapeHtml(rank.name || '—') + '</span></dd>';
    html += '<dt>累計利用回数</dt><dd>' + escapeHtml(String(rank.visit_count != null ? rank.visit_count : 0)) + '回</dd>';
    html += '<dt>最終利用日</dt><dd>' + escapeHtml(rank.last_visit_display || '—') + '</dd>';
    html += '</dl>';
    if (rank.demotion && rank.demotion.message) {
      html += '<p class="member-loyalty-alert">' + escapeHtml(rank.demotion.message) + '</p>';
    }
    if (rank.return && rank.return.message) {
      html += '<p class="member-loyalty-alert member-loyalty-return">' + escapeHtml(rank.return.message) + '</p>';
    }
    html += '</div>';

    html += '<div class="member-loyalty-block">';
    html += '<h4 class="member-settings-heading">ポイント</h4>';
    html += '<dl class="member-loyalty-dl">';
    html += '<dt>現在の保有ポイント</dt><dd>' + escapeHtml(points.held_display || '0P') + '</dd>';
    html += '<dt>利用可能ポイント</dt><dd>' + escapeHtml(points.usable_display || '0P') + '</dd>';
    if (points.show_shortfall) {
      html += '<dt>ポイント利用に必要な不足ポイント</dt><dd>' + escapeHtml(points.shortfall_display || '0P') + '</dd>';
    }
    html += '<dt>1回あたりの利用上限ポイント</dt><dd>' + escapeHtml(points.use_max_display || '—') + '</dd>';
    html += '<dt>有効期限が近いポイント</dt><dd>' + escapeHtml(points.expiring_soon_display || '0P') + '</dd>';
    html += '</dl>';
    html += '</div>';

    html += '<div class="member-loyalty-block">';
    html += '<h4 class="member-settings-heading">クーポンチケット</h4>';
    html += '<dl class="member-loyalty-dl">';
    html += '<dt>保有クーポンチケット</dt><dd>' + escapeHtml(coupons.held_display || '0円') + '</dd>';
    html += '<dt>利用可能クーポンチケット</dt><dd>' + escapeHtml(coupons.usable_display || '0円') + '</dd>';
    html += '<dt>1回あたりの利用上限</dt><dd>' + escapeHtml(coupons.use_max_display || '—') + '</dd>';
    html += '<dt>有効期限が近いクーポンチケット</dt><dd>' + escapeHtml(coupons.expiring_soon_display || '0円') + '</dd>';
    html += '</dl>';
    html += '</div>';

    return html;
  }

  function bindProfileEvents(msg, getPendingPhone, setPendingPhone, getPhoneAction, setPhoneAction) {
    var listEl = document.getElementById('memberPhoneList');
    if (listEl) {
      listEl.addEventListener('click', function (e) {
        var t = e.target;
        if (t.classList.contains('member-phone-delete')) {
          if (!confirm('この電話番号を削除しますか？')) return;
          postJson('111', { phone_id: parseInt(t.getAttribute('data-id'), 10) }).then(function (res) {
            if (handleRelogin(res)) return;
            if (res.status !== 0) {
              applyFieldErrors(res.field_errors);
              showMsg(msg, res.message || '削除に失敗しました', 'error');
              return;
            }
            fillProfileForm(res.data);
            showMsg(msg, res.message, 'success');
          });
        }
        if (t.classList.contains('member-phone-set-primary')) {
          if (!confirm('この番号をメイン（ログイン用）に変更しますか？\n変更後は再ログインが必要です。')) return;
          postJson('112', { phone_id: parseInt(t.getAttribute('data-id'), 10) }).then(function (res) {
            if (handleRelogin(res)) return;
            if (res.status !== 0) {
              showMsg(msg, res.message || '切り替えに失敗しました', 'error');
              return;
            }
            showMsg(msg, res.message, 'info');
          });
        }
      });
    }

    function sendPhoneSms(action, confirmLabel) {
      hideMsg(msg);
      clearFieldErrors();
      var phone = document.getElementById('addPhone').value;
      setPendingPhone(phone);
      setPhoneAction(action);
      postJson('105', { phone: phone, action: action }).then(function (res) {
        if (res.field_errors) {
          applyFieldErrors(res.field_errors);
          showMsg(msg, res.message || '送信できません', 'error');
          return;
        }
        if (res.status !== 0) {
          showMsg(msg, res.message || '送信に失敗しました', 'error');
          return;
        }
        var mockEl = document.getElementById('memberPhoneMockCode');
        if (mockEl && res.data && res.data.mock_code) {
          mockEl.textContent = '【テスト用】認証コード: ' + res.data.mock_code;
        }
        document.getElementById('memberPhoneCodeField').style.display = '';
        var confirmBtn = document.getElementById('memberPhoneConfirm');
        confirmBtn.style.display = '';
        confirmBtn.textContent = confirmLabel;
        showMsg(msg, '認証コードを送信しました', 'info');
      });
    }

    var addPhoneBtn = document.getElementById('memberPhoneAdd');
    if (addPhoneBtn) {
      addPhoneBtn.addEventListener('click', function () {
        sendPhoneSms('add', '電話番号を追加');
      });
    }

    var changePrimaryBtn = document.getElementById('memberPhoneChangePrimary');
    if (changePrimaryBtn) {
      changePrimaryBtn.addEventListener('click', function () {
        sendPhoneSms('change_primary', 'メイン電話番号を変更');
      });
    }

    var confirmPhoneBtn = document.getElementById('memberPhoneConfirm');
    if (confirmPhoneBtn) {
      confirmPhoneBtn.addEventListener('click', function () {
        postJson('106', {
          phone: getPendingPhone(),
          code: document.getElementById('addPhoneCode').value,
          action: getPhoneAction()
        }).then(function (res) {
          if (handleRelogin(res)) return;
          if (res.field_errors) {
            applyFieldErrors(res.field_errors);
            showMsg(msg, res.message || '登録できません', 'error');
            return;
          }
          if (res.status !== 0) {
            showMsg(msg, res.message || '登録に失敗しました', 'error');
            return;
          }
          resetPhoneSmsForm();
          fillProfileForm(res.data);
          showMsg(msg, res.message, 'success');
        });
      });
    }

    var sendEmailBtn = document.getElementById('memberEmailSendCode');
    if (sendEmailBtn) {
      sendEmailBtn.addEventListener('click', function () {
        hideMsg(msg);
        postJson('107', { email: document.getElementById('editEmail').value }).then(function (res) {
          if (res.status !== 0) {
            showMsg(msg, res.message || '送信に失敗しました', 'error');
            return;
          }
          var mockEl = document.getElementById('memberEmailMockCode');
          if (mockEl && res.data && res.data.mock_code) {
            mockEl.textContent = '【テスト用】認証コード: ' + res.data.mock_code;
          }
          document.getElementById('memberEmailCodeField').style.display = '';
          document.getElementById('memberEmailConfirm').style.display = '';
          showMsg(msg, res.message || '認証コードを送信しました', 'info');
        });
      });
    }

    var confirmEmailBtn = document.getElementById('memberEmailConfirm');
    if (confirmEmailBtn) {
      confirmEmailBtn.addEventListener('click', function () {
        postJson('108', {
          email: document.getElementById('editEmail').value,
          code: document.getElementById('editEmailCode').value
        }).then(function (res) {
          if (res.status !== 0) {
            showMsg(msg, res.message || '登録に失敗しました', 'error');
            return;
          }
          fillProfileForm(res.data);
          showMsg(msg, res.message || 'メールアドレスを登録しました', 'success');
        });
      });
    }

    var delEmailBtn = document.getElementById('memberEmailDelete');
    if (delEmailBtn) {
      delEmailBtn.addEventListener('click', function () {
        if (!confirm('メールアドレスを削除しますか？お知らせ配信はOFFになります。')) return;
        postJson('110', {}).then(function (res) {
          if (res.status !== 0) {
            showMsg(msg, res.message || '削除に失敗しました', 'error');
            return;
          }
          document.getElementById('editEmail').value = '';
          fillProfileForm(res.data);
          showMsg(msg, res.message, 'success');
        });
      });
    }

    var pwBtn = document.getElementById('memberPasswordChange');
    if (pwBtn) {
      pwBtn.addEventListener('click', function () {
        hideMsg(msg);
        clearFieldErrors();
        postJson('109', {
          current_password: document.getElementById('currentPassword').value,
          new_password: document.getElementById('newPassword').value,
          password_confirm: document.getElementById('confirmPassword').value
        }).then(function (res) {
          if (res.field_errors) {
            applyFieldErrors(res.field_errors);
            showMsg(msg, res.message || '変更できません', 'error');
            return;
          }
          if (res.status !== 0) {
            showMsg(msg, res.message || '変更に失敗しました', 'error');
            return;
          }
          if (handleRelogin(res)) return;
          showMsg(msg, res.message, 'success');
        });
      });
    }
  }

  function bindEmailSettings(msg, profile) {
    refreshEmailStatus(profile || {});
  }

  var infoPage = 1;

  function bindAnnouncements(msg) {
    var listEl = document.getElementById('memberInfoList');
    var pagerEl = document.getElementById('memberInfoPager');
    var detailEl = document.getElementById('memberInfoDetail');
    var unreadEl = document.getElementById('memberInfoUnread');
    var closeBtn = document.getElementById('memberInfoDetailClose');
    if (!listEl) return;

    function updateUnreadBadge(count) {
      if (!unreadEl) return;
      if (count > 0) {
        unreadEl.textContent = '未読 ' + count;
        unreadEl.style.display = 'inline-block';
      } else {
        unreadEl.style.display = 'none';
      }
    }

    function showList() {
      if (detailEl) detailEl.style.display = 'none';
      listEl.style.display = '';
      if (pagerEl) pagerEl.style.display = '';
    }

    function showDetail(item) {
      listEl.style.display = 'none';
      if (pagerEl) pagerEl.style.display = 'none';
      if (!detailEl) return;
      detailEl.style.display = 'block';
      document.getElementById('memberInfoDetailTitle').textContent = item.title || '';
      var meta = [];
      if (item.display_date_label) meta.push(item.display_date_label);
      else if (item.created_at) meta.push(item.created_at);
      if (item.category) meta.push(item.category);
      document.getElementById('memberInfoDetailMeta').textContent = meta.join(' · ');

      var mediaHtml = '';
      if (item.image_url) {
        mediaHtml += '<div class="member-info-media"><img src="' + escapeHtml(item.image_url) + '" alt=""></div>';
      }
      if (item.video_url) {
        mediaHtml += '<div class="member-info-media"><video src="' + escapeHtml(item.video_url) + '" controls playsinline></video></div>';
      }
      var bodyEl = document.getElementById('memberInfoDetailBody');
      bodyEl.innerHTML = mediaHtml
        + '<div class="member-info-body-text"></div>'
        + (item.html_comment ? ('<div class="member-info-html">' + item.html_comment + '</div>') : '');
      var textEl = bodyEl.querySelector('.member-info-body-text');
      if (textEl) textEl.textContent = item.body || '';

      if (item.info_id) {
        var row = listEl.querySelector('[data-info-id="' + item.info_id + '"]');
        if (row) row.classList.remove('unread');
      }
    }

    function loadPage(page) {
      infoPage = page;
      listEl.textContent = '読み込み中…';
      if (pagerEl) pagerEl.innerHTML = '';
      postJson('401', { page: page, per_page: 5 }).then(function (res) {
        if (res.status !== 0) {
          listEl.textContent = res.message || 'お知らせの取得に失敗しました';
          return;
        }
        var d = res.data;
        updateUnreadBadge(d.unread_count || 0);
        if (!d.items || d.items.length === 0) {
          listEl.textContent = 'お知らせはありません';
          return;
        }
        listEl.innerHTML = d.items.map(renderInfoItem).join('');
        listEl.querySelectorAll('.member-info-item').forEach(function (el) {
          el.addEventListener('click', function () {
            var infoId = parseInt(el.getAttribute('data-info-id'), 10);
            postJson('402', { info_id: infoId }).then(function (dr) {
              if (dr.status !== 0) {
                showMsg(msg, dr.message || '詳細の取得に失敗しました', 'error');
                return;
              }
              showDetail(dr.data);
              updateUnreadBadge(Math.max(0, (d.unread_count || 0) - (el.classList.contains('unread') ? 1 : 0)));
            });
          });
        });
        renderInfoPager(pagerEl, d.total, page, d.per_page, loadPage);
      }).catch(function () {
        listEl.textContent = '通信エラーが発生しました';
      });
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        showList();
        loadPage(infoPage);
      });
    }

    loadPage(1);
  }

  function renderInfoItem(item) {
    var cls = 'member-info-item' + (item.is_read ? '' : ' unread');
    var dateLabel = item.display_date_label || item.created_at || '';
    return '<div class="' + cls + '" data-info-id="' + item.info_id + '">' +
      '<div class="member-info-item-date">' + escapeHtml(dateLabel) + '</div>' +
      '<div class="member-info-item-title">' + escapeHtml(item.title) + '</div>' +
      '</div>';
  }

  function renderInfoPager(el, total, page, perPage, loadPage) {
    if (!el || total <= perPage) return;
    var maxPage = Math.ceil(total / perPage);
    el.innerHTML = '';
    var prev = document.createElement('button');
    prev.textContent = '前へ';
    prev.disabled = page <= 1;
    prev.addEventListener('click', function () {
      if (page > 1) loadPage(page - 1);
    });
    var next = document.createElement('button');
    next.textContent = '次へ';
    next.disabled = page >= maxPage;
    next.addEventListener('click', function () {
      if (page < maxPage) loadPage(page + 1);
    });
    el.appendChild(prev);
    el.appendChild(next);
  }

  function loadFavoriteIds() {
    return postJson('304', {}).then(function (res) {
      if (res.status === 0 && res.data && res.data.girls_ids) {
        favoriteIds = res.data.girls_ids;
      } else {
        favoriteIds = [];
      }
      return favoriteIds;
    }).catch(function () {
      favoriteIds = [];
      return favoriteIds;
    });
  }

  function isFavorite(girlsId) {
    return favoriteIds.indexOf(girlsId) !== -1;
  }

  function bindFavorites(msg) {
    var listEl = document.getElementById('memberFavoritesList');
    var pagerEl = document.getElementById('memberFavoritesPager');
    if (!listEl) return;

    function loadPage(page) {
      favoritesPage = page;
      listEl.textContent = '読み込み中…';
      if (pagerEl) pagerEl.innerHTML = '';
      postJson('301', { page: page, per_page: 10 }).then(function (res) {
        if (res.status !== 0) {
          listEl.textContent = res.message || 'お気に入りの取得に失敗しました';
          return;
        }
        var d = res.data;
        if (!d.items || d.items.length === 0) {
          listEl.textContent = 'お気に入りはまだありません';
          return;
        }
        try {
          listEl.innerHTML = d.items.map(renderFavoriteItem).join('');
          bindFavoriteRemoveButtons(listEl, msg, function () {
            loadFavoriteIds().then(function () {
              loadPage(favoritesPage);
            });
          });
          renderFavPager(pagerEl, d.total, page, d.per_page, loadPage);
        } catch (renderErr) {
          console.error(renderErr);
          listEl.textContent = 'お気に入りの表示処理でエラーが発生しました';
        }
      }).catch(function (err) {
        listEl.textContent = apiErrorMessage(err, '通信エラーが発生しました');
      });
    }

    loadFavoriteIds().then(function () {
      loadPage(1);
    });
    bindFavoriteScheduleNotices();
  }

  function bindFavoriteScheduleNotices() {
    var badgeEl = document.getElementById('memberFavNoticeBadge');
    var listEl = document.getElementById('memberFavNoticeList');
    if (!badgeEl && !listEl) return;

    postJson('305', {}).then(function (res) {
      if (res.status !== 0) return;
      var d = res.data || {};
      var count = d.unread_count || 0;
      var items = d.items || [];

      if (badgeEl) {
        if (count > 0) {
          badgeEl.textContent = '出勤通知 ' + count;
          badgeEl.style.display = 'inline-block';
        } else {
          badgeEl.style.display = 'none';
        }
      }

      if (!listEl) return;
      if (count <= 0 || items.length === 0) {
        listEl.style.display = 'none';
        listEl.innerHTML = '';
        return;
      }

      listEl.style.display = 'block';
      listEl.innerHTML =
        '<div class="member-fav-notice-head">' +
          '<span>お気に入りの出勤通知</span>' +
          '<button type="button" id="memberFavNoticeMarkRead" class="member-site-btn-secondary">すべて既読</button>' +
        '</div>' +
        items.map(function (item) {
          var name = item.girl_name ? escapeHtml(item.girl_name) : ('ID:' + item.girls_id);
          var label = item.schedule_label ? escapeHtml(item.schedule_label) : '出勤';
          var date = item.schedule_date ? escapeHtml(item.schedule_date) : '';
          var href = item.girl_no
            ? 'girls.php?no=' + encodeURIComponent(item.girl_no)
            : '';
          var nameHtml = href
            ? '<a href="' + escapeAttr(href) + '">' + name + '</a>'
            : name;
          return '<div class="member-fav-notice-item">' +
            '<div class="member-fav-notice-name">' + nameHtml + '</div>' +
            '<div class="member-fav-notice-meta">' + date + (date && label ? ' · ' : '') + label + '</div>' +
            '</div>';
        }).join('');

      var markBtn = document.getElementById('memberFavNoticeMarkRead');
      if (markBtn) {
        markBtn.addEventListener('click', function () {
          postJson('306', {}).then(function (markRes) {
            if (markRes.status !== 0) return;
            if (badgeEl) badgeEl.style.display = 'none';
            listEl.style.display = 'none';
            listEl.innerHTML = '';
          });
        });
      }
    }).catch(function () { /* ignore */ });
  }

  var historyPage = 1;
  var historyDetailCache = {};

  function escapeAttr(s) {
    if (s == null) return '';
    return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
  }

  function renderGirlThumb(imageUrl) {
    if (!imageUrl) {
      return '<div class="member-fav-thumb member-fav-thumb-empty"></div>';
    }
    return '<img class="member-fav-thumb" src="' + escapeAttr(imageUrl) + '" alt="" loading="lazy">';
  }

  function renderGirlCardBody(item) {
    var name = item.name ? escapeHtml(item.name) : '（情報なし）';
    var age = item.age ? '（' + item.age + '）' : '';
    var nameHtml = item.profile_url
      ? '<a href="' + escapeAttr(item.profile_url) + '">' + name + age + '</a>'
      : name + age;

    var kana = item.name_kana ? '<div class="member-fav-kana">' + escapeHtml(item.name_kana) + '</div>' : '';
    var romaji = item.name_romaji ? '<div class="member-fav-romaji">' + escapeHtml(item.name_romaji) + '</div>' : '';
    var size = item.size_display ? '<div class="member-fav-size">' + escapeHtml(item.size_display) + '</div>' : '';

    var statusClass = 'member-fav-status';
    if (item.schedule_code === 'working') statusClass += ' is-working';
    else if (item.schedule_code === 'tel_check') statusClass += ' is-tel';
    else if (item.schedule_code === 'closed_today') statusClass += ' is-closed';
    else statusClass += ' is-off';

    var scheduleHtml = '';
    if (item.active !== false) {
      var schedLabel = item.schedule_label ? escapeHtml(item.schedule_label) : '';
      scheduleHtml = '<div class="' + statusClass + '"><span class="member-fav-sched-label">本日</span> ' + schedLabel + '</div>';
      if (item.schedule_next && item.schedule_next.label) {
        scheduleHtml += '<div class="member-fav-next"><span class="member-fav-sched-label">次回</span> ' + escapeHtml(item.schedule_next.label) + '</div>';
      }
    }

    var enroll = item.enrollment_status
      ? '<span class="member-fav-enroll">' + escapeHtml(item.enrollment_status) + '</span>'
      : '';

    return renderGirlThumb(item.image_url) +
      '<div class="member-fav-body">' +
        '<div class="member-fav-name">' + nameHtml + enroll + '</div>' +
        kana + romaji + size + scheduleHtml +
      '</div>';
  }

  function renderFavoriteItem(item) {
    return '<div class="member-fav-item" data-girls-id="' + item.girls_id + '">' +
      renderGirlCardBody(item) +
      '<button type="button" class="member-fav-remove" data-girls-id="' + item.girls_id + '">解除</button>' +
      '</div>';
  }

  function bindFavoriteRemoveButtons(container, msg, onDone) {
    container.querySelectorAll('.member-fav-remove').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var girlsId = parseInt(btn.getAttribute('data-girls-id'), 10);
        postJson('303', { girls_id: girlsId }).then(function (res) {
          if (res.status !== 0) {
            showMsg(msg, res.message || '解除に失敗しました', 'error');
            return;
          }
          showMsg(msg, res.message || 'お気に入りを解除しました', 'success');
          if (onDone) onDone();
        });
      });
    });
  }

  function renderFavPager(el, total, page, perPage, loadPage) {
    if (!el || total <= perPage) return;
    var maxPage = Math.ceil(total / perPage);
    el.innerHTML = '';
    var prev = document.createElement('button');
    prev.textContent = '前へ';
    prev.disabled = page <= 1;
    prev.addEventListener('click', function () {
      if (page > 1) loadPage(page - 1);
    });
    var next = document.createElement('button');
    next.textContent = '次へ';
    next.disabled = page >= maxPage;
    next.addEventListener('click', function () {
      if (page < maxPage) loadPage(page + 1);
    });
    el.appendChild(prev);
    el.appendChild(next);
  }

  function toggleFavorite(girlsId, msg, btn) {
    var add = !isFavorite(girlsId);
    var fno = add ? '302' : '303';
    postJson(fno, { girls_id: girlsId }).then(function (res) {
      if (res.status !== 0) {
        showMsg(msg, res.message || '操作に失敗しました', 'error');
        return;
      }
      loadFavoriteIds().then(function () {
        if (btn) {
          btn.textContent = isFavorite(girlsId) ? 'お気に入り済み' : 'お気に入りに追加';
          btn.classList.toggle('active', isFavorite(girlsId));
        }
        if (add) {
          showMsg(msg, res.message || 'お気に入りに追加しました', 'success');
        } else {
          showMsg(msg, res.message || 'お気に入りを解除しました', 'success');
        }
      });
    });
  }

  function historyGirlLabel(item) {
    if (item.girl && item.girl.name) {
      var name = item.girl.name;
      if (item.girl.age) {
        name += '（' + item.girl.age + '）';
      }
      return name;
    }
    return '（女の子情報なし）';
  }

  function formatVisitSummary(item) {
    var time = escapeHtml(item.date_display || (item.date + ' ' + (item.start || '')));
    var course = item.course_label || (item.course ? (String(item.course) + '分') : '');
    var badge = item.stat === 2 ? ' <span class="member-history-badge reserved">予約</span>' : '';
    var evalBadge = item.evaluation ? ' <span class="member-history-badge evaluated">評価済み</span>' : '';
    return time + (course ? ' · ' + escapeHtml(course) : '') + badge + evalBadge;
  }

  var EVAL_FIELDS = [
    {
      key: 'rating',
      label: '総合満足度',
      options: [
        [5, '非常に満足'], [4, '満足'], [3, '普通'], [2, '不満'], [1, '非常に不満']
      ]
    },
    {
      key: 'rating_service',
      label: '接客対応',
      options: [
        [5, '非常に満足'], [4, '満足'], [3, '普通'], [2, '不満'], [1, '非常に不満']
      ]
    },
    {
      key: 'rating_friendliness',
      label: '親しみやすさ',
      options: [
        [5, '非常に満足'], [4, '満足'], [3, '普通'], [2, '不満'], [1, '非常に不満']
      ]
    },
    {
      key: 'rating_cleanliness',
      label: '清潔感',
      options: [
        [5, '非常に満足'], [4, '満足'], [3, '普通'], [2, '不満'], [1, '非常に不満']
      ]
    },
    {
      key: 'rating_match',
      label: '掲載情報との一致度',
      options: [
        [5, '非常にイメージに近かった'],
        [4, 'イメージに近かった'],
        [3, '普通'],
        [2, 'イメージとやや異なった'],
        [1, 'イメージと異なった']
      ]
    },
    {
      key: 'rating_repeat',
      label: 'リピート希望度',
      options: [
        [5, 'ぜひまた指名したい'],
        [4, 'また指名したい'],
        [3, '普通'],
        [2, 'あまり指名したいと思わない'],
        [1, '指名したいと思わない']
      ]
    }
  ];

  function ratingLabel(fieldKey, value) {
    var field = null;
    for (var i = 0; i < EVAL_FIELDS.length; i++) {
      if (EVAL_FIELDS[i].key === fieldKey) {
        field = EVAL_FIELDS[i];
        break;
      }
    }
    if (!field) return String(value || '');
    for (var j = 0; j < field.options.length; j++) {
      if (field.options[j][0] === value) return field.options[j][1];
    }
    return String(value || '');
  }

  function renderEvalDone(ev) {
    var html = '<div class="member-eval-done"><p class="member-modal-subtitle">評価済み（修正不可）</p>';
    html += '<dl class="member-eval-summary">';
    EVAL_FIELDS.forEach(function (f) {
      var v = ev[f.key];
      if (v == null && f.key === 'rating') v = ev.rating;
      html += '<dt>' + escapeHtml(f.label) + '</dt><dd>' + escapeHtml(ratingLabel(f.key, parseInt(v, 10))) + '</dd>';
    });
    html += '</dl>';
    if (ev.comment) {
      html += '<p class="member-eval-comment">' + escapeHtml(ev.comment) + '</p>';
    }
    html += '</div>';
    return html;
  }

  function renderEvalForm(taskId) {
    var html = '<form class="member-eval-form" data-task-id="' + taskId + '">';
    html += '<p class="member-modal-subtitle">女の子評価</p>';
    html += '<p class="member-note">各項目必須です。登録後は修正できません。</p>';
    EVAL_FIELDS.forEach(function (f) {
      html += '<div class="member-field member-eval-field">';
      html += '<label for="eval_' + f.key + '">' + escapeHtml(f.label) + '</label>';
      html += '<select id="eval_' + f.key + '" name="' + f.key + '" required>';
      html += '<option value="">選択してください</option>';
      f.options.forEach(function (opt) {
        html += '<option value="' + opt[0] + '">' + escapeHtml(opt[1]) + '</option>';
      });
      html += '</select></div>';
    });
    html += '<div class="member-field"><textarea name="comment" placeholder="コメント（任意）" maxlength="1000"></textarea></div>';
    html += '<button type="submit" class="member-site-btn-secondary" style="margin-top:8px;">評価を登録</button>';
    html += '</form>';
    return html;
  }

  function formatYen(n) {
    var num = parseInt(n, 10);
    if (isNaN(num)) num = 0;
    return num.toLocaleString() + '円';
  }

  function bindHistoryModal(msg) {
    var modal = document.getElementById('memberHistoryModal');
    if (!modal) return;
    var overlay = modal.querySelector('.member-modal-overlay');
    var closeBtn = modal.querySelector('.member-modal-close');

    function closeModal() {
      modal.hidden = true;
      modal.setAttribute('aria-hidden', 'true');
    }

    if (overlay) overlay.addEventListener('click', closeModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    window.openHistoryModal = function (cacheKey) {
      var item = historyDetailCache[cacheKey];
      if (!item) return;
      var bodyEl = document.getElementById('memberHistoryModalBody');
      if (!bodyEl) return;

      var isPastOnly = item.source === 'history' || !item.task_id;
      var html = '<div class="member-history-modal-visit">' + formatVisitSummary(item) + '</div>';
      if (item.girl) {
        html += '<div class="member-fav-item member-fav-item-inmodal">' + renderGirlCardBody(item.girl) + '</div>';
      } else {
        html += '<p class="member-note">（女の子情報なし）</p>';
      }

      var pb = item.price_breakdown || {};
      html += '<dl class="member-history-detail-list">';
      html += '<dt>日付</dt><dd>' + escapeHtml(item.date_display || item.date || '—') + '</dd>';
      html += '<dt>女の子</dt><dd>' + escapeHtml(historyGirlLabel(item)) + '</dd>';
      html += '<dt>コース</dt><dd>' + escapeHtml(item.course_label || (item.course ? (item.course + '分') : '—')) + '</dd>';
      html += '<dt>オプション</dt><dd>' + escapeHtml(item.options_text || (isPastOnly ? '—' : 'なし')) + '</dd>';
      html += '<dt>割引</dt><dd>' + escapeHtml(item.discounts_text || (isPastOnly ? '—' : 'なし')) + '</dd>';
      html += '<dt>利用ポイント</dt><dd>' + escapeHtml(item.points_used_display || (isPastOnly ? '—' : '0ポイント')) + '</dd>';
      html += '<dt>指名</dt><dd>' + escapeHtml(item.nominate_label || (item.nominate ? '指名' : (isPastOnly ? '—' : 'なし'))) + '</dd>';
      html += '<dt>支払方法</dt><dd>' + escapeHtml(item.payment_label || '—') + '</dd>';
      html += '<dt>派遣先</dt><dd>' + escapeHtml(item.destination_display || '—') + '</dd>';
      html += '<dt>金額</dt><dd>' + escapeHtml(formatHistoryPrice(item)) + '</dd>';
      html += '</dl>';

      if (!isPastOnly) {
        html += '<div class="member-history-breakdown">';
        html += '<p class="member-modal-subtitle">金額内訳</p>';
        html += '<ul>';
        html += '<li>コース料金：' + formatYen(pb.course_price) + '</li>';
        html += '<li>オプション料金：' + formatYen(pb.options_total) + '</li>';
        if (pb.nominate_fee) html += '<li>指名料金：' + formatYen(pb.nominate_fee) + '</li>';
        if (pb.traffic_fare) html += '<li>交通費：' + formatYen(pb.traffic_fare) + '</li>';
        html += '<li>割引額：' + formatYen(pb.discount_total) + '</li>';
        html += '<li>利用ポイント：' + formatYen(pb.points_used) + '</li>';
        html += '<li class="member-history-breakdown-total">合計金額：' + formatYen(pb.total_price != null ? pb.total_price : item.total_price) + '</li>';
        html += '</ul></div>';
      } else {
        html += '<p class="member-note">過去履歴のため、金額・詳細の一部は表示できません。</p>';
      }

      if (item.girls_id) {
        var favActive = isFavorite(item.girls_id);
        html += '<button type="button" class="member-fav-add-btn' + (favActive ? ' active' : '') + '" data-girls-id="' + item.girls_id + '">'
          + (favActive ? 'お気に入り済み' : 'お気に入りに追加') + '</button>';
      }

      if (item.evaluation) {
        html += renderEvalDone(item.evaluation);
      } else if (item.can_evaluate) {
        html += renderEvalForm(item.task_id);
      }

      bodyEl.innerHTML = html;
      bindHistoryFavButtons(bodyEl, msg);
      bindEvalForms(bodyEl, msg, closeModal);
      modal.hidden = false;
      modal.setAttribute('aria-hidden', 'false');
    };
  }

  function bindHistoryDetailButtons(container, msg) {
    container.querySelectorAll('.member-history-detail-btn, .member-history-eval-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var key = btn.getAttribute('data-history-key') || btn.getAttribute('data-task-id');
        if (window.openHistoryModal) window.openHistoryModal(key);
      });
    });
  }

  function bindHistory(msg) {
    bindHistoryModal(msg);
    var listEl = document.getElementById('memberHistoryList');
    var noteEl = document.getElementById('memberHistoryNote');
    var pagerEl = document.getElementById('memberHistoryPager');
    if (!listEl) return;

    function loadPage(page) {
      historyPage = page;
      listEl.textContent = '読み込み中…';
      if (pagerEl) pagerEl.innerHTML = '';
      postJson('201', { page: page, per_page: 10 }).then(function (res) {
        if (res.status !== 0) {
          listEl.textContent = res.message || '履歴の取得に失敗しました';
          return;
        }
        var d = res.data;
        if (!d.guest_linked) {
          if (noteEl) {
            noteEl.textContent = 'CTI顧客と未連携のため、利用履歴を表示できません。会員登録の電話番号がCTI顧客マスタと一致しているかご確認ください。';
          }
          listEl.textContent = '利用履歴はありません';
          return;
        }
        if (noteEl) noteEl.textContent = '';
        if (!d.items || d.items.length === 0) {
          listEl.textContent = 'キャンディでの利用履歴はありません';
          renderPager(pagerEl, d.total, page, d.per_page);
          return;
        }
        try {
          listEl.innerHTML = d.items.map(renderHistoryItem).join('');
          bindHistoryDetailButtons(listEl, msg);
          renderPager(pagerEl, d.total, page, d.per_page);
        } catch (renderErr) {
          console.error(renderErr);
          listEl.textContent = '利用履歴の表示処理でエラーが発生しました';
        }
      }).catch(function (err) {
        listEl.textContent = apiErrorMessage(err, '通信エラーが発生しました');
      });
    }

    loadPage(1);
  }

  function historyCacheKey(item) {
    if (item.task_id) {
      return 't:' + item.task_id;
    }
    if (item.history_id) {
      return 'h:' + item.history_id;
    }
    return 'x:' + (item.date || '') + ':' + (item.cast_id || 0);
  }

  function formatHistoryPrice(item) {
    if (item.source === 'history' || !item.task_id) {
      if (!item.total_price_display || item.total_price_display === '—' || item.total_price_display === '0') {
        return '—';
      }
    }
    if (item.total_price_display) {
      return String(item.total_price_display).indexOf('円') >= 0
        ? item.total_price_display
        : (item.total_price_display + '円');
    }
    return formatYen(item.total_price);
  }

  function renderHistoryItem(item) {
    var key = historyCacheKey(item);
    historyDetailCache[key] = item;
    var meta = escapeHtml(item.date_display || (item.date + ' ' + (item.start || '') + '〜' + (item.end || '')));
    var badge = item.stat === 2 ? ' <span class="member-history-badge reserved">予約</span>' : '';
    var evalBadge = item.evaluation ? ' <span class="member-history-badge evaluated">評価済み</span>' : '';
    var course = item.course_label || (item.course ? (String(item.course) + '分') : '');
    var price = formatHistoryPrice(item);
    if (price === '—') price = '';
    var evalBtn = '';
    if (item.evaluation) {
      evalBtn = '<span class="member-history-eval-status">評価済み</span>';
    } else if (item.can_evaluate) {
      evalBtn = '<button type="button" class="member-history-eval-btn" data-history-key="' + escapeHtml(key) + '">評価</button>';
    }

    var html = '<div class="member-history-item" data-history-key="' + escapeHtml(key) + '">';
    html += '<div class="member-history-item-head">';
    html += '<div class="member-history-item-main">';
    html += '<div class="member-history-meta">' + meta + badge + evalBadge + '</div>';
    html += '<div class="member-history-name">' + escapeHtml(historyGirlLabel(item)) + '</div>';
    html += '<div class="member-history-course">';
    if (course) html += escapeHtml(course);
    if (price) html += (course ? ' · ' : '') + escapeHtml(price);
    html += '</div>';
    html += '</div>';
    html += '<div class="member-history-item-actions">';
    html += evalBtn;
    html += '<button type="button" class="member-history-detail-btn" data-history-key="' + escapeHtml(key) + '">詳細</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    return html;
  }

  function bindHistoryFavButtons(container, msg) {
    container.querySelectorAll('.member-fav-add-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var girlsId = parseInt(btn.getAttribute('data-girls-id'), 10);
        toggleFavorite(girlsId, msg, btn);
      });
    });
  }

  function bindEvalForms(container, msg, onDone) {
    container.querySelectorAll('.member-eval-form').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var taskId = parseInt(form.getAttribute('data-task-id'), 10);
        var payload = { task_id: taskId, comment: (form.querySelector('textarea[name="comment"]') || {}).value || '' };
        var missing = false;
        EVAL_FIELDS.forEach(function (f) {
          var sel = form.querySelector('[name="' + f.key + '"]');
          var val = sel ? parseInt(sel.value, 10) : 0;
          if (!val) missing = true;
          payload[f.key] = val;
        });
        if (missing) {
          showMsg(msg, 'すべての評価項目を選択してください', 'error');
          return;
        }
        postJson('202', payload).then(function (res) {
          if (res.status !== 0) {
            showMsg(msg, res.message || '評価の保存に失敗しました', 'error');
            return;
          }
          showMsg(msg, res.message || '評価を保存しました', 'success');
          if (onDone) onDone();
          bindHistoryReload(historyPage);
        }).catch(function () {
          showMsg(msg, '通信エラーが発生しました', 'error');
        });
      });
    });
  }

  function renderPager(el, total, page, perPage) {
    if (!el || total <= perPage) return;
    var maxPage = Math.ceil(total / perPage);
    el.innerHTML = '';
    var prev = document.createElement('button');
    prev.textContent = '前へ';
    prev.disabled = page <= 1;
    prev.addEventListener('click', function () {
      if (page > 1) bindHistoryReload(page - 1);
    });
    var next = document.createElement('button');
    next.textContent = '次へ';
    next.disabled = page >= maxPage;
    next.addEventListener('click', function () {
      if (page < maxPage) bindHistoryReload(page + 1);
    });
    el.appendChild(prev);
    el.appendChild(next);
  }

  function bindHistoryReload(page) {
    var listEl = document.getElementById('memberHistoryList');
    var noteEl = document.getElementById('memberHistoryNote');
    var pagerEl = document.getElementById('memberHistoryPager');
    var msg = document.getElementById('memberMsg');
    historyPage = page;
    listEl.textContent = '読み込み中…';
    postJson('201', { page: page, per_page: 10 }).then(function (res) {
      if (res.status !== 0) {
        listEl.textContent = res.message || '履歴の取得に失敗しました';
        return;
      }
      var d = res.data;
      if (!d.guest_linked) {
        listEl.textContent = '利用履歴はありません';
        return;
      }
      if (!d.items || d.items.length === 0) {
        listEl.textContent = '利用履歴はありません';
        renderPager(pagerEl, d.total, page, d.per_page);
        return;
      }
      listEl.innerHTML = d.items.map(renderHistoryItem).join('');
      bindHistoryDetailButtons(listEl, msg);
      renderPager(pagerEl, d.total, page, d.per_page);
      listEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  function escapeHtml(s) {
    if (s == null) return '';
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  function bindPasswordReset() {
    var sendBtn = document.getElementById('resetSendSms');
    if (!sendBtn) return;
    var msg = document.getElementById('memberMsg');
    var step1 = document.getElementById('resetStep1');
    var step2 = document.getElementById('resetStep2');
    var step3 = document.getElementById('resetStep3');
    var mockEl = document.getElementById('resetMockCode');
    var tokenEl = document.getElementById('resetToken');
    var form = document.getElementById('memberResetForm');
    var phoneEl = document.getElementById('resetPhone');

    sendBtn.addEventListener('click', function () {
      hideMsg(msg);
      postJson('004', { phone: phoneEl.value }).then(function (res) {
        if (res.status !== 0) {
          showMsg(msg, res.message || '送信に失敗しました', 'error');
          return;
        }
        if (mockEl && res.data && res.data.mock_code) {
          mockEl.textContent = '【開発用】認証コード: ' + res.data.mock_code;
        }
        step1.classList.remove('active');
        step2.classList.add('active');
        showMsg(msg, '認証コードを送信しました', 'info');
      }).catch(function () {
        showMsg(msg, '通信エラーが発生しました', 'error');
      });
    });

    document.getElementById('resetVerify').addEventListener('click', function () {
      hideMsg(msg);
      postJson('005', {
        phone: phoneEl.value,
        code: document.getElementById('resetCode').value
      }).then(function (res) {
        if (res.status !== 0) {
          showMsg(msg, res.message || '認証に失敗しました', 'error');
          return;
        }
        tokenEl.value = res.data.reset_token;
        step2.classList.remove('active');
        step3.classList.add('active');
        showMsg(msg, '新しいパスワードを設定してください', 'info');
      }).catch(function () {
        showMsg(msg, '通信エラーが発生しました', 'error');
      });
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      hideMsg(msg);
      postJson('006', {
        reset_token: tokenEl.value,
        password: document.getElementById('resetPassword').value,
        password_confirm: document.getElementById('resetPasswordConfirm').value
      }).then(function (res) {
        if (res.status !== 0) {
          showMsg(msg, res.message || '変更に失敗しました', 'error');
          return;
        }
        showMsg(msg, res.message || 'パスワードを変更しました', 'success');
        setTimeout(function () {
          window.location.href = 'member_login.php';
        }, 1500);
      }).catch(function () {
        showMsg(msg, '通信エラーが発生しました', 'error');
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    bindLogin();
    bindRegister();
    bindMypage();
    bindPasswordReset();
  });
})();
