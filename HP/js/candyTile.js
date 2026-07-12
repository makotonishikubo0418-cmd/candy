// JavaScript Document
var xBoxCount;
var gBoxAtr=new Array();
// 女の子写真を早く表示するため、window "load" ではなく DOM 準備完了時点でタイル表示（6～8秒遅れ対策）
function runTileInit(){
	var el = getObj("tile");
	if(!el || typeof gBox === "undefined"){ return false; }
	if(!gBox.length){ return false; }
	var w=parseInt(document.body.clientWidth);
	if(w<=967){ w=967; }
	xBoxCount=getXBoxCount();
	el.style.width=(xBoxCount * 310 +(xBoxCount-1)*8)+"px";
	firstTile();
	tile();
	if(typeof WAimgLoadIni === "function"){ WAimgLoadIni(); }
	el.style.left=(w-(xBoxCount*318-8))/2+7+"px";
	window.addEventListener("resize",resizeEvent,0);
	return true;
}
function scheduleTileInit(){
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", function(){
			if(!runTileInit()){ jQuery(window).on("load", runTileInit); }
		});
	} else {
		if(!runTileInit()){ jQuery(window).on("load", runTileInit); }
	}
}
scheduleTileInit();
	
	
function firstTile(){
	var y=0,x=0,i;
	var c=0,p,c2=xBoxCount;
	gBoxAtr.length=0;
	var o=getObj("tile");
	if(o.getElementsByClassName("photo").length>0){
		for(i=0;i<o.getElementsByClassName("photo").length;i++){
			o.getElementsByClassName("photo")[i].removeEventListener("mouseover",photoMouseOver,false);
			o.getElementsByClassName("photo")[i].removeEventListener("mouseout",photoMouseOut,false);
		}
	}
	if(o.getElementsByClassName("video").length>0){
		for(i=0;i<o.getElementsByClassName("video").length;i++){
			o.getElementsByClassName("video")[i].removeEventListener("mouseover",videoMouseOver,false);
			o.getElementsByClassName("video")[i].removeEventListener("mouseout",videoMouseOut,false);
		}
	}
	o.innerHTML="";
/*	for (;o.childNodes.length>0;) {
		o.removeChild(o.childNodes[0]);
	}*/
	if (window.CollectGarbage) {
		window.CollectGarbage();
	}
	for(p=0;c<gBox.length;p++){
		y=Math.floor(p/xBoxCount);
		switch(y % 3){
			case 0:
				gBoxAtr.push("A");
				c++;
				break
			case 1:
				for(;c2!=0;){
					gBoxAtr.push("A");
					c2--;
					c++;
				}
				c2=xBoxCount;
				p=p+xBoxCount-1;
				break
			case 2:
				gBoxAtr.push("C");
				c++;
				p=p+xBoxCount-1;
			break;
			default:
			alert("error");
			break;
		}
	}
	for(c=0;c<gBox.length;c++){
		var html;
		var para=gBox[c].split("/__/");
		o=document.createElement('div');
		o.id="gBox"+c;
		o.className="box box"+gBoxAtr[c];
		html=tmplate[gBoxAtr[c]].innerHTML;
		html=html.replace("____name____",para[0]);
		html=html.replace("____age____",para[1]);
		html=html.replace("____height____",para[2]);
		html=html.replace("____brest____",para[3]);
		html=html.replace("____cup____",para[4]);
		html=html.replace("____waist____",para[5]);
		html=html.replace("____hip____",para[6]);
		html=html.replace("____link____",para[18]);
		switch(para[7]){
			case "TEL確認":
				html=html.replace("____state____",'<div class="tel">電話確認</div>');
				break;
			case "TEL確認+":
				html=html.replace("____state____",'<div class="tel add">電話確認</div>');
				break;
			case "CLOSED":
				html=html.replace("____state____",'<div class="close">CLOSED TODAY</div>');
				break;
			case "THANKS":
				html=html.replace("____state____",'<div class="thanks">案内終了</div>');
				break;
			default:
				if(para[7].slice(-1)!="+"){
					html=html.replace("____state____",'<div class="reserve">'+para[7]+'</div>');
				}else{
					html=html.replace("____state____",'<div class="reservea add">'+para[7].slice(0, -1)+'</div>');
				}
				break;
		}
		var tmp="";
		if(para[8]=="true" && para[7] !="CLOSED"){
			tmp='<div class="cssSprite topIconFav"></div>';
		}
		if(para[9]=="true"){
			tmp=tmp+'<div class="cssSprite topIconPhoto"></div>';
		}
		if(para[10]=="true"){
			tmp=tmp+'<div class="cssSprite topIconDiary"></div>';
		}
		html=html.replace("____icon____",tmp);
		
		
		tmp="";
		switch(para[11]){
			case "new":
				html=html.replace("____newtag____",'<div class="cssSprite girlsTagNew">NEW</div>');
			break
			case "test":
				html=html.replace("____newtag____",'<div class="cssSprite girlsTagTrial">体験</div>');
			break;
			default:
				html=html.replace("____newtag____","");
			break;
		}
		switch(gBoxAtr[c]){
			case "A":
				if(para[15]!="" && para[16]!="" && para[17]){
					html=html.split("<!--photo-->")[0]+html.split("<!--photoEnd-->")[1];
					html=html.replace("____video____","<source src='"+para[15]+"'><source src='"+para[16]+"'><source src='"+para[17]+"'>");
					html=html.replace("<video","<!--hideVideo style='-webkit-transition: opacity 0.4s;-moz-transition: opacity 0.4s;-ms-transition: opacity 0.4s;-o-transition: opacity 0.4s;' autoplay ").replace("</video>","hideVideo-->");
				}else{
					html=html.split("<!--video-->")[0]+html.split("<!--videoEnd-->")[1];
				}
				html=html.replace("____photo____",para[12]);
			break;
			case "B":
				html=html.replace("____photo____",para[13]);
			break;
			case "C":
				if(para[15]!="" && para[16]!="" && para[17]){
					html=html.split("<!--photo-->")[0]+html.split("<!--photoEnd-->")[1];
					html=html.replace("____video____","<source src='"+para[15]+"'><source src='"+para[16]+"'><source src='"+para[17]+"'>");
					html=html.replace("<video","<!--hideVideo style='-webkit-transition: opacity 0.4s;-moz-transition: opacity 0.4s;-ms-transition: opacity 0.4s;-o-transition: opacity 0.4s;' autoplay ").replace("</video>","hideVideo-->");
				}else{
					html=html.split("<!--video-->")[0]+html.split("<!--videoEnd-->")[1];
				}
				html=html.replace("____photo____",para[14]);
			break;
		}
/*		if(html.split("<video").length>1){
			html=html.split("<video")[0]+html.split("<video")[1].split("video>")[1];
		}
*/		
		
		o.innerHTML=html;
		o.style.position="absolute";
		getObj("tile").appendChild(o);


		if(o.getElementsByClassName("photo").length>0){
			o.getElementsByClassName("photo")[0].getElementsByTagName("img")[0].style.cssText="-webkit-transition: opacity 0.4s;-moz-transition: opacity 0.4s;-ms-transition: opacity 0.4s;-o-transition: opacity 0.4s;";
			o.getElementsByClassName("photo")[0].style.cssText="background:#ccc;";
			o.addEventListener("mouseover",photoMouseOver,false);
			o.addEventListener("mouseout",photoMouseOut,false);
		}
		if(o.innerHTML.match(/<!--hideVideo/)){
			o.addEventListener("mouseover",videoMouseOver,false);
			o.addEventListener("mouseout",videoMouseOut,false);
		}

		if(o.getElementsByClassName("movie").length>0){
			o.getElementsByClassName("movie")[0].style.cssText="background:#ccc;";
		}
	}
	
	o=null;
}
function photoMouseOver(ev){
	ev.currentTarget.getElementsByClassName("photo")[0].getElementsByTagName("img")[0].style.opacity=0.6;
	ev.currentTarget.getElementsByClassName("photo")[0].style.cssText="background:#000;";
}
function photoMouseOut(ev){
	ev.currentTarget.getElementsByClassName("photo")[0].getElementsByTagName("img")[0].style.opacity=1;
}
function videoMouseOver(ev){
	ev.currentTarget.getElementsByClassName("movie")[0].innerHTML=ev.currentTarget.getElementsByClassName("movie")[0].innerHTML.replace("<!--hideVideo","<video").replace("hideVideo-->","</video>");
	ev.currentTarget.getElementsByClassName("video")[0].style.opacity=1;
}
function videoMouseOut(ev){
	ev.currentTarget.getElementsByClassName("movie")[0].innerHTML=ev.currentTarget.getElementsByClassName("movie")[0].innerHTML.replace("<video","<!--hideVideo").replace("</video>","hideVideo-->");
	if (window.CollectGarbage) {
		window.CollectGarbage();
	}
}

function tile(){
	var nullLine="";
	var vScreen=new Array();
	var tmpCheight=Math.floor(720/1280*(xBoxCount*318-8));
	getObj("boxCtemplate").style.dislpay="none";
	for(var i=0;i<xBoxCount;i++){
		nullLine=nullLine+"0";
	}
	for(i=0;i<=gBox.length*3;i++){
		vScreen.push(nullLine);
	}
	for(var c=0;c<gBox.length;c++){
		switch(gBoxAtr[c]){
			case "A":
				for(i=0;i<vScreen.length;i++){
					if(i  % 3 !=2){
						var x=vScreen[i].indexOf("0");
						if(x>-1){
							vScreen[i]=rep(vScreen[i],x,"A");
							var t=0;
							for(var y=0;y<i;y++){
								if(y %3 ==2){
									t=t+tmpCheight;
								}else{
									t=t+174;
								}
							}
							t=t+i*8;
							getObj("gBox"+c).style.top=t+"px";
							var l=x*310;
							if(x>0){
								l=l+x*8;
							}
							getObj("gBox"+c).style.left=l+"px";
							break;
						}
					}
				}
				break
			case "B":
					for(i=0;i<vScreen.length;i++){
					if(i % 3 ==0){
						var x=vScreen[i].indexOf("0");
						if(x>-1 && vScreen[i+1].charAt(x)=="0"){
							vScreen[i]=rep(vScreen[i],x,"B");
							vScreen[i+1]=rep(vScreen[i+1],x,"B");
							var t=0;
							for(var y=0;y<i;y++){
								if(y %3 ==2){
									t=t+tmpCheight;
								}else{
									t=t+174;
								}
							}
							t=t+i*8;
							getObj("gBox"+c).style.top=t+"px";
							var l=x*310;
							if(x>0){
								l=l+x*8;
							}
							getObj("gBox"+c).style.left=l+"px";
							break;
						}
					}
				}
				break
			case "C":
					for(i=0;i<vScreen.length;i++){
					if(i % 3 ==2){
						if(vScreen[i]==nullLine){
							vScreen[i]=nullLine.replace(/0/g, "C");  
							var t=0;
							for(var y=0;y<i;y++){
								if(y %3 ==2){
									t=t+tmpCheight;
								}else{
									t=t+174;
								}
							}
							t=t+i*8;
							getObj("gBox"+c).style.top=t+"px";
							getObj("gBox"+c).style.left="0px";
							getObj("gBox"+c).style.height=tmpCheight+"px";
							break;
						}
					}
				}
				break
		}
	}
	for(;;){
		if(vScreen[vScreen.length-1]==nullLine){
			vScreen.pop();
		}else{
			break;
		}
	}
	getObj("tile").style.height=vScreen.length*174+(vScreen.length)*8+Math.floor(vScreen.length/3)*(tmpCheight-174)+"px";
	WAimgLoadAdd();
}
var rep = function(text,n,value){
　　return text.substr(0, n) + value + text.substr(n+1);
};
var tileResizeTimer;
function resizeEvent(){
	clearTimeout(tileResizeTimer);
	tileResizeTimer=setTimeout(function(){_resizeEvent();},500);
}
function _resizeEvent(){
	var w=parseInt(document.body.clientWidth);
	if(w<=967){
		w=967;
	}
	var tc=getXBoxCount();
	if(tc!=xBoxCount){
		xBoxCount=tc;
		getObj("tile").style.width=(xBoxCount * 310 +(xBoxCount-1)*8)+"px";
		
		firstTile();
		tile();
	}
	getObj("tile").style.left=(w-(xBoxCount*318-8))/2+7+"px";
}
function getXBoxCount(){
	var w=parseInt(document.body.clientWidth);
	if(w<=967){
		return 3;
	}else{
		w=w-14;
		return Math.floor((w+8)/318);
	}
}