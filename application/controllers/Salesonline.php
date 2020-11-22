<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');

class Salesonline extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Salesonline_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Daily Sales Online';
        $data['content'] = $this->load->view('vSalesonline', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
        if($aksi=="add"){
            $data['title'] = 'Add Daily Sales Online';
            $data['content'] = $this->load->view('vSalesonline_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Daily Sales Online';
            $data['docno'] = $this->input->get('docno');
            $data['content'] = $this->load->view('vSalesonline_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

     function load_grid($status, $prd){ 
        $d= substr($prd,6); 
        $y= substr($prd, 0, 4);
        $m= substr($prd, 4, 2);
        $tgl = $y."-".$m."-".$d;  
        // var_dump($prd)  ;
        if($status=='ALL' AND $prd=='ALL1'){   
            $f = $this->getParamGrid("","doc_date");
        }
        else{
            if($status=='ALL'){  
                $f = $this->getParamGrid(" doc_date='$tgl' ","doc_date");
            }
            else{ 
                $f = $this->getParamGrid(" status='$status' and doc_date='$tgl' ","doc_date");
            }
        } 
        //var_dump($f);
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function load_gridlist(){
        $f = $this->getParamGrid("","status");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
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
                    'qty' => $input['qty'],
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
            $read = $this->model->read_data($input['docno']);
             
            if ($read->num_rows() > 0) {
                $rd = $read->row();
                $data = array(
                    'remark' => $input['remark'],
                    'status' => $input['status'],
                    'docno' => $input['docno'],
                    'so_number' => $input['so_no'],
                    'qty_item' => $input['qty_item'],
                    'qty' => $input['qty'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                if($input['status']=="POSTING"){
                    $data['posting_date'] = date('Y-m-d');
                }

                if($input['reason'] != ""){
                    $this->insert_log("daily_sales_online", $input['docno'], $input['reason']);
                }


                // if($rd->so_number!=$input['so_number']){
                //     $data['docno']=$input['docno'];
                //     $data['so_number']=$input['so_number'];
                //     $total = $this->model->copySOtoPL($data);
                //     if($total>0){
                //         $data['so_number']=$rd->so_number;
                //     }
                // }
                $this->model->update_data($input['docno'], $data);

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
        $f = $this->getParamGrid(" a.docno='$docno' GROUP BY a.docno,a.nobar ","seqno");
        $data = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
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
                    'qty' => $input['qty'],
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
  
            $read = $this->model->read_data_detailID($input['docno']); 
            if ($read->num_rows() > 0) { 
                $bf = $read->row();
                
                $data = array(
                    'disc1_persen' => $input['disc1_persen'],
                    'disc2_persen' => $input['disc2_persen'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );  
                if($bf->disc1_persen != $data['disc1_persen']){
                    $this->model->update_data_detail_disc($input['docno'], $data['disc1_persen'], 1, $data['updby'], $data['upddt']);
                } 
                if($bf->disc2_persen != $data['disc2_persen']){
                    $this->model->update_data_detail_disc($input['docno'], $data['disc2_persen'],2, $data['updby'], $data['upddt']);
                }
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

    function delete_data_detail($code){
        try {
            $read = $this->model->read_data_detailID($code);
            if ($read->num_rows() > 0) {

                $read = $this->model->read_transactions_detail($code);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
                }else{
                    $this->model->delete_data($read->row()->docno, $code);
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
