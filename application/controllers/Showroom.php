<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Showroom extends IO_Controller {

	var $table;
	var $table_field;
	var $table_detail;
	var $table_field_detail;
	function __construct(){
		parent::__construct();
		$this->table = "so_showroom_header";
		$this->table_field = array("docno","doc_date","location_code","trans_date","seri_pajak","provinsi_id","regency_id","remark",
			"jenis_so","customer_code","salesman_id","tipe_komisi","komisi_persen","disc1_persen","disc2_persen",
			"qty_item","qty_order","gross_sales","total_ppn","total_discount","sales_before_tax","sales_after_tax",
			"total_komisi","total_dp","sisa_faktur","total_hpp","status","verifikasi_fa","sales_pada_toko","so_no",
			"jumlah_print");
		$this->table_detail = "so_showroom_detail";
		$this->table_field_detail = array("id","docno","product_tipe","seqno","nobar","tipe","komisi","qty_order","qty_sales","qty_refund",
			"uom_code","unit_price","disc1_persen","disc1_amount","disc2_persen","disc2_amount","disc_total","net_unit_price",
			"sales_before_ppn","sales_after_ppn","net_total_price","jumlah_hpp","status_detail","add_cost1","add_cost2","add_cost3");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index(){
		if($aksi=="") {

		}else if($aksi=="add"){
			$data['title'] = 'Add Sales Order Showroom';
			$data['aksi'] = $aksi;
			$data['id'] = 0;
			$data['paymenttype'] = $this->db->get('payment_type')->result();
			$data['content'] = $this->load->view('adminfee/entry', $data, TRUE);
		}else if($aksi=="edit"){
			$data['title'] = 'Edit Sales Order Showroom';
			$data['aksi'] = $aksi;
			$data['id'] = $this->input->get('id');
			$data['item'] = $this->db->get_where($this->table,['id'=>$data['id']])->row();
			$data['paymenttype'] = $this->db->get('payment_type')->result();
			$data['content'] = $this->load->view('adminfee/entry', $data, TRUE);
		}
		$data['title'] = 'Sales Order Showroom';
		$data['content'] = $this->load->view('showroom/index', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function form($docno = ""){
		$param = $this->input->post();
		$tgl = date("ymd");
		if($docno=="") {
			if (isset($param['tanggal'])) $tgl = $this->formatDate("ymd", $param['tanggal']);
			$lokasi = $this->session->userdata(sess_location_code);
			$prefix = $lokasi . "." . $tgl;
			$nomor = $this->db->select("right(docno,4) as nomor")
				->where("docno like '$prefix%'")
				->where('location_code', $lokasi)
				->order_by('docno desc')
				->limit(1)
				->get($this->table)->row()->nomor;
			if (isset($nomor)) $nomor = $nomor + 1;
			else $nomor = 1;
			$docno = $lokasi . "." . $tgl . "." . str_pad($nomor, 4, "0", STR_PAD_LEFT);
			$insert = array(
				"docno"=>$docno,
				"doc_date" => $this->formatDate("Y-m-d",$tgl),
				"location_code" => $lokasi
			);
			$this->db->insert($this->table, $insert);
		}
		$head = $this->db->select("a.*, b.store_code, b.store_name")
			->where('docno',$docno)
			->join("profile_p b","b.default_stock_l=a.location_code")
			->get($this->table." a")->row();
		$data['title'] = 'Add Sales Order Showroom';
		$data['docno'] = $docno;
		$data['header'] = $head;
		$data['detail'] = $this->db->get_where($this->table_detail,["docno"=>$docno])->result();
		$data['paymenttype'] = $this->db->get('payment_type')->result();
		$data['content'] = $this->load->view('showroom/entry', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function grid(){
		$lokasi = $this->session->userdata(sess_location_code);
		$total = $this->db->where("location_code",$lokasi)->get($this->table)->num_rows();
		$this->db->select("a.*, b.store_name");
		$this->getParamGrid_Builder("","id");
		$this->db->join("profile_p b", "b.default_stock_l=a.location_code");
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
