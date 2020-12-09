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
	var $table_bayar;
	var $table_field_bayar;
	function __construct(){
		parent::__construct();
		$this->table = "so_showroom_header";
		$this->table_field = array("docno","doc_date","location_code","trans_date","promoid","provinsi_id","regency_id","remark",
			"jenis_so","customer_code","salesman_id","tipe_komisi","komisi_persen","disc1_persen","disc2_persen",
			"qty_item","qty_order","gross_sales","total_ppn","total_discount","sales_before_tax","sales_after_tax",
			"total_komisi","total_dp","sisa_faktur","total_hpp","status","verifikasi_fa","sales_pada_toko","so_no",
			"jumlah_print");
		$this->table_detail = "so_showroom_detail";
		$this->table_field_detail = array("id","docno","product_tipe","seqno","nobar","tipe","komisi","qty_order","qty_sales","qty_refund",
			"uom_code","unit_price","disc1_persen","disc1_amount","disc2_persen","disc2_amount","disc_total","net_unit_price",
			"sales_before_ppn","sales_after_ppn","net_total_price","jumlah_hpp","status_detail","add_cost1","add_cost2","add_cost3");
		$this->table_bayar = "kasir_payment";
		$this->table_field_bayar = array("id","trx_no","paymenttypeid","keterangan","nilai_bayar");
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index(){
//		if($aksi=="") {
//
//		}else if($aksi=="add"){
//			$data['title'] = 'Add Sales Order Showroom';
//			$data['aksi'] = $aksi;
//			$data['id'] = 0;
//			$data['paymenttype'] = $this->db->get('payment_type')->result();
//			$data['content'] = $this->load->view('adminfee/entry', $data, TRUE);
//		}else if($aksi=="edit"){
//			$data['title'] = 'Edit Sales Order Showroom';
//			$data['aksi'] = $aksi;
//			$data['id'] = $this->input->get('id');
//			$data['item'] = $this->db->get_where($this->table,['id'=>$data['id']])->row();
//			$data['paymenttype'] = $this->db->get('payment_type')->result();
//			$data['content'] = $this->load->view('adminfee/entry', $data, TRUE);
//		}
		$data['title'] = 'Sales Order Showroom';
		$data['content'] = $this->load->view('showroom/index', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function form($docno = ""){
		$param = $this->input->post();
		$tgl = date("ymd");
		$lokasi = $this->session->userdata(sess_location_code);
		$customer = $this->db->get_where("customer",["lokasi_stock"=>$lokasi])->row();
		if($docno=="") {
			if (isset($param['tanggal'])) $tgl = $this->formatDate("ymd", $param['tanggal']);
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
				"trans_date" => $this->formatDate("Y-m-d",$tgl),
				"location_code" => $lokasi,
				"provinsi_id" => $customer->provinsi_id,
				"regency_id" => $customer->regency_id,
				"customer_code" => $customer->customer_code
			);
			$this->db->insert($this->table, $insert);
		}
		$head = $this->db->select("a.*, b.store_code, b.store_name, (select sum(nilai_bayar) from kasir_payment where trx_no=a.docno) payment_sum")
			->where('docno',$docno)
			->join("profile_p b","b.default_stock_l=a.location_code")
			->get($this->table." a")->row();
		$uom_stk = $this->session->userdata("uom stock");
		$product = $this->db->select("p.id as product_id, p.satuan_jual, u.uom_id, p.sku, p.product_code, p.product_name
				, (select ifnull(convertion,1) from product_uom_convertion where uom_from=u.uom_code and uom_to=$uom_stk) as convertion
				, p.article_code, s.saldo_akhir, ifnull(ah.hpp1,0) hpp1, ifnull(ah.hpp2,0) hpp2, ifnull(ah.hpp_ekspedisi,0) hppe")
			->join("stock s","s.nobar=p.sku and s.periode='".$this->formatDate('Ym',$head->doc_date)."' and location_code='$lokasi'")
			->join("article_hpp ah", "ah.article_code=p.article_code and ah.effdate<='".$this->formatDate('Y-m-d',$head->doc_date)."'","left")
			->join('product_uom u','u.uom_code=p.satuan_jual')
			->group_by("p.sku, p.product_code, p.article_code")
			->get("product p")->result();
		$detail = $this->db->select("d.*, p.satuan_jual, p.product_code, u.uom_id")
			->where("docno",$docno)
			->join("product p","p.sku=d.nobar")
			->join('product_uom u','u.uom_code=p.satuan_jual')
			->get($this->table_detail." d")->result();
		$bayar = $this->db->select("k.*, p.description")
			->where("k.trx_no",$docno)
			->join("payment_type p","p.id=k.paymenttypeid")
			->get($this->table_bayar." k")->result();

		$data['promo_header'] = $this->db->get("promo_header")->result();
		$data['promo_detail'] = $this->db->where_in("promoid", array_column($data['promo_header'],"id"))->get("promo_detail")->result();
		$data['title'] = 'Add Sales Order Showroom';
		$data['docno'] = $docno;
		$data['header'] = $head;
		$data['products'] = $product;
		$data['detail'] = $detail;
		$data['bayar'] = $bayar;
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

	public function entryp(){
		$input = $this->toUpper($this->input->post());
//		pre($input);
		$header = $input['header'];
		$detail = $input['detailitem'];
		$payment = $input['detail'];
		foreach ($header as $key=> $r){
			if(!in_array($key,$this->table_field)) unset($header[$key]);
		}
		foreach ($detail as $key => $row){
			foreach ($row as $key2 => $r) {
				if (!in_array($key2, $this->table_field_detail)) unset($detail[$key][$key2]);
			}
		}
		foreach ($payment as $key => $row){
			foreach ($row as $key2 => $r) {
				if (!in_array($key2, $this->table_field_bayar)) unset($payment[$key][$key2]);
			}
		}

		$this->db->trans_start();
		$header['upddt'] = date('Y-m-d H:i:s');
		$header['updby'] = $this->session->userdata(sess_user_id);
		$header['salesman_id'] = $this->session->userdata(sess_user_id);
		$header['qty_item'] = count($detail);
		$header['qty_order'] = array_sum(array_column($detail,"qty_order"));
		if(isset($header['promoid']) && $header['promoid']!= null && $header['promoid']!="" && $header['promoid']!="0"){
			$header['jenis_so']= "PROMO";
		}else $header['jenis_so']= "NORMAL";
		$this->db->update($this->table,$header,["docno"=>$header['docno']]);
		$arr_ins_det = [];
		$arr_upd_det = [];
		foreach ($detail as $key => $row){
			if($detail[$key]['id']==0 || $detail[$key]['id']=="0" ){
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

		$arr_ins_pay = [];
		$arr_upd_pay = [];
		foreach ($payment as $key => $row){
			if($payment[$key]['id']==0 || $payment[$key]['id']=="0" ){
				$payment[$key]['crtdt'] = date('Y-m-d H:i:s');
				$payment[$key]['crtby'] = $this->session->userdata('user_id');
				unset($payment[$key]['id']);
				$arr_ins_pay[] = $payment[$key];
			}else{
				$payment[$key]['upddt'] = date('Y-m-d H:i:s');
				$payment[$key]['updby'] = $this->session->userdata('user_id');
				$arr_upd_pay[] =$payment[$key];
			}
		}
		if(count($arr_ins_pay)) $this->db->insert_batch($this->table_bayar,$arr_ins_pay);
		if(count($arr_upd_pay)) $this->db->update_batch($this->table_bayar,$arr_upd_pay,'id');

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->set_error("Insert failed");
		}else{
			$this->set_success("Transaction success...");
		}
		redirect("showroom/form/".$header['docno']);
	}
}