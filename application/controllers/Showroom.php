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
		$tgl = date("Y-m-d");
		$param = $this->input->post();
		$location_code = $this->session->userdata(sess_location_code);
		if(isset($param['tanggal'])) $tgl = $param['tanggal'];
		if(isset($param['location_code'])) $location_code = $param['location_code'];
		$data['title'] = 'Sales Order Showroom';
		$data['tanggal'] = $tgl;
		$data['lokasi'] = $this->db->get('location')->result();
		$data['location_code'] = $location_code;
		$data['content'] = $this->load->view('showroom/index', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function rekapindex(){
		$tgl = date("Y-m-d");
		$param = $this->input->post();
		$location_code = $this->session->userdata(sess_location_code);
		if(isset($param['tanggal'])) $tgl = $param['tanggal'];
		if(isset($param['location_code'])) $location_code = $param['location_code'];
		$data['title'] = 'Sales Order Showroom';
		$data['tanggal'] = $tgl;
		$data['lokasi'] = $this->db->get('location')->result();
		$data['location_code'] = $location_code;
		$data['content'] = $this->load->view('showroom/indexrekap', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function form($docno = ""){
		$param = $this->input->post();
		$tgl = date("ymd");
		$lokasi = $this->session->userdata(sess_location_code);
		if(isset($param['location_code'])) $lokasi=$param['location_code'];
		if (isset($param['tanggal'])) $tgl = $this->formatDate("ymd", $param['tanggal']);
		$customer = $this->db->get_where("customer",["lokasi_stock"=>$lokasi])->row();
		if($docno=="") {
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
				"doc_date" => $this->formatDate("Y-m-d",$tgl),
				"trans_date" => $this->formatDate("Y-m-d",$tgl),
				"location_code" => $lokasi,
				"provinsi_id" => $customer->provinsi_id,
				"regency_id" => $customer->regency_id,
				"customer_code" => $customer->customer_code,
				"crtdt"=>date("Y-m-d H:i:s"),
				"crtby"=>$this->session->userdata('user_id'),
				"jumlah_print"=>0
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
		$param = $this->input->get();
		$lokasi = $this->session->userdata(sess_location_code);
		$tanggal=date("Y-m-d");
		if(isset($param['location_code'])) $lokasi = $param['location_code'];
		if(isset($param['tanggal'])) $tanggal = $param['tanggal'];
		$total = $this->db->where("location_code",$lokasi)->where('doc_date',$tanggal)->get($this->table)->num_rows();
		$this->db->select("a.*, b.store_name");
		$this->getParamGrid_Builder("","docno");
		$this->db->join("profile_p b", "b.default_stock_l=a.location_code");
		$data = $this->db->get($this->table." a")->result();
		if(count($data)==0) $data=[];
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
		$total = $this->db->where("location_code",$lokasi)->where('tanggal',$tanggal)->get("rekap_payment_harian")->num_rows();
		$this->db->select("a.*, b.description locname, pt.description ptname");
		$this->getParamGrid_Builder("","tanggal");
		$this->db->join("location b", "b.location_code=a.location_code");
		$this->db->join("payment_type pt", "pt.id=a.paymenttypeid");
		$data = $this->db->get("rekap_payment_harian a")->result();
		if(count($data)==0) $data=[];
		echo json_encode(array(
				"total"=>$total,
				"rows" =>$data)
		);
	}

	public function editfee(){
		$param = $this->input->post();
		$item = $this->db->get_where("rekap_payment_harian",["id"=>$param['id']])->row();
		$adminamt = $item->total_bayar*$param['adminfee']/100;
		$param['adminamt'] = $adminamt;
		$this->db->update("rekap_payment_harian",$param,["id",$param['id']]);
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
		redirect("showroom/form/".$header['docno']);
	}

	function rekap($tgl,$lokasi=""){
		$tgl = date('Y-m-d',strtotime($tgl));
		if($lokasi=="") $lokasi = $this->session->userdata(sess_location_code);
		$data = $this->db->select("kp.*, h.location_code, h.docno, h.doc_date, ifnull(pa.persen,0) as adminfee")
			->where("rekap",0)
			->where("h.doc_date",$tgl)
			->where("h.location_code",$lokasi)
			->join($this->table_bayar." kp", "kp.trx_no=h.docno")
			->join("payment_type_adm pa","pa.id=kp.paymenttypeid","left")
			->get($this->table." h")->result();
		$docno = array_column($data,"docno");
		$kasir = [];
		foreach ($data as $row){
			$kasir[$row->paymenttypeid] = $row;
		}
		$item = [];
		foreach ($kasir as $key => $r){
				$item[$key]['location_code'] = $row->location_code;
				$item[$key]['tanggal'] = $row->doc_date;
				$item[$key]['paymenttypeid'] = $key;
				$item[$key]['total_bayar'][] = $row->nilai_bayar;
				$item[$key]['adminfee'] = $row->adminfee;
				$item[$key]['adminamt'][] = $row->nilai_bayar*$row->adminfee/100;
				$item[$key]['crtby'] = $this->session->userdata(sess_user_id);
				$item[$key]['crtdt'] = date("Y-m-d H:i:s");
		}
		foreach ($item as $key => $rw){
			$item[$key]['total_bayar'] = array_sum($item[$key]['total_bayar']);
			$item[$key]['adminamt'] = array_sum($item[$key]['adminamt']);
		}
		$this->db->trans_start();
		if(count($item)>0) {
			$this->db->insert_batch("rekap_payment_harian",$item);
			$this->db->where_in("docno", $docno)
				->update($this->table,["rekap"=>1,"upddt"=>date('Y-m-d H:i:s'), "updby"=>$this->session->userdata(sess_user_id)]);
		}
		if ($this->db->trans_status() === FALSE){
			$this->set_error($this->db->error());
		}else{
			$this->db->trans_complete();
			$this->set_success("Rekap Transaksi Harian berhasil");
		}
		redirect('showroom/index');
	}

	function print_so($docno){
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
		echo json_encode(array(
			"status"=>0
		));
	}
	public function print_rekap(){
		$param = $this->input->post();
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

//		pre($detail);

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
}
