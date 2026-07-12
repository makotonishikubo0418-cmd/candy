$(function() {
	
	// ローディング
	jQuery.event.add(window,"load",function() { // 全ての読み込み完了後に呼ばれる関数
	  $("#loading").fadeOut(600);
	});
	
	// エントランスフェードアウト
	$("#entrance #enter-pc, #entrance #enter-sp").click(function() {
		$("body").css( { 'overflow': 'hidden' } );	
		$("#index").css( { 'display': 'block' } );	
		$("#entrance").fadeOut(600);
		$("#news").fadeIn(600);
		videoResize();  
	});
	
	// ヘッダーナビ初期非表示
	$( '#headNavi' ).css( { 'margin-top': '-60px' } );
	// ヘッダーナビの表示フラグ
	var header_show_flag = false;
	// ヘッダーナビの表示処理
	$( window ).scroll( function() {
		if ( $( window ).scrollTop() > 136 ) {
			if ( !header_show_flag ) {
				$( '#headNavi' ).stop().animate( { 'margin-top': 0 } , 300 );
				header_show_flag = true;
			}
		} else {
			if ( header_show_flag ) {
				$( '#headNavi' ).stop().animate( { 'margin-top': '-60px'} , 300 );
				header_show_flag = false;
			}
		}
	} );

	// news表示時のスクロール禁止
	$("a.iframe").click(function() {
		$("body").css( { 'overflow': 'hidden' } );
		$("#news").fadeIn(600);
	});
	$("#news .bg").click(function() {
		$("body").css( { 'overflow': 'auto' } );
		$("#news").fadeOut(600);
	});
});
	// トップ動画リサイズ処理
	function videoResize () {
		var videoW=document.body.clientWidth;
		var videoH=videoW*9/16;
		$('#index #player').css('width', videoW);
		$('#index #player').css('height', videoH);
	}
	// ウィンドウサイズが変更された時の処理
	$(window).resize( function() {
		videoResize();
	} );
	jQuery.event.add(window,"load",function() {
		videoResize();
	});  
	videoResize();
