/*
amadare web application development kit
amadare_wabapp.2.3.js
Copyright(C) 2015 PuroductionAMADARE allrights reserved.*/

/*******************************************
*
*		  環境変数
*
*******************************************/
var WAdomain = location.href.split('/')[2];
var AnimWait=50;
var WAandroid=WAandroidCheck();

/*******************************************
*		  セレクトボックス
*******************************************/
//WAselector('on','selector_open_sw','selector_design','selector_frame','data_frame',func,0,offset);
function WAselector(){
	if(arguments[0]=="on"){
		_WAselector.onstage(arguments[0],arguments[1],arguments[2],arguments[3],arguments[4],arguments[5],arguments[6],arguments[7]);
	}else{
		_WAselector.offstage();
	}
}
var _WAselector=function(){
	var obj,sObj,fObj,dObj;
	var mx,my,tx,ty,sx,sy;
	var aHandle,Ti;
	var func;
	var sourceList;
	var barRitsu;
	return{
		onstage :function(){
			func=arguments[5];
			sourceList=arguments[4];
			WAdialog("on",arguments[1],arguments[2],arguments[6],arguments[7]);
			dObj=getObj(arguments[2]);
			fObj=getObj(arguments[3]);
			fObj.style.overflow="hidden";
			fObj.style.position="relative";
			sObj=document.createElement('div');
			sObj.id="selectorData"+sourceList;
			sObj.innerHTML=getObj(sourceList).innerHTML;
			sObj.style.marginTop="0px";
			getObj(sourceList).innerHTML="";
			
			fObj.appendChild(sObj);
			
			barRitsu=fObj.offsetHeight/sObj.offsetHeight;
			obj=document.createElement('div');
			obj.id="selectorBar";
			obj.innerHTML="<div id='selectorBarNet' style='position:absolute;background-color:#000000;width:100%;right:0px;height:"+parseInt(fObj.offsetHeight*barRitsu)+"px;'></div>";
			with(obj.style){
				position="absolute";
				top="0px";
				right="0px";
				height=fObj.offsetHeight+"px";
				width="10px";
				backgroundColor="#555555";
				opacity="0";
			}
			fObj.appendChild(obj);

			
			fObj.addEventListener("touchstart",function(event){
				sx=event.touches[0].pageX;
				sy=event.touches[0].pageY;
				mx=sx;
				my=sy;
				getObj("selectorBar").style.opacity="0.5";
				getObj("selectorBarNet").style.top=parseInt(-1*barRitsu*parseInt(sObj.style.marginTop))+"px";
				event.preventDefault();
			},false);
			fObj.addEventListener("touchmove",function(event){
				ty=parseInt(sObj.style.marginTop)-(my-event.touches[0].pageY);
				if(ty>0){
					ty=0;
				}else if(fObj.offsetHeight>sObj.offsetHeight+ty){
					ty=(sObj.offsetHeight-fObj.offsetHeight)*-1;
				}
				if(sObj.offsetHeight>fObj.offsetHeight){
					sObj.style.marginTop=ty+"px";
				}
				getObj("selectorBarNet").style.top=parseInt(-1*barRitsu*parseInt(sObj.style.marginTop))+"px";
				my=event.touches[0].pageY;
				event.preventDefault();
			},false);
			fObj.addEventListener("touchend",function(){
				event.preventDefault();
				ty=parseInt(sObj.style.marginTop)*-1+sy-xy(fObj)[0];
				if(sy==my && ty<sObj.offsetHeight){
					func(ty);
				}
				getObj("selectorBar").style.opacity="0";
			},false);
		},
		offstage:function(){
			WAdialog('off');
			deleteObj(sObj);
		}
	};
}();
/*******************************************
*		  画像を必要な分だけ読み込む(準備)
*******************************************/
var varWAimg=new Array();
var varWAimgOpa=new Array();
	document.write("<style>");
	document.write(".WACSSfadeIn{");
	document.write("-webkit-animation: WACSSanime1 1s linear 1;");
	document.write("-moz-animation: WACSSanime1 1s linear 1;");
	document.write("-o-animation: WACSSanime1 1s linear 1;");
	document.write("animation:WACSSanime1 1s linear 1;}");
	document.write("@keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	document.write("@-o-keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	document.write("@-webkit-keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	document.write("@-moz-keyframes WACSSanime1 { 0% {opacity: 0;} 100% {opacity: 1;}}");
	document.write("}</style>");
function WAimgLoadIni(){
	WAimgLoadAdd();
	window.addEventListener("scroll",_WAimgLoad,0);
}
var _WAimgLoadTimer;
function _WAimgLoad(){
	clearTimeout(_WAimgLoadTimer);
	// スマホでの読み込み開始を早めるため、タイマーを短く（100ms）
	_WAimgLoadTimer=setTimeout(function(){__WAimgLoad();},100);
}
function __WAimgLoad(){
	var tmp=new Array();
	var h,t,sc,height;
	var priorityImgs=new Array();
	if(varWAimg.length >0){
		sc=getScrollPosition();
		var bh=getBrowserHeight();
		var bw=getBrowserWidth();
		// PCとスマホで異なる事前読み込み範囲を設定
		// PCの方が画面が大きいので、より広い範囲で事前読み込み
		var preloadTop, preloadBottom;
		if(bw > 967){ // PC版
			preloadTop=sc-1000; // 上方向に1000px余裕
			preloadBottom=sc+bh+2000; // 下方向に2000px余裕
		}else{ // スマホ版
			preloadTop=sc-500; // 上方向に500px余裕
			preloadBottom=sc+bh+1000; // 下方向に1000px余裕
		}
		h=sc+bh;
		// 優先読み込み画像（fetchpriority="high"）を先に処理
		for(var i=0;i<varWAimg.length;i++){
			if(varWAimg[i].getAttribute("fetchpriority") === "high"){
				priorityImgs.push(varWAimg[i]);
			}
		}
		// 優先画像を即座に読み込み
		for(var j=0;j<priorityImgs.length;j++){
			addClassName(priorityImgs[j],"WACSSfadeIn");
			priorityImgs[j].style.opacity=1;
			priorityImgs[j].alt="";
			priorityImgs[j].src=priorityImgs[j].title;
		}
		// 通常の画像読み込み処理
		for(var i=0;i<varWAimg.length;i++){
			// 優先画像はスキップ
			if(varWAimg[i].getAttribute("fetchpriority") === "high"){
				continue;
			}
			height=parseInt(varWAimg[i].offsetHeight);
			t=xy(varWAimg[i])[0];
			// ビューポートの拡張範囲内に入った画像を読み込む
			if(t<preloadBottom && t+height>preloadTop){
				addClassName(varWAimg[i],"WACSSfadeIn");
//				varWAimg[i].addEventListener('load',function(){
//					this.style["opacity"]=1;
//				});
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
	var priorityCount=0;
	var maxPriority=9; // 最初の9枚の画像を優先読み込み
	for(var i=0;i<tmp.length;i++){
		// nolazyクラスを持つ画像は遅延読み込みの対象から除外
		if(tmp[i].className && tmp[i].className.indexOf('nolazy') !== -1){
			continue;
		}
		if(tmp[i].alt.length>0){
			tmp[i].title=tmp[i].alt;
			// 最初に表示される画像（最初の数枚）に優先読み込みを設定
			// ただし、既にfetchpriorityが設定されていない場合のみ
			if(priorityCount < maxPriority && !tmp[i].getAttribute("fetchpriority")){
				tmp[i].setAttribute("fetchpriority", "high");
				priorityCount++;
			}else if(!tmp[i].getAttribute("loading") && !tmp[i].getAttribute("fetchpriority")){
				// ビューポート外の画像には遅延読み込みを設定
				tmp[i].setAttribute("loading", "lazy");
			}
			varWAimg.push(tmp[i]);
		}
	}
	// 初回読み込み時は即座にチェック（タイマーを使わない）
	__WAimgLoad();
}
/*******************************************
*		  CSSを交互に変更
*******************************************/
function WAcssToggle(id,stylep,css1,css2){
	var objs=getObj(id).style;
	if(objs[stylep]==css1){
		objs[stylep]=css2;
	}else if(objs[stylep]==css2){
		objs[stylep]=css1;
	}
}
/*******************************************
*		  クラス変更
*******************************************/
function WAclass(ids,cName){
	var p=new Array();
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
*		  テキストの入力があるまでボタンを薄く
*******************************************/
function WAinputActive(TextObjId,swObjId){
	var obj=new Array();
	var f=true;
	var i,l;
	obj=TextObjId.split(",");
	for(i=0,l=obj.length;i<l;i++){
		if(getObj(obj[i]).value.length==0){
			f=false;
		}
	}
	obj=swObjId.split(",");
	for(i=0,l=obj.length;i<l;i++){
		getObj(obj[i]).style.opacity=(f)? "1":"0.5";
	}
}

/*******************************************
*		  スクロールする
*******************************************/
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
*		  テキストファイルを読み込む
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
*		  マスク
*******************************************/
mask_timer=null;
mask_handle=null;
function WAmask(){
	var obj=getObj("maskObj");
	switch(arguments[0]){
		case "on":
		case "ON":
			if(obj==null){
				obj=document.createElement('div');
				obj.id="maskObj";
				obj.style.cssText="position:absolute;width:100%;height:"+getPageSize()[1]+"px;background:"+arguments[1]+";opacity:0;zIndex:399;top:0px;transition:all 1s ease-out;";
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
*		  エレメントのをフラッシュ
*******************************************/
function _WAflash(parent, color) {
	var obj = document.createElement('div');
	var tid;
	obj.id = _WAgenerateId();
	obj.style.zIndex = 401;
	obj.style.top = xy(parent)[0] + 'px';
	obj.style.left = xy(parent)[1] + 'px';
	obj.style.width = parent.offsetWidth + 'px';
	obj.style.height = parent.offsetHeight + 'px';
	obj.style.color = color;
	obj.style.backgroundColor = color;
	obj.style.position = 'absolute'
	document.body.appendChild(obj);

	var handle = anim(64, 0);
	return {
		setInterval:function(cb) {
			tid = setInterval(cb, AnimWait);
		},
		callback:function() {
			var opacity = _anim(handle);
			if (opacity != null) {
				obj.style.opacity = opacity / 64;
			} else {
				clearInterval(tid);
				delete_obj(obj);
			}
		}
	}
}

function WAflash(id, color) {
	var obj = getObj(id);
	var flash = _WAflash(obj, color);

	flash.callback();
	flash.setInterval(flash.callback);
}

function _WAgenerateId() {
	do {
		var id = (new Date).getTime();
	} while (document.getElementById(id) != null);

	return id;
}
/*******************************************
*		  エレメントの横移動
*******************************************/
function WAmoveH(id,st,ed){
    _WAmove.entry(id,st,ed,"margin-left");
}
var _WAmove=function(){
    var obj=new Object();
    var aHandle=new Object();
    var tanni=new Object();
    var ti=null;
    
    return{
        entry:function(id,st,ed,sp){
            var uid=id+"%"+sp;
            obj[uid]=getObj(id);
            aHandle[uid]=anim(st,ed);
            tanni[uid]=numtanni(st).tanni;
            if(ti==null){
                ti=setInterval(_WAmove.timer,AnimWait);
            }
        },
        timer:function(){
            var c=0;
            var p;
            for(var uid in aHandle){
                c++;
                p=_anim(aHandle[uid]);
                if(p==null){
                    c--;
                    delete obj[uid];
                    delete aHandle[uid];
                    delete tanni[uid];
                }else{
                    obj[uid].style.setProperty([uid.split("%")[1]],p+tanni[uid]);
                }
            }
            if(c==0){
                clearInterval(ti);
                ti=null;
            }
        }
    };
}();
/*******************************************
*		  エレメントの縦移動
*******************************************/
function WAmoveV(id,st,ed){
    _WAmove.entry(id,st,ed,"margin-top");
}
/*******************************************
*		  下から閉じる
*******************************************/
function _WAcloseUp(obj) {
	var tid;
	var handle = anim(obj.offsetHeight, 0);
	return {
		setInterval:function(cb) {
			tid = setInterval(cb, AnimWait);
		},
		callback:function() {
			var height = _anim(handle);
			if (height != null) {
				obj.style.height = height + 'px';
			} else {
				clearInterval(tid);
			}
		}
	}
}

function WAcloseUp(id) {
	var obj = getObj(id);
	obj.style.overflow = 'hidden';
	var closeUp = _WAcloseUp(obj);

	closeUp.callback();
	closeUp.setInterval(closeUp.callback);
}

/*******************************************
*		  下へ開ける
*******************************************/
function _WAopenDown(obj, pxls) {
	var handle = anim(0, pxls);
	var tid;
	return {
		setInterval:function(cb) {
			tid = setInterval(cb, AnimWait);
		},
		callback:function() {
			var height = _anim(handle);
			if (height != null) {
				obj.style.height = height + 'px';
			} else {
				obj.style.overflow = '';
				clearInterval(tid);
			}
		}
	}
}

function WAopenDown(id, pxls) {
	var obj = getObj(id);
	obj.style.overflow = 'hidden';
	var openDown = _WAopenDown(obj, pxls);

	openDown.callback();
	openDown.setInterval(openDown.callback);
}

/*******************************************
*		  APIからの戻り値をHTMLに埋め込む
*******************************************/
function WAincludeApi(req,incfmt,url){
	var api_incfmt=array_split(incfmt);
	
	var handle=ajax_POST(url,req,function(){
		var p,obj;
		if (handle.readyState == 4 && handle.status == 200){
			p=array_split(handle.responseText);
			for (var key in api_incfmt){
				obj=getObj(key);
				if(obj !=null){
					obj.innerHTML=p[api_incfmt[key]];
				}
			}
		}
	});
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
*		  ダイアログを表示非表示
*******************************************/
var dialog_obj=null;
function WAdialog(){
var obj,html,ty,sy,y,func;
	switch(arguments[0]){
		case "on":
		case "ON":
			if(dialog_obj==null){
				WAdontScroll.start();
				dialog_obj=document.getElementById(arguments[2]);
				html=dialog_obj.innerHTML;
				func=dialog_obj.func;
				deleteObj(dialog_obj);
		
				dialog_obj=document.createElement('div');
				obj=dialog_obj;
				obj.id=arguments[2];		
				obj.innerHTML=html;
				obj.style.position="absolute";
				obj.style.zIndex="400";
				obj.func=func;
				document.body.appendChild(obj);
		
				if(arguments[3]==1){
					ty=getScrollPosition()+arguments[4];
				}else{
					ty=document.getElementById(arguments[1]).offsetHeight+xy(document.getElementById(arguments[1]))[0];
				}
				sy=0-obj.offsetHeight;
				obj.style["top"]=sy+"px";
				obj.style["left"]=parseInt((getBrowserWidth()-obj.offsetWidth)/2)+"px";
				obj.style["-webkit-transform"]="translate3d(0,0,0)";
				obj.style["-webkit-transition"]="top 0.5s ease-out 0";
				obj.style["top"]=ty+"px";
			}
			break;
		case "off":
		case "OFF":
			if(dialog_obj !=null){
				dialog_obj.style.display="none";
				dialog_obj=null;
				WAdontScroll.stop();
			}
			break;
	}
}
var WAdontScroll=function(){
	var scPos;
	return{
		start:function(pos){
			scPos=getScrollPosition();
			window.addEventListener('scroll', WAdontScroll.move, false);
		},
		stop:function(){
			window.removeEventListener('scroll', WAdontScroll.move, false);
		},
		move:function(){
			window.scrollTo(0,scPos);
		}
	}
}();

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
*	  文字列を分割して連想配列で返す
*******************************************/
function array_split(s){
	var r=new Array();
	var n=new Array();
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
*
*		  連想配列内に要素があるか調べる
*
*******************************************/
function array_key_exists ( key, search ) {
	if( !search || (search.constructor !== Array && search.constructor !== Object) ){
		return false;
	}
	return key in search;
}
/*******************************************
*		  数字と単位に分ける
*******************************************/
function numtanni(str) {
  var tmp = /(\D*\d+)(\D+)/.exec(str);
  return {
	num: tmp[1],
	tanni: tmp[2]
  }
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
*		  機種チェック
*******************************************/
function WAandroidCheck(){
    var agent = navigator.userAgent;
    if(agent.search(/iPhone/) != -1 || agent.search(/iPad/) != -1){
        return false;
    }else{
        return true;
    }
}
/*******************************************
*		  BIOS
*******************************************/
anim_handle_st=new Array();
anim_handle_ed=new Array();
anim_handle_now=new Array();
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
function get_obj(id){
	return window.document.getElementById(id);
}
/*エレメントを削除*/
function deleteObj(obj){
		obj.parentNode.removeChild(obj);
}
function delete_obj(obj){
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
  var tmp_hash = new Array();
  var new_class_names = new Array();
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
	var handle;
	try{
		if (window.XMLHttpRequest){
			handle = new XMLHttpRequest();
		}else{
			if (window.ActiveXObject){
				handle = new ActiveXObject("Microsoft.XMLHTTP");
			}else{
				handle = null;
			}
		}
		handle.onreadystatechange = ok_func;
		handle.open("POST", url, true);
		handle.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		handle.send(param+"&time="+(new Date).getTime());
		return handle;
	//--a  m  a  d  a  r  e
	}catch(e){
	}
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
	<!--a m a d a r e-->
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
*	javascript外部ファイル読み込み
************************************************/
function loadScript(path){
	var sc = document.createElement('sc'+'ript');
	sc.src = path;
	document.body.appendChild(sc);
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

	var frames = new Array();
	for (var i = 0; i < f.length; i++) {
		var frame = new Array();
		frame.count = f[i].split('&')[0];
		frame.base64 = f[i].split('&')[1];
		frame.img = null;
		frames[i] = frame;
	}
	return frames;
}

function _WAmovieCreateMovie(movieId, data) {
	var movie = new Array();

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

var _WAmovies = new Array();

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

