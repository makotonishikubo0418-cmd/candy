var websocket=null;
var ua=navigator.userAgent;
var accessId=CookieRead("accessId");
if(accessId==""){accessId=Math.random().toString(36).slice(-8);CookieWrite("accessId", accessId,365);}

window.addEventListener("pageshow",function(e){
	if (e.persisted) {
		window.location.reload();
  	}else{
		amadareAccess();
	}
}, false);
function amadareAccess(){
	if (window["WebSocket"]) {
		websocket = new WebSocket("wss://amadare.me/access/9005/"); 
		websocket.onopen = function(ev) {
			websocket.send(JSON.stringify({"name": location.href.replace("http://"+location.hostname,"").split("&PHPSESSID")[0].split("?PHPSESSID")[0],"accessId": accessId,"ua":ua,"screen":screen.width+"X"+screen.height,referrer:encodeURIComponent(document.referrer)}));
		}
		websocket.onerror= function(ev){serverRestart();}; 
		websocket.onclose= function(ev){}; 
	}else{
		var path="/pc/js/amadareAccessServer.php?name="+location.href.replace("http://"+location.hostname,"").split("&PHPSESSID")[0].split("?PHPSESSID")[0]+"&accessId="+accessId+"&screen="+screen.width+"X"+screen.height+"&referrer="+encodeURIComponent(document.referrer);
		var o=document.createElement("img");
		o.src=path;
		document.body.appendChild(o);
	}
}
function amadareAcHook(adrs){
		location.href="https://amadare.me/acc/9005/js/amadareAccessServer.php?mode=hook&name="+adrs.replace("http://"+location.hostname,"")+"&accessId="+accessId+"&href="+adrs+"&screen="+screen.width+"X"+screen.height+"&referrer="+encodeURIComponent(document.referrer);
}
function amadareAcRec(tel){
	var path="https://amadare.me/acc/9005/js/amadareAccessServer.php?mode=rec&name=tel:"+tel+"&accessId="+accessId+"&screen="+screen.width+"X"+screen.height+"&referrer="+encodeURIComponent(document.referrer);
	var o=document.createElement("img");
	o.src=path;
	document.body.appendChild(o);
}
function serverRestart(){
	// WebSocket接続エラー時の処理
	// 画像タグによるアクセス情報送信は行わない（不要なDOM要素の生成を避けるため）
	// fetch APIを使用してエラー情報を送信（DOM要素を追加しない）
	var path="https://amadare.me/acc/9005/js/amadareAccessServer.php?mode=restart";
	if (window.fetch) {
		// fetch APIが使用可能な場合
		fetch(path, {
			method: 'GET',
			mode: 'no-cors',
			cache: 'no-cache'
		}).catch(function(error) {
			// エラーログは出力しない（サイレントに失敗を許容）
		});
	} else if (navigator.sendBeacon) {
		// sendBeacon APIが使用可能な場合（ページ遷移時でも送信可能）
		navigator.sendBeacon(path);
	} else {
		// 両方とも使用できない場合は、非表示の画像タグを使用（最小限の影響）
		var o=document.createElement("img");
		o.src=path;
		o.style.display='none';
		o.style.width='1px';
		o.style.height='1px';
		o.style.position='absolute';
		o.style.left='-9999px';
		document.body.appendChild(o);
	}
}
