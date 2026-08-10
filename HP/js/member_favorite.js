(function () {
  'use strict';

  var API = './member/api.php';
  var girlsId = window.MEMBER_FAVORITE_GIRLS_ID ? parseInt(window.MEMBER_FAVORITE_GIRLS_ID, 10) : 0;
  if (!girlsId) return;

  function postJson(fno, body) {
    return fetch(API + '?fno=' + encodeURIComponent(fno), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify(body || {})
    }).then(function (r) { return r.json(); });
  }

  function setFavUi(isFav) {
    document.querySelectorAll('a[href*="flw=1"], a[href*="unf=1"]').forEach(function (a) {
      var href = a.getAttribute('href') || '';
      if (href.indexOf('flw=1') !== -1 && isFav) {
        a.style.display = 'none';
      } else if (href.indexOf('unf=1') !== -1 && !isFav) {
        a.style.display = 'none';
      } else if (href.indexOf('flw=1') !== -1 || href.indexOf('unf=1') !== -1) {
        a.style.display = '';
      }
    });
  }

  function bindLinks() {
    document.querySelectorAll('a[href*="flw=1"], a[href*="unf=1"]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        var href = a.getAttribute('href') || '';
        var add = href.indexOf('flw=1') !== -1;
        e.preventDefault();
        postJson(add ? '302' : '303', { girls_id: girlsId }).then(function (res) {
          if (res.status === -3) {
            window.location.href = 'member_login.php';
            return;
          }
          if (res.status !== 0) {
            alert(res.message || '操作に失敗しました');
            return;
          }
          setFavUi(add);
        }).catch(function () {
          alert('通信エラーが発生しました');
        });
      });
    });
  }

  postJson('304', {}).then(function (res) {
    if (res.status === -3) return;
    if (res.status !== 0) return;
    var ids = (res.data && res.data.girls_ids) ? res.data.girls_ids : [];
    var isFav = ids.indexOf(girlsId) !== -1;
    setFavUi(isFav);
    bindLinks();
  }).catch(function () {});
})();
