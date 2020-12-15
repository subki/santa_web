<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Mastersupplier extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Mastersupplier_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Supplier';
        $data['content']    = $this->load->view('vSupplier',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
//        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
//        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
//        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'supplier_code';
//        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
//        $role = $this->session->userdata('role');
//        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";
//
//        $app="";
//        if($fltr!=""){
//            foreach ($fltr as $r){
//                if($app==""){
//                    $app .= " where ".$r->field." like '%".$r->value."%'";
//                }else{
//                    $app .= " AND ".$r->field." like '%".$r->value."%'";
//                }
//            }
//        }
//        $data = $this->model->get_list_data($page,$rows,$sort,$order,$role, $app);
			$f = $this->getParamGrid("","supplier_code");
			$data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function get_regency($code){
        echo json_encode(array("data"=>$this->model->get_regency($code)->result()));
    }
    function get_provinsi(){
        echo json_encode(array("data"=>$this->model->get_provinsi()->result()));
    }
    function save_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $code = $this->model->generate_auto_number($input['supplier_name']);
            $data = array(
                'supplier_code' => $code,
                'tipe_supplier' => $input['tipe_supplier'],
                'supplier_name' => $input['supplier_name'],
                'contact_person' => $input['contact_person'],
                'phone' => $input['phone'],
                'allow_return' => $input['allow_return'],
                'address' => $input['address'],
                'provinsi_id' => $input['provinsi_id'],
                'regency_id' => $input['regency_id'],
                'zip' => $input['zip'],
                'fax' => $input['fax'],
                'email_address' => $input['email_address'],
                'status' => $input['status'],
//                'currency' => $input['currency'],
//                'lead_day' => $input['lead_day'],
                'top_day' => $input['top_day'],
                'pkp' => $input['pkp'],
                'npwp' => $input['npwp'],
                'nama_pkp' => $input['nama_pkp'],
                'alamat_pkp' => $input['alamat_pkp'],
                'bank_name' => $input['bank_name'],
                'bank_account' => $input['bank_account'],
                'gl_account' => $input['gl_account'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            $this->model->insert_data($data);
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

    function edit_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['supplier_code']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'tipe_supplier' => $input['tipe_supplier'],
                    'supplier_name' => $input['supplier_name'],
                    'contact_person' => $input['contact_person'],
                    'phone' => $input['phone'],
                    'allow_return' => $input['allow_return'],
                    'address' => $input['address'],
                    'provinsi_id' => $input['provinsi_id'],
                    'regency_id' => $input['regency_id'],
                    'zip' => $input['zip'],
                    'fax' => $input['fax'],
                    'email_address' => $input['email_address'],
                    'status' => $input['status'],
//                    'currency' => $input['currency'],
//                    'lead_day' => $input['lead_day'],
                    'top_day' => $input['top_day'],
                    'pkp' => $input['pkp'],
                    'npwp' => $input['npwp'],
                    'nama_pkp' => $input['nama_pkp'],
                    'alamat_pkp' => $input['alamat_pkp'],
                    'bank_name' => $input['bank_name'],
                    'bank_account' => $input['bank_account'],
                    'gl_account' => $input['gl_account'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data($input['supplier_code'], $data);
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

    function delete_data($code){
        try {
            $read = $this->model->read_data($code);
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



    function get_products($code){
        $f = $this->getParamGrid(" supplier_code='$code' ","product_code");
        $data = $this->model->get_products($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function get_sku($code){
        $special = " product_code not in(select product_code from purchase where supplier_code='$code') ";
        $f = $this->getParamGrid($special,"product_code");
        $data = $this->model->get_sku($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

    function save_data_product($supplier_code){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'supplier_code' => $supplier_code,
                'product_code' => $input['product_code'],
                'main_supplier' => 'YES',
                'uom_code' => $input['uom_code'],
                'unit_price' => $input['unit_price'],
                'std_price' => $input['std_price'],
                'mu_persen' => $input['mu_persen'],
                'gp_persen' => $input['gp_persen'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            $this->model->insert_data_product($data);
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

    function edit_data_product(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_product($input['supplier_code'], $input['product_code']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'unit_price' => $input['unit_price'],
                    'std_price' => $input['std_price'],
                    'mu_persen' => $input['mu_persen'],
                    'gp_persen' => $input['gp_persen'],
                );

                $this->model->update_data_product($input['supplier_code'], $input['product_code'], $data);
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

    function delete_data_product(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->read_data_product2($input['id']);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                $this->model->delete_data_product($input['id']);
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

    function get_contact($code){
        $page = ($this->input->post('page')) ? $this->input->post('page'):1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows'):20;
        $sort = ($this->input->post('sort')) ? $this->input->post('sort'):'id';
        $order= ($this->input->post('order')) ? $this->input->post('order'):'asc';
        $role = $this->session->userdata('role');
        $fltr= ($this->input->post('filterRules')) ? json_decode($this->input->post('filterRules')):"";

        $code = urldecode($code);
        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND supplier_code = '".$code."' ";
            else $app .= " where supplier_code = '".$code."' ";
        }else{
            $app .= " where supplier_code = '".$code."' ";
        }
        $data = $this->model->get_contact($page,$rows,$sort,$order,$role, $app);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data_contact($supplier_code){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'supplier_code' => $supplier_code,
                'contact' => $input['contact'],
                'no_telp' => $input['no_telp'],
                'dept' => $input['dept'],
                'keterangan' => $input['keterangan'],
            );

            $this->model->insert_data_contact($data);
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

    function edit_data_contact(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_contact($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'contact' => $input['contact'],
                    'no_telp' => $input['no_telp'],
                    'dept' => $input['dept'],
                    'keterangan' => $input['keterangan'],
                );

                $this->model->update_data_contact($input['id'], $data);
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

    function delete_data_contact(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->read_data_contact($input['id']);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                $this->model->delete_data_contact($input['id']);
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
    function export_data(){
        $filename = 'SUPPLIER_' . date('Ymd') . '.csv';
        $header = array("Kode", "Tipe","Nama Supplier","Allow Return","Contact Person",
            "Phone","Alamat","Provinsi","Kota/Kab","ZIP","Fax",
            "Email","Status","TOP","PKP","NPWP","Nama PKP",
            "Alamat PKP","Nama Bank", "No Rekening","GL Account",
            "Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['provinsi_id','regency_id',
            'currency','lead_day'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }
}
