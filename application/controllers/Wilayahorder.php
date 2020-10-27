<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayahorder extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Wilayahorder_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function get_customer_type(){
        echo json_encode(array("data"=>$this->model->get_customer_type()->result()));
    }

    function load_grid($code){
        $f = $this->getParamGrid(" regency_id = '".$code."' ","customer_type");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function save_data($sku){
        try {
            $input = $this->toUpper($this->input->post());
            $cek = $this->model_regency->read_data($sku);
            if($cek->num_rows()>0) {
                $r = $cek->row();

//                $time = strtotime($input['eff_date']);
//                $newformat = date('Y-m-d',$time);
                $cek2 = $this->model->check_insert($sku, $input['customer_type']);
                if($cek2->num_rows()>0){
                    $result = 1;
                    $msg = "Tidak boleh menginput multiprice pada Tipe Customer dan Effecive date yang sama.";
                }else {
                    $data = array(
                        'regency_id' => $sku,
                        'customer_type' => $input['customer_type'],
                        'nilai_minimal' => $input['nilai_minimal'],
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
                    );
                    $this->model->insert_data($data);

                    $result = 0;
                    $msg = "OK";
                }
            }else{
                $result = 1;
                $msg = "Produk tidak ditemukan";
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

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {

                $data = array(
                    'customer_type' => $input['customer_type'],
                    'nilai_minimal' => $input['nilai_minimal'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($input['id'], $data);
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

}
