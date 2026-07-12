
$(function () {
	// ローディング
	jQuery.event.add(window, "load", function () { // 全ての読み込み完了後に呼ばれる関数
		$("#loading").fadeOut(600);
	});
	const matchMedia = window.matchMedia('(max-width:768px)');

	if (matchMedia.matches) {
		// 768px以下で行う処理（SP版）

		// エントランスフェードアウト
		$("#entrance #enter-pc, #entrance #enter-sp").click(function () {
			$("body").css({ 'overflow': 'hidden' });
			$("#index").css({ 'display': 'block' });
			$("#entrance").fadeOut(600);
			$("#news").fadeIn(600);
		});

		// news表示時のスクロール禁止
		$("a.iframe").click(function () {
			$("body").css({ 'overflow': 'hidden' });
			$("#news").fadeIn(600);
		});
		$("#news .bg , #news .close").click(function () {
			$("body").css({ 'overflow': 'auto' });
			$("#news").fadeOut(600);
		});

		// スケジュールタブ切り替え（SP版）
		$("#schedule #listToday .tab li").click(function () {
			$("#listToday").css({ 'display': 'none' });
			$("#listTomorrow").fadeIn(600);
			// アクティブ状態の切り替え
			$("#listToday .tab li").removeClass('active');
			$("#listTomorrow .tab li").removeClass('active');
			$("#listTomorrow .tab li:nth-child(2)").addClass('active');
		});
		$("#schedule #listTomorrow .tab li").click(function () {
			$("#listTomorrow").css({ 'display': 'none' });
			$("#listToday").fadeIn(600);
			// アクティブ状態の切り替え
			$("#listToday .tab li").removeClass('active');
			$("#listTomorrow .tab li").removeClass('active');
			$("#listToday .tab li:nth-child(1)").addClass('active');
		});
	} else {
		// 768px以上で行う処理（PC版）
		
		// エントランスフェードアウト
		$("#entrance #enter-pc, #entrance #enter-sp").click(function () {
			$("body").css({ 'overflow': 'hidden' });
			$("#index").css({ 'display': 'block' });
			$("#entrance").fadeOut(600);
			$("#news").fadeIn(600);
			videoResize();
		});

		// ヘッダーメニューをheadNaviにコピー
		if ($('.header .menu.pcOnly').length > 0 && $('#headNavi .menu').length === 0) {
			var headerMenu = $('.header .menu.pcOnly').clone(true);
			$('#headNavi').append(headerMenu);
		}
		// ヘッダーナビ初期非表示
		$('#headNavi').css({ 'margin-top': '-80px' });
		// ヘッダーナビの表示フラグ
		var header_show_flag = false;
		// ヘッダーナビの表示処理
		$(window).scroll(function () {
			if ($(window).scrollTop() > 136) {
				if (!header_show_flag) {
					$('#headNavi').stop().animate({ 'margin-top': 0 }, 300);
					header_show_flag = true;
				}
			} else {
				if (header_show_flag) {
					$('#headNavi').stop().animate({ 'margin-top': '-80px' }, 300);
					header_show_flag = false;
				}
			}
		});

		// news表示時のスクロール禁止
		$("a.iframe").click(function () {
			$("body").css({ 'overflow': 'hidden' });
			$("#news").fadeIn(600);
		});
		$("#news .bg").click(function () {
			$("body").css({ 'overflow': 'auto' });
			$("#news").fadeOut(600);
		});

		// トップ動画リサイズ処理
		function videoResize() {
			videoH = $(window).height() - $('div.header').height();
			$('#index #player').css('height', videoH);
		}
		// ウィンドウサイズが変更された時の処理
		$(window).resize(function () {
			videoResize();
		});
		jQuery.event.add(window, "load", function () {
			videoResize();
		});

		// トップサムネフェード処理
		$('#index #tile .box').hover(function () {
			$('.photo img', this).stop().animate({ 'opacity': .6 }, 400);
		},
			function () {
				$('.photo img', this).stop().animate({ 'opacity': 1 }, 400);
			});
		// 在籍一覧サムネフェード処理
		$('#girlsL .list .box').hover(function () {
			$('.photo img', this).stop().animate({ 'opacity': .6 }, 400);
		},
			function () {
				$('.photo img', this).stop().animate({ 'opacity': 1 }, 400);
			});
		// マイページサムネフェード処理
		$('#mypage .list .box .photo').hover(function () {
			$('img', this).stop().animate({ 'opacity': .6 }, 400);
		},
			function () {
				$('img', this).stop().animate({ 'opacity': 1 }, 400);
			});
		// 求人サムネフェード処理
		$('#job .facilities ul li').hover(function () {
			$('.photo img', this).stop().animate({ 'opacity': .4 }, 400);
		},
			function () {
				$('.photo img', this).stop().animate({ 'opacity': 1 }, 400);
			});

		// トップサムネ動画処理
		$(document).on({
			mouseenter: function () {
				if ($('.video', this)[0]) {
					$('.video', this).stop().animate({ 'opacity': 1 }, 400);
					$('.video', this)[0].currentTime = $('.video', this)[0].initialTime || 0;
					$('.video', this)[0].play();
				}
			},
			mouseleave: function () {
				if ($('.video', this)[0]) {
					$('.video', this).stop().animate({ 'opacity': 0 }, 400);
					$('.video', this)[0].pause();
				}
			}
		}, '#index #tile .box');

		// スケジュールタブ切り替え（PC版）
		$("#schedule #listToday .tab li").click(function () {
			$("#listToday").css({ 'display': 'none' });
			$("#listTomorrow").fadeIn(600);
			// アクティブ状態の切り替え
			$("#listToday .tab li").removeClass('active');
			$("#listTomorrow .tab li").removeClass('active');
			$("#listTomorrow .tab li:nth-child(2)").addClass('active');
		});
		$("#schedule #listTomorrow .tab li").click(function () {
			$("#listTomorrow").css({ 'display': 'none' });
			$("#listToday").fadeIn(600);
			// アクティブ状態の切り替え
			$("#listToday .tab li").removeClass('active');
			$("#listTomorrow .tab li").removeClass('active');
			$("#listToday .tab li:nth-child(1)").addClass('active');
		});

		// 新人詳細コンテンツ高さリサイズ処理
		function gilrsResize() {
			contH = $(window).height() - ($('div.header').height() + $('div.footer').height());
			$('#girlsD .main').css('height', contH);
		}
		// ウィンドウサイズが変更された時の処理
		$(window).resize(function () {
			gilrsResize();
		});
		jQuery.event.add(window, "load", function () {
			gilrsResize();
		});

		// 女の子一覧リサイズ処理
		function listResize() {
			listW = ($(window).width() / 4) - 7.5;
			listH = listW * 207 / 310;
			$('#girlsL .list .box').css('height', listH);
		}
		// ウィンドウサイズが変更された時の処理
		$(window).resize(function () {
			listResize();
		});
		window.onload = function () {
			listResize();
		};
	}

});

// 画像読み込み
addOnloadEvent(function () { WAimgLoadIni(); });