<?
/*********************************************************************
* データセット(統合版 - PC/SP対応)
 * 
* 2011-07-
 *********************************************************************/



//-----  変数取得・設定  -----//

// 画像表示制限の定数
define('MAX_HORIZONTAL_IMAGES', 1);  // 横向き画像最大枚数（mainImgでのみ使用）
define('MIN_VERTICAL_IMAGES', 2);    // 縦向き画像最小枚数
define('MAX_VERTICAL_IMAGES', 10);   // 縦向き画像最大枚数
define('DAMMY_IMG_SQ_h', 'dmy_h.jpg'); // 縦向きダミー画像


// テスト環境のアップロードベース
if(!defined('TEST_UPLOAD_BASE_URL')){
	$controlBase = URL_HOME; // 例: http://firststar.kir.jp/group_test/control/
	$siteBase = preg_replace('#control/$#', '', $controlBase);
	define('TEST_UPLOAD_BASE_URL', $siteBase . 'upfiles/');
	define('TEST_RESIZE_BASE_URL', $controlBase . 'site/');
	define('TEST_UPLOAD_BASE_DIR', rtrim(UP_DIR, '/').'/'); // 例: /home/.../upfiles/
}
if(!defined('USE_TEST_UPLOADS')){
	// テスト環境（firststar.kir.jp）のみ true。本番（55810.com）では IMG_HOME を使い girls_list と同一URLにする
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	define('USE_TEST_UPLOADS', (strpos($host, 'firststar.kir.jp') !== false));
}

function mapImageTypeToDir($imgtype){
	switch($imgtype){
		case 'h':
			return UP_DIR_H;
		case 'w':
		case 'li':
		case 'list':
			return UP_DIR_W;
		case 'icon':
			return UP_DIR_ICON;
		default:
			return '';
	}
}

function mediaFileExistsLocal($clubId, $dir, $filename){
	if($filename === '' || $dir === ''){
		return false;
	}
	$path = TEST_UPLOAD_BASE_DIR . $clubId . '/' . $dir . $filename;
	return file_exists($path);
}

function buildStaticImageUrl($clubId, $dir, $filename){
	if($filename === ''){
		return '';
	}
	if(USE_TEST_UPLOADS && mediaFileExistsLocal($clubId, $dir, $filename)){
		return TEST_UPLOAD_BASE_URL . $clubId . '/' . $dir . $filename;
	}
	return IMG_HOME . $clubId . '/' . $dir . $filename;
}

function buildResizeImageUrl($clubId, $imgfile, $imgsize, $imgtype, $extra = array()){
	global $debug_log;
	if($imgfile === ''){
		$debug_log .= "buildResizeImageUrl: imgfileが空のため空文字を返します\n";
		return '';
	}
	$params = array_merge(array(
		'club' => $clubId,
		'j'    => $imgfile,
		'size' => $imgsize,
		'type' => $imgtype
	), $extra);
	$query = http_build_query($params);
	$dir = mapImageTypeToDir($imgtype);
	$debug_log .= "buildResizeImageUrl: clubId=$clubId, imgfile=$imgfile, imgsize=$imgsize, imgtype=$imgtype, dir=$dir\n";
	if(USE_TEST_UPLOADS && $dir !== '' && mediaFileExistsLocal($clubId, $dir, $imgfile)){
		$url = TEST_RESIZE_BASE_URL . 'resizeimg.php?' . $query;
		$debug_log .= "buildResizeImageUrl: テスト環境URL生成: $url\n";
		return $url;
	}
	$url = IMG_HOME . 'resizeimg.php?' . $query;
	$debug_log .= "buildResizeImageUrl: 本番環境URL生成: $url\n";
	return $url;
}

function buildMovieUrl($clubId, $filename){
	if($filename === ''){
		return '';
	}
	if(USE_TEST_UPLOADS && mediaFileExistsLocal($clubId, UP_DIR_MOVIE, $filename)){
		return TEST_UPLOAD_BASE_URL . $clubId . '/' . UP_DIR_MOVIE . $filename;
	}
	return IMG_HOME . $clubId . '/' . UP_DIR_MOVIE . $filename;
}

$no     = ParamCharMasked($_GET["no"]);   //女の子no
//$img_path_w = $club_urls_img[CLUBID] . 'img/';
$flw     = ParamCharMasked($_GET["flw"]);
$unf     = ParamCharMasked($_GET["unf"]);
$kog     = ParamCharMasked($_GET["kog"]);



// お気に入り数の設定（HpgCoder用）- 先頭で設定
$favcast = array();
if(isset($_COOKIE["candyfav"])){
	$favcast = explode(',', urldecode($_COOKIE["candyfav"]));
}
$data1['00010601'] = count($favcast);








//-----  処理  -----//

/*
* 女の子情報取得
*/
$girldata = array();
$girldata["id"] = array();
$QUERY  = "SELECT";
$QUERY .= " id, club_id, no, name, age, height, bust, cup, waist, hip, name_kana, name_romaji";
$QUERY .= ", caption, detail, image1, image2, type1, type2, playok, nyuuten, newface, options, next_photo_update, type_toku, in_blog";
$QUERY .= " FROM girls_data";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND status = 1";
//$QUERY .= " AND guest_club_id = 0";
$QUERY .= " ORDER BY id DESC";
$RESULT = $Database->Query($QUERY);

// クエリエラーチェック
if($RESULT === false) {
    error_log("Database query failed: " . $QUERY);
    $ROWS = 0;
} else {
$ROWS = $Database->Num_Rows($RESULT);
}

if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$girldata["id"][]          = $row["id"];
		$girldata["no2id"][$row["no"]]       = $row["id"];
		$girldata["club_id"][$row["id"]]     = $row["club_id"];
		$girldata["no"][$row["id"]]          = $row["no"];
		$girldata["name"][$row["id"]]        = $row["name"];
		$girldata["name_kana"][$row["id"]]   = $row["name_kana"];
		$girldata["name_romaji"][$row["id"]] = $row["name_romaji"];
		$girldata["age"][$row["id"]]         = $row["age"];
		$girldata["height"][$row["id"]]      = $row["height"];
		$girldata["bust"][$row["id"]]        = $row["bust"];
		$girldata["cup"][$row["id"]]         = $row["cup"];
		$girldata["waist"][$row["id"]]       = $row["waist"];
		$girldata["hip"][$row["id"]]         = $row["hip"];
		$girldata["caption"][$row["id"]]     = $row["caption"];
		$girldata["detail"][$row["id"]]      = $row["detail"];
		$girldata["caption"][$row["id"]]     = $row["caption"];
		$girldata["detail"][$row["id"]]      = $row["detail"];
		$girldata["image1"][$row["id"]]      = $row["image1"];
		$girldata["image2"][$row["id"]]      = $row["image2"];
		$girldata["type1"][$row["id"]]       = $row["type1"];
		$girldata["type2"][$row["id"]]       = $row["type2"];
		$girldata["playok"][$row["id"]]      = $row["playok"];
		$girldata["nyuuten"][$row["id"]]     = $row["nyuuten"];
		$girldata["newface"][$row["id"]]     = $row["newface"];
		$girldata["options"][$row["id"]]     = $row["options"];
		/*$girldata["newface"][$row["id"]]     = $row["newface"];
		$girldata["gravure"][$row["id"]]     = $row["gravure"];*/
		$girldata["in_blog"][$row["id"]]     = $row["in_blog"];
		/*$girldata["out_blog"][$row["id"]]    = $row["out_blog"];
		$girldata["teiban"][$row["id"]]      = $row["teiban"];
		$girldata["status"][$row["id"]]      = $row["status"];
		$girldata["shimeiryou"][$row["id"]]  = $row["shimeiryou"];
		$girldata["grade"][$row["id"]]       = $row["grade"];
		$girldata["last_update"][$row["id"]] = $row["last_update"];
		$girldata["last_uptype"][$row["id"]] = $row["last_uptype"]; //1:img,2:syame,3:news,4:realtime*/
		$girldata["next_photo_update"][$row["id"]] = $row["next_photo_update"];
		$girldata["type_toku"][$row["id"]]       = $row["type_toku"];
	}
}

// $gidの設定とチェック
$gid = isset($girldata["no2id"][$no]) ? $girldata["no2id"][$no] : "";

// $gidが空の場合の処理
if($gid == ""){
	$gid = $girldata["id"][0];
	$no = $girldata["no"][$gid];
}
$gg = $gid;

// $gid設定後の実際のお気に入り登録/解除処理
if(isset($flw) && $flw == 1){
	// データベースのIDを取得（gidは既にno2idで変換済み）
	$girl_id = $gid;
	
	// 既に登録されていないかチェック
	if(!in_array($girl_id, $favcast)){
		$favcast[] = $girl_id;
		$data1['00010601'] = count($favcast);
		
		// クッキーを更新
		$cookie_value = urlencode(implode(',', $favcast));
		setcookie("candyfav", $cookie_value, time() + (86400 * 30), "/"); // 30日間有効
	}
}elseif(isset($unf) && $unf == 1){
	if($data1['00010601'] > 0){
		// データベースのIDを取得（gidは既にno2idで変換済み）
		$girl_id = $gid;
		
		// 配列から削除
		$key = array_search($girl_id, $favcast);
		if($key !== false){
			unset($favcast[$key]);
			$favcast = array_values($favcast); // インデックスを再構築
			$data1['00010601'] = count($favcast);
			
			// クッキーを更新
			$cookie_value = urlencode(implode(',', $favcast));
			setcookie("candyfav", $cookie_value, time() + (86400 * 30), "/"); // 30日間有効
		}
	}
}

// 画像制限用デバッグログ初期化
$debug_log = "";
// ログファイルパス設定（girls.phpと同じディレクトリのlogフォルダ）
// サーバー上とローカルでパスが異なる可能性があるため、両方を試す
$possible_log_dirs = array(
	dirname(dirname(__FILE__)) . '/log/',  // ローカル: C:\Work\nishikubo\Candy_HP\log\
	'/home/firststar/public_html/group_test/candy/log/',  // サーバー上: フロントエンド側
	__DIR__ . '/log/'  // includefileディレクトリ内のlogフォルダ
);
$log_dir = null;
foreach($possible_log_dirs as $dir){
	if(is_dir(dirname($dir)) || @mkdir(dirname($dir), 0777, true)){
		if(!is_dir($dir)){
			@mkdir($dir, 0777, true);
		}
		if(is_dir($dir) && is_writable($dir)){
			$log_dir = $dir;
			break;
		}
	}
}
// どのディレクトリも使えない場合は、最初のディレクトリを使用
if($log_dir === null){
	$log_dir = $possible_log_dirs[0];
	if(!is_dir($log_dir)){
		@mkdir($log_dir, 0777, true);
	}
}
$log_timestamp = date("Y-m-d_H-i-s");
$log_file = $log_dir . 'mainImg_debug_' . $log_timestamp . '.log';
$debug_log .= "ログファイルパス: " . $log_file . "\n";
$debug_log .= "__FILE__: " . __FILE__ . "\n";
$debug_log .= "dirname(__FILE__): " . dirname(__FILE__) . "\n";
$debug_log .= "dirname(dirname(__FILE__)): " . dirname(dirname(__FILE__)) . "\n";

// 画像枚数制限関数
// SP版とPC版で同じ制限を適用（MAX_VERTICAL_IMAGES = 10）
function limitImageCounts(&$imagedata, $girl_id, $isSP) {
    global $debug_log;
    
    $debug_log .= "制限関数開始 - girl_id: $girl_id\n";
    $debug_log .= "SP版は最大 " . MAX_VERTICAL_IMAGES . " 枚、PC版は無制限（MIN " . MIN_VERTICAL_IMAGES . " を保証）\n";
    
    // 横向き画像（type=2）を1枚に制限（SP版とPC版で同じ）
    if (isset($imagedata["filename"][$girl_id][2])) {
        $original_count = count($imagedata["filename"][$girl_id][2]);
        $imagedata["filename"][$girl_id][2] = array_slice($imagedata["filename"][$girl_id][2], 0, MAX_HORIZONTAL_IMAGES);
        $debug_log .= "横向き画像制限: $original_count → " . count($imagedata["filename"][$girl_id][2]) . "枚\n";
    } else {
        $debug_log .= "横向き画像なし\n";
    }
    
    // 縦向き画像（type=3）: 最低枚数のみ保証（上限はPC/SPともに解除）
    if (isset($imagedata["filename"][$girl_id][3])) {
        $count = count($imagedata["filename"][$girl_id][3]);
        $debug_log .= "縦向き画像処理前: $count枚\n";
        $debug_log .= "MIN_VERTICAL_IMAGES: " . MIN_VERTICAL_IMAGES . ", MAX_VERTICAL_IMAGES: " . MAX_VERTICAL_IMAGES . "（上限は適用しない）\n";
        
        if ($count < MIN_VERTICAL_IMAGES) {
            // 最小枚数に満たない場合はダミー画像で補完
            $dummy_needed = MIN_VERTICAL_IMAGES - $count;
            $debug_log .= "ダミー画像追加: $dummy_needed枚\n";
            for ($i = 0; $i < $dummy_needed; $i++) {
                $imagedata["filename"][$girl_id][3][] = DAMMY_IMG_SQ_h;
            }
        } elseif ($count > MAX_VERTICAL_IMAGES) {
            // 以前はSPのみMAX_VERTICAL_IMAGESで上限をかけていたが、PC版と同様に上限は撤廃
            $debug_log .= "縦向き画像はPC/SPともに上限制限を適用しません（$count枚）\n";
        } else {
            $debug_log .= "縦向き画像数は適切範囲内: $count枚\n";
        }
        
        $debug_log .= "縦向き画像処理後: " . count($imagedata["filename"][$girl_id][3]) . "枚\n";
    } else {
        $debug_log .= "縦向き画像なし\n";
    }
    
    $debug_log .= "制限関数完了\n";
}

// 画像データを動的に処理する関数
function processImageData($imagedata, $gid, $isSP) {
    global $debug_log, $vertical_movies;
    $data1 = array();
    
    // 縦向き画像（type=3）の処理
    $vertical_images = isset($imagedata["filename"][$gid][3]) ? $imagedata["filename"][$gid][3] : array();
    $vertical_count = count($vertical_images);
    
    $debug_log .= "processImageData開始 - 縦向き画像: $vertical_count枚\n";
    $debug_log .= "縦向き動画: " . count($vertical_movies) . "個\n";
    $debug_log .= "縦向き画像配列: " . print_r($vertical_images, true) . "\n";
    if ($vertical_count > 0) {
        $debug_log .= "縦向き画像: " . implode(', ', $vertical_images) . "\n";
    }
    
    // 画像と動画を統合して設定順にソート
    $combined_media = array();
    
    // 画像を追加（ダミー画像は除外）
    foreach($vertical_images as $index => $filename){
        // ダミー画像は除外
        if ($filename === DAMMY_IMG_SQ_h) {
            $debug_log .= "ダミー画像を除外: $filename\n";
            continue;
        }
        $combined_media[] = array(
            'type' => 'image',
            'filename' => $filename,
            'index' => $index
        );
    }
    
    // 動画を追加
    foreach($vertical_movies as $movie){
        $combined_media[] = array(
            'type' => 'movie',
            'filename' => $movie['filename'],
            'filetype' => $movie['filetype'],
            'id' => $movie['id']
        );
    }
    
    $total_media = count($combined_media);
    $debug_log .= "統合メディア総数: $total_media個\n";
    $debug_log .= "統合メディア配列: " . print_r($combined_media, true) . "\n";
    
    // detailセクション用スロット（最初の2枚）
    $detail_slots = array('01010009', '01010010');
    // ギャラリー用スロット（3枚目以降）
    $gallery_slots = array(
        '01010003', '01010004', '01010005', '01010006', '01010008',
        '01010011', '01010012', '01010013', '01010014', '01010015',
        '01010016', '01010017', '01010018', '01010019', '01010020',
        '01010021', '01010022'
    );
    
    // 画像のみが1枚の場合のみダミー画像を使用（0枚の場合はダミー画像なし）
    $use_dummy = ($vertical_count == 1 && count($vertical_movies) == 0);
    
    $debug_log .= "総メディア数: $total_media個, ダミー画像使用: " . ($use_dummy ? "はい" : "いいえ") . "\n";
    
    // detailセクションに最初の2枚を割り当て
    for ($i = 0; $i < count($detail_slots); $i++) {
        $slot = $detail_slots[$i];
        
        if ($i < $total_media) {
            // 実際のメディア（画像または動画）がある場合
            $media = $combined_media[$i];
            $debug_log .= "detailスロット $slot にメディア割り当て: " . $media['type'] . " - " . $media['filename'] . "\n";
            
            if ($media['type'] == 'image') {
                // 画像の場合
                $imgfile = $media['filename'];
                // ダミー画像の場合はdmy/ディレクトリを使用
                $img_dir = ($imgfile === DAMMY_IMG_SQ_h) ? 'dmy/' : UP_DIR_H;
                // resizeimg.phpが正しく動作していないため、SP版でもbuildStaticImageUrlを使用
                $data1[$slot] = buildStaticImageUrl(CLUBID, $img_dir, $imgfile);
                $debug_log .= "画像URL生成: slot=$slot, imgfile=$imgfile, dir=$img_dir, URL=" . $data1[$slot] . "\n";
            } elseif ($media['type'] == 'movie') {
                // 動画の場合
                $movie_url = buildMovieUrl(CLUBID, $media['filename']);
                $data1[$slot] = $movie_url;
                $debug_log .= "動画URL設定: $movie_url\n";
            }
        } else {
            // メディアが0個または2個以上ある場合はスロットを設定しない（空のまま）
            // ダミー画像は表示しない
            $debug_log .= "detailスロット $slot は設定しない（メディア0個または十分、ダミー画像は表示しない）\n";
        }
    }
    
    // ギャラリーに残りのメディアを割り当て（3個目以降）
    // 総メディアが2個以上の場合はギャラリーにメディアを設定、それ以外は設定しない
    if ($total_media >= 2) {
        $gallery_start_index = 2; // 3個目から開始
        $assigned_gallery_count = 0;
        for ($i = 0; $i < count($gallery_slots); $i++) {
            $slot = $gallery_slots[$i];
            $media_index = $gallery_start_index + $i;
            
            // detailセクションで使用しているスロットはギャラリーでは再利用しない
            if (in_array($slot, $detail_slots, true)) {
                $debug_log .= "ギャラリースロット $slot はdetailセクションで使用されているためスキップ\n";
                continue;
            }
            
            if ($media_index < $total_media) {
                // 実際のメディア（画像または動画）がある場合
                $media = $combined_media[$media_index];
                $debug_log .= "ギャラリースロット $slot にメディア割り当て: " . $media['type'] . " - " . $media['filename'] . "\n";
                
                if ($media['type'] == 'image') {
                    // 画像の場合
                    $imgfile = $media['filename'];
                    // ダミー画像の場合はdmy/ディレクトリを使用
                    $img_dir = ($imgfile === DAMMY_IMG_SQ_h) ? 'dmy/' : UP_DIR_H;
                    // resizeimg.phpが正しく動作していないため、SP版でもbuildStaticImageUrlを使用
                    $data1[$slot] = buildStaticImageUrl(CLUBID, $img_dir, $imgfile);
                    $debug_log .= "ギャラリー画像URL生成: slot=$slot, imgfile=$imgfile, dir=$img_dir, URL=" . $data1[$slot] . "\n";
                } elseif ($media['type'] == 'movie') {
                    // 動画の場合
                    $movie_url = buildMovieUrl(CLUBID, $media['filename']);
                    $data1[$slot] = $movie_url;
                    $debug_log .= "ギャラリー動画URL設定: $movie_url\n";
                }
                $assigned_gallery_count++;
            } else {
                // メディアが十分にある場合はスロットを設定しない（空のまま）
                $debug_log .= "ギャラリースロット $slot は設定しない（メディア十分）\n";
            }
        }

        // 2列レイアウトで奇数枚になった場合のダミー画像補完は行わない（ダミー画像は表示しない）
        if($assigned_gallery_count % 2 === 1){
            $debug_log .= "ギャラリーが奇数枚ですが、ダミー画像は表示しない\n";
        }
    } else {
        $debug_log .= "総メディアが2個未満のため、ギャラリーにはメディアを設定しない\n";
    }
    
    // 横向き画像スロットを削除（01010007はmainImgで使用するため除外）
    $horizontal_slots = array('01010001', '01010002'); // 01010005, 01010006, 01010011, 01010012, 01010015, 01010016は縦画像スロットとして使用
    foreach ($horizontal_slots as $slot) {
        if (isset($data1[$slot])) {
            unset($data1[$slot]);
            $debug_log .= "横向き画像スロット削除: " . $slot . "\n";
        }
    }
    
    $debug_log .= "processImageData完了 - 処理された画像スロット数: " . count($data1) . "\n";
    $debug_log .= "処理されたスロット: " . implode(', ', array_keys($data1)) . "\n";
    
    // 最終確認：すべての必要なスロットが設定されているかチェック
    $required_slots = array('01010003', '01010004', '01010005', '01010006', '01010008', '01010009', '01010010', '01010011', '01010012', '01010013', '01010014', '01010015', '01010016', '01010017', '01010018', '01010019', '01010020', '01010021', '01010022');
    foreach ($required_slots as $slot) {
        if (!isset($data1[$slot])) {
            $debug_log .= "警告: スロット $slot が設定されていません\n";
        }
    }
    
    return $data1;
}

/*
* 画像情報を取得 // horw/1:h,2:w
*/
$imagedata = array();
$QUERY  = "SELECT `type`, girls_id, filename, horw, status FROM girls_images";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND (type = '2' || type = '3')"; //31:pc,32:sp
$QUERY .= " AND girls_id = '". $gid ."'"; //31:pc,32:sp
$QUERY .= " ORDER BY sort, id DESC";

$debug_log .= "SQLクエリ: $QUERY\n";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
$debug_log .= "取得行数: $ROWS\n";

if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
		$debug_log .= "取得行: type=" . $row["type"] . ", filename=" . $row["filename"] . ", status=" . $row["status"] . "\n";
		//$imagedata["id"][]       = $row["id"];
		//$imagedata["club_id"][]  = $row["club_id"];
		//$imagedata["girls_id"][] = $row["girls_id"];
		//$imagedata["no"][$row["type"]][$row["girls_id"]][]       = $row["no"];
		//$imagedata["type"][]     = $row["type"];
		//$imagedata["name"][$row["type"]][$row["girls_id"]][]     = $row["name"];
		//$imagedata["filename"][$row["type"]][$row["girls_id"]][] = $row["filename"];
		//$imagedata["status"][$row["type"]][$row["girls_id"]][]   = $row["status"];
		// status = 1または0の条件をここで適用（0も表示可能にする）
        if ($row["status"] == 1 || $row["status"] == 0) {
            $imagedata["filename"][$gid][$row["type"]][] = $row["filename"];
        }
	}
	
	// 画像データ取得完了ログ
	$debug_log .= "画像データ取得完了 - gid: $gid\n";
	$debug_log .= "横向き画像数: " . (isset($imagedata["filename"][$gid][2]) ? count($imagedata["filename"][$gid][2]) : 0) . "\n";
	$debug_log .= "縦向き画像数: " . (isset($imagedata["filename"][$gid][3]) ? count($imagedata["filename"][$gid][3]) : 0) . "\n";
	
}



//
$data1['00010320'] = $girldata["name"][$gid]; //名称
$data1['00010329'] = $girldata["caption"][$gid];   //リード
$data1['00010330'] = nl2br($girldata["detail"][$gid]);    //ボディコピー
//$data1['00010327'] = $funiki_arr[$girldata["image1"][$gid]]; //雰囲気
//$data1['00010328'] = $type_arr[$girldata["type1"][$gid]];    //タイプ
$data1['00010321'] = $girldata["age"][$gid];    //age
$data1['00010322'] = $girldata["height"][$gid]; //height
$data1['00010323'] = $girldata["bust"][$gid];   //bust
$data1['00010324'] = $cup_array[$girldata["cup"][$gid]]; //cup
$data1['00010325'] = $girldata["waist"][$gid];  //waist
$data1['00010326'] = $girldata["hip"][$gid];    //hip
//$data1['00010349'] = number_format($girldata["shimeiryou"][$gid]);    //指名料
//$data1['00010332'] = $grade_name[$girldata["grade"][$gid]];    //グレード
$data1['00010331'] = strtoupper($girldata["name_romaji"][$gid]);
$data1['00010333'] = ucwords(strtolower($girldata["name_romaji"][$gid]));

//cityheaven url
$churl = $club_url_ch[CLUBID];
if($girldata["in_blog"][$gid] != ""){
	$churl = str_replace('/?of=y', '/girlid-' . $girldata["in_blog"][$gid] . '/diary/?of=y', $churl);
}else{
	$churl = str_replace('/?of=y', '/diarylist/?of=y', $churl);
}
$data1['03010090'] = $churl;
// CityHeaven 個人専用口コミ（reviews）
// in_blog は "girlid-123" か "123" のどちらでもあり得る前提で整形して差し込む
$chGirlId = isset($girldata["in_blog"][$gid]) ? strval($girldata["in_blog"][$gid]) : '';
$chGirlId = preg_replace('/^girlid-/', '', $chGirlId);
$chGirlId = preg_replace('/[^0-9]/', '', $chGirlId);
$chreviewBase = 'https://www.cityheaven.net/kagoshima/A4601/A460102/newcandy/reviews/';
$chreview = $chreviewBase; // girlid が無い場合は汎用ページへ
if($chGirlId !== ''){
	$chreview = $chreviewBase . '?girlid=' . $chGirlId;
}
$data1['03010093'] = $chreview;

if(isset($_GET['no']) && strval($_GET['no']) === '1241'){
	$debugGid = isset($gid) ? $gid : '(no gid)';
	error_log('[candy][dataset_girls] no=1241 gid=' . $debugGid . ' chGirlId=' . $chGirlId . ' chreview=' . $chreview);
}
// このページの正規URL（絶対URL・canonical/og:url用）
$data1['03010092'] = 'https://www.55810.com/girls.php?no=' . $girldata["no"][$gid];

// デバイス判定（PC版かSP版かを判定）
$isSP = (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || 
         strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false ||
         strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false ||
         strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false);
$debug_log .= "デバイス判定: isSP=" . ($isSP ? "true" : "false") . ", User-Agent=" . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A') . "\n";

// デバイス別枚数制限を適用
limitImageCounts($imagedata, $gid, $isSP);
$debug_log .= "制限適用後 - 横向き画像数: " . (isset($imagedata["filename"][$gid][2]) ? count($imagedata["filename"][$gid][2]) : 0) . "\n";
$debug_log .= "制限適用後 - 縦向き画像数: " . (isset($imagedata["filename"][$gid][3]) ? count($imagedata["filename"][$gid][3]) : 0) . "\n";

/*
* 動画情報取得
*/
$filedata = array();
$filedata["id"] = array();
$filedata["filename"] = array();
$filedata["filetype"] = array();
$QUERY  = "SELECT id,filetype,filename,sort FROM girls_movie_file";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND girls_id = '" . $gid . "'";
$QUERY .= " AND status = '1'";
$QUERY .= " ORDER BY sort ASC, id DESC";
$debug_log .= "動画データクエリ: $QUERY\n";
$debug_log .= "動画データ取得パラメータ: club_id=$club_id, girls_id=$gid\n";
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
$debug_log .= "動画データ取得行数: $ROWS\n";
if($ROWS != 0){
	$debug_log .= "取得された動画データ一覧:\n";
	while($row = $Database->Fetch_Array($RESULT)){
		$filedata["id"][$row["filetype"]]       = $row["id"];
        $filedata["filename"][$row["filetype"]]       = $row["filename"];
        $filedata["filetype"][$row["id"]]       = $row["filetype"];
		$debug_log .= "  動画データ: id=" . $row["id"] . ", filetype=" . $row["filetype"] . ", filename=" . $row["filename"] . ", sort=" . (isset($row["sort"]) ? $row["sort"] : "未設定") . "\n";
	}
	$debug_log .= "filedata配列に設定された動画: " . print_r($filedata, true) . "\n";
} else {
	$debug_log .= "動画データなし - girls_id=$gid, club_id=$club_id の条件で動画が見つかりませんでした\n";
}

// 縦型画像用動画データを取得（v5以降のみ、v1-v4は横型動画として除外）
$vertical_movies = array();
foreach($filedata["filename"] as $filetype => $filename){
	if(strpos($filetype, 'v') === 0 && $filename != ""){
		$slot_num = str_replace('v', '', $filetype);
		// 横型動画（v1, v2, v3, v4）は除外
		if(!in_array($slot_num, array('1', '2', '3', '4'))){
            $vertical_movies[] = array(
				'type' => 'movie',
				'filetype' => $filetype,
				'filename' => $filename,
                'id' => $filedata["id"][$filetype]
			);
			$debug_log .= "縦型動画データ取得: filetype=$filetype, filename=$filename\n";
		} else {
			$debug_log .= "横型動画（除外）: filetype=$filetype, filename=$filename\n";
		}
	}
}
$debug_log .= "縦型動画総数: " . count($vertical_movies) . "個\n";

// 動的画像処理関数を呼び出し（データベースに画像があるかどうかに関係なく実行）
$debug_log .= "processImageData関数呼び出し開始\n";
$debug_log .= "縦型動画データ: " . print_r($vertical_movies, true) . "\n";
$image_data = processImageData($imagedata, $gid, $isSP);

// 戻り値の安全性チェック
if (is_array($image_data)) {
    $debug_log .= "processImageData関数呼び出し完了 - 取得した画像データ数: " . count($image_data) . "\n";
    $debug_log .= "processImageData戻り値: " . print_r($image_data, true) . "\n";
    // 01010007はmainImgで使用するため、processImageDataの戻り値から除外
    if (isset($image_data['01010007'])) {
        unset($image_data['01010007']);
        $debug_log .= "processImageDataの戻り値から01010007を除外（mainImgで使用するため）\n";
    }
    $data1 = array_merge($data1, $image_data);
    $debug_log .= "data1配列マージ完了 - 総データ数: " . count($data1) . "\n";
} else {
    $debug_log .= "processImageData関数が配列を返しませんでした\n";
    $image_data = array(); // 空の配列を設定
}

//IMG
if($girldata["newface"][$gid] == 1){ //体験

//SQimg - mainImgは横型動画（v1-v4）を優先、なければ横向き画像を使用（縦型動画は除外）
$debug_log .= "mainImg(体験版)処理開始 - girl_id: $gid\n";
$main_media_set_trial = false;

// 1. 横型動画（v1, v2, v3, v4）を優先
$horizontal_movie = null;
$horizontal_movie_id = 0;
$horizontal_movie_filetype = null;
$debug_log .= "横型動画検索開始(体験版) - filedata[\"filename\"]の全件数: " . (isset($filedata["filename"]) ? count($filedata["filename"]) : 0) . "\n";
foreach($filedata["filename"] as $filetype => $filename){
	$debug_log .= "  チェック中(体験版): filetype=$filetype, filename=$filename\n";
	if($filename != "" && strpos($filetype, 'v') === 0){
		// 横型動画（v1, v2, v3, v4）をチェック
		$slot_num = str_replace('v', '', $filetype);
		$debug_log .= "    スロット番号(体験版): $slot_num\n";
		if(in_array($slot_num, array('1', '2', '3', '4'))){
			$movie_id = isset($filedata["id"][$filetype]) ? intval($filedata["id"][$filetype]) : 0;
			$debug_log .= "    横型動画候補(体験版): filetype=$filetype, filename=$filename, movie_id=$movie_id, current_max_id=$horizontal_movie_id\n";
			// 最新のIDを持つ動画を選択
			if($movie_id > $horizontal_movie_id){
				$horizontal_movie_id = $movie_id;
				$horizontal_movie = $filename;
				$horizontal_movie_filetype = $filetype;
				$debug_log .= "    新しい最大ID動画を選択(体験版): filetype=$filetype, filename=$filename, movie_id=$movie_id\n";
			}
		} else {
			$debug_log .= "    スロット番号 $slot_num は横型動画スロット（1-4）ではないためスキップ(体験版)\n";
		}
	} else {
		if($filename == ""){
			$debug_log .= "    ファイル名が空のためスキップ(体験版): filetype=$filetype\n";
		} else {
			$debug_log .= "    'v'で始まらないためスキップ(体験版): filetype=$filetype\n";
		}
	}
}
if($horizontal_movie != null){
	$movie_url = buildMovieUrl(CLUBID, $horizontal_movie);
	$data1['01010007'] = $movie_url;
	$debug_log .= "mainImg(体験版): 横型動画を表示 - filetype=$horizontal_movie_filetype, filename=$horizontal_movie, movie_id=$horizontal_movie_id, URL=$movie_url\n";
	$main_media_set_trial = true;
} else {
	$debug_log .= "mainImg(体験版): 横型動画が見つかりませんでした\n";
}

// 2. 横型動画がない場合、横向き画像を使用
if(!$main_media_set_trial && $imagedata["filename"][$gid][2][0] != ""){ //横
	// 横型動画もない場合、横向き画像を使用
	// resizeimg.phpが正しく動作していないため、SP版でもbuildStaticImageUrlを使用
	$data1['01010007'] = buildStaticImageUrl(CLUBID, UP_DIR_W, $imagedata["filename"][$gid][2][0]);
	$debug_log .= "mainImg(体験版): 横向き画像を使用: " . $imagedata["filename"][$gid][2][0] . "\n";
	$main_media_set_trial = true;
}

// 3. どちらもない場合はダミー画像を使用
if(!$main_media_set_trial){
	// 横型動画も横向き画像もない場合はダミー画像を使用
	$debug_log .= "mainImg(体験版): 横型動画も横向き画像も見つからないためダミー画像を使用\n";
	// resizeimg.phpが正しく動作していないため、SP版でもbuildStaticImageUrlを使用
	$data1['01010007'] = buildStaticImageUrl(CLUBID, 'dmy/', DAMMY_IMG_SQ_w);
}


}else{

//TOP - mainImgは横型動画（v1-v4）を優先、なければ横向き画像を使用（縦型動画は除外）
$debug_log .= "mainImg処理開始 - girl_id: $gid\n";
$debug_log .= "横向き画像配列: " . print_r($imagedata["filename"][$gid][2], true) . "\n";
$debug_log .= "横向き画像[0]: " . (isset($imagedata["filename"][$gid][2][0]) ? $imagedata["filename"][$gid][2][0] : "未設定") . "\n";
$debug_log .= "filedata配列全体: " . print_r($filedata, true) . "\n";

$main_media_set = false;

// 1. 横型動画（v1, v2, v3, v4）を優先
$horizontal_movie = null;
$horizontal_movie_id = 0;
$horizontal_movie_filetype = null;
$debug_log .= "横型動画検索開始 - filedata[\"filename\"]の全件数: " . (isset($filedata["filename"]) ? count($filedata["filename"]) : 0) . "\n";
foreach($filedata["filename"] as $filetype => $filename){
	$debug_log .= "  チェック中: filetype=$filetype, filename=$filename\n";
	if($filename != "" && strpos($filetype, 'v') === 0){
		// 横型動画（v1, v2, v3, v4）をチェック
		$slot_num = str_replace('v', '', $filetype);
		$debug_log .= "    スロット番号: $slot_num\n";
		if(in_array($slot_num, array('1', '2', '3', '4'))){
			$movie_id = isset($filedata["id"][$filetype]) ? intval($filedata["id"][$filetype]) : 0;
			$debug_log .= "    横型動画候補: filetype=$filetype, filename=$filename, movie_id=$movie_id, current_max_id=$horizontal_movie_id\n";
			// 最新のIDを持つ動画を選択
			if($movie_id > $horizontal_movie_id){
				$horizontal_movie_id = $movie_id;
				$horizontal_movie = $filename;
				$horizontal_movie_filetype = $filetype;
				$debug_log .= "    新しい最大ID動画を選択: filetype=$filetype, filename=$filename, movie_id=$movie_id\n";
			}
		} else {
			$debug_log .= "    スロット番号 $slot_num は横型動画スロット（1-4）ではないためスキップ\n";
		}
	} else {
		if($filename == ""){
			$debug_log .= "    ファイル名が空のためスキップ: filetype=$filetype\n";
		} else {
			$debug_log .= "    'v'で始まらないためスキップ: filetype=$filetype\n";
		}
	}
}
if($horizontal_movie != null){
	$movie_url = buildMovieUrl(CLUBID, $horizontal_movie);
	$data1['01010007'] = $movie_url;
	$debug_log .= "mainImg: 横型動画を表示 - filetype=$horizontal_movie_filetype, filename=$horizontal_movie, movie_id=$horizontal_movie_id, URL=$movie_url\n";
	$main_media_set = true;
} else {
	$debug_log .= "mainImg: 横型動画が見つかりませんでした\n";
}

// 3. 動画がない場合、横向き画像を使用
if(!$main_media_set && isset($imagedata["filename"][$gid][2][0]) && $imagedata["filename"][$gid][2][0] != ""){
	// 縦型動画も横型動画もない場合、横向き画像を使用
	// resizeimg.phpが正しく動作していないため、SP版でもbuildStaticImageUrlを使用
	$data1['01010007'] = buildStaticImageUrl(CLUBID, UP_DIR_W, $imagedata["filename"][$gid][2][0]);
	$debug_log .= "mainImg: 横向き画像を使用: " . $imagedata["filename"][$gid][2][0] . "\n";
	$main_media_set = true;
}

// 4. どちらもない場合はダミー画像を使用
if(!$main_media_set){
	// 縦型動画も横型動画も横向き画像もない場合はダミー画像を使用
	$debug_log .= "mainImg: 縦型動画も横型動画も横向き画像も見つからないためダミー画像を使用\n";
	// resizeimg.phpが正しく動作していないため、SP版でもbuildStaticImageUrlを使用
	$data1['01010007'] = buildStaticImageUrl(CLUBID, 'dmy/', DAMMY_IMG_SQ_w);
}



// 横向き画像スロットを削除（01010007はmainImgで使用するため除外）
$horizontal_slots = array('01010001', '01010002'); // 01010005, 01010006, 01010011, 01010012, 01010015, 01010016は縦画像スロットとして使用
foreach ($horizontal_slots as $slot) {
    if (isset($data1[$slot])) {
        unset($data1[$slot]);
        $debug_log .= "横向き画像スロット削除: " . $slot . "\n";
    }
}

// data1配列の内容をデバッグ出力
$debug_log .= "data1配列の内容:\n";
foreach ($data1 as $key => $value) {
    if (strpos($key, '0101') === 0) { // 画像関連のキーのみ
        $debug_log .= "  $key => $value\n";
    }
}

// デバッグログをファイルに書き込み
$debug_log .= "=== 最終的なdata1配列の内容 ===\n";
foreach ($data1 as $key => $value) {
    if (strpos($key, '0101') === 0) { // 画像関連のキーのみ
        $debug_log .= "  $key => $value\n";
    }
}
$debug_log .= "=== ログ書き込み完了 ===\n";
// ログファイルに日時秒数を付けて出力
$log_entry = "[" . date("Y-m-d H:i:s") . "] " . $debug_log;
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

}

//--  NOW  --//
/*
* 日付情報生成
*/
$weekarr1 = array(0=>'SUN',1=>'MON',2=>'TUE',3=>'WED',4=>'THU',5=>'FRI',6=>'SAT');
$weekarr = array(0=>'日',1=>'月',2=>'火',3=>'水',4=>'木',5=>'金',6=>'土');
//
//切替時間確認
if(date('G') < NEWDAY_TIME){
	$last_ymd = date("Y-n-j",getDay(-1));
	list($yy,$mm,$dd) = explode('-', $last_ymd);
	$ww = date("w",getDay(-1));
}else{
$yy = date('Y');
$mm = date('n');
$dd = date('j');
$ww = date('w');
}
$yyy = array();
$mmm = array();
$ddd = array();
$www = array();
$i = 0;
while($i < 8){
	if(checkdate($mm,$dd,$yy)){
		$yyy[] = $yy;
		$mmm[] = $mm;
		$ddd[] = $dd;
		$dd++;
		$i++;
	}else{
		$dd = 1;
		$mm++;
		if(checkdate($mm,$dd,$yy)){
			$yyy[] = $yy;
			$mmm[] = $mm;
			$ddd[] = $dd;
			$dd++;
			$i++;
		}else{
			$mm = 1;
			$yy++;
			if(checkdate($mm,$dd,$yy)){
				$yyy[] = $yy;
				$mmm[] = $mm;
				$ddd[] = $dd;
				$dd++;
				$i++;
			}
		}
	}
	$www[] = $ww;
	$ww++;
	if($ww > 6){
		$ww = 0;
	}
}

//if($work == ""){$work = 1;} //空白時強制

//検索用日付設定
if($work != ""){
	$y = $yyy[$work];
	$m = $mmm[$work];
	$d = $ddd[$work];
	$now = 0;
}else{
	//切替時間確認
	if(date('G') < NEWDAY_TIME){
		$last_ymd = date("Y-n-j",getDay(-1));
		list($y,$m,$d) = explode('-', $last_ymd);
		$now = intval(date('Gi')) + 2400;
	}else{
	$y = date('Y');
	$m = date('n');
	$d = date('j');
	$now = intval(date('Gi'));
	}
}

//表示用
$workday = array();
$workday[0] = $mmm[0] . '/' . $ddd[0];// . '(' . $weekarr1[$www[0]] . ')';
$workday[1] = $mmm[1] . '/' . $ddd[1];// . '(' . $weekarr1[$www[1]] . ')';
$workday[2] = $mmm[2] . '/' . $ddd[2];// . '(' . $weekarr1[$www[2]] . ')';
$workday[3] = $mmm[3] . '/' . $ddd[3];// . '(' . $weekarr1[$www[3]] . ')';
$workday[4] = $mmm[4] . '/' . $ddd[4];// . '(' . $weekarr1[$www[4]] . ')';
$workday[5] = $mmm[5] . '/' . $ddd[5];// . '(' . $weekarr1[$www[5]] . ')';
$workday[6] = $mmm[6] . '/' . $ddd[6];// . '(' . $weekarr1[$www[6]] . ')';


/*
* 出勤情報取得
*/
$scheduledata = array();
$schedate[1] = array();
$schedate[2] = array();
$schedate[3] = array();
$scheduledata2 = array();
$schedate2[0] = array();
$scheduledata["girls_id"] = array();
$scheduledata2["girls_id"] = array();
$karicast11 = array();
	$karicast11[0] = array();
	$karicast11[1] = array();
	$karicast11[2] = array();
	$karicast11[3] = array();
	$karicast11[4] = array();
	$karicast11[5] = array();
	$karicast11[6] = array();
$karicast12 = array();
	$karicast12[0] = array();
	$karicast12[1] = array();
	$karicast12[2] = array();
	$karicast12[3] = array();
	$karicast12[4] = array();
	$karicast12[5] = array();
	$karicast12[6] = array();
$karicast21 = array();
	$karicast21[0] = array();
	$karicast21[1] = array();
	$karicast21[2] = array();
	$karicast21[3] = array();
	$karicast21[4] = array();
	$karicast21[5] = array();
	$karicast21[6] = array();
$karicast22 = array();
	$karicast22[0] = array();
	$karicast22[1] = array();
	$karicast22[2] = array();
	$karicast22[3] = array();
	$karicast22[4] = array();
	$karicast22[5] = array();
	$karicast22[6] = array();
$QUERY  = "SELECT * FROM girls_schedule";
$QUERY .= " WHERE club_id = '" . CLUBID . "'";
$QUERY .= " AND girls_id = '". $gid ."'";
$QUERY .= " AND (type = '1' || type = '6' || type = '0')";

//1week全選択
$QUERY .= " AND ( ";
$QUERY .= "(year = '" . $yyy[0] . "' AND month = '" . $mmm[0] . "' AND day = '" . $ddd[0] . "')";
$QUERY .= " || (year = '" . $yyy[1] . "' AND month = '" . $mmm[1] . "' AND day = '" . $ddd[1] . "')";
$QUERY .= " || (year = '" . $yyy[2] . "' AND month = '" . $mmm[2] . "' AND day = '" . $ddd[2] . "')";
$QUERY .= " || (year = '" . $yyy[3] . "' AND month = '" . $mmm[3] . "' AND day = '" . $ddd[3] . "')";
$QUERY .= " || (year = '" . $yyy[4] . "' AND month = '" . $mmm[4] . "' AND day = '" . $ddd[4] . "')";
$QUERY .= " || (year = '" . $yyy[5] . "' AND month = '" . $mmm[5] . "' AND day = '" . $ddd[5] . "')";
$QUERY .= " || (year = '" . $yyy[6] . "' AND month = '" . $mmm[6] . "' AND day = '" . $ddd[6] . "')";
$QUERY .= " ) ";

$QUERY .= " AND views >= '0'";
$QUERY .= " AND status = '1'";
$QUERY .= " ORDER BY year, month, day, open_ji, open_fun, end_ji, end_fun, id DESC";//日付、時間順にソート
$RESULT = $Database->Query($QUERY);
$ROWS = $Database->Num_Rows($RESULT);
if($ROWS != 0){
	while($row = $Database->Fetch_Array($RESULT)){
	
	if($row["year"] == $yyy[0] && $row["month"] == $mmm[0] && $row["day"] == $ddd[0]){
		$weekid = 0;
	}elseif($row["year"] == $yyy[1] && $row["month"] == $mmm[1] && $row["day"] == $ddd[1]){
		$weekid = 1;
	}elseif($row["year"] == $yyy[2] && $row["month"] == $mmm[2] && $row["day"] == $ddd[2]){
		$weekid = 2;
	}elseif($row["year"] == $yyy[3] && $row["month"] == $mmm[3] && $row["day"] == $ddd[3]){
		$weekid = 3;
	}elseif($row["year"] == $yyy[4] && $row["month"] == $mmm[4] && $row["day"] == $ddd[4]){
		$weekid = 4;
	}elseif($row["year"] == $yyy[5] && $row["month"] == $mmm[5] && $row["day"] == $ddd[5]){
		$weekid = 5;
	}elseif($row["year"] == $yyy[6] && $row["month"] == $mmm[6] && $row["day"] == $ddd[6]){
		$weekid = 6;
	}else{
		$weekid = 9;
	}
		
		if($row["end_fun"] < 10){
		$endtime = intval($row["end_ji"]."0".$row["end_fun"]);
		}else{
		$endtime = intval($row["end_ji"].$row["end_fun"]);
		}
		
		if($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6){
		
		//if($work > 0){} //翌日以降は全て出勤予定に
		if($weekid > 0){ //翌日以降は全て出勤予定に
				
				if($row["open_ji"] == 100){ //日の出
					$karicast11[$weekid][] = $row["girls_id"];
				}else{
					$karicast12[$weekid][] = $row["girls_id"];
				}
				
		}else{
		
			//if(($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now) || (2330 <= $now)){} //受付終了or受付終了or受付時間＜現在時刻or23:30以降
			// SP版のみ時間チェック条件を厳しくする
			$timeCheck = ($isSP) ? (($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now) || (2330 <= $now)) : (($row["type"] == 6) || ($row["type2"] == 3) || ($endtime < $now));
			
			if($timeCheck){ //受付終了or受付終了or受付時間＜現在時刻or23:30以降
				
				if($row["open_ji"] == 100){ //日の出
					$karicast21[$weekid][] = $row["girls_id"];
				}else{
					$karicast22[$weekid][] = $row["girls_id"];
				}
				
			}elseif($row["type"] == 0 || $row["type"] == 1){ //出勤予定or出勤済
				
				if($row["open_ji"] == 100){ //日の出
					$karicast11[$weekid][] = $row["girls_id"];
				}else{
					$karicast12[$weekid][] = $row["girls_id"];
				}
				
			}
			
		}
		
		}
		$scheduledata[$weekid]["type"][$row["girls_id"]]     = $row["type"];
		$scheduledata[$weekid]["open_ji"][$row["girls_id"]]  = $row["open_ji"];
		$scheduledata[$weekid]["open_fun"][$row["girls_id"]] = $row["open_fun"];
		$scheduledata[$weekid]["end_ji"][$row["girls_id"]]   = $row["end_ji"];
		$scheduledata[$weekid]["end_fun"][$row["girls_id"]]  = $row["end_fun"];
		$scheduledata[$weekid]["type2"][$row["girls_id"]]    = $row["type2"];
		$scheduledata[$weekid]["aki_ji"][$row["girls_id"]]   = $row["aki_ji"];
		$scheduledata[$weekid]["aki_fun"][$row["girls_id"]]  = $row["aki_fun"];
		$scheduledata[$weekid]["plusview"][$row["girls_id"]]  = $row["plusview"];
		
		//表示テキスト生成
		if($row["type"] == 0 || $row["type"] == 1 || $row["type"] == 6){
			
			//開始時間
			if($row["open_ji"] == 100){
				$vtime = '日の出';
			}else{
				if($row["open_fun"] == 0){
					$ofun = "00";
				}else{
					$ofun = $row["open_fun"];
				}
				$vtime = $row["open_ji"] . ":" . $ofun;
			}
			
			$vtime .= "~";
			
			if($row["end_ji"] == 99){
				$vtime .= $isSP ? "LAST" : "";
			}else{
				if($row["end_fun"] == 0){
					$efun = "00";
				}else{
					$efun = $row["end_fun"];
				}
				$vtime .= $row["end_ji"] . ":" . $efun;
			}
			
			$scheduledata[$weekid]["view"]["time"][$row["girls_id"]] = $vtime; //時間
			$scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //
			$scheduledata[$weekid]["view"]["type2"][$row["girls_id"]] = $row["type2"]; //
			$scheduledata[$weekid]["view"]["time_view"][$row["girls_id"]]  = $row["time_view"];
			
			
			//出勤２（SP版のみ）
			if($isSP && $row["kn2_open_ji"] != ""){
			
			//開始時間
			if($row["kn2_open_ji"] == 100){
				$vtime2 = '日の出';
			}else{
				if($row["kn2_open_fun"] == 0){
					$ofun = "00";
				}else{
					$ofun = $row["kn2_open_fun"];
				}
				$vtime2 = $row["kn2_open_ji"] . ":" . $ofun;
			}
			
			$vtime2 .= "~";
			
			if($row["kn2_end_ji"] == 99){
				$vtime2 .= "LAST";
			}else{
				if($row["kn2_end_fun"] == 0){
					$efun = "00";
				}else{
					$efun = $row["kn2_end_fun"];
				}
				$vtime2 .= $row["kn2_end_ji"] . ":" . $efun;
			}
			
			$scheduledata[$weekid]["view"]["time2"][$row["girls_id"]] = $vtime2; //時間
			
			}else{
			$scheduledata[$weekid]["view"]["time2"][$row["girls_id"]] = ""; //時間
			}
			
		}else{
			$scheduledata[$weekid]["view"]["type"][$row["girls_id"]] = $row["type"]; //出/休
		}
	}
}

$week = array(0=>'SUN',1=>'MON',2=>'TUE',3=>'WED',4=>'THU',5=>'FRI',6=>'SAT');
$month_name = array(1 => 'JANUARY',2 => 'FEBRUARY', 3 => 'MARCH', 4 => 'APRIL', 5 => 'MAY', 6 => 'JUNE',
7 => 'JULY', 8 => 'AUGUST', 9 => 'SEPTEMBER', 10 => 'OCTOBER', 11 => 'NOVEMBER', 12 => 'DECEMBER');

	$sche_type = $scheduledata[0]["view"]["type"][$gid];
	//
	if($sche_type == ""){//予定なし
	$sche_type = 9;//休み
	}elseif($sche_type == 6){
	$sche_type = 8;//終了
	}else{
	$sche_type = 1;//出勤(受付中)
	}
	//
	if($scheduledata[0]["view"]["type2"][$gid] == 6){ //TEL確認
	$sche_type = 6;
	}elseif($scheduledata[0]["view"]["type2"][$gid] == 3){ //受付終了
	$sche_type = 8;
	}
	
	if($sche_type == 1){//出勤(受付中)
		
		//時間帯
		$status = $scheduledata[0]["view"]["time"][$gid];

	}elseif($sche_type == 6){//TEL確認
		
		//
		$status = '電話確認';
		
	}elseif($sche_type == 8){//受付終了
		
		//
		$status = '案内終了';
	
	}elseif($sche_type == 9){//休み
		
		//
		$status = 'CLOSED TODAY';
	
	}
	$data1['00010354'] = $status;


//htmlファイル変更
// newfaceが1でもgirls.htmlを使うように変更（20251201ではgirls_d.htmlを使わない）
// if($girldata["newface"][$gid] == 1){
// 	//
// 	// pc/やs/ディレクトリを削除してから置換
// 	$path_clean = str_replace('/pc/', '/', $path);
// 	$path_clean = str_replace('/s/', '/', $path_clean);
// 	$paht_ntrdy = str_replace('girls.html', 'girls_d.html', $path_clean);
// 	$source_file = $paht_ntrdy;
// 	
// 	//次回撮影日
// 	if($girldata["next_photo_update"][$gid] != ""){
// 		list($yyn,$mmn,$ddn) = explode('-', $girldata["next_photo_update"][$gid]);
// 		$data1['00010710'] = $mmn .'/'. $ddn;
// 	}else{
// 		$data1['00010710'] = '--/--';
// 	}
// 		
// }

// 次回撮影日の設定（newfaceに関係なく設定）
if($girldata["next_photo_update"][$gid] != ""){
	list($yyn,$mmn,$ddn) = explode('-', $girldata["next_photo_update"][$gid]);
	$data1['00010710'] = $mmn .'/'. $ddn;
}else{
	$data1['00010710'] = '--/--';
}

/*
* 独自タグから表示枠ソースを取得
*/
$source = file_get_contents($source_file);



//
$movie_dir = IMG_HOME . CLUBID . '/' . UP_DIR_MOVIE;
//
$mvck = 0;
//mp4
if($filedata["filename"][1] != ""){
	$data1['01010310'] = $movie_dir . $filedata["filename"][1];
	$mvck = 1;
}else{
	$source = str_replace('<source src="rep01010310eot" type="video/mp4">', '', $source);
}
//ogv
if($filedata["filename"][2] != ""){
	$data1['01010311'] = $movie_dir . $filedata["filename"][2];
	$mvck = 1;
}else{
	$source = str_replace('<source src="rep01010311eot" type="video/ogg">', '', $source);
}
//webm
if($filedata["filename"][3] != ""){
	$data1['01010312'] = $movie_dir . $filedata["filename"][3];
	$mvck = 1;
}else{
	$source = str_replace('<source src="rep01010312eot" type="video/webm">', '', $source);
}
//jpg
if($filedata["filename"][4] != ""){
	$data1['01010321'] = $movie_dir . $filedata["filename"][4];
	//$mvck = 1;
}else{
	$source = str_replace(' poster="rep01010321eot"', '', $source);
}
// 古い形式の動画（filetype=1,2,3,4）が存在し、かつ新しい形式の横型動画（v1-v4）が設定されていない場合のみ、古い形式の動画タグを有効化
// 体験版の場合は $main_media_set_trial を、通常版の場合は $main_media_set をチェック
$has_new_format_movie = false;
if(isset($main_media_set_trial) && $main_media_set_trial){
	$has_new_format_movie = true;
	$debug_log .= "体験版: 横型動画v1-v4が設定されている\n";
}
if(isset($main_media_set) && $main_media_set){
	$has_new_format_movie = true;
	$debug_log .= "通常版: 横型動画v1-v4が設定されている\n";
}
if($mvck == 1 && !$has_new_format_movie){
		$source = str_replace('<!-- TOPPHOTO -->', '<!-- TOPPHOTO', $source);//コメントアウト
		$source = str_replace('<!-- /TOPPHOTO -->', '/TOPPHOTO -->', $source);//
		$source = str_replace('<!-- TOPMOVIE', '<!-- TOPMOVIE -->', $source);//コメントイン
		$source = str_replace('/TOPMOVIE -->', '<!-- /TOPMOVIE -->', $source);//
		$debug_log .= "古い形式の動画タグを有効化（横型動画v1-v4が存在しないため）\n";
}else if($mvck == 1 && $has_new_format_movie){
		$debug_log .= "古い形式の動画は存在するが、横型動画v1-v4が優先されるため、古い形式の動画タグは無効のまま\n";
}


//お気に入り登録
if($flw == 1){
	//
	if(isset($_COOKIE["candyfav"])){
		$favarr = array();
		$favarr = explode(',', urldecode($_COOKIE["candyfav"]));
		
		if(!in_array($gid, $favarr)){ //重複確認
			$setval = urldecode($_COOKIE["candyfav"]) . ',' . $gid;
		} else {
			$setval = urldecode($_COOKIE["candyfav"]); // 既に登録済みの場合
		}
	}else{
		$setval = $gid;
	}
	//
	$seturl = $club_domains[CLUBID];
	$seturl = str_replace('http://', '', $seturl);//
	$seturl = str_replace('group_test/candy/', '', $seturl);//
	$seturl = str_replace('/', '', $seturl);//
	
	//cookie set - 現在のサイトドメインを使用
	$current_host = $_SERVER['HTTP_HOST'];
	
	// firststar.kir.jpの場合は.firststar.kir.jpを使用、それ以外は設定ファイルの値を使用
	if (strpos($current_host, 'firststar.kir.jp') !== false) {
		$cookie_domain = ".firststar.kir.jp";
	} else {
		$cookie_domain = $seturl;
	}
	
	setcookie( "candyfav", urlencode($setval), time()+(60*60*24*30), "/", $cookie_domain);
	


		//linkin
		$source = str_replace('<!-- FAVUNFLW', '<!-- FAVUNFLW -->', $source);//
		$source = str_replace('/FAVUNFLW -->', '<!-- /FAVUNFLW -->', $source);//
		//linkout
		$source = str_replace('<!-- FAVFLW -->', '<!-- FAVFLW ', $source);//
		$source = str_replace('<!-- /FAVFLW -->', ' /FAVFLW -->', $source);//
		
		$data1['03010091'] = 'girls.php?no=' . $no . '&unf=1';
		//COOKIE-
		if($sche_type == 1){
			$data1['03010091'] .= '&kog=g';
		}
		
}elseif($unf == 1){//お気に入り解除
	//
	if(isset($_COOKIE["candyfav"])){
		$favarr = array();
		$favarr = explode(',', urldecode($_COOKIE["candyfav"]));
		//
		if(in_array($gid, $favarr)){ //重複確認
			$setval = "";
			$i = 0;
			foreach($favarr as $val){
				if($gid != $val){
					if($i > 0){ $setval .= ','; }
					$setval .= $val;
				$i++;
				}
			}
		//
		$seturl = $club_domains[CLUBID];
		$seturl = str_replace('http://', '', $seturl);//
		$seturl = str_replace('group_test/candy/', '', $seturl);//
		$seturl = str_replace('/', '', $seturl);//
		//cookie set - 現在のサイトドメインを使用
		$current_host = $_SERVER['HTTP_HOST'];
		
		// firststar.kir.jpの場合は.firststar.kir.jpを使用、それ以外は設定ファイルの値を使用
		if (strpos($current_host, 'firststar.kir.jp') !== false) {
			$cookie_domain = ".firststar.kir.jp";
		} else {
			$cookie_domain = $seturl;
		}
		setcookie( "candyfav", urlencode($setval), time()+(60*60*24*30), "/", $cookie_domain);
		}
	}
		$data1['03010091'] = 'girls.php?no=' . $no . '&flw=1';
		//COOKIE+
		if($sche_type == 1){
			$data1['03010091'] .= '&kog=k';
		}

}elseif(isset($_COOKIE["candyfav"])){//btnlink
	$favarr = array();
	$favarr = explode(',', urldecode($_COOKIE["candyfav"]));
	
	// デバッグ：現在のクッキー内容とgidを確認
	$debug_log .= "Checking fav button display - gid: " . $gid . ", favarr: " . print_r($favarr, true) . "\n";
	
	// クッキーに保存されている値がno（女の子番号）かid（内部ID）かを判定
	// 現在のgid（内部ID）がfavarrに含まれているかチェック
	if(in_array($gid, $favarr)){ //登録済
		$data1['03010091'] = 'girls.php?no=' . $no . '&unf=1';
		//linkin
		$source = str_replace('<!-- FAVUNFLW', '<!-- FAVUNFLW -->', $source);//
		$source = str_replace('/FAVUNFLW -->', '<!-- /FAVUNFLW -->', $source);//
		//linkout
		$source = str_replace('<!-- FAVFLW -->', '<!-- FAVFLW ', $source);//
		$source = str_replace('<!-- /FAVFLW -->', ' /FAVFLW -->', $source);//
		//COOKIE-
		if($sche_type == 1){
			$data1['03010091'] .= '&kog=g';
		}
		
	}else{
		$data1['03010091'] = 'girls.php?no=' . $no . '&flw=1';
		//COOKIE+
		if($sche_type == 1){
			$data1['03010091'] .= '&kog=k';
		}
	}
}else{
	$data1['03010091'] = 'girls.php?no=' . $no . '&flw=1';
		//COOKIE+
		if($sche_type == 1){
			$data1['03010091'] .= '&kog=k';
		}
}

//新人判定
if($girldata["newface"][$gid] == 1){

	//コメントイン
	$source = str_replace('<!-- TRIAL', '<!-- TRIAL -->', $source);//
	$source = str_replace('/TRIAL -->', '<!-- /TRIAL -->', $source);//
	
}elseif($girldata["newface"][$gid] == 2){

	//コメントイン
	$source = str_replace('<!-- NEWFACE', '<!-- NEWFACE -->', $source);//
	$source = str_replace('/NEWFACE -->', '<!-- /NEWFACE -->', $source);//
	
}

//lightbox処理
if(!isset($imagedata["filename"][$gid][3][0]) || $imagedata["filename"][$gid][3][0] == ""){
		$source = str_replace('<a href="rep01010019eot" class="blackfade lightbox">', '<a class="blackfade lightbox">', $source);
		$source = str_replace('<a href="rep01010019eot" class="lightbox">', '<a class="lightbox">', $source);
}
if(!isset($imagedata["filename"][$gid][3][1]) || $imagedata["filename"][$gid][3][1] == ""){
		$source = str_replace('<a href="rep01010020eot" class="blackfade lightbox">', '<a class="blackfade lightbox">', $source);
		$source = str_replace('<a href="rep01010020eot" class="lightbox">', '<a class="lightbox">', $source);
}

if(!isset($imagedata["filename"][$gid][3][2]) || $imagedata["filename"][$gid][3][2] == ""){
		$source = str_replace('<a href="rep01010013eot" class="blackfade lightbox">', '<a class="blackfade lightbox">', $source);
		$source = str_replace('<a href="rep01010013eot" class="box lightbox">', '<a class="box lightbox">', $source);
}
if(!isset($imagedata["filename"][$gid][3][3]) || $imagedata["filename"][$gid][3][3] == ""){
		$source = str_replace('<a href="rep01010014eot" class="blackfade lightbox">', '<a class="blackfade lightbox">', $source);
		$source = str_replace('<a href="rep01010014eot" class="box lightbox">', '<a class="box lightbox">', $source);
}


//COOKIE処理
if($kog == "g"){
	if($isSP) {
		$jss = 'if(favCount>0){favCount-=1;CookieWrite("favCount", favCount, 1);}' . "\n";
		$source = str_replace('favTask();', $jss . 'favTask();', $source);
	} else {
		// $source = str_replace('js/fav.js', 'js/fav_gen.js', $source);
	}
}elseif($kog == "k"){
	if($isSP) {
		$jss = 'favCount+=1;CookieWrite("favCount", favCount, 1);' . "\n";
		$source = str_replace('favTask();', $jss . 'favTask();', $source);
} else {
		// $source = str_replace('js/fav.js', 'js/fav_ka.js', $source);
	}
}


//CAN-DIARY取得
//パラメータ設定
$prm  = "&gId=" . $gid;
$prm .= "&limit=1";
//ポイントサーバ連携API
$cddata = GetCandiaryapiData('010', $prm);
if($cddata["status"] == "0"){
	
	$diarydata = $cddata["diary"][0];
	
	//コメントイン
	$source = str_replace('class="diary" style="display:none;"', 'class="diary"', $source);//
	
	//date
	$ymd = str_replace('-', '/', $diarydata["date"]);
	$hi = substr($diarydata["time"], 0, 5);
	$data1['00010640'] = $ymd . ' ' . $hi;

	//caption
	$title = $diarydata["title"];
	if($title == ""){ $title = 'non title'; }
	$data1['00010641'] = $title;
	
	//detail
	$detail = $diarydata["text"];
	if($isSP) {
		// SP版：短縮処理
		$detail = htmlchardec($diarydata["text"]);
		$detail = str_replace(array("\r\n","\n","\r"," ","　"), '', $detail);
		$detail = strip_tags($detail);
		if(mb_strlen($detail, 'utf-8') > 80){
		$detail  = mb_substr($detail, 0, 80, 'utf-8');
		$detail .= '…';
		}
} else {
		// PC版：HTMLタグ処理
		if(!preg_match("/<\//", $detail)){
			$detail = nl2br($detail);
		}
	}
	$data1['00010642'] = $detail;
	
	//img
	if($diarydata["dImg"] != ""){
		$data1['01010280'] = $diarydata["dImg"];
	}else{
		$source = str_replace('class="diary-img"', 'class="diary-img"  style="display:none;"', $source);//
	}
	
	//love
	$data1['00010630'] = $diarydata["love"];
	
	//
	$source = str_replace('http://firststar.kir.jp/group_test/diary/', 'https://can-diary.com/', $source);//
	
	//list-link
	$llpara = "?gid=" . $gid . "&gName=" . $diarydata["gName"];
	$source = str_replace('diary/list.html"', 'diary/list.html'.$llpara.'"', $source);//
	$source = str_replace('can-diary.com/list.html"', 'can-diary.com/list.html'.$llpara.'"', $source);//
	
	//kiji-link
	$klpara = "?gid=" . $gid . "&did=" . $diarydata["dId"];
	$source = str_replace('diary/blog.html"', 'diary/blog.html'.$klpara.'"', $source);//
	$source = str_replace('can-diary.com/blog.html"', 'can-diary.com/blog.html'.$klpara.'"', $source);//
	
	
	//API設定
	$source = str_replace('(gid, did)', '('. $gid .', '. $diarydata["dId"] .')', $source);//
	
	//クッキー設定
	$kie = 'loveon' . $diarydata["dId"];
	$val = 1;
	$source = str_replace('(kie, value, days)', "('". $kie ."', ". $val .", 1)", $source);//

	//クッキー確認
	if($_COOKIE[$kie] == 1){
	$source = str_replace('id="loveoff"', 'id="loveoff" style="display:none;"', $source);//
	}else{
	$source = str_replace('id="loveon"', 'id="loveon" style="display:none;"', $source);//
	}
	
	//body cookie
	$source = str_replace("('gid', value, days)", "('gid', ". $gid .", 1)", $source);//
	$source = str_replace("('did', value, days)", "('did', ". $diarydata["dId"] .", 1)", $source);//
	
}else{
	$source = str_replace("window.onload = CookieWrite('gid', value, days);", '', $source);//
$source = str_replace("window.onload = CookieWrite('did', value, days);", '', $source);//
}

// デバッグ: 最終的な$sourceの一部をファイルに出力（空の横向きスロット削除処理の確認用）
$debug_log = "=== 最終的な$sourceの横向きギャラリー部分 ===\n";
$debug_log .= "PC版ギャラリー:\n";
if (preg_match('/<!-- PC版ギャラリー -->.*?<\/div>/s', $source, $matches)) {
    $debug_log .= $matches[0] . "\n";
}
$debug_log .= "\nSP版ギャラリー:\n";
if (preg_match('/<!-- SP版ギャラリー -->.*?<\/div>\s*<\/div>/s', $source, $matches)) {
    $debug_log .= $matches[0] . "\n";
}
// ログファイルに日時秒数を付けて出力
$log_entry = "[" . date("Y-m-d H:i:s") . "] " . $debug_log;
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

// $data1配列を$data['code']に設定（HTMLテンプレートの置換用）
foreach ($data1 as $key => $value) {
    $data['code'][$key] = $value;
}

?>