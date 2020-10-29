<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockadjustment extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Stock Adjustment';
        $data['content']    = $this->load->view('vStockAdj',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $store = $this->session->userdata('store_code');
//        $special = " from_store_code ='$store'  and do_type='ADJ'";
        $special = " do_type='ADJ'";
        $f = $this->getParamGrid($special,"doc_date");
        $data = $this->model_delivery->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function get_location($store){
        $special = " location_code in(select location_code from cabang where store_code='$store')";
        $f = $this->getParamGrid($special,"location_code");
        $data = $this->model_delivery->get_location($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data
            )
        );
    }
    function get_store(){
        $store = $this->session->userdata('store_code');
        $special = " store_code = '$store'";
        $f = $this->getParamGrid($special,"store_code");
        $data = $this->model_delivery->get_store($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

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

            $code = $this->model_delivery->generate_auto_number('AJ',$this->formatDate('Y-m-d', $input['doc_date']),$input['from_location_code']);
            $data = array(
                'docno' => $code,
                'doc_date' => $this->formatDate('Y-m-d', $input['doc_date']),
//                'receive_date' => $this->formatDate('Y-m-d', $input['receive_date']),
                'from_store_code' => $input['from_store_code'],
                'from_location_code' => $input['from_location_code'],
                'to_store_code' => $input['to_store_code'],
                'to_location_code' => $input['to_location_code'],
                'do_type' => 'ADJ',
                'status' => $input['status'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            if($this->checkPeriod($data['from_location_code'], $data['doc_date'])) {
                $this->model_delivery->insert_data($data);
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
            "msg" => $msg, "message" => $msg
        ));
    }

    function edit_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model_delivery->read_data($input['docno']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'doc_date' => $this->formatDate('Y-m-d', $input['doc_date']),
                    'receive_date' => $this->formatDate('Y-m-d', $input['receive_date']),
                    'from_store_code' => $input['from_store_code'],
                    'from_location_code' => $input['from_location_code'],
                    'to_store_code' => $input['to_store_code'],
                    'to_location_code' => $input['to_location_code'],
                    'status' => $input['status'],
                    'do_type' => 'ADJ',
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );


                if($data['status']=="APPROVED"){
                    $data2 = array(
                        'status' => 'transfered'
                    );
                    $cc = $this->model_delivery->checkQtyReceive($input['docno']);
                    if($cc > 0){
                        $result = 1;
                        $msg=$cc==2?"Qty Receive di detail item belum di input semua.":"Detail belum di input";
                    }else {
                        if($this->checkPeriod($data['from_location_code'], $data['receive_date'])) {
                            $this->model_delivery->update_data($input['docno'], $data);
                            $this->model_delivery->update_status_data_detail($input['docno'], $data2);
                            $result = 0;
                            $msg="OK";
                        }else{
                            $result = 1;
                            $msg = "Transaksi tidak dalam periode berjalan";
                        }
                    }
                }else{
                    $this->model_delivery->update_data($input['docno'], $data);
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

    function delete_data(){
        try {
            $input = $this->toUpper($this->input->post());
            $code = $input['id'];
            $read = $this->model_delivery->read_data($code);
            if ($read->num_rows() > 0) {
                if($read->row()->status=="Open") {
                    $read = $this->model_delivery->read_transactions($code);
                    if ($read->num_rows() > 0) {
                        $result = 1;
                        $msg = "Data tidak bisa dihapus, sudah ada transaksi";
                    } else {
                        $this->model_delivery->delete_data($code);
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
        $f = $this->getParamGrid(" docno = '".$code."' ","docno");
        $data = $this->model_delivery->load_grid_nobar($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function get_product($code){

        $read = $this->model_delivery->read_data($code);
        if($read->num_rows()>0){
            $loc = $read->row()->from_location_code;
            $prd = $this->formatDate('Ym', $read->row()->doc_date);
            $special = " nobar in(select nobar from stock where location_code='$loc' and periode='$prd') ";
            $f = $this->getParamGrid($special,"nobar");
            $data = $this->model_delivery->get_product($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],$code);
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
                'status' => 'new',
                'keterangan' => $input['keterangan']
            );
//            var_dump($data);
//            die();

            $this->model_delivery->insert_data_nobar($data);
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

            $read = $this->model_delivery->read_data_nobar($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'qty' => $input['qty'],
                    'qty_rcv' => ($input['qty_rcv'])?$input['qty_rcv']:0,
                    'qty_rev' => ($input['qty_rev'])?$input['qty_rev']:0,
                    'status' => $input['status'],
                    'keterangan' => $input['keterangan']
                );

                $this->model_delivery->edit_data_nobar($input['id'], $data);
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
            "msg" => $msg, "message" => $msg
        ));
    }

}
