<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Setupreceipt extends IO_Controller {

	var $table;
	var $table_field;
	function __construct(){
		parent::__construct();
		$this->table = "setup_receipt";
		$this->table_field = array("id","location_code","headerf","footerf");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($aksi=""){
		if($aksi=="") {
			$data['title'] = 'Setup Receipt';
			$data['content'] = $this->load->view('receipt/index', $data, TRUE);
		}else if($aksi=="add"){
			$data['title'] = 'Add Setup Receipt';
			$data['aksi'] = $aksi;
			$data['id'] = 0;
			$data['location'] = $this->db->get('location')->result();
			$data['content'] = $this->load->view('receipt/entry', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Setup Receipt';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['item'] = $this->db->get_where($this->table,['id'=>$data['id']])->row();
			$data['location'] = $this->db->get('location')->result();
			$data['content'] = $this->load->view('receipt/entry', $data, TRUE);
		}
		$this->load->view('main', $data);
	}

	public function grid(){
		$total1 = $this->getParamGrid_BuilderComplete(array("tipe"=>"total","table"=>$this->table,"sortir"=>"id"));
		$total = $total1->total;
		$data = $total1->data;
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
		redirect("Setupreceipt/index/edit?id=".$input['id']);
	}
}
