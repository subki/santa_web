<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Stock_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Stock Monitoring';
        $data['content']    = $this->load->view('vStock',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid($location, $prd){
			$total = $this->getParamGrid_BuilderComplete(array(
				"tipe"=>"total",
				"table"=>"stock a",
				"sortir"=>"nobar",
				"special"=>["a.location_code"=>$location,"a.periode"=>$prd],
				"select"=>"a.id, a.nobar, a.location_code, a.periode, a.saldo_awal
                  , a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir
                  , b.description as location_name, c.nmbar",
				"join"=>["location b"=>"a.location_code=b.location_code","product_barang c"=>"a.nobar=c.nobar"]
			));
			$data = $this->getParamGrid_BuilderComplete(array(
				"tipe"=>"query",
				"table"=>"stock a",
				"sortir"=>"nobar",
				"special"=>["a.location_code"=>$location,"a.periode"=>$prd],
				"select"=>"a.id, a.nobar, a.location_code, a.periode, a.saldo_awal
                  , a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir
                  , b.description as location_name, c.nmbar",
				"join"=>["location b"=>"a.location_code=b.location_code","product_barang c"=>"a.nobar=c.nobar"]
			));

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>$total,
                "data" =>$data)
        );

    }

    function get_location(){
        $store = $this->session->userdata('store_code');
        $special = " location_code in(select location_code from cabang where store_code='$store')";
        $f = $this->getParamGrid($special,"location_code");
        $data = $this->model_delivery->get_location($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data
            )
        );
    }

    function upload_data() {
        $input = $this->toUpper($this->input->post());
        if($this->checkPeriod($input['location_code'], $input['periode'])) {
            $fp = fopen($_FILES['userfile']['tmp_name'],'r') or $json = array(
                "msg" => "Can't open file",
                "status" => 1,
            );
            while($csv_line = fgetcsv($fp,1024)) {
                for($i = 0, $j = count($csv_line); $i < $j; $i++) {
                    $val = explode(";", $csv_line[$i]);
                    if (count($val) > 0) {
                        $insert_csv = array();
                        $read = $this->model->read_data_by_nobar($input['location_code'], $this->formatDate('Ym',$input['periode']), isset($val[0])?$val[0]:'');
                        if ($read->num_rows() == 0) {
                            $insert_csv['nobar'] = isset($val[0])?$val[0]:'';
                            $insert_csv['location_code'] = $input['location_code'];
                            $insert_csv['periode'] = $this->formatDate('Ym',$input['periode']);
                            $insert_csv['saldo_awal'] = isset($val[1])?$val[1]:0;
                            $insert_csv['do_masuk'] = 0;
                            $insert_csv['do_keluar'] = 0;
                            $insert_csv['penyesuaian'] = 0;
                            $insert_csv['pengembalian'] = 0;
                            $insert_csv['penjualan'] = 0;
                            $insert_csv['saldo_akhir'] = isset($val[1])?$val[1]:0;;
                            $this->model->insert_data($insert_csv);
                        }
                        $json = array(
                            "msg" => "Insert data, success",
                            "status" => 0,
                        );
                    }
                }
            }
            if(fclose($fp)){
                $result = 0;
                $msg = "OK";
            } else {
                $result = 1;
                $msg = "Can't close file";
            }
        }else{
            $result = 1;
            $msg = "Transaksi tidak dalam periode berjalan";
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg
        ));
    }
}
