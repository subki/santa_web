<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockopname extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Stockopname_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index($aksi=""){
        $data['title'] = 'Stockopname List';
        $data['content'] = $this->load->view('vStockopname', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
        if($aksi=="add"){
            $data['title'] = 'Add Stockopname';
            $docno = $this->model->generate_auto_number();
            $data['title'] = 'Add Stockopname';
            $data['docno'] = $docno;
            $data['content'] = $this->load->view('vStockopname_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Stockopname';
            $data['docno'] = $this->input->get('id');
            $data['content'] = $this->load->view('vStockopname_form', $data, TRUE);
        }
        $this->load->view('main',$data);
    }

    function read_data_by_so($code){
        try {
            $read = $this->model->read_data_by_so($code);
            if ($read->num_rows() > 0) { 
                $statuspick=$read->row()->status; 
                if($statuspick=='Open'){
                    $result = 0;
                    $status = 'Unpost';
                    $msg="OK";
                    $data = $read->result()[0];
                }
                else{
                    $result = 1;
                    $status = 'Pick Up';
                    $msg="SO ini sudah di Stockopname";
                    $data = $read->result()[0];
                }
                
            } else {
                $result = 0;
                $status = 'Unpost';
                $msg="OK";
                $data =null;
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $status, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg, "result" => $result,
            "data" => $data
        ));
    }
    function load_grid(){
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
        $input =$this->input->post();    
        $periode=$this->formatDate("Ym",$input['trx_date']);
        $location_code=$input['on_loc']; 
          

             $datagondola = array(  
                    'store_code' => $input['store_code'],
                    'trx_no' => $input['trx_no'],
                    'on_loc' => $input['on_loc'], 
                    'jenis_adjust' => $input['jenis_adjust'],
                    'gondola' => $input['gondola'], 
                    'remark' => $input['remark'],
                    'status' => $input['status'], 
                    'taking' =>'Yes',
                    'trx_date' => $this->formatDate("Y-m-d",$input['trx_date']),  
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );  
                $this->insert_log("Stockopname_h", "Stockopname", "Add Header Data");
                $result = 0;
                $msg = "OK";

            $this->model->insert_datagondola($datagondola); 
           // $this->model->insert_datadetail($periode,$location_code,$input['store_code'],$input['trx_no']); 
          echo json_encode(array(
                "status" => $result, "isError" => ($result==1),
                "msg" => $msg, "message" => $msg,
                "data" => $data,
                "id"=>$input['trx_no']
            )); 
    }

    function edit_data_header(){
        try {
            $input =$this->input->post(); 
            $read = $this->model->read_data($input['trx_no']);
            
            if ($read->num_rows() > 0) {
                $bf = $read->row();
                $data = array(  
                    'store_code' => $input['store_code'], 
                    'on_loc' => $input['on_loc'], 
                    'jenis_adjust' => $input['jenis_adjust'],
                    'gondola' => $input['gondola'], 
                    'remark' => $input['remark'],
                    'status' => $input['status'], 
                    'taking' =>'Yes',
                    'trx_date' => $this->formatDate("Y-m-d",$input['trx_date']),  
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );     
                $this->insert_log("Stockopname_header", $input['docno'], $input['status'].": Update Header Data");
                $this->model->update_data($input['trx_no'], $data);  

                $result = 0;
                $msg="OK";
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
            "msg" => $msg, "message" => $msg, "id"=>$input['trx_no']
        ));
    }
    function getDate(){
        $input = $this->toUpper($this->input->post());
        $from=$this->formatDate("Y-m-d", $input['from']);
        $to=$this->formatDate("Y-m-d", $input['to']); 
        $store_code=$input['store_code']; 
        $location_code=$input['location_code'];  
        try {
            $read = $this->model->read_dataopname($from,$to,$store_code,$location_code); 
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
        $store_code=$input['store_code']; 
        $location_code=$input['location_code'];  
        $docno = $this->model->generate_auto_numberadj();
        $periode=$this->formatDate("Ym",$input['to']);
        // var_dump($customer_code);
        // die();
        try {    
            $read = $this->model->read_opnamepost($from,$to,$location_code,$store_code); 
        
            $data = array( 
                    'trx_date' => $this->formatDate("Y-m-d",$input['to']),
                    'trx_no' => $docno,
                    'store_code' => $store_code,
                    'status' => 'Open', 
                    'jenis_adjust' => 'Stock Taking',
                    'on_loc' => $location_code, 
                    'tot_item' => $read->row()->tot_item, 
                    'tot_qty' => $read->row()->tot_qty, 
                    'remark' => $docno,
                    'print' => 0,
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );  
            $id=$this->model->insert_data($data); 
            $this->model->insert_datadetail($periode,$location_code,$store_code,$docno); 
            $this->model->update_refno($docno,$from,$to,$location_code,$store_code); 
                $result = 0;
                $msg = "Berhasil Merge Opname";
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
    function read_data($code){
        try {
            $read = $this->model->read_data($code);
            $readdetail = $this->model->read_datadetail($code); 
            $readdetailopname = $this->model->read_datadetailopname($code);
            if ($read->num_rows() > 0) {
                $result = 0;
                $msg="OK";
                $data = $read->result()[0];
                $total = $readdetail->result()[0];
                $totalopname = $readdetailopname->result()[0];
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
                $data = null;
                $total = null;
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg,
            "data" => $data,
            "total" => $total,
            "totalopname" => $totalopname
        ));
    }

    function delete_data($code){
        try {
            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {
 
                $readstatus = $read->row()->status; 
                if ($readstatus=='Posted') {
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
       function print_opfull($docno){

          $read = $this->model->read_dataadj($docno);
          if ($read->num_rows() > 0) {
              $r = $read->row();
              $this->model->update_dataprint($docno, array("print"=>$r->print+1));
              $data['so']=$r; 
              $f = $this->getParamGrid(" trx_no = '".$docno."' ","crtdt");
              $readopname   = $this->model->opnametotal($docno)->row(); 
              $data['totalopname']=$readopname; 
              $data['totalload']=20000; 
              $data['det']  = $this->model->get_list_data_detailall(1, $readopname->totaldata,$f['sort'],$f['order'],$f['role'], $f['app']);
              
          // $this->load->library('pdf');
          // $this->pdf->load_view('print/Stockopnamefull', $data);
          // $this->pdf->render();
          //   $x          = 540;
          //   $y          = 770;
          //   $text       = "{PAGE_NUM} of {PAGE_COUNT}";
          //   $font       = $this->pdf->getFontMetrics('Courier', 'normal');
          //   $size       = 10;
          //   $color      = array(0,0,0);
          //   $word_space = 0.0;
          //   $char_space = 0.0;
          //   $angle      = 0.0;
          //   $this->pdf->getCanvas()->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle); 
          //   $this->pdf->stream($docno.'.pdf',array("Attachment"=>0));
     
  
             $this->load->view('print/Stockopnamefull',$data);
          } 
          else{
            $data['so']=0;
            $this->load->view('print/Stockopnamefull',$data);
          } 

      }  
       function print_opfullexcel($docno){

          $read = $this->model->read_dataadj($docno);
          if ($read->num_rows() > 0) {
              $r = $read->row();
              $this->model->update_dataprint($docno, array("print"=>$r->print+1));
              $data['so']=$r; 
              $f = $this->getParamGrid(" trx_no = '".$docno."' ","crtdt");
              $readopname   = $this->model->opnametotal($docno)->row(); 
              $data['totalopname']=$readopname; 
              $data['totalload']=20000; 
              $data['det']  = $this->model->get_list_data_detailall(1,$readopname->totaldata,$f['sort'],$f['order'],$f['role'], $f['app']);
              
          // $this->load->library('pdf');
          // $this->pdf->load_view('print/stockopnamefullexcel', $data);
          // $this->pdf->render();
          //   $x          = 540;
          //   $y          = 770;
          //   $text       = "{PAGE_NUM} of {PAGE_COUNT}";
          //   $font       = $this->pdf->getFontMetrics('Courier', 'normal');
          //   $size       = 10;
          //   $color      = array(0,0,0);
          //   $word_space = 0.0;
          //   $char_space = 0.0;
          //   $angle      = 0.0;
          //   $this->pdf->getCanvas()->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle); 
          //   $this->pdf->stream($docno.'.pdf',array("Attachment"=>0));
     
  
             $this->load->view('print/stockopnamefullexcel',$data);
          } 
          else{
            $data['so']=0;
            $this->load->view('print/stockopnamefullexcel',$data);
          } 

      }   
      function print_op($docno){
          $read = $this->model->read_data($docno); 
          // var_dump($read->num_rows());
          // die();
          $data=array();
          if ($read->num_rows() > 0) {
              $r = $read->row();
              $this->model->update_data($docno, array("print"=>$r->print+1));
              $data['so']=$r;
              $f = $this->getParamGrid(" trx_no = '".$docno."' ","crtdt"); 
              $data['det'] = $this->model->get_list_data_detailgondola(1,9999999,$f['sort'],$f['order'],$f['role'], $f['app']);
              
          }
         $this->load->library('pdf');
          $this->pdf->load_view('print/Stockopname', $data);
          $this->pdf->render();
            $x          = 540;
            $y          = 770;
            $text       = "{PAGE_NUM} of {PAGE_COUNT}";
            $font       = $this->pdf->getFontMetrics('Courier', 'normal');
            $size       = 10;
            $color      = array(0,0,0);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle      = 0.0;
            $this->pdf->getCanvas()->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle); 
            $this->pdf->stream($docno.'.pdf',array("Attachment"=>0)); 
            //    $this->load->view('print/Stockopname',$data);

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

    function get_byStockopname(){
       $id = $this->input->post('id'); 
        $special = " a.id='$id'";
        $f = $this->getParamGrid($special,"id");
        $data = $this->model->get_byStockopname($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
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
        $f = $this->getParamGrid(" trx_no='$docno' ","item");  
        $data = $this->model->get_list_data_detail($f['page'],$f['rows']," item ",$f['order'],$f['role'], $f['app']);
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
            $read = $this->model->cek_detail($docno, $input['nobar'],$input['tipe']);
            if($read->num_rows()>0){
                $result = 1;
                $msg = "Product sudah diinput!!";
            }else {
               
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


                $this->insert_log("sales_order_detail", $docno, "Add detail Data ".$input['nobar']);
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
    function Updatestatus($docno){   

            $docno = $this->input->post("docno"); 
            $status = $this->input->post("status"); 
            $data = array( 
                    'status' => $status,
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s') 
                ); 
           
                $result = 0;
                $msg="OK";
                $this->model->updatestatusstokopname($docno, $data);   
            echo json_encode(array(
                "status" => $result, "isError" => ($result==1),
                "msg" => $msg, "message" => $msg 
            ));
    }
    function delete_data_detail(){
        try {
            $id = $this->input->post("id"); 
            $code = $this->input->post('row');
            $read = $this->model->read_data_detailID($id,$code)->row();
            $readheader = $this->model->read_data($id)->row();
             
            if ($readheader->status =='Open') {
                    $this->model->delete_data_detail($id,$code); 
                    $result = 0;
                    $msg="OK";
            } else {
                $result = 1;
                $msg="Ada Kesalahan.Data tidak bisa dihapus";
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
   function submitdetail(){
        $input = $this->toUpper($this->input->post()); 
        $barcode = $input['barcode'];
        $trx_no = $input['trx_no'];   
        $gondola = $input['gondola'];  
        $store = $input['store_code'];  
        $qty = $input['qty']; 
        $location_code = $input['location_code']; 
        $tanggal = $this->formatDate("Ym",$input['tanggal']);   
        $cekOP = $this->model->cekOP($barcode,$tanggal,$location_code);
        $cekOPadjust = $this->model->cekOPadjust($barcode,$trx_no,$gondola);
        $readdetailopname = $this->model->read_datadetailopname($trx_no);
         
        if($cekOP->num_rows()==0){
                $stt = 1;
                $msg="Kode tidak ditemukan";
                $data = null;
        }
        else{
            if($cekOPadjust->num_rows()==0){
                     $data = array(
                            'store' => $store,
                            'trx_no' => $trx_no,
                            'item' => $barcode, 
                            'gondola' => $gondola, 
                            'product_code'=>$cekOP->row()->product_code, 
                            'uom' =>$cekOP->row()->uom,
                            'taking_qty' => 1,
                            'crtby' => $this->session->userdata('user_id'),
                            'crtdt' => date('Y-m-d H:i:s'), 
                            'updby' => $this->session->userdata('user_id'),
                            'upddt' => date('Y-m-d H:i:s'),
                            'barcode' => $barcode
                        );
                    $this->model->insert_data_opname($data);
                        $stt =0;
                        $msg="Insert";
                        $res="OK";
                        $totalopname = $readdetailopname->result()[0];
               }else{
                        $data = array( 
                            'taking_qty' => $cekOPadjust->row()->taking_qty+$qty, 
                            'updby' => $this->session->userdata('user_id'),
                            'upddt' => date('Y-m-d H:i:s') 
                        );
                    $this->model->update_data_opname($barcode,$trx_no,$gondola, $data);
                        $stt =0;
                        $msg="UPdate";
                        $res="OK";
                        $totalopname = $readdetailopname->result()[0];
               }
        }    
        echo json_encode(array(
            "status" => $stt, "isError" => $res,
            "msg" => $msg, "message" => $res,
            "totalopname" => $totalopname
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

    function cekdataStockopname(){ 

            $tanggal = $this->input->post("tgl"); 
            $data1 = $this->model->cekdataStockopname($tanggal)->row();
            $cekrecord=$this->model->cekdataStockopname($tanggal)->num_rows(); 
        
            if($cekrecord==0){
               $data=1;
            }
            else{ 
               $data=$data1->fase;
            }
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK", 
                "data" =>$data)
        );
    }

    function load_gridlocation(){ 
        $f = $this->getParamGrid("","location_code");
        $data = $this->model->get_list_dataloc($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],0,$gol);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

}
