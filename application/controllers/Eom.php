<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Eom extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Eom_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'End Of Month';
        $data['content']    = $this->load->view('vEom',$data,TRUE);

        $this->load->view('main',$data);
    }

    function get_location(){
        $store = $this->session->userdata('store_code');
        $store1 = $this->session->userdata('kode store pusat');

        $special = " location_code in(select location_code from cabang where store_code='$store')";
        if($store==$store1){
            $special = "";
        }
        $f = $this->getParamGrid($special,"location_code");
        $data = $this->model->get_location($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data
            )
        );
    }

    function get_nobar($loc1="", $loc2="", $prd=""){
        if($loc1=="" || $loc2=="" || $loc1=="~" || $loc2=="~"){
            $store = $this->session->userdata('store_code');
            $store1 = $this->session->userdata('kode store pusat');
            $special = " a.location_code in(select location_code from cabang where store_code='$store') group by a.nobar";
            if($store==$store1){
                $special=" a.periode='$prd' group by a.nobar ";
            }
        }else{
            if($loc1==$loc2){
                $special = " a.location_code ='$loc1' and a.periode='$prd' group by a.nobar";
            }else $special = " a.location_code >='$loc1' and a.location_code<='$loc2' and a.periode='$prd' group by a.nobar";
        }
        $f = $this->getParamGrid($special,"nobar");
        $data = $this->model->get_nobar($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data
            )
        );
    }

    function execute_eom(){
        try {
            $input = $this->toUpper($this->input->post());
            if($input['from_location_code']=='~') $input['from_location_code'] = 'a';
            if($input['to_location_code']=='~') $input['to_location_code'] = 'ZZZ';
            if($input['from_nobar']=='~') $input['from_nobar'] = '0';
            if($input['to_nobar']=='~') $input['to_nobar'] = '999999999999';
            $store = $this->session->userdata('store_code');
            $p = str_replace("/","",$input['periode']);
            $read_loc = $this->model->get_location_store($store, $input['from_location_code'],$input['to_location_code']);
            $locc=array();
            if($read_loc->num_rows()>0){
                foreach ($read_loc->result() as $row){
                    $read_closing = $this->model->get_location_closing($row->location_code, $p,0);
                    if($read_closing->num_rows()>0){
                        //update closing_location='Close'
                        $this->model->update_closing_location($read_closing->row()->id,array(
                            "status_cl"=>'Close',
                            'updby' => $this->session->userdata('user_id'),
                            'upddt' => date('Y-m-d H:i:s')
                        ));
                        $conn = $this->db->conn_id;
                        do {
                            if ($result = mysqli_store_result($conn)) {
                                mysqli_free_result($result);
                            }
                        } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        //create new closing_loacation="Open" next month
                        $cc = $this->model->get_location_closing($row->location_code, $p, 1);
                        if($cc->num_rows()>0){
                            $this->model->update_closing_location($cc->row()->id,array(
                                "status_cl"=>'Open',
                                'updby' => $this->session->userdata('user_id'),
                                'upddt' => date('Y-m-d H:i:s')
                            ));
                            $conn = $this->db->conn_id;
                            do {
                                if ($result = mysqli_store_result($conn)) {
                                    mysqli_free_result($result);
                                }
                            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        }else {
                            $this->model->insert_closing_location(array(
                                "location" => $row->location_code,
                                "periode" => substr($p, 0, 4) . "-" . str_pad(substr($p, -2) + 1, 2, "0", STR_PAD_LEFT) . "-" . date('d'),
                                "status_cl" => 'Open',
                                'crtby' => $this->session->userdata('user_id'),
                                'crtdt' => date('Y-m-d H:i:s')
                            ));
                            $conn = $this->db->conn_id;
                            do {
                                if ($result = mysqli_store_result($conn)) {
                                    mysqli_free_result($result);
                                }
                            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        }
                    }else{
                        //create closing_location='Close'
                        $this->model->insert_closing_location(array(
                            "location" => $row->location_code,
                            "periode" => substr($p,0,4)."-".str_pad(substr($p,-2),2,"0",STR_PAD_LEFT)."-".date('d'),
                            "status_cl" => 'Close',
                            'crtby' => $this->session->userdata('user_id'),
                            'crtdt' => date('Y-m-d H:i:s')
                        ));
                        $conn = $this->db->conn_id;
                        do {
                            if ($result = mysqli_store_result($conn)) {
                                mysqli_free_result($result);
                            }
                        } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        //create new closing_loacation="Open" next month
                        $cc = $this->model->get_location_closing($row->location_code, $p, 1);
                        if($cc->num_rows()>0){
                            $this->model->update_closing_location($cc->row()->id,array(
                                "status_cl"=>'Open',
                                'updby' => $this->session->userdata('user_id'),
                                'upddt' => date('Y-m-d H:i:s')
                            ));
                            $conn = $this->db->conn_id;
                            do {
                                if ($result = mysqli_store_result($conn)) {
                                    mysqli_free_result($result);
                                }
                            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        }else {
                            $this->model->insert_closing_location(array(
                                "location" => $row->location_code,
                                "periode" => substr($p, 0, 4) . "-" . str_pad(substr($p, -2) + 1, 2, "0", STR_PAD_LEFT) . "-" . date('d'),
                                "status_cl" => 'Open',
                                'crtby' => $this->session->userdata('user_id'),
                                'crtdt' => date('Y-m-d H:i:s')
                            ));
                            $conn = $this->db->conn_id;
                            do {
                                if ($result = mysqli_store_result($conn)) {
                                    mysqli_free_result($result);
                                }
                            } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        }
                    }
                    array_push($locc,$row->location_code);
                }

                //clear data stock di bulan depan, jika ada
                $this->model->delete_stock_next_month(join("','",$locc), $p, $input['from_nobar'], $input['to_nobar']);
                $conn = $this->db->conn_id;
                do {
                    if ($result = mysqli_store_result($conn)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                //create new stock di bulan depan. ending bln sblm nya jadi begin bulan depan
                $this->model->insert_stock_next_month(join("','",$locc), $p, $input['from_nobar'], $input['to_nobar']);
                $conn = $this->db->conn_id;
                do {
                    if ($result = mysqli_store_result($conn)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_more_results($conn) && mysqli_next_result($conn));

                $result = 0;
                $msg = "OK";
            }else{
                $result = 1;
                $msg = "Lokasi tidak ditemukan";
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
