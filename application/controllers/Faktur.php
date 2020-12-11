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
            $kiri = substr($input['awal'],0, count($input['awal'])-4);
            $kanan = substr($input['awal'],-4);
            $kanan = intval($kanan);
            $datas = [];
					for($i=0;$i<$cnt; $i++){
						$c = str_pad(strval($kanan),4,"0",STR_PAD_LEFT);
						$datas[] = array(
							'periode'=>date('Y'),
							'seqno'=>$kiri."".$c,
							'inuse'=>0,
							'crtby'=>$this->session->userdata(sess_user_id),
							'crtdt'=>date('Y-m-d h:i:s')
						);
						$kanan++;
					}
//					pre($datas);
					if(count($datas)>0) $this->db->insert_batch("seri_pajak",$datas);

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
        $filename = 'FAKTUR_' . date('Ymd') . '.csv';
        $header = array("Tahun","Seri Faktur","Trx. No","In Use","Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app)->result_array();
        foreach ($data as $key => $row){
        	if($row->inuse=="0") $data[$key]['inuse'] = "NO";
        	else $data[$key]['inuse'] = "YES";
				}
        $field = ["periode","seqno","refno","inuse","crtby", "updby","crtdt","upddt"];
        $top = array();
        $this->export_csv2($filename,$header, $data, $top,$field);
    }
}
