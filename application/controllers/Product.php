<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Product_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

//    function index(){
//        $data['title']      = 'Master Product';
//        $data['content']    = $this->load->view('vProduct',$data,TRUE);
//
//        $this->load->view('main',$data);
//    }
    function get_colour($code){
        $art = urldecode($code);
        echo json_encode(array("data"=>$this->model->get_colour($art)->result()));
    }

    function load_grid($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'nobar';
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
            if(count($fltr)>0) $app .= " AND product_id = '".$code."' ";
            else $app .= " where product_id = '".$code."' ";
        }else{
            $app .= " where product_id = '".$code."' ";
        }
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

                $no = $this->model->generate_auto_number($r->sku);
                if($no->num_rows()>0){
                    $nomor = $no->row()->nomor;
                }else $nomor = $r->sku."01";
                $data = array(
                    'product_id' => $sku,
                    'nobar' => $nomor,
                    'nmbar' => $input['nmbar'],
                    'warna' => $input['warna'],
                    'soh' => $input['soh'],
                    'min_stock' => $input['min_stock'],
                    'max_stock' => $input['max_stock'],
                    'user_crt' => $this->session->userdata('user_id'),
                    'date_crt' => date('Y-m-d'),
                    'time_crt' => date('H:i:s'),
                );
                $this->model->insert_data($data);
                $this->model->update_header($sku);

                $data2 = array(
                    'article_code' => $r->article_code,
                    'art_colour_id' => $input['warna'],
                    'art_size_id' => $r->size_code,
                    'product_id' => $sku,
                    'nobar' => $nomor,
                    'user_crt' => $this->session->userdata('user_id'),
                    'date_crt' => date('Y-m-d'),
                    'time_crt' => date('H:i:s'),
                );
                $this->model->insert_data_article_size_colour($data2);

                $result = 0;
                $msg = "OK";
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
            $result = 1;
            $msg="Tidak boleh mengedit, hanya bisa hapus (jika belum ada transaksi)";
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
