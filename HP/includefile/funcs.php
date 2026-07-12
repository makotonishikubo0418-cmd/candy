<?
/*********************************************************************
* 共用関数ファイル(本店用)
* ※
* 2010-05-
*********************************************************************/

/*
* 待ち時間取得(10分単位)
*/
function GetWaitTime($time){
	
	$gtime = strtotime(date("Y/m/d H:i:s"));
	$ptime = strtotime(date("Y/m/d") .' '. $time);
	$daydiff1 = $ptime - $gtime;
	
	if($daydiff1 < 0){
		$ntime = 0;
	}else{
		$kari = floor($daydiff1 / 60);
		$ntime = intval(ceil($kari / 10) * 10); //切り上げ
	}
	
	return $ntime;
}


/*
* 経過時間変換
*/
function GetPostTime($date, $time){

	$gtime = strtotime(date("Y/m/d H:i:s"));
	$ptime = strtotime(str_replace('-', '/', $date) .' '. $time);
	$daydiff1 = $gtime - $ptime;

	if($daydiff1 < 60){ //sec
		$ntime = $daydiff1.'秒';
	}elseif($daydiff1 < 3600){ //minit
		$ntime = floor($daydiff1 / 60) . '分前';
	}elseif($daydiff1 < 86400){ //hour
		$ntime = floor($daydiff1 / 3600) . '時間前';
	}else{ //mm-dd
		//list($yy,$mm,$dd) = explode('-',$date);
		//$ntime = $mm.'月'.$dd.'日';
		
		if($daydiff1 >= 2592000){
			$ntime = '１ヶ月前';
		}elseif($daydiff1 >= 604800){
			$ntime = '１週間前';
			/*list($yy,$mm,$dd) = explode('-',$date);
			list($hh,$ii,$ss) = explode(':', $time);
			$ntime = "$yy.$mm/$dd $hh:$ii";*/
		}else{
			$ntime = floor($daydiff1 / 86400);
			
			list($yy,$mm,$dd) = explode('-',$date);
			list($hh,$ii,$ss) = explode(':', $time);
			
			$hi = "$hh:$ii";
			
			if($ntime == 1){
				$ntime = "昨日";
			}else{
				//
				/*$week = array(0=>'日',1=>'月',2=>'火',3=>'水',4=>'木',5=>'金',6=>'土');
				$w = date('w');
				$ww = $w - $ntime;
				if($ww < 0){ $ww += 6; }
				$ntime = $week[$ww] . "曜日";*/
				$ntime = '１週間';
			}
		}
	}

	return $ntime;
}


/*
* 日付取得
*/
function getDay($day){
    $now = time();
    return mktime(date("H",$now),date("i",$now),date("s",$now),date("m",$now),date("d",$now)+$day,date("Y",$now));
}


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

/*
* CAN-DIARY情報取得(API//JSON)
*/
function GetCandiaryapiData($fno, $param){
	
	if($fno != ""){
		$para = "";
		$para = '?fno=' . $fno . $param;
		$url = 'https://can-diary.com/api/get_diary_data.php' . $para;
		$json = my_file_get_contents($url);
		$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		$arr = json_decode($json,true);
	}else{
		$arr = array();
	}
	
	return $arr;
}


/*
* 転送先決定
* pms 11:PC,12:sp
* $trs 1:強制有効, 9:強制無効
*/
function SiteTrans($pms, $club_id, $trs, $Database){

	//セッティング情報取得
	$settdata = array();
	$QUERY  = "SELECT * FROM site_trans";
	$QUERY .= " WHERE club_id = '" . $club_id . "'";
	$QUERY .= " AND no = '". $pms ."'";
	$QUERY .= " ORDER BY id";
	$QUERY .= " LIMIT 1";
	$RESULT = $Database->Query($QUERY);
	$ROWS = $Database->Num_Rows($RESULT);
	if($ROWS != 0){
		while($row = $Database->Fetch_Array($RESULT)){
			//$settdata["id"][]         = $row["id"];
			//$settdata["club_id"][]    = $row["club_id"];
			//$settdata["no"][]         = $row["no"];
			$settdata["uri"]        = $row["uri"];
			$settdata["start_time"] = $row["start_time"];
			$settdata["end_time"]   = $row["end_time"];
			$settdata["status"]     = $row["status"];
		}
	}
	
	if(($settdata["status"] == 1 && $trs != 9) || ($trs == 1 && $settdata["uri"] != "")){ //有効
	
		$nowh = intval(date('G')); //現在時
	
		//またぎ調整
		if($settdata["start_time"] > $settdata["end_time"]){
			if($nowh < $settdata["end_time"]){ //24時以降
				$nowh+=24;
			}
			$settdata["end_time"]+=24;
		}
		
		//
		if(($settdata["start_time"] <= $nowh && $settdata["end_time"] > $nowh)){
			//
			if($settdata["uri"] != ""){
				$url = $settdata["uri"];
			}
			
			if($url != ""){
				//return $url;
				header("Cache-Control: no-cache, must-revalidate");
				header("HTTP/1.1 301 Moved Permanently" );
				header("Location: " . $url);
				die;
			}
	
		}
	
	}
}

?>