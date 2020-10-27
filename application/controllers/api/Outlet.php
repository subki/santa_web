<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Outlet extends CI_Controller {

    function __construct(){

        parent::__construct();
        header('Content-Type: application/json');
        $this->load->model('Outlet_model','model');
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function list_outlet(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):100;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'outlet_code';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');

        $data = $this->model->get_list_data($page,$rows,$sort,$order,$role);

        echo json_encode(array(
                "status" => 0,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

}
