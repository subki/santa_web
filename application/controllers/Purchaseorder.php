<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaseorder extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Po_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index($aksi=""){
        $data['title'] = 'Purchase Order';
        $data['datenow'] = date("d/m/Y"); 
        $data['content'] = $this->load->view('vPo', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
       $get = $this->toUpper($this->input->post());  
        if($aksi=="add"){
            $data['title'] = 'Add Purchase Order';
            $docno = $this->model->generate_auto_number();
            $data['title'] = 'Add Purchase Order';
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

            $data['content'] = $this->load->view('vPo_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Purchase Order';
            $data['docno'] = $this->input->get('docno');
            $data['content'] = $this->load->view('vPo_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

    function load_grid($status, $supp, $prd){  

        $d= substr($prd,6); 
        $y= substr($prd, 0, 4);
        $m= substr($prd, 4, 2);
        $tgl = $y."-".$m."-".$d;   
        $kdsupp= $supp; 
        $dtnow=date('Y-m-d'); 
        // $f = $this->getParamGrid(" CASE WHEN  status != '$status'  THEN doc_date <= '$tgl' ELSE  doc_date = '$tgl' END
        //     AND (status )
        //     AND CASE WHEN '$tgl' != '$dtnow' THEN status='OPEN' ELSE  status in('OPEN','BATAL','CLOSED','ON ORDER') END   
        //                      ","status");
        if($status=='ALL'){   
            if($cust=='~'){ 
               $special = "p.po_date <= '$tgl' AND CASE WHEN '$tgl' = '$dtnow' THEN p.status_po in('Open','Cancel','Closed','On Order') ELSE p.status_po='Open'  END ";
               // $f = $this->getParamGrid(" doc_date <= '$tgl' AND CASE WHEN '$tgl' != '$dtnow' THEN status in('OPEN','BATAL','CLOSED','ON ORDER') ELSE status='OPEN'  END ","status");
             }
             else{
              $special ="p.po_date <= '$tgl' AND CASE WHEN '$tgl' = '$dtnow' THEN p.status_po in('Open','Cancel','Closed','On Order') ELSE p.status_po='Open'  END  and s.supplier_code like '%$supp%' ";
                 //$f = $this->getParamGrid(" doc_date <= '$tgl' AND CASE WHEN '$tgl' != '$dtnow' THEN status in('OPEN','BATAL','CLOSED','ON ORDER') ELSE status='OPEN'  END  and customer_code like '%$cust%' ","status");
             }
        }
        else{
            if($cust=='~'){  
               $special = "p.po_date <= '$tgl' AND p.status_po='$status'";
               // $f = $this->getParamGrid(" doc_date <= '$tgl' AND status='$status'","status");
            }
            else{ 
               $special = "p.status_po='$status' and p.po_date <='$tgl' and s.supplier_code like '%$supp%' ";
               // $f = $this->getParamGrid(" status='$status' and doc_date <='$tgl' and customer_code like '%$cust%' ","status");
            }
        }
 
        $total1 = $this->getParamGrid_BuilderComplete(array(
            "table"=>"po_hdr p",
            "sortir"=>"p.status_po",
            "special"=>$special,
            "select"=>"p.*,s.supplier_name", 
             "join"=>[
                "supplier s"=>"s.supplier_code=p.supplier_id" 
            ],
          "posisi"=>["INNER"]
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

    function load_gridsupp(){ 
        $f = $this->getParamGrid("","supplier_code");
        $data = $this->model->get_list_datasupp($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],0);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data_header(){
       $input = $this->toUpper($this->input->post()); 
 
          $data = array(
                    'po_no' => $input['docno'],
                    'po_date' => $this->formatDate("Y-m-d",$input['po_date']),
                    'wilayah' => $input['regency_id'],
                    'store_code' => $input['store_code'],
                    'supplier_id' => $input['supplier_code'],
                    'currency' => $input['currency'],
                    'rate' => $input['rate'],
                    'eta' => $this->formatDate("Y-m-d",$input['eta']),
                    'expired_date' => $this->formatDate("Y-m-d",$input['expired_date']), 
                    'ref_no' => $input['ref_no'],
                    'remark' => $input['remark'],
                    'tot_item' => $input['tot_item'],
                    'tot_qty_order' => $input['tot_qty_order'],
                    'tot_qty_recv' => $input['tot_qty_recv'],
                    'subtotal' => $input['subtotal'],
                    'disc' => $input['disc'],
                    'ppn' => $input['ppn'],
                    'total_purch' => $input['subtotal'],
                    'status_po' => $input['status_po'],
                    'po_type' => $input['po_type'],
                    'print' => $input['jumlah_print'], 
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );
 
                $this->model->insert_data($data);
                $this->insert_log("po_hdr", $input['docno'], "Add Header Data");
                $result = 0;
                $msg = "OK";
                $docno = $input['docno'];
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
            // pre($input);
            if ($read->num_rows() > 0) {
                $bf = $read->row();
                 $data = array(
                    'po_no' => $input['docno'],
                    'po_date' => $this->formatDate("Y-m-d",$input['po_date']),
                    'wilayah' => $input['regency_id'],
                    'store_code' => $input['store_code'],
                    'supplier_id' => $input['supplier_code'],
                    'currency' => $input['currency'],
                    'rate' => $input['rate'],
                    'eta' => $this->formatDate("Y-m-d",$input['eta']),
                    'expired_date' => $this->formatDate("Y-m-d",$input['expired_date']), 
                    'ref_no' => $input['ref_no'],
                    'remark' => $input['remark'],
                    'tot_item' => $input['tot_item'],
                    'tot_qty_order' => $input['tot_qty_order'],
                    'tot_qty_recv' => $input['tot_qty_recv'],
                    'subtotal' => $input['subtotal'],
                    'disc' => $input['disc'],
                    'ppn' => $input['ppn'],
                    'total_purch' => $input['subtotal'],
                    'status_po' => $input['status_po'],
                    'po_type' => $input['po_type'],
                    'print' => $input['jumlah_print'], 
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s'),
                );
                // var_dump($input['disc1_persen']);
                // var_dump($bf->disc2_persen);
                if($input['status']=="On Order"){
                    $data['upddt'] = date('Y-m-d');
                    $data['updby'] = $this->session->userdata('user_id');
                } 
                $this->insert_log("po_hdr", $input['docno'], $input['status_po'].": Update Header Data");
                $this->model->update_data($input['docno'], $data);
  

                if($input['reason'] != ""){
                    $this->insert_log("po_hdr", $input['docno'], $input['status_po'].": ".$input['reason']);
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
        $f = $this->getParamGrid("","nobar");
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
        $f = $this->getParamGrid(" a.po_no='$docno' ","seqno");
        $data = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }


    function save_data_detail(){
        try {
            $input =  $this->input->post();  
            $seqno = $this->model->generate_seqno($input['po_no']); 
            $disc=$input['discdetail']/100;
            $ppn=$input['ppndetail']/100;
            $net_unit_price=$input['qty_order']*$input['unit_price'];
            $net= $net_unit_price-(($input['qty_order']*$input['unit_price'])*$disc);
            $net_purchase= $net-($net*$ppn);
                $data = array(
                    'po_no' =>  $input['po_no'],
                    'po_date' =>  $input['datepo'], 
                    'store_code' =>  $input['store_code'],
                    'seqno' => $seqno,
                    'product_code' => $input['sku'],
                    'uom' => $input['uom'],
                    'sku' => $input['skucode'],
                    'supplier_id' => $input['supplier_id'],
                    'net_unit_price' => $net_unit_price,
                    'net_purchase' =>$net_purchase ,
                    'qty_order' => $input['qty_order'],
                    'unit_price' => $input['unit_price'],
                    'disc' => $input['discdetail'],
                    'ppn' => $input['ppndetail'], 
                    'status' => 'Open',
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );

                // pre($data);
                // die();
                $this->insert_log("po_detail", $docno, "Add detail Data ".$input['po_no'].$input['sku']);
                $this->model->insert_data_detail($input['po_no'],$data);
                $result = 0;
                $msg = "OK"; 
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
 
            $disc=$input['discdetail']/100;
            $ppn=$input['ppndetail']/100;
            $net_unit_price=$input['qty_order']*$input['unit_price'];
            $net= $net_unit_price-(($input['qty_order']*$input['unit_price'])*$disc);
            $net_purchase= $net-($net*$ppn);
                $data = array(
                    'po_no' =>  $input['po_no'],
                    'po_date' =>  $input['datepo'], 
                    'store_code' =>  $input['store_code'],
                    'seqno' => $input['seqno'],
                    'product_code' => $input['sku'],
                    'uom' => $input['uom'],
                    'sku' => $input['skucode'],
                    'supplier_id' => $input['supplier_id'],
                    'net_unit_price' => $net_unit_price,
                    'net_purchase' =>$net_purchase ,
                    'qty_order' => $input['qty_order'],
                    'unit_price' => $input['unit_price'],
                    'disc' => $input['discdetail'],
                    'ppn' => $input['ppndetail'], 
                    'status' => 'Open',
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s'),
                );  
                $this->insert_log("edit_po_detail", $input['po_no'], "Update PO Data ".$input['sku']);
                $this->model->update_data_detail($input['po_no'], $input['seqno'], $data);
                $result = 0;
                $msg="OK"; 
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "docno"=>$input['po_no']
        ));
    }

    function read_data_detail($docno,$seqno){
        try {
            $read = $this->model->read_data_detailID($docno,$seqno);
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

    function delete_data_detail($po_no,$seqno){
        try { 
            $read = $this->model->read_data_detailID($po_no,$seqno);
            if ($read->num_rows() > 0) {
                $rd = $read->row(); 
               // / pre($rd);
               $this->model->delete_data_detail($rd->po_no, $seqno);
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
