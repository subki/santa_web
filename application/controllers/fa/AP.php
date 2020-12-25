<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class AP extends IO_Controller {

	var $table;
	var $table_field;
	var $table_detail;
	var $table_field_detail;
	function __construct(){
		parent::__construct();
		$this->table = "cashbank_history";
		$this->table_field = array("id","trx_type","docno","docno_temp","store_code","payment_type","tipe_pos_biaya","payment_date","cbtype","no_cb",
			"dbcr","reff","customer_code","bg_no","due_date","cleared_date","remark","seqno","payment_amount","payment_by",
			"status","info_status","journal_no","tahun","bulan","printno");
		$this->table_detail = "cashbank_detail";
		$this->table_field_detail = array("id","cbhistoryid","seqno","tipe","dbcr","cost_center","gl_account","remark","outstanding_amt",
			"payment_amt","customer_code","status","associatedwith","associatedid");

		$this->load->model('Finance_model','fa');
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	function index($aksi=""){
		if($aksi=="") {
			$data['title'] = 'Payment Voucher';
			$data['content'] = $this->load->view('vFinanceAP', $data, TRUE);
		}else if($aksi=="add"){
			$data['title'] = 'Add Payment Voucher';
			$data['aksi'] = $aksi;
			$data['content'] = $this->load->view('vFinanceAP_form', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Payment Voucher';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['content'] = $this->load->view('vFinanceAP_form', $data, TRUE);
		}
		$this->load->view('main', $data);
	}

	public function grid(){
		$total1 = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"total",
			"table"=>$this->table." a",
			"sortir"=>"id",
		));
		$total = $total1->total;
		$data = $total1->data;
		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>$total,
				"data" =>$data)
		);
	}

	public function getLastNumber($bankcode){
		$yymm = date('ym');
		$this->db->select('right(docno_temp,4) nomor')
			->where('docno_temp like', "BBK$bankcode$yymm%")
			->order_by('docno_temp', 'desc');
		$gen = $this->db->get($this->table, 1)->row();
		$ctr = "00000";
		if (isset($gen)) {
			$ctr = $gen->nomor;
			$ctr = str_pad($ctr, 4, "0", STR_PAD_LEFT);
		}
		echo json_encode(array(
			"last"=> "BBK".$bankcode.$ctr
		));
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
		$yymm = date('ym');
		$pref = $this->db->where("cbaccno",$input['no_cb'])->get("master_rekening")->row()->tr_code;

		$this->db->select('right(docno_temp,4) nomor')
			->where('docno_temp like', "BBK$pref$yymm%")
			->order_by('docno_temp', 'desc');
		$gen = $this->db->get($this->table, 1)->row();

		if (isset($gen)) {
			$ctr = $gen->nomor + 1;
			$ctr = str_pad($ctr, 4, "0", STR_PAD_LEFT);
		}
		$this->db->trans_start();
		$input["docno"] = "BBK$pref$yymm$ctr";
		$input["docno_temp"] = "BBK$pref$yymm$ctr";
		$input["payment_type"] = "AP PAYMENT";
		$input["store_code"] = $this->session->userdata(sess_store_code);
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
		redirect("fa/ap/index/edit?id=".$input['id']);
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
		redirect("fa/ap/index/edit?id=".$input['id']);
	}

	function read_data($docno){
		try {
			$read = $this->db->get_where($this->table,['id'=>$docno]);
			if ($read->num_rows() > 0) {
				$result = 0;
				$msg="OK";
				$data = $read->row();
				$data->detail_ap = $this->db->where("cbhistoryid",$docno)->get($this->table_detail)->result();
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


	public function getFaktur(){
//		$total = $this->db->get("sales_invoice")->num_rows();
//		$this->db->select("a.*, a.crtdt tanggal_crt, a.upddt tanggal_upd
//                  , DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
//                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
//                  , l.location_code, l.description as locname, c.gl_account");
//		$this->getParamGrid_Builder("","id");
//		$this->db->where("CONCAT('SALES_INVOICE',a.id) NOT IN (select CONCAT(associatedwith,associatedid) from $this->table_detail d where d.customer_code=a.customer_code)");
//		$this->db->join("customer c","c.customer_code=a.customer_code");
//		$this->db->join("location l","l.location_code=c.lokasi_stock");
//		$data = $this->db->get("sales_invoice a")->result();
//		echo json_encode(array(
//				"status" => 1,
//				"msg" => "OK",
//				"total"=>$total,
//				"rows" =>$data)
//		);
		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>0,
				"rows" =>[])
		);
	}

	public function printap(){
		$input = $this->input->post();
//        pre($input);
		$dt = $this->db->where('docno',$input['docno'])->get('sales_proforma')->row();
		$invoice = json_decode($dt->sales_invoice_data);
		$query = $this->db->select('a.*, c.customer_name, c.address1, c.address2, rg.name as regency_name')
			->where_in('a.id',$invoice)
			->join('customer c', 'c.customer_code=a.customer_code')
			->join('regencies rg', 'rg.id=c.regency_id');
//        pre($sales_inv);

		$data['header'] = $query->get('sales_invoice a')->row();
		$data['detail'] = $query->get('sales_invoice a')->result();
		$this->load->library('pdf');
		$this->pdf->load_view('print/proforma_invoice', $data);
		$this->pdf->render();

		$this->pdf->stream($input['docno'].'.pdf',array("Attachment"=>0));
//        $this->load->view('print/proforma_invoice',$data);
	}

}
