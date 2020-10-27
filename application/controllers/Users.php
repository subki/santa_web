<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Users_model','model');
        $this->load->model('Auth_model','model2');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Users';
        $data['content']    = $this->load->view('vUsers',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'user_id';
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
    function get_store(){
        echo json_encode(array("data"=>$this->model->get_store()->result()));
    }

    function get_location($store){
        echo json_encode(array("data"=>$this->model->get_location($store)->result()));
    }

    function save_data(){
        try {
            $input = $this->input->post();

            $data = array(
                'nik' => $input['nik'],
                'fullname' => $input['fullname'],
                'user_name' => $input['user_name'],
                'user_password' => $input['user_password'],
                'store_code' => $input['store_code'],
                'location_code' => $input['location_code'],
                'kode_otoritas' => $input['kode_otoritas'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s'),
            );

            if($input['kode_otoritas']!=""){
                $cek = $this->model->cekOtoritas($input['kode_otoritas']);
                if($cek->num_rows()>0){
                    $result = 1;
                    $msg="Kode Otoritas harus Unik";
                }else{
                    $this->model->insert_data($data);
                    $result = 0;
                    $msg = "OK";
                }
            }else{
                $this->model->insert_data($data);
                $result = 0;
                $msg = "OK";
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
            $input = $this->input->post();

            $read = $this->model->read_data($input['user_id']);
            if ($read->num_rows() > 0) {
                if($read->row()->user_password != md5($input['user_password'])) {
                    $this->model->update_data2($input['user_id'], $input['user_password']);
                }

                $data = array(
                    'nik' => $input['nik'],
                    'fullname' => $input['fullname'],
                    'user_name' => $input['user_name'],
                    'store_code' => $input['store_code'],
                    'location_code' => $input['location_code'],
                    'kode_otoritas' => $input['kode_otoritas'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s'),
                );

                if($read->row()->kode_otoritas != $input['kode_otoritas']) {
                    $cek = $this->model->cekOtoritas($input['kode_otoritas']);
                    if($cek->num_rows()>0){
                        $result = 1;
                        $msg="Kode Otoritas harus Unik";
                    }else{
                        $this->model->update_data($input['user_id'], $data);
                        $result = 0;
                        $msg="OK";
                    }
                }else{
                    $this->model->update_data($input['user_id'], $data);
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

    function delete_data($code){
        try {
            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data($code);
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

}
