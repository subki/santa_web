<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');

class Salesonline extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Salesonline_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Daily Sales Online';
        $data['content'] = $this->load->view('vSalesonline', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
        if($aksi=="add"){
            $data['title'] = 'Add Daily Sales Online';
            $data['content'] = $this->load->view('vSalesonline_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Daily Sales Online';
            $data['docno'] = $this->input->get('docno');
            $data['content'] = $this->load->view('vSalesonline_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

     function load_grid($status, $prd){ 
        $d= substr($prd,6); 
        $y= substr($prd, 0, 4);
        $m= substr($prd, 4, 2);
        $tgl = $y."-".$m."-".$d;  
        // var_dump($prd)  ;
        if($status=='ALL' AND $prd=='ALL1'){   
            $special = [];
           // $f = $this->getParamGrid("","doc_date");
        }
        else{
            if($status=='ALL'){  
                $special = ["doc_date ="=>$tgl];
              //  $f = $this->getParamGrid(" doc_date='$tgl' ","doc_date");
            }
            else{ 
                $special = ["doc_date ="=>$tgl,"status"=>$status];
                //$f = $this->getParamGrid(" status='$status' and doc_date='$tgl' ","doc_date");
            }
        }
        $total1 = $this->getParamGrid_BuilderComplete(array(
            "table"=>"sales_online_header so ",
            "sortir"=>"doc_date",
            "special"=>$special,
            "select"=>" DATE_FORMAT(p.tgl_pickup, '%d/%b/%Y') tgl_pickup,so.docno,so.remark
                      , a.sales_date, DATE_FORMAT(a.sales_date, '%d/%m/%Y') ak_doc_date
                      , DATE_FORMAT(so.doc_date, '%d/%b/%Y') tgl_so, DATE_FORMAT(so.doc_date, '%d/%m/%Y') ak_tgl_so
                      , a.so_number,so.so_no, so.status, c.address1, c.phone1, c.pkp, c.beda_fp
                      , so.customer customer_code, c.customer_name, so.qty_item, so.qty, so.sales
                      , so.disc1_persen, so.disc2_persen , so.doc_date  
                      , so.gross_sales, so.total_discount, so.sales_before_tax, so.total_ppn, so.sales_after_tax
                      , IFNULL(u1.fullname,a.crtby) AS crtby, IFNULL(u2.fullname, a.updby) AS updby
                      , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                      , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt ",
            "join"=>[
                "sales_online_detail a "=>"so.docno=a.so_number",
                "pickup_d d"=>"so.docno=d.barcode",
                "pickup_h p"=>"p.id=d.pickup_h_id",
                "customer c "=>"so.customer=c.customer_code",
                "users u1"=>" a.crtby=u1.user_id",
                "users u2 "=>"a.updby=u2.user_id",
            ]
        ));

        $total = $total1->total;
        $data = $total1->data;
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>$total,
                "data" =>$data)
        );

    }
    function load_gridlist(){
        $f = $this->getParamGrid("","status");
        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function save_data_header(){
        try {
            $input = $this->toUpper($this->input->post());
            $docno = $this->model->generate_auto_number($input['so_number']);
            if($docno==""){
                $result = 1;
                $msg = "Kode store tidak dikenali";
            }else {
                $data = array(
                    'docno' => $docno,
                    'doc_date' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'so_number' => $input['so_number'],
                    'remark' => $input['remark'],
                    'qty_item' => $input['qty_item'],
                    'qty' => $input['qty'],
                    'status' => $input['status'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );

                $this->model->insert_data($data);
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

    function cekrekapdaily(){
        $input = $this->toUpper($this->input->post());
        $tgl=$this->formatDate("Y-m-d",$input['tgl']);
        try {
            $read = $this->model->read_datarekap($tgl);
            if ($read->num_rows() > 0) {
                $result = 1;
                $msg="Rekap Daily sudah terbentuk di Tanggal ".$read->row()->doc_date;
                $data = $read->result()[0];
            } else {
                $result = 0;
                $msg="OK";
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
    function edit_data_header(){
        try {
            $input = $this->toUpper($this->input->post()); 
            $read = $this->model->read_data($input['docno']);
             
            if ($read->num_rows() > 0) {
                $rd = $read->row();
                $data = array(
                    'remark' => $input['remark'],
                    'status' => $input['status'],
                    'docno' => $input['docno'],
                    'so_number' => $input['so_no'],
                    'qty_item' => $input['qty_item'],
                    'qty' => $input['qty'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                if($input['status']=="POSTING"){
                    $data['posting_date'] = date('Y-m-d');
                }

                if($input['reason'] != ""){
                    $this->insert_log("daily_sales_online", $input['docno'], $input['reason']);
                }


                // if($rd->so_number!=$input['so_number']){
                //     $data['docno']=$input['docno'];
                //     $data['so_number']=$input['so_number'];
                //     $total = $this->model->copySOtoPL($data);
                //     if($total>0){
                //         $data['so_number']=$rd->so_number;
                //     }
                // }
                $this->model->update_data($input['docno'], $data);

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

    function print_pl($docno){
        $read = $this->model->read_data($docno);
        $data=array();
        if ($read->num_rows() > 0) {
            $r = $read->row();
            $data['header']=$r;
            $f = $this->getParamGrid(" a.docno='$docno' ","seqno");
            $data['detail'] = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        }
        $this->load->library('pdf');
        $this->pdf->load_view('print/SLS_PACKING', $data);
        $this->pdf->render();

        $this->pdf->stream($docno.'.pdf',array("Attachment"=>0));
//        $this->load->view('print/salesorder',$data);

    }

    function load_grid_detail($docno){
        $total1 = $this->getParamGrid_BuilderComplete(array(
                "table"=>"sales_online_detail a ",
                "sortir"=>"a.docno,a.nobar",
                "special"=>"a.docno= '$docno'",
                "select"=>"a.id, a.docno, a.seqno, a.type, a.nobar, SUM(a.qty_order) qty_order, 
                    SUM(a.unitprice)unit_price, (SUM(a.unitprice))+ SUM(a.total_tax)  pricetax , 
                    SUM(a.disc1_persen) disc1_persen,SUM( a.disc2_persen)disc2_persen, 
                    SUM(a.disc1_amount) disc1_amount,SUM(a.disc2_amount) disc2_amount , 
                    SUM(a.disc_total) disc_total,SUM(a.bruto_before_tax) bruto_before_tax,
                    (SUM(a.unitprice)+ SUM(a.total_tax))/1.1  total_tax, SUM(a.net_unit_price) net_unit_price, 
                    SUM(a.net_after_tax) net_total_price , a.status_detail , b.nmbar, c.satuan_jual, 
                    d.description AS uom_jual, c.product_code, d.uom_id , c.product_name, b.product_id , 
                    (SELECT IFNULL(SUM(pl.qty_pl),0) 
                        FROM packing_detail pl 
                        INNER JOIN packing_header ph ON ph.docno=pl.docno 
                            WHERE pl.so_number=a.docno AND pl.seqno=a.seqno AND ph.status IN('POSTING','CLOSED')) AS qty_pl , 
                        COALESCE(a.updby, a.crtby) last_user , COALESCE(a.upddt, a.crtdt) last_time ",
                "join"=>[
                    "product_barang b"=>" a.nobar=b.nobar",
                    "product c"=>" b.product_id=c.id",
                    "product_uom d"=>" c.satuan_jual=d.uom_code",
                ],
                "posisi"=>["INNER","INNER","INNER"]
            ));
            $total = $total1->total;
            $data = $total1->data;
//        $f = $this->getParamGrid("","doc_date");
//        $data = $this->model->get_list_data($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>$total,
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
                    'qty' => $input['qty'],
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
            $read = $this->model->read_data_detailID($input['docno']); 
            if ($read->num_rows() > 0) { 
                $bf = $read->row();
                
                $data = array(
                    'disc1_persen' => $input['disc1_persen'],
                    'disc2_persen' => $input['disc2_persen'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );  
                if($bf->disc1_persen != $data['disc1_persen']){
                    $this->model->update_data_detail_disc($input['docno'],$input['id'], $data['disc1_persen'], 1, $data['updby'], $data['upddt']);
                } 
                if($bf->disc2_persen != $data['disc2_persen']){
                    $this->model->update_data_detail_disc($input['docno'],$input['id'], $data['disc2_persen'],2, $data['updby'], $data['upddt']);
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

                $read = $this->model->read_transactions_detail($code);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
                }else{
                    $this->model->delete_data($read->row()->docno, $code);
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


}
