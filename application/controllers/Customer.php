<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Customer_model','model');
        $this->load->model('Location_model','model_location');
        $this->load->model('Customertype_model','model_custtype');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Customer';
        $data['content']    = $this->load->view('vCustomer',$data,TRUE);

        $this->load->view('main',$data);
    }
    function wholesales(){
        $data['title']      = 'Customer Wholesales';
        $data['golongan']      = 'Wholesales';
        $data['content']    = $this->load->view('vCustomer',$data,TRUE);
        $this->load->view('main',$data);
    }

    function counter(){
        $data['title']      = 'Customer Counter';
        $data['golongan']      = 'Counter';
        $data['content']    = $this->load->view('vCustomer',$data,TRUE);
        $this->load->view('main',$data);
    }

    function showroom(){
        $data['title']      = 'Customer Showroom';
        $data['golongan']      = 'Showroom';
        $data['content']    = $this->load->view('vCustomer',$data,TRUE);
        $this->load->view('main',$data);
    }

    function online(){
        $data['title']      = 'Customer Online';
        $data['golongan']      = 'Customer Online';
        $data['content']    = $this->load->view('vCustomer',$data,TRUE);
        $this->load->view('main',$data);
    }

    function load_grid(){
        $gol = $this->input->get('golongan');
        $f = $this->getParamGrid("","customer_code");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],0,$gol);

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
    function get_customer_type(){
        echo json_encode(array("data"=>$this->model->get_customer_type()->result()));
    }
    function get_salesman($prov, $regency){
        echo json_encode(array("data"=>$this->model->get_salesman($prov, $regency)->result()));
    }
    function get_location_stock(){
        echo json_encode(array("data"=>$this->model->get_location_stock()->result()));
    }
    function get_head_customer(){
        echo json_encode(array("data"=>$this->model->get_head_customer()->result()));
    }
    function get_parent_cust(){
        echo json_encode(array("data"=>$this->model->get_parent_cust()->result()));
    }

    function save_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $code = $this->model->generate_auto_number($input['customer_name'],$input['gol_customer']);
            $data_customer = array(
                'customer_code' => $code,
                'customer_name' => $input['customer_name'],
                'address1' => $input['address1'],
                'address2' => $input['address2'],
                'provinsi_id' => $input['provinsi_id'],
                'regency_id' => $input['regency_id'],
                'zip' => $input['zip'],
                'phone1' => $input['phone1'],
                'phone2' => $input['phone2'],
                'phone3' => $input['phone3'],
                'fax' => $input['fax'],
                'contact_person' => $input['contact_person'],
                'salesman_id' => $input['salesman_id'],
                'status' => $input['status'],
                'toc_day' => $input['toc_day'],
                'top_day' => $input['top_day'],
                'pkp' => $input['pkp'],
                'beda_fp' => $input['beda_fp'],
                'npwp' => $input['npwp'],
                'nama_pkp' => $input['nama_pkp'],
                'alamat_pkp' => $input['alamat_pkp'],
                'customer_type' => $input['customer_type'],
                'gol_customer' => $input['gol_customer'],
                'payment_first' => $input['payment_first'],
                'credit_limit' => $input['credit_limit'],
                'outstanding' => $input['outstanding'],
                'info_cust' => $input['info_cust'],
                'head_customer_id' => $input['head_customer_id'],
                'gl_account' => $input['gl_account'],
                'cust_fk' => $input['cust_fk'],
                'parent_cust' => $input['parent_cust'],
                'customer_class' => $input['customer_class'],
                'margin_persen' => $input['margin_persen'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );
            $data_sales = array(
                'customer_code' => $code,
                'salesman_id' => $input['salesman_id'],
                'periode' => date('Y-m-d'),
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            if($input['gol_customer']=="SHOWROOM" || $input['gol_customer']=="COUNTER"){
                $read = $this->model_location->read_data($input['kode_lokasi']);
                if($read->num_rows()>0){
                    $result = 1;
                    $msg = "Kode Lokasi baru sudah ada, kode harus unik.";
                }else{
                    $data_customer['lokasi_stock'] = $input['kode_lokasi'];
                    $data_loc = array(
                        'location_code'=>$input['kode_lokasi'],
                        'description'=>$input['customer_name'],
                        'pkp' => $input['pkp'],
                        'price_type' => $input['customer_type'],
                        'check_stock' => $input['check_stock'],
                        'type' => '',
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
                    );
                    $kdstr = $this->model_profile->generate_auto_number();
                    $data_profile = array(
                        'store_code' => $kdstr,
                        'store_name' => $input['customer_name'],
                        'store_address' => $input['address1'],
                        'provinsi_id' => $input['provinsi_id'],
                        'regency_id' => $input['regency_id'],
                        'zip' => $input['zip'],
                        'phone' => $input['phone1'],
                        'fax' => $input['fax'],
                        'email_address' => "",
                        'default_stock_l' => $input['kode_lokasi'],
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
                    );
                    $data_cbg = array(
                        'store_code' => $kdstr,
                        'location_code' => $input['kode_lokasi'],
                        'kode_cabang' => $kdstr.$input['kode_lokasi'],
                        'nama_cabang' => $input['customer_name'],
                        'prefix_trx' => "",
                        'type' => "Cabang",
                        'flag' => "1",
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
                    );
                    $this->model->insert_data($data_customer);
                    $this->model->insert_data_customer_sales($data_sales);
                    $this->model_location->insert_data($data_loc);
                    if($input['gol_customer']=="SHOWROOM") {
                        $this->model_profile->insert_data($data_profile);
                    }
                    if($input['gol_customer']=="COUNTER"){
                        $data_cbg['store_code'] = $this->session->userdata('kode store outlet');
                    }
                    $this->model_cabang->insert_data($data_cbg);
                    $result = 0;
                    $msg = "OK";
                }
            }else{
                $data_customer['lokasi_stock'] = $input['lokasi_stock'];
                $this->model->insert_data($data_customer);
                $this->model->insert_data_customer_sales($data_sales);
                $result = 0;
                $msg = "OK";
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

            $read = $this->model->read_data($input['customer_code']);
            if ($read->num_rows() > 0) {
                $before = $read->row();
                $data = array(
                    'customer_name' => $input['customer_name'],
                    'address1' => $input['address1'],
                    'address2' => $input['address2'],
                    'provinsi_id' => $input['provinsi_id'],
                    'regency_id' => $input['regency_id'],
                    'zip' => $input['zip'],
                    'phone1' => $input['phone1'],
                    'phone2' => $input['phone2'],
                    'phone3' => $input['phone3'],
                    'fax' => $input['fax'],
                    'contact_person' => $input['contact_person'],
                    'salesman_id' => $input['salesman_id'],
                    'status' => $input['status'],
                    'toc_day' => $input['toc_day'],
                    'top_day' => $input['top_day'],
                    'pkp' => $input['pkp'],
                    'beda_fp' => $input['beda_fp'],
                    'npwp' => $input['npwp'],
                    'nama_pkp' => $input['nama_pkp'],
                    'alamat_pkp' => $input['alamat_pkp'],
                    'customer_type' => $input['customer_type'],
                    'gol_customer' => $input['gol_customer'],
                    'payment_first' => $input['payment_first'],
                    'lokasi_stock' => $input['lokasi_stock'],
                    'credit_limit' => $input['credit_limit'],
                    'outstanding' => $input['outstanding'],
                    'info_cust' => $input['info_cust'],
                    'head_customer_id' => $input['head_customer_id'],
                    'gl_account' => $input['gl_account'],
                    'cust_fk' => $input['cust_fk'],
                    'parent_cust' => $input['parent_cust'],
                    'customer_class' => $input['customer_class'],
                    'margin_persen' => $input['margin_persen'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

//                var_dump($data);
//                var_dump(json_encode($before));
//                var_dump(json_encode($data));
//                    die();
                $this->model->update_data($input['customer_code'], $data);
                $this->insert_log("customer",json_encode($before), json_encode($data));

                if($read->row()->status!=$input['status']){
                    $dt = array(
                        "customer_code"=>$input['customer_code'],
                        "status"=>$input['status'],
                        "info_status"=>$input['info_status'],
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
                    );
                    $this->model->insert_status($dt);
                }

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


    function get_customer_sales($code){
        $f = $this->getParamGrid(" customer_code = '".$code."' ","tanggalan");
        $data = $this->model->get_customer_sales($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data_customer_sales($supplier_code){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'customer_code' => $supplier_code,
                'salesman_id' => $input['salesman_id'],
                'periode' => $this->formatDate('Y-m-d',$input['periode']),
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            $cek = $this->model->check_insert_salesman($data['customer_code'],$data['salesman_id'],$data['periode']);
            if($cek->num_rows()>0){
                $result = 1;
                $msg = "Tidak boleh menginput salesman pada periode yang sama.";
            }else {
                $this->model->insert_data_customer_sales($data);
                $this->model->update_sales_customer($supplier_code);
                $result = 0;
                $msg = "OK";
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

    function edit_data_customer_sales(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_customer_sales($input['id']);
            if ($read->num_rows() > 0) {
                $time = strtotime($input['periode']);
                $newformat = date('Y-m-d',$time);
                $data = array(
                    'salesman_id' => $input['salesman_id'],
                    'periode' => $newformat,
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data_customer_sales($input['id'], $data);
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

    function delete_data_customer_sales(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->read_data_customer_sales($input['id']);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                $this->model->delete_data_customer_sales($input['id']);
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

        $app="";
        if($fltr!=""){
            foreach ($fltr as $r){
                if($app==""){
                    $app .= " where ".$r->field." like '%".$r->value."%'";
                }else{
                    $app .= " AND ".$r->field." like '%".$r->value."%'";
                }
            }
            if(count($fltr)>0) $app .= " AND customer_code = '".$code."' ";
            else $app .= " where customer_code = '".$code."' ";
        }else{
            $app .= " where customer_code = '".$code."' ";
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
                'customer_code' => $supplier_code,
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

    function get_article($code){
        $f = $this->getParamGrid("customer_code='$code'","customer_code");
        $data = $this->model->get_article($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data_article($supplier_code){
        try {
            $input = $this->toUpper($this->input->post());

            $data = array(
                'customer_code' => $supplier_code,
                'article_code' => $input['article_code'],
                'customer_type' => $input['customer_type'],
                'discount' => $input['discount'],
                'level_category' => "",
                'print_barcode' => $input['print_barcode'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );

            $this->model->insert_data_article($data);
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

    function edit_data_article(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_contact($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'article_code' => $input['article_code'],
                    'customer_type' => $input['customer_type'],
                    'discount' => $input['discount'],
                    'level_category' => "",
                    'print_barcode' => $input['print_barcode'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data_article($input['id'], $data);
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

    function delete_data_article(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->read_data_article($input['id']);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                $this->model->delete_data_article($input['id']);
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

    function copy_article(){
        $input = $this->toUpper($this->input->post());
        $from = $input['from'];
        $to = $input['to'];
        $user = $this->session->userdata('user_id');
        $tgl = date('Y-m-d H:i:s');

        $res = $this->model->copy_article($from, $to, $user, $tgl);
        echo json_encode(array(
            "status" => 0, "isError" => false,
            "msg" => "OK", "message" => $res
        ));
    }

    function export_data(){
        $filename = 'CUSTOMER_' . date('Ymd') . '.csv';
        $header = array("Status","Golongan Customer","Customer Type",
            "Customer Class","Kode Customer","Nama",
            "Head Cust","Parent Cust","Alamat1", "Alamat2", "Provinsi","Kota/Kab",
            "ZIP","Fax","Contact Person",
            "Phone1", "Phone2","Phone3", "Salesman",
            "TOP","PKP","NPWP","Nama PKP","Alamat PKP",
            "Lokasi Stok","Kredit Limit","Outstanding",
            "GL Account","Customer Faktur","Keterangan",
            "Create By", "Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['toc_day','payment_first',
            'provinsi_id','regency_id','customer_type',
            'lokasi_stock','head_customer_id','salesman_id','credit_remain','parent_cust','diskon','lokasi_stock_name'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }
}

