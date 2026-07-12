/*
amadare web application development kit
Copyright(C) 2015 PuroductionAMADARE allrights reserved.*/

/*******************************************
*
*		  環境変数
*
*******************************************/
var WAdomain = location.href.split('/')[2];
/*******************************************
*		  画像を必要な分だけ読み込む(準備)
*******************************************/
var varWAimg=[];
var varWAimgOpa=[];
function WAimgLoadIni(){
	WAaddCssRule(".WACSSfadeIn{-webkit-animation: WACSSanime1 1s linear 1;-moz-animation: WACSSanime1 1s linear 1;-o-animation: WACSSanime1 1s linear 1;animation:WACSSanime1 1s linear 1;}");
	WAaddCssRule("@keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	WAaddCssRule("@-o-keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	WAaddCssRule("@-webkit-keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	WAaddCssRule("@-moz-keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	WAimgLoadAdd();
	window.addEventListener("scroll",_WAimgLoad,0);
}
var _WAimgLoadTimer;
function _WAimgLoad(){
	clearTimeout(_WAimgLoadTimer);
	_WAimgLoadTimer=setTimeout(function(){__WAimgLoad();},500);
}
function __WAimgLoad(){
	var tmp=[];
	var h,t,sc,height;
	if(varWAimg.length >0){
		sc=getScrollPosition();
		h=sc+getBrowserHeight();
		for(var i=0;i<varWAimg.length;i++){
			height=parseInt(varWAimg[i].offsetHeight);
			t=xy(varWAimg[i])[0];
			if(t<h && t+height>sc){
				addClassName(varWAimg[i],"WACSSfadeIn");
				varWAimg[i].style.opacity=1;
				varWAimg[i].alt="";
				varWAimg[i].src=varWAimg[i].title;
			}else{
				tmp.push(varWAimg[i]);
			}
		}
		varWAimg=tmp;
	}
}
function WAimgLoadAdd(){
	var tmp=document.getElementsByTagName("img");
	for(var i=0;i<tmp.length;i++){
		// nolazyクラスを持つ画像は遅延読み込みの対象から除外
		if(tmp[i].className && tmp[i].className.indexOf('nolazy') !== -1){
			continue;
		}
		if(tmp[i].alt.length>0){
			tmp[i].title=tmp[i].alt;
			varWAimg.push(tmp[i]);
		}
	}
	_WAimgLoad();
}
/*******************************************
*		  CSSを交互に変更
*******************************************/
function WAcssToggle(id,stylep,css1,css2){
	var objs=getObj(id).style;
	objs[stylep]= (objs[stylep]==css1) ? css2 : css1;
}
/*******************************************
*		  クラス変更
*******************************************/
function WAclass(ids,cName){
	var p=[];
	var i,l;
	p=ids.split(",");
	for(i=0,l=p.length;i<l;i++){
		getObj(p[i]).className=cName;
	}
}
/*******************************************
*		  コンテンツが画面一杯になるように高さを調節
*******************************************/
function WAmaxDiv(id){
	var obj=getObj(id);
	obj.style.height="3000px";
	scrollTo(0,1);
	var bh=getBrowserHeight();
	var top=xy(obj)[0];
	var h=bh-top;
	if(h<0){
		h=0;
	}
	obj.style.height=h+"px";
	scrollTo(0,0);
}
/*******************************************
*		  スクロールする
*******************************************/
var AnimWait=50;
function WAscroll(id){
	var scroll_ahandle=null;
	var scroll_timer=null;
    var sr=function(vy){
        /*window.pageYOffset=vy;
        document.body.scrollTop=vy;*/
        window.scrollTo(0,vy);
    };
	scroll_ahandle=anim(getScrollPosition(),xy(getObj(id))[0]);
	scroll_timer=setInterval(function(){
		var y=_anim(scroll_ahandle);
		(y==null)? clearInterval(scroll_timer):sr(y);
	},AnimWait);
}
/*******************************************
*		  エレメントの表示・非表示
*******************************************/
function WAtoggle(id_list){
	var p=id_list.split(",");
	var obj;
	for(var i=0,l=p.length;i<l;i++){
		obj=document.getElementById(p[i].substr(1,p[i].length-1));
		if(obj==null){
			alert(p[i].substr(1,p[i].length-1));
		}else{
			switch(p[i].substr(0,1)){
				case "+":
					obj.style.display="block";
					break;
				case "-":
					obj.style.display="none";
					break;
			}
		}
	}
}
/*******************************************
*		  CSSルール追加
*******************************************/
function WAaddCssRule(cssRule){
	var style = document.getElementsByTagName('head')[0].appendChild(document.createElement('style'));
	style.type = 'text/css';
	try{
		style.sheet.insertRule(cssRule, style.sheet.cssRules.length);
	}catch(e){};
}
/*******************************************
*		  グループのエレメントの表示・非表示
*******************************************/
function WAradio(group,disp){
	var gObj=getObj(group).childNodes;
	for(var i=0;i<gObj.length;i++){
		if(gObj[i].nodeName.substr(0,1)!="#"){
			gObj[i].style.display=(gObj[i].id==disp) ? "block":"none";
		}
	}
	getObj(group).style.display="block";
}
/*******************************************
*		  ファイルを読み込む(非同期)
*******************************************/
function WAincludeFile(id,file){
	var includeFile_handle=null;
	var includeFile_obj=null;
	includeFile_obj=getObj(id);
	includeFile_handle=ajax_GET(file,"",function(){
		if (includeFile_handle.readyState == 4 && includeFile_handle.status == 200){
			includeFile_obj.innerHTML=get_body(includeFile_handle.responseText);
		}
	});
}
/*******************************************
*		  ファイルを読み込む(同期)
*******************************************/
function WAfileGet(file){
	var request = new XMLHttpRequest();
	if(file.indexOf("?")==-1){
		request.open('GET', file+"?"+(new Date()).getTime(), false);
	}else{
		request.open('GET', file+"&"+(new Date()).getTime(), false);
	}
	request.send(null);
	if (request.readyState == 4 && request.status == 200){
 		return request.responseText;
	}else{
		return request.status;
	}
}
/*******************************************
*		  マスク
*******************************************/
function WAmask(){
	var obj=getObj("maskObj");
	switch(arguments[0]){
		case "on":
		case "ON":
			if(obj==null){
				obj=document.createElement('div');
				obj.id="maskObj";
				obj.style.cssText="position:absolute;width:100%;height:"+getPageSize()[1]+"px;background:"+arguments[1]+";opacity:0;z-index:399;top:0px;transition:all 1s ease-out;";
				document.body.appendChild(obj);
				obj.addEventListener("touchstart", function(){event.preventDefault();}, false);
				obj.addEventListener("touchmove", function(){event.preventDefault();}, false);
				obj.addEventListener("touchend", function(){event.preventDefault();}, false);
				setTimeout(function(){getObj("maskObj").style.opacity=0.7;},10);
			}
			break;
		case "off":
		case "OFF":
			if(obj!=null){
   				deleteObj(obj);
			}
			break;
	}
}
/*******************************************
*		  submitする
*******************************************/
function WAsubmit(form,func){
	var frm=document.createElement('iframe');
	frm.id="tmpfrm";
	frm.style.display="none";
	
	document.body.appendChild(frm);
	frm.onload=function(){eval(func);deleteObj(getObj("tmpfrm"));}
	var obj=getObj(form);
	obj.target="tmpfrm";
	obj.submit();
}
/*******************************************
*		  get <body>-</body>
*******************************************/
function get_body(s){
	/*return s.split("</body>")[0].split("<body")[1].split(">").splice(0,1).join(">");*/
	s0=s.split("</body>");
	s0=s0[0].split("<body");
	s0=s0[1].split(">");
	s0.splice(0,1);
	return s0.join(">");
}
/*******************************************
*	  文字列を分割して連想配列で返す$_GET
*******************************************/
function arraySplit(s){
	var r=[],n=[];
	var name;
	var p=s.split("&");
	for(var i=0,len=p.length;i<len;i++){
		n=p[i].split("=");
		name=""+n[0];
		n.splice(0,1);
		r[name]=""+n.join("=");
	}
	return r;
}
/*******************************************
*		  連想配列内に要素があるか調べる
*******************************************/
function arrayKeyExists ( key, search ) {
	if( !search || (search.constructor !== Array && search.constructor !== Object) ){
		return false;
	}
	return key in search;
}
/*******************************************
*		  ローカルストレージ
*******************************************/
function localLoad(key){
	return localStorage.getItem(key);
}
function localSave(key,value){
	localStorage.setItem(key,value);
}
function localRemove(key){
	localStorage.removeItem(key);
}
function localAllClear(){
	localStorage.clear();
}
/*******************************************
*
*		  クッキー（localStrageを推奨)
*
*******************************************/
function CookieRead(key) {
	 var sCookie = document.cookie;
	 var aData = sCookie.split(";");
	 var oExp = new RegExp(" ", "g");
	 key = key.replace(oExp, "");

	 var i = 0;
	 while (aData[i]) {
		  var aWord = aData[i].split("=");
		  aWord[0] = aWord[0].replace(oExp, "");
		  if (key == aWord[0]) return unescape(aWord[1]);
		  if (++i >= aData.length) break;
	 }
	 return ""; 
}
function CookieWrite(key, value, days) {
	 var str = key + "=" + escape(value) + ";path=/;";
	 if (days != 0) {
		  var dt = new Date();
		  dt.setDate(dt.getDate() + days);
		  str += "expires=" + dt.toGMTString() + ";";
	 }
	 document.cookie = str;
}
function CookieDelete(key) 
{
	 var dt = new Date();
	 dt.setTime(0);
	 var str = key + "=;path=/;expires=" + dt.toGMTString();
	 document.cookie = str;
}
function CookieCheck(){
	var s="check"+Math.random();
	CookieWrite(s,"OK",1);
	if(CookieRead(s)!="OK"){
		return false;
	}else{
		CookieDelete(s);
		return true;
	}
}
/*******************************************
*		  連想配列内に要素があるか調べる
*******************************************/
function array_key_exists ( key, search ) {
	if( !search || (search.constructor !== Array && search.constructor !== Object) ){
		return false;
	}
	return key in search;
}
/*******************************************
*		  BIOS
*******************************************/
anim_handle_st=[];
anim_handle_ed=[];
anim_handle_now=[];
function anim(st,ed){
	var h=(new Date).getTime();
	while(array_key_exists(h,anim_handle_st) !=false){
		h=(new Date).getTime();
	}
	anim_handle_st[h]=parseInt(st);
	anim_handle_ed[h]=parseInt(ed);
	anim_handle_now[h]=parseInt(st);
	return h;
}
function _anim(handle){
	var h=handle;
	var now=anim_handle_now[h];
	if(now==null){
		delete anim_handle_st[h];
		delete anim_handle_ed[h];
		delete anim_handle_now[h];
	}else if(anim_handle_ed[h]==anim_handle_now[h]){
		anim_handle_now[h]=null;
	}else{
		anim_handle_now[h]+=parseInt((anim_handle_ed[h]-anim_handle_now[h])/2);
		if(Math.abs(anim_handle_ed[h]-anim_handle_now[h])==1){
			anim_handle_now[h]=anim_handle_ed[h];
		}
	}
	return now;
}
function _anim_removehandle(handle){
	delete anim_handle_st[handle];
	delete anim_handle_ed[handle];
	delete anim_handle_now[handle];
}
/*エレメントを返す*/
function getObj(id){
	return window.document.getElementById(id);
}
/*エレメントを削除*/
function deleteObj(obj){
		obj.parentNode.removeChild(obj);
}
/*エレメントの絶対座標を返す*/
function xy(element) {
	var top = 0, left = 0;
	do {
		top  += element.offsetTop;
		left += element.offsetLeft;
	} while (element = element.offsetParent);
	return [top, left];
}

/*オブジェクトにcssクラスを追加*/
function addClassName(obj,add_classes){
  var tmp_hash = [];
  var new_class_names = [];
  var class_names = obj.className.split(" ").concat(add_classes.split(" "));
  for(var i in class_names){if(class_names[i] != ""){tmp_hash[class_names[i]] = 0;}}
  for(var key in tmp_hash){new_class_names.push(key);}
  obj.className = new_class_names.join(" ");
}


/*ajaxでAPIから値を受け取り処理関数へ*/
ajax_test=null;
function ajax_GET(url,param,ok_func){
	var handle;
	var url_para=url;
	if (window.XMLHttpRequest){
		handle = new XMLHttpRequest();
	}else{
		if (window.ActiveXObject){
			handle = new ActiveXObject("Microsoft.XMLHTTP");
		}else{
			handle = null;
		}
	}
	
	if(param.length>0){
		url_para=url_para+"?"+param;
	}
	handle.onreadystatechange = ok_func;
	handle.open("GET", url_para, true);
	handle.send(null);
	return handle;
}
function ajax_POST(url,param,ok_func){
	var handle = new XMLHttpRequest();
	handle.onreadystatechange =ok_func;
	handle.open( 'POST', url ,true);
	handle.setRequestHeader( 'Content-Type', "application/x-www-form-urlencoded");
	handle.send( EncodeHTMLForm( param ) );
	return handle;
}
function EncodeHTMLForm( data )
{
    var params = [];
    for( var name in data ){
        var value = data[ name ];
        var param = encodeURIComponent( name ) + '=' + encodeURIComponent( value );
        params.push( param );
    }
	return params.join( '&' ).replace( /%20/g, '+' );
}
/*ブラウザの表示サイズの高さを得る*/
function getBrowserHeight() {
		if ( window.innerHeight ) {
				return window.innerHeight;
		}
		else if ( document.documentElement && document.documentElement.clientHeight != 0 ) {
				return document.documentElement.clientHeight;
		}
		else if ( document.body ) {
				return document.body.clientHeight;
		}
		return 0;
}
/*ブラウザの表示サイズの幅を得る*/
function getBrowserWidth() {
		if ( window.innerWidth ) {
				return window.innerWidth;
		}
		else if ( document.documentElement && document.documentElement.clientWidth != 0 ) {
				return document.documentElement.clientWidth;
		}
		else if ( document.body ) {
				return document.body.clientWidth;
		}
		return 0;
}
/*現在のスクロール位置の高さを得る*/
function getScrollPosition() {
	return (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
}
/*onloadイベントを登録する*/
function addOnloadEvent(fnc){  
	  if ( typeof window.addEventListener != "undefined" )  
		window.addEventListener( "load", fnc, false );  
	  else if ( typeof window.attachEvent != "undefined" ) {  
		window.attachEvent( "onload", fnc );  
	  }  
	  else {  
		if ( window.onload != null ) {  
		  var oldOnload = window.onload;  
		  window.onload = function ( e ) {  
			oldOnload( e );  
			window[fnc]();  
		  };  
		}  
		else  
		  window.onload = fnc;  
	  }  
	} 
/*メタ文字のエスケープ*/
function meta_to_escape(str) {
	var s;
	s = str.replace(/&/g,"&amp;");
	s = s.replace(/"/g,"&quot;");
	s = s.replace(/'/g,"&#039;");
	s = s.replace(/</g,"&lt;");
	s = s.replace(/>/g,"&gt;");
	return s;
} 
/*ページサイズ*/
function getPageSize(){
	
	var xScroll, yScroll;
	
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else {
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	
	var windowWidth, windowHeight;
	if (self.innerHeight) {
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) {
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}	
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}

	if(xScroll < windowWidth){	
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}


	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	return arrayPageSize;
}
/**********************************************
*	テキストを送信用にエスケープする
************************************************/
function WAtextEsc(s){
	var a;
	a=s;
	a=a.split('"').join("”");
	a=a.split("'").join("’");
	a=a.split('&').join("＆");
	a=a.split('=').join("＝");
	return a;
}
//movie
function _WAmovieCreateImgElements(movieId, movie) {
	var div = getObj(movieId);
	for (var i = 0; i < movie.frames.length; i++) {
		var frame = movie.frames[i];
		var img = document.createElement('img');
		frame.img = img;
		img.src = frame.base64;
		img.style.display = "none";
		img.width = movie.width;
		img.height = movie.height;
		div.appendChild(img);
	}
}

function _WAmovieGetTagContents(tag, data) {
	var beginTag = '<' + tag + '>';
	var endTag = '</' + tag + '>';
	var cutBegin = data.split(beginTag)[1];
	return cutBegin.split(endTag)[0];
}

function _WAmovieGetMovieFrames(mov) {
	var f = mov.split('</frame><frame>');
	f[0] = f[0].split('<frame>')[1];
	f[f.length - 1] = f[f.length - 1].split('</frame>')[0];

	var frames = [];
	for (var i = 0; i < f.length; i++) {
		var frame = [];
		frame.count = f[i].split('&')[0];
		frame.base64 = f[i].split('&')[1];
		frame.img = null;
		frames[i] = frame;
	}
	return frames;
}

function _WAmovieCreateMovie(movieId, data) {
	var movie = [];

	movie.id = movieId;
	movie.title = _WAmovieGetTagContents('title', data);
	movie.frameRate = _WAmovieGetTagContents('frameRate', data);
	movie.copyRights = _WAmovieGetTagContents('copyRights', data);
	movie.width = _WAmovieGetTagContents('width', data);
	movie.height = _WAmovieGetTagContents('height', data);
	loop = _WAmovieGetTagContents('loop', data);
	if (loop == "true") {
		movie.loop = true;
	} else {
		movie.loop = false;
	}
	movie.script = _WAmovieGetTagContents('script', data);

	var mov = _WAmovieGetTagContents('movie', data);
	movie.frames = _WAmovieGetMovieFrames(mov);

	movie.idx = 0;
	movie.frameIdx = 0;
	movie.tid = -1;

	return movie;
}

var _WAmovies = [];

function _WAmovieGetMovie(movieId) {
	for (var idx = 0; idx < _WAmovies.length; idx++) {
		if (_WAmovies[idx] == null) {
			continue;
		}
		if (_WAmovies[idx].id == movieId) {
			return _WAmovies[idx];
		}
	}
	return null;
}

function _WAmovie() {
	function callback(movieId) {
		var movie = _WAmovieGetMovie(movieId);
		if (movie == null) {
			return;
		}
		var frame = movie.frames[movie.frameIdx];
		frame.img.style.display = "none";
		movie.idx++;
		if (movie.idx < movie.count) {
			frame.img.style.display = "block";
			return;
		}
		movie.idx = 0;
		movie.frameIdx++;
		if (movie.frameIdx < movie.frames.length) {
			frame = movie.frames[movie.frameIdx];
			frame.img.style.display = "block";
			return;
		}
		movie.frameIdx = 0;
//		movie.script();
		if (movie.loop == true) {
			frame = movie.frames[movie.frameIdx];
			frame.img.style.display = "block";
			return;
		}
		clearInterval(movie.tid);
		for (var i = 0; i < _WAmovies.length; i++) {
			if (_WAmovies[i] == null) {
				continue;
			}
			if (_WAmovies[i].id == movieId) {
				_WAmovies[i] = null;
				break;
			}
		}
	}

	return {
		start:function(movieId) {
			var movie = _WAmovieGetMovie(movieId);
			if (movie == null) {
				return;
			}

			var frame = movie.frames[movie.frameIdx];
			frame.img.style.display = "block";
			movie.idx++;
			if (movie.tid == -1) {
				movie.tid = setInterval(function() {
					callback(movieId);
				}, 1000 / movie.frameRate);
			}
		},
		stop:function(movieId) {
			var movie = _WAmovieGetMovie(movieId);
			if (movie == null) {
				return;
			}
			clearInterval(movie.tid);
			movie.tid = -1;
		},
		cont:function(movieId) {
			var movie = _WAmovieGetMovie(movieId);
			if (movie == null) {
				return;
			}
			if (movie.tid == -1) {
				movie.tid = setInterval(function() {
					callback(movieId);
				}, 1000 / movie.frameRate);
			}
		}
	}
}

function WAmovie(movieId, data) {
	WAaddCssRule(".playIcon {font-size: 80px;position: absolute;width: 1.4em;height: 1.4em;border: 0.1em solid #fff;border-radius: 100%;line-height: 1em;pointer-events: none;-webkit-transform: translate(260px, 140px);-moz-transform: translate(260px, 140px);-o-transform: translate(260px, 140px);-ms-transform: translate(260px, 140px);}");
	WAaddCssRule(".playIcon::before {content: '';position: absolute;top: 18px;left: 30px;width: 0;height: 0;border-top: 0.4em solid transparent;border-left: 0.6em solid #fff;border-bottom: 0.4em solid transparent;}");
	for (var i = 0; i < _WAmovies.length; i++) {
		if (_WAmovies[i] == null) {
			continue;
		}
		if (_WAmovies[i].id === movieId) {
			return;
		}
	}

	var found = false;
	var idx;
	for (idx = 0; idx < _WAmovies.length; idx++) {
		if (_WAmovies[idx] === null) {
			found = true;
			break;
		}
	}
	var movie = _WAmovieCreateMovie(movieId, data);
	if (found == true) {
		_WAmovies[idx] = movie;
	} else {
		_WAmovies.push(movie);
	}

	_WAmovieCreateImgElements(movieId, movie);

	_WAmovie().start(movieId);
}

function WAmovieStop(movieId) {
	_WAmovie().stop(movieId);
}

function WAmovieContinue(movieId) {
	_WAmovie().cont(movieId);
}

