<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class AR extends IO_Controller {

	var $table;
	var $table_field;
	var $table_detail;
	var $table_field_detail;
	function __construct(){
		parent::__construct();
		$this->table = "cashbank_history";
		$this->table_field = array("id","docno","docno_temp","store_code","payment_type","tipe_pos_biaya","payment_date","cbtype","no_cb",
			"dbcr","reff","customer_code","bg_no","due_date","cleared_date","remark","seqno","payment_amount","payment_by",
			"status","info_status","journal_no","tahun","bulan","printno");
		$this->table_detail = "cashbank_detail";
		$this->table_field_detail = array("id","cbhistoryid","seqno","tipe","dbcr","cost_center","gl_account","remark","outstanding_amt",
			"payment_amt","customer_code","status");

		$this->load->model('Finance_model','fa');
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function save_header(){
		$input = $this->toUpper($this->input->post());
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

		$ctr = "00001";
		$yymm = date('Ym');

		$this->db->select('right(docno_temp,5) nomor')
			->where('docno_temp like', "T$yymm%")
			->order_by('docno_temp', 'desc');
		$gen = $this->db->get($this->table, 1)->row();

		if (isset($gen)) {
			$ctr = $gen->nomor + 1;
			$ctr = str_pad($ctr, 5, "0", STR_PAD_LEFT);
		}
		$this->db->trans_start();
		$input["docno"] = "T$yymm$ctr";
		$input["docno_temp"] = "T$yymm$ctr";
		$input["payment_type"] = "AR RECEIPT";
		$input["tipe_pos_biaya"] = "";
		$input["seqno"] = "";
		$input["info_status"] = "";
		$input["payment_date"] = $this->formatDate("Y-m-d",$input['payment_date']);
		$input["due_date"] = $this->formatDate("Y-m-d",$input['due_date']);
		$input["cleared_date"] = $this->formatDate("Y-m-d",$input['cleared_date']);
		$input["tahun"] = date('Y');
		$input["bulan"] = date('m');
		$input["crtdt"] = date('Y-m-d H:i:s');
		$input["crtby"] = $this->session->userdata('user_id');
		$this->db->insert($this->table,$input);
		$input['id'] = $this->db->insert_id();

		$arr_ins_det = [];
		$arr_upd_det = [];
		foreach ($detail as $key => $row){
			if(!isset($detail[$key]['tipe'])){
				$detail[$key]['tipe'] = "";
			} else{
				$detail[$key]['payment_amt'] = floor($detail[$key]['payment_amt']);
				$detail[$key]['outstanding_amt'] = floor($detail[$key]['outstanding_amt']);
			}
			if($detail[$key]['id']==0 || $detail[$key]['id']=="0" ){
				$detail[$key]['cbhistoryid'] = $input["id"];
				$detail[$key]['seqno'] = str_pad($key,3,"0",STR_PAD_LEFT);
				$detail[$key]['customer_code'] = $input['customer_code'];
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
//		pre($arr_ins_det);
		if(count($arr_ins_det)) $this->db->insert_batch($this->table_detail,$arr_ins_det);
		if(count($arr_upd_det)) $this->db->update_batch($this->table_detail,$arr_upd_det,'id');


		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->session->set_flashdata("Insert failed");
		}else{
			$this->session->set_flashdata("Insert success");
		}
		redirect("Finance/ar/edit?id=".$input['id']);
	}
	public function edit_header(){
		$input = $this->toUpper($this->input->post());
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
//		pre([$input,$detail]);
		$this->db->trans_start();
		$input["payment_date"] = $this->formatDate("Y-m-d",$input['payment_date']);
		$input["due_date"] = $this->formatDate("Y-m-d",$input['due_date']);
		$input["cleared_date"] = $this->formatDate("Y-m-d",$input['cleared_date']);
		$input["upddt"] = date('Y-m-d H:i:s');
		$this->db->update($this->table,$input,['id'=>$input['id']]);
		$last_no = $this->db->where('cbhistoryid',$input['id'])->order_by('seqno desc')->get($this->table_detail)
			->row()->seqno;
		if(isset($last_no)) $last_no = $last_no+1;
		else $last_no = "001";

		$arr_ins_det = [];
		$arr_upd_det = [];
		foreach ($detail as $key => $row){
			if(!isset($detail[$key]['tipe'])){
				$detail[$key]['tipe'] = "";
			} else{
				$detail[$key]['payment_amt'] = floor($detail[$key]['payment_amt']);
				$detail[$key]['outstanding_amt'] = floor($detail[$key]['outstanding_amt']);
			}
			if($input['status']=="CLEARED") $detail[$key]['status'] = "CLOSED";
			else $detail[$key]['status'] = "OPEN";

			if($detail[$key]['id']==0 || $detail[$key]['id']=="0" ){
				$detail[$key]['cbhistoryid'] = $input["id"];
				$detail[$key]['seqno'] = str_pad($last_no,3,"0",STR_PAD_LEFT);
				$detail[$key]['customer_code'] = $input['customer_code'];
				$detail[$key]['crtdt'] = date('Y-m-d H:i:s');
				$detail[$key]['crtby'] = $this->session->userdata('user_id');
				unset($detail[$key]['id']);
				$arr_ins_det[] = $detail[$key];
				$last_no++;
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
			$this->session->set_flashdata("Update failed");
		}else{
			$this->session->set_flashdata("Update success");
		}
		redirect("Finance/ar/edit?id=".$input['id']);
	}

	function read_data($docno){
		try {
			$read = $this->db->query($this->fa->query." where id=$docno");
			if ($read->num_rows() > 0) {
				$result = 0;
				$msg="OK";
				$data = $read->row();
				$data->detail_ar = $this->db->where("cbhistoryid",$docno)->get($this->table_detail)->result();
			} else {
				$result = 1;
				$msg="Kode tidak ditemukan";
				$data = null;
			}
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg,
			"data" => $data
		));
	}
	function delete_detail($id){
		$input = $this->input->post();
		$this->db->trans_start();
		$this->db->delete($this->table_detail,['id'=>$id]);
		$det = $this->db->get_where($this->table_detail,['cbhistoryid'=>$input['id_head']])->result();
		$total = array_sum(array_column($det,"payment_amt"));
		$this->db->update($this->table,['payment_amount'=>$total],['id'=>$input['id_head']]);
		$this->db->trans_complete();
		redirect("Finance/ar/edit?id=".$input['id_head']);
	}

}
