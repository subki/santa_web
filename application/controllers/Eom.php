<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
/**
 * Class Eom
 * @property Eom_model $eom
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Eom extends IO_Controller {

	function __construct(){

		parent::__construct();
		$this->load->model('Eom_model','model');
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	function index(){
		$data['title']      = 'End Of Month';
		$data['content']    = $this->load->view('vEom',$data,TRUE);

		$this->load->view('main',$data);
	}

	function get_location(){
		$store = $this->session->userdata('store_code');
		$store1 = $this->session->userdata('kode store pusat');

		$special = " a.location_code in(select location_code from cabang where store_code='$store')";
		if($store==$store1){
			$special = "";
		}
		$total = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"total",
			"table"=>"location a",
			"sortir"=>"location_code",
			"special"=>$special,
			"select"=>"a.location_code, a.description location_name, b.store_name, b.store_code",
			"join"=>["cabang c"=>"c.location_code=a.location_code","profile_p b"=>"b.store_code=c.store_code"]
		));
		$data = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"query",
			"table"=>"location a",
			"sortir"=>"location_code",
			"special"=>$special,
			"select"=>"a.location_code, a.description location_name, b.store_name, b.store_code",
			"join"=>["cabang c"=>"c.location_code=a.location_code","profile_p b"=>"b.store_code=c.store_code"]
		));

		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>$total,
				"data" =>$data
			)
		);
	}

	function get_nobar($loc1="", $loc2="", $prd=""){
		if($loc1=="" || $loc2=="" || $loc1=="~" || $loc2=="~"){
			$store = $this->session->userdata('store_code');
			$store1 = $this->session->userdata('kode store pusat');
			$special = " a.location_code in(select location_code from cabang where store_code='$store') group by a.nobar";
			if($store==$store1){
				$special=" a.periode='$prd' group by a.nobar ";
			}
		}else{
			if($loc1==$loc2){
				$special = " a.location_code ='$loc1' and a.periode='$prd' group by a.nobar";
			}else $special = " a.location_code >='$loc1' and a.location_code<='$loc2' and a.periode='$prd' group by a.nobar";
		}
		$total = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"total",
			"table"=>"stock a",
			"sortir"=>"nobar",
			"special"=>$special,
			"select"=>"a.nobar, a.location_code, a.periode, a.saldo_awal, b.description as location_name, c.nmbar
                  , a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir",
			"join"=>["location b"=>"a.location_code=b.location_code","product_barang c"=>"a.nobar=c.nobar"]
		));
		$data = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"query",
			"table"=>"stock a",
			"sortir"=>"nobar",
			"special"=>$special,
			"select"=>"a.nobar, a.location_code, a.periode, a.saldo_awal, b.description as location_name, c.nmbar
                  , a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir",
			"join"=>["location b"=>"a.location_code=b.location_code","product_barang c"=>"a.nobar=c.nobar"]
		));

		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>$total,
				"data" =>$data
			)
		);
	}

	function execute_eom(){
		try {
			$input = $this->toUpper($this->input->post());
//			pre($input);
			if($input['from_location_code']=='~') $input['from_location_code'] = 'a';
			if($input['to_location_code']=='~') $input['to_location_code'] = 'ZZZ';
			if($input['from_nobar']=='~') $input['from_nobar'] = '0';
			if($input['to_nobar']=='~') $input['to_nobar'] = '999999999999';
			$store = $this->session->userdata('store_code');
			$p = str_replace("/","",$input['periode']);

			$store1 = $this->session->userdata('kode store pusat');
			$this->db->select("l.*")
				->where("l.location_code >=", $input['from_location_code'])
				->where("l.location_code <=", $input['to_location_code'])
				->join("cabang c","c.location_code=l.location_code");
			if($store!=$store1){
				$this->db->where('c.store_code',$store);
			}
			$read_loc = $this->db->get("location l")->result();
			$arr_loc = [];
			foreach ($read_loc as $r) $arr_loc[] = $r->location_code;

			$ins_batch = [];
			$upd_batch = [];
			$template = array(
				"location" => "",
//				"periode" => substr($p, 0, 4) . "-" . str_pad(substr($p, -2) + 1, 2, "0", STR_PAD_LEFT) . "-" . date('d'),
				"status_cl" => 'Open',
				'crtby' => $this->session->userdata('user_id'),
				'crtdt' => date('Y-m-d H:i:s')
			);
			if(count($read_loc)>0){
				$lokasi0 = $this->db->where_in("location",$arr_loc)
					->where("DATE_FORMAT(periode, '%Y%m')=period_add('$p',0)")->get('closing_location')->result();
				$lokasi1 = $this->db->where_in("location",$arr_loc)
					->where("DATE_FORMAT(periode, '%Y%m')=period_add('$p',1)")->get('closing_location')->result();
				foreach ($arr_loc as $c) {
					if(!in_array($c, array_column($lokasi0,"location"))){
						//insert close
						$template["location"]=$c;
						$template["periode"] = date("Y-m-d",strtotime("+0 month",strtotime($p."25")));
						$template['status_cl'] = 'Close';
						$ins_batch[] = $template;
					}
					if(!in_array($c, array_column($lokasi1,"location"))){
						//insert close
						$template["location"]=$c;
						$template["periode"] = date("Y-m-d",strtotime("+1 month",strtotime($p."25")));
						$template['status_cl'] = 'Open';
						$ins_batch[] = $template;
					}
				}

				if(count($lokasi0)>0){
					foreach ($lokasi0 as $row){
						$upd_batch[] = array(
							"id" => $row->id,
							"status_cl"=>'Close',
							'updby' => $this->session->userdata('user_id'),
							'upddt' => date('Y-m-d H:i:s')
						);
					}
				}
				if(count($lokasi1)>0){
					foreach ($lokasi1 as $row){
						$upd_batch[] = array(
							"id" => $row->id,
							"status_cl"=>'Open',
							'updby' => $this->session->userdata('user_id'),
							'upddt' => date('Y-m-d H:i:s')
						);
					}
				}
				if(count($upd_batch)>0)$this->db->update_batch('closing_location',$upd_batch,'id');
				if(count($ins_batch)>0) $this->db->insert_batch('closing_location',$ins_batch);

				//clear data stock di bulan depan, jika ada
				$this->db->where_in("location_code",$arr_loc)
					->where("periode",date("Ym",strtotime("+1 month",strtotime($p."25"))))
					->where("nobar >=",$input['from_nobar'])
					->where("nobar >=",$input['to_nobar'])
					->delete('stock');
				//create new stock di bulan depan. ending bln sblm nya jadi begin bulan depan
				$this->model->insert_stock_next_month(join("','",$arr_loc), $p, $input['from_nobar'], $input['to_nobar']);
				$this->set_success("End Of Month Success");
			}else{
//				$result = 1;
//				$msg = "Lokasi tidak ditemukan";
				$this->set_error("Lokasi tidak ditemukan");
			}
		}catch (Exception $e){
//			$result = 1;
//			$msg=$e->getMessage();
			$this->set_error($e->getMessage());
		}
		redirect('Eom');
//		echo json_encode(array(
//			"status" => $result, "isError" => ($result==1),
//			"msg" => $msg, "message" => $msg
//		));
	}

}
