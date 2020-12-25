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
        $data['title']      = 'Automatic Configuration';
        $data['content']    = $this->load->view('vAuto',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
			$total1 = $this->getParamGrid_BuilderComplete(array(
				"tipe"=>"total",
				"table"=>"automatic_config a",
				"sortir"=>"key",
				"special"=>"",
				"select"=>"a.id, a.kunci, a.nilai",
				"join"=>[]
			));
			$total = $total1->total;
			$data = $total1->data;
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>$total,
                "data" =>$data)
        );

    }

    function edit_data(){
        try {
            $input = $this->input->post();

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'kunci' => $input['kunci'],
                    'nilai' => $input['nilai']
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
}
