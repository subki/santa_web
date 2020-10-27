<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockopname extends CI_Controller {

    function __construct(){

        parent::__construct();
        header('Content-Type: application/json');
        $this->load->model('api/Stockopname_model','model');
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function list_stockopname(){
        $offset = $this->input->post('offset')*20;
        $outlet = $this->input->post('outlet');
        $search = $this->input->post('search');
        $xx = $this->model->get_stockopname($outlet, $offset, $search);
        $stt = 0;
        $msg="OK";
        $data = $xx->result();
        echo json_encode(array(
                "status" => $stt,
                "msg" => $msg, "message" => $msg
                "data"=>$data
            )
        );
    }

    function get_stockopname($docno){
        try{
            $cek = $this->model->get_stockopname_id($docno);
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
                "msg" => $msg, "message" => $msg
                "data"=>$data
            )
        );
    }

    function list_stockopname_detail($docno){
        try{
            $cek = $this->model->get_stockopname_id($docno);
            if($cek->num_rows()>0){
                $stt=0;
                $msg="OK";
                $data = $this->model->get_stockopname_detail($docno)->result();
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
                "msg" => $msg, "message" => $msg
                "data"=>$data
            )
        );
    }

    function create_stockopname(){
        $outlet = $this->input->post('outlet');
        $user = $this->input->post('user_id');

        $docno = $this->model->generate_auto_number();
        $data = array(
            'docno' => $docno,
            'outlet_code' => $outlet,
            'doc_date' => date('Y-m-d'),
            'user_id' => $user,
            'status' => 'Open'
        );

        $this->model->insert_header($data);

        echo json_encode(array(
                "status" => 0,
                "msg" => "OK",
                "data" => $docno
            )
        );
    }

    function save_detail(){
        try{
            $docno = $this->input->post('docno');
            $outlet = $this->input->post('outlet');
            $sku = $this->input->post('sku');
            $qty = $this->input->post('qty');
            $id = $this->session->userdata('id');

            $cek = $this->model->get_stockopname_id($docno);
            if($cek->num_rows()>0){
                $cek_stock = $this->model->get_stock_by_sku($outlet, $sku,date('Ym'));
                if($cek_stock->num_rows()==0){
                    $stok = array(
                        "sku"=>$sku,
                        "outlet_code"=>$outlet,
                        "periode"=>date('Ym'),
                        "saldo_awal"=>0,
                        "do_masuk"=>0,
                        "do_keluar"=>0,
                        "penyesuaian"=>0,
                        "pengembalian"=>0,
                        "saldo_akhir"=>0,
                        "unit_price"=>0
                    );
                    $this->model->insert_stock($stok);
                }
                $data = array(
                    'docno' => $docno,
                    'outlet_code'=>$outlet,
                    'sku' => $sku,
                    'qty' => $qty,
                    'user_id'=>$id
                );
                $this->model->insert_detail($data);
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

    function update_detail(){
        try {
            $id = $this->input->post('id');
            $qty = $this->input->post('qty')?$this->input->post('qty'):0;
            $note = $this->input->post('note')?$this->input->post('note'):'';

            $cek = $this->model->cek_detail_id($id);
            if ($cek->num_rows() > 0) {
                $data = array(
                    "qty" => $qty,
                    "note" => $note
                );
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

    function delete_detail($id){
        try{
            $cek = $this->model->get_stockopname_detail_id($id);
            if($cek->num_rows()>0){
                $this->model->delete_detail($id);
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
    function delete_pelaksana($id){
        try{
            $this->model->delete_detail_pelaksana($id);
            $stt=0;
            $msg="OK";
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

    function update_pelaksana(){
        try{
            $docno = $this->input->post('docno');
            $user_id = $this->input->post('user_id');

            $data = array(
                'docno' => $docno,
                'user_id'=>$user_id
            );
            $this->model->insert_detail_pelaksana($data);
            $stt=0;
            $msg="OK";
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


    function list_note(){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):100;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'id';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');

        $data = $this->model->get_list_data_note($page,$rows,$sort,$order,$role);

        echo json_encode(array(
                "status" => 0,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }


}
