<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Location_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Location';
//        $apps = $this->model->get_customer_type()->result();
//        $i=0;
//        foreach ($apps as $r1){
//            $data['select'][$i]['value'] = $r1->code;
//            $data['select'][$i]['display'] = $r1->code.":".$r1->description;
//            $i++;
//        }
        $data['content']    = $this->load->view('vLocation',$data,TRUE);

        $this->load->view('main',$data);
    }

    function get_customer_type(){
        echo json_encode($this->model->get_customer_type()->result());
    }
    function load_grid(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'location_code';
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

    function load_grid_location_close($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'periode';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'desc';
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
            if(count($fltr)>0) $app .= " AND location = '".$code."' ";
            else $app .= " where location = '".$code."' ";
        }else{
            $app .= " where location = '".$code."' ";
        }
        $data = $this->model->get_list_data_location_close($page,$rows,$sort,$order,$role, $app);

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
                'location_code' => $input['location_code'],
                'description' => $input['description'],
                'pkp' => $input['pkp'],
                'price_type' => $input['price_type'],
                'check_stock' => $input['check_stock'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            $read = $this->model->read_data($input['location_code']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg = "Kode harus unik.";
            }else{
                if($this->checkChar($input['location_code'])){
                    //cek length kode
                    if(strlen($input['location_code'])==3) {
                        $this->model->insert_data($data);
                        $result = 0;
                        $msg = "OK";
                    }else{
                        $result = 1;
                        $msg = "Kode harus 3 digit";
                    }
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

            $read = $this->model->read_data($input['location_code']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'description' => $input['description'],
                    'pkp' => $input['pkp'],
                    'price_type' => $input['price_type'],
                    'check_stock' => $input['check_stock'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($input['location_code'], $data);
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


    function save_data_sub($code){
        try {
            $input = $this->toUpper($this->input->post());
            $code = urldecode($code);
            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {

                $read2 = $this->model->read_data_periode($code,$this->formatDate("Y-m", $input['periode']));
                if($read2->num_rows()==0) {
                    $data = array(
                        'location' => $code,
                        'periode' => $this->formatDate("Y-m-d", $input['periode']),
                        'status_cl' => $input['status_cl'],
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
                    );

                    $this->model->insert_data_sub($data);
                    $result = 0;
                    $msg = "OK";
                }else{
                    $result = 1;
                    $msg="Periode berjalan sudah di input";
                }
            } else {
                $result = 1;
                $msg="Kode Location tidak ditemukan";
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

    function edit_data_sub(){
        try {
            $input = $this->toUpper($this->input->post());
//            $code = urldecode($code);
            $read = $this->model->read_data_sub($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
//                    'periode' => $input['periode'],
                    'status_cl' => $input['status_cl'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
                if($input['status_cl']=="OPEN"){
                    $read2 = $this->model->read_data_periode2($read->row()->location, $this->formatDate("Y-m", $input['periode']));
                    if($read2->num_rows()>1){
                        $result = 1;
                        $msg="Open periode hanya maksimal 2 bulan";
                    }else{
                        $this->model->update_data_sub($input['id'], $data);
                        $result = 0;
                        $msg="OK";
                    }
                }else{
                    $this->model->update_data_sub($input['id'], $data);
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
        $filename = 'LOCATION_' . date('Ymd') . '.csv';
        $header = array("Kode", "Nama Lokasi","PKP", "Check Stock", "Price Type","Create By", "Create Date","Update By","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['price_type','type'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }

}
