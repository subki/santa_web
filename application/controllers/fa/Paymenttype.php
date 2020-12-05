<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Paymenttype extends IO_Controller {

	var $table;
	var $table_field;
	function __construct(){
		parent::__construct();
		$this->table = "payment_type";
		$this->table_field = array("id","tipe","description","accno");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($aksi=""){
		if($aksi=="") {
			$data['title'] = 'Payment Type';
			$data['content'] = $this->load->view('Paymenttype/index', $data, TRUE);
		}else if($aksi=="add"){
			$data['title'] = 'Add Payment Type';
			$data['aksi'] = $aksi;
			$data['id'] = 0;
			$data['coa'] = $this->db->get('coa')->result();
			$data['content'] = $this->load->view('Paymenttype/entry', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Payment Type';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['item'] = $this->db->get_where($this->table,['id'=>$data['id']])->row();
			$data['coa'] = $this->db->get('coa')->result();
			$data['content'] = $this->load->view('Paymenttype/entry', $data, TRUE);
		}
		$this->load->view('main', $data);
	}

	public function grid(){
		$total = $this->db->get($this->table)->num_rows();
		$this->getParamGrid_Builder("","id");
		$data = $this->db->get($this->table)->result();
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
		redirect("fa/Paymenttype/index/edit?id=".$input['id']);
	}
}
