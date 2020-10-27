<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Privillege extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Privillege_model','model');
        $this->load->model('Module_model','modul');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Privillege';
        $data['content']    = $this->load->view('vPrivillege',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'users_group_id';
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
        $data2 = [];
        $i=0;
        foreach ($data as $row){
            $dt = array(
                "id" =>$row->id,
                "text" => $row->text
            );
//            $ch = $this->model->get_children($row->id);
//            if($ch->num_rows() > 0){
//                $dt['children'] = $ch->result();
//            }
            $data2[$i] = $dt;
            $i++;
        }

        echo json_encode($data);

    }

    function show_users_group($group, $detail){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'users_group_detail_id';
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
            if(count($fltr)>0) $app .= " AND users_group_id = $group AND users_group_detail_id = $detail ";
            else $app .= " WHERE users_group_id = $group AND users_group_detail_id = $detail ";
        }else{
            $app .= " WHERE users_group_id = $group AND users_group_detail_id = $detail ";
        }
        $data = $this->model->show_users_group($page,$rows,$sort,$order,$role, $app);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "data" =>$data)
        );

    }
    function get_subgrid($id){
        $data = $this->model->get_children($id)->result();
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "data" =>$data)
        );
    }
    function get_subgrid2($id){
        $data = $this->model->get_app($id)->result();
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "data" =>$data)
        );
    }
    function get_users($id){
        $data = $this->model->get_users($id)->result();
        echo json_encode(array("data" => $data));
    }
    function get_subgrid_user($id){
        $data = $this->model->get_children2($id)->result();
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "data" =>$data)
        );
    }
    function get_subgrid2_user($id){
        $data = $this->model->get_app2($id)->result();
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "data" =>$data)
        );
    }

    function save_users_group_detail(){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'users_group_id' => $input['group_id'],
                'app_id' => $input['app_id'],
            );
            $this->model->save_users_group_detail($data, $input['group_id']);

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
    function save_users_group_detail2(){
        try {
            $input = $this->toUpper($this->input->post());

            $this->model->save_users_group_detail2($input['group_id'],$input['user_id']);

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
    function change_permission(){
        try {
            $input = $this->toUpper($this->input->post());

            $this->model->change_permission($input['group_id'],$input['field'], $input['nilai']);

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

    function save_users_group_det_user($group, $detail){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'users_group_id' => $group,
                'users_group_detail_id' => $detail,
                'user_id' => $input['user_id'],
                'allow_add' => $input['allow_add'],
                'allow_edit' => $input['allow_edit'],
                'allow_delete' => $input['allow_delete'],
                'allow_print' => $input['allow_print'],
                'allow_approve' => $input['allow_approve'],
                'allow_approve2' => $input['allow_approve2'],
                'allow_approve3' => $input['allow_approve3'],
                'allow_approve4' => $input['allow_approve4'],
                'allow_approve5' => $input['allow_approve5'],
                'allow_download' => $input['allow_download'],
                'allow_unposting' => $input['allow_unposting'],
            );

            $this->model->save_users_group_det_user($data);
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

    function remove_users_group_detail(){
        try {
            $input = $this->toUpper($this->input->post());
//            var_dump($input);
//            die();
            $this->model->remove_users_group_detail($input['group_id'], $input['app_id']);
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

    function remove_users_group_detail2(){
        try {
            $input = $this->toUpper($this->input->post());
//            var_dump($input);
//            die();
            $this->model->remove_users_group_detail2($input['group_id'], $input['user_id']);
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

    function save_group(){
        $data = array(
            'group_name' => 'New Group',
        );

        $this->model->insert_data($data);
        $this->load_grid();
    }

    function update_group(){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'group_name' => $input['text'],
            );

            $this->model->update_group($input['id'], $data);
            $result = 0;
            $msg="OK";
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg
        ));
    }

    function update_users_group_det_user(){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'user_id' => $input['user_id'],
                'allow_add' => $input['allow_add'],
                'allow_edit' => $input['allow_edit'],
                'allow_delete' => $input['allow_delete'],
                'allow_print' => $input['allow_print'],
                'allow_approve' => $input['allow_approve'],
                'allow_approve2' => $input['allow_approve2'],
                'allow_approve3' => $input['allow_approve3'],
                'allow_approve4' => $input['allow_approve4'],
                'allow_approve5' => $input['allow_approve5'],
                'allow_download' => $input['allow_download'],
                'allow_unposting' => $input['allow_unposting']
            );

            $this->model->update_users_group_det_user($input['id'], $data);
            $result = 0;
            $msg="OK";
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

    function delete_group(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->read_transactions($input['id']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Data tidak bisa dihapus, sudah ada transaksi";
            }else{
                $this->model->delete_data($input['id']);
                $result = 0;
                $msg="OK";
            }
//            $read = $this->model->read_data($code);
//            if ($read->num_rows() > 0) {
//
//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
//                    $this->model->delete_data($code);
//                    $result = 0;
//                    $msg="OK";
//                }
//            } else {
//                $result = 1;
//                $msg="Kode tidak ditemukan";
//            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg
        ));
    }

    function delete_users_group_det_user(){
        try {
            $input = $this->toUpper($this->input->post());
//            $read = $this->model->read_transactions($input['id']);
//            if ($read->num_rows() > 0) {
//                $result = 1;
//                $msg="Data tidak bisa dihapus, sudah ada transaksi";
//            }else{
                $this->model->delete_users_group_det_user($input['id']);
                $result = 0;
                $msg="OK";
//            }
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
