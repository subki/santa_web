<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Customeraddress extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Customeraddress_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function load_grid($customer_code){
        $f = $this->getParamGrid(" customer_code='$customer_code'","customer_code");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data($code){
        try {
            $input = $this->toUpper($this->input->post());

            $data_customer = array(
                'customer_code' => $code,
                'alias_name' => $input['alias_name'],
                'alamat1' => $input['alamat1'],
                'alamat2' => $input['alamat2'],
                'province_id' => $input['province_id'],
                'regency_id' => $input['regency_id'],
                'zip' => $input['zip'],
                'phone' => $input['phone'],
                'fax' => $input['fax'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );
            $this->model->insert_data($data_customer);
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

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'alias_name' => $input['alias_name'],
                    'alamat1' => $input['alamat1'],
                    'alamat2' => $input['alamat2'],
                    'province_id' => $input['province_id'],
                    'regency_id' => $input['regency_id'],
                    'zip' => $input['zip'],
                    'phone' => $input['phone'],
                    'fax' => $input['fax'],
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

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data($input['id']);
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
