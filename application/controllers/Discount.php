<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Discount_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Set Discount Periode';
        $data['content']    = $this->load->view('vDiscount',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $f = $this->getParamGrid("","discount_id");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function load_grid_nobar($code){
        $f = $this->getParamGrid(" discount_id = '".$code."' ","discount_id");
        $data = $this->model->load_grid_nobar($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function load_grid_nobar_by_article_customer(){
        $article = $this->input->get('article');
        $customer_code = $this->input->get('customer');
        $sp = " a.article_code = '".$article."' AND b.customer_code='".$customer_code."' ";
        $f = $this->getParamGrid("","start_date");
        $data = $this->model->load_grid_nobar_by_article_customer($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'], $sp);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function load_grid_location($code){
        $f = $this->getParamGrid(" discount_id = '".$code."' ","discount_id");
        $data = $this->model->load_grid_location($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }


    function get_product($code,$tipe){
        $customer_code=$this->input->get('customer');
        $special = "article_code not in(select c.article_code from discount_item c where c.discount_id='$code')
                    and customer_type='".$tipe."' ";
        $f = $this->getParamGrid($special,"article_code");
        $data = $this->model->get_product($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],$customer_code);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function get_location($code,$tipe){
        $customer_code=$this->input->get('customer');
        $special = "location_code not in(select c.location_code from discount_for c where c.discount_id='$code')
                    and gol_customer=(select gol_customer from customer where customer_code='".$customer_code."')";
        $f = $this->getParamGrid($special,"location_code");
        $data = $this->model->get_location($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function get_customer_type(){
        echo json_encode(array("data"=>$this->model->get_customer_type()->result()));
    }
    function save_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $code = $this->model->generate_auto_number();
            $data = array(
                'discount_id' => $code,
                'customer_type' => $input['customer_type'],
                'customer_code' => $input['customer_code'],
                'start_date' => $this->formatDate("Y-m-d",$input['start_date']),
                'end_date' => $this->formatDate("Y-m-d",$input['end_date']),
                'keterangan' => $input['keterangan'],
                'discount1' => $input['discount1'],
                'margin_persen' => $input['margin_persen'],
                'status' => $input['status'],
                'print_barcode' => $input['print_barcode'],
                'discount_type' => 'promo',//strtoupper($input['discount_type']),
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            $this->model->insert_data($data);

//            if($input['discount_for']=="All Location"){
//                $this->model->insert_data_all_location($code,$input['customer_type']);
//            }
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

    function edit_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['discount_id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'customer_type' => $input['customer_type'],
                    'customer_code' => $input['customer_code'],
                    'start_date' => $this->formatDate("Y-m-d",$input['start_date']),
                    'end_date' => $this->formatDate("Y-m-d",$input['end_date']),
                    'keterangan' => $input['keterangan'],
                    'discount1' => $input['discount1'],
                    'margin_persen' => $input['margin_persen'],
                    'status' => $input['status'],
                    'print_barcode' => $input['print_barcode'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($input['discount_id'], $data);
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

    function delete_data(){
        try {
            $input = $this->toUpper($this->input->post());
            $code = $input['id'];
//            var_dump($input);
//            die();
            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data($code);
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














    function copy_article(){
        try {
            $input = $this->toUpper($this->input->post());
            $from = $input['docno'];
            $code = $this->model->generate_auto_number();
            $user = $this->session->userdata('user_id');
            $tgl = date('Y-m-d H:i:s');
            $tes = $this->model->copy_discount($from,$code, $user, $tgl);

//            $dt = explode(",",$input['combo']);
//            foreach ($dt as $r) {
//                $read2 = $this->model->read_data_nobar($r);
//                if ($read2->num_rows() > 0) {
//                    $data = array(
//                        'discount_id' => $code,
//                        'article_code' => $read2->row()->article_code,
//                        'print_barcode' => $read2->row()->print_barcode,
//                        'discount' => $read2->row()->discount,
//                    );
//
//                    $this->model->insert_data_nobar($data);
//                }
//            }
            $result = $tes==true?0:1;
            $msg = $tes==true?"OK":"Gagal copy trx";
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
            $code="";
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $code, "message" => $msg
        ));
    }

    function save_data_nobar($code){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {
                $r2 = $read->row();
                $dt = explode(",",$input['article_code']);
                foreach ($dt as $r) {
                    $data = array(
                        'discount_id' => $code,
                        'article_code' => $r,
                        'print_barcode' => $r2->print_barcode,
                        'discount' => $r2->discount1,
                        'margin_persen' => $input['margin_persen']
                    );

                    $this->model->insert_data_nobar($data);
                }
                $result = 0;
                $msg = "OK";
            }else{
                $result = 1;
                $msg = "Discount tidak ditemukan";
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

    function edit_data_nobar(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_nobar($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'article_code' => $input['article_code'],
                    'print_barcode' => $input['print_barcode'],
                    'discount' => $input['discount'],
                    'margin_persen' => $input['margin_persen']
                );

                $this->model->update_data_nobar($input['id'], $data);
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
                $r1 = explode("||",$r);
                $data = array(
                    'discount_id' => $code,
                    'customer_code' => $r1[0],
                    'location_code' => $r1[1],
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

//    function edit_data_location(){
//        try {
//            $input = $this->toUpper($this->input->post());
//
//            $read = $this->model->read_data_location($input['id']);
//            if ($read->num_rows() > 0) {
//                $data = array(
//                    'provinsi_id' => $input['provinsi_id'],
//                    'regency_id' => $input['regency_id'],
//                    'user_crt' => $this->session->userdata('user_id'),
//                    'date_crt' => date('Y-m-d'),
//                    'time_crt' => date('H:i:s'),
//                );
//
//                $this->model->update_data_region($input['id'], $data);
//                $result = 0;
//                $msg="OK";
//            } else {
//                $result = 1;
//                $msg="Kode tidak ditemukan";
//            }
//        }catch (Exception $e){
//            $result = 1;
//            $msg=$e->getMessage();
//        }
//        echo json_encode(array(
//            "status" => $result, "isError" => ($result==1),
//            "msg" => $msg, "message" => $msg
//        ));
//    }
//
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
}
