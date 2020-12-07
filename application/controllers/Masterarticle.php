<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterarticle extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Masterarticle_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Article';
        $apps = $this->model->get_product_size()->result();
        $i=0;
        foreach ($apps as $r1){
            $data['select'][-1]['value'] = NULL;
            $data['select'][-1]['display'] = '- Please Select -';
            $data['select'][$i]['value'] = $r1->size_code;
            $data['select'][$i]['display'] = $r1->size_code." : ".$r1->description;
            $i++;
        }
        $apps = $this->model->get_product_colour()->result();
        foreach ($apps as $r1){
            $data['select2'][-1]['value'] = NULL;
            $data['select2'][-1]['display'] = '- Please Select -';
            $data['select2'][$i]['value'] = $r1->colour_code;
            $data['select2'][$i]['display'] = $r1->colour_code." : ".$r1->description;
            $i++;
        }
        $data['content']    = $this->load->view('vArticle',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $f = $this->getParamGrid("","article_code");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function load_grid_size($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'article_code';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $code = urldecode($code);
        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND article_code = '".$code."' ";
            else $app .= " where article_code = '".$code."' ";
        }else{
            $app .= " where article_code = '".$code."' ";
        }
        $data = $this->model->load_grid_size($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data,
                "param"=>$app)
        );

    }
    function get_colour2($code){
        $code = urldecode($code);
        echo json_encode(array("data"=>$this->model->get_colour($code)->result()));
    }

    function get_colour($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'article_code';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $code = urldecode($code);
        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND colour_code not in (select art_colour_code from article_colour where article_code='$code') ";
            else $app .= " where colour_code not in (select art_colour_code from article_colour where article_code='$code') ";
        }else{
            $app .= " where colour_code not in (select art_colour_code from article_colour where article_code='$code') ";
        }
        $data = $this->model->get_colour($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data,
                "param"=>$app)
        );

    }

    function get_size($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'article_code';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $code = urldecode($code);
        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND size_code not in (select art_size_code from article_size where article_code='$code') ";
            else $app .= " where size_code not in (select art_size_code from article_size where article_code='$code') ";
        }else{
            $app .= " where size_code not in (select art_size_code from article_size where article_code='$code') ";
        }
        $data = $this->model->get_size($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data,
                "param"=>$app)
        );

    }

    function load_grid_colour($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'article_code';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $code = urldecode($code);
        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND article_code = '".$code."' ";
            else $app .= " where article_code = '".$code."' ";
        }else{
            $app .= " where article_code = '".$code."' ";
        }
        $data = $this->model->load_grid_colour($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data,
                "param"=>$app)
        );

    }

    function load_grid_size_colour($code, $size, $colour){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'article_code';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $code = urldecode($code);
        $size = urldecode($size);
        $colour = urldecode($colour);
        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND article_code = '".$code."' ";
            else $app .= " where article_code = '".$code."' ";
        }else{
            $app .= " where article_code = '".$code."' ";
        }
        $data = $this->model->load_grid_size_colour($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data,
                "param"=>$app)
        );

    }

    function save_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['article_code']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Kode Article harus Unique";
            } else {
                $data = array(
                    'article_code' => $input['article_code'],
                    'article_name' => $input['article_name'],
                    'style' => $input['style'],
                    'bom_pcs' => $input['bom_pcs'],
                    'foh_pcs' => $input['foh_pcs'],
                    'ongkos_jahit_pcs' => $input['ongkos_jahit_pcs'],
                    'operation_cost' => $input['operation_cost'],
                    'interest_cost' => $input['interest_cost'],
                    'buffer_cost' => $input['buffer_cost'],
                    'ekspedisi' => $input['ekspedisi'],
                    'hpp1' => $input['hpp1'],
                    'hpp2' => $input['hpp2'],
                    'hpp_ekspedisi' => $input['hpp_ekspedisi'],
                    'keterangan' => $input['keterangan'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s')
                );

                if($this->checkChar($input['article_code'])){
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

    function save_data_size($article_code){
        try {
            $input = $this->toUpper($this->input->post());
            $article_code = urldecode($article_code);

            $read = $this->model->read_data_size($article_code, $input['art_size_code']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Kode Size sudah Ada";
            } else {
                $data = array(
                    'art_size_code' => $input['art_size_code'],
                    'article_code' => $article_code,
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s')
                );

                $this->model->insert_data_size($data);
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

    function save_data_colour($article_code){
        try {
            $input = $this->toUpper($this->input->post());
            $article_code = urldecode($article_code);

            $read = $this->model->read_data_colour($article_code, $input['art_colour_code']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Kode Colour sudah Ada";
            } else {
                $data = array(
                    'art_colour_code' => $input['art_colour_code'],
                    'article_code' => $article_code,
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s')
                );

                $this->model->insert_data_colour($data);
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

    function save_data_size_colour($art, $size, $colour){
        try {
            $input = $this->toUpper($this->input->post());
            $art = urldecode($art);
            $size = urldecode($size);
            $colour = urldecode($colour);

            $read = $this->model->read_data_size_colour($art, $size,$colour, $input['sku']);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Kode Colour sudah di Ada";
            } else {
                $data = array(
                    'sku' => $input['sku'],
                    'article_code' => $art,
                    'art_size_id' => $size,
                    'art_colour_id' => $colour,
                    'user_crt' => $this->session->userdata('user_id'),
                    'date_crt' => date('Y-m-d'),
                    'time_crt' => date('H:i:s'),
                );

                $this->model->insert_data_size_colour($data);
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


    function edit_data_size(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_size_id($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'art_size_code' => $input['art_size_code'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data_size($input['id'], $data);
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

    function edit_data_colour(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_colour_id($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'art_colour_code' => $input['art_colour_code'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data_colour($input['id'], $data);
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

    function edit_data_size_colour(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_size_colour_id($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'sku' => $input['sku'],
                    'user_crt' => $this->session->userdata('user_id'),
                    'date_crt' => date('Y-m-d'),
                    'time_crt' => date('H:i:s'),
                );

                $this->model->update_data_size_colour($input['id'], $data);
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

    function edit_data($tipe){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['article_code']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'article_name' => $input['article_name'],
                    'style' => $input['style'],
                    'bom_pcs' => $input['bom_pcs'],
                    'foh_pcs' => $input['foh_pcs'],
                    'ongkos_jahit_pcs' => $input['ongkos_jahit_pcs'],
                    'operation_cost' => $input['operation_cost'],
                    'interest_cost' => $input['interest_cost'],
                    'buffer_cost' => $input['buffer_cost'],
                    'ekspedisi' => $input['ekspedisi'],
                    'hpp1' => $input['hpp1'],
                    'hpp2' => $input['hpp2'],
                    'hpp_ekspedisi' => $input['hpp_ekspedisi'],
                    'keterangan' => $input['keterangan'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
//                var_dump($input['article_code']);
//                var_dump($data);
//                die();

                $this->model->update_data($input['article_code'], $data);
                unset($data['article_name']);
                unset($data['style']);
                unset($data['updby']);
                unset($data['upddt']);
                $data['article_code'] = $input['article_code'];
                $data['effdate'] = $this->formatDate("Y-m-d",$input['effdate']);
                $data['tipe'] = $tipe;

                $this->db->insert("article_hpp",$data);
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
    function get_product_size_colour($art, $size, $colour){
        try {
            $art = urldecode($art);
            $size = urldecode($size);
            $colour = urldecode($colour);

            $read = $this->model->get_product_size_colour($art, $size, $colour);
            if ($read->num_rows() > 0) {
                $result = 0;
                $msg="OK";
                $data = $read->result();
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
                $data = null;
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array("data" => $data));
    }
    function delete_data($code){
        try {
            $code = urldecode($code);
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

    function delete_data_size(){
        try {
            $input = $this->toUpper($this->input->post());
            $code = urldecode($input['id']);
//            $read = $this->model->read_data($code);
//            if ($read->num_rows() > 0) {
//
//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data_size($code);
                    $result = 0;
                    $msg="OK";
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

    function delete_data_colour(){
        try {
            $input = $this->toUpper($this->input->post());
            $code = urldecode($input['id']);
//            $read = $this->model->read_data_colour_id($code);
//            if ($read->num_rows() > 0) {
//
//                $read = $this->model->read_transactions_colour($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data_colour($code);
                    $result = 0;
                    $msg="OK";
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


    function export_data(){
        $filename = 'ARTICLE_' . date('Ymd') . '.csv';
        $header = array("Article Code", "Article Name","Style","BOM","FOH","Ongkos Jahit", "Operation Cost","Interest Cost","Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['gambar', 'buffer_cost','ekspedisi','hpp1','hpp2','hpp_ekspedisi'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }
    function export_data_size($code){
        $code = urldecode($code);
        $filename = 'ARTICLE_SIZE_' . date('Ymd') . '.csv';
        $header = array("ID", "Size Code","Size Name","Create By","Update By", "Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->load_grid_size2($app);
        $unset = ['article_code'];
        $top = array("Article : ", $code." - ".$this->model->read_data($code)->row()->article_name);
        $this->export_csv($filename,$header, $data, $unset, $top);
    }
    function export_data_colour($code){
        $code = urldecode($code);
        $filename = 'ARTICLE_COLOUR_' . date('Ymd') . '.csv';
        $header = array("ID", "Colour Code","Colour Name","Create By","Update By", "Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->load_grid_colour2($app);
        $unset = ['article_code'];
        $top = array("Article : ", $code." - ".$this->model->read_data($code)->row()->article_name);
        $this->export_csv($filename,$header, $data, $unset, $top);
    }

}
