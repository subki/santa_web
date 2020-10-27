<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    function __construct(){

        parent::__construct();
        $this->load->helper('cookie');
        $this->load->model('api/Users_model','model');
        $this->load->model('auth_model','auth');
        header('Content-Type: application/json');
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function login(){

        $token 	= $this->input->post('log_token');
        $uid 	= $this->input->post('username');
        $pwd 	= md5($this->input->post('password'));

        $data 	= $this->auth->check_user($uid,$pwd);

        if($data!=null){
            if($data->tgl_resign=="") {
                $sess_array = array(
                    'id' => $data->user_id,
                    'role' => $data->role,
                    'fullname' => $data->fullname,
                    'outlet_code' => $data->outlet_code,
                    'logged_in' => TRUE
                );

                $this->model->update_password($data->user_id, array('token' => $token));
                $this->session->set_userdata($sess_array);

                $stt = 0;
                $msg = "OK";
            }else{
                $stt = 1;
                $msg = "Anda sudah tidak memiliki akses ke aplikasi";
            }

        } else{
            $stt = 1;
            $msg = "Invalid username/password";
        }
        echo json_encode(array(
            "status" => $stt,
            "msg" =>  $msg,
            "profile" => $data
        ));
    }

    function verifikasi($nik){
        $data = $this->model->getUserByNik($nik);

        if($data->num_rows()>0){
            if($data->row()->activate == "not active") {
                $stt = 0;
                $msg = "OK";
                $this->model->update_password($data->row()->user_id, array('activate'=>'active'));
                $datax = $data->row();
            }else{
                $stt = 1;
                $msg = "NIK sudah di aktivasi";
                $datax = null;
            }
        }else{
            $stt = 1;
            $msg = "NIK tidak ditemukan";
            $datax = null;
        }
        echo json_encode(array(
            "status" => $stt,
            "msg" => $msg, "message" => $msg,
            "data" => $datax
        ));
    }

    function update_password(){
        $nik = $this->input->post('user_id');
        $old = $this->input->post('old_password');
        $new = $this->input->post('new_password');
        $username = $this->input->post('username');
        $tipe = $this->input->post('tipe');

        $data = $this->model->getUserByNik($nik);

        if($data->num_rows()>0){
            $user = $data->row();
            if($tipe=='update') {
                if (md5($old) == $user->password) {
                    $data = array(
//                        'username' => $username,
                        'password' => md5($new)
                    );

                    $this->model->update_password($user->user_id, $data);
                    $stt = 0;
                    $msg = "OK";
                } else {
                    $stt = 1;
                    $msg = "Invalid current password";
                }
            }else if($tipe=='verifikasi'){
                $data = array(
                    'username' => $username,
                    'password' => md5($new)
                );
                $check = $this->model->cek_username($username);
                if($check->num_rows() > 0){
                    $stt = 1;
                    $msg = "Username tidak tersedia, silahkan ganti username dengan yg lain.";
                }else {
                    $this->model->update_password($user->user_id, $data);
                    $stt = 0;
                    $msg = "OK";
                }
            }else{
                $stt = 1;
                $msg = "Update password ditolak!";
            }
        }else{
            $stt = 1;
            $msg = "NIK tidak ditemukan";
        }
        echo json_encode(array(
            "status" => $stt,
            "msg" => $msg, "message" => $msg
        ));
    }


    function list_employee(){
        $offset = $this->input->post('offset')*20;
        $outlet = $this->input->post('outlet');
        $search = $this->input->post('search');
        $tipe = $this->input->post('status');
        $xx = $this->model->get_list_employee($outlet, $offset, $search, $tipe);
        $stt = 0;
        $msg="OK";
        $data = $xx->result();
        echo json_encode(array(
                "status" => $stt,
                "msg" => $outlet,
                "data"=>$data
            )
        );
    }

    function update_user(){
        $user_id = $this->input->post('user_id');
        $activate = $this->input->post('activate');
        $req_reset = $this->input->post('req_reset');
        $xx = $this->model->getUserByNik($user_id);

        if($xx->num_rows()>0) {
            $data = array(
                'activate' => $activate,
                'req_reset' => $req_reset,
            );
            $this->model->update_password($user_id, $data);
            $stt = 0;
            $msg = "OK";
        }else{
            $stt = 1;
            $msg = "NIK Anda tidak terdaftar";
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg
            )
        );
    }

}
