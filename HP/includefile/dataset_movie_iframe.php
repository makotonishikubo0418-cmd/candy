<?php
/*********************************************************************
* データセット(動画iframe用・統合版 PC/SP共通)
* 
* 2011-07-
*********************************************************************/

//-----  変数取得・設定  -----//

$mids     = ParamCharMasked($_GET["mids"]);   //店舗動画id
$midg     = ParamCharMasked($_GET["midg"]);   //キャスト動画id

//-----  処理  -----//

if($mids != ""){//店舗動画

	//動画情報取得
	$filedata = array();
	$QUERY  = "SELECT id,arch_id,filename,filetype FROM shop_movie_archive_file";
	$QUERY .= " WHERE club_id = '" . CLUBID . "'";
	$QUERY .= " AND status = 1";
	$QUERY .= " AND arch_id = '". $mids ."'";
	$QUERY .= " ORDER BY id DESC";
	$RESULT = $Database->Query($QUERY);
	$ROWS = $Database->Num_Rows($RESULT);
	if($ROWS != 0){
		while($row = $Database->Fetch_Array($RESULT)){
			$filedata[$row["filetype"]]       = $row["filename"];
		}
	}

	//店舗動画
	$movie_dir = IMG_HOME . CLUBID . '/' . UP_DIR_ARCHIVE;

}elseif($midg != ""){//キャスト動画

	$filedata = array();
	$QUERY  = "SELECT id,filetype,filename,girls_id,update_time FROM girls_movie_file";
	$QUERY .= " WHERE club_id = '" . CLUBID . "'";
	$QUERY .= " AND girls_id = '" . $midg . "'";
	$QUERY .= " AND status = '1'";
	$QUERY .= " ORDER BY id DESC";
	$RESULT = $Database->Query($QUERY);
	$ROWS = $Database->Num_Rows($RESULT);
	if($ROWS != 0){
		while($row = $Database->Fetch_Array($RESULT)){
			$filedata[$row["filetype"]]       = $row["filename"];
		}
	}

	//キャスト動画
	$movie_dir = IMG_HOME . CLUBID . '/' . UP_DIR_MOVIE;

} else {
	$filedata = array();
}

/*
* 独自タグから表示枠ソースを取得
*/
$source = file_get_contents($source_file);

//mp4
if(!empty($filedata[1])){
	$data1['01010310'] = $movie_dir . $filedata[1];
	$mvck = 1;
}else{
	$source = str_replace('<source src="rep01010310eot" type="video/mp4">', '', $source);
}
//ogv
if(!empty($filedata[2])){
	$data1['01010311'] = $movie_dir . $filedata[2];
	$mvck = 1;
}else{
	$source = str_replace('<source src="rep01010311eot" type="video/ogg">', '', $source);
}
//webm
if(!empty($filedata[3])){
	$data1['01010312'] = $movie_dir . $filedata[3];
	$mvck = 1;
}else{
	$source = str_replace('<source src="rep01010312eot" type="video/webm">', '', $source);
}
//jpg
if(!empty($filedata[4])){
	$data1['01010321'] = $movie_dir . $filedata[4];
	$mvck = 1;
}else{
	$source = str_replace(' poster="rep01010321eot"', '', $source);
}

?>
