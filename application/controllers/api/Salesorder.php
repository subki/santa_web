<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Salesorder extends CI_Controller {

    function __construct(){

        parent::__construct();
        header('Content-Type: application/json');
        $this->load->model('api/Salesorder_model','model');
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function list_order(){
        $this->input->raw_input_stream;
        $input_data = json_decode($this->input->raw_input_stream, true);

        $offset = $input_data['offset']?$input_data['offset']:$this->input->post('offset');
        $outlet = $input_data['outlet']?$input_data['outlet']:$this->input->post('outlet');
        $search = $input_data['search']?$input_data['search']:$this->input->post('search');
        $status = $input_data['status']?$input_data['status']:$this->input->post('status');
        $offset = $offset*20;

        $xx = $this->model->get_order($outlet, $offset, $search,$status);
        $stt = 0;
        $msg="OK";
        $data = $xx->result();
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data,
                "dump"=>array("offset"=>$offset,"outlet"=>$outlet,"search"=>$search,"status"=>$status)
            )
        );
    }
    function get_order_id($docno){
        try{
            $cek = $this->model->get_order_id($docno);
            if($cek->num_rows()>0){
                $stt=0;
                $msg="OK";
                $data = $cek->row();
            }else{
                $stt=1;
                $msg="Data tidak ditemukan";
                $data = null;
            }
        }catch (Exception $e){
            $stt=1;
            $msg=$e->getMessage();
            $data = null;
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data
            )
        );
    }

    function list_order_detail($docno){
        try{
            $cek = $this->model->get_order_id($docno);
            if($cek->num_rows()>0){
                $stt=0;
                $msg="OK";
                $data = $this->model->get_order_detail($docno)->result();
            }else{
                $stt=1;
                $msg="Data tidak ditemukan";
                $data = null;
            }
        }catch (Exception $e){
            $stt=1;
            $msg=$e->getMessage();
            $data = null;
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data
            )
        );
    }
    function create_order(){
        $outlet = $this->input->post('outlet');
        $user = $this->input->post('user_id');

        $docno = $this->model->generate_auto_number();
        $data = array(
            'docno' => $docno,
            'outlet_code' => $outlet,
            'doc_date' => date('Y-m-d H:i:s'),
            'user_id' => $user,
            'nomor_struk' => '',
            'status' => 'Nota Sementara',
            'total' => 0,
            'discount_id' =>'',
            'grand_total'=>0
        );

        $this->model->insert_header($data);

        echo json_encode(array(
                "status" => 0,
                "msg" => $docno
            )
        );
    }

    function save_detail(){
        try{
            $docno = $this->input->post('docno');
            $outlet = $this->input->post('outlet');
            $sku = $this->input->post('sku');
            $qty = $this->input->post('qty');
            $discount_id = $this->input->post('discount_id');
            $amount = $this->input->post('amount');

//            $discount_id = 0;

            $periode = date('Ym');
//            $dtime = date('Y-m-d H:i:s');

            $msg = "";
            $cek = $this->model->get_order_id($docno);
            if($cek->num_rows()>0) {
                $cek_stok = $this->model->cek_stok($sku, $outlet, $periode);
                if ($cek_stok->num_rows() > 0) {
                    if ($cek_stok->row()->saldo_akhir >= $qty) {
                        $unit_price_asli = $cek_stok->row()->unit_price;

                        $data = array(
                            'docno' => $docno,
                            'outlet_code' => $outlet,
                            'sku' => $sku,
                            'discount_id' => $discount_id,
                            'qty' => $qty,
                            'unit_price' => $unit_price_asli,
                            'qty_paid' => $qty,
                            'unit_price_paid' => $amount,
                            'sub_total' => $qty * $amount,
                            'status' => 'Unpaid'
                        );
                        $this->model->insert_detail($data);
                        $this->model->update_total_belanja($docno);
//                        $this->model->update_stock_jual($periode,$sku, $outlet, $qty);
                        $stt = 0;
                    } else {
                        $stt = 1;
                        $msg = "Qty melebihi stok";
                    }
                } else {
                    $stt = 1;
                    $msg = "Produk tidak terdaftar di outlet " . $outlet;
                }
            }else{
                $stt = 1;
                $msg = "Data tidak ditemukan";
            }
        }catch (Exception $e){
            $stt=1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg
            )
        );
    }

    function change_status_header(){
        try{
            $docno = $this->input->post('docno');
            $struk = $this->input->post('nomor_struk');
            $ket = $this->input->post('keterangan');
            $status = $this->input->post('status');

            $cek = $this->model->get_order_id($docno);
            if($cek->num_rows()>0){

                $this->model->update_header($docno, array('status'=>$status,'nomor_struk'=>$struk,'keterangan'=>$ket));

                $stt=0;
                $msg="OK";
            }else{
                $stt=1;
                $msg="Data tidak ditemukan";
            }
        }catch (Exception $e){
            $stt=1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg
            )
        );
    }

    function change_status_detail(){
        try {
            $id = $this->input->post('id');
            $status = $this->input->post('status');
            $qty = $this->input->post('qty')?$this->input->post('qty'):0;

            $cek = $this->model->cek_detail_id($id);
            if ($cek->num_rows() > 0) {
                $det = $cek->row();
                $data = array();
                $data['status'] = $status;

                //Partial Paid, Paid All, Cancel
                if($status == "Partial Paid" || $status == "Cancel"){
                    $data['qty_paid'] = $qty;
                    $data['sub_total'] = $qty*$det->unit_price_paid;
//                    $periode = date('Ym');
//                    $this->model->update_stock_kembali($periode, $det->sku, $det->outlet_code, $det->qty-$qty);
                }
                $periode = date('Ym');
                $this->model->update_stock_jual($periode,$det->sku, $det->outlet_code, $qty);

                $this->model->update_detail($id, $data);
                $stt = 0;
                $msg = "Detail Updated";
            } else {
                $stt = 1;
                $msg = "Detail tidak ditemukan";
            }
        }catch (Exception $e){
            $stt=1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg
            )
        );
    }

    function get_product_info(){
        $outlet = $this->input->post('outlet');
        $sku = $this->input->post('sku');
        $periode = date('Ym');


        $xx = $this->model->cek_product($outlet, $sku, $periode);
        $stt = 0;
        $msg="OK";
        $data = $xx->row();
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg,
                "data"=>$data
            )
        );

//        $cek_prod = $this->model->cek_product($outlet, $sku,$periode, 'promo');
//        if ($cek_prod->num_rows() > 0) {
//            $stt = 0;
//            $msg = "OK";
//            $data = $cek_prod->row();
//        } else {
//            $cek_prod = $this->model->cek_product($outlet, $sku,$periode, 'normal');
//            if ($cek_prod->num_rows() > 0) {
//                $stt = 0;
//                $msg = "OK";
//                $data = $cek_prod->row();
//            }else {
//                $cek_prod = $this->model->cek_product($outlet, $sku,$periode, '');
//                if ($cek_prod->num_rows() > 0) {
//                    $stt = 0;
//                    $msg = "OK";
//                    $data = $cek_prod->row();
//                }else {
//                    $stt = 1;
//                    $msg = "Produk tidak terdaftar di outlet " . $outlet;
//                    $data = null;
//                }
//            }
//        }
//        echo json_encode(array(
//                "status" => $stt,
//                "msg" => $msg, "message" => $msg
//                "data"=>$data,
//                "param"=>$outlet." ".$sku." ".$periode
//            )
//        );
    }

    function get_list_return(){
//        $this->input->raw_input_stream;
//        $input_data = json_decode($this->input->raw_input_stream, true);

        $offset = $this->input->post('offset')*20;
        $outlet = $this->input->post('outlet');
        $search = $this->input->post('search');
        $status = $this->input->post('status');

        $xx = $this->model->get_list_return($outlet, $offset, $search,$status);
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

    function save_return(){
        $outlet = $this->input->post('outlet');
        $sku = $this->input->post('sku');
        $unit_price = $this->input->post('unit_price');
        $unit_price_paid = $this->input->post('unit_price_paid');
        $qty = $this->input->post('qty');
        $id = $this->input->post('id');
        $nomor_struk = $this->input->post('nomor_struk');
        $nomor_so = $this->input->post('nomor_so');
        $periode = date('Ym');


        $cek_prod = $this->model->cek_product($outlet, $sku,$periode, 'promo');
        if ($cek_prod->num_rows() > 0) {
            $data = array(
                'outlet_code' => $outlet,
                'sku' => $sku,
                'unit_price' =>$unit_price,
                'unit_price_paid' =>$unit_price_paid,
                'qty' => $qty,
                'nomor_struk' => $nomor_struk,
                'nomor_so' => $nomor_so,
                'tanggal' => date('Y-m-d H:i:s'),
                'status' => 'On Proccess'
            );

            if($id!=null && $id>0){
                $this->model->update_return($id, $data);
            }else {
                $this->model->insert_return($data);
            }
            $stt = 0;
            $msg = "Ok";
        } else {
            $cek_prod = $this->model->cek_product($outlet, $sku,$periode, 'normal');
            if ($cek_prod->num_rows() > 0) {
                $data = array(
                    'outlet_code' => $outlet,
                    'sku' => $sku,
                    'unit_price' =>$unit_price,
                    'unit_price_paid' =>$unit_price_paid,
                    'qty' => $qty,
                    'tanggal' => date('Y-m-d H:i:s'),
                    'status' => 'On Proccess'
                );

                if($id!=null && $id>0){
                    $this->model->update_return($id, $data);
                }else {
                    $this->model->insert_return($data);
                }
                $stt = 0;
                $msg = "Ok";
            }else {
                $stt = 1;
                $msg = "Produk tidak terdaftar di outlet " . $outlet;
            }
        }
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg
            )
        );
    }

}
