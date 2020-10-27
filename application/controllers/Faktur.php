<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Faktur extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Faktur_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Master Faktur';
        $data['content']    = $this->load->view('vFaktur',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $f = $this->getParamGrid("","seqno");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
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

            $cnt = $input['total'];
            $thn = date('Y');
            $data = array();
            for($i=1;$i<$cnt+1; $i++){
                $aa = substr($input['awal'],-5);
                $end = intval($aa)+$i;

                $end1 = substr("000".$end,-5);
                $comb = substr($input['awal'],0,count($input['awal'])-6).$end1;

//                var_dump($aa);
//                var_dump($end);
//                var_dump($end1);
//                var_dump($comb);
//                die();
                if($this->model->read_data($thn,$comb)->num_rows() == 0) {
                    $this->model->insert_data(array(
                        "periode" => $thn,
                        "seqno" => $comb
                    ));
                    $conn = $this->db->conn_id;
                    do {
                        if ($result = mysqli_store_result($conn)) {
                            mysqli_free_result($result);
                        }
                    } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                }
            }
//            var_dump($data);
//            die();
//            if(count($data)>0) {
//                $this->model->insert_batch($data);
                $result = 0;
                $msg = "OK";
//            }else{
//                $result = 1;
//                $msg = "Tidak ada data yang disimpan";
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

    function delete_data($code){
        try {
            $read = $this->model->read_data2($code);
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


    function export_data(){
        $filename = 'SIZE_' . date('Ymd') . '.csv';
        $header = array("Kode", "Ukuran","Status","Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['tanggal_crt','tanggal_upd'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }
}
