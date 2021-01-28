<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Showroomrecap extends IO_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($location_code=""){
		if($location_code=="") $location_code=$this->session->userdata(sess_location_code);

		$data['title'] = 'Rekap Sales Order Showroom';
		$data['locations'] = $this->getLocations('showroom','showroom');
		$data['location_code'] = $location_code;
		$data['content'] = $this->load->view('showroom/rekap', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function grid($location_code=""){
		$option = array(
			"rows"=>100,
			"table"=>"sales_trans_header sth",
			"sortir"=>"doc_date",
			"special"=>["jenis_faktur"=>"SHOWROOM"],
			"select"=>"sth.*, c.customer_name, c.lokasi_stock",
			"join"=>["customer c"=>"c.customer_code=sth.customer_code"],
			"posisi"=>["inner"]
		);
		if($location_code!=""){
			$option['special']["location_code"] = $location_code;
		}
		$total1 = $this->getParamGrid_BuilderComplete($option);
		$total = $total1->total;
		$data = $total1->data;
		echo json_encode(array(
				"total"=>$total,
				"rows" =>$data)
		);
	}
	function form($aksi=""){
		$input = $this->input->get();
		$data['aksi']=$aksi;

		$loc = $this->session->userdata(sess_location_code);
		if(isset($input['location_code'])) $loc = $input['location_code'];
		$location = $this->getLocations("","",$loc,"");
		$id = 0;
		if(isset($input['id'])){
			$id = $input['id'];
			$header = $this->db->where("id",$input['id'])->get("sales_trans_header")->row();
			$location = $this->getLocations("","","",$header->customer_code);
		}

		if(!isset($location)){
			$this->set_error("Informasi Lokasi tidak ditemukan");
		}
		if($aksi=="add"){
			$data['title_main'] = 'Add Rekap Showroom';
			$data['location_code'] = $loc;
			$data['location_data'] = $location;
			$data['id'] = $id;
			$data['content'] = $this->load->view('showroom/form', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title_main'] = 'Edit Rekap Showroom';
			$data['location_code'] = $loc;
			$data['location_data'] = $location;
			$data['id'] = $id;
			$data['content'] = $this->load->view('showroom/form', $data, TRUE);
		}else{
			$data['title_main'] = 'View Rekap Showroom';
			$data['location_code'] = $loc;
			$data['location_data'] = $location;
			$data['id'] = $id;
			$data['content'] = $this->load->view('showroom/form', $data, TRUE);
		}
		$this->load->view('main',$data);
	}

}
