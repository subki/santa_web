<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Headcustomer extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Headcustomer_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Head Customer';
        $data['content']    = $this->load->view('vHeadCustomer',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'head_customer_id';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
        }
        $data = $this->model->get_list_data($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function get_customers($code){
        $f = $this->getParamGrid(" head_customer_id = '".$code."'","head_customer_id");
        $data = $this->model->get_customers($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function get_regency($code){
        echo json_encode(array("data"=>$this->model->get_regency($code)->result()));
    }
    function get_provinsi(){
        echo json_encode(array("data"=>$this->model->get_provinsi()->result()));
    }
    function get_customertype(){
        echo json_encode(array("data"=>$this->model->get_customertype()->result()));
    }
    function save_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['head_customer_id']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Kode tidak ditemukan";
            }else {
                $data = array(
                    'head_customer_id' => $input['head_customer_id'],
                    'nama_company' => $input['nama_company'],
                    'customer_type' => $input['customer_type'],
                    'market_type' => $input['market_type'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s')
                );

                if($this->checkChar($input['head_customer_id'])){
                    $this->model->insert_data($data);
                    $result = 0;
                    $msg = "OK";
                }else{
                    $result = 1;
                    $msg = "Kode hanya boleh karakter huruf dan angka";
                }
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

            $read = $this->model->read_data($input['head_customer_id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'nama_company' => $input['nama_company'],
                    'customer_type' => $input['customer_type'],
                    'market_type' => $input['market_type'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($input['head_customer_id'], $data);
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


    function get_region($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'id';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND salesman_id = '".$code."' ";
            else $app .= " where salesman_id = '".$code."' ";
        }else{
            $app .= " where salesman_id = '".$code."' ";
        }
        $data = $this->model->get_region($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data_region($supplier_code){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'salesman_id' => $supplier_code,
                'provinsi_id' => $input['provinsi_id'],
                'regency_id' => $input['regency_id'],
                'user_crt' => $this->session->userdata('user_id'),
                'date_crt' => date('Y-m-d'),
                'time_crt' => date('H:i:s'),
            );

            $this->model->insert_data_region($data);
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

    function edit_data_region(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_region($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'provinsi_id' => $input['provinsi_id'],
                    'regency_id' => $input['regency_id'],
                    'user_crt' => $this->session->userdata('user_id'),
                    'date_crt' => date('Y-m-d'),
                    'time_crt' => date('H:i:s'),
                );

                $this->model->update_data_region($input['id'], $data);
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

    function delete_data_region(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->read_data_region($input['id']);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                $this->model->delete_data_region($input['id']);
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

    function export_data(){
        $filename = 'HEADCUSTOMER_' . date('Ymd') . '.csv';
        $header = array("Kode", "Head Customer","Customer Type","Market Type","Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['customer_type'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }
}
