<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterproduct extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Masterproduct_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Master Product';
        $data['content']    = $this->load->view('vProduct',$data,TRUE);

        $this->load->view('main',$data);
    }
    function sub($index){
        $tit = "";
        if($index==1) $tit = "Barang Jadi";
        else if($index==2) $tit = "Bahan Baku";
        else if($index==3) $tit = "Accessories";
        else if($index==4) $tit = "Packing";
        else if($index==5) $tit = "Sparepart";
        else if($index==6) $tit = "ATK";

        $data['title']      = $tit;
//        $data['title']      = 'Master Product - '.$tit;
        $data['index']      = $index;
        $data['kelompok']      = $tit;
        $data['content']    = $this->load->view('vProduct',$data,TRUE);

        $this->load->view('main',$data);
    }
    function get_uom(){
        echo json_encode(array("data"=>$this->model->get_uom()->result()));
    }
    function get_size($code){
        $art = urldecode($code);
        echo json_encode(array("data"=>$this->model->get_size($art)->result()));
    }
    function get_colour(){
        echo json_encode(array("data"=>$this->model->get_colour()->result()));
    }
    function get_supplier(){
        echo json_encode(array("data"=>$this->model->get_supplier()->result()));
    }
    function get_customer_type(){
        echo json_encode(array("data"=>$this->model->get_customer_type()->result()));
    }
    function get_subclass($code){
        echo json_encode(array("data"=>$this->model->get_subclass($code)->result()));
    }
    function get_class(){
        echo json_encode(array("data"=>$this->model->get_class()->result()));
    }
    function get_brand(){
        echo json_encode(array("data"=>$this->model->get_brand()->result()));
    }
    function get_article(){
        $f = $this->getParamGrid(" con_brg = '' ","article_code");
        $data = $this->model->get_article($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

    function load_grid($kelompok=""){
        $kelompok = urldecode($kelompok);
        $f = $this->getParamGrid("","sku");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'], 0, $kelompok);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }


    function stock_sku($code){
        $f = $this->getParamGrid("","nobar");
        $data = $this->model->stock_sku($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'], $code, date('Ym'));

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function stock_location_d($product_id,$location_code){
        $f = $this->getParamGrid("","nobar");
        $data = $this->model->stock_location_d($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'], $product_id, $location_code, date('Ym'));

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function stock_location($code){
        $f = $this->getParamGrid("","location_code");
        $data = $this->model->stock_location($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'], $code, date('Ym'));

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

//            $article = $this->model->cek_article($input['article_code']);
//            if($article->num_rows()>0){
//                $nomor = $article->row()->sku;//.$article->row()->seq;
//            }else{
                $ss = $this->model->generate_auto_number();
                $nomor = $ss;//."01";
//            }

            $data = array(
                'sku' => $nomor,
                'product_code' => $input['article_code']." ".$input['size_code'],
                'article_code' => $input['article_code'],
                'product_name' => $input['product_name'],
                'brand_code' => $input['brand_code'],
                'class_code' => $input['class_code'],
                'subclass_code' => $input['subclass_code'],
//                'type_barang' => $input['type_barang'],
                'supplier_code' => $input['supplier_code'],
                'size_code' => $input['size_code'],
                'colour_code' => $input['colour_code'],
                'jenis_barang' => $input['jenis_barang'],
                'satuan_beli' => $input['satuan_beli'],
                'satuan_stock' => $input['satuan_stock'],
                'satuan_jual' => $input['satuan_jual'],
                'status_product' => $input['status_product'],
                'total_soh' => 0,
                'min_stock' => 0,
                'max_stock' => 0,
                'first_production' => date('Y-m-d'),
//                'last_production' => $newformat2,
//                'avg_cost' => $input['avg_cost'],
//                'price_h1' => $input['price_h1'],
//                'purchase_market' => $input['purchase_market'],
//                'sales_market' => $input['sales_market'],
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );
            $insertId = $this->model->insert_data($data);
            $subinsert = $this->save_data_sub($insertId,$nomor);
//            $no1=1;
//            foreach ($size as $r1){
////                $no1 = str_pad($no1,2,"0",STR_PAD_LEFT);
//                $no2=1;
//                foreach ($colour as $r2){
////                    $no2 = str_pad($no2,2,"0",STR_PAD_LEFT);
//
//
//                    $data2 = array(
//                        'article_code' => $input['article_code'],
//                        'art_colour_id' => $r2->id,
//                        'art_size_id' => $r1->id,
//                        'sku' => $sku,
//                        'user_crt' => $this->session->userdata('user_id'),
//                        'date_crt' => date('Y-m-d'),
//                        'time_crt' => date('H:i:s'),
//                    );
//                    $this->model->insert_data_article_size_colour($data2);
//
//                    $no2++;
//                }
//
//                $no1++;
//            }

            $result = $subinsert['result'];
            $msg = $subinsert['msg'];
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
                "status" => $result, "isError" => ($result == 1),
                "msg" => $msg, "message" => $msg
            ));
    }

	
    function save_data_sub($product_id,$skuu){
        try {
            $input = $this->toUpper($this->input->post());
            $data = array(
                'product_id' => $product_id,
                'nobar' => $skuu,
                'nmbar' => $input['product_name'],
                'warna' => $input['colour_code'],
                'soh' => 0,//$input['soh'],
                'min_stock' => 0,//$input['min_stock'],
                'max_stock' => 0,//$input['max_stock'],
                'user_crt' => $this->session->userdata('user_id'),
                'date_crt' => date('Y-m-d'),
                'time_crt' => date('H:i:s'),
            );
            $this->model_subproduct->insert_data($data);
            $this->model_subproduct->update_header($product_id);

            $data2 = array(
                'article_code' => $input['article_code'],
                'art_colour_id' => $input['colour_code'],
                'art_size_id' => $input['size_code'],
                'product_id' => $product_id,
                'nobar' => $skuu,
                'user_crt' => $this->session->userdata('user_id'),
                'date_crt' => date('Y-m-d'),
                'time_crt' => date('H:i:s'),
            );
            $this->model_subproduct->insert_data_article_size_colour($data2);

            $result = 0;
            $msg = "OK";
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        return array("msg" => $msg, "result" => $result);
    }


    function edit_data(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'product_name' => $input['product_name'],
                    'brand_code' => $input['brand_code'],
                    'class_code' => $input['class_code'],
                    'subclass_code' => $input['subclass_code'],
//                    'type_barang' => $input['type_barang'],
                    'supplier_code' => $input['supplier_code'],
                    'colour_code' => $input['colour_code'],
                    'jenis_barang' => $input['jenis_barang'],
                    'satuan_beli' => $input['satuan_beli'],
                    'status_product' => $input['status_product'],
//                    'first_production' => $input['first_production'],
//                    'last_production' => $input['last_production'],
//                    'avg_cost' => $input['avg_cost'],
//                    'price_h1' => $input['price_h1'],
//                    'purchase_market' => $input['purchase_market'],
//                    'sales_market' => $input['sales_market'],
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
    function read_mutasi($nobar, $location){
        $f = $this->getParamGrid("","periode");
        $data = $this->model->read_mutasi($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'], $nobar, $location);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function read_mutasi_trx($periode, $location,$nobar){
        $f = $this->getParamGrid("","tanggal");
        $data = $this->model->read_mutasi_trx($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'], $periode, $location,$nobar);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
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


    function export_data(){
        $filename = 'PRODUCT_' . date('Ymd') . '.csv';
        $header = array("Jenis Barang", "ID", "SKU","Kode Produk","Nama Produk", "Kode Article","Merk","Ukuran","Grup","Subgrup","Supplier","UOM Jual","UOM Beli","UOM Stok","SOH","Status","Purchases Market","Create By","Update By","Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = ['brand_code', 'class_code', 'subclass_code'
                    , 'type_barang', 'supplier_code', 'size_code'
                    , 'satuan_beli', 'satuan_stock', 'satuan_jual'
                    , 'min_stock', 'max_stock', 'first_production'
                    , 'last_production', 'avg_cost', 'price_h1', 'sales_market'
                    , 'tanggal_crt', 'tanggal_upd'
                    , 'convertion', 'id','gambar'];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }


}
