<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Autoconfig extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Autoconfig_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
//    	pre($this->session->userdata());
        $data['title']      = 'Automatic Configuration';
        $data['content']    = $this->load->view('vAuto',$data,TRUE);

        $this->load->view('main',$data);
    }

	function edit_data(){
		try {
			$input = $this->input->post();
			$this->db->where("id",$input['id']);
			$this->db->update("automatic_config",['nilai'=>$input['nilai']]);
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
