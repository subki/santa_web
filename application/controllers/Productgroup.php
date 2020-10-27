<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Productgroup extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Productgroup_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Product Group';
        $data['content']    = $this->load->view('vProductgroup',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'class_code';
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
    function load_grid2($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'subclass_code';
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
            if(count($fltr)>0) $app .= " AND class_code = '".$code."' ";
            else $app .= " where class_code = '".$code."' ";
        }else{
            $app .= " where class_code = '".$code."' ";
        }
        $data = $this->model->get_list_data2($page,$rows,$sort,$order,$role, $app);

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
			
            $read = $this->model->read_data($input['class_code']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Kode Class harus Unique";
            } else {
                $data = array(
                    'class_code' => $input['class_code'],
                    'description' => $input['description'],
                    'jenis_barang' => $input['jenis_barang'],
                    'addcost' => $input['addcost'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s')
                );

                if($this->checkChar($input['class_code'])){
                    $this->model->insert_data($data);
                    $result = 0;
                    $msg = "OK";
                }else{
                    $result = 1;
                    $msg = "Kode hanya boleh karakter huruf dan angka";
                }
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

            $read = $this->model->read_data($input['class_code']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'description' => $input['description'],
                    'jenis_barang' => $input['jenis_barang'],
                    'addcost' => $input['addcost'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($input['class_code'], $data);
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

                $read = $this->model->read_transactions($code);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
                }else{
                    $this->model->delete_data($code);
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

    function save_data2(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->generate_auto_number($input['class_code']);
            $data = array(
                'subclass_code' => $read,
                'class_code' => $input['class_code'],
                'description' => $input['description'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            if($this->checkChar($read)){
                $this->model->insert_data2($data);
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

    function edit_data2(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data2($input['class_code'],$input['subclass_code']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'description' => $input['description'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data2($input['class_code'],$input['subclass_code'], $data);
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

    function read_data2($code,$code2){
        try {
            $read = $this->model->read_data2($code,$code2);
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

    function delete_data2($code,$code2){
        try {
            $read = $this->model->read_data2($code,$code2);
            if ($read->num_rows() > 0) {

                $read = $this->model->read_transactions2($code,$code2);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
                }else{
                    $this->model->delete_data2($code,$code2);
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

    function export_data(){
        $filename = 'PRODUCTGROUP_' . date('Ymd') . '.csv';
        $header = array("Kode", "Nama","Add. Cost","Jenis Barang","Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data_2($app);
        $unset = [];
        $top = array();
        $this->export_csv($filename,$header, $data,$unset, $top);
    }
    function export_data_sub($code){
        $filename = 'SUBPRODUCTGROUP_' . date('Ymd') . '.csv';
        $read = $this->model->read_data($code)->row();
        $top = array("Header",$read->class_code." - ".$read->description);
        $header = array("Sub ID", "Description","Create By","Update By", "Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data_sub2($app);
        $unset = ['class_code'];
        $this->export_csv($filename,$header, $data, $unset, $top);
    }
}
