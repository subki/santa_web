<?php

class IO_Controller extends CI_Controller {

	public $data;
	function __construct(){
		parent::__construct();
		$this->load->model('Customertype_model','customertype');
		$this->load->model('Storeprofile_model','model_profile');
		$this->load->model('Delivery_model','model_delivery');
		$this->load->model('Stock_model','model_stock');
		$this->load->model('Cabang_model','model_cabang');
		$this->load->model('Autoconfig_model','model_autoconfig');
		$this->load->model('Privillege_model','model_privillege');
		$this->load->model('Product_model','model_subproduct');
		$this->load->model('Productbrand_model','model_brand');
		$this->load->model('Productsize_model','model_size');
		$this->load->model('Productcolour_model','model_colour');
		$this->load->model('Productuom_model','model_productuom');
		$this->load->model('Uomconvertion_model','model_uom_conv');
		$this->load->model('Productgroup_model','model_group');
		$this->load->model('Masterarticle_model','model_article');
		$this->load->model('Masterproduct_model','model_product');
		$this->load->model('Wilayah2_model','model_regency');
		$this->load->model('Customer_model','model_cust');
		$this->load->model('Log_model','model_log');
		$this->load->model('Users_model','model_user');
		$this->load->model('Salesorder_model','model_sales');
		$this->load->model('Packinglist_model','model_packing');
		$this->load->model('Faktur_model','model_faktur');
		$this->load->model('Wholesales_model','model_ws');

		$ci =& get_instance();
		$name = $ci->router->class;
		$sub1 = $this->uri->segment('2');
		$sub2 = $this->uri->segment('3');
		if($sub1!=null) $name .= "/".$sub1;
		if($sub2!=null) $name .= "/".$sub2;
		if($name!="" && $name!="welcome") {
//            var_dump($name);
//            die();
			$app = $this->model_privillege->read_app_by_name($name)->row();
//            var_dump($app);
//            die();
			if($app!=null) $this->session->set_userdata(array("app" => $app->app_id));
		}

		error_reporting(0);
		$this->AUTH_KEY="AIzaSyDn24Gw6MzBlWzcyJIhRagzq3A2xtN709A";
		$this->URL_API="https://fcm.googleapis.com/fcm/send";
		$this->SERVER_KEY = "AAAATz6kz4U:APA91bFAORTAspGiYlHlTPYk94YRKUKVawNpWxE7WFNim-LvliGh5Pfv8XdVBeCAL5XjyWwSXJyW2LqJXCihsr6wGR0TBBij53IN-C_x4HRelZ6HYPV9_PVg-zLxygEzG0H8jqZ3u5sa";

		$data = $this->model_autoconfig->get_list_data(1,1000,"kunci","asc","", "");
		$this->session->set_userdata("auto_config",$data);
		foreach ($data as $row){
			$this->data['auto'][$row->kunci] = $row->nilai;
			$this->session->set_userdata(array($row->kunci => $row->nilai));
		}
//        $this->checkPeriod('ABC','2020-06-03');
		if (!$this->session->userdata('logged_in')
			|| $this->session->userdata('logged_in')==null) {

			redirect('auth', 'refresh');
		}
	}

	function getDateNow(){
		date_default_timezone_set('Asia/Jakarta');
		return date('Y-m-d H:i:s');
	}

	function toUpper($input){
		$arr = array_keys($_POST);
		foreach ($arr as $key){
			if(is_array($input[$key])){
				$arr2 = array_keys($input[$key]);
				foreach ($arr2 as $key2){
					if(is_array($input[$key][$key2])) {
						$arr3 = array_keys($input[$key][$key2]);
						foreach ($arr3 as $key3) {
							$input[$key][$key2][$key3] = strtoupper($input[$key][$key2][$key3]);
						}
					}else $input[$key][$key2] = strtoupper($input[$key][$key2]);
				}
			}else $input[$key] = strtoupper($input[$key]);
		}
		return $input;
	}

	function push_topic($title, $message, $jenis, $data){

		date_default_timezone_set('Asia/Jakarta');
		header('Content-Type: application/json');

//        $this->input->raw_input_stream;
//        $input_data = json_decode($this->input->raw_input_stream, true);

		$fields = array (
			'to'=>'/topics/news-counter',
			'priority'=>'high',
			"mutable_content"=>true,
			'data' => array (
				"title" => $title,
				"message" => $message,
				"jenis_tr" => $jenis,
				"data" => $data,
			)
		);
		$headers = array (
			'Authorization: key=' . $this->AUTH_KEY,
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->URL_API);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		curl_close($ch);

		return json_encode(array(
			"result" => 0,
			"msg" => $result
		));

	}

	function formatDate($format, $value){
		if($value=="") return null;
		$value = str_replace('/', '-', $value);
		$time = strtotime($value);
		return date($format,$time);
	}

	function checkChar($text){
		$teks = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$bool = true;
		for($i=0; $i<count(str_split($teks)); $i++){
			if(!preg_match("/".str_split($text)[$i]."/", $teks)){
				$bool = false;
				break;
			}
		}
		return $bool;
	}

	function getParamOption(){
		if($this->input->get('field')=="") return "";
		$field = explode(",",$this->input->get('field'));
		$op = explode(",",$this->input->get('op'));
		$value = explode(",",$this->input->get('value'));
		$fltr = array();
		for($i=0; $i<count($field); $i++){
			array_push($fltr, array(
				"field"=>$field[$i],
				"op"=>$op[$i],
				"value"=>$value[$i]
			));
		}
		$app="";
		foreach ($fltr as $r){
			$oop = $r["op"]=="equal"?"=":"like";
			//$vv = $r["op"]=="equal"?"'".$r["value"]."'":"'%".$r["value"]."%'";
			$vv = $r["op"]=="equal"?"'".$r["value"]."'":"'".$r["value"]."%'";
			if($app==""){
				$app .= " where ".$r["field"]." ".$oop." ".$vv." ";
			}else{
				$app .= " AND ".$r["field"]." ".$oop." ".$vv." ";
			}
		}
		return $app;
	}
	function getParamGrid($special="", $sortir=""){

		$data['page'] = ($this->input->post('page')) ? $this->input->post('page'):1;
		$data['rows'] = ($this->input->post('rows')) ? $this->input->post('rows'):20;
		$sort = ($this->input->post('sort')) ? $this->input->post('sort'):$sortir;
		$data['order'] = ($this->input->post('order')) ? $this->input->post('order'):'desc';
		$data['role'] = $this->session->userdata('role');
		$fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";


		if($sort=="crtdt") $sort = 'tanggal_crt';
		if($sort=="upddt") $sort = 'tanggal_upd';
		$data['sort'] = $sort;

		$app="";
		if($fltr!=""){
			foreach ($fltr as $r){
				$oop = $r->op=="equal"?"=":"like";
				$vv = $r->op=="equal"?"'".$r->value."'":"'%".$r->value."%'";
				if($app==""){
					$app .= " where ".$r->field." ".$oop." ".$vv." ";
				}else{
					$app .= " AND ".$r->field." ".$oop." ".$vv." ";
				}
			}
			if($special!="") {
				if (count($fltr) > 0) $app .= " AND ".$special;
				else $app .= " where ".$special;
			}
		}else{
			if($special!=""){
				$app .= " where ".$special;
			}
		}
		$data['app']=$app;
		return $data;
	}

	function getParamGrid_BuilderComplete($data){
		return (object) ["total"=>$this->getParamGrid_BuilderCompleteTotal($data),"data"=>$this->getParamGrid_BuilderCompleteQuery($data)];
	}
	function getParamGrid_BuilderCompleteTotal($data){
//		pre($data->table);
		$page = ($this->input->post('page')) ? $this->input->post('page'):1;
		$rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
		$sort = ($this->input->post('sort')) ? $this->input->post('sort'):$data['sortir'];
		$order = ($this->input->post('order')) ? $this->input->post('order'):'desc';
		$fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

		if($sort=="crtdt") $sort = 'tanggal_crt';
		if($sort=="upddt") $sort = 'tanggal_upd';

		if(isset($data['select'])){
			$this->db->select($data['select']);
		}
		if(isset($data['special'])) {
			if(is_array($data['special'])) foreach ($data['special'] as $key => $row){
				if(!is_numeric($key)){
					$this->db->where($key,$row);
				}else{
					$this->db->where($row);
				}
			}
			else {
				if($data['special']!=="") $this->db->where($data['special']);
			}
		}
		if(isset($data['join'])){
			$no=0;
			foreach ($data['join'] as $key => $row) {
				$this->db->join($key, $row, isset($data['posisi'][$no])?$data['posisi'][$no]:"left");
				$no++;
			}
		}
		if($fltr!=""){
			foreach ($fltr as $r){
				if($r->op=="equal") $this->db->having($r->field,$r->value);
				if($r->op=="notequal") $this->db->having("$r->field !=",$r->value);
				if($r->op=="contains") $this->db->having("$r->field like '%$r->value%'");
				if($r->op=="beginwith") $this->db->having("$r->field like '$r->value%'");
				if($r->op=="endwith") $this->db->having("$r->field like '%$r->value'");
				if($r->op=="less") $this->db->having("$r->field < ",$r->value);
				if($r->op=="lessorequal") $this->db->having("$r->field <= ",$r->value);
				if($r->op=="greater") $this->db->having("$r->field > ",$r->value);
				if($r->op=="greaterorequal") $this->db->having("$r->field >= ",$r->value);
			}
		}
		$this->db->order_by("$sort $order");
		if(isset($data['group'])){
			foreach ($data['group'] as $row){
				$this->db->group_by($row);
			}
		}
		return $this->db->get($data['table'])->num_rows();
	}
	function getParamGrid_BuilderCompleteQuery($data){
//		pre($data->table);
		$page = ($this->input->post('page')) ? $this->input->post('page'):1;
		$rows = ($this->input->post('rows')) ? $this->input->post('rows'): isset($data['rows'])?$data['rows']:20;
		$sort = ($this->input->post('sort')) ? $this->input->post('sort'):$data['sortir'];
		$order = ($this->input->post('order')) ? $this->input->post('order'):'desc';
		$fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

		if($sort=="crtdt") $sort = 'tanggal_crt';
		if($sort=="upddt") $sort = 'tanggal_upd';

		if(isset($data['select'])){
			$this->db->select($data['select']);
		}
		if(isset($data['special'])) {
			if(is_array($data['special'])) foreach ($data['special'] as $key => $row){
				if(!is_numeric($key)){
					$this->db->where($key,$row);
				}else{
					$this->db->where($row);
				}
			}
			else {
				if($data['special']!=="") $this->db->where($data['special']);
			}
		}
		if(isset($data['join'])){
			$no=0;
			foreach ($data['join'] as $key => $row) {
				$this->db->join($key, $row, isset($data['posisi'][$no])?$data['posisi'][$no]:"left");
				$no++;
			}
		}
		if($fltr!=""){
			foreach ($fltr as $r){
				if($r->op=="equal") $this->db->having($r->field,$r->value);
				if($r->op=="notequal") $this->db->having("$r->field !=",$r->value);
				if($r->op=="contains") $this->db->having("$r->field like '%$r->value%'");
				if($r->op=="beginwith") $this->db->having("$r->field like '$r->value%'");
				if($r->op=="endwith") $this->db->having("$r->field like '%$r->value'");
				if($r->op=="less") $this->db->having("$r->field < ",$r->value);
				if($r->op=="lessorequal") $this->db->having("$r->field <= ",$r->value);
				if($r->op=="greater") $this->db->having("$r->field > ",$r->value);
				if($r->op=="greaterorequal") $this->db->having("$r->field >= ",$r->value);
			}
		}
		$this->db->order_by("$sort $order");
		if(isset($data['group'])){
			foreach ($data['group'] as $row){
				$this->db->group_by($row);
			}
		}
		return $this->db->limit($rows,($page-1)*$rows)->get($data['table'])->result();
	}

	function export_csv($filename,$header,$data,$unset=[], $top=array()){
		try {
			header("Content-Description: File transfer");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Type: application/csv;");

			$delimiter = ';';
			$enclosure = '"';

			$file = fopen('php://output', 'w');
			if(count($top)>0) fputcsv($file, $top);
			fputcsv($file, $header, $delimiter, $enclosure);
			foreach ($data->result_array() as $key => $value) {
				if(count($unset)){
					for ($i=0; $i<count($unset); $i++) unset($value[$unset[$i]]);
				}
				unset($value['total']);
				unset($value['tanggal_crt']);
				unset($value['tanggal_upd']);
				fputcsv($file, $value, $delimiter, $enclosure);
			}
			fclose($file);
			exit;
		}catch (Exception $e){
			var_dump($e);
			die();
		}
	}

	function export_csv2($filename,$header,$dt, $top=array(), $urutan){
		try {
			foreach ($dt as $key => $row){
				foreach ($row as $key2 => $rw){
					if(!in_array($key2, $urutan)) unset($dt[$key][$key2]);
				}
			}

			foreach ($dt as $key => $row){
				$urut = [];
				foreach ($urutan as $k){
					$urut[$k] = $row[$k];
				}
				$dt[$key] = $urut;
			}
//			pre($dt);
			header("Content-Description: File transfer");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Type: application/csv;");

			$delimiter = ';';
			$enclosure = '"';

			$file = fopen('php://output', 'w');
			if(count($top)>0) fputcsv($file, $top);
			fputcsv($file, $header, $delimiter, $enclosure);
			foreach ($dt as $key => $value) {
				unset($value['total']);
				unset($value['tanggal_crt']);
				unset($value['tanggal_upd']);
				fputcsv($file, $value, $delimiter, $enclosure);
			}
			fclose($file);
			exit;
		}catch (Exception $e){
			var_dump($e);
			die();
		}
	}

	function checkPeriod($loc,$tgl){
		$periode = $this->formatDate("Y-m",$tgl);
		$sql = "select * from closing_location WHERE location='$loc' and periode LIKE '$periode%' order by periode desc";
		$read = $this->db->query($sql);
		if($read->num_rows()>0){
			if($read->row()->status_cl=="Open"){
				return true;
			}
		}
		return false;
	}
	function insert_log($table,$before,$after){
		$dt = array(
			"tabel"=> $table,
			"data_before"=>$before,
			"data_after"=>$after,
			"user_id"=>$this->session->userdata('user_id'),
			"log_date"=>date('Y-m-d H:i:s')
		);
		$this->model_log->insert_data($dt);
	}
	function read_log($where,$order){
		$sql = "select a.*, u.fullname from log_update a 
						LEFT JOIN users u on u.user_id=a.user_id ";
		$sql .= $where.$order;
		return $this->db->query($sql)->result();
	}

	function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false, $SizeFactor=1 ) {
		$code_string = "";
		// Translate the $text into barcode the correct $code_type
		if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
			$chksum = 104;
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

			$code_string = "211214" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code128a" ) {
			$chksum = 103;
			$text = strtoupper($text); // Code 128A doesn't support lower case
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

			$code_string = "211412" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code39" ) {
			$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");

			// Convert to uppercase
			$upper_text = strtoupper($text);

			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
			}

			$code_string = "1211212111" . $code_string . "121121211";
		} elseif ( strtolower($code_type) == "code25" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
			$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");

			for ( $X = 1; $X <= strlen($text); $X++ ) {
				for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
					if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
						$temp[$X] = $code_array2[$Y];
				}
			}

			for ( $X=1; $X<=strlen($text); $X+=2 ) {
				if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
					$temp1 = explode( "-", $temp[$X] );
					$temp2 = explode( "-", $temp[($X + 1)] );
					for ( $Y = 0; $Y < count($temp1); $Y++ )
						$code_string .= $temp1[$Y] . $temp2[$Y];
				}
			}

			$code_string = "1111" . $code_string . "311";
		} elseif ( strtolower($code_type) == "codabar" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
			$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");

			// Convert to uppercase
			$upper_text = strtoupper($text);

			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
					if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
						$code_string .= $code_array2[$Y] . "1";
				}
			}
			$code_string = "11221211" . $code_string . "1122121";
		}

		// Pad the edges of the barcode
		$code_length = 20;
		if ($print) {
			$text_height = 30;
		} else {
			$text_height = 0;
		}

		for ( $i=1; $i <= strlen($code_string); $i++ ){
			$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
		}

		if ( strtolower($orientation) == "horizontal" ) {
			$img_width = $code_length*$SizeFactor;
			$img_height = $size;
		} else {
			$img_width = $size;
			$img_height = $code_length*$SizeFactor;
		}

		$image = imagecreate($img_width, $img_height + $text_height);
		$black = imagecolorallocate ($image, 0, 0, 0);
		$white = imagecolorallocate ($image, 255, 255, 255);

		imagefill( $image, 0, 0, $white );
		if ( $print ) {
			imagestring($image, 5, 31, $img_height, $text, $black );
		}

		$location = 10;
		for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
			$cur_size = $location + ( substr($code_string, ($position-1), 1) );
			if ( strtolower($orientation) == "horizontal" )
				imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
			else
				imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
			$location = $cur_size;
		}

		// Draw barcode to the screen or save in a file
		if ( $filepath=="" ) {
			header ('Content-type: image/png');
			imagepng($image);
			imagedestroy($image);
		} else {
			imagepng($image,$filepath);
			imagedestroy($image);
		}
	}

	/**
	 * @param $data     = text data yg mau di print. ex : array('A','B',200)
	 * @param $dataType = tipe data dari $data. ex : array('text','text','curr')
	 * @param $width    = lebar column tiap baris data(tergantung dari jenis lebar kertas).
	 *                      ex : ukuran 35mm => array(8,18,12)
	 * @return string   = hasil dari proses di kembalikan ke controller buat di print.
	 */
	function createRowColumn($data,$dataType,$width) {
		$calculateRows = array();
		$columnData = array();
		foreach ($data as $key => $d){
			$column = wordwrap($d, $width[$key], "\n", true);
//            if($key==count($data)-1)pre($column);
			$exp = explode("\n", $column);
//            if($key==count($data)-1)pre($exp);
			$calculateRows[] = count($exp);
			$columnData[] = $exp;
		}
//        pre($columnData);
//        pre($calculateRows);
//        pre($width);
//        pre($dataType);
//        pre($data);

		$maxRows = max($calculateRows);
//        pre($maxRows);
		$tempRows = array();
		for ($i = 0; $i < $maxRows; $i++) {
			$tempColumns = "";
			foreach ($columnData as $j => $row){
				if ($dataType[$j] == "curr") {
					$tempColumns = str_pad((isset($row[$i]) ? $row[$i] : ""), $width[$j], " ", STR_PAD_LEFT);
				} else $tempColumns .= str_pad((isset($row[$i]) ? $row[$i] : ""), $width[$j], " ");
			}
			$tempRows[] = $tempColumns;
		};
//        pre($tempRows);
//        pre($maxRows);
		$imp  = implode($tempRows, "\n") . "\n";
//        pre($imp);
		return $imp;
	}

	/**
	 * @param $location_code = kode lokasi
	 * @param $periode = periode yang akan di update ex : 202010
	 * @param $nobarqty = nobarang yg akan di update dalam bentuk array(nobar[key]:qty[value]) : array('a'=>2,'b'=>1,'c'=>4)
	 * @param $tipe_transaksi = tipe yang akan di update : 'do_masuk', 'do_keluar', 'penyesuaian', 'penjualan', 'pengembalian'
	 * @param $data = untuk isi ke product_history berupa : docno, tanggal, remark
	 * @return string =
	 */
	function updateStock($location_code, $periode, $nobarqty, $tipe_transaksi, $data){
		if($location_code=="") return "Lokasi tidak ada ".$location_code;
		if($periode=="") return "Periode invalid";
		if(!in_array($tipe_transaksi,array('do_masuk', 'do_keluar', 'penyesuaian', 'penjualan', 'pengembalian'))) return "Tipe Transaksi tidak dikenali";
		if(!isset($data->docno)) return "Nomor dokumen harus di sertakan";
		if(!isset($data->tanggal)) return "Tanggal dokumen harus di sertakan";
		if(!isset($data->remark)) return "Keterangan dokumen harus di sertakan";

		$stocks = $this->db->where('location_code',$location_code)
			->where('periode', $periode)
			->where_in('nobar', array_keys($nobarqty))
			->get('stock')->result();

		$arr_ins = [];
		$arr_upd = [];
		$template = array(
			"nobar"=>"",
			"location_code"=>$location_code,
			"periode"=>$periode
		);
		foreach ($nobarqty as $key => $r){
			if(!in_array($key, array_column($stocks,"nobar"))){
				$template['nobar'] = $key;
				$template[$tipe_transaksi] = $r;
				$arr_ins[] = $template;
			}else{
				$stk = (array) $stocks[array_search($key,array_column($stocks,"nobar"))];
				$template['nobar'] = $key;
				$template[$tipe_transaksi] = $stk[$tipe_transaksi];
				$arr_upd[] = $template;
			}
		}
		if(count($arr_ins) > 0) $this->db->insert_batch('stock',$arr_ins);
		if(count($arr_upd) > 0) $this->db->update_batch('stock',$arr_upd,['nobar','location_code','periode']);
		$store = $this->db->get_where('cabang',['location_code'=>$location_code])->row();

		$tipe = "";
		switch ($tipe_transaksi){
			case 'do_masuk': $tipe = "DO IN"; break;
			case 'do_keluar': $tipe = "DO OUT"; break;
			case 'penyesuaian': $tipe = "ADJUSTMENT"; break;
			case 'penjualan': $tipe = "PENJUALAN"; break;
			case 'pengembalian': $tipe = "RETUR PENJUALAN"; break;
		}

		$prodhist = [];
		$ctr = 1;
		foreach ($nobarqty as $key => $r){
			$temo["store_code"] = isset($store) ? $store->store_code : "";
			$temo["trx_date"] = $data->tanggal;
			$temo["trx_time"] = date('H:i:s');
			$temo["trx_type"] = $tipe;
			$temo["customer_code"] = $data->tanggal;
			$temo["location_code"] = $location_code;
			$temo["trx_no"] = $data->docno;
			$temo["seqno"] = str_pad($ctr,3,"0",STR_PAD_LEFT);;
			$temo["sku"] = $key;
			$temo["qty"] = $r;
			$temo["total"] = 0;
			$temo["remark"] = $data->remark;
			$prodhist[] = $temo;
			$ctr++;
		}

		if(count($prodhist) > 0) $this->db->insert_batch('product_history',$prodhist);
		$this->db->where_in('nobar',array_keys($nobarqty))
			->where('periode',$periode)
			->where('location_code',$location_code)
			->update('stock', ['saldo_akhir=saldo_awal+do_masuk+do_keluar+penyesuaian+penjualan+pengembalian']);

//			$sql = "UPDATE stock SET saldo_akhir=saldo_awal+do_masuk+do_keluar+penyesuaian+penjualan+pengembalian
//                WHERE periode='$periode' ";
//			$this->db->query($sql);
//
//			UPDATE product_barang a,
//				(
//				SELECT b.nobar, SUM(b.saldo_akhir) saldo
//					FROM stock b
//					WHERE b.periode=DATE_FORMAT(NOW(),'%Y%m')
//					GROUP BY b.nobar
//				) b1
//			SET a.soh=b1.saldo
//			WHERE a.nobar=b1.nobar;
//
//			UPDATE product a,
//				(
//				SELECT b.product_id, SUM(b.soh) saldo
//					FROM product_barang b
//					GROUP BY b.product_id
//				) b1
//			SET a.total_soh=b1.saldo
//			WHERE a.id=b1.product_id;
		$qtys = $this->db->select('b.nobar, sum(b.saldo_akhir) as soh')
			->from('stock b')
			->where('b.periode',"$periode")
			->group_by("b.nobar")->get()->result();
		if(count($qtys)>0) $this->db->update_batch("product_barang", $qtys, "nobar");
		$qtysoh = $this->db->select('b.product_id as id, SUM(b.soh) total_soh')
			->from('product_barang b')
			->group_by("b.product_id")->get()->result();
		if(count($qtysoh)>0) $this->db->update_batch("product", $qtysoh, "id");


		return "ok";
	}

	/**
	 * @param $header : array
	 * @param $detail : array
	 */
	function journal_record($header, $detail){
		$field_header = ["id","journal_no","store_code","fiscal_year","fiscal_month","journal_date","entry_date",
			"journal_code","reference","keterangan","total_debet","total_credit","status_journal","journal_type","crtby","crtdt","updby","upddt"];
		$fiel_detail=["id","journal_headerid","journal_no","seqno","cost_center","account_no","dbcr","remark","nilai_debet","nilai_credit","crtby","crtdt","updby","upddt"];
		$this->db->trans_start();
		foreach ($header as $key => $row){
			foreach ($row as $key2 => $r) {
				if (!in_array($key2, $field_header)) unset($header[$key][$key2]);
			}
		}
		foreach ($detail as $key => $row){
			foreach ($row as $key2 => $r) {
				if (!in_array($key2, $fiel_detail)) unset($detail[$key][$key2]);
			}
		}
		$lastID = $this->db->order_by("id desc")->limit(1)->get("journal_header")->row()->id;
		if(isset($lastID)) $lastID = $lastID+1;
		else $lastID = 1;
		foreach ($header as $i => $r){
			$header[$i]['id'] = $lastID;
			$lastID++;
		}
		foreach ($detail as $key => $row){
			$detail[$key]['journal_headerid'] = array_search($row->docno, $header)[0]->id;
		}
		if(count($header)>0) $this->db->insert_batch("journal_header",$header);
		if(count($detail)>0) $this->db->insert_batch("journal_detail",$detail);
		$this->db->trans_complete();
	}

	public function set_success($text){
		$this->set_custom($text,"notice");
	}
	public function set_error($text){
		$this->set_custom($text,"error");
	}
	public function set_custom($text,$type){
		$data=array(
			$type=>$text
		);
		$this->message->set($data);

//		$data = array(
//			'message'=>'this is just a message',
//			'notice'=>'this is just a notice'
//		);
//		$this->message->set($data);
	}
	public function getLocations($golongan="", $cust_class="", $location="", $customer_code=""){
		$this->db->select("l.location_code, l.description, c.customer_code, c.customer_name, cb.store_code, p.store_name
						, c.regency_id, c.provinsi_id, c.beda_fp, c.pkp")
			->from("customer c")
			->join("location l","l.location_code=c.lokasi_stock")
			->join("cabang cb","cb.location_code=l.location_code")
			->join("profile_p p","cb.store_code=p.store_code");
		if($golongan!="") $this->db->where("c.gol_customer",$golongan);
		if($cust_class!="") $this->db->where("c.customer_class",$cust_class);
		if($location!="") {
			return $this->db->where("l.location_code",$location)->get()->row();
		}
		else if($customer_code!="") return $this->db->where("c.customer_code",$customer_code)->get()->row();
		else return $this->db->get()->result();
	}
}
