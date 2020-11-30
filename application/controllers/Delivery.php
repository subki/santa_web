<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends IO_Controller {

	function __construct(){

		parent::__construct();
		$this->load->model('Delivery_model','model');
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	function index(){

	}

	function in($prefix=""){
		$add = "";
		if($prefix=="PON") $add = "Dari Produksi ke Pusat";
		else if($prefix=="MPI") $add = "Dari Import Produksi ke Pusat";
		else if($prefix=="DO2") $add = "Dari Pusat ke All Location";
		else if($prefix=="DO1") $add = "Dari Pusat ke Produksi";
		else if($prefix=="DO2_1") $add = "Dari All Location ke Pusat";
		$this->data['title']      = 'Receiving DO '.$add;
		$this->data['route']      = 'from';
		$this->data['prefix']		= $prefix;
		$this->data['content']    = $this->load->view('vDelivery',$this->data,TRUE);

		$this->load->view('main',$this->data);
	}

	function out($prefix=""){
		$add = "";
		if($prefix=="PON") $add = "Dari Produksi ke Pusat";
		else if($prefix=="MPI") $add = "Dari Import Produksi ke Pusat";
		else if($prefix=="DO2") $add = "Dari Pusat ke All Location";
		else if($prefix=="DO1") $add = "Dari Pusat ke Produksi";
		else if($prefix=="DO2_1") $add = "Dari All Location ke Pusat";
		$this->data['title']      = 'Sending DO '.$add;
		$this->data['route']      = 'to';
		$this->data['prefix']		= $prefix;
		$this->data['content']    = $this->load->view('vDelivery',$this->data,TRUE);

		$this->load->view('main',$this->data);
	}

	function load_grid($route="",$prefix=""){
		$store = $this->session->userdata('store_code');
		$loc = ($route=="in") ? "to_store_code" : "from_store_code";
		$special="";
		if($route=="out") {
			$pst = $this->session->userdata('kode store pusat');
			if($pst==$store){
				$special = " do_type='DO' and status IN ('OPEN','CANCELED') ";
			}else $special .= " and $loc ='$store' ";
		}else{
			$special = " do_type='DO' and status in('ON DELIVERY','RECEIVED')";
		}

		if($prefix=="PON") $special .= " and golongan_do='prod2pst' ";
		else if($prefix=="MPI") $special .= " and golongan_do='iprod2pst' ";
		else if($prefix=="DO2") $special .= " and golongan_do='pst2loc' ";
		else if($prefix=="DO1") $special .= " and golongan_do='pst2prod' ";
		else if($prefix=="DO2_1")$special .= " and golongan_do='loc2pst' ";

		$f = $this->getParamGrid($special,"location_code");
		$data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>(count($data)>0)?$data[0]->total:0,
				"data" =>$data)
		);

	}

	function get_location($store){
		$special = " location_code in(select location_code from cabang where store_code='$store')";
		$f = $this->getParamGrid("","location_code");
		$data = $this->model->get_location($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>(count($data)>0)?$data[0]->total:0,
				"data" =>$data
			)
		);
	}
	function get_store($route){
		$store = $this->session->userdata('store_code');
		$special="";
		if($route=="out") {
			$special = " store_code = '$store'";
		}
		$f = $this->getParamGrid($special,"store_code");
		$data = $this->model->get_store($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>(count($data)>0)?$data[0]->total:0,
				"data" =>$data
			)
		);
	}

	function save_data(){
		try {
			$input = $this->toUpper($this->input->post());

			$pref = $input['docno'];
			$golongan_do = "Other";
			if($pref=="PON") $golongan_do      = "prod2pst";
			else if($pref=="MPI") $golongan_do = "iprod2pst";
			else if($pref=="DO2") $golongan_do = "pst2loc";
			else if($pref=="DO1") $golongan_do = "pst2prod";
			else if($pref=="DO2_1")$golongan_do= "loc2pst";

			$code = $this->model->generate_auto_number_innerprefix(
				$this->formatDate('Y-m-d', $input['doc_date'])
				,$input['from_location_code']
				,$input['to_location_code']);
			if($golongan_do=="iprod2pst"){
				$code = $this->model->generate_auto_number_innerprefix_withI(
					$this->formatDate('Y-m-d', $input['doc_date'])
					,$input['from_location_code']
					,$input['to_location_code']);
			}
			$data = array(
				'docno' => $code,
				'doc_date' => $this->formatDate('Y-m-d', $input['doc_date']),
				'tgl_promo' => $input['tgl_promo']?$this->formatDate('Y-m-d', $input['tgl_promo']):null,
//                'receive_date' => $this->formatDate('Y-m-d', $input['receive_date']),
				'from_store_code' => $input['from_store_code'],
				'from_location_code' => $input['from_location_code'],
				'to_store_code' => $input['to_store_code'],
				'to_location_code' => $input['to_location_code'],
				'do_type' => 'DO',
				'golongan_do' => $golongan_do,
				'status' => $input['status'],
				'keterangan' => $input['keterangan'],
				'crtby' => $this->session->userdata('user_id'),
				'crtdt' => date('Y-m-d H:i:s')
			);

			if($this->checkPeriod($data['from_location_code'], $data['doc_date'])) {
				$this->model->insert_data($data);
				$result = 0;
				$msg = "OK";
			}else{
				$result = 1;
				$msg = "Transaksi tidak dalam periode berjalan";
			}
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg, "docno"=>$code
		));
	}

	function edit_data(){
		try {
			$input = $this->toUpper($this->input->post());
			$this->db->trans_start();

			$read = $this->model->read_data($input['docno']);
			if ($read->num_rows() > 0) {
				$data = array(
					'doc_date' => $this->formatDate('Y-m-d', $input['doc_date']),
					'receive_date' => isset($input['receive_date'])?$this->formatDate('Y-m-d H:i:s', $input['receive_date']):null,
					'tgl_promo' => $input['tgl_promo']?$this->formatDate('Y-m-d', $input['tgl_promo']):null,
					'from_store_code' => $input['from_store_code'],
					'from_location_code' => $input['from_location_code'],
					'to_store_code' => $input['to_store_code'],
					'to_location_code' => $input['to_location_code'],
					'status' => $input['status'],
					'keterangan' => $input['keterangan'],
					'do_type' => 'DO',
					'updby' => $this->session->userdata('user_id'),
					'rcvby' => $this->session->userdata('user_id'),
					'upddt' => date('Y-m-d H:i:s')
				);

				if($data['status']=="RECEIVED" || $data['status']=="APPROVED"){
					$data['receive_date'] = date('Y-m-d H:i:s');
					if($read->row()->golongan_do='prod2pst'){
						$this->model->updateQtyRecv($input['docno']);
					}
					$data2 = array(
						'status' => 'transfered'
					);
					$cc = $this->model->checkQtyReceive($input['docno']);
					if($cc > 0){
						$result = 1;
						$msg=$cc==2?"Qty Receive di detail item belum di input semua.":"Detail belum di input";
					}else {
						if($this->checkPeriod($data['to_location_code'], $data['receive_date'])) {
							$this->model->update_data($input['docno'], $data);
							$this->model->update_status_data_detail($input['docno'], $data2);
							//update stock
							$det = $this->db->get_where("do_detail",["docno"=>$input['docno']])->result();
							$nobarqty = [];
							$cust = $this->db->get_where('customer',['lokasi_stock'=>$input['to_location_code']])->row();
							$detail = [];
							foreach ($det as $i => $r) {
								$nobarqty[$r->nobar] = $r->qty_rcv;
								$dt['journal_no'] = $input['docno']."I";
								$dt['seqno'] = $r->id;
								$dt['cost_center'] = $cust->customer_code;
								$dt['account_no'] = $cust->gl_account;
								$dt['dbcr'] = 'CREDIT';
								$dt['remark'] = $input['keterangan'];
								$dt['nilai_debet'] = 0;
								$dt['nilai_credit'] = $r->qty_rcv*$r->net_price;
								$detail[] = $dt;
							}
							$dt['journal_no'] = $input['docno']."I";
							$dt['seqno'] = '';
							$dt['cost_center'] = $cust->customer_code;
							$dt['account_no'] = $cust->gl_account;
							$dt['dbcr'] = 'DEBET';
							$dt['remark'] = $input['keterangan'];
							$dt['nilai_debet'] = array_sum(array_column($detail,"nilai_credit"));
							$dt['nilai_credit'] = 0;
							$detail[] = $dt;

							$header[] = array(
								"journal_no"=>$input['docno']."I",
								"store_code"=>$input['from_store_code'],
								"fiscal_year"=>date('Y'),
								"fiscal_month"=>date('m'),
								"journal_date"=>date('Y-m-d'),
								"entry_date"=>date('Y-m-d'),
								"journal_code"=>$cust->gl_account,
								"reference"=>"",
								"keterangan"=>$input['keterangan'],
								"total_debet"=>array_sum(array_column($detail,"nilai_debet")),
								"total_credit"=>array_sum(array_column($detail,"nilai_credit")),
								"status_journal"=>"POSTED",
								"journal_type"=>"INVENTORY JOURNAL"
							);
							$this->updateStock($data['to_location_code']
								,date('Ym', strtotime($data['receive_date']))
								,$nobarqty,'do_masuk'
								,['docno'=>$input['docno'],"tanggal"=>$data['doc_date'],"remark"=>$data['keterangan']]);
							$this->journal_record($header,$detail);
							$result = 101;
							$msg=$input['docno'];
						}else{
							$result = 1;
							$msg = "Transaksi tidak dalam periode berjalan";
						}
					}
				}else{
					if($read->row()->status=="OPEN" || $read->row()->status=="DRAFT"){
						if($data['status'] != $read->row()->status){
							$as = $this->model->checkItemsDO($input['docno']);
							if($as->num_rows()>0){
								$this->model->update_data($input['docno'], $data);
								if($data['status']=="ON DELIVERY"){
									$data2 = array(
										'status' => 'on delivery'
									);
									$this->model->update_status_data_detail($input['docno'], $data2);
									//update stock
									$det = $this->db->get_where("do_detail",["docno"=>$input['docno']])->result();
									$nobarqty = [];
									$cust = $this->db->get_where('customer',['lokasi_stock'=>$input['from_location_code']])->row();
									$detail = [];
									foreach ($det as $i => $r) {
										$nobarqty[$r->nobar] = $r->qty;
										$dt['journal_no'] = $input['docno'].'O';
										$dt['seqno'] = $r->id;
										$dt['cost_center'] = $cust->customer_code;
										$dt['account_no'] = $cust->gl_account;
										$dt['dbcr'] = 'DEBET';
										$dt['remark'] = $input['keterangan'];
										$dt['nilai_debet'] = $r->qty*$r->net_price;
										$dt['nilai_credit'] = 0;
										$detail[] = $dt;
									}
									$dt['journal_no'] = $input['docno']."O";
									$dt['seqno'] = '';
									$dt['cost_center'] = $cust->customer_code;
									$dt['account_no'] = $cust->gl_account;
									$dt['dbcr'] = 'CREDIT';
									$dt['remark'] = $input['keterangan'];
									$dt['nilai_debet'] = 0;
									$dt['nilai_credit'] = array_sum(array_column($detail,"nilai_debet"));
									$detail[] = $dt;
									$header[] = array(
										"journal_no"=>$input['docno'].'O',
										"store_code"=>$input['from_store_code'],
										"fiscal_year"=>date('Y'),
										"fiscal_month"=>date('m'),
										"journal_date"=>date('Y-m-d'),
										"entry_date"=>date('Y-m-d'),
										"journal_code"=>$cust->gl_account,
										"reference"=>"",
										"keterangan"=>$input['keterangan'],
										"total_debet"=>array_sum(array_column($detail,"nilai_debet")),
										"total_credit"=>array_sum(array_column($detail,"nilai_credit")),
										"status_journal"=>"POSTED",
										"journal_type"=>"INVENTORY JOURNAL"
									);
									$this->updateStock($data['from_location_code']
										,date('Ym', strtotime($data['receive_date']))
										,$nobarqty,'do_keluar'
										,['docno'=>$input['docno'],"tanggal"=>$data['doc_date'],"remark"=>$data['keterangan']]);
									$this->journal_record($header,$detail);
								}
								$result = 0;
								$msg="OK";
							}else{
								$result = 1;
								$msg = "Detail DO masih kosong";
							}
						}else{
							$this->model->update_data($input['docno'], $data);
							$result = 0;
							$msg="OK";
						}
					}else{
						$this->model->update_data($input['docno'], $data);
						$result = 0;
						$msg="OK";
					}
				}
			} else {
				$result = 1;
				$msg="Kode tidak ditemukan";
			}
			$this->db->trans_complete();
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg
		));
	}

	function delete_data(){
		try {
			$input = $this->toUpper($this->input->post());
			$code = $input['id'];
			$read = $this->model->read_data($code);
			if ($read->num_rows() > 0) {
				if($read->row()->status=="Open") {
					$read = $this->model->read_transactions($code);
					if ($read->num_rows() > 0) {
						$result = 1;
						$msg = "Data tidak bisa dihapus, sudah ada transaksi";
					} else {
						$this->model->delete_data($code);
						$result = 0;
						$msg = "OK";
					}
				}else{
					$result = 1;
					$msg = "Data tidak bisa dihapus, status tidak sama dengan Open";
				}
			} else {
				$result = 1;
				$msg="Kode tidak ditemukan";
			}
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg
		));
	}


	function load_grid_nobar($code){
//        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
//        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
//        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'docno';
//        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
//        $role = $this->session->userdata('role');
//        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";
//
//        $app="";
//        if($fltr!=""){
//            foreach ($fltr as $r){
//                if($app==""){
//                    $app .= " where ".$r->field." like '%".$r->value."%'";
//                }else{
//                    $app .= " AND ".$r->field." like '%".$r->value."%'";
//                }
//            }
//            if(count($fltr)>0) $app .= " AND docno = '".$code."' ";
//            else $app .= " where docno = '".$code."' ";
//        }else{
//            $app .= " where docno = '".$code."' ";
//        }
		$f = $this->getParamGrid(" docno = '".$code."' ","docno");
		$data = $this->model->load_grid_nobar($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

//        $data = $this->model->load_grid_nobar($page,$rows,$sort,$order,$role, $app);

		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>(count($data)>0)?$data[0]->total:0,
				"data" =>$data)
		);

	}

	function get_product($code){

		$read = $this->model->read_data($code);
		if($read->num_rows()>0){
			$loc = $read->row()->from_location_code;
			$prd = $this->formatDate('Ym', $read->row()->doc_date);
			$special = " nobar in(select nobar from stock where location_code='$loc' and periode='$prd') ";
			$f = $this->getParamGrid("","nobar");
			$data = $this->model->get_product($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],$code,$loc,$prd);
		}else{
			$data = array();
		}


		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>(count($data)>0)?$data[0]->total:0,
				"data" =>$data)
		);

	}

	function save_data_nobar($code){
		try {
			$input = $this->toUpper($this->input->post());

			$data = array(
				'docno' => $code,
				'nobar' => $input['nobar'],
				'qty' => $input['qty'],
				'qty_rcv' => ($input['qty_rcv'])?$input['qty_rcv']:0,
				'qty_rev' => ($input['qty_rev'])?$input['qty_rev']:0,
				'retail_price' => ($input['retail_price'])?$input['retail_price']:0,
				'discount' => ($input['discount'])?$input['discount']:0,
				'net_price' => ($input['net_price'])?$input['net_price']:0,
				'status' => 'new',
				'keterangan' => $input['keterangan']
			);

			$this->model->insert_data_nobar($data);
			$result = 0;
			$msg = "OK";
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg
		));
	}

	function edit_data_nobar(){
		try {
			$input = $this->toUpper($this->input->post());

			$read = $this->model->read_data_nobar($input['id']);
			if ($read->num_rows() > 0) {
				$loc_penerima = $this->session->userdata('location_code');
				$read2 = $this->model->read_data($input['docno']);
				if($read2->num_rows()>0){
					$data = array(
						'qty' => $input['qty'],
						'qty_rcv' => ($input['qty_rcv'])?$input['qty_rcv']:0,
						'qty_rev' => ($input['qty_rev'])?$input['qty_rev']:0,
						'status' => $input['status'],
						'retail_price' => ($input['retail_price'])?$input['retail_price']:0,
						'discount' => ($input['discount'])?$input['discount']:0,
						'net_price' => ($input['net_price'])?$input['net_price']:0,
						'keterangan' => $input['keterangan']
					);

					if($loc_penerima==$read2->row()->to_location_code){
						if($input['qty']==$input['qty_rcv']){
							$this->model->edit_data_nobar($input['id'], $data);
							$result = 0;
							$msg="OK";
						}else{
							$result = 1;
							$msg="Qty terima harus sama dengan qty kirim";
						}
					}else {
						$this->model->edit_data_nobar($input['id'], $data);
						$result = 0;
						$msg = "OK";
					}
				}else{
					$result = 1;
					$msg="Header tidak ditemukan";
				}
			} else {
				$result = 1;
				$msg="Kode tidak ditemukan";
			}
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg
		));
	}

	function read_data($code){
		try {
			$read = $this->model->read_data($code);
			if ($read->num_rows() > 0) {
				$result = 0;
				$msg="OK";
				$data = $read->result()[0];
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

	function delete_data_nobar(){
		try {
			$input = $this->toUpper($this->input->post());
			$read = $this->model->read_data_nobar($input['id']);
			if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
				$this->model->delete_data_nobar($input['id']);
				$result = 0;
				$msg="OK";
//                }
			} else {
				$result = 1;
				$msg="Kode tidak ditemukan";
			}
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg
		));
	}
	function save_data_location($code){
		try {
			$input = $this->toUpper($this->input->post());

			$dt = explode(",",$input['location_code']);
			foreach ($dt as $r) {
				$data = array(
					'discount_id' => $code,
					'location_code' => $r,
				);

				$this->model->insert_data_location($data);
			}
			$result = 0;
			$msg = "OK";
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg
		));
	}

	function delete_data_location(){
		try {
			$input = $this->toUpper($this->input->post());
			$read = $this->model->read_data_location($input['id']);
			if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
				$this->model->delete_data_location($input['id']);
				$result = 0;
				$msg="OK";
//                }
			} else {
				$result = 1;
				$msg="Kode tidak ditemukan";
			}
		}catch (Exception $e){
			$result = 1;
			$msg=$e->getMessage();
		}
		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg
		));
	}


	function export_data($nomor){
		$filename = 'DO_' .$nomor. '.csv';
		$header = array("Nomor DO", "Tanggal DO","Product Item","UOM", "Qty","Remark");
		$app = $this->getParamGrid(" a.docno='$nomor' ","id");
		$data = $this->model->load_grid_nobar2($app['app']);
		$unset = ['hpp1','uom_jual','article_code','hpp2', 'hpp_ekspedisi','retail_price', 'discount', 'net_price','id', 'ak_tgl_promo','article_name','tgl_promo', 'doc_date','product_code', 'nmbar', 'qty_rcv', 'qty_rev', 'status', 'keterangan', 'satuan_stock'];
		$top = array();
		$this->export_csv($filename,$header, $data, $unset, $top);
	}

	function print_data(){
		$input = $this->input->get();
		$read = $this->model->read_data($input['nomor']);
		$data=array();
		if ($read->num_rows() > 0) {
			$r = $read->row();
			$data['header']=$r;
			$f = $this->getParamGrid(" docno = '".$input['nomor']."' ","docno");
			$data['detail'] = $this->model->load_grid_nobar(1,99999999,$f['sort'],$f['order'],$f['role'], $f['app']);
		}

		if($input['golongan'] == "PON"){
			$view = "print/PRD2PST_RCV";
		}else if($input['golongan'] == "MPI"){
			$view = "print/PRDI2PST_RCV";
		}else if($input['golongan'] == "DO2"){
			if($input['tipe']==1){
				$view = "print/PST2LOC_PCK";
			}else if($input['tipe']==2){
				$view = "print/PST2LOC_SJ";
			}else if($input['tipe']==3){
				$view = "print/PST2LOC_RP";
			}else if($input['tipe']==4){
				$view = "print/PST2LOC_HPP";
			}
		}else if($input['golongan'] == "DO1"){
			$view = "print/PST2PRD";
		}

//        $this->load->view($view,$data);
		$this->load->library('pdf');
		$this->pdf->load_view($view, $data);
		$this->pdf->render();
		//set page numbers
		$x          = 540;
		$y          = 760;
		$text       = "{PAGE_NUM} of {PAGE_COUNT}";
		$font       = $this->pdf->getFontMetrics('Courier', 'normal');
		$size       = 10;
		$color      = array(0,0,0);
		$word_space = 0.0;
		$char_space = 0.0;
		$angle      = 0.0;
		$this->pdf->getCanvas()->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
		$this->pdf->stream($input['nomor'].'.pdf',array("Attachment"=>0));
	}
}
