<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Coa extends IO_Controller {

	var $table;
	var $table_field;
	function __construct(){
		parent::__construct();
		$this->table = "coa";
		$this->table_field = array("account_no","acc_description","parent","level","header_detail","normal_balance","account_type","pro_beg_bal");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($aksi=""){
		if($aksi=="") {
			$data['title'] = 'Chart Of Account';
			$data['content'] = $this->load->view('coa/index', $data, TRUE);
		}else if($aksi=="add"){
			$data['title'] = 'Add Chart Of Account';
			$data['aksi'] = $aksi;
			$data['id'] = '';
			$data['content'] = $this->load->view('coa/entry', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Chart Of Account';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['item'] = $this->db->get_where($this->table,['account_no'=>$data['id']])->row();
			$data['content'] = $this->load->view('coa/entry', $data, TRUE);
		}
		$this->load->view('main', $data);
	}

	public function grid(){
		$total = $this->db->get($this->table)->num_rows();
		$this->db->select("a.*, b.acc_description as parentname");
		$this->getParamGrid_Builder("","account_no");
		$this->db->join($this->table." b","b.account_no=a.parent","left");
		$data = $this->db->get($this->table." a")->result();
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
			$input['crtdt'] = date('Y-m-d H:i:s');
			$input['crtby'] = $this->session->userdata('user_id');
			$this->db->insert($this->table, $input);
		}else{
			$input['upddt'] = date('Y-m-d H:i:s');
			$input['updby'] = $this->session->userdata('user_id');
			$this->db->update($this->table,$input,["account_no"=>$input['account_no']]);
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->set_error("Insert failed");
		}else{
			if($aksi=="add")$this->set_success("Insert success...");
			else $this->set_success("Update success...");
		}
		redirect("fa/coa/index/edit?id=".$input['account_no']);
	}
}
