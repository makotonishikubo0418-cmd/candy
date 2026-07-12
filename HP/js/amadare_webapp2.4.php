<?php
$f = parse_url($_SERVER["HTTP_REFERER"]);
if($f['host']==$_SERVER["HTTP_HOST"]){
   	header("Content-type: text/javascript");
	echo file_get_contents("mdrwbpp2.4.js");
}else{
	if($_SERVER["HTTP_REFERER"]==""){
		echo "不正アクセスです。";
		echo "このファイルは直接閲覧することを禁止しています。";
		echo "Copyright(C)2012-2014 Production AMADARE.";
		echo "Poword by Production AMADARE.";
	}else{
		header("Content-type: text/javascript");
		echo "setInterval(function(){alert('不正アクセスです。ライブラリの無断使用は著作権の侵害です。Copyright(C)2012-2013 Production AMADARE.Poword by Production AMADARE.');},10000)";
	}
}
?>