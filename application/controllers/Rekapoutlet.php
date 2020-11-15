<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekapoutlet extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Rekapoutlet_model','model');
        $this->load->model('Salesonline_model','modelonline');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Rekapoutlet';
        $data['content'] = $this->load->view('vRekapoutlet', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
        if($aksi=="add"){
            $data['title'] = 'Add Rekapoutlet';
            $data['content'] = $this->load->view('vRekapoutlet_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Rekapoutlet';
            $data['docno'] = $this->input->get('id');
            $data['content'] = $this->load->view('vRekapoutlet_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

    function load_grid(){
        $f = $this->getParamGrid("","doc_date");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

    function save_data_header(){
        $docno=0;
        try {
            $input = $this->toUpper($this->input->post());
            // var_dump($input);
            // die();
            $pl = $this->modelonline->read_data($input['base_so']);
  
           //if($pl->num_rows()>0){
                 $data = array(
                        'doc_date' => $this->formatDate("Y-m-d", $input['doc_date']),
                        'faktur_date' => $this->formatDate("Y-m-d", $input['faktur_date']),
                        'no_faktur' => $input['no_faktur'],
                        'no_faktur2' => $input['no_faktur2'],
                        'seri_pajak' => $input['seri_pajak'],
                        'jenis_faktur' => $input['jenis_faktur'],
                        'remark' => $input['remark'],
                        'customer_code' => $input['customer_code'],
                      //  'base_so' => $input['base_so'],
                        'gross_sales' =>0,// $pl->row()->gross_sales,
                        'total_ppn' =>0,// $pl->row()->total_ppn,
                        'total_disc' => 0,//$pl->row()->total_discount,
                        'sales_before_tax' =>0,// $pl->row()->sales_before_tax,
                        'sales_after_tax' =>0,// $pl->row()->sales_after_tax,
                        'total_dp' => $input['total_dp'],
                        'sisa_faktur' => $input['sisa_faktur'],
                        'total_hpp' => $input['total_hpp'],
                        'status' => $input['status'],
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s'),
                    );
        
                    if($input['pkp']=="NO"){
                        $nomor = $this->model->generate_auto_number($input['store_code']);
                        if($nomor==""){
                            $result = 1;
                            $msg = "Gagal generate Nomor Rekapoutlet Non PKP, cek sales toko";
                        }else{
                            $data['no_faktur'] = $nomor;
                            $data['no_faktur2'] = $nomor;
                            $docno = $this->model->insert_data($data);
                            $result = 0;
                            $msg = "OK";
                        }
                    }else{
                        if($input['beda_fp']=="YES"){

                            //cek ivs
                            $nomor_ivs = $this->model->generate_auto_number_ivs();
                            $data['no_faktur'] = $nomor_ivs;
                            $docno = $this->model->insert_data($data);
                            $result = 0;
                            $msg = "OK";
                        }else{
                             $nomor_sg = $this->model->generate_auto_number_sg();           
                             // var_dump($nomor_sg);
                             // var_dump($input['no_faktur']);
                             // die();
                            if($input['no_faktur']!="") {
//                                $ivs = $this->model->cek_nofaktur2($input['no_faktur2']);
//                                if ($ivs->num_rows() == 0) {
//                                    if(count($data['no_faktur2'])==13) {
                                        $data['no_faktur'] = $nomor_sg;
                                        $data['no_faktur2'] = $data['no_faktur'] ;
                                        $docno = $this->model->insert_data($data);
                                        $result = 0;
                                        $msg = "OK";
//                                    }else{
//                                        $result = 1;
//                                        $msg = "Nomor Faktur tidak valid. harus 13 digit";
//                                    }
//                                } else {
//                                    $result = 1;
//                                    $msg = "Nomor Faktur sudah digunakan, tidak bisa digunakan lagi.";
//                                }
                            }else {
                                $result = 1;
                                $msg = "Nomor Faktur harus diisi.";
                            }
                        }
                    } 
          //  }else{
         //    $result = 1;
         //      $msg = "Base SO Number tidak ditemukan";
        // }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "id"=>$docno
        ));
    }

    function edit_data_header(){
        $input = $this->toUpper($this->input->post());
        try {

            $read = $this->model->read_data($input['id']);
            // var_dump($read->num_rows());
            if ($read->num_rows() > 0) {
                $data = array(
                    'faktur_date' => $this->formatDate("Y-m-d", $input['faktur_date']),
                    'no_faktur' => $input['no_faktur'],
                    'no_faktur2' => $input['no_faktur2'],
                    'seri_pajak' => $input['seri_pajak'],
                    'jenis_faktur' => $input['jenis_faktur'],
                    'remark' => $input['remark'],
                    'total_dp' => $input['total_dp'],
                    'sisa_faktur' => $input['sisa_faktur'],
                    'total_hpp' => $input['total_hpp'],
                    'status' => $input['status'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
                if($input['status']=="POSTING"){
                    $data['posting_date'] = date('Y-m-d');
                }

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
            "msg" => $msg, "message" => $msg, "id"=>$input['id']
        ));
    }

    function get_seripajak(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model_faktur->read_available_faktur(date('Y'));
            if ($read->num_rows() > 0) {
                $dt =$read->row();
                $data = array(
                    'seri_pajak' => '010'.$dt->seqno,
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
                $data2 = array(
                    'inuse' => 1,
                    'refno' => $input['docno'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
                $this->model_faktur->update_data($dt->id, $data2);
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
            "msg" => $msg, "message" => $msg, "docno"=>$input['docno']
        ));
    }

    function update_finance_verify(){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'verifikasi_finance' => 'VERIFIED',
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
            "msg" => $msg, "message" => $msg, "docno"=>$input['id']
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

    function read_data_by_so($code){
        try {
            $read = $this->model->read_data_by_so($code);
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

    function print_ws(){
        $input = $this->input->get();
        $read = $this->model->read_data($input['id']);
        $data=array();
        if ($read->num_rows() > 0) {
            $r = $read->row();
            $data['header']=$r;
            $f = $this->getParamGrid(" a.sales_trans_header_id='".$input['id']."' ","id");
            $data['detail'] = $this->model->get_list_data_detail(1,99999999,$f['sort'],$f['order'],$f['role'], $f['app']);
        }

        if($input['pkp']=="YES") {
            if ($input['tipe'] == 1) {
                $view = "print/PKP_WS_INVOICE";
            } else if ($input['tipe'] == 2) {
                $view = "print/PKP_WS_SURJAL";
            }
        }else{
            if($input['tipe']==1){
                $view = "print/NON_WS_INVOICE";
            }else if($input['tipe']==2){
                $view = "print/NON_WS_SURJAL";
            }
        }

//        $this->load->view($view,$data);
        $this->load->library('pdf');
        $this->pdf->load_view($view, $data);
        $this->pdf->render();
        //set page numbers
        $x          = 540;
        $y          = 760;
        $text       = "{PAGE_NUM} of {PAGE_COUNT}";
        $font       = $this->pdf->getFontMetrics('Courier', 'normal');
        $size       = 10;
        $color      = array(0,0,0);
        $word_space = 0.0;
        $char_space = 0.0;
        $angle      = 0.0;
        $this->pdf->getCanvas()->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        $this->pdf->stream($input['nomor'].'.pdf',array("Attachment"=>0));
    }



    function load_grid_detail($docno){
        $f = $this->getParamGrid(" a.sales_trans_header_id='$docno' ","id");
        $data = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

    function save_data_detail($docno){
        try {
            $input = $this->toUpper($this->input->post());
            $read = $this->model->cek_detail($docno, $input['seqno']);
            if($read->num_rows()>0){
                $result = 1;
                $msg = "Product sudah diinput!!";
            }else {
                $data = array(
                    'so_number' => $input['so_number'],
                    'docno' => $docno,
                    'seqno' => $input['seqno'],
                    'nobar' => $input['nobar'],
                    'qty_order' => $input['qty_order'],
                    'qty_pl' => $input['qty_pl'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );

                $this->model->insert_data_detail($docno,$data);
                $result = 0;
                $msg = "OK";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "docno"=>$docno
        ));
    }

    function edit_data_detail(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data_detailID($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'qty_on_sales' => $input['qty_on_sales'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->model->update_data_detail($input['docno'],$input['id'], $data);
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
            "msg" => $msg, "message" => $msg, "docno"=>$input['docno']
        ));
    }

    function read_data_detail($code){
        try {
            $read = $this->model->read_data_detailID($code);
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

    function delete_data_detail($code){
        try {
            $read = $this->model->read_data_detailID($code);
            if ($read->num_rows() > 0) {

//                $read = $this->model->read_transactions_detail($code);
//                if ($read->num_rows() > 0) {
//                    $result = 1;
//                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                }else{
                    $this->model->delete_data_detail($read->row()->docno, $code);
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

    function get_product($so_number){
        $special = " a.docno='$so_number' ";
        $f = $this->getParamGrid($special,"nobar");
        $data = $this->model->get_product($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function getDate(){
        $input = $this->toUpper($this->input->post());
        $from=$this->formatDate("Y-m-d", $input['from']);
        $to=$this->formatDate("Y-m-d", $input['to']); 
        $customer_code=$input['customer_code']; 
        // var_dump($customer_code);
        // die();
        try {
            $read = $this->model->read_datadaily($from,$to,$customer_code); 
            if ($read->num_rows() > 0) {
                $result = 0;
                $msg="OK";
                $data = $read->result();
            } else {
                $result = 1;
                $msg="Data tidak ditemukan";
                $data = null;
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "total" => $read->num_rows(),
            "data" => $data
        ));
    }

    function postdaily(){
        $input = $this->toUpper($this->input->post());
        $from=$this->formatDate("Y-m-d", $input['from']);
        $to=$this->formatDate("Y-m-d", $input['to']); 
        $customer_code=$input['customer_code'];  
        $docno=$input['docno']; 
        // var_dump($customer_code);
        // die();
        try { 
            $read = $this->model->read_datadailypost($from,$to,$customer_code,$docno); 
                $result = 0;
                $msg = "OK";
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg,
            "data" => ''
        ));
    }
    function export_data(){
        $filename = 'EFAKTUR_' . date('Ymd') . '.csv';
        $data = array();
        //default
        array_push($data, array("FK", "KD_JENIS_TRANSAKSI", "FG_PENGGANTI", "NOMOR_FAKTUR", "MASA_PAJAK", "TAHUN_PAJAK", "TANGGAL_FAKTUR", "NPWP", "NAMA", "ALAMAT_LENGKAP", "JUMLAH_DPP", "JUMLAH_PPN", "JUMLAH_PPNBM", "ID_KETERANGAN_TAMBAHAN", "FG_UANG_MUKA", "UANG_MUKA_DPP", "UANG_MUKA_PPN", "UANG_MUKA_PPNBM"));
        array_push($data, array("LT", "NPWP", "NAMA", "JALAN", "BLOK", "NOMOR", "RT", "RW", "KECAMATAN", "KELURAHAN", "KABUPATEN", "PROPINSI", "KODE_POS", "NOMOR_TELEPON"));
        array_push($data, array("OF", "KODE_OBJEK", "NAMA", "HARGA_SATUAN", "JUMLAH_BARANG", "HARGA_TOTAL", "DISKON", "DPP", "PPN", "TARIF_PPNBM", "PPNBM"));

        $app = $this->getParamOption();
        $headers = $this->model->get_list_data(1, 999999999999, "id", "asc","", $app);
        foreach ($headers as $h){
            $docno = $h->id;
            $r = $this->model->read_data($docno)->result()[0];
            $p = $this->model_packing->read_data($r->base_so)->result()[0];
            $nomor = substr($r->no_faktur2,0,3).".".substr($r->no_faktur2,3,4).".".substr($r->no_faktur2,7,2).".".substr($r->no_faktur2,-4);
            $sesi = $this->session->userdata();
            $f = $this->getParamGrid(" a.sales_trans_header_id='$docno' ","id");
            $detail = $this->model->get_list_data_detail($f['page'],1000000,$f['sort'],$f['order'],$f['role'], $f['app']);
            //header
            array_push($data, array("FK","01","0",substr($r->seri_pajak,-13),"7",$this->formatDate("Y",$r->doc_date),$r->ak_doc_date2,$r->npwp,$r->nama_pkp,$r->alamat_pkp,$p->gross_sales,$p->total_ppn,"0","0","0","0","0","0","PENJUALAN ATAS INVOICE NO ".$nomor));
            array_push($data, array("FAPR",$sesi['nama pkp'], $sesi['alamat pkp'], $sesi['pemegang bagian'], $sesi['kota pkp']));
            //detail
            foreach ($detail as $row){
                array_push($data, array("OF",$row->product_code,$row->product_name,$row->unit_price,$row->qty_on_sales,$row->unit_price*$row->qty_on_sales,$row->disc_total,$row->bruto_before_tax,$row->total_tax,"0","0"));
            }
        }
        $this->export_csv_faktur($filename,$data);
    }

}
