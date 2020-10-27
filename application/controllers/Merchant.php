<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Merchant_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Merchant Setup';
        $data['content'] = $this->load->view('vMerchant', $data, TRUE);
        $this->load->view('main',$data);
    }

    function edit_data(){
        try {
            $input = $this->toUpper($this->input->post());
            $this->model->update_data($input['key'], array('nilai' => $input['value']));
            $result = 0;
            $msg="OK";
        } catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg
        ));
    }


}
