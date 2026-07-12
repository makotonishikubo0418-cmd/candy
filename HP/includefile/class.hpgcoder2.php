<?
/*********************************************************************
* コード変換クラス(本店用)(ソース直入版)
* ※変換用コード -> 表示用データ
* 2010-06-
*********************************************************************/

class HpgCoder{

	public $Converted = '';
	public $dbg_extractedHas03010093 = '';
	public $dbg_extractedTokenCount = '';
	public $dbg_data03010093 = '';
	public $dbg_outHas03010093 = '';
	public $dbg_outValue03010093 = '';

	function HpgCoder($source, $data){
	
		//変数として取得
		//$source = file_get_contents($source_file);
		
		//使用変換コードの抽出
		$debugNo = isset($_GET['no']) ? strval($_GET['no']) : '';
		$isDebugTarget = ($debugNo === '1241');

		if($isDebugTarget){
			error_log('[candy][HpgCoder] start __FILE__=' . __FILE__);
		}

		$result = preg_match_all('/(rep[0-9a-zA-Z]+eot)/', $source, $get_code);

		if($isDebugTarget){
			$hasToken = in_array('rep03010093eot', $get_code[0], true);
			$codeVal = isset($data['code']['03010093']) ? $data['code']['03010093'] : '(not set)';
			$this->dbg_extractedHas03010093 = $hasToken ? '1' : '0';
			$this->dbg_extractedTokenCount = strval(count($get_code[0]));
			$this->dbg_data03010093 = strval($codeVal);
			error_log('[candy][HpgCoder] extractedTokenHas03010093=' . ($hasToken ? '1' : '0') . ' tokenCount=' . count($get_code[0]) . ' data[03010093]=' . $codeVal);
		}
		
		//初期化
		$out_code = array();
		
		//コードから各処理を行う
		foreach($get_code[0] as $key => $val){
		
			switch($val){
				case "rep00010001eot": $out_code[$val] = $this->func00010001($data); break;
				case "rep00010007eot": $out_code[$val] = $this->func00010007($data); break;
				case "rep00010008eot": $out_code[$val] = $this->func00010008($data); break;
				case "rep00010009eot": $out_code[$val] = $this->func00010009($data); break;
				case "rep00010010eot": $out_code[$val] = $this->func00010010($data); break;
				case "rep00010011eot": $out_code[$val] = $this->func00010011($data); break;
				case "rep00010012eot": $out_code[$val] = $this->func00010012($data); break;
				case "rep00010013eot": $out_code[$val] = $this->func00010013($data); break;
				case "rep00010014eot": $out_code[$val] = $this->func00010014($data); break;
				case "rep00010015eot": $out_code[$val] = $this->func00010015($data); break;
				case "rep00010016eot": $out_code[$val] = $this->func00010016($data); break;
				case "rep00010017eot": $out_code[$val] = $this->func00010017($data); break;
				case "rep00010018eot": $out_code[$val] = $this->func00010018($data); break;
				case "rep00010019eot": $out_code[$val] = $this->func00010019($data); break;
				case "rep00010020eot": $out_code[$val] = $this->func00010020($data); break;
				case "rep00010021eot": $out_code[$val] = $this->func00010021($data); break;
				case "rep00010022eot": $out_code[$val] = $this->func00010022($data); break;
				case "rep00010023eot": $out_code[$val] = $this->func00010023($data); break;
				case "rep00010024eot": $out_code[$val] = $this->func00010024($data); break;
				case "rep00010025eot": $out_code[$val] = $this->func00010025($data); break;
				case "rep00010026eot": $out_code[$val] = $this->func00010026($data); break;
				case "rep00010027eot": $out_code[$val] = $this->func00010027($data); break;
				case "rep00010107eot": $out_code[$val] = $this->func00010107($data); break;
				case "rep00010108eot": $out_code[$val] = $this->func00010108($data); break;
				case "rep00010109eot": $out_code[$val] = $this->func00010109($data); break;
				case "rep00010110eot": $out_code[$val] = $this->func00010110($data); break;
				case "rep00010111eot": $out_code[$val] = $this->func00010111($data); break;
				case "rep00010112eot": $out_code[$val] = $this->func00010112($data); break;
				case "rep00010113eot": $out_code[$val] = $this->func00010113($data); break;
				case "rep00010114eot": $out_code[$val] = $this->func00010114($data); break;
				case "rep00010115eot": $out_code[$val] = $this->func00010115($data); break;
				case "rep00010116eot": $out_code[$val] = $this->func00010116($data); break;
				case "rep00010117eot": $out_code[$val] = $this->func00010117($data); break;
				case "rep00010118eot": $out_code[$val] = $this->func00010118($data); break;
				case "rep00010119eot": $out_code[$val] = $this->func00010119($data); break;
				case "rep00010120eot": $out_code[$val] = $this->func00010120($data); break;
				case "rep00010121eot": $out_code[$val] = $this->func00010121($data); break;
				case "rep00010122eot": $out_code[$val] = $this->func00010122($data); break;
				case "rep00010123eot": $out_code[$val] = $this->func00010123($data); break;
				case "rep00010124eot": $out_code[$val] = $this->func00010124($data); break;
				case "rep00010125eot": $out_code[$val] = $this->func00010125($data); break;
				case "rep00010126eot": $out_code[$val] = $this->func00010126($data); break;
				case "rep00010127eot": $out_code[$val] = $this->func00010127($data); break;
				case "rep00010250eot": $out_code[$val] = $this->func00010250($data); break;
				case "rep00010251eot": $out_code[$val] = $this->func00010251($data); break;
				case "rep00010252eot": $out_code[$val] = $this->func00010252($data); break;
				case "rep00010253eot": $out_code[$val] = $this->func00010253($data); break;
				case "rep00010254eot": $out_code[$val] = $this->func00010254($data); break;
				case "rep00010255eot": $out_code[$val] = $this->func00010255($data); break;
				case "rep00010260eot": $out_code[$val] = $this->func00010260($data); break;
				case "rep00010261eot": $out_code[$val] = $this->func00010261($data); break;
				case "rep00010265eot": $out_code[$val] = $this->func00010265($data); break;
				case "rep00010266eot": $out_code[$val] = $this->func00010266($data); break;
				case "rep00010267eot": $out_code[$val] = $this->func00010267($data); break;
				case "rep00010268eot": $out_code[$val] = $this->func00010268($data); break;
				case "rep00010269eot": $out_code[$val] = $this->func00010269($data); break;
				case "rep00010270eot": $out_code[$val] = $this->func00010270($data); break;
				case "rep00010271eot": $out_code[$val] = $this->func00010271($data); break;
				case "rep00010272eot": $out_code[$val] = $this->func00010272($data); break;
				case "rep00010273eot": $out_code[$val] = $this->func00010273($data); break;
				case "rep00010280eot": $out_code[$val] = $this->func00010280($data); break;
				case "rep00010281eot": $out_code[$val] = $this->func00010281($data); break;
				case "rep00010282eot": $out_code[$val] = $this->func00010282($data); break;
				case "rep00010283eot": $out_code[$val] = $this->func00010283($data); break;
				case "rep00010285eot": $out_code[$val] = $this->func00010285($data); break;
				case "rep00010286eot": $out_code[$val] = $this->func00010286($data); break;
				case "rep00010287eot": $out_code[$val] = $this->func00010287($data); break;
				case "rep00010288eot": $out_code[$val] = $this->func00010288($data); break;
				case "rep00010289eot": $out_code[$val] = $this->func00010289($data); break;
				case "rep00010290eot": $out_code[$val] = $this->func00010290($data); break;
				case "rep00010295eot": $out_code[$val] = $this->func00010295($data); break;
				case "rep00010296eot": $out_code[$val] = $this->func00010296($data); break;
				case "rep00010297eot": $out_code[$val] = $this->func00010297($data); break;
				case "rep00010300eot": $out_code[$val] = $this->func00010300($data); break;
				case "rep00010305eot": $out_code[$val] = $this->func00010305($data); break;
				case "rep00010306eot": $out_code[$val] = $this->func00010306($data); break;
				case "rep00010307eot": $out_code[$val] = $this->func00010307($data); break;
				case "rep00010308eot": $out_code[$val] = $this->func00010308($data); break;
				case "rep00010309eot": $out_code[$val] = $this->func00010309($data); break;
				case "rep00010310eot": $out_code[$val] = $this->func00010310($data); break;
				case "rep00010311eot": $out_code[$val] = $this->func00010311($data); break;
				case "rep00010312eot": $out_code[$val] = $this->func00010312($data); break;
				case "rep00010313eot": $out_code[$val] = $this->func00010313($data); break;
				case "rep00010314eot": $out_code[$val] = $this->func00010314($data); break;
				case "rep00010315eot": $out_code[$val] = $this->func00010315($data); break;
				case "rep00010316eot": $out_code[$val] = $this->func00010316($data); break;
				case "rep00010317eot": $out_code[$val] = $this->func00010317($data); break;
				case "rep00010318eot": $out_code[$val] = $this->func00010318($data); break;
				case "rep00010319eot": $out_code[$val] = $this->func00010319($data); break;
				case "rep00010320eot": $out_code[$val] = $this->func00010320($data); break;
				case "rep00010321eot": $out_code[$val] = $this->func00010321($data); break;
				case "rep00010322eot": $out_code[$val] = $this->func00010322($data); break;
				case "rep00010323eot": $out_code[$val] = $this->func00010323($data); break;
				case "rep00010324eot": $out_code[$val] = $this->func00010324($data); break;
				case "rep00010325eot": $out_code[$val] = $this->func00010325($data); break;
				case "rep00010326eot": $out_code[$val] = $this->func00010326($data); break;
				case "rep00010327eot": $out_code[$val] = $this->func00010327($data); break;
				case "rep00010328eot": $out_code[$val] = $this->func00010328($data); break;
				case "rep00010329eot": $out_code[$val] = $this->func00010329($data); break;
				case "rep00010330eot": $out_code[$val] = $this->func00010330($data); break;
				case "rep00010331eot": $out_code[$val] = $this->func00010331($data); break;
				case "rep00010332eot": $out_code[$val] = $this->func00010332($data); break;
				case "rep00010333eot": $out_code[$val] = $this->func00010333($data); break;
				case "rep00010334eot": $out_code[$val] = $this->func00010334($data); break;
				case "rep00010340eot": $out_code[$val] = $this->func00010340($data); break;
				case "rep00010341eot": $out_code[$val] = $this->func00010341($data); break;
				case "rep00010342eot": $out_code[$val] = $this->func00010342($data); break;
				case "rep00010343eot": $out_code[$val] = $this->func00010343($data); break;
				case "rep00010344eot": $out_code[$val] = $this->func00010344($data); break;
				case "rep00010345eot": $out_code[$val] = $this->func00010345($data); break;
				case "rep00010346eot": $out_code[$val] = $this->func00010346($data); break;
				case "rep00010347eot": $out_code[$val] = $this->func00010347($data); break;
				case "rep00010348eot": $out_code[$val] = $this->func00010348($data); break;
				case "rep00010349eot": $out_code[$val] = $this->func00010349($data); break;
				case "rep00010350eot": $out_code[$val] = $this->func00010350($data); break;
				case "rep00010351eot": $out_code[$val] = $this->func00010351($data); break;
				case "rep00010352eot": $out_code[$val] = $this->func00010352($data); break;
				case "rep00010353eot": $out_code[$val] = $this->func00010353($data); break;
				case "rep00010354eot": $out_code[$val] = $this->func00010354($data); break;
				case "rep00010355eot": $out_code[$val] = $this->func00010355($data); break;
				case "rep00010360eot": $out_code[$val] = $this->func00010360($data); break;
				case "rep00010361eot": $out_code[$val] = $this->func00010361($data); break;
				case "rep00010380eot": $out_code[$val] = $this->func00010380($data); break;
				case "rep00010381eot": $out_code[$val] = $this->func00010381($data); break;
				case "rep00010382eot": $out_code[$val] = $this->func00010382($data); break;
				case "rep00010383eot": $out_code[$val] = $this->func00010383($data); break;
				case "rep00010384eot": $out_code[$val] = $this->func00010384($data); break;
				case "rep00010385eot": $out_code[$val] = $this->func00010385($data); break;
				case "rep00010386eot": $out_code[$val] = $this->func00010386($data); break;
				case "rep00010387eot": $out_code[$val] = $this->func00010387($data); break;
				case "rep00010390eot": $out_code[$val] = $this->func00010390($data); break;
				case "rep00010391eot": $out_code[$val] = $this->func00010391($data); break;
				case "rep00010392eot": $out_code[$val] = $this->func00010392($data); break;
				case "rep00010400eot": $out_code[$val] = $this->func00010400($data); break;
				case "rep00010401eot": $out_code[$val] = $this->func00010401($data); break;
				case "rep00010402eot": $out_code[$val] = $this->func00010402($data); break;
				case "rep00010500eot": $out_code[$val] = $this->func00010500($data); break;
				case "rep00010501eot": $out_code[$val] = $this->func00010501($data); break;
				case "rep00010502eot": $out_code[$val] = $this->func00010502($data); break;
				case "rep00010503eot": $out_code[$val] = $this->func00010503($data); break;
				case "rep00010504eot": $out_code[$val] = $this->func00010504($data); break;
				case "rep00010505eot": $out_code[$val] = $this->func00010505($data); break;
				case "rep00010506eot": $out_code[$val] = $this->func00010506($data); break;
				case "rep00010507eot": $out_code[$val] = $this->func00010507($data); break;
				case "rep00010510eot": $out_code[$val] = $this->func00010510($data); break;
				case "rep00010511eot": $out_code[$val] = $this->func00010511($data); break;
				case "rep00010512eot": $out_code[$val] = $this->func00010512($data); break;
				case "rep00010513eot": $out_code[$val] = $this->func00010513($data); break;
				case "rep00010520eot": $out_code[$val] = $this->func00010520($data); break;
				case "rep00010521eot": $out_code[$val] = $this->func00010521($data); break;
				case "rep00010522eot": $out_code[$val] = $this->func00010522($data); break;
				case "rep00010523eot": $out_code[$val] = $this->func00010523($data); break;
				case "rep00010530eot": $out_code[$val] = $this->func00010530($data); break;
				case "rep00010531eot": $out_code[$val] = $this->func00010531($data); break;
				case "rep00010532eot": $out_code[$val] = $this->func00010532($data); break;
				case "rep00010533eot": $out_code[$val] = $this->func00010533($data); break;
				case "rep00010534eot": $out_code[$val] = $this->func00010534($data); break;
				case "rep00010535eot": $out_code[$val] = $this->func00010535($data); break;
				case "rep00010536eot": $out_code[$val] = $this->func00010536($data); break;
				case "rep00010537eot": $out_code[$val] = $this->func00010537($data); break;
				case "rep00010538eot": $out_code[$val] = $this->func00010538($data); break;
				case "rep00010539eot": $out_code[$val] = $this->func00010539($data); break;
				case "rep00010540eot": $out_code[$val] = $this->func00010540($data); break;
				case "rep00010541eot": $out_code[$val] = $this->func00010541($data); break;
				case "rep00010542eot": $out_code[$val] = $this->func00010542($data); break;
				case "rep00010543eot": $out_code[$val] = $this->func00010543($data); break;
				case "rep00010544eot": $out_code[$val] = $this->func00010544($data); break;
				case "rep00010545eot": $out_code[$val] = $this->func00010545($data); break;
				case "rep00010546eot": $out_code[$val] = $this->func00010546($data); break;
				case "rep00010547eot": $out_code[$val] = $this->func00010547($data); break;
				case "rep00010548eot": $out_code[$val] = $this->func00010548($data); break;
				case "rep00010549eot": $out_code[$val] = $this->func00010549($data); break;
				case "rep00010550eot": $out_code[$val] = $this->func00010550($data); break;
				case "rep00010551eot": $out_code[$val] = $this->func00010551($data); break;
				case "rep00010552eot": $out_code[$val] = $this->func00010552($data); break;
				case "rep00010553eot": $out_code[$val] = $this->func00010553($data); break;
				case "rep00010554eot": $out_code[$val] = $this->func00010554($data); break;
				case "rep00010555eot": $out_code[$val] = $this->func00010555($data); break;
				case "rep00010556eot": $out_code[$val] = $this->func00010556($data); break;
				case "rep00010557eot": $out_code[$val] = $this->func00010557($data); break;
				case "rep00010558eot": $out_code[$val] = $this->func00010558($data); break;
				case "rep00010559eot": $out_code[$val] = $this->func00010559($data); break;
				case "rep00010560eot": $out_code[$val] = $this->func00010560($data); break;
				case "rep00010561eot": $out_code[$val] = $this->func00010561($data); break;
				case "rep00010562eot": $out_code[$val] = $this->func00010562($data); break;
				case "rep00010563eot": $out_code[$val] = $this->func00010563($data); break;
				case "rep00010564eot": $out_code[$val] = $this->func00010564($data); break;
				case "rep00010565eot": $out_code[$val] = $this->func00010565($data); break;
				case "rep00010566eot": $out_code[$val] = $this->func00010566($data); break;
				case "rep00010567eot": $out_code[$val] = $this->func00010567($data); break;
				case "rep00010568eot": $out_code[$val] = $this->func00010568($data); break;
				case "rep00010569eot": $out_code[$val] = $this->func00010569($data); break;
				case "rep00010570eot": $out_code[$val] = $this->func00010570($data); break;
				case "rep00010571eot": $out_code[$val] = $this->func00010571($data); break;
				case "rep00010572eot": $out_code[$val] = $this->func00010572($data); break;
				case "rep00010573eot": $out_code[$val] = $this->func00010573($data); break;
				case "rep00010574eot": $out_code[$val] = $this->func00010574($data); break;
				case "rep00010575eot": $out_code[$val] = $this->func00010575($data); break;
				case "rep00010576eot": $out_code[$val] = $this->func00010576($data); break;
				case "rep00010577eot": $out_code[$val] = $this->func00010577($data); break;
				case "rep00010578eot": $out_code[$val] = $this->func00010578($data); break;
				case "rep00010579eot": $out_code[$val] = $this->func00010579($data); break;
				case "rep00010580eot": $out_code[$val] = $this->func00010580($data); break;
				case "rep00010581eot": $out_code[$val] = $this->func00010581($data); break;
				case "rep00010582eot": $out_code[$val] = $this->func00010582($data); break;
				case "rep00010583eot": $out_code[$val] = $this->func00010583($data); break;
				case "rep00010584eot": $out_code[$val] = $this->func00010584($data); break;
				case "rep00010585eot": $out_code[$val] = $this->func00010585($data); break;
				case "rep00010586eot": $out_code[$val] = $this->func00010586($data); break;
				case "rep00010587eot": $out_code[$val] = $this->func00010587($data); break;
				case "rep00010588eot": $out_code[$val] = $this->func00010588($data); break;
				case "rep00010589eot": $out_code[$val] = $this->func00010589($data); break;
				case "rep00010590eot": $out_code[$val] = $this->func00010590($data); break;
				case "rep00010591eot": $out_code[$val] = $this->func00010591($data); break;
				case "rep00010592eot": $out_code[$val] = $this->func00010592($data); break;
				case "rep00010593eot": $out_code[$val] = $this->func00010593($data); break;
				case "rep00010594eot": $out_code[$val] = $this->func00010594($data); break;
				case "rep00010595eot": $out_code[$val] = $this->func00010595($data); break;
				case "rep00010596eot": $out_code[$val] = $this->func00010596($data); break;
				case "rep00010597eot": $out_code[$val] = $this->func00010597($data); break;
				case "rep00010598eot": $out_code[$val] = $this->func00010598($data); break;
				case "rep00010599eot": $out_code[$val] = $this->func00010599($data); break;
				case "rep00010600eot": $out_code[$val] = $this->func00010600($data); break;
				case "rep00010601eot": $out_code[$val] = $this->func00010601($data); break;
				case "rep00010602eot": $out_code[$val] = $this->func00010602($data); break;
				case "rep00010603eot": $out_code[$val] = $this->func00010603($data); break;
				case "rep00010604eot": $out_code[$val] = $this->func00010604($data); break;
				case "rep00010605eot": $out_code[$val] = $this->func00010605($data); break;
				case "rep00010606eot": $out_code[$val] = $this->func00010606($data); break;
				case "rep00010607eot": $out_code[$val] = $this->func00010607($data); break;
				case "rep00010608eot": $out_code[$val] = $this->func00010608($data); break;
				case "rep00010609eot": $out_code[$val] = $this->func00010609($data); break;
				case "rep00010610eot": $out_code[$val] = $this->func00010610($data); break;
				case "rep00010611eot": $out_code[$val] = $this->func00010611($data); break;
				case "rep00010620eot": $out_code[$val] = $this->func00010620($data); break;
				case "rep00010621eot": $out_code[$val] = $this->func00010621($data); break;
				case "rep00010622eot": $out_code[$val] = $this->func00010622($data); break;
				case "rep00010623eot": $out_code[$val] = $this->func00010623($data); break;
				case "rep00010624eot": $out_code[$val] = $this->func00010624($data); break;
				case "rep00010625eot": $out_code[$val] = $this->func00010625($data); break;
				case "rep00010626eot": $out_code[$val] = $this->func00010626($data); break;
				case "rep00010627eot": $out_code[$val] = $this->func00010627($data); break;
				case "rep00010630eot": $out_code[$val] = $this->func00010630($data); break;
				case "rep00010640eot": $out_code[$val] = $this->func00010640($data); break;
				case "rep00010641eot": $out_code[$val] = $this->func00010641($data); break;
				case "rep00010642eot": $out_code[$val] = $this->func00010642($data); break;
				case "rep00010643eot": $out_code[$val] = $this->func00010643($data); break;
				case "rep00010651eot": $out_code[$val] = $this->func00010651($data); break;
				case "rep00010652eot": $out_code[$val] = $this->func00010652($data); break;
				case "rep00010653eot": $out_code[$val] = $this->func00010653($data); break;
				case "rep00010654eot": $out_code[$val] = $this->func00010654($data); break;
				case "rep00010660eot": $out_code[$val] = $this->func00010660($data); break;
				case "rep00010661eot": $out_code[$val] = $this->func00010661($data); break;
				case "rep00010670eot": $out_code[$val] = $this->func00010670($data); break;
				case "rep00010671eot": $out_code[$val] = $this->func00010671($data); break;
				case "rep00010672eot": $out_code[$val] = $this->func00010672($data); break;
				case "rep00010680eot": $out_code[$val] = $this->func00010680($data); break;
				case "rep00010681eot": $out_code[$val] = $this->func00010681($data); break;
				case "rep00010682eot": $out_code[$val] = $this->func00010682($data); break;
				case "rep00010690eot": $out_code[$val] = $this->func00010690($data); break;
				case "rep00010691eot": $out_code[$val] = $this->func00010691($data); break;
				case "rep00010692eot": $out_code[$val] = $this->func00010692($data); break;
				case "rep00010693eot": $out_code[$val] = $this->func00010693($data); break;
				case "rep00010694eot": $out_code[$val] = $this->func00010694($data); break;
				case "rep00010695eot": $out_code[$val] = $this->func00010695($data); break;
				case "rep00010696eot": $out_code[$val] = $this->func00010696($data); break;
				case "rep00010697eot": $out_code[$val] = $this->func00010697($data); break;
				case "rep00010698eot": $out_code[$val] = $this->func00010698($data); break;
				case "rep00010699eot": $out_code[$val] = $this->func00010699($data); break;
				case "rep00010700eot": $out_code[$val] = $this->func00010700($data); break;
				case "rep00010701eot": $out_code[$val] = $this->func00010701($data); break;
				case "rep00010702eot": $out_code[$val] = $this->func00010702($data); break;
				case "rep00010705eot": $out_code[$val] = $this->func00010705($data); break;
				case "rep00010706eot": $out_code[$val] = $this->func00010706($data); break;
				case "rep00010707eot": $out_code[$val] = $this->func00010707($data); break;
				case "rep00010710eot": $out_code[$val] = $this->func00010710($data); break;
				case "rep00010711eot": $out_code[$val] = $this->func00010711($data); break;
				case "rep00010712eot": $out_code[$val] = $this->func00010712($data); break;
				case "rep00010713eot": $out_code[$val] = $this->func00010713($data); break;
				case "rep00010720eot": $out_code[$val] = $this->func00010720($data); break;
				case "rep00010721eot": $out_code[$val] = $this->func00010721($data); break;
				case "rep00010722eot": $out_code[$val] = $this->func00010722($data); break;
				case "rep00010725eot": $out_code[$val] = $this->func00010725($data); break;
				case "rep00010726eot": $out_code[$val] = $this->func00010726($data); break;
				case "rep00010727eot": $out_code[$val] = $this->func00010727($data); break;
				case "rep00010730eot": $out_code[$val] = $this->func00010730($data); break;
				case "rep00010731eot": $out_code[$val] = $this->func00010731($data); break;
				case "rep00010732eot": $out_code[$val] = $this->func00010732($data); break;
				case "rep00010735eot": $out_code[$val] = $this->func00010735($data); break;
				case "rep00010736eot": $out_code[$val] = $this->func00010736($data); break;
				case "rep00010737eot": $out_code[$val] = $this->func00010737($data); break;
				case "rep00010740eot": $out_code[$val] = $this->func00010740($data); break;
				case "rep00010741eot": $out_code[$val] = $this->func00010741($data); break;
				case "rep00010750eot": $out_code[$val] = $this->func00010750($data); break;
				case "rep00010751eot": $out_code[$val] = $this->func00010751($data); break;
				case "rep00010752eot": $out_code[$val] = $this->func00010752($data); break;
				case "rep00010760eot": $out_code[$val] = $this->func00010760($data); break;
				case "rep00010761eot": $out_code[$val] = $this->func00010761($data); break;
				case "rep00010762eot": $out_code[$val] = $this->func00010762($data); break;
				case "rep00010770eot": $out_code[$val] = $this->func00010770($data); break;
				case "rep00010771eot": $out_code[$val] = $this->func00010771($data); break;
				case "rep00010772eot": $out_code[$val] = $this->func00010772($data); break;
				case "rep01010001eot": $out_code[$val] = $this->func01010001($data); break;
				case "rep01010002eot": $out_code[$val] = $this->func01010002($data); break;
				case "rep01010003eot": $out_code[$val] = $this->func01010003($data); break;
				case "rep01010004eot": $out_code[$val] = $this->func01010004($data); break;
				case "rep01010005eot": $out_code[$val] = $this->func01010005($data); break;
				case "rep01010006eot": $out_code[$val] = $this->func01010006($data); break;
				case "rep01010007eot": $out_code[$val] = $this->func01010007($data); break;
				case "rep01010008eot": $out_code[$val] = $this->func01010008($data); break;
				case "rep01010009eot": $out_code[$val] = $this->func01010009($data); break;
				case "rep01010010eot": $out_code[$val] = $this->func01010010($data); break;
				case "rep01010011eot": $out_code[$val] = $this->func01010011($data); break;
				case "rep01010012eot": $out_code[$val] = $this->func01010012($data); break;
				case "rep01010013eot": $out_code[$val] = $this->func01010013($data); break;
				case "rep01010014eot": $out_code[$val] = $this->func01010014($data); break;
				case "rep01010015eot": $out_code[$val] = $this->func01010015($data); break;
				case "rep01010016eot": $out_code[$val] = $this->func01010016($data); break;
				case "rep01010017eot": $out_code[$val] = $this->func01010017($data); break;
				case "rep01010018eot": $out_code[$val] = $this->func01010018($data); break;
				case "rep01010019eot": $out_code[$val] = $this->func01010019($data); break;
				case "rep01010020eot": $out_code[$val] = $this->func01010020($data); break;
				case "rep01010021eot": $out_code[$val] = $this->func01010021($data); break;
				case "rep01010022eot": $out_code[$val] = $this->func01010022($data); break;
				case "rep01010023eot": $out_code[$val] = $this->func01010023($data); break;
				case "rep01010024eot": $out_code[$val] = $this->func01010024($data); break;
				case "rep01010025eot": $out_code[$val] = $this->func01010025($data); break;
				case "rep01010026eot": $out_code[$val] = $this->func01010026($data); break;
				case "rep01010027eot": $out_code[$val] = $this->func01010027($data); break;
				case "rep01010030eot": $out_code[$val] = $this->func01010030($data); break;
				case "rep01010035eot": $out_code[$val] = $this->func01010035($data); break;
				case "rep01010036eot": $out_code[$val] = $this->func01010036($data); break;
				case "rep01010037eot": $out_code[$val] = $this->func01010037($data); break;
				case "rep01010040eot": $out_code[$val] = $this->func01010040($data); break;
				case "rep01010045eot": $out_code[$val] = $this->func01010045($data); break;
				case "rep01010050eot": $out_code[$val] = $this->func01010050($data); break;
				case "rep01010055eot": $out_code[$val] = $this->func01010055($data); break;
				case "rep01010056eot": $out_code[$val] = $this->func01010056($data); break;
				case "rep01010057eot": $out_code[$val] = $this->func01010057($data); break;
				case "rep01010060eot": $out_code[$val] = $this->func01010060($data); break;
				case "rep01010061eot": $out_code[$val] = $this->func01010061($data); break;
				case "rep01010062eot": $out_code[$val] = $this->func01010062($data); break;
				case "rep01010063eot": $out_code[$val] = $this->func01010063($data); break;
				case "rep01010064eot": $out_code[$val] = $this->func01010064($data); break;
				case "rep01010065eot": $out_code[$val] = $this->func01010065($data); break;
				case "rep01010066eot": $out_code[$val] = $this->func01010066($data); break;
				case "rep01010067eot": $out_code[$val] = $this->func01010067($data); break;
				case "rep01010068eot": $out_code[$val] = $this->func01010068($data); break;
				case "rep01010069eot": $out_code[$val] = $this->func01010069($data); break;
				case "rep01010070eot": $out_code[$val] = $this->func01010070($data); break;
				case "rep01010075eot": $out_code[$val] = $this->func01010075($data); break;
				case "rep01010076eot": $out_code[$val] = $this->func01010076($data); break;
				case "rep01010080eot": $out_code[$val] = $this->func01010080($data); break;
				case "rep01010081eot": $out_code[$val] = $this->func01010081($data); break;
				case "rep01010082eot": $out_code[$val] = $this->func01010082($data); break;
				case "rep01010083eot": $out_code[$val] = $this->func01010083($data); break;
				case "rep01010084eot": $out_code[$val] = $this->func01010084($data); break;
				case "rep01010085eot": $out_code[$val] = $this->func01010085($data); break;
				case "rep01010091eot": $out_code[$val] = $this->func01010091($data); break;
				case "rep01010092eot": $out_code[$val] = $this->func01010092($data); break;
				case "rep01010093eot": $out_code[$val] = $this->func01010093($data); break;
				case "rep01010094eot": $out_code[$val] = $this->func01010094($data); break;
				case "rep01010095eot": $out_code[$val] = $this->func01010095($data); break;
				case "rep01010100eot": $out_code[$val] = $this->func01010100($data); break;
				case "rep01010105eot": $out_code[$val] = $this->func01010105($data); break;
				case "rep01010101eot": $out_code[$val] = $this->func01010101($data); break;
				case "rep01010110eot": $out_code[$val] = $this->func01010110($data); break;
				case "rep01010111eot": $out_code[$val] = $this->func01010111($data); break;
				case "rep01010112eot": $out_code[$val] = $this->func01010112($data); break;
				case "rep01010113eot": $out_code[$val] = $this->func01010113($data); break;
				case "rep01010114eot": $out_code[$val] = $this->func01010114($data); break;
				case "rep01010115eot": $out_code[$val] = $this->func01010115($data); break;
				case "rep01010120eot": $out_code[$val] = $this->func01010120($data); break;
				case "rep01010200eot": $out_code[$val] = $this->func01010200($data); break;
				case "rep01010201eot": $out_code[$val] = $this->func01010201($data); break;
				case "rep01010202eot": $out_code[$val] = $this->func01010202($data); break;
				case "rep01010203eot": $out_code[$val] = $this->func01010203($data); break;
				case "rep01010221eot": $out_code[$val] = $this->func01010221($data); break;
				case "rep01010222eot": $out_code[$val] = $this->func01010222($data); break;
				case "rep01010223eot": $out_code[$val] = $this->func01010223($data); break;
				case "rep01010224eot": $out_code[$val] = $this->func01010224($data); break;
				case "rep01010225eot": $out_code[$val] = $this->func01010225($data); break;
				case "rep01010226eot": $out_code[$val] = $this->func01010226($data); break;
				case "rep01010227eot": $out_code[$val] = $this->func01010227($data); break;
				case "rep01010228eot": $out_code[$val] = $this->func01010228($data); break;
				case "rep01010229eot": $out_code[$val] = $this->func01010229($data); break;
				case "rep01010230eot": $out_code[$val] = $this->func01010230($data); break;
				case "rep01010240eot": $out_code[$val] = $this->func01010240($data); break;
				case "rep01010250eot": $out_code[$val] = $this->func01010250($data); break;
				case "rep01010251eot": $out_code[$val] = $this->func01010251($data); break;
				case "rep01010252eot": $out_code[$val] = $this->func01010252($data); break;
				case "rep01010253eot": $out_code[$val] = $this->func01010253($data); break;
				case "rep01010254eot": $out_code[$val] = $this->func01010254($data); break;
				case "rep01010255eot": $out_code[$val] = $this->func01010255($data); break;
				case "rep01010256eot": $out_code[$val] = $this->func01010256($data); break;
				case "rep01010257eot": $out_code[$val] = $this->func01010257($data); break;
				case "rep01010258eot": $out_code[$val] = $this->func01010258($data); break;
				case "rep01010259eot": $out_code[$val] = $this->func01010259($data); break;
				case "rep01010260eot": $out_code[$val] = $this->func01010260($data); break;
				case "rep01010261eot": $out_code[$val] = $this->func01010261($data); break;
				case "rep01010270eot": $out_code[$val] = $this->func01010270($data); break;
				case "rep01010271eot": $out_code[$val] = $this->func01010271($data); break;
				case "rep01010273eot": $out_code[$val] = $this->func01010273($data); break;
				case "rep01010274eot": $out_code[$val] = $this->func01010274($data); break;
				case "rep01010275eot": $out_code[$val] = $this->func01010275($data); break;
				case "rep01010280eot": $out_code[$val] = $this->func01010280($data); break;
				case "rep01010290eot": $out_code[$val] = $this->func01010290($data); break;
				case "rep01010291eot": $out_code[$val] = $this->func01010291($data); break;
				case "rep01010292eot": $out_code[$val] = $this->func01010292($data); break;
				case "rep01010293eot": $out_code[$val] = $this->func01010293($data); break;
				case "rep01010294eot": $out_code[$val] = $this->func01010294($data); break;
				case "rep01010295eot": $out_code[$val] = $this->func01010295($data); break;
				case "rep01010296eot": $out_code[$val] = $this->func01010296($data); break;
				case "rep01010297eot": $out_code[$val] = $this->func01010297($data); break;
				case "rep01010298eot": $out_code[$val] = $this->func01010298($data); break;
				case "rep01010300eot": $out_code[$val] = $this->func01010300($data); break;
				case "rep01010301eot": $out_code[$val] = $this->func01010301($data); break;
				case "rep01010302eot": $out_code[$val] = $this->func01010302($data); break;
				case "rep01010305eot": $out_code[$val] = $this->func01010305($data); break;
				case "rep01010306eot": $out_code[$val] = $this->func01010306($data); break;
				case "rep01010307eot": $out_code[$val] = $this->func01010307($data); break;
				case "rep01010310eot": $out_code[$val] = $this->func01010310($data); break;
				case "rep01010311eot": $out_code[$val] = $this->func01010311($data); break;
				case "rep01010312eot": $out_code[$val] = $this->func01010312($data); break;
				case "rep01010321eot": $out_code[$val] = $this->func01010321($data); break;
				case "rep01010327eot": $out_code[$val] = $this->func01010327($data); break;
				case "rep01010328eot": $out_code[$val] = $this->func01010328($data); break;
				case "rep01010329eot": $out_code[$val] = $this->func01010329($data); break;
				case "rep01010330eot": $out_code[$val] = $this->func01010330($data); break;
				case "rep01010331eot": $out_code[$val] = $this->func01010331($data); break;
				case "rep01010332eot": $out_code[$val] = $this->func01010332($data); break;
				case "rep01010333eot": $out_code[$val] = $this->func01010333($data); break;
				case "rep01010334eot": $out_code[$val] = $this->func01010334($data); break;
				case "rep03010007eot": $out_code[$val] = $this->func03010007($data); break;
				case "rep03010008eot": $out_code[$val] = $this->func03010008($data); break;
				case "rep03010009eot": $out_code[$val] = $this->func03010009($data); break;
				case "rep03010010eot": $out_code[$val] = $this->func03010010($data); break;
				case "rep03010011eot": $out_code[$val] = $this->func03010011($data); break;
				case "rep03010012eot": $out_code[$val] = $this->func03010012($data); break;
				case "rep03010013eot": $out_code[$val] = $this->func03010013($data); break;
				case "rep03010014eot": $out_code[$val] = $this->func03010014($data); break;
				case "rep03010015eot": $out_code[$val] = $this->func03010015($data); break;
				case "rep03010016eot": $out_code[$val] = $this->func03010016($data); break;
				case "rep03010017eot": $out_code[$val] = $this->func03010017($data); break;
				case "rep03010018eot": $out_code[$val] = $this->func03010018($data); break;
				case "rep03010019eot": $out_code[$val] = $this->func03010019($data); break;
				case "rep03010020eot": $out_code[$val] = $this->func03010020($data); break;
				case "rep03010021eot": $out_code[$val] = $this->func03010021($data); break;
				case "rep03010022eot": $out_code[$val] = $this->func03010022($data); break;
				case "rep03010023eot": $out_code[$val] = $this->func03010023($data); break;
				case "rep03010024eot": $out_code[$val] = $this->func03010024($data); break;
				case "rep03010025eot": $out_code[$val] = $this->func03010025($data); break;
				case "rep03010026eot": $out_code[$val] = $this->func03010026($data); break;
				case "rep03010027eot": $out_code[$val] = $this->func03010027($data); break;
				case "rep03010030eot": $out_code[$val] = $this->func03010030($data); break;
				case "rep03010031eot": $out_code[$val] = $this->func03010031($data); break;
				case "rep03010032eot": $out_code[$val] = $this->func03010032($data); break;
				case "rep03010035eot": $out_code[$val] = $this->func03010035($data); break;
				case "rep03010036eot": $out_code[$val] = $this->func03010036($data); break;
				case "rep03010037eot": $out_code[$val] = $this->func03010037($data); break;
				case "rep03010038eot": $out_code[$val] = $this->func03010038($data); break;
				case "rep03010040eot": $out_code[$val] = $this->func03010040($data); break;
				case "rep03010041eot": $out_code[$val] = $this->func03010041($data); break;
				case "rep03010042eot": $out_code[$val] = $this->func03010042($data); break;
				case "rep03010043eot": $out_code[$val] = $this->func03010043($data); break;
				case "rep03010050eot": $out_code[$val] = $this->func03010050($data); break;
				case "rep03010051eot": $out_code[$val] = $this->func03010051($data); break;
				case "rep03010052eot": $out_code[$val] = $this->func03010052($data); break;
				case "rep03010053eot": $out_code[$val] = $this->func03010053($data); break;
				case "rep03010055eot": $out_code[$val] = $this->func03010055($data); break;
				case "rep03010060eot": $out_code[$val] = $this->func03010060($data); break;
				case "rep03010061eot": $out_code[$val] = $this->func03010061($data); break;
				case "rep03010062eot": $out_code[$val] = $this->func03010062($data); break;
				case "rep03010065eot": $out_code[$val] = $this->func03010065($data); break;
				case "rep03010068eot": $out_code[$val] = $this->func03010068($data); break;
				case "rep03010069eot": $out_code[$val] = $this->func03010069($data); break;
				case "rep03010070eot": $out_code[$val] = $this->func03010070($data); break;
				case "rep03010071eot": $out_code[$val] = $this->func03010071($data); break;
				case "rep03010072eot": $out_code[$val] = $this->func03010072($data); break;
				case "rep03010073eot": $out_code[$val] = $this->func03010073($data); break;
				case "rep03010074eot": $out_code[$val] = $this->func03010074($data); break;
				case "rep03010075eot": $out_code[$val] = $this->func03010075($data); break;
				case "rep03010076eot": $out_code[$val] = $this->func03010076($data); break;
				case "rep03010077eot": $out_code[$val] = $this->func03010077($data); break;
				case "rep03010080eot": $out_code[$val] = $this->func03010080($data); break;
				case "rep03010081eot": $out_code[$val] = $this->func03010081($data); break;
				case "rep03010082eot": $out_code[$val] = $this->func03010082($data); break;
				case "rep03010083eot": $out_code[$val] = $this->func03010083($data); break;
				case "rep03010084eot": $out_code[$val] = $this->func03010084($data); break;
				case "rep03010085eot": $out_code[$val] = $this->func03010085($data); break;
				case "rep03010086eot": $out_code[$val] = $this->func03010086($data); break;
				case "rep03010087eot": $out_code[$val] = $this->func03010087($data); break;
				case "rep03010088eot": $out_code[$val] = $this->func03010088($data); break;
				case "rep03010089eot": $out_code[$val] = $this->func03010089($data); break;
				case "rep03010090eot": $out_code[$val] = $this->func03010090($data); break;
				case "rep03010091eot": $out_code[$val] = $this->func03010091($data); break;
				case "rep03010092eot": $out_code[$val] = $this->func03010092($data); break;
				case "rep03010093eot": $out_code[$val] = $this->func03010093($data); break;
				case "rep03010100eot": $out_code[$val] = $this->func03010100($data); break;
				case "rep03010101eot": $out_code[$val] = $this->func03010101($data); break;
				case "rep03010102eot": $out_code[$val] = $this->func03010102($data); break;
				case "rep03010103eot": $out_code[$val] = $this->func03010103($data); break;
				case "rep03010104eot": $out_code[$val] = $this->func03010104($data); break;
				case "rep03010105eot": $out_code[$val] = $this->func03010105($data); break;
				case "rep03010106eot": $out_code[$val] = $this->func03010106($data); break;
				case "rep03010107eot": $out_code[$val] = $this->func03010107($data); break;
				case "rep03010108eot": $out_code[$val] = $this->func03010108($data); break;
				case "rep03010109eot": $out_code[$val] = $this->func03010109($data); break;
				case "rep03010110eot": $out_code[$val] = $this->func03010110($data); break;
				case "rep03010111eot": $out_code[$val] = $this->func03010111($data); break;
				case "rep03010115eot": $out_code[$val] = $this->func03010115($data); break;
				case "rep03010116eot": $out_code[$val] = $this->func03010116($data); break;
				case "rep03010117eot": $out_code[$val] = $this->func03010117($data); break;
				case "rep03010118eot": $out_code[$val] = $this->func03010118($data); break;
				case "rep03010119eot": $out_code[$val] = $this->func03010119($data); break;
				case "rep03010120eot": $out_code[$val] = $this->func03010120($data); break;
				case "rep03010121eot": $out_code[$val] = $this->func03010121($data); break;
				case "rep03010122eot": $out_code[$val] = $this->func03010122($data); break;
				case "rep03010123eot": $out_code[$val] = $this->func03010123($data); break;
				case "rep03010130eot": $out_code[$val] = $this->func03010130($data); break;
				case "rep03010131eot": $out_code[$val] = $this->func03010131($data); break;
				case "rep03010140eot": $out_code[$val] = $this->func03010140($data); break;
				case "rep03010150eot": $out_code[$val] = $this->func03010150($data); break;
				case "rep03010151eot": $out_code[$val] = $this->func03010151($data); break;
				case "rep03010152eot": $out_code[$val] = $this->func03010152($data); break;
				case "rep03010200eot": $out_code[$val] = $this->func03010200($data); break;
				case "rep03010210eot": $out_code[$val] = $this->func03010210($data); break;
				case "rep03010211eot": $out_code[$val] = $this->func03010211($data); break;
				case "rep03010212eot": $out_code[$val] = $this->func03010212($data); break;
				case "rep03010213eot": $out_code[$val] = $this->func03010213($data); break;
				case "rep03010214eot": $out_code[$val] = $this->func03010214($data); break;
				case "rep03010215eot": $out_code[$val] = $this->func03010215($data); break;
				case "rep03010216eot": $out_code[$val] = $this->func03010216($data); break;
				case "rep03010223eot": $out_code[$val] = $this->func03010223($data); break;
				case "rep03010224eot": $out_code[$val] = $this->func03010224($data); break;
				case "rep03010225eot": $out_code[$val] = $this->func03010225($data); break;
				case "rep03010226eot": $out_code[$val] = $this->func03010226($data); break;
				case "rep03010227eot": $out_code[$val] = $this->func03010227($data); break;
				case "rep03010228eot": $out_code[$val] = $this->func03010228($data); break;
				case "rep03010229eot": $out_code[$val] = $this->func03010229($data); break;
				case "rep03010240eot": $out_code[$val] = $this->func03010240($data); break;
				case "rep03010241eot": $out_code[$val] = $this->func03010241($data); break;
				case "rep03010250eot": $out_code[$val] = $this->func03010250($data); break;
				case "rep03010251eot": $out_code[$val] = $this->func03010251($data); break;
				case "rep03010268eot": $out_code[$val] = $this->func03010268($data); break;
				case "rep03010269eot": $out_code[$val] = $this->func03010269($data); break;
				case "rep03010270eot": $out_code[$val] = $this->func03010270($data); break;
				case "rep03010290eot": $out_code[$val] = $this->func03010290($data); break;
				case "rep03010291eot": $out_code[$val] = $this->func03010291($data); break;
				case "rep03010292eot": $out_code[$val] = $this->func03010292($data); break;
				case "rep03010293eot": $out_code[$val] = $this->func03010293($data); break;
				case "rep03010300eot": $out_code[$val] = $this->func03010300($data); break;
				case "rep03010301eot": $out_code[$val] = $this->func03010301($data); break;
				case "rep03010302eot": $out_code[$val] = $this->func03010302($data); break;
				case "rep03010311eot": $out_code[$val] = $this->func03010311($data); break;
				case "rep00040001eot": $out_code[$val] = $this->func00040001($data); break;
				case "rep00040002eot": $out_code[$val] = $this->func00040002($data); break;
				case "rep00040003eot": $out_code[$val] = $this->func00040003($data); break;
				case "rep00040004eot": $out_code[$val] = $this->func00040004($data); break;
				case "rep00040005eot": $out_code[$val] = $this->func00040005($data); break;
				case "rep00040006eot": $out_code[$val] = $this->func00040006($data); break;
				case "rep00040007eot": $out_code[$val] = $this->func00040007($data); break;
			}
			
		}
		
		if($isDebugTarget){
			$hasOut = array_key_exists('rep03010093eot', $out_code);
			$valOut = $hasOut ? $out_code['rep03010093eot'] : '(missing)';
			$this->dbg_outHas03010093 = $hasOut ? '1' : '0';
			$this->dbg_outValue03010093 = strval($valOut);
			error_log('[candy][HpgCoder] out_code has rep03010093eot=' . ($hasOut ? '1' : '0') . ' out_value=' . $valOut);
		}

		//keyと値の再配列化
		$code_array = array_keys($out_code);
		$out_array  = array_values($out_code);
		
		//コード変換
		$this->Converted = str_replace($code_array, $out_array, $source);
		
	}
	
	
	//
	
	
	//各処理
function func00010001($data){ return $data['code']['00010001']; } // テロップテキスト
function func00010007($data){ return $data['code']['00010007']; } // バナー01(兼全ページ反映バナー)のキャプション太字
function func00010008($data){ return $data['code']['00010008']; } // バナー02のキャプション太字
function func00010009($data){ return $data['code']['00010009']; } // バナー03のキャプション太字
function func00010010($data){ return $data['code']['00010010']; } // バナー04のキャプション太字
function func00010011($data){ return $data['code']['00010011']; } // バナー05のキャプション太字
function func00010012($data){ return $data['code']['00010012']; } // バナー06のキャプション太字
function func00010013($data){ return $data['code']['00010013']; } // バナー07のキャプション太字
function func00010014($data){ return $data['code']['00010014']; } // バナー08のキャプション太字
function func00010015($data){ return $data['code']['00010015']; } // バナー09のキャプション太字
function func00010016($data){ return $data['code']['00010016']; } // バナー10のキャプション太字
function func00010017($data){ return $data['code']['00010017']; } // バナー11のキャプション太字
function func00010018($data){ return $data['code']['00010018']; } // バナー12のキャプション太字
function func00010019($data){ return $data['code']['00010019']; } // バナー13のキャプション太字
function func00010020($data){ return $data['code']['00010020']; } // バナー14のキャプション太字
function func00010021($data){ return $data['code']['00010021']; } // バナー15のキャプション太字
function func00010022($data){ return $data['code']['00010022']; } // バナー16のキャプション太字
function func00010023($data){ return $data['code']['00010023']; } // バナー17のキャプション太字
function func00010024($data){ return $data['code']['00010024']; } // バナー18のキャプション太字
function func00010025($data){ return $data['code']['00010025']; } // バナー19のキャプション太字
function func00010026($data){ return $data['code']['00010026']; } // バナー20のキャプション太字
function func00010027($data){ return $data['code']['00010027']; } // バナー21のキャプション太字
function func00010107($data){ return $data['code']['00010107']; } // バナー01(兼全ページ反映バナー)のキャプション
function func00010108($data){ return $data['code']['00010108']; } // バナー02のキャプション
function func00010109($data){ return $data['code']['00010109']; } // バナー03のキャプション
function func00010110($data){ return $data['code']['00010110']; } // バナー04のキャプション
function func00010111($data){ return $data['code']['00010111']; } // バナー05のキャプション
function func00010112($data){ return $data['code']['00010112']; } // バナー06のキャプション
function func00010113($data){ return $data['code']['00010113']; } // バナー07のキャプション
function func00010114($data){ return $data['code']['00010114']; } // バナー08のキャプション
function func00010115($data){ return $data['code']['00010115']; } // バナー09のキャプション
function func00010116($data){ return $data['code']['00010116']; } // バナー10のキャプション
function func00010117($data){ return $data['code']['00010117']; } // バナー11のキャプション
function func00010118($data){ return $data['code']['00010118']; } // バナー12のキャプション
function func00010119($data){ return $data['code']['00010119']; } // バナー13のキャプション
function func00010120($data){ return $data['code']['00010120']; } // バナー14のキャプション
function func00010121($data){ return $data['code']['00010121']; } // バナー15のキャプション
function func00010122($data){ return $data['code']['00010122']; } // バナー16のキャプション
function func00010123($data){ return $data['code']['00010123']; } // バナー17のキャプション
function func00010124($data){ return $data['code']['00010124']; } // バナー18のキャプション
function func00010125($data){ return $data['code']['00010125']; } // バナー19のキャプション
function func00010126($data){ return $data['code']['00010126']; } // バナー20のキャプション
function func00010127($data){ return $data['code']['00010127']; } // バナー21のキャプション
function func00010250($data){ return $data['code']['00010250']; } // バナー区切り1
function func00010251($data){ return $data['code']['00010251']; } // バナー区切り2
function func00010252($data){ return $data['code']['00010252']; } // バナー区切り3
function func00010253($data){ return $data['code']['00010253']; } // バナー区切り4
function func00010254($data){ return $data['code']['00010254']; } // バナー区切り5
function func00010255($data){ return $data['code']['00010255']; } // バナー区切り6
function func00010260($data){ return $data['code']['00010260']; } // リアルタイム予約状況アイコン名前
function func00010261($data){ return $data['code']['00010261']; } // リアルタイム予約状況アイコン3サイズ
function func00010265($data){ return $data['code']['00010265']; } // ニュース/新着 1行目ヘッドライン
function func00010266($data){ return $data['code']['00010266']; } // ニュース/新着2行目ヘッドライン
function func00010267($data){ return $data['code']['00010267']; } // ニュース/新着3行目ヘッドライン
function func00010268($data){ return $data['code']['00010268']; } // ニュース/新着4行目ヘッドライン
function func00010269($data){ return $data['code']['00010269']; } // ニュース/新着テキスト
function func00010270($data){ return $data['code']['00010270']; } // イベント情報タイトル1
function func00010271($data){ return $data['code']['00010271']; } // イベント情報タイトル2
function func00010272($data){ return $data['code']['00010272']; } // イベント情報タイトル3
function func00010273($data){ return $data['code']['00010273']; } // イベント情報タイトル4
function func00010280($data){ return $data['code']['00010280']; } // 個人イベント情報氏名1
function func00010281($data){ return $data['code']['00010281']; } // 個人イベント情報氏名2
function func00010282($data){ return $data['code']['00010282']; } // 個人イベント情報氏名3
function func00010283($data){ return $data['code']['00010283']; } // 個人イベント情報氏名4
function func00010285($data){ return $data['code']['00010285']; } // 新人入店速報日時名前1
function func00010286($data){ return $data['code']['00010286']; } // 新人入店速報日時名前2
function func00010287($data){ return $data['code']['00010287']; } // 新人入店速報日時名前3
function func00010288($data){ return $data['code']['00010288']; } // 新人入店速報日時名前4
function func00010289($data){ return $data['code']['00010289']; } // 新人入店速報テキスト
function func00010290($data){ return $data['code']['00010290']; } // 写メ日記最新書き込み名前+本文数文字
function func00010295($data){ return $data['code']['00010295']; } // 新着フォトグラフィー名前
function func00010296($data){ return $data['code']['00010296']; } // 新着フォトグラフィー名前2
function func00010297($data){ return $data['code']['00010297']; } // 新着フォトグラフィー名前3
function func00010300($data){ return $data['code']['00010300']; } // 新着グラビア名前
function func00010305($data){ return $data['code']['00010305']; } // スペシャルムービー更新日+名前1
function func00010306($data){ return $data['code']['00010306']; } // スペシャルムービー更新日+名前2
function func00010307($data){ return $data['code']['00010307']; } // スペシャルムービー更新日+名前3
function func00010308($data){ return $data['code']['00010308']; } // ランキングの集計期間(ex2010年6月27日～2010年7月3日)
function func00010309($data){ return $data['code']['00010309']; } // ランキングのタイトル 
function func00010310($data){ return $data['code']['00010310']; } // ランキング1位名前
function func00010311($data){ return $data['code']['00010311']; } // ランキング2位名前
function func00010312($data){ return $data['code']['00010312']; } // ランキング3位名前
function func00010313($data){ return $data['code']['00010313']; } // ランキング4位名前
function func00010314($data){ return $data['code']['00010314']; } // ランキング5位名前
function func00010315($data){ return $data['code']['00010315']; } // ランキング6位名前
function func00010316($data){ return $data['code']['00010316']; } // ランキング7位名前
function func00010317($data){ return $data['code']['00010317']; } // ランキング8位名前
function func00010318($data){ return $data['code']['00010318']; } // ランキング9位名前
function func00010319($data){ return $data['code']['00010319']; } // ランキング10位名前
function func00010320($data){ return $data['code']['00010320']; } // 女の子の名前(日本語)
function func00010321($data){ return $data['code']['00010321']; } // 女の子の年齢
function func00010322($data){ return $data['code']['00010322']; } // 身長
function func00010323($data){ return $data['code']['00010323']; } // バスト
function func00010324($data){ return $data['code']['00010324']; } // カップ
function func00010325($data){ return $data['code']['00010325']; } // ウエスト
function func00010326($data){ return $data['code']['00010326']; } // ヒップ
function func00010327($data){ return $data['code']['00010327']; } // 雰囲気
function func00010328($data){ return $data['code']['00010328']; } // タイプ
function func00010329($data){ return $data['code']['00010329']; } // 女の子のリード
function func00010330($data){ return $data['code']['00010330']; } // 女の子のボディコピー
function func00010331($data){ return $data['code']['00010331']; } // 名字ローマ字大文字
function func00010332($data){ return $data['code']['00010332']; } // 名前ローマ字大文字
function func00010333($data){ return $data['code']['00010333']; } // 名字ローマ字先頭だけ大文字
function func00010334($data){ return $data['code']['00010334']; } // 名前ローマ字先頭だけ大文字
function func00010340($data){ return $data['code']['00010340']; } // 女の子の出勤時間帯(ex. 17:00～LAST)
function func00010341($data){ return $data['code']['00010341']; } // 女の子の週間出勤予定(1日目・当日)
function func00010342($data){ return $data['code']['00010342']; } // 女の子の週間出勤予定(2日目)
function func00010343($data){ return $data['code']['00010343']; } // の子の週間出勤予定(3日目)
function func00010344($data){ return $data['code']['00010344']; } // 女の子の週間出勤予定(4日目)
function func00010345($data){ return $data['code']['00010345']; } // 女の子の週間出勤予定(5日目)
function func00010346($data){ return $data['code']['00010346']; } // 女の子の週間出勤予定(6日目)
function func00010347($data){ return $data['code']['00010347']; } // 女の子の週間出勤予定(7日目)
function func00010348($data){ return $data['code']['00010348']; } // この子のリアルタイム予約状況
function func00010349($data){ return $data['code']['00010349']; } // この子の指名料
function func00010350($data){ return $data['code']['00010350']; } // Q&Aテキスト
function func00010351($data){ return $data['code']['00010351']; } // 入店日(9999年99月99日)
function func00010352($data){ return $data['code']['00010352']; } // 写真更新日(9999年99月99日)
function func00010353($data){ return $data['code']['00010353']; } // この子が3P可能かどうかを表すテキスト「対応可」「未対応」
function func00010354($data){ return $data['code']['00010354']; } // 待ち時間(ex30分待ち・ご案内可能)
function func00010355($data){ return $data['code']['00010355']; } // 女の子の可能プレイ
function func00010360($data){ return $data['code']['00010360']; } // 割引情報ページタイトル
function func00010361($data){ return $data['code']['00010361']; } // 割引情報ページ本文
function func00010380($data){ return $data['code']['00010380']; } // トップバナーのイベントタイトルリスト
function func00010381($data){ return $data['code']['00010381']; } // トップバナーのイベントタイトルリスト2
function func00010382($data){ return $data['code']['00010382']; } // トップバナーのイベントタイトルリスト3
function func00010383($data){ return $data['code']['00010383']; } // トップバナーのイベントタイトルリスト4
function func00010384($data){ return $data['code']['00010384']; } // トップバナーのイベントタイトルリスト5
function func00010385($data){ return $data['code']['00010385']; } // イベントバナーアーカイブスイベント名リスト
function func00010386($data){ return $data['code']['00010386']; } // イベントバナーアーカイブス制作データリスト
function func00010387($data){ return $data['code']['00010387']; } // イベントバナーを持ち、表示可能な女の子の名前リスト
function func00010390($data){ return $data['code']['00010390']; } // リアルタイム予約用コメント
function func00010391($data){ return $data['code']['00010391']; } // ポップアップ画像表示画面テキストリード
function func00010392($data){ return $data['code']['00010392']; } // ポップアップ画像表示画面テキストボディ
function func00010400($data){ return $data['code']['00010400']; } // ニュース新着 更新日時(ex 2010年4月22日　20:55))
function func00010401($data){ return $data['code']['00010401']; } // ニュース新着 タイトル
function func00010402($data){ return $data['code']['00010402']; } // ニュース新着 本文
function func00010500($data){ return $data['code']['00010500']; } // トップページナビゲーション内のバナーコメント1
function func00010501($data){ return $data['code']['00010501']; } // トップページナビゲーション内のバナーコメント2
function func00010502($data){ return $data['code']['00010502']; } // プレイリストによるコメントリスト
function func00010503($data){ return $data['code']['00010503']; } // キャストイベントPRのコメントリスト
function func00010504($data){ return $data['code']['00010504']; } // ショップイベントPRのコメントリスト
function func00010505($data){ return $data['code']['00010505']; } // プレイリストによるタイトルリスト
function func00010506($data){ return $data['code']['00010506']; } // キャストイベントPRのタイトルリスト
function func00010507($data){ return $data['code']['00010507']; } // ショップイベントPRのタイトルリスト
function func00010510($data){ return $data['code']['00010510']; } // ニュース/新着1行目ヘッドライン日時(ex 10/06/06
function func00010511($data){ return $data['code']['00010511']; } // ニュース/新着2行目ヘッドライン日時(ex 10/06/06
function func00010512($data){ return $data['code']['00010512']; } // ニュース/新着3行目ヘッドライン日時(ex 10/06/06
function func00010513($data){ return $data['code']['00010513']; } // ニュース/新着4行目ヘッドライン日時(ex 10/06/06
function func00010520($data){ return $data['code']['00010520']; } // キャンペーン情報ヘッドライン1
function func00010521($data){ return $data['code']['00010521']; } // キャンペーン情報ヘッドライン2
function func00010522($data){ return $data['code']['00010522']; } // キャンペーン情報ヘッドライン3
function func00010523($data){ return $data['code']['00010523']; } // キャンペーン情報ヘッドライン4
function func00010530($data){ return $data['code']['00010530']; } // ランキング1位名前(ローマ字)
function func00010531($data){ return $data['code']['00010531']; } // ランキング2位名前(ローマ字)
function func00010532($data){ return $data['code']['00010532']; } // ランキング3位名前(ローマ字)
function func00010533($data){ return $data['code']['00010533']; } // ランキング4位名前(ローマ字)
function func00010534($data){ return $data['code']['00010534']; } // ランキング5位名前(ローマ字)
function func00010535($data){ return $data['code']['00010535']; } // ランキング6位名前(ローマ字)
function func00010536($data){ return $data['code']['00010536']; } // ランキング7位名前(ローマ字)
function func00010537($data){ return $data['code']['00010537']; } // ランキング8位名前(ローマ字)
function func00010538($data){ return $data['code']['00010538']; } // ランキング9位名前(ローマ字)
function func00010539($data){ return $data['code']['00010539']; } // ランキング10 位名前(ローマ字)
function func00010540($data){ return $data['code']['00010540']; } // ランキング1位年齢
function func00010541($data){ return $data['code']['00010541']; } // ランキング2位年齢
function func00010542($data){ return $data['code']['00010542']; } // ランキング3位年齢
function func00010543($data){ return $data['code']['00010543']; } // ランキング4位年齢
function func00010544($data){ return $data['code']['00010544']; } // ランキング5位年齢
function func00010545($data){ return $data['code']['00010545']; } // ランキング6位年齢
function func00010546($data){ return $data['code']['00010546']; } // ランキング7位年齢
function func00010547($data){ return $data['code']['00010547']; } // ランキング8位年齢
function func00010548($data){ return $data['code']['00010548']; } // ランキング9位年齢
function func00010549($data){ return $data['code']['00010549']; } // ランキング10位年齢
function func00010550($data){ return $data['code']['00010550']; } // ランキング1位カップ
function func00010551($data){ return $data['code']['00010551']; } // ランキング2位カップ
function func00010552($data){ return $data['code']['00010552']; } // ランキング3位カップ
function func00010553($data){ return $data['code']['00010553']; } // ランキング4位カップ
function func00010554($data){ return $data['code']['00010554']; } // ランキング5位カップ
function func00010555($data){ return $data['code']['00010555']; } // ランキング6位カップ
function func00010556($data){ return $data['code']['00010556']; } // ランキング7位カップ
function func00010557($data){ return $data['code']['00010557']; } // ランキング8位カップ
function func00010558($data){ return $data['code']['00010558']; } // ランキング9位カップ
function func00010559($data){ return $data['code']['00010559']; } // ランキング10位カップ
function func00010560($data){ return $data['code']['00010560']; } // ランキング1位名字(ローマ字)
function func00010561($data){ return $data['code']['00010561']; } // ランキング2位名字(ローマ字)
function func00010562($data){ return $data['code']['00010562']; } // ランキング3位名字(ローマ字)
function func00010563($data){ return $data['code']['00010563']; } // ランキング4位名字(ローマ字)
function func00010564($data){ return $data['code']['00010564']; } // ランキング5位名字(ローマ字)
function func00010565($data){ return $data['code']['00010565']; } // ランキング6位名字(ローマ字)
function func00010566($data){ return $data['code']['00010566']; } // ランキング7位名字(ローマ字)
function func00010567($data){ return $data['code']['00010567']; } // ランキング8位名字(ローマ字)
function func00010568($data){ return $data['code']['00010568']; } // ランキング9位名字(ローマ字)
function func00010569($data){ return $data['code']['00010569']; } // ランキング10位名字(ローマ字)
function func00010570($data){ return $data['code']['00010570']; } // ランキング1位ウエスト
function func00010571($data){ return $data['code']['00010571']; } // ランキング2位ウエスト
function func00010572($data){ return $data['code']['00010572']; } // ランキング3位ウエスト
function func00010573($data){ return $data['code']['00010573']; } // ランキング4位ウエスト
function func00010574($data){ return $data['code']['00010574']; } // ランキング5位ウエスト
function func00010575($data){ return $data['code']['00010575']; } // ランキング6位ウエスト
function func00010576($data){ return $data['code']['00010576']; } // ランキング7位ウエスト
function func00010577($data){ return $data['code']['00010577']; } // ランキング8位ウエスト
function func00010578($data){ return $data['code']['00010578']; } // ランキング9位ウエスト
function func00010579($data){ return $data['code']['00010579']; } // ランキング10位ウエスト
function func00010580($data){ return $data['code']['00010580']; } // ランキング1位ヒップ
function func00010581($data){ return $data['code']['00010581']; } // ランキング2位ヒップ
function func00010582($data){ return $data['code']['00010582']; } // ランキング3位ヒップ
function func00010583($data){ return $data['code']['00010583']; } // ランキング4位ヒップ
function func00010584($data){ return $data['code']['00010584']; } // ランキング5位ヒップ
function func00010585($data){ return $data['code']['00010585']; } // ランキング6位ヒップ
function func00010586($data){ return $data['code']['00010586']; } // ランキング7位ヒップ
function func00010587($data){ return $data['code']['00010587']; } // ランキング8位ヒップ
function func00010588($data){ return $data['code']['00010588']; } // ランキング9位ヒップ
function func00010589($data){ return $data['code']['00010589']; } // ランキング10位ヒップ
function func00010590($data){ return $data['code']['00010590']; } // ランキング1位バスト
function func00010591($data){ return $data['code']['00010591']; } // ランキング2位バスト
function func00010592($data){ return $data['code']['00010592']; } // ランキング3位バスト
function func00010593($data){ return $data['code']['00010593']; } // ランキング4位バスト
function func00010594($data){ return $data['code']['00010594']; } // ランキング5位バスト
function func00010595($data){ return $data['code']['00010595']; } // ランキング6位バスト
function func00010596($data){ return $data['code']['00010596']; } // ランキング7位バスト
function func00010597($data){ return $data['code']['00010597']; } // ランキング8位バスト
function func00010598($data){ return $data['code']['00010598']; } // ランキング9位バスト
function func00010599($data){ return $data['code']['00010599']; } // ランキング10位バスト
function func00010600($data){ return $data['code']['00010600']; } // ランキング1位身長
function func00010601($data){ return $data['code']['00010601']; } // ランキング2位身長
function func00010602($data){ return $data['code']['00010602']; } // ランキング3位身長
function func00010603($data){ return $data['code']['00010603']; } // ランキング4位身長
function func00010604($data){ return $data['code']['00010604']; } // ランキング5位身長
function func00010605($data){ return $data['code']['00010605']; } // ランキング6位身長
function func00010606($data){ return $data['code']['00010606']; } // ランキング7位身長
function func00010607($data){ return $data['code']['00010607']; } // ランキング8位身長
function func00010608($data){ return $data['code']['00010608']; } // ランキング9位身長
function func00010609($data){ return $data['code']['00010609']; } // ランキング10位身長
function func00010610($data){ return $data['code']['00010610']; } // ランキング件数
function func00010611($data){ return $data['code']['00010611']; } // 出勤予定件数
function func00010620($data){ return $data['code']['00010620']; } // ランダムイベントタイトル(優先順位・女の子イベント>ショップイベント>女の子PR)
function func00010621($data){ return $data['code']['00010621']; } // 本日売り出し中の女の子名前(日本語)
function func00010622($data){ return $data['code']['00010622']; } // 本日売り出し中の女の子の待ち時間
function func00010623($data){ return $data['code']['00010623']; } // ランダム新人名前
function func00010624($data){ return $data['code']['00010624']; } // ランダム新人リード
function func00010625($data){ return $data['code']['00010625']; } // ランダム新着フォト名前
function func00010626($data){ return $data['code']['00010626']; } // ランダム新着フォトリード
function func00010627($data){ return $data['code']['00010627']; } // 本日売り出し中の女の子のリード
function func00010630($data){ return $data['code']['00010630']; } // site/m/enquete/enquete_m.htmlをそのままインクルード
function func00010640($data){ return $data['code']['00010640']; } // ブログタイトル
function func00010641($data){ return $data['code']['00010641']; } // 日記タイトル
function func00010642($data){ return $data['code']['00010642']; } // 日記本文
function func00010643($data){ return $data['code']['00010643']; } // 過去記事タイトル
function func00010651($data){ return $data['code']['00010651']; } // オフィシャルブログ タイトル
function func00010652($data){ return $data['code']['00010652']; } // オフィシャルブログ 本文
function func00010653($data){ return $data['code']['00010653']; } // オフィシャルブログ 過去記事タイトル
function func00010654($data){ return $data['code']['00010654']; } // オフィシャルブログ 日時テキスト
function func00010660($data){ return $data['code']['00010660']; } // ShopPRイベント名
function func00010661($data){ return $data['code']['00010661']; } // ShopPRイベントPR文
function func00010670($data){ return $data['code']['00010670']; } // 写メ日記最新順1のコの名前
function func00010671($data){ return $data['code']['00010671']; } // 写メ日記最新順2のコの名前
function func00010672($data){ return $data['code']['00010672']; } // 写メ日記最新順3のコの名前
function func00010680($data){ return $data['code']['00010680']; } // 写メ日記最新順1の本文10文字程度
function func00010681($data){ return $data['code']['00010681']; } // 写メ日記最新順2の本文10文字程度
function func00010682($data){ return $data['code']['00010682']; } // 写メ日記最新順3の本文10文字程度
function func00010690($data){ return $data['code']['00010690']; } // 一覧用女の子の名前(日本語)
function func00010691($data){ return $data['code']['00010691']; } // 一覧用女の子の年齢
function func00010692($data){ return $data['code']['00010692']; } // 一覧用女の子の身長
function func00010693($data){ return $data['code']['00010693']; } // 一覧用女の子のバスト
function func00010694($data){ return $data['code']['00010694']; } // 一覧用女の子のカップ
function func00010695($data){ return $data['code']['00010695']; } // 一覧用女の子のウエスト
function func00010696($data){ return $data['code']['00010696']; } // 一覧用女の子のヒップ
function func00010697($data){ return $data['code']['00010697']; } // 一覧用女の子の雰囲気
function func00010698($data){ return $data['code']['00010698']; } // 一覧用女の子のタイプ
function func00010699($data){ return $data['code']['00010699']; } // 一覧用女の子の指名料 ex 1,000
function func00010700($data){ return $data['code']['00010700']; } // 新着ムービー＆グラビア日時1
function func00010701($data){ return $data['code']['00010701']; } // 新着ムービー＆グラビア日時2
function func00010702($data){ return $data['code']['00010702']; } // 新着ムービー＆グラビア日時3
function func00010705($data){ return $data['code']['00010705']; } // 新着ムービー＆グラビア名前1
function func00010706($data){ return $data['code']['00010706']; } // 新着ムービー＆グラビア名前2
function func00010707($data){ return $data['code']['00010707']; } // 新着ムービー＆グラビア名前3
function func00010710($data){ return $data['code']['00010710']; } // 新着フォトグラフィー日時1
function func00010711($data){ return $data['code']['00010711']; } // 新着フォトグラフィー日時2
function func00010712($data){ return $data['code']['00010712']; } // 新着フォトグラフィー日時3
function func00010713($data){ return $data['code']['00010713']; } // 新着フォトグラフィー日時3
function func00010720($data){ return $data['code']['00010720']; } // 新人入店速報バスト1
function func00010721($data){ return $data['code']['00010721']; } // 新人入店速報バスト2
function func00010722($data){ return $data['code']['00010722']; } // 新人入店速報バスト3
function func00010725($data){ return $data['code']['00010725']; } // 新人入店速報カップ1
function func00010726($data){ return $data['code']['00010726']; } // 新人入店速報カップ2
function func00010727($data){ return $data['code']['00010727']; } // 新人入店速報カップ3
function func00010730($data){ return $data['code']['00010730']; } // 新人入店速報ウェスト1
function func00010731($data){ return $data['code']['00010731']; } // 新人入店速報ウェスト2
function func00010732($data){ return $data['code']['00010732']; } // 新人入店速報ウェスト3
function func00010735($data){ return $data['code']['00010735']; } // 新人入店速報ヒップ1
function func00010736($data){ return $data['code']['00010736']; } // 新人入店速報ヒップ2
function func00010737($data){ return $data['code']['00010737']; } // 新人入店速報ヒップ3
function func00010740($data){ return $data['code']['00010740']; } // サブテロップ
function func00010741($data){ return $data['code']['00010741']; } // PICKUP PRテキスト
function func00010750($data){ return $data['code']['00010750']; } // 定番の子1名前
function func00010751($data){ return $data['code']['00010751']; } // 定番の子2名前
function func00010752($data){ return $data['code']['00010752']; } // 定番の子3名前
function func00010760($data){ return $data['code']['00010760']; } // 定番の子1年齢
function func00010761($data){ return $data['code']['00010761']; } // 定番の子2年齢
function func00010762($data){ return $data['code']['00010762']; } // 定番の子3年齢
function func00010770($data){ return $data['code']['00010770']; } // 新着情報本文1
function func00010771($data){ return $data['code']['00010771']; } // 新着情報本文2
function func00010772($data){ return $data['code']['00010772']; } // 新着情報本文3
function func01010001($data){ return $data['code']['01010001']; } // トップバナーサムネールのファイルリスト
function func01010002($data){ return $data['code']['01010002']; } // トップバナーサムネールのファイルリスト2
function func01010003($data){ return $data['code']['01010003']; } // トップバナーサムネールのファイルリスト3
function func01010004($data){ return $data['code']['01010004']; } // トップバナーサムネールのファイルリスト4
function func01010005($data){ return $data['code']['01010005']; } // トップバナーサムネールのファイルリスト5
function func01010006($data){ return $data['code']['01010006']; } // トップバナーサムネールのファイルリスト6
function func01010007($data){ return $data['code']['01010007']; } // バナー01(兼全ページ反映バナー)の拡張子を含まないファイルネーム
function func01010008($data){ return $data['code']['01010008']; } // バナー02の拡張子を含まないファイルネーム
function func01010009($data){ return $data['code']['01010009']; } // バナー03の拡張子を含まないファイルネーム
function func01010010($data){ return $data['code']['01010010']; } // バナー04の拡張子を含まないファイルネーム
function func01010011($data){ return $data['code']['01010011']; } // バナー05の拡張子を含まないファイルネーム
function func01010012($data){ return $data['code']['01010012']; } // バナー06の拡張子を含まないファイルネーム
function func01010013($data){ return $data['code']['01010013']; } // バナー07の拡張子を含まないファイルネーム
function func01010014($data){ return $data['code']['01010014']; } // バナー08の拡張子を含まないファイルネーム
function func01010015($data){ return $data['code']['01010015']; } // バナー09の拡張子を含まないファイルネーム
function func01010016($data){ return $data['code']['01010016']; } // バナー10の拡張子を含まないファイルネーム
function func01010017($data){ return $data['code']['01010017']; } // バナー11の拡張子を含まないファイルネーム
function func01010018($data){ return $data['code']['01010018']; } // バナー12の拡張子を含まないファイルネーム
function func01010019($data){ return $data['code']['01010019']; } // バナー13の拡張子を含まないファイルネーム
function func01010020($data){ return $data['code']['01010020']; } // バナー14の拡張子を含まないファイルネーム
function func01010021($data){ return $data['code']['01010021']; } // バナー15の拡張子を含まないファイルネーム
function func01010022($data){ return $data['code']['01010022']; } // バナー16の拡張子を含まないファイルネーム
function func01010023($data){ return $data['code']['01010023']; } // バナー17の拡張子を含まないファイルネーム
function func01010024($data){ return $data['code']['01010024']; } // バナー18の拡張子を含まないファイルネーム
function func01010025($data){ return $data['code']['01010025']; } // バナー19の拡張子を含まないファイルネーム
function func01010026($data){ return $data['code']['01010026']; } // バナー20の拡張子を含まないファイルネーム
function func01010027($data){ return $data['code']['01010027']; } // バナー21の拡張子を含まないファイルネーム
function func01010030($data){ return $data['code']['01010030']; } // リアルタイム予約状況アイコン画像拡張子を含まないファイルネーム
function func01010035($data){ return $data['code']['01010035']; } // ニュース/新着1行目アイコン画像拡張子を含まないファイルネーム
function func01010036($data){ return $data['code']['01010036']; } // ニュース/新着2 行目アイコン画像拡張子を含まないファイルネーム
function func01010037($data){ return $data['code']['01010037']; } // ニュース/新着3行目アイコン画像拡張子を含まないファイルネーム
function func01010040($data){ return $data['code']['01010040']; } // 写メ日記最新書き込み写メアイコン拡張子を含まないファイルネーム
function func01010045($data){ return $data['code']['01010045']; } // 新着フォトグラフィーのアイコン画像拡張子を含まないファイルネーム
function func01010050($data){ return $data['code']['01010050']; } // 新着グラビアのアイコン画像拡張子を含まないファイルネーム
function func01010055($data){ return $data['code']['01010055']; } // ニュース/新着1行目ヘッドラインのアイコンcss名(soucho:hiru:yoru:sougou)
function func01010056($data){ return $data['code']['01010056']; } // ニュース/新着 2行目ヘッドラインのアイコンcss名(soucho:hiru:yoru:sougou)
function func01010057($data){ return $data['code']['01010057']; } // ニュース/新着3 行目ヘッドラインのアイコンcss名(soucho:hiru:yoru:sougou)
function func01010060($data){ return $data['code']['01010060']; } // 定番の子1バナーの拡張子を含まないファイルネーム
function func01010061($data){ return $data['code']['01010061']; } // 定番の子2バナーの拡張子を含まないファイルネーム
function func01010062($data){ return $data['code']['01010062']; } // 定番の子3バナーの拡張子を含まないファイルネーム
function func01010063($data){ return $data['code']['01010063']; } // 定番の子4バナーの拡張子を含まないファイルネーム
function func01010064($data){ return $data['code']['01010064']; } // 定番の子5バナーの拡張子を含まないファイルネーム
function func01010065($data){ return $data['code']['01010065']; } // 定番の子6バナーの拡張子を含まないファイルネーム
function func01010066($data){ return $data['code']['01010066']; } // 定番の子7バナーの拡張子を含まないファイルネーム
function func01010067($data){ return $data['code']['01010067']; } // 定番の子8バナーの拡張子を含まないファイルネーム
function func01010068($data){ return $data['code']['01010068']; } // 定番の子9バナーの拡張子を含まないファイルネーム
function func01010069($data){ return $data['code']['01010069']; } // 定番の子10バナーの拡張子を含まないファイルネーム
function func01010070($data){ return $data['code']['01010070']; } // リアルタイム一覧の写真。拡張子を含まないファイルネーム
function func01010075($data){ return $data['code']['01010075']; } // リアルタイム一覧の新人アイコン位置の画像。(該当なしの場合には白)
function func01010076($data){ return $data['code']['01010076']; } // リアルタイム一覧・個人ページの個人イベントバナー。(該当なしの場合には白)
function func01010080($data){ return $data['code']['01010080']; } // ローマ字表記画像。アルファベット画像を元に合成画像を出力
function func01010081($data){ return $data['code']['01010081']; } // 女の子ページの写真1拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
function func01010082($data){ return $data['code']['01010082']; } // 女の子ページの写真2拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
function func01010083($data){ return $data['code']['01010083']; } // 女の子ページの写真3拡張子を含まないファイルネーム (javascriptで拡大するので実寸大)
function func01010084($data){ return $data['code']['01010084']; } // 女の子ページの写真4拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
function func01010085($data){ return $data['code']['01010085']; } // 女の子ページの写真5拡張子を含まないファイルネーム(javascriptで拡大するので実寸大)
function func01010091($data){ return $data['code']['01010091']; } // 女の子ページの写真1の移り込み画像。拡張子を含まないファイルネーム
function func01010092($data){ return $data['code']['01010092']; } // 女の子ページの写真2の移り込み画像。拡張子を含まないファイルネーム
function func01010093($data){ return $data['code']['01010093']; } // 女の子ページの写真3の移り込み画像。拡張子を含まないファイルネーム
function func01010094($data){ return $data['code']['01010094']; } // 女の子ページの写真4の移り込み画像。拡張子を含まないファイルネーム
function func01010095($data){ return $data['code']['01010095']; } // 女の子ページの写真5の移り込み画像。拡張子を含まないファイルネーム
function func01010100($data){ return $data['code']['01010100']; } // イベントバナーアーカイブスファイルリスト(正方形バナー)
function func01010105($data){ return $data['code']['01010105']; } // 待ち時間を表す画像
function func01010101($data){ return $data['code']['01010101']; } // イベントバナーを持ち、表示可能な女の子の正方形写真ファイルリスト
function func01010110($data){ return $data['code']['01010110']; } // 正方形カラー写真
function func01010111($data){ return $data['code']['01010111']; } // 正方形モノクロ写真
function func01010112($data){ return $data['code']['01010112']; } // ビッグサイズバナー
function func01010113($data){ return $data['code']['01010113']; } // ビッグサイズバナー映り込み画像
function func01010114($data){ return $data['code']['01010114']; } // 正方形バナー
function func01010115($data){ return $data['code']['01010115']; } // リアルタイム予約状況・表示上トップの女の子写真
function func01010120($data){ return $data['code']['01010120']; } // ニュース新着 ニュースカテゴリアイコン画像のパス名
function func01010200($data){ return $data['code']['01010200']; } // トップページナビゲーション内のバナー画像ファイルリスト
function func01010201($data){ return $data['code']['01010201']; } // プレイリストによる正方形バナーのファイルリスト
function func01010202($data){ return $data['code']['01010202']; } // キャストイベントPRの正方形バナーのファイルリスト
function func01010203($data){ return $data['code']['01010203']; } // ショップイベントPRの正方形バナーのファイルリスト
function func01010221($data){ return $data['code']['01010221']; } // ランキング画像1
function func01010222($data){ return $data['code']['01010222']; } // ランキング画像2
function func01010223($data){ return $data['code']['01010223']; } // ランキング画像3
function func01010224($data){ return $data['code']['01010224']; } // ランキング画像4
function func01010225($data){ return $data['code']['01010225']; } // ランキング画像5
function func01010226($data){ return $data['code']['01010226']; } // ランキング画像6
function func01010227($data){ return $data['code']['01010227']; } // ランキング画像7
function func01010228($data){ return $data['code']['01010228']; } // ランキング画像8
function func01010229($data){ return $data['code']['01010229']; } // ランキング画像9
function func01010230($data){ return $data['code']['01010230']; } // ランキング画像10
function func01010240($data){ return $data['code']['01010240']; } // ニュース新着1の画像
function func01010250($data){ return $data['code']['01010250']; } // 個人ページオプションのON/OFF画像1
function func01010251($data){ return $data['code']['01010251']; } // 個人ページオプションのON/OFF画像2
function func01010252($data){ return $data['code']['01010252']; } // 個人ページオプションのON/OFF画像3
function func01010253($data){ return $data['code']['01010253']; } // 個人ページオプションのON/OFF画像4
function func01010254($data){ return $data['code']['01010254']; } // 個人ページオプションのON/OFF画像5
function func01010255($data){ return $data['code']['01010255']; } // 個人ページオプションのON/OFF画像6
function func01010256($data){ return $data['code']['01010256']; } // 個人ページオプションのON/OFF画像7
function func01010257($data){ return $data['code']['01010257']; } // 個人ページオプションのON/OFF画像8
function func01010258($data){ return $data['code']['01010258']; } // 個人ページオプションのON/OFF画像9
function func01010259($data){ return $data['code']['01010259']; } // 個人ページオプションのON/OFF画像10
function func01010260($data){ return $data['code']['01010260']; } // 個人ページオプションのON/OFF画像11
function func01010261($data){ return $data['code']['01010261']; } // 個人ページオプションのON/OFF画像12
function func01010270($data){ return $data['code']['01010270']; } // ランダムイベント画像(優先順位・女の子イベント>ショップイベント>女の子PR)
function func01010271($data){ return $data['code']['01010271']; } // 本日売り出し中の女の子画像
function func01010273($data){ return $data['code']['01010273']; } // ランダム新人画像
function func01010274($data){ return $data['code']['01010274']; } // ランダム新着フォト写真
function func01010275($data){ return $data['code']['01010275']; } // イベントかPRかを表すアイコン
function func01010280($data){ return $data['code']['01010280']; } // 写メ日記にエントリしてる子の写真
function func01010290($data){ return $data['code']['01010290']; } // 写メ日記最新順1のコの正方形画像
function func01010291($data){ return $data['code']['01010291']; } // 写メ日記最新順2のコの正方形画像
function func01010292($data){ return $data['code']['01010292']; } // 写メ日記最新順3のコの正方形画像
function func01010293($data){ return $data['code']['01010293']; } // グレードのアイコン画像
function func01010294($data){ return $data['code']['01010294']; } // ニュース新着用画像
function func01010295($data){ return $data['code']['01010295']; } // この子のビッグサイズバナー画像
function func01010296($data){ return $data['code']['01010296']; } // この子の過去ポップアップ
function func01010297($data){ return $data['code']['01010297']; } // この子の過去ポップアップ URl
function func01010298($data){ return $data['code']['01010298']; } // 空き予定時刻
function func01010300($data){ return $data['code']['01010300']; } // 新人入店速報画像1
function func01010301($data){ return $data['code']['01010301']; } // 新人入店速報画像2
function func01010302($data){ return $data['code']['01010302']; } // 新人入店速報画像3
function func01010305($data){ return $data['code']['01010305']; } // 新着フォトグラフィー画像1
function func01010306($data){ return $data['code']['01010306']; } // 新着フォトグラフィー画像2
function func01010307($data){ return $data['code']['01010307']; } // 新着フォトグラフィー画像3
function func01010310($data){ return $data['code']['01010310']; } // 新着ムービー＆グラビア画像1
function func01010311($data){ return $data['code']['01010311']; } // 新着ムービー＆グラビア画像2
function func01010312($data){ return $data['code']['01010312']; } // 新着ムービー＆グラビア画像3
function func01010321($data){ return $data['code']['01010321']; } // PICKUP PR画像
function func01010327($data){ return $data['code']['01010327']; } // イベントバナー帯アイコン画像1
function func01010328($data){ return $data['code']['01010328']; } // イベントバナー帯アイコン画像2
function func01010329($data){ return $data['code']['01010329']; } // イベントバナー帯アイコン画像3
function func01010330($data){ return $data['code']['01010330']; } // イベントバナー帯アイコン画像4
function func01010331($data){ return $data['code']['01010331']; } // イベントバナー帯アイコン画像5
function func01010332($data){ return $data['code']['01010332']; } // イベントバナー帯アイコン画像6
function func01010333($data){ return $data['code']['01010333']; } // イベントバナー帯アイコン画像7
function func01010334($data){ return $data['code']['01010334']; } // イベントバナー帯アイコン画像8
function func03010007($data){ return $data['code']['03010007']; } // バナー 01(兼全ページ反映バナー)のリンク先URI
function func03010008($data){ return $data['code']['03010008']; } // バナー02のリンク先URI
function func03010009($data){ return $data['code']['03010009']; } // バナー03のリンク先URI
function func03010010($data){ return $data['code']['03010010']; } // バナー04のリンク先URI
function func03010011($data){ return $data['code']['03010011']; } // バナー05のリンク先URI
function func03010012($data){ return $data['code']['03010012']; } // バナー06のリンク先URI
function func03010013($data){ return $data['code']['03010013']; } // バナー07のリンク先URI
function func03010014($data){ return $data['code']['03010014']; } // バナー08のリンク先URI
function func03010015($data){ return $data['code']['03010015']; } // バナー09のリンク先URI
function func03010016($data){ return $data['code']['03010016']; } // バナー10のリンク先URI
function func03010017($data){ return $data['code']['03010017']; } // バナー11のリンク先URI
function func03010018($data){ return $data['code']['03010018']; } // バナー12のリンク先URI
function func03010019($data){ return $data['code']['03010019']; } // バナー13のリンク先URI
function func03010020($data){ return $data['code']['03010020']; } // バナー14のリンク先URI
function func03010021($data){ return $data['code']['03010021']; } // バナー15のリンク先URI
function func03010022($data){ return $data['code']['03010022']; } // バナー16のリンク先URI
function func03010023($data){ return $data['code']['03010023']; } // バナー17のリンク先URI
function func03010024($data){ return $data['code']['03010024']; } // バナー18のリンク先URI
function func03010025($data){ return $data['code']['03010025']; } // バナー19のリンク先URI
function func03010026($data){ return $data['code']['03010026']; } // バナー20のリンク先URI
function func03010027($data){ return $data['code']['03010027']; } // バナー21のリンク先URI
function func03010030($data){ return $data['code']['03010030']; } // ニュース/新着 1行目全文リンク先URI
function func03010031($data){ return $data['code']['03010031']; } // ニュース/新着2行目全文リンク先URI
function func03010032($data){ return $data['code']['03010032']; } // ニュース/新着3行目全文リンク先URI
function func03010035($data){ return $data['code']['03010035']; } // イベント情報1 リンク先URI
function func03010036($data){ return $data['code']['03010036']; } // イベント情報2リンク先URI
function func03010037($data){ return $data['code']['03010037']; } // イベント情報3リンク先URI
function func03010038($data){ return $data['code']['03010038']; } // イベント情報4 リンク先URI
function func03010040($data){ return $data['code']['03010040']; } // 新人入店速報1個人ページへのURI(TOPページ用)
function func03010041($data){ return $data['code']['03010041']; } // 新人入店速報2個人ページへのURI(TOPページ用)
function func03010042($data){ return $data['code']['03010042']; } // 新人入店速報3個人ページへのURI(TOPページ用)
function func03010043($data){ return $data['code']['03010043']; } // 新人入店速報4個人ページへのURI(TOPページ用)
function func03010050($data){ return $data['code']['03010050']; } // 個人イベント情報1リンク先URI
function func03010051($data){ return $data['code']['03010051']; } // 個人イベント情報2リンク先URI
function func03010052($data){ return $data['code']['03010052']; } // 個人イベント情報3リンク先URI
function func03010053($data){ return $data['code']['03010053']; } // 個人イベント情報4リンク先URI
function func03010055($data){ return $data['code']['03010055']; } // 写メ日記最新書き込みへのリンクURI
function func03010060($data){ return $data['code']['03010060']; } // 新着フォトグラフィーの個人ページへのURI
function func03010061($data){ return $data['code']['03010061']; } // 新着フォトグラフィーの個人ページへのURI2
function func03010062($data){ return $data['code']['03010062']; } // 新着フォトグラフィーの個人ページへのURI3
function func03010065($data){ return $data['code']['03010065']; } // 新着グラビアの個人ページへのURI
function func03010068($data){ return $data['code']['03010068']; } // 各ランキングページへリンクつきリスト(html)
function func03010069($data){ return $data['code']['03010069']; } // このランキングページへのURI
function func03010070($data){ return $data['code']['03010070']; } // ランキング1位の個人ページへのURI
function func03010071($data){ return $data['code']['03010071']; } // ランキング2位の個人ページへのURI
function func03010072($data){ return $data['code']['03010072']; } // ランキング3位の個人ページへのURI
function func03010073($data){ return $data['code']['03010073']; } // ランキング4位の個人ページへのURI
function func03010074($data){ return $data['code']['03010074']; } // ランキング5位の個人ページへのURI
function func03010075($data){ return $data['code']['03010075']; } // スペシャルムービー1へのURI
function func03010076($data){ return $data['code']['03010076']; } // スペシャルムービー2へのURI
function func03010077($data){ return $data['code']['03010077']; } // スペシャルムービー3へのURI
function func03010080($data){ return $data['code']['03010080']; } // 定番の子1個人ページへのURI
function func03010081($data){ return $data['code']['03010081']; } // 定番の子2個人ページへのURI
function func03010082($data){ return $data['code']['03010082']; } // 定番の子3個人ページへのURI
function func03010083($data){ return $data['code']['03010083']; } // 定番の子4個人ページへのURI
function func03010084($data){ return $data['code']['03010084']; } // 定番の子5個人ページへのURI
function func03010085($data){ return $data['code']['03010085']; } // 定番の子6個人ページへのURI
function func03010086($data){ return $data['code']['03010086']; } // 定番の子7個人ページへのURI
function func03010087($data){ return $data['code']['03010087']; } // 定番の子8個人ページへのURI
function func03010088($data){ return $data['code']['03010088']; } // 定番の子9個人ページへのURI
function func03010089($data){ return $data['code']['03010089']; } // 定番の子10個人ページへのURI
function func03010090($data){ return $data['code']['03010090']; } // 個人ページへのリンク先URI
function func03010091($data){ return $data['code']['03010091']; } // 個人イベントページへのリンク先URI
function func03010092($data){ return $data['code']['03010092']; } // このページの正規URL（絶対URL・canonical/og:url用）
function func03010093($data){ return $data['code']['03010093']; } // CityHeaven個人専用口コミページURL
function func03010100($data){ return $data['code']['03010100']; } // トップバナー1クリック時のリンク先URI
function func03010101($data){ return $data['code']['03010101']; } // トップバナー2クリック時のリンク先URI
function func03010102($data){ return $data['code']['03010102']; } // トップバナー3 クリック時のリンク先URI
function func03010103($data){ return $data['code']['03010103']; } // トップバナー4クリック時のリンク先URI
function func03010104($data){ return $data['code']['03010104']; } // トップバナー5クリック時のリンク先URI
function func03010105($data){ return $data['code']['03010105']; } // トップバナーonclick=後に記述するjavascriptリスト
function func03010106($data){ return $data['code']['03010106']; } // トップバナーonclick=後に記述するjavascriptリスト2
function func03010107($data){ return $data['code']['03010107']; } // トップバナーonclick=後に記述するjavascriptリスト3
function func03010108($data){ return $data['code']['03010108']; } // トップバナーonclick=後に記述するjavascriptリスト4
function func03010109($data){ return $data['code']['03010109']; } // トップバナーonclick=後に記述するjavascriptリスト5
function func03010110($data){ return $data['code']['03010110']; } // この子の縦ページ
function func03010111($data){ return $data['code']['03010111']; } // この子の横ページ
function func03010115($data){ return $data['code']['03010115']; } // この子のページ写真サムネールの[BACK]
function func03010116($data){ return $data['code']['03010116']; } // この子のページ写真サムネールの[NEXT]
function func03010117($data){ return $data['code']['03010117']; } // この子のスペシャルムービー一覧
function func03010118($data){ return $data['code']['03010118']; } // この子のスペシャルグラビア一覧
function func03010119($data){ return $data['code']['03010119']; } // この子の写メ日記
function func03010120($data){ return $data['code']['03010120']; } // この子の個人ブログ
function func03010121($data){ return $data['code']['03010121']; } // この子の個人イベントバナーアーカイブス
function func03010122($data){ return $data['code']['03010122']; } // 前の子の個人ページへ[BACK]
function func03010123($data){ return $data['code']['03010123']; } // 次の子の個人ページへ[NEXT]
function func03010130($data){ return $data['code']['03010130']; } // ポップアップする画像のURI
function func03010131($data){ return $data['code']['03010131']; } // ポップアップする画像とテキストのURI
function func03010140($data){ return $data['code']['03010140']; } // アーカイブスの全一覧から、個人アーカイブスへのリンク先リスト
function func03010150($data){ return $data['code']['03010150']; } // ニュース新着 全文リンク
function func03010151($data){ return $data['code']['03010151']; } // ニュース新着　前の記事のURI
function func03010152($data){ return $data['code']['03010152']; } // ニュース新着　次の記事のURI
function func03010200($data){ return $data['code']['03010200']; } // トップページナビゲーション内のバナーonclick=後に記述するjavascriptリスト
function func03010210($data){ return $data['code']['03010210']; } // ランダムイベント詳細ベージへのURI(優先順位・女の子イベント>ショップイベント>女の子
function func03010211($data){ return $data['code']['03010211']; } // ランダムイベント一覧ベージへのURI(女の子イベントPR一覧かショップイベント一覧)
function func03010212($data){ return $data['code']['03010212']; } // 本日売り出し中の女の子の個人ページへのURI
function func03010213($data){ return $data['code']['03010213']; } // ランダム新人・個人ページへのURI
function func03010214($data){ return $data['code']['03010214']; } // ランダム新着フォト個人ページへのURI
function func03010215($data){ return $data['code']['03010215']; } // ショップイベントPR詳細画面へのURI
function func03010216($data){ return $data['code']['03010216']; } // ShopPRイベント 縦横ページへのURI
function func03010223($data){ return $data['code']['03010223']; } // ランキング4位の個人ページへのURI
function func03010224($data){ return $data['code']['03010224']; } // ランキング5位の個人ページへのURI
function func03010225($data){ return $data['code']['03010225']; } // ランキング6位の個人ページへのURI
function func03010226($data){ return $data['code']['03010226']; } // ランキング7位の個人ページへのURI
function func03010227($data){ return $data['code']['03010227']; } // ランキング8位の個人ページへのURI
function func03010228($data){ return $data['code']['03010228']; } // ランキング9位の個人ページへのURI
function func03010229($data){ return $data['code']['03010229']; } // ランキング10位の個人ページへのURI
function func03010240($data){ return $data['code']['03010240']; } // 過去記事リンクアドレス
function func03010241($data){ return $data['code']['03010241']; } // 過去記事リンクアドレス
function func03010250($data){ return $data['code']['03010250']; } // オフィシャルブログ 過去記事リンクアドレス
function func03010251($data){ return $data['code']['03010251']; } // オフィシャルブログ 過去記事リンクアドレス
function func03010268($data){ return $data['code']['03010268']; } // 写メ日記最新順1の記事へのURI
function func03010269($data){ return $data['code']['03010269']; } // 写メ日記最新順2の記事へのURI
function func03010270($data){ return $data['code']['03010270']; } // 写メ日記最新順3の記事へのURI
function func03010290($data){ return $data['code']['03010290']; } // キャンペーン記事NEXTのURI
function func03010291($data){ return $data['code']['03010291']; } // キャンペーン記事BACKのURI
function func03010292($data){ return $data['code']['03010292']; } // ニュース新着記事NEXTのURI
function func03010293($data){ return $data['code']['03010293']; } // ニュース新着記事BACKのURI
function func03010300($data){ return $data['code']['03010300']; } // プレイリストによるリンクリスト
function func03010301($data){ return $data['code']['03010301']; } // キャストイベントPRのリンクリスト
function func03010302($data){ return $data['code']['03010302']; } // ショップイベントPRのリンクリスト
function func03010311($data){ return $data['code']['03010311']; } // PICKUP PR URI
function func00040001($data){ return $data['code']['00040001']; } // 本日の日付(ex. 3/26(金))
function func00040002($data){ return $data['code']['00040002']; } // 2日目の日付
function func00040003($data){ return $data['code']['00040003']; } // 3日目の日付
function func00040004($data){ return $data['code']['00040004']; } // 4日目の日付
function func00040005($data){ return $data['code']['00040005']; } // 5日目の日付
function func00040006($data){ return $data['code']['00040006']; } // 6日目の日付
function func00040007($data){ return $data['code']['00040007']; } // 7日目の日付

}
?>