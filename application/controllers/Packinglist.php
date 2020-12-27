<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Packinglist extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Packinglist_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Packing List';
        $data['content'] = $this->load->view('vPackinglist', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
        if($aksi=="add"){
            $data['title'] = 'Add Packing List';
            $data['content'] = $this->load->view('vPackinglist_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Packing List';
            $data['docno'] = $this->input->get('docno');
            $data['content'] = $this->load->view('vPackinglist_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

    function load_grid(){
			$total1 = $this->getParamGrid_BuilderComplete(array(
				"table"=>"packing_header a",
				"sortir"=>"doc_date",
				"special"=>[],
				"select"=>"a.docno
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%b/%Y') ak_doc_date
                  , so.doc_date tgl_so, DATE_FORMAT(so.doc_date, '%d/%b/%Y') ak_tgl_so
                  , a.so_number, a.remark, a.status, c.address1, c.phone1, c.pkp, c.beda_fp
                  , so.customer_code, c.customer_name, a.qty_item, a.qty_pl, so.salesman_id
                  , so.disc1_persen, so.disc2_persen, so.disc3_persen
                  , so.qty_order, so.qty_deliver, so.service_level
                  , so.gross_sales, so.total_discount, so.sales_before_tax, so.total_ppn, so.sales_after_tax
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt",
				"join"=>[
					"sales_order_header so"=>"a.so_number=so.docno",
					"customer c"=>"so.customer_code=c.customer_code",
					"location l"=>"l.location_code=so.location_code",
					"users u1"=>"a.crtby=u1.user_id",
					"users u2"=>"a.updby=u2.user_id",
				],
				"posisi"=>["left","inner","left","left"]
			));
			$total = $total1->total;
			$data = $total1->data;
//        $f = $this->getParamGrid("","doc_date");
//        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>$total,
                "data" =>$data)
        );
    }

    function save_data_header(){
        try {
            $input = $this->toUpper($this->input->post());
            $docno = $this->model->generate_auto_number($input['so_number']);
            if($docno==""){
                $result = 1;
                $msg = "Kode store tidak dikenali";
            }else {
                $data = array(
                    'docno' => $docno,
                    'doc_date' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'so_number' => $input['so_number'],
                    'remark' => $input['remark'],
                    'qty_item' => $input['qty_item'],
                    'qty_pl' => $input['qty_pl'],
                    'status' => $input['status'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );

                $this->model->insert_data($data);
                $result = 0;
                $msg = "OK";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "docno"=>$docno
        ));
    }

    function edit_data_header(){
        try {
            $input = $this->toUpper($this->input->post());
            $this->db->trans_start();
            $read = $this->model->read_data($input['docno']);
            if ($read->num_rows() > 0) {
                $rd = $read->row();
                $data = array(
                    'remark' => $input['remark'],
                    'status' => $input['status'],
                    'so_number' => $input['so_number'],
                    'qty_item' => $input['qty_item'],
                    'qty_pl' => $input['qty_pl'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
                if($input['status']=="OPEN" && $rd->status=="POSTING"){
                	$sl = $this->db->get_where("sales_order_header",["docno"=>$rd->so_number])->row();
                	if($sl->status=="CLOSE") {
										$this->db->update("sales_order_header",
											[
												"status" => "ON ORDER",
												"upddt" => date("Y-m-d H:i:s"),
												"updby" => $this->session->userdata(sess_user_id)
											], ["docno" => $rd->so_number]);
										$this->insert_log("sales_order_header", $rd->so_number, "ON ORDER - PL." . $input['reason']);
									}
								}
                $err = 0;
                if($input['status']=="POSTING"){
                	$so = $this->db->get_where("sales_order_header",["docno"=>$rd->so_number])->row();
//                	pre($so);
                	if($so->status=="ON ORDER") {
										$data['posting_date'] = date('Y-m-d');
									}else{
                		$err = 1;
										$result = 1;
										$msg="Status Sales order ".$so->status;
									}
                }
                if($err==0) {

									if ($input['reason'] != "") {
										$this->insert_log("packing_header", $input['docno'], $input['status']." - PL.".$input['reason']);
									}

									if ($rd->so_number != $input['so_number']) {
										$data['docno'] = $input['docno'];
										$data['so_number'] = $input['so_number'];
										$total = $this->model->copySOtoPL($data);
										if ($total > 0) {
											$data['so_number'] = $rd->so_number;
										}
									}
									$this->model->update_data($input['docno'], $data);

									$result = 0;
									$msg = "OK";
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
            "msg" => $msg, "message" => $msg, "docno"=>$input['docno']
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

    function read_data_by_so($code){
        try {
            $read = $this->model->read_data_by_so($code);
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

    function delete_data($code){
        try {
            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {

                $read = $this->model->read_transactions($code);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
                }else{
                    $this->model->delete_data($code);
                    $result = 0;
                    $msg="OK";
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

    function print_pl($docno){
        $read = $this->model->read_data($docno);
        $data=array();
        if ($read->num_rows() > 0) {
            $r = $read->row();
            $data['header']=$r;
            $f = $this->getParamGrid(" a.docno='$docno' ","seqno");
            $data['detail'] = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        }
        $this->load->library('pdf');
        $this->pdf->load_view('print/SLS_PACKING', $data);
        $this->pdf->render();

        $this->pdf->stream($docno.'.pdf',array("Attachment"=>0));
//        $this->load->view('print/salesorder',$data);

    }


    function load_grid_detail($docno){
			$total1 = $this->getParamGrid_BuilderComplete(array(
				"table"=>"packing_detail a",
				"sortir"=>"seqno",
				"special"=>" a.docno='$docno' ",
				"select"=>"a.id, a.docno, a.seqno, a.nobar, a.qty_order, a.qty_pl
                    , b.nmbar, c.satuan_jual, d.description AS uom_jual, c.product_code, d.uom_id
                    , c.product_name, sd.tipe, c.article_code",
				"join"=>[
					"sales_order_detail sd"=>"a.so_number=sd.docno and a.seqno=sd.seqno",
					"product_barang b"=>"a.nobar=b.nobar",
					"product c"=>"b.product_id=c.id",
					"product_uom d"=>"c.satuan_jual=d.uom_code",
				],
				"posisi"=>["left","left","inner","inner"]
			));
			$total = $total1->total;
			$data = $total1->data;
//        $f = $this->getParamGrid(" a.docno='$docno' ","seqno");
//        $data = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>$total,
                "data" =>$data)
        );
    }


    function save_data_detail($docno){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->cek_detail($docno, $input['seqno']);
            if($read->num_rows()>0){
                $result = 1;
                $msg = "Product sudah diinput!!";
            }else {
                $data = array(
                    'so_number' => $input['so_number'],
                    'docno' => $docno,
                    'seqno' => $input['seqno'],
                    'nobar' => $input['nobar'],
                    'qty_order' => $input['qty_order'],
                    'qty_pl' => $input['qty_pl'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );

                $this->model->insert_data_detail($docno,$data);
                $result = 0;
                $msg = "OK";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "docno"=>$docno
        ));
    }

    function edit_data_detail(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_detailID($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'qty_pl' => $input['qty_pl'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data_detail($input['docno'],$input['id'], $data);
                $result = 0;
                $msg="OK";
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
            "msg" => $msg, "message" => $msg, "docno"=>$input['docno']
        ));
    }

    function read_data_detail($code){
        try {
            $read = $this->model->read_data_detailID($code);
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

    function delete_data_detail(){
        try {
					$code = $this->input->post("id");
            $read = $this->model->read_data_detailID($code);
            if ($read->num_rows() > 0) {
            	$rd = $read->row();
                $read = $this->model->read_transactions_detail($rd->docno);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus";
                }else{
                    $this->model->delete_data_detail($rd->docno, $code);
                    $result = 0;
                    $msg="OK";
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

    function get_product($so_number){
        $special = " a.docno='$so_number' ";
        $f = $this->getParamGrid($special,"nobar");
        $data = $this->model->get_product($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }


}
