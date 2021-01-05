<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Wholesalesapp extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Wholesales_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Wholesales';
        $data['content'] = $this->load->view('vWholesalesapp', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
        if($aksi=="add"){
            $data['title'] = 'Add Wholesales';
            $data['content'] = $this->load->view('vWholesales_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Wholesales';
            $data['docno'] = $this->input->get('id');
            $data['content'] = $this->load->view('vWholesales_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

    function load_grid(){
    	$max = $this->session->userdata('maksimal transaksi');
    	if($max==null || $max=="") $this->set_error("Maksimum transaksi belum di setting");
			$total1 = $this->getParamGrid_BuilderComplete(array(
				"table"=>"sales_trans_header a",
				"sortir"=>"doc_date",
				"special"=>["a.status"=>"OPEN","a.sales_after_tax>(c.credit_limit-c.outstanding) OR (a.sales_after_tax > $max AND a.app_creditby != '')"],
				"select"=>"a.id, a.no_faktur,a.no_faktur2, a.seri_pajak
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%b/%Y') ak_doc_date, a.jenis_faktur
                  , DATE_FORMAT(a.doc_date, '%d/%m/%Y') ak_doc_date2
                  , a.faktur_date, DATE_FORMAT(a.faktur_date, '%d/%m/%Y') ak_faktur_date
                  , IFNULL(a.verifikasi_finance,'') verifikasi_finance, c.top_day
                  , so.doc_date tgl_so, DATE_FORMAT(so.doc_date, '%d/%m/%Y') ak_tgl_so
                  , a.base_so, a.remark, a.status, a.qty_print, c.pkp, c.beda_fp, c.npwp, c.nama_pkp, c.alamat_pkp
                  , so.customer_code, c.customer_name, so.store_code, so.location_code, c.payment_first
                  , so.salesman_id, sl.salesman_name, c.address1, c.address2, r.name as regency_name
                  , store.store_name, l.description as location_name, c.phone1
                  , c.credit_limit, c.outstanding, (c.credit_limit-c.outstanding) credit_remain
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , coalesce(ifnull(u3.fullname,a.app_creditby),'') as creditby
                  , coalesce(ifnull(u4.fullname, a.app_maxslsby),'') as maxslsby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , coalesce(wp.keterangan,'') as ket
                  , coalesce(wp.id,0) id_confirm
                  , a.sales_after_tax
                  , a.status as statushd
                  , ifnull(si.id,0) sales_invoice_id
                  , ifnull(spi.docno,'') as proforma_no",
				"join"=>[
					"wholesales_payment wp"=>"wp.sales_trans_header_id=a.id",
					"sales_invoice si"=>" si.id=a.id",
					"sales_proforma spi"=>"spi.docno=si.sales_proforma_id",
					"packing_header ph"=>"ph.docno = a.base_so",
					"sales_order_header so"=>"so.docno = ph.so_number",
					"salesman sl"=>"sl.salesman_id=so.salesman_id",
					"customer c"=>"so.customer_code=c.customer_code",
					"regencies r"=>"r.id = c.regency_id",
					"profile_p store"=>"store.store_code=so.store_code",
					"location l"=>"l.location_code=so.location_code",
					"users u1"=>"a.crtby=u1.user_id",
					"users u2"=>"a.updby=u2.user_id",
					"users u3"=>"a.app_creditby=u3.user_id",
					"users u4"=>"a.app_maxslsby=u3.user_id",
					],
				"posisi"=>["left","left","left","left","left","left","inner","left","left","left","left","left","left","left"]
			));
			$total = $total1->total;
			$data = $total1->data;
//        $f = $this->getParamGrid("status='OPEN' AND sales_after_tax>credit_remain","doc_date");
//        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>$total,
                "data" =>$data)
        );
    }

    function save_data_header(){
        $docno=0;
        try {
            $input = $this->toUpper($this->input->post());

            $pl = $this->model_packing->read_data($input['base_so']);
            if($pl->num_rows()>0){
                $so = $this->model_sales->read_data($pl->row()->so_number);
                if($so->num_rows()>0){
                    $data = array(
                        'doc_date' => $this->formatDate("Y-m-d", $input['doc_date']),
                        'faktur_date' => $this->formatDate("Y-m-d", $input['faktur_date']),
                        'no_faktur' => $input['no_faktur'],
                        'no_faktur2' => $input['no_faktur2'],
                        'seri_pajak' => $input['seri_pajak'],
                        'jenis_faktur' => $input['jenis_faktur'],
                        'remark' => $input['remark'],
                        'customer_code' => $input['customer_code'],
                        'base_so' => $input['base_so'],
                        'gross_sales' => $so->row()->gross_sales,
                        'total_ppn' => $so->row()->total_ppn,
                        'total_disc' => $so->row()->total_discount,
                        'sales_before_tax' => $so->row()->sales_before_tax,
                        'sales_after_tax' => $so->row()->sales_after_tax,
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
                            $msg = "Gagal generate Nomor wholesales Non PKP, cek sales toko";
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
                            if($input['no_faktur']!="") {
//                                $ivs = $this->model->cek_nofaktur2($input['no_faktur2']);
//                                if ($ivs->num_rows() == 0) {
//                                    if(count($data['no_faktur2'])==13) {
                                        $data['no_faktur'] = $data['no_faktur2'];
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

                }else{
                    $result = 1;
                    $msg = "Base SO Number tidak ditemukan";
                }
            }else{
                $result = 1;
                $msg = "Base Packing Number tidak ditemukan";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "id"=>$docno
        ));
    }

    function edit_data_header_credit(){
        $input = $this->toUpper($this->input->post());
        try {

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {
                $dt = $read->row();
                $data = array(
                    'status' => $input['status'],
                    'app_creditby' => $this->session->userdata('user_id'),
                    'app_creditdt' => date('Y-m-d H:i:s')
                );
                $result = 0;
                $msg="OK";
                if($input['status']=="CLOSED"){
                    //cek customer yg perlu bayar dimuka atau bukan? or jika iya sudah di confirm finance atau blm
                    $data['posting_date'] = date('Y-m-d');
                    if($dt->payment_first=="No" || $dt->id_confirm > 0 ) {
                        $this->model->update_data($input['id'], $data);
                    }else{
                        $result = 1;
                        $msg = "Customer harus melakukan pembayaran terlebih dahulu, dan perlu di konfirmasi oleh finance.";
                    }
                }else{
                    $this->model->update_data($input['id'], $data);
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
            "msg" => $msg, "message" => $msg, "id"=>$input['id']
        ));
    }
    function edit_data_header_maximum(){
        $input = $this->toUpper($this->input->post());
        try {

            $read = $this->model->read_data($input['id']);
            if ($read->num_rows() > 0) {
                $dt = $read->row();
                $data = array(
                    'status' => $input['status'],
                    'app_maxslsby' => $this->session->userdata('user_id'),
                    'app_maxslsdt' => date('Y-m-d H:i:s')
                );
                $result = 0;
                $msg="OK";
                if($input['status']=="CLOSED"){
                    //cek customer yg perlu bayar dimuka atau bukan? or jika iya sudah di confirm finance atau blm
                    $data['posting_date'] = date('Y-m-d');
                    if($dt->payment_first=="No" || $dt->id_confirm > 0 ) {
                        $this->model->update_data($input['id'], $data);
                    }else{
                        $result = 1;
                        $msg = "Customer harus melakukan pembayaran terlebih dahulu, dan perlu di konfirmasi oleh finance.";
                    }
                }else{
                    $this->model->update_data($input['id'], $data);
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
