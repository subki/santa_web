<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    function __construct(){

        parent::__construct();
        header('Content-Type: application/json');
        $this->load->model('Report_model','model');
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function list_report(){
        $outlet = $this->input->get('outlet');
        $periode = $this->input->get('periode');
        $data = $this->model->report_sales_daily($outlet, $periode)->result();
        $stt = 0;
        $msg="OK";
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data
            )
        );
    }

    function get_principle(){
        echo json_encode(array(
                "status" => 0,
                "msg" => "OK",
                "data"=>$this->model->get_principle()->result()
            )
        );
    }
    function get_outlet($outlet_code=""){
        echo json_encode(array(
                "status" => 0,
                "msg" => "OK",
                "data"=>$this->model->get_outlet($outlet_code)->result()
            )
        );
    }
    function get_sku(){
        echo json_encode(array(
                "status" => 0,
                "msg" => "OK",
                "data"=>$this->model->get_sku()->result()
            )
        );
    }

}
