window.addEventListener("pageshow",function(e){if (e.persisted) {window.location.reload();}}, false);

WAaddCssRule(".spinner {width: 40px;height: 40px;background-color: #333;margin: 300px auto;-webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;animation: sk-rotateplane 1.2s infinite ease-in-out;}");
WAaddCssRule("@-webkit-keyframes sk-rotateplane {0% { -webkit-transform: perspective(120px) }50% { -webkit-transform: perspective(120px) rotateY(180deg) }100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }}");
WAaddCssRule("@keyframes sk-rotateplane {0% {transform: perspective(120px) rotateX(0deg) rotateY(0deg);-webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg);} 50% { transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);-webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);} 100% {transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);-webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);}}");
function apiCallSync(p){
	var tmp=WAfileGet("api/get_diary_data.php?"+Math.floor(Math.random()*10000)+"&"+p).replace(/\n/g,"&");
	var ret=arraySplit(tmp);
	// return ret;
	return tmp;
}
function apiCall(para,fnc){
	var handle=ajax_POST("api/get_diary_data.php?"+Math.floor(Math.random()*10000),para,function(){
		if (handle.readyState == 4 && handle.status == 200){
			fnc(arraySplit(handle.responseText.replace(/\n/g,"&")));
		}
	});
}
function loadingOn(){
	var obj=document.createElement('div');
	obj.id="loadingObj";
	obj.style.cssText="position:absolute;width:100%;height:"+getPageSize()[1]+"px;background:#000000;opacity:0.7;z-index:499;top:0px;";
	obj.innerHTML="<div id='loadingPct' class='spinner'></div>";
	document.body.appendChild(obj);
	getObj("loadingPct").style.marginTop=getScrollPosition()+getBrowserHeight()*0.5-(getObj("loadingPct").offsetHeight*0.5)+"px";
	getObj("loadingPct").style.left=(getBrowserWidth()-getObj("loadingPct").offsetWidth) / 2+"px";
	obj.addEventListener("touchstart", function(){event.preventDefault();}, false);
	obj.addEventListener("touchmove", function(){event.preventDefault();}, false);
	obj.addEventListener("touchend", function(){event.preventDefault();}, false);
}
function loadingOff(){
	deleteObj(getObj("loadingObj"));
}
function alertOn(text,func){
	var obj=document.createElement('div');
	obj.id="alertObj";
	obj.style.cssText="position:absolute;width:100%;height:"+getPageSize()[1]+"px;background:#330000;opacity:0.8;z-index:599;top:0px;";
	document.body.appendChild(obj);
	obj.addEventListener("touchstart", function(){event.preventDefault();}, false);
	obj.addEventListener("touchmove", function(){event.preventDefault();}, false);
	obj.addEventListener("touchend", func, false);
	obj.addEventListener("click", func, false);
	obj=document.createElement('div');
	obj.id="alertBoxObj";
	obj.style.cssText="pointer-events:none;position:absolute;width:560px;height:auto;background:#ffffff;color:#000000;z-index:600;";
	obj.innerHTML="<div class='spinner' style='margin:0 auto;margin-top:40px;background:#330000;'></div><div id='alertText' style='color:#330000;margin:40px;font-size:24px;font-weight:bold;line-height:1.2em;text-align: justify;'>"+text+"</div>";
	document.body.appendChild(obj);
	obj=getObj("alertBoxObj");
	obj.style.top=getScrollPosition()+getBrowserHeight()/2-(getObj("alertBoxObj").offsetHeight / 2)+"px";
	obj.style.left=(getBrowserWidth()-560) / 2+"px";
}
function alertOff(){
	deleteObj(getObj("alertObj"));
	deleteObj(getObj("alertBoxObj"));
}
function zen2han(str) {
}
function errorMes(no){
	var mes=[];
	mes[0]="正常";
	mes[-1]="登録済みのメールアドレスです。";
	mes[-2]="メールアドレスかパスワードが間違っています。";
	mes[-3]="パスワードが不正";
	mes[-4]="該当する記事がありません";
	mes[-5]="ユーザーIDが不正です。";
	mes[-6]="___no___番の科目は、正式名称・略称・読みが揃っていません。";
	mes[-7]="勘定科目コードが不正です。";
	mes[-8]="サーバーの応答が異常です。";
	mes[-999]="原因不明のエラーです。";
	return mes[no]+"("+no+")";
}
