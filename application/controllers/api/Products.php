<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    function __construct(){

        parent::__construct();
        header('Content-Type: application/json');
        $this->load->model('api/Products_model','model');
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function list_product(){
        $offset = $this->input->post('offset')*20;
        $outlet = $this->input->post('outlet');
        $search = $this->input->post('search');
        $periode = date('Ym');
        $xx = $this->model->get_products($outlet, $offset, $search, $periode);
        $stt = 0;
        $msg="OK";
        $data = $xx->result();
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg
                "data"=>$data
            )
        );
    }

}
