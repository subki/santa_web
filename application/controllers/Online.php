<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Online extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Salesorderonline_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index($aksi=""){
        $data['title'] = 'Sales Order Online';
        $data['datenow'] = date("d/m/Y"); 
        $data['content'] = $this->load->view('vSalesorderonline', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
       $get = $this->toUpper($this->input->post());  
        if($aksi=="add"){
            $data['title'] = 'Add Sales Order Online';
            $docno = $this->model->generate_auto_number();
            $data['title'] = 'Add Sales Order Online';
            $data['docno'] = $docno;
            $data['tgl'] = $get['tglnow'];  
            if($get['cust']){
                $customer_code=$get['cust'];
            }else{ 
                $customer_code=$this->input->get('customer_code');  
            }
            $data['customer_code'] = $customer_code;
            $customer_name=$this->input->get('customer_name');
            $data['customer_name'] = $customer_name;

            $data['content'] = $this->load->view('vSalesorderonline_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Sales Order Online';
            $data['docno'] = $this->input->get('docno');
            $data['content'] = $this->load->view('vSalesorderonline_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

    function load_grid($status, $cust, $prd){  

        $d= substr($prd,6); 
        $y= substr($prd, 0, 4);
        $m= substr($prd, 4, 2);
        $tgl = $y."-".$m."-".$d;   
        $kdcust = $cust; 
        $dtnow=date('Y-m-d');
        // $f = $this->getParamGrid(" CASE WHEN  status != '$status'  THEN doc_date <= '$tgl' ELSE  doc_date = '$tgl' END
        //     AND (status )
        //     AND CASE WHEN '$tgl' != '$dtnow' THEN status='OPEN' ELSE  status in('OPEN','BATAL','CLOSED','ON ORDER') END   
        //                      ","status");
        if($status=='ALL'){   
            if($cust=='~'){
               $special = "a.doc_date <= '$tgl' AND CASE WHEN '$tgl' != '$dtnow' THEN a.status in('OPEN','BATAL','CLOSED','ON ORDER') ELSE a.status='OPEN'  END ";
               // $f = $this->getParamGrid(" doc_date <= '$tgl' AND CASE WHEN '$tgl' != '$dtnow' THEN status in('OPEN','BATAL','CLOSED','ON ORDER') ELSE status='OPEN'  END ","status");
             }
             else{
              $special ="a.doc_date <= '$tgl' AND CASE WHEN '$tgl' != '$dtnow' THEN a.status in('OPEN','BATAL','CLOSED','ON ORDER') ELSE a.status='OPEN'  END  and a.customer_code like '%$cust%' ";
                 //$f = $this->getParamGrid(" doc_date <= '$tgl' AND CASE WHEN '$tgl' != '$dtnow' THEN status in('OPEN','BATAL','CLOSED','ON ORDER') ELSE status='OPEN'  END  and customer_code like '%$cust%' ","status");
             }
        }
        else{
            if($cust=='~'){  
               $special = "a.doc_date <= '$tgl' AND a.status='$status'";
               // $f = $this->getParamGrid(" doc_date <= '$tgl' AND status='$status'","status");
            }
            else{ 
               $special = "a.status='$status' and a.doc_date <='$tgl' and a.customer_code like '%$cust%' ";
               // $f = $this->getParamGrid(" status='$status' and doc_date <='$tgl' and customer_code like '%$cust%' ","status");
            }
        }

        $total1 = $this->getParamGrid_BuilderComplete(array(
            "table"=>"so_online_header a",
            "sortir"=>"a.status",
            "special"=>$special,
            "select"=>"a.docno,a.so_no
                  , CONCAT(LEFT(a.docno,3),'.',RIGHT(LEFT(a.docno,7),4),'.',RIGHT(LEFT(a.docno,9),2),'.',RIGHT(a.docno,4)) AS ak_docno
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%b/%Y') ak_doc_date
                  , a.store_code, b.store_name, a.location_code , a.kode_kirim, a.provinsi_id, c.name as provinsi
                  , a.regency_id, d.name as regency, a.jenis_so
                  , a.remark, a.customer_code, e.customer_name, e.phone1, a.salesman_id, f.salesman_name
                  , e.lokasi_stock, e.customer_type
                  , a.tipe_komisi, a.komisi_persen,IFNULL(a.disc1_persen,0) ,IFNULL(a.disc2_persen ,0)
                  , a.qty_item, a.qty_order, a.gross_sales, a.total_ppn, a.total_discount
                  , a.sales_before_tax, a.sales_after_tax, a.service_level, a.qty_deliver
                  , a.posting_date, DATE_FORMAT(a.posting_date, '%d/%m/%Y') ak_posting_date
                  , a.status, a.sales_pada_toko, e.pkp
                  , ifnull(a.jumlah_print,0) jumlah_print, e.credit_limit, e.outstanding, (e.credit_limit-e.outstanding) credit_remain
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt",
             "join"=>[
                "users u1"=>"a.crtby=u1.user_id",
                "users u2"=>"a.updby=u2.user_id",
                "profile_p b"=>"a.store_code=b.store_code",
                "provinces c "=>"c.id=a.provinsi_id",
                "regencies d"=>" c.id=d.province_id  AND d.id=a.regency_id",
                "customer e"=>"a.customer_code=e.customer_code",
                "salesman f"=>"a.salesman_id=f.salesman_id",
            ],
          "posisi"=>["left","left","left","left","inner","left","left"]
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

    function load_gridcust(){
        $gol = $this->input->get('golongan');
        $f = $this->getParamGrid("","customer_code");
        $data = $this->model->get_list_datacust($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],0,$gol);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data_header(){
       $input = $this->toUpper($this->input->post()); 
            $docno = $this->model->generate_auto_number($input['pkp'],$input['store_code']);
            if($docno==""){
                $result = 1;
                $msg = "Kode store tidak dikenali";
            }else {
                $data = array(
                    'docno' => $docno,
                    'doc_date' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'so_no' => $input['so_no'],
                    'store_code' => $input['store_code'],
                    'location_code' => $input['location_code'],
                    'provinsi_id' => $input['provinsi_id'],
                    'regency_id' => $input['regency_id'],
                    'jenis_so' => $input['jenis_so'],
                    'remark' => $input['remark'],
                    'customer_code' => $input['customer_code'],
                    'salesman_id' => $input['salesman_id'],
                    'tipe_komisi' => $input['tipe_komisi'],
                    'komisi_persen' => $input['komisi_persen'],
                    'disc1_persen' => $input['disc1_persen'],
                    'disc2_persen' => $input['disc2_persen'],
                    'qty_item' => $input['qty_item'],
                    'qty_order' => $input['qty_order'],
                    'gross_sales' => $input['gross_sales'],
                    'total_ppn' => $input['total_ppn'],
                    'total_discount' => $input['total_discount'],
                    'sales_before_tax' => $input['sales_before_tax'],
                    'sales_after_tax' => $input['sales_after_tax'],
                    'service_level' => $input['service_level'],
                    'qty_deliver' => $input['qty_deliver'],
//                    'posting_date' => $this->formatDate("Y-m-d",$input['posting_date']),
                    'status' => $input['status'],
                    'sales_pada_toko' => $input['store_code'],
                    'jumlah_print' => $input['jumlah_print'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );
 
                $this->model->insert_data($data);
                $this->insert_log("so_online_header", $docno, "Add Header Data");
                $result = 0;
                $msg = "OK";
                $docno = $docno;
            }
          echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg,"docno"=>$docno,
            "data" => $data
        )); 
    }

    function edit_data_header(){
        try {
            $input = $this->toUpper($this->input->post());

            $read = $this->model->read_data($input['docno']);
           
            if ($read->num_rows() > 0) {
                $bf = $read->row();
                $data = array(
                    'doc_date' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'store_code' => $input['store_code'],
                    'location_code' => $input['location_code'],
                    'so_no' => $input['so_no'],
                    'provinsi_id' => $input['provinsi_id'],
                    'regency_id' => $input['regency_id'],
                    'jenis_so' => $input['jenis_so'],
                    'remark' => $input['remark'],
                    'customer_code' => $input['customer_code'],
                    'salesman_id' => $input['salesman_id'],
                    'tipe_komisi' => $input['tipe_komisi'],
                    'komisi_persen' => $input['komisi_persen'],
                    'disc1_persen' => $input['disc1_persen'],
                    'disc2_persen' => $input['disc2_persen'],
                    // 'disc3_persen' => $input['disc3_persen'],
//                    'qty_item' => $input['qty_item'],
//                    'qty_order' => $input['qty_order'],
//                    'gross_sales' => $input['gross_sales'],
//                    'total_ppn' => $input['total_ppn'],
//                    'total_discount' => $input['total_discount'],
//                    'sales_before_tax' => $input['sales_before_tax'],
//                    'sales_after_tax' => $input['sales_after_tax'],
//                    'service_level' => $input['service_level'],
//                    'qty_deliver' => $input['qty_deliver'],
                    'status' => $input['status'],
                    'sales_pada_toko' => $input['store_code'],
                    'jumlah_print' => $input['jumlah_print'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );
                // var_dump($input['disc1_persen']);
                // var_dump($bf->disc2_persen);
                if($input['status']=="ON ORDER"){
                    $data['posting_date'] = date('Y-m-d');
                } 
                $this->insert_log("sales_order_header", $input['docno'], $input['status'].": Update Header Data");
                $this->model->update_data($input['docno'], $data);

                if($bf->disc1_persen != $data['disc1_persen']){
                    $this->model->update_data_detail_disc($input['docno'], $data['disc1_persen'], 1, $input['pkp']);
                }

                if($bf->disc2_persen != $data['disc2_persen']){
                    $this->model->update_data_detail_disc($input['docno'], $data['disc2_persen'], 2, $input['pkp']);
                }

                // if($bf->disc3_persen != $data['disc3_persen']){
                //     $this->model->update_data_detail_disc($input['docno'], $data['disc3_persen'], 3, $input['pkp']);
                // }

                if($input['reason'] != ""){
                    $this->insert_log("sales_order_header", $input['docno'], $input['status'].": ".$input['reason']);
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

    function read_data($code){
        try {
            $read = $this->model->read_data($code);
            $totaldetail = $this->model->read_totaldetail($code)->row();
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
            "data" => $data,
            "total"=>$totaldetail->total
        ));
    }

    function delete_data($code){
     try {
            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {
 
                $readstatus = $read->row()->status; 

                if ($readstatus=='CLOSED') {
                    $result = 1;
                    $msg="Data tidak bisa dihapus";
                }else{
                    $this->model->delete_data($code);
                    $result = 0;
                    $msg="OK";
                }
            } else {
                $result = 1;
                $msg="Data tidak ditemukan";
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

      function print_so($docno){
          $read = $this->model->read_data($docno);
          $readcount = $this->model->count_data($docno)->row();
          $path = FCPATH."assets/barcode/".$docno.".png";
          $this->barcode($path,$docno,"50","horizontal","Code128","true",1);
          $data=array();
          if ($read->num_rows() > 0) {
              $r = $read->row();
              $this->model->update_data($docno, array("jumlah_print"=>$r->jumlah_print+1));
              $data['so']=$r;
              $data['totalitem'] = $readcount->item;
              $data['qty'] = $readcount->qty;
              $f = $this->getParamGrid(" a.docno='$docno' ","seqno");
              $data['det'] = $this->model->get_list_data_detailprint($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
              $data['barcode'] = $path;
          }

          $this->load->library('escpos');
          $connector = new Escpos\PrintConnectors\WindowsPrintConnector("EPSON TM-U220 Receipt");
          $printer = new Escpos\Printer($connector);

          $printer->initialize();
          $tux = Escpos\EscposImage::load($data['barcode'], true);
          $printer -> bitImageColumnFormat($tux, Escpos\Printer::IMG_DOUBLE_WIDTH | Escpos\Printer::IMG_DOUBLE_HEIGHT);
          $printer -> feed();

          $printer->initialize();
          $printer->setLineSpacing(25);
          $printer->text($this->createRowColumn(array("No. / Cust :",$data['so']->ak_docno." / ".$data['so']->customer_name), array("text","text"),array(13,25)));
          $printer->text($this->createRowColumn(array("SO Date    :",$data['so']->ak_doc_date), array("text","text"),array(13,25)));
          $printer->text($this->createRowColumn(array("SO         :",$data['so']->so_no), array("text","text"),array(13,25)));

          $width = array(8,18,12);
          $printer->initialize();
          $printer->setLineSpacing(25);
          $printer->setFont(Escpos\Printer::FONT_B);
          $printer->text("----------------------------------------\n");
          $printer->text($this->createRowColumn(array("No","Item#","Qty"),array("text","text","text"),$width));
          $printer->text("----------------------------------------\n");
          $qty = 0;
          foreach ($data['det'] as $i => $detail){
              $qty += $detail->qty_order;
              $printer->text($this->createRowColumn(array($i+1,$detail->product_code,number_format($detail->qty_order)." ".$detail->uom_id), array("text","text","text"),$width));
          }
          $printer->text("----------------------------------------\n");
          $printer->text("Item       : ".count($data['det'])."\n");
          $printer->text("Qty        : ".number_format($qty)."\n");
          $printer->text("----------------------------------------\n");
          $printer->text("Print      : ".$data['so']->crtby."\n");
          $printer->text(date('d/m/Y H:i:s')."\n");
          if($data['so']->jumlah_print>=1){
              $printer->text("Copied ".$data['so']->jumlah_print."\n");
          }

          $printer->initialize();
          $printer->setLineSpacing(25);
          $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
          $printer -> feed();
          $printer->text($this->createRowColumn(array("  Disiapkan  ","  Packing   ","   Checked   "),array("text","text","text"),array(13,12,13)));
          $printer -> feed(4);
          $printer->text($this->createRowColumn(array("(___________)","(__________)","(___________)"),array("text","text","text"),array(13,12,13)));
          $printer->feed(6);
          $printer->close();

          echo json_encode(array(
              "status"=>0
          ));
      }

     
    function get_product(){
        $tgl = $this->input->get('doc_date');
        $lokasi = $this->input->get('lokasi');
        $prd = $this->formatDate("Ym", $tgl);
        $special = " a.periode='$prd' and a.location_code='$lokasi' ";
        $f = $this->getParamGrid($special,"nobar");
        $data = $this->model->get_product($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

    function get_byproduct(){
       $tgl = $this->input->post('doc_date');
        $docno = $this->input->post('docno');
        $lokasi = $this->input->post('lokasi');
        $prd = $this->formatDate("Ym", $tgl);
        $special = " a.periode='$prd' and a.location_code='$lokasi' and nobar='$docno' ";
        $f = $this->getParamGrid($special,"nobar");
        $data = $this->model->get_byproduct($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function get_unit_price(){
        $input = $this->input->get();
        $product_id = $input['product_id'];
        $tgl = $input['tanggal'];
        $lokasi = $input['lokasi'];
        $customer_code=$input['customer_code'];
        if($customer_code==""){
            $rd = $this->model_cust->read_data_by_lokasi($lokasi);
            if($rd->num_rows()>0){
                $customer_code = $rd->row()->customer_code;
            }
        } 
        $discount = $this->model->get_discount($product_id, $tgl,$lokasi,$customer_code);
        $unit_price = $this->model->get_unit_price($product_id,$customer_code,$tgl);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "diskon"=>$discount,
                "unit_price"=>$unit_price)
        );
    }

    function load_grid_detail($docno){
        $f = $this->getParamGrid(" a.docno='$docno' ","seqno");
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
            $input =  $this->input->post();
            if($input['nobar']==''){
                $result = 1;
                $msg='Procuct Harap discan';
            }
            else{
            $read = $this->model->cek_detail($docno, $input['nobar'],$input['tipe']);
            $seqno = $this->model->generate_seqno($docno);
                $data = array(
                    'docno' => $docno,
                    'seqno' => $seqno,
                    'nobar' => $input['nobar'],
                    'tipe' => $input['tipe'],
                    'qty_order' => $input['qty_order'],
                    'unit_price' => $input['unit_price'],
                    'disc1_persen' => $input['disc1_persen'],
                    'disc1_amount' => $input['disc1_amount'],
                    'disc2_persen' => $input['disc2_persen'],
                    'disc2_amount' => $input['disc2_amount'], 
                    'disc_total' => $input['disc_total'],
                    'bruto_before_tax' => $input['bruto_before_tax'],
                    'total_tax' => $input['total_tax'],
                    'net_unit_price' => $input['net_unit_price'],
                    'net_total_price' => $input['net_total_price'],
                    'finish_so' => $input['finish_so'],
                    'status_detail' => $input['status_detail'],
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );

                // var_dump($data);
                // die();
                $this->insert_log("sales_order_online_detail", $docno, "Add detail Data ".$input['nobar']);
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
                    'nobar' => $input['nobar'],
                    'qty_order' => $input['qty_order'],
                    'unit_price' => $input['unit_price'],
                    'disc1_persen' => $input['disc1_persen'],
                    'disc1_amount' => $input['disc1_amount'],
                    'disc2_persen' => $input['disc2_persen'],
                    'disc2_amount' => $input['disc2_amount'], 
                    'disc_total' => $input['disc_total'],
                    'bruto_before_tax' => $input['bruto_before_tax'],
                    'total_tax' => $input['total_tax'],
                    'net_unit_price' => $input['net_unit_price'],
                    'net_total_price' => $input['net_total_price'],
                    'finish_so' => $input['finish_so'],
                    'status_detail' => $input['status_detail'],
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                );

                $this->insert_log("sales_order_detail", $input['docno'], "Update detail Data ".$input['nobar']);
                $this->model->update_data_detail($input['docno'], $input['id'], $data);
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

    function read_history($docno){
        try {
            $result = 0;
            $msg="OK";
            $data = $this->read_log(" where tabel='sales_order_header' and a.data_before='$docno'", " order by log_date desc");
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

    function delete_data_detail(){
        try {
            $code = $this->input->post("id");
            $read = $this->model->read_data_detailID($code);
            if ($read->num_rows() > 0) {
                $rd = $read->row();
                $read = $this->model->read_transactions_detail($rd->docno, $rd->seqno);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
                }else{
                    $this->model->delete_data_detail($rd->docno, $code);
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


    function copy_detail(){
        $input = $this->toUpper($this->input->post());
        $from = $input['from'];
        $to = $input['to'];
        $user = $this->session->userdata('user_id');
        $tgl = date('Y-m-d H:i:s');
        $read = $this->model->read_data($to);

        if($read->num_rows()>0) {
            $rf = $read->row();
            $res = $this->model->copy_detail($from, $to, $user, $tgl, $rf->pkp);
            $stt = $res?0:1;
        }else {$res = true; $stt=1;}
        echo json_encode(array(
            "status" => $stt, "isError" => $res,
            "msg" => "OK", "message" => $res
        ));
    }
    function cek_authority(){
        $input = $this->input->post();
        $kode_otoritas = $input['kode_otoritas'];
        $docno = $input['docno'];
        $tabel = $input['tabel'];

        $res = $this->model_user->cekOtoritas($kode_otoritas);
        if($res->num_rows()>0){
            $this->insert_log($tabel, $docno, $res->row()->user_id);
            $result = 0;
            $msg="Authorized";
        }else{
            $result = 1;
            $msg="Kode tidak valid";
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg
        ));
    }


    function export_data(){
        $filename = 'SALESORDER_' . date('Ymd') . '.csv';
        $header = array("Kode", "Nama","Status","Create By","Update By", "Create Date","Update Date");
        $app = $this->getParamOption();
        $data = $this->model->get_list_data2($app);
        $unset = [];
        $top = array();
        $this->export_csv($filename,$header, $data, $unset, $top);
    }


    function read_datacustomer($code,$so_no){
        try {
            $read = $this->model->read_datacustomer($code,$so_no);
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

}
