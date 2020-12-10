<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo extends IO_Controller {

	var $table;
	var $table_field;
	var $table_detail;
	var $table_field_detail;
	function __construct(){
		parent::__construct();
		$this->table = "promo_header";
		$this->table_field = array("id","no_promo","active_from","active_to","discount","remark");
		$this->table_detail = "promo_detail";
		$this->table_field_detail = array("id","promoid","prefix","disc","status");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($aksi=""){
		if($aksi=="") {
			$data['title'] = 'Bank Promo';
			$data['content'] = $this->load->view('bankpromo/index', $data, TRUE);
		}else if($aksi=="add"){
			$data['title'] = 'Add Bank Promo';
			$data['aksi'] = $aksi;
			$data['id'] = 0;
			$data['detail'] = [];
			$data['content'] = $this->load->view('bankpromo/entry', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Bank Promo';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['item'] = $this->db->get_where($this->table,['id'=>$data['id']])->row();
			$data['detail'] =$this->db->get_where($this->table_detail,["promoid"=>$data['id']])->result();
//			pre($data);
			$data['content'] = $this->load->view('bankpromo/entry', $data, TRUE);
		}
		$this->load->view('main', $data);
	}

	public function grid(){
		$total = $this->db->get($this->table)->num_rows();
		$this->getParamGrid_Builder("","id");
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
		$input['active_from'] = $this->formatDate("Y-m-d",$input['active_from']);
		$input['active_to'] = $this->formatDate("Y-m-d",$input['active_to']);
//		pre($input);
		$detail = [];
		if(isset($input['detail'])) $detail = $input['detail'];
		foreach ($input as $key=> $r){
			if(!in_array($key,$this->table_field)) unset($input[$key]);
		}
		foreach ($detail as $key => $row){
			foreach ($row as $key2 => $r) {
				if (!in_array($key2, $this->table_field_detail)) unset($detail[$key][$key2]);
			}
		}

//		pre([$input,$aksi]);
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
//			pre($input);
			$this->db->update($this->table,$input,["id"=>$input['id']]);
		}

		$arr_ins_det = [];
		$arr_upd_det = [];
		foreach ($detail as $key => $row){
			if($detail[$key]['id']==0 || $detail[$key]['id']=="0" ){
				$detail[$key]['promoid'] = $input["id"];
				$detail[$key]['crtdt'] = date('Y-m-d H:i:s');
				$detail[$key]['crtby'] = $this->session->userdata('user_id');
				unset($detail[$key]['id']);
				$arr_ins_det[] = $detail[$key];
			}else{
				$detail[$key]['upddt'] = date('Y-m-d H:i:s');
				$detail[$key]['updby'] = $this->session->userdata('user_id');
				$arr_upd_det[] =$detail[$key];
			}
		}
		if(count($arr_ins_det)) $this->db->insert_batch($this->table_detail,$arr_ins_det);
		if(count($arr_upd_det)) $this->db->update_batch($this->table_detail,$arr_upd_det,'id');


		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->set_error("Insert failed");
		}else{
			if($aksi=="add")$this->set_success("Insert success...");
			else $this->set_success("Update success...");
		}
		redirect("promo/index/edit?id=".$input['id']);
	}

	function delete_detail($id){
		$input = $this->input->post();
		$this->db->trans_start();
		$this->db->delete($this->table_detail,['id'=>$id]);
		$this->db->trans_complete();
		redirect("promo/index/edit?id=".$input['id_head']);
	}
}
