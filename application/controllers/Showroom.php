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
			"total_komisi","total_dp","sisa_faktur","total_hpp","status","verifikasi_fa","sales_pada_toko","so_no","payment_sum","payment_ret",
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
		$tgl = date("Y-m-d");
		$param = $this->input->post();
		$location_code = $this->session->userdata(sess_location_code);
		if(isset($param['tanggal'])) $tgl = $param['tanggal'];
		if(isset($param['location_code'])) $location_code = $param['location_code'];


		$lokasi = $this->db->select("l.location_code, l.description, c.customer_code, cb.store_code, p.store_name")
			->from("customer c")
			->join("location l","l.location_code=c.lokasi_stock")
			->join("cabang cb","cb.location_code=l.location_code")
			->join("profile_p p","cb.store_code=p.store_code")
			->where("c.gol_customer",'showroom')
			->where("c.customer_class",'showroom')
			->get()->result();

		$data['title'] = 'Sales Order Showroom';
		$data['location_code'] = $location_code;
		$data['tanggal'] = $tgl;
		$data['lokasi'] = $lokasi;
		$data['content'] = $this->load->view('showroom/index', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function rekapindex(){
		$tgl = date("Y-m-d");
		$param = $this->input->post();
		$location_code = $this->session->userdata(sess_location_code);
		if(isset($param['tanggal'])) $tgl = $param['tanggal'];
		if(isset($param['location_code'])) $location_code = $param['location_code'];
		$pt = $this->db->get('payment_type')->result();
		$paymenttype = "";
		foreach ($pt as $r){
			$paymenttype .= '<option value="'.$r->id.'">'.$r->descripiton.'</option>';
		}
		$lokasi = $this->db->select("l.location_code, l.description, c.customer_code, cb.store_code, p.store_name")
			->from("customer c")
			->join("location l","l.location_code=c.lokasi_stock")
			->join("cabang cb","cb.location_code=l.location_code")
			->join("profile_p p","cb.store_code=p.store_code")
			->where("c.gol_customer",'showroom')
			->where("c.customer_class",'showroom')
			->get()->result();
		$data['title'] = 'Sales Order Showroom';
		$data['tanggal'] = $tgl;
		$data['lokasi'] = $lokasi;
		$data['location_code'] = $location_code;
		$data['paymenttype_json'] = $pt;
		$data['paymenttype'] = $paymenttype;
		$data['content'] = $this->load->view('showroom/indexrekap', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function form($docno = ""){
		$param = $this->input->get();
//		pre($param);
		$tgl = date("ymd");
		$tanggal = date("Y-m-d");
		$lokasi = $this->session->userdata(sess_location_code);
		if(isset($param['location_code'])) $lokasi=$param['location_code'];
		if (isset($param['tanggal'])) {
			$tgl = $this->formatDate("ymd", $param['tanggal']);
			$tanggal = $this->formatDate("Y-m-d", $param['tanggal']);
		}
		$customer = $this->db->get_where("customer",["lokasi_stock"=>$lokasi])->row();
//		pre($docno);
		if($docno=="") {
			$cek = $this->db->order_by("docno asc")->limit(1)
				->get_where($this->table,["location_code"=>$lokasi,"doc_date"=>$tgl,"status"=>"OPEN"])
				->row();
			if(isset($cek)){
				redirect("showroom/form/".$cek->docno."?tanggal=".$param['tanggal']."&location_code=".$param['location_code']);
			}
			$prefix = $lokasi . "." . $tgl;
			$nomor = $this->db->select("right(docno,3) as nomor")
				->where("docno like '$prefix%'")
				->where('location_code', $lokasi)
				->order_by('docno desc')
				->limit(1)
				->get($this->table)->row()->nomor;
			if (isset($nomor)) $nomor = $nomor + 1;
			else $nomor = 1;
			$docno = $lokasi . "." . $tgl . "." . str_pad($nomor, 3, "0", STR_PAD_LEFT);
			$insert = array(
				"docno"=>$docno,
				"doc_date" => $this->formatDate("Y-m-d",$param['tanggal']),
				"trans_date" => $this->formatDate("Y-m-d",$param['tanggal']),
				"location_code" => $lokasi,
				"provinsi_id" => $customer->provinsi_id,
				"regency_id" => $customer->regency_id,
				"customer_code" => $customer->customer_code,
				"status" => "OPEN",
				"crtdt"=>date("Y-m-d H:i:s"),
				"crtby"=>$this->session->userdata('user_id'),
				"jumlah_print"=>0
			);
			$this->db->insert($this->table, $insert);
			redirect("showroom/form/".$docno."?tanggal=".$param['tanggal']."&location_code=".$param['location_code']);
		}
		$head = $this->db->select("a.*, b.store_code, b.store_name, (select sum(nilai_bayar) from kasir_payment where trx_no=a.docno) payment_sum")
			->where('docno',$docno)
			->join("location l","l.location_code=a.location_code")
			->join("cabang cb","cb.location_code=l.location_code")
			->join("profile_p b","cb.store_code=b.store_code")
			->get($this->table." a")->row();
//		pre($head);
		$uom_stk = $this->session->userdata("uom stock");
//		pre($this->session->userdata());
		$product = $this->db->select("p.id as product_id, p.satuan_jual, u.uom_id, p.sku, p.product_code, p.product_name
				, (select ifnull(convertion,1) from product_uom_convertion where uom_from=u.uom_code and uom_to=$uom_stk) as convertion
				, p.article_code, s.saldo_akhir, ifnull(ah.hpp1,0) hpp1, ifnull(ah.hpp2,0) hpp2, ifnull(ah.hpp_ekspedisi,0) hppe")
			->from("product p")
			->join("stock s","s.nobar=p.sku and s.periode='".$this->formatDate('Ym',$head->doc_date)."' and s.location_code='$lokasi'")
			->join("article_hpp ah", "ah.article_code=p.article_code and ah.effdate<='".$this->formatDate('Y-m-d',$head->doc_date)."'","left")
			->join('product_uom u','u.uom_code=p.satuan_jual')
			->group_by("p.sku, p.product_code, p.article_code")
			->get()->result();
//		pre($product);
		$detail = $this->db->select("d.*, p.satuan_jual, p.product_code, u.uom_id")
			->where("docno",$docno)
			->join("product p","p.sku=d.nobar")
			->join('product_uom u','u.uom_code=p.satuan_jual')
			->get($this->table_detail." d")->result();
		$bayar = $this->db->select("k.*, p.description")
			->where("k.trx_no",$docno)
			->join("payment_type p","p.id=k.paymenttypeid")
			->get($this->table_bayar." k")->result();

		$lokasis = $this->db->select("l.location_code, l.description, c.customer_code, cb.store_code, p.store_name")
			->from("customer c")
			->join("location l","l.location_code=c.lokasi_stock")
			->join("cabang cb","cb.location_code=l.location_code")
			->join("profile_p p","cb.store_code=p.store_code")
			->where("c.gol_customer",'showroom')
			->where("c.customer_class",'showroom')
			->get()->result();
//		pre($product);
		$data['location_code'] = $lokasi;
		$data['tanggal'] = $tanggal;
//		pre($data);
		$data['locations'] = $lokasis;
		$data['promo_header'] = $this->db->get("promo_header")->result();
		$data['promo_detail'] = $this->db->where_in("promoid", array_merge(array_column($data['promo_header'],"id"),[""]))->get("promo_detail")->result();
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
		$param = $this->input->get();
		$lokasi = $this->session->userdata(sess_location_code);
		$tanggal=date("Y-m-d");
		if(isset($param['location_code'])) $lokasi = $param['location_code'];
		if(isset($param['tanggal'])) $tanggal = $param['tanggal'];
		$total1 = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"total",
			"table"=>$this->table." a",
			"sortir"=>"docno",
			"special"=>["location_code"=>$lokasi,'doc_date'=>$tanggal],
			"select"=>"a.*, b.store_name",
			"join"=>["profile_p b"=>"b.default_stock_l=a.location_code"]
		));
		$total = $total1->total;
		$data = $total1->data;
		echo json_encode(array(
				"total"=>$total,
				"rows" =>$data)
		);
	}
	public function gridrekap(){
		$param = $this->input->get();
		$lokasi = $this->session->userdata(sess_location_code);
		$tanggal=date("Y-m-d");
		if(isset($param['location_code'])) $lokasi = $param['location_code'];
		if(isset($param['tanggal'])) $tanggal = $param['tanggal'];
		$total1 = $this->getParamGrid_BuilderComplete(array(
			"tipe"=>"total",
			"table"=>"rekap_payment_harian a",
			"sortir"=>"tanggal",
			"special"=>["a.location_code"=>$lokasi,'a.tanggal'=>$tanggal],
			"select"=>"a.*, b.description locname, pt.description ptname",
			"join"=>["location b"=>"b.location_code=a.location_code","payment_type pt"=>"pt.id=a.paymenttypeid"]
		));
		$total = $total1->total;
		$data = $total1->data;

		echo json_encode(array(
				"total"=>$total,
				"rows" =>$data)
		);
	}

	public function add_rekap(){
		$param = $this->input->post();
		$param['crtby'] = $this->session->userdata(sess_user_id);
		$param['crtdt'] = date('Y-m-d H:i:s');
		$this->db->insert("rekap_payment_harian",$param);
		redirect('showroom/rekapindex');
	}
	public function edit_rekap($field=""){
		$param = $this->input->post();
		$item = $this->db->get_where("rekap_payment_harian",["id"=>$param['id']])->row();
		if($field==="adminfee") {
			$adminamt = $item->total_bayar * $param['adminfee'] / 100;
			$param['adminamt'] = $adminamt;
		}else if($field=="amount"){
			$adminamt = $param['total_bayar']*$item->adminfee/100;
			$param['adminamt'] = $adminamt;
		}
		$this->db->update("rekap_payment_harian",$param,["id"=>$param['id']]);
		echo json_encode(array(
			"status"=>0,
			"msg"=>"OK"
		));
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
		$header['status']= "PAID";
//		pre($header);
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
		$this->print_so($header['docno']);
		redirect("showroom/form/?tanggal=".$header['doc_date']."&location_code=".$header['location_code']);
//		redirect("showroom/form/".$header['docno']);
	}

	function rekap(){
		$params = $this->input->post();
		$sesi = $this->session->userdata();
		$tgl=date("Y-m-d");
		$lokasi = $this->session->userdata(sess_location_code);
		if(isset($params['tanggal'])) $tgl = $this->formatDate('Y-m-d',$params['tanggal']);
		if(isset($params['location_code'])) $lokasi = $params['location_code'];

		$data = $this->db->select("kp.*, h.location_code, h.docno, h.doc_date,
		h.customer_code, h.gross_sales, h.total_ppn, h.total_discount, h.sales_before_tax, h.sales_after_tax,
		h.total_dp, h.sisa_faktur, h.total_hpp, ifnull(pa.persen,0) as adminfee")
			->where("rekap",0)
			->where("h.doc_date",$tgl)
			->where("h.location_code",$lokasi)
			->join($this->table_bayar." kp", "kp.trx_no=h.docno")
			->join("payment_type_adm pa","pa.id=kp.paymenttypeid","left")
			->get($this->table." h")->result();
		if(count($data)>0) {
			$data_customer = [];
			$docno = array_column($data, "docno");
			$kasir = [];
			foreach ($data as $row) {
				$kasir[$row->paymenttypeid] = $row;
				$data_customer[$row->customer_code][] = $row;
			}
			$detail = $this->db->select("d.*, h.location_code, p.article_code, pu.uom_id")
				->where_in("d.docno", $docno)
				->join("product p", "p.sku=d.nobar")
				->join("product_uom pu", "pu.uom_code=p.satuan_jual")
				->join($this->table . " h", "h.docno=d.docno")
				->get($this->table_detail . " d")->result();
			$det_so = [];
			foreach ($detail as $row) $det_so[$row->docno][] = $row;
			$item = [];
			foreach ($kasir as $key => $row) {
				$item[$key]['location_code'] = $row->location_code;
				$item[$key]['tanggal'] = $row->doc_date;
				$item[$key]['paymenttypeid'] = $key;
				$item[$key]['total_bayar'][] = $row->nilai_bayar;
				$item[$key]['adminfee'] = $row->adminfee;
				$item[$key]['adminamt'][] = $row->nilai_bayar * $row->adminfee / 100;
				$item[$key]['crtby'] = $this->session->userdata(sess_user_id);
				$item[$key]['crtdt'] = date("Y-m-d H:i:s");
			}
			foreach ($item as $key => $rw) {
				$item[$key]['total_bayar'] = array_sum($item[$key]['total_bayar']);
				$item[$key]['adminamt'] = array_sum($item[$key]['adminamt']);
			}
			$this->db->trans_start();
			if (count($item) > 0) {
//				pre($item);
				$this->db->insert_batch("rekap_payment_harian", $item);
				$this->db->where_in("docno", $docno)
					->update($this->table, ["rekap" => 1, "status" => "CLOSED", "upddt" => date('Y-m-d H:i:s'), "updby" => $this->session->userdata(sess_user_id)]);

				$arr_insert_header = [];
				$arr_insert_detail = [];
				$lastid = $this->db->order_by("id desc")->get("sales_trans_header")->row()->id;
				$lastid++;
				foreach ($data_customer as $key => $row) {
					$nomor = $this->model_ws->generate_auto_number("001");
					$seri = $this->model_faktur->read_available_faktur($this->formatDate('Y', $tgl))->row();
					$seri_pajak = "";
					if (isset($seri)) {
						$seri_pajak = $sesi['kode store pusat'] . $seri->seqno;
						$data2 = array(
							'inuse' => 1,
							'refno' => $nomor,
							'updby' => $this->session->userdata('user_id'),
							'upddt' => date('Y-m-d H:i:s')
						);
						$this->model_faktur->update_data($seri->id, $data2);
					}
					$dt = array();
					$dt['id'] = $lastid;
					$dt['doc_date'] = $tgl;
					$dt['faktur_date'] = $tgl;
					$dt['no_faktur'] = $nomor;
					$dt['no_faktur2'] = $nomor;
					$dt['seri_pajak'] = $seri_pajak;
					$dt['jenis_faktur'] = 'SHOWROOM';
					$dt['remark'] = 'REKAP SALES SHOWROOM ' . $tgl;
					$dt['customer_code'] = $key;
					$dt['posting_date'] = $tgl;
					$dt['status'] = 'CLOSED';
					$dt['verifikasi_finance'] = '';
					$dt['base_so'] = implode(",",array_column($row,"trx_no"));
					$dt['qty_print'] = 0;
					$dt['crtby'] = $this->session->userdata(sess_user_id);
					$dt['crtdt'] = date('Y-m-d H:i:s');
					$dt['gross_sales'] = array_sum(array_column($row,"gross_sales"));
					$dt['total_ppn'] = array_sum(array_column($row,"total_ppn"));
					$dt['total_disc'] = array_sum(array_column($row,"total_discount"));
					$dt['sales_before_tax'] = array_sum(array_column($row,"sales_before_tax"));
					$dt['sales_after_tax'] = array_sum(array_column($row,"sales_after_tax"));
					$dt['total_dp'] = array_sum(array_column($row,"total_dp"));
					$dt['sisa_faktur'] = array_sum(array_column($row,"sisa_faktur"));
					$dt['total_hpp'] = array_sum(array_column($row,"total_hpp"));
					$arr_insert_header[] = $dt;
					foreach ($row as $d){
						foreach ($det_so[$d->trx_no] as $res) {
							$det = array();
							$det['sales_trans_header_id'] = $lastid;
							$det['base_so'] = $d->trx_no;
							$det['product_type'] = $res->product_tipe;
							$det['item'] = $res->article_code;
							$det['nobar'] = $res->nobar;
							$det['tipe'] = $res->tipe;
							$det['komisi_persen'] = $res->komisi;
							$det['qty_order'] = $res->qty_order;
							$det['qty_on_sales'] = $res->qty_sales;
							$det['qty_refund'] = $res->qty_refund;
							$det['uom_code'] = $res->uom_code;
							$det['location_code'] = $res->location_code;
							$det['unit_price'] = $res->unit_price;
							$det['disc1_persen'] = $res->disc1_persen;
							$det['disc1_amount'] = $res->disc1_amount;
							$det['disc2_persen'] = $res->disc2_persen;
							$det['disc2_amount'] = $res->disc2_amount;
							$det['disc3_persen'] = 0;
							$det['disc3_amount'] = 0;
							$det['disc_total'] = $res->disc_total;
							$det['disc_open'] = 0;
							$det['net_unit_price'] = $res->net_unit_price;
							$det['bruto_before_tax'] = $res->sales_before_ppn;
							$det['total_tax'] = $res->sales_after_ppn - $res->sales_before_ppn;
							$det['netto_after_tax'] = $res->sales_after_ppn;
							$det['total_komisi_amount'] = 0;
							$det['jumlah_hpp'] = $res->jumlah_hpp;
							$det['proses_to_ho'] = '';
							$det['status_detail'] = 'OPEN';
							$det['add_cost1'] = $res->add_cost1;
							$det['add_cost2'] = $res->add_cost2;
							$det['add_cost3'] = $res->add_cost3;
							$det['crtby'] = $this->session->userdata(sess_user_id);
							$det['crtdt'] = date('Y-m-d H:i:s');
							$arr_insert_detail[] = $det;
						}
					}
					$lastid++;
				}
				if (count($arr_insert_header) > 0) $this->db->insert_batch("sales_trans_header", $arr_insert_header);
				if (count($arr_insert_detail) > 0) $this->db->insert_batch("sales_trans_detail", $arr_insert_detail);
			}
			if ($this->db->trans_status() === FALSE) {
				$this->set_error($this->db->error());
			} else {
				$this->db->trans_complete();
				$this->set_success("Rekap Transaksi Harian berhasil");
				$this->print_rekap();
			}
		} else{
			$this->set_error("Sales pada ".$tgl." sudah di rekap, tidak bisa di rekap ulang");
		}
		redirect('showroom/index');
	}

	function print_so($docno){
		if(JANGAN_PRINT) return;
		$item = $this->db->select('h.*, u.fullname crtbyname, l.description locname')
			->join('users u','u.user_id=h.crtby')
			->join("location l","l.location_code=h.location_code")
			->get_where($this->table." h",["docno"=>$docno])->row();
		$detail = $this->db->select("d.*, p.product_code, pb.nmbar, pu.uom_id")
			->where("docno",$docno)
			->join("product_barang pb","pb.nobar=d.nobar")
			->join("product p","p.id=pb.product_id")
			->join("product_uom pu","pu.uom_code=d.uom_code")
			->get($this->table_detail." d")->result();
		$fmt = $this->db->get_where("setup_receipt",["location_code"=>$item->location_code])->row();

		$this->load->library('escpos');
		$connector = new Escpos\PrintConnectors\WindowsPrintConnector("EPSON TM-U220 Receipt");
		$printer = new Escpos\Printer($connector);

		$head = isset($fmt->headerf) ? $fmt->headerf : $item->locname;
		$printer->initialize();
		$printer->setLineSpacing(25);
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		$printer->text($head."\n");
		$printer->text($this->createRowColumn([$docno,date("d/m/Y H:i:s",strtotime($item->crtdt))],["text","text"],[19,19]));
		$printer->feed(2);

		$width = [4,15,21];
		$printer->initialize();
		$printer->setLineSpacing(25);
		$printer->setFont(Escpos\Printer::FONT_B);
		$printer->text($this->createRowColumn(["No","Item#","Nama Barang"],["text","text","text"],$width));
		$printer->text("========================================\n");
		$qty = 0; $total = 0;
		foreach ($detail as $i => $d){
			$qty += $d->qty_order;
			$total += $d->qty_order*$d->net_unit_price;
			$printer->text($this->createRowColumn([$i+1,$d->product_code,$d->nmbar],["text","text","text"],$width));
			$printer->text($this->createRowColumn(["",$d->qty_order." ".$d->uom_id,number_format($d->unit_price),number_format($d->qty_order*$d->unit_price)],["text","text","text"],[4,11,11,12]));
			$printer->text($this->createRowColumn(["",$d->disc1_persen."%",$d->disc2_persen."%","(".number_format($d->qty_order*$d->disc_total).")"], ["text","text","text"],[4,11,11,12]));
			$printer->text($this->createRowColumn([number_format($d->qty_order*$d->net_unit_price)],["curr"],[38]));

		}
		$printer->text("----------------------------------------\n");
		$printer->text($this->createRowColumn(["Qty        : ",number_format($qty)],["text","text"],[24,14]));
		$printer->text($this->createRowColumn(["T O T A L  : ",number_format($total)],["text","text"],[24,14]));
		$printer->text($this->createRowColumn(["CASH       : ",number_format($total)],["text","text"],[24,14]));
		$printer->text("========================================\n");
		$printer->text("Serv : ".$item->crtbyname."\n");
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		if(isset($fmt->footerf)) {
			$printer->text($fmt->footerf . "\n");
		}else{
			$printer->text( "Terima Kasih\n");
			$printer->text( "Barang Yang Sudah Dibeli Tidak Dapat\n");
			$printer->text( "Ditukar atau Dikembalikan\n");
		}
		$printer->feed(3);
		$printer->close();

		$this->db->query("update $this->table set jumlah_print=jumlah_print+1 where docno='$docno'");
		return "OK";
	}
	public function print_rekap(){
		if(JANGAN_PRINT) return;
		$param = $this->input->post();
//		pre($param);
		$payment = $this->db->select("a.*, pt.description, pt.tipe")
			->where("a.location_code",$param['location_code'])
			->where("a.tanggal",$param['tanggal'])
			->join("payment_type pt","pt.id=a.paymenttypeid")
			->get("rekap_payment_harian a")->result();
		$detail = $this->db->select("h.*, sum(kp.nilai_bayar) as nilai")
			->where('doc_date',$param['tanggal'])
			->where('h.location_code',$param['location_code'])
			->join($this->table_bayar." kp","kp.trx_no=h.docno")
			->group_by("h.docno")
			->get($this->table." h")->result();
		$lokasi = $this->db->get_where("location",["location_code"=>$param['location_code']])->row();
		$this->load->library('escpos');
		$connector = new Escpos\PrintConnectors\WindowsPrintConnector("EPSON TM-U220 Receipt");
		$printer = new Escpos\Printer($connector);

		$periode = date("d/m/Y", strtotime($param['tanggal']));
		$printer->initialize();
		$printer->setLineSpacing(25);
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		$printer->text($lokasi->description."\n");
		$printer->text("RECAP PENJUALAN HARIAN\n");
		$printer->text("PERIODE ".$periode." s/d ".$periode."\n");
		$printer->setFont(Escpos\Printer::FONT_B);
		$printer->text("========================================\n");
		$printer->text($this->createRowColumn(["NO","TGL","NO TRX","#IT", str_pad("NILAI",11," ",STR_PAD_LEFT)],["text","text","text","text","text"],[4,4,16,4,11]));
		$printer->initialize();
		$printer->text("========================================\n");
		foreach ($detail as $i => $d){
			$printer->text($this->createRowColumn([$i+1,date("d",strtotime($d->doc_date)),$d->docno,$d->qty_order, str_pad(number_format($d->nilai),11," ",STR_PAD_LEFT)],["text","text","text","text","text"],[4,4,16,4,11]));
		}
		$printer->text($this->createRowColumn(["","JENIS BAYAR",str_pad("NILAI BAYAR",14," ",STR_PAD_LEFT)],["text","text","text"],[6,19,14]));
		$printer->initialize();
		$printer->text("========================================\n");
		$total = 0;
		foreach ($payment as $i => $d){
			$total += $d->total_bayar;
			$printer->text($this->createRowColumn(["",$d->tipe."=".$d->description,":",str_pad(number_format($d->total_bayar),11," ",STR_PAD_LEFT)],["text","text","text"],[6,20,2,11]));
		}
		$printer->text("========================================\n");
		$printer->text($this->createRowColumn(["","T O T A L  ",":",str_pad(number_format($total),11," ",STR_PAD_LEFT)],[["text","text","text"]],[6,20,2,11]));
		$printer->feed(3);
		$printer->close();

		echo json_encode(array(
			"status"=>0
		));
	}
	public function getsummary(){
		$input = $this->input->post();
		$data = $this->db->where("doc_date",$this->formatDate("Y-m-d", $input['tanggal']))
			->where("location_code",$input['location_code'])
			->get($this->table)->result();
		$dt["summary"] = 0;
		if(count($data)>0){
			foreach ($data as $row){
				$dt['summary'] += $row->sales_after_tax;
			}
		}
		$dt['status'] = 0;
		echo json_encode($dt);
	}
}
