<?php
// Execute the actual profile acquisition/render code with an in-memory query double.
// This deliberately returns non-public rows too, checking the second PHP guard.
$source = file_get_contents(dirname(__DIR__, 2) . '/HP/includefile/dataset_girls.php');
$start = strpos($source, '$imagedata = array();');
$end = strpos($source, '$data1[\'00010320\']', $start + 1);
$acquisition = substr($source, $start, $end - $start);
$funcStart = strpos($source, 'function limitImageCounts(');
$funcEnd = strpos($source, '/*', $funcStart);
$function = substr($source, $funcStart, $funcEnd - $funcStart);
class ProfileImageTestDb {
    public $query;
    public $rows;
    public function Query($query) { $this->query = $query; return true; }
    public function Num_Rows($result) { return count($this->rows); }
    public function Fetch_Array($result) { return array_shift($this->rows); }
}
define('CLUBID', 2);
define('UP_DIR_H', 'gl_h/');
define('DAMMY_IMG_SQ_h', 'dmy_h.jpg');
define('MAX_HORIZONTAL_IMAGES', 1);
define('MIN_VERTICAL_IMAGES', 2);
define('USE_TEST_UPLOADS', false);
define('IMG_HOME', 'https://image.can-diary.com/');
define('CANDY_GIRLS_PAGE_CONTENT_FRONT', true);
require dirname(__DIR__, 2) . '/HP/includefile/candy_girls_page_content.php';
require dirname(__DIR__, 2) . '/HP/includefile/class.hpgcoder2.php';
$urlStart = strpos($source, 'function buildStaticImageUrl(');
$urlEnd = strpos($source, 'function buildResizeImageUrl(', $urlStart);
eval(substr($source, $urlStart, $urlEnd - $urlStart));
function buildMovieUrl($club, $filename) { return '/fixture/movies/' . $filename; }
eval($function);
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
$checks = 0;
function checkProfile($ok, $message) {
    global $checks;
    $checks++;
    if (!$ok) { throw new Exception($message); }
}
$template = file_get_contents(dirname(__DIR__, 2) . '/HP/source/girls.html');
preg_match_all('/<(?:img|source)\b[^>]*rep010100(?:09|10)eot[^>]*>/', $template, $detailTags);
$detailTemplate = implode("\n", $detailTags[0]);
checkProfile(count($detailTags[0]) === 8, 'PC/SP detail image and video tags must all be tested');
function checkDetailHtml($slots, $label) {
    global $detailTemplate;
    $coder = new HpgCoder();
    // Legacy constructor is invoked explicitly for PHP 8 compatibility in this isolated test.
    $coder->HpgCoder($detailTemplate, array('code' => $slots));
    checkProfile(strpos($coder->Converted, 'src=""') === false, $label . ': detail media URLs must not be empty');
    checkProfile(strpos($coder->Converted, 'rep010100') === false, $label . ': no unresolved detail tokens');
    foreach (array('01010009', '01010010') as $slot) {
        checkProfile(isset($slots[$slot]) && $slots[$slot] !== '', $label . ': detail slot must be populated');
        checkProfile(strpos($coder->Converted, 'src="' . $slots[$slot] . '"') !== false, $label . ': template must use selected URL');
    }
}
$dummyUrl = 'https://image.can-diary.com/2/dmy/dmy_h.jpg';
foreach (array(false, true) as $isSP) {
    foreach (array(3046, 9999) as $gid) {
        $Database = new ProfileImageTestDb();
        $Database->rows = array();
        foreach (array('2', '3') as $type) {
            foreach (array('0', '1', '2', '9') as $status) {
                $Database->rows[] = array('type' => $type, 'girls_id' => $gid, 'filename' => $type . '_' . $status . '.jpg', 'status' => $status);
            }
        }
        eval($acquisition);
        checkProfile(strpos($Database->query, 'AND status = 1') !== false, 'SQL must exclude hidden and deleted images');
        checkProfile($imagedata['filename'][$gid][2] === array('2_1.jpg'), 'horizontal images must contain only status 1');
        checkProfile($imagedata['filename'][$gid][3] === array('3_1.jpg'), 'vertical images must contain only status 1');
        $vertical_movies = array();
        limitImageCounts($imagedata, $gid, $isSP);
        $slots = processImageData($imagedata, $gid, $isSP);
        checkProfile($slots['01010009'] === 'https://image.can-diary.com/2/gl_h/3_1.jpg', 'visible image reaches profile slot');
        checkProfile(strpos(json_encode($slots), '3_0.jpg') === false && strpos(json_encode($slots), '3_2.jpg') === false, 'hidden/deleted image never reaches PC/SP slots');
        checkDetailHtml($slots, 'mixed visibility');
        $Database->rows = array(array('type' => '3', 'girls_id' => $gid, 'filename' => 'hidden.jpg', 'status' => '0'));
        eval($acquisition);
        checkProfile($imagedata === array(), 'all hidden leaves no real image candidates');
        limitImageCounts($imagedata, $gid, $isSP);
        $slots = processImageData($imagedata, $gid, $isSP);
        checkProfile($slots === array('01010009' => $dummyUrl, '01010010' => $dummyUrl), 'all hidden must display two fallback images');
        checkDetailHtml($slots, 'all hidden');
    }
}

// Expected media sequences are explicit fixtures, independent of the implementation.
$imageBase = 'https://image.can-diary.com/2/gl_h/';
$cases = array(
    array('missing image key', null, array(), array($dummyUrl, $dummyUrl), null),
    array('empty image array', array(), array(), array($dummyUrl, $dummyUrl), null),
    array('one image', array('a.JPG'), array(), array($imageBase . 'a.jpg', $dummyUrl), null),
    array('two images', array('a.jpg', 'b.jpg'), array(), array($imageBase . 'a.jpg', $imageBase . 'b.jpg'), null),
    array('three images', array('a.jpg', 'b.jpg', 'c.jpg'), array(), array($imageBase . 'a.jpg', $imageBase . 'b.jpg'), $imageBase . 'c.jpg'),
    array('only one movie', null, array('a.mp4'), array('/fixture/movies/a.mp4', $dummyUrl), null),
    array('only two movies', null, array('a.mp4', 'b.mp4'), array('/fixture/movies/a.mp4', '/fixture/movies/b.mp4'), null),
    array('one image one movie', array('a.jpg'), array('a.mp4'), array($imageBase . 'a.jpg', '/fixture/movies/a.mp4'), null),
    array('one image two movies', array('a.jpg'), array('a.mp4', 'b.mp4'), array($imageBase . 'a.jpg', '/fixture/movies/a.mp4'), '/fixture/movies/b.mp4'),
    array('two images one movie', array('a.jpg', 'b.jpg'), array('a.mp4'), array($imageBase . 'a.jpg', $imageBase . 'b.jpg'), '/fixture/movies/a.mp4'),
    array('dummy images only', array('dmy_h.jpg', 'dmy_h.jpg'), array(), array($dummyUrl, $dummyUrl), null)
);
foreach (array(false, true) as $isSP) {
    foreach ($cases as $case) {
        $gid = 42;
        $imagedata = $case[1] === null ? array() : array('filename' => array($gid => array(3 => $case[1])));
        $vertical_movies = array();
        foreach ($case[2] as $index => $filename) {
            $vertical_movies[] = array('filename' => $filename, 'filetype' => 'v' . ($index + 5), 'id' => $index + 1);
        }
        limitImageCounts($imagedata, $gid, $isSP);
        $slots = processImageData($imagedata, $gid, $isSP);
        $label = ($isSP ? 'SP ' : 'PC ') . $case[0];
        checkProfile(isset($slots['01010009']) && $slots['01010009'] === $case[3][0], $label . ': first detail URL');
        checkProfile(isset($slots['01010010']) && $slots['01010010'] === $case[3][1], $label . ': second detail URL');
        $gallery = array_diff_key($slots, array('01010009' => true, '01010010' => true));
        checkProfile($gallery === ($case[4] === null ? array() : array('01010003' => $case[4])), $label . ': only real surplus media belongs in gallery');
        checkDetailHtml($slots, $label);
    }
}
restore_error_handler();
echo 'PASS assertions=' . $checks . "\n";
