<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){

        parent::__construct();
        $this->load->model('auth_model','model');
    }

   	function index(){
   		$this->load->view('vLogin');
   	}

	function login_act(){

		$uid 	= $this->input->post('txt_uid');
		$pwd 	= md5($this->input->post('txt_pwd'));
//		$pwd 	= $this->input->post('txt_pwd');

		$data 	= $this->model->check_user($uid);
//		pre($data);
//		var_dump($this->input->post('txt_pwd'));
//		var_dump($pwd);
//		var_dump($data->pass);
//		var_dump(strcmp($data->pass,$pwd));
//		die();

	   	if($data!=null){
//	   		pre("masuk");
//				pre([$data->pass,$pwd]);
	   	    if($data->pass==$pwd) {
                $sess_array = array(
                    'user_id' => $data->user_id,
                    'nik' => $data->nik,
                    'fullname' => $data->fullname,
                    'store_code' => $data->store_code,
                    'store_name' => $data->store_name,
                    'location_code' => $data->location_code,
                    'location_name' => $data->location_name,
                    'lokasi_sales' => $data->lokasi_sales,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($sess_array);

                redirect(base_url() . 'welcome', 'refresh');
            }else{
                redirect('auth','refresh');
            }
	   	}else{
	     	redirect('auth','refresh');
	   	}
	}

	function logout_act(){

		$this->session->unset_userdata('logged_in');
   		session_destroy();

   		redirect('auth','refresh');
	}

	function closing_stock(){
        $now = date('Ym');
       echo $this->model->closing_stock($now);
    }

    function execute(){
	    $data = array(
            "201502", "201503", "201504", "201505", "201506", "201507", "201508", "201509", "201510", "201511", "201512",
	        "201601", "201602", "201603", "201604", "201605", "201606", "201607", "201608", "201609", "201610", "201611", "201612",
	        "201701", "201702", "201703", "201704", "201705", "201706", "201707", "201708", "201709", "201710", "201711", "201712",
	        "201801", "201802", "201803", "201804", "201805", "201806", "201807", "201808", "201809", "201810", "201811", "201812",
	        "201901", "201902", "201903", "201904", "201905", "201906", "201907", "201908", "201909", "201910", "201911", "201912",
	        "202001", "202002", "202003", "202004", "202005", "202006"
        );
	    foreach ($data as $prd){
	        $this->model->pindah($prd);
            $conn = $this->db->conn_id;
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            $this->model->awal($prd);
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            $this->model->masuk($prd);
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            $this->model->keluar($prd);
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            $this->model->adjust($prd);
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            $this->model->jual($prd);
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            $this->model->retur($prd);
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            $this->model->akhir($prd);
            do { if ($result = mysqli_store_result($conn)) { mysqli_free_result($result); }
            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
        }

    }
}
