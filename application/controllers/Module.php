<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Module_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Module Application';
        $apps = $this->model->get_app();
        $i=0;
        foreach ($apps as $r1){
            $data['apps'][-1]['value'] = NULL;
            $data['apps'][-1]['display'] = '- Please Select -';
            $data['apps'][0]['value'] = 'root';
            $data['apps'][0]['display'] = 'Root';
            $data['apps'][$i+1]['value'] = $r1->app_id;
            $data['apps'][$i+1]['display'] = $r1->app_id.":".$r1->app_name;
            $i++;
        }
        $data['content']    = $this->load->view('vModule',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'app_id';
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
            $input = $this->input->post();

//            var_dump($input);
//            die();
            $idx = ($this->model->get_new_app_id($input['parent_id']));
            if($input['parent_id']==="root"){
                $app_id = "M".sprintf("%02d", ($idx->no+1));
            }else{
                $app_id = $input['parent_id'].sprintf("%02d", ($idx->no+1));
            }
            $data = array(
                'app_id' =>  $app_id,
                'app_name' => $input['app_name'],
                'seq' => $idx->seq,
                'url' => $input['url'],
                'icon' => $input['icon'],
                'parent_id' => $input['parent_id'],
                'create_by' => $this->session->userdata('user_id'),
                'create_date' => date('Y-m-d H:i:s'),
            );

            $this->model->insert_data($data);
            $result = 0;
            $msg = "OK";
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

            $read = $this->model->read_data($input['app_id']);
            if ($read->num_rows() > 0) {
                if(($read->row()->seq != $input['seq']) && $read->row()->seq!=null){
                    if($read->row()->seq < $input['seq']) {
                        $this->model->update_data_seq_minus($input['parent_id'], $read->row()->seq, $input['seq']);
                    }else{
                        $this->model->update_data_seq_plus($input['parent_id'], $read->row()->seq, $input['seq']);
                    }
                }
                $data = array(
                    'app_name' => $input['app_name'],
                    'seq' => $input['seq'],
                    'url' => $input['url'],
                    'icon' => $input['icon'],
                    'parent_id' => $input['parent_id'],
                    'update_by' => $this->session->userdata('user_id'),
                    'update_date' => date('Y-m-d H:i:s'),
                );

                $this->model->update_data($input['app_id'], $data);

                if($read->row()->parent_id != $input['parent_id']){
                    $dt = $this->model->get_app_parent($read->row()->parent_id);
                    $dt2 = $this->model->get_app_parent($input['parent_id']);
                    $i=1;
                    foreach ($dt as $r1){
                        $this->model->update_data($r1->app_id, array('seq'=>$i));
                        $i++;
                    }
                    $i=1;
                    foreach ($dt2 as $r1){
                        $this->model->update_data($r1->app_id, array('seq'=>$i));
                        $i++;
                    }
                }

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
            "data" => $data,
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

}
