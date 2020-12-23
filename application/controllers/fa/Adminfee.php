<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminfee extends IO_Controller {

	var $table;
	var $table_field;
	function __construct(){
		parent::__construct();
		$this->table = "payment_type_adm";
		$this->table_field = array("id","paymenttypeid","effectivedate","persen");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($aksi=""){
		if($aksi=="") {
			$data['title'] = 'Payment Admin Fee';
			$data['content'] = $this->load->view('adminfee/index', $data, TRUE);
		}else if($aksi=="add"){
			$data['title'] = 'Add Payment Admin Fee';
			$data['aksi'] = $aksi;
			$data['id'] = 0;
			$data['paymenttype'] = $this->db->get('payment_type')->result();
			$data['content'] = $this->load->view('adminfee/entry', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Payment Admin Fee';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['item'] = $this->db->get_where($this->table,['id'=>$data['id']])->row();
			$data['paymenttype'] = $this->db->get('payment_type')->result();
			$data['content'] = $this->load->view('adminfee/entry', $data, TRUE);
		}
		$this->load->view('main', $data);
	}

	public function grid(){
		$total = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"total",
			"table"=>$this->table." a",
			"sortir"=>"id",
			"special"=>[],
			"select"=>"a.*, b.description",
			"join"=>["payment_type b"=>"b.id=a.paymenttypeid"]
		));
		$data = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"query",
			"table"=>$this->table." a",
			"sortir"=>"id",
			"special"=>[],
			"select"=>"a.*, b.description",
			"join"=>["payment_type b"=>"b.id=a.paymenttypeid"]
		));
		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>$total,
				"data" =>$data)
		);
	}

	public function entryp($aksi="add"){
		$input = $this->toUpper($this->input->post());
		$input['effectivedate'] = $this->formatDate("Y-m-d",$input['effectivedate']);
//		pre($input);
		foreach ($input as $key=> $r){
			if(!in_array($key,$this->table_field)) unset($input[$key]);
		}

		$this->db->trans_start();
		if($aksi=="add"){
			unset($input['id']);
			$input['crtdt'] = date('Y-m-d H:i:s');
			$input['crtby'] = $this->session->userdata('user_id');
//			pre($input);
			$this->db->insert($this->table, $input);
			$input['id'] = $this->db->insert_id();
//			pre($input);
		}else{
			$input['upddt'] = date('Y-m-d H:i:s');
			$input['updby'] = $this->session->userdata('user_id');
			$this->db->update($this->table,$input,["id"=>$input['id']]);
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->set_error("Insert failed");
		}else{
			if($aksi=="add")$this->set_success("Insert success...");
			else $this->set_success("Update success...");
		}
		redirect("fa/Adminfee/index/edit?id=".$input['id']);
	}
}
