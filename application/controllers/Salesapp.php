<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Salesapp extends IO_Controller {

    function __construct(){

        parent::__construct();
//        $this->load->model('Salesorder_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Sales Order Approval';
        $data['content'] = $this->load->view('vSalesorderapp', $data, TRUE);
        $this->load->view('main',$data);
    }

    function monitor(){
        $data['title'] = 'Sales Order Monitoring';
        $data['content'] = $this->load->view('vSalesorderapp_monitor', $data, TRUE);
        $this->load->view('main',$data);
    }

    function load_grid(){
        $f = $this->getParamGrid("status='OPEN' AND sales_after_tax>credit_remain","doc_date");
        $data = $this->model_sales->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function load_grid_monitor(){
        $f = $this->getParamGrid("status='ON ORDER'","doc_date");
        $data = $this->model_sales->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function edit_data_header(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model_sales->read_data($input['docno']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'status' => $input['status'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
                if($input['status']=="ON ORDER"){
                    $data['posting_date'] = date('Y-m-d');
                }
                $this->model_sales->update_data($input['docno'], $data);

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
            $read = $this->model_sales->read_data($code);
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

}
