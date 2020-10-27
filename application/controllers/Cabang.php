<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabang extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Cabang_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Cabang';
        $data['content']    = $this->load->view('vCabang',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'code';
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
            if(count($fltr)>0) $app .= " AND store_code = '".$code."' ";
            else $app .= " where store_code = '".$code."' ";
        }else{
            $app .= " where store_code = '".$code."' ";
        }
        $data = $this->model->get_list_data($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function save_data(){
        try {
            $input = $this->toUpper($this->input->post());
            $data = array(
                'store_code' => $input['store_code2'],
                'kode_cabang' => $input['kode_cabang'],
                'nama_cabang' => $input['nama_cabang'],
                'prefix_trx' => $input['prefix_trx'],
                'type' => $input['type'],
                'flag' => $input['flag'],
                'location_code' => $input['location_code'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            if($this->checkChar($input['kode_cabang'])){
                $this->model->insert_data($data);
                $result = 0;
                $msg = "OK";
            }else{
                $result = 1;
                $msg = "Kode hanya boleh karakter huruf dan angka";
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
            $input = $this->toUpper($this->input->post());
//            var_dump($input);
//            die();

            $read = $this->model->read_data($input['store_code2'], $input['kode_cabang']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'nama_cabang' => $input['nama_cabang'],
                    'prefix_trx' => $input['prefix_trx'],
                    'type' => $input['type'],
                    'flag' => $input['flag'],
                    'location_code' => $input['location_code'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($input['store_code2'], $input['kode_cabang'], $data);
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

    function read_data($code, $code1){
        try {
            $read = $this->model->read_data($code, $code1);
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

    function delete_data($code, $code1){
        try {
            $read = $this->model->read_data($code, $code1);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code1);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data($code, $code1);
                    $result = 0;
                    $msg="OK";
//                }
//            } else {
//                $result = 1;
//                $msg="Kode tidak ditemukan";
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
