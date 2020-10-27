<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends CI_Controller {

    function __construct(){

        parent::__construct();
        header('Content-Type: application/json');
        $this->load->model('api/Delivery_model','model');
        $this->load->model('Transfer_model','transfer');
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function list_DO(){
        $offset = $this->input->post('offset')*20;
        $outlet = $this->input->post('outlet');
        $search = $this->input->post('search');
        $tipe = $this->input->post('tipe');
        $xx = $this->model->get_DO($outlet, $offset, $search, $tipe);
        $stt = 0;
        $msg="OK";
        $data = $xx->result();
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data
            )
        );
    }

    function get_detail_do($docno){
        $cek = $this->model->get_header_do($docno);
        if($cek->num_rows()>0){
            $stt=0;
            $msg="OK";
            $data = $this->model->get_detail_do($docno)->result();
        }else{
            $stt=1;
            $msg="Data tidak ditemukan";
            $data = null;
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data
            )
        );
    }

    function receive_do_detail(){
        $this->input->raw_input_stream;
        $input_data = json_decode($this->input->raw_input_stream, true);

        $docno = $input_data['docno'];
        $trx_no = $input_data['trx_no'];
        $sku = $input_data['sku'];
        $qty_rcv = $input_data['qty_rcv'];
        $outlet = $input_data['outlet'];

//        var_dump($docno);
//        die();
//        $unit_price = 0;

        $periode = date('Ym');

        $cek = $this->model->get_detail_do_sku($docno,$sku);
        if($cek->num_rows()>0){
//            $unit_price = $cek->row()->unit_price;
            if($cek->row()->qty_rcv==$qty_rcv){
                $stt = 0;
                $msg = "DO Detail updated";
                $data = null;
            }else {
                $cek_stok = $this->model->cek_stock($outlet, $sku, $periode);
                if ($cek_stok->num_rows() > 0) {
//                    $this->model->update_stok_sku($sku, $outlet, $periode, $qty_rcv);
                    $this->model->update_detail_do($docno, $trx_no, $sku, $qty_rcv);
//                    yg ini $this->model->update_stok_sku($sku, $outlet, $periode, $cek->row()->qty);
//                    $this->model->update_detail_do($docno, $trx_no, $sku, $cek->row()->qty);
                    $stt = 0;
                    $msg = "DO Detail updated";
                    $data = null;
                } else {
                    $cek_stok_before = $this->model->cek_stock_before($outlet, $sku);
                    if ($cek_stok_before->num_rows() > 0) {
                        $a = $cek_stok_before->row();
                        $stok = array(
                            'sku' => $a->sku,
                            'outlet_code' => $a->outlet_code,
                            'periode' => $periode,
                            'saldo_awal' => $a->saldo_akhir,
                            'do_masuk' => 0,
                            'do_keluar' => 0,
                            'penyesuaian' => 0,
                            'pengembalian' => 0,
                            'unit_price' => $cek->row()->unit_price
                        );
                    } else {
                        $stok = array(
                            'sku' => $sku,
                            'outlet_code' => $outlet,
                            'periode' => $periode,
                            'saldo_awal' => 0,
                            'do_masuk' => 0,
                            'do_keluar' => 0,
                            'penyesuaian' => 0,
                            'pengembalian' => 0,
                            'unit_price' => $cek->row()->unit_price
                        );
                    }
                    $this->model->create_new_stock($stok);
//                    $this->model->update_stok_sku($sku, $outlet, $periode, $qty_rcv);
                    $this->model->update_detail_do($docno, $trx_no, $sku, $qty_rcv);
//                    yg ini $this->model->update_stok_sku($sku, $outlet, $periode, $cek->row()->qty);
//                    $this->model->update_detail_do($docno, $trx_no, $sku, $cek->row()->qty);
                    $stt = 0;
                    $msg = "DO Detail updated";
                    $data = null;
                }
            }
        }else{
            $stt=1;
            $msg="DO Detail tidak ditemukan";
            $data = null;
            //insert new row
            /*$cek_stok = $this->model->cek_stock($outlet, $sku, $periode);
            if($cek_stok->num_rows()>0) {
                $this->model->update_stok_sku($sku, $outlet, $periode, $qty_rcv);
                $stt = 0;
                $msg = "DO Detail updated";
                $data = null;
            }else{
                $cek_stok_before = $this->model->cek_stock_before($outlet, $sku);
                if($cek_stok_before->num_rows()>0){
                    $a = $cek_stok_before->row();
                    $stok = array(
                        'sku'=> $a->sku,
                        'outlet_code' => $a->outlet_code,
                        'periode' => $periode,
                        'saldo_awal' => $a->saldo_akhir,
                        'do_masuk' =>0,
                        'do_keluar'=>0,
                        'penyesuaian'=>0,
                        'pengembalian'=>0,
                        'unit_price' => $cek_stok_before->row()->unit_price
                    );
                }else{
                    $stok = array(
                        'sku'=> $sku,
                        'outlet_code' => $outlet,
                        'periode' => $periode,
                        'saldo_awal' => 0,
                        'do_masuk' =>0,
                        'do_keluar'=>0,
                        'penyesuaian'=>0,
                        'pengembalian'=>0,
                        'unit_price' => $cek_stok_before->row()->unit_price
                    );
                }
                $this->model->create_new_stock($stok);
                $this->model->update_stok_sku($sku, $outlet, $periode, $qty_rcv);
            }
            $product = $this->model->get_product($sku);
            if($product->num_rows()>0){
                $p = $product->row();
                $ar = array(
                    "docno"=>$docno,
                    "trx_no"=>$trx_no,
                    "brand_code"=>$p->brand,
                    "sku"=>$sku,
                    "article_code"=>$p->article_code,
                    "uom"=>$p->uom_jual,
                    "qty"=>0,
                    "qty_rcv"=>$qty_rcv,
                    "unit_price"=>0
                );
                $this->model->insert_detail_do($ar);
                $stt=0;
                $msg="DO Detail added";
                $data = null;
            }else{
                $stt=1;
                $msg="SKU tidak ditemukan";
                $data = null;
            }*/
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data
            )
        );
    }

    function insert_do_header(){
        try {
            $input = $this->toUpper($this->input->post());

            $docno = $this->transfer->generate_auto_number($input['do_type']);
            $data = array(
                'docno' => $docno,
                'trx_no' => $input['trx_no'],
                'trx_date' => $input['trx_date'],
                'outlet_id' => $input['outlet_id'],
                'outlet_src' => $input['outlet_src'],
                'do_type' => $input['do_type'],
                'status' => $input['status'],
            );
            $this->transfer->insert_data_header($data);
            $result = 0;
        }catch (Exception $e){
            $result = 1;
            $docno=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $docno,
        ));
    }

    function insert_do_detail(){
        try {
            $input = $this->toUpper($this->input->post());

//            $sku = explode('|||',$input['sku'])[0];
//            $article = explode('|||',$input['sku'])[1];

            $read = $this->transfer->read_data_detail($input['docno'], $input['sku']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'qty' => $input['qty'],
                );
                $this->transfer->update_data_detail($input['docno'], $input['sku'], $data);

                $result = 0;
                $msg="No SKU sudah di input";
            } else {
                $data = array(
                    'docno' => $input['docno'],
                    'trx_no' => $input['trx_no'],
                    'brand_code' => $input['brand_code'],
                    'sku' => $input['sku'],
                    'article_code' => $input['article_code'],
                    'uom' => $input['uom'],
                    'qty' => $input['qty'],
                    'unit_price' => $input['unit_price'],
                );
                $this->transfer->insert_data_detail($data);
                $result = 0;
                $msg="OK";
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

    function edit_do_header(){
        try {
            $docno = $this->input->post('docno');
            $trx_no = $this->input->post('trx_no');
            $trx_date = $this->input->post('trx_date');
            $outlet_id = $this->input->post('outlet_id');
            $outlet_src = $this->input->post('outlet_src');
            $do_type = $this->input->post('do_type');
            $status = $this->input->post('status');
            $read = $this->transfer->read_data_docno($docno);
            if ($read->num_rows() > 0) {
                $aa = $read->result()[0];

                $data = array();
                if($trx_no != null) $data["trx_no"]=$trx_no;
                if($trx_date != null) $data["trx_date"]=$trx_date;
                if($outlet_id != null) $data["outlet_id"]=$outlet_id;
                if($outlet_src != null) $data["outlet_src"]=$outlet_src;
                if($do_type != null) $data["do_type"]=$do_type;
                if($status != null) $data["status"]=$status;

//                var_dump($data);
//                die();

                $this->transfer->update_data_header($docno, $data);
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

    function delete_do_header($docno){
        try {
            $read = $this->transfer->read_data_docno($docno);
            if ($read->num_rows() > 0) {
                if($read->result()[0]->status=="Open") {
                    $this->model->delete_do_header($docno);
                    $result = 0;
                    $msg = "OK";
                }else{
                    $result = 1;
                    $msg="DO tidak bisa dihapus";
                }
            }else{
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

    function delete_do_detail($docno, $id){
        try {
            $read = $this->transfer->read_data_docno($docno);
            if ($read->num_rows() > 0) {
                if($read->result()[0]->status=="Open") {
                    $this->model->delete_do_detail($docno,$id);
                    $result = 0;
                    $msg = "OK";
                }else{
                    $result = 1;
                    $msg="Detail tidak bisa dihapus";
                }
            }else{
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
