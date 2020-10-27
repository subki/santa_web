<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Multiprice extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Multiprice_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function get_customer_type(){
        echo json_encode(array("data"=>$this->model->get_customer_type()->result()));
    }

    function load_grid($code){
        $f = $this->getParamGrid(" product_id = '".$code."' ","tanggalan");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
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

//                $time = strtotime($input['eff_date']);
//                $newformat = date('Y-m-d',$time);
                $cek2 = $this->model->check_insert($sku, $input['description'], $this->formatDate('Y-m-d',$input['eff_date']));
                if($cek2->num_rows()>0){
                    $result = 1;
                    $msg = "Tidak boleh menginput multiprice pada Tipe Customer dan Effecive date yang sama.";
                }else {
                    $data = array(
                        'product_id' => $sku,
                        'customer_type' => $input['description'],
                        'eff_date' => $this->formatDate('Y-m-d', $input['eff_date']),
                        'price_non_pkp' => $input['price_non_pkp'],
                        'price_tax' => $input['price_tax'],
                        'price_pkp' => $input['price_pkp'],
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
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
    function duplikat(){
        try {
            $ids = $this->input->get("ids");
            $idx = explode(",",$ids);
            foreach ($idx as $id) {
                $read = $this->model->read_data($id);
                if ($read->num_rows() > 0) {
                    $prc = $read->row();
                    $read2 = $this->model->read_product('id', $prc->product_id);
                    if($read2->num_rows()>0) {
                        $read3 = $this->model->read_product('article_code', $read2->row()->article_code);
                        $dt = $read3->result();
                        $msg = "Multiprice berhasil di duplikat : ";
                        foreach ($dt as $row){
                            if($prc->product_id==$row->id) continue;
                            $msg .= "<br>".$row->product_code;
                            $data = array(
                                'product_id' => $row->id,
                                'customer_type' => $prc->customer_type,
                                'eff_date' => $prc->eff_date,
                                'price_non_pkp' => $prc->price_non_pkp,
                                'price_tax' => $prc->price_tax,
                                'price_pkp' => $prc->price_pkp,
                                'crtby' => $this->session->userdata('user_id'),
                                'crtdt' => date('Y-m-d H:i:s')
                            );
                            $this->model->insert_data($data);
                        }

                        $result = 0;
                    }else{
                        $result = 1;
                        $msg="Kode Product tidak ditemukan";
                    }
                }else {
                    $result = 1;
                    $msg="Kode tidak ditemukan";
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

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {
                $time = strtotime($input['eff_date']);
                $newformat = date('Y-m-d',$time);

                $data = array(
                    'customer_type' => $input['description'],
                    'eff_date' => $newformat,
                    'price_non_pkp' => $input['price_non_pkp'],
                    'price_tax' => $input['price_tax'],
                    'price_pkp' => $input['price_pkp'],
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
