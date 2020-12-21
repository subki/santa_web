<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening extends IO_Controller {

	var $table;
	var $table_field;
	function __construct(){
		parent::__construct();
		$this->table = "master_rekening";
		$this->table_field = array("id","company_code","tipe_rekening","cost_center","accno","cbaccno","accname","bank_code","manual_receipt_no","tr_code");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($aksi=""){
		if($aksi=="") {
			$data['title'] = 'Master Rekening';
			$data['content'] = $this->load->view('Rekening/index', $data, TRUE);
		}else if($aksi=="add"){
			$data['title'] = 'Add Master Rekening';
			$data['aksi'] = $aksi;
			$data['id'] = 0;
			$data['coa'] = $this->db->get('coa')->result();
			$data['content'] = $this->load->view('Rekening/entry', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Master Rekening';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['item'] = $this->db->get_where($this->table,['id'=>$data['id']])->row();
			$data['coa'] = $this->db->get('coa')->result();
			$data['content'] = $this->load->view('Rekening/entry', $data, TRUE);
		}
		$this->load->view('main', $data);
	}

	public function grid(){
		$total = $this->getParamGrid_BuilderComplete(array("tipe"=>"total","table"=>$this->table,"sortir"=>"id"));
		$data = $this->getParamGrid_BuilderComplete(array("tipe"=>"query","table"=>$this->table,"sortir"=>"id"));
		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>$total,
				"data" =>$data)
		);
	}

	public function entryp($aksi="add"){
		$input = $this->toUpper($this->input->post());
		foreach ($input as $key=> $r){
			if(!in_array($key,$this->table_field)) unset($input[$key]);
		}

		$this->db->trans_start();
		if($aksi=="add"){
			unset($input['id']);
			$input['crtdt'] = date('Y-m-d H:i:s');
			$input['crtby'] = $this->session->userdata('user_id');
			$this->db->insert($this->table, $input);
			$input['id'] = $this->db->insert_id();
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
		redirect("fa/Rekening/index/edit?id=".$input['id']);
	}
}
