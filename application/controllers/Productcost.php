<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Productcost extends IO_Controller {
    function __construct(){

        parent::__construct();
        $this->load->model('Productcost_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function get_customer_type(){
        echo json_encode(array("data"=>$this->model->get_customer_type()->result()));
    }

    function load_grid($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'product_id';
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
            if(count($fltr)>0) $app .= " AND product_id = '".$code."' ";
            else $app .= " where product_id = '".$code."' ";
        }else{
            $app .= " where product_id = '".$code."' ";
        }
//        $f = $this->getParamGrid("","article_code");
//        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        $data = $this->model->get_list_data($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function save_data($sku){
        try {
            $input = $this->toUpper($this->input->post());
            $cek = $this->model->get_product($sku);
            if($cek->num_rows()>0) {
                $r = $cek->row();
                $cek2 = $this->model->check_insert($sku, $input['purchase_market'], $this->formatDate('Y-m-d',$input['periode']));
                if($cek2->num_rows()>0){
                    $result = 1;
                    $msg = "Tidak boleh menginput additional cost pada Purchases Market dan Effecive date yang sama.";
                }else {
                    $data = array(
                        'product_id' => $sku,
                        'periode' => $this->formatDate('Y-m-d', $input['periode']),
                        'purchase_market' => $input['purchase_market'],
                        'hpp' => $input['hpp'],
                        'cost1' => $input['cost1'],
                        'cost2' => isset($input['cost2']) ? $input['cost2'] : 0,
                        'cost3' => isset($input['cost3']) ? $input['cost3'] : 0,
                        'hpp_end' => isset($input['hpp_end']) ? $input['hpp_end'] : 0,
                    );
                    $this->model->insert_data($data);

                    $result = 0;
                    $msg = "OK";
                }
            }else{
                $result = 1;
                $msg = "Produk tidak ditemukan";
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

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {

                $data = array(
                    'periode' => $this->formatDate('Y-m-d', $input['periode']),
                    'hpp' => $input['hpp'],
                    'purchase_market' => $input['purchase_market'],
                    'cost1' => $input['cost1'],
                    'cost2' => $input['cost2'],
                    'cost3' => $input['cost3'],
                    'hpp_end' => $input['hpp_end'],
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

    function delete_data(){
        try {
            $input = $this->toUpper($this->input->post());
            $code = $input['id'];
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
