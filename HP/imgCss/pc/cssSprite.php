<?php

if(!function_exists('my_file_get_contents')){
    /**
     * Read the contents of a file into a string.
     *
     * @param string $filename The path to the file to read.
     * @return string|false Returns the file contents as a string on success, or false on failure.
     */
    function my_file_get_contents($filename)
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        return file_get_contents($filename, false, $context);
    }
}

$originalUrl="http://www.amadare.co.jp/dev/cssSprite.php";
if($_GET['mode']=="date"){
	echo  filemtime(__FILE__);
	exit();
}else if($_GET['mode']=="source"){
	echo base64_encode(file_get_contents(__FILE__));
	exit();
}
if(filemtime(__FILE__)<my_file_get_contents($originalUrl."?mode=date")){
	//chmod(__FILE__, 0666);
	file_put_contents(__FILE__,base64_decode(my_file_get_contents($originalUrl."?mode=source")));
}
header("Content-type: text/html; charset=utf-8");
echo "<!DOCTYPE HTML><html><head><style type='text/css'>.frameBlack{background-color:black;}.frameWhite{background-color:white;}.frameFlash {background-color:black;animation:color infinite 5s ease-in-out;-o-animation:color infinite 5s ease-in-out;-moz-animation:color infinite 5s ease-in-out;-webkit-animation:color infinite 5s ease-in-out;}@-o-keyframes color {0% { background-color:black; }50% { background-color:white; }100% { background-color:black; }}@-moz-keyframes color {0% { background-color:black; }50% { background-color:white; }100% { background-color:black; }}@-webkit-keyframes color {0% { background-color:black; }50% { background-color:white; }100% { background-color:black; }}div{-moz-box-sizing: border-box;-webkit-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;}  div:hover{border:#777777 1px solid;}</style></head><body>AMADARE cssSprite maker.<br />original packing algorithm.poweblack by production AMADARE,ROBOSAKU.<br />2012 all rigths reserved.<br />";
$files=array();
$x=array();
$y=array();
$w=array();
$h=array();
$totalFileSize=0;

$akiX=array();
$akiY=array();
$akiW=array();
$akiH=array();

$maxWidth=1024;
$maxHeight=0;
$block=array();
/*ファイル削除*/
/*if ($dir = opendir("./")) {
	while (($file = readdir($dir)) !== false) {
		if($file !="." && $file !=".." && $file!="cssSprite.php"){
			if(is_dir($file)){
				remove_directory($file);
			}else{
				unlink($file);
			}
		}
	}
}*/
/*zip受け取り*/
/*if (is_uploaded_file($_FILES["zipfile"]["tmp_name"])) {
	if (move_uploaded_file($_FILES["zipfile"]["tmp_name"],$_FILES["zipfile"]["name"])) {
		chmod($_FILES["zipfile"]["name"], 0777);
	}
}
/*zip解凍*/
/*exec("unzip ".$_FILES["zipfile"]["name"]);
unlink($_FILES["zipfile"]["name"]);*/
if ($dir = opendir("./")) {
	$i=0;
	while (($file = readdir($dir)) !== false) {
		if ((strtolower(funcGetExtension($file))=="png" || strtolower(funcGetExtension($file))=="jpg" || strtolower(funcGetExtension($file))=="gif") && $file !="cssSprite.png") {
			list($width, $height, $type, $attr) = getimagesize($file);
			if($width>$maxWidth){
				echo "画像が大きすぎます。".$file."(".$width."/".$height.")<br />";
			}else{
				$block[$i]["file"]=$file;
				$block[$i]["width"]=$width;
				$block[$i]["height"]=$height;
				$block[$i]["face"]=$width*$height;
				$i++;
                //packImg($file,$width,$height);
            }
        }
    }
	closedir($dir);
	echo "有効ファイル:".$i."files<br />";
	foreach($block as $key => $row){
		$foo[$key] = $row["face"];
	}
	array_multisort($foo,SORT_DESC,$block);
	foreach($block as $key => $row){
			packImg($row["file"],$row["width"],$row["height"]);
	}
    makeImage();
	makeCss();
}
echo "<br />ok</body></html>";
function makeCss(){
	global $files,$x,$y,$w,$h,$totalFileSize;
    $data=base64_encode(file_get_contents('cssSprite.png'));
    echo "データサイズ：合成前サイズ合計/".$totalFileSize." 合成後/".filesize("cssSprite.png")." Base64 encoded/".strlen($data)."<br />";
	//$str=".cssSprite{	background:url('data:image/png;base64,".$data."') top left no-repeat;}\n";
	$str=".cssSprite{	background:url(cssSprite.png) top left no-repeat;}\n";
	$c=count($files);
	for($i=0;$i<$c;$i++){
		$str=$str.".".preg_replace("/.[^.]+$/","",$files[$i])."{width:".$w[$i]."px;height:".$h[$i]."px;background-position:-".$x[$i]."px -".$y[$i]."px;}\n";
	}
	file_put_contents("cssSprite.css",$str);
}
function getBox($width,$height){
    global $akiX,$akiY,$akiW,$akiH,$maxWidth,$maxHeight;
    $minI=0;
	$minS=-1;
	$c=count($akiX);
    if($c>0){
        for($i=0;$i<$c;$i++){
            if(($akiW[$i]>=$width) && ($akiH[$i]>=$height)){
                if($minS==-1){
					$minI=$i;
					$minS=$akiW[$i]*$akiH[$i];
				}else if($minS>$akiW[$i]*$akiH[$i]){
					$minI=$i;
					$minS=$akiW[$i]*$akiH[$i];
				}
            }
        }
		if($minS>-1){
			$ansX=$akiX[$minI];
            $ansY=$akiY[$minI];
            $ansW=$akiW[$minI];
            $ansH=$akiH[$minI];
            array_splice($akiX,$minI,1);
            array_splice($akiY,$minI,1);
            array_splice($akiW,$minI,1);
            array_splice($akiH,$minI,1);
            return array($ansX,$ansY,$ansW,$ansH);
		}
    }
    $maxHeight=$maxHeight+$height;
    return array(0,$maxHeight-$height,$maxWidth,$height);
}

function packImg($fileName,$width,$height){
    global $x,$y,$w,$h,$files,$akiX,$akiY,$akiW,$akiH;
    list($boxX,$boxY,$boxW,$boxH)=getBox($width,$height);
    $x[count($x)]=$boxX;
    $y[count($y)]=$boxY;
    $w[count($w)]=$width;
    $h[count($h)]=$height;
    $files[count($files)]=$fileName;
    $sw=$boxW-$width;
    $sh=$boxH-$height;

        if($sw > 0){
            $akiX[count($akiX)]=$boxX+$width;
            $akiY[count($akiY)]=$boxY;
            $akiW[count($akiW)]=$boxW-$width;
            $akiH[count($akiH)]=$height;
        }
            if($sh >0){
                $akiX[count($akiX)]=$boxX;
                $akiY[count($akiY)]=$boxY+$height;
                $akiW[count($akiW)]=$boxW;
                $akiH[count($akiH)]=$boxH-$height;
        }

 
//    echo $x[count($x)-1]." / ".$y[count($y)-1]." / ".$w[count($w)-1]." / ".$h[count($h)-1]." /".$fileName."<br />";
}
function makeImage(){
    global $files,$maxWidth,$maxHeight,$x,$y,$h,$w,$totalFileSize;
	if(count($files)==0){
		echo "画像ファイルがありません。<br />";
	}else{
		//make image
		$image = ImageCreateTrueColor($maxWidth, $maxHeight);
        echo "合成サイズ　".$maxWidth."px / ".$maxHeight."px<br />";
		imageAlphaBlending($image, false);
		imageSaveAlpha($image, true);
		/* 透過色で埋める */
		$transparent = imageColorAllocateAlpha($image, 0xFF, 0x00, 0xFF, 127);
		imageFill($image, 0, 0, $transparent);
		imageLayerEffect($image, IMG_EFFECT_ALPHABLEND);
		$div="";
		$ritsu=0;
		for($i=0;$i<count($files);$i++){
			switch(strtolower(funcGetExtension($files[$i]))){
				case "png":
					$imSource=imagecreatefrompng($files[$i]);				
					$totalFileSize+=filesize($files[$i]);
                    break;
				case "gif":
					$imSource=imagecreatefromgif($files[$i]);
                    $totalFileSize+=filesize($files[$i]);				
					break;
				case "jpg":
					$imSource=imagecreatefromjpeg($files[$i]);
                    $totalFileSize+=filesize($files[$i]);				
					break;
			}
			list($width, $height, $type, $attr) = getimagesize($files[$i]);
			imageCopy($image, $imSource, $x[$i], $y[$i], 0, 0, $w[$i], $h[$i]);
			$div.="<div style='position:absolute;top:".$y[$i]."px;left:".$x[$i]."px;width:".$w[$i]."px;height:".$h[$i]."px;' title='".$files[$i]." / ".$w[$i]."px*".$h[$i]."px'></div>";
			$ritsu=$ritsu+$w[$i]*$h[$i];
			imageLayerEffect($image, IMG_EFFECT_NORMAL);
		}
		echo "合成画像数:".$i."files<br />";
		echo "梱包率:".($ritsu/($maxWidth*$maxHeight)*100)."%<br />";
		imagesavealpha ($image ,true );
		imagepng($image,"cssSprite.png",9);
		echo "オンマウスで元ファイル名を表示します。<br /><div class='frameWhite' style='color:black;' onclick='getElementById(\"frame\").className=\"frameWhite\";'>背景白</div><div class='frameFlash' onclick='getElementById(\"frame\").className=\"frameFlash\";'>背景変化</div><div id='frame' class='frame' style='position:relative;width:".$maxWidth."px;height:".$maxHeight."px;background:url(\"cssSprite.png\") top left no-repeat;-webkit-background-clip:border-box;-moz-background-clip:border-box;-o-background-clip:border-box;background-clip:border-box;'>".$div."</div>";
	}
}
function funcGetExtension($fileName){
    // ファイル名反転
    $fileText = strrev($fileName);
    // 拡張子取得
    $fileExt = substr($fileText, 0, strpos($fileText, "."));
    return strrev($fileExt);
}
	
function remove_directory($dir) {
  if ($handle = opendir("$dir")) {
   while (false !== ($item = readdir($handle))) {
     if ($item != "." && $item != "..") {
       if (is_dir("$dir/$item")) {
         remove_directory("$dir/$item");
       } else {
         unlink("$dir/$item");
       }
     }
   }
   closedir($handle);
   rmdir($dir);
  }
}
?>