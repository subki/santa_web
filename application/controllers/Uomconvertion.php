<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Uomconvertion extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Uomconvertion_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'UOM Convertion';
        $uom = $this->model->get_uom()->result();
        $i=0;
        //$data['uom'][-1]['value'] = NULL;
        //$data['uom'][-1]['display'] = '- Please Select -';
        foreach ($uom as $r1){
            $data['uom'][$i]['value'] = $r1->uom_code;
            $data['uom'][$i]['display'] = $r1->uom_id." : ".$r1->description;
            $i++;
        }
        $data['content']    = $this->load->view('vUomconvertion',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'id';
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

    function save_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['uom_from'],$input['uom_to']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="UOM From dan UOM To harus Unique";
            } else {
                $data = array(
                    'uom_from' => $input['uom_from'],
                    'uom_to' => $input['uom_to'],
                    'convertion' => $input['convertion'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s')
                );

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
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data2($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'uom_from' => $input['uom_from'],
                    'uom_to' => $input['uom_to'],
                    'convertion' => $input['convertion'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
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

    function edit_data_status($code,$status){
        try {
            $code = urldecode($code);
            $read = $this->model->read_data2($code);
            if ($read->num_rows() > 0) {
                $data = array(
                    'status' => $status,
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($code, $data);
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


    function read_data($code,$code2){
        try {
            $read = $this->model->read_data($code, $code2);
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

    function delete_data($code,$code2){
        try {
            $read = $this->model->read_data($code,$code2);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code,$code2);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data($code,$code2);
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

    function export_data(){
        $filename = 'UOM_CONVERTION_' . date('Ymd') . '.csv';
        $header = array("FROM", "TO","Nilai Konversi", "Status","Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['id','uom_from','uom_to', 'code1','code2'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }

}
