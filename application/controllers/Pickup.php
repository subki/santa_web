<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pickup extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Pickup_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index($aksi=""){
        $data['title'] = 'Pickup List';
        $data['content'] = $this->load->view('vPickup', $data, TRUE);
        $this->load->view('main',$data);
    }

    function form($aksi=""){
        $data['aksi']=$aksi;
        if($aksi=="add"){
            $data['title'] = 'Add Pickup';
            $docno = $this->model->generate_auto_number();
            $data['title'] = 'Add Pickup';
            $data['docno'] = $docno;
            $data['content'] = $this->load->view('vPickup_form', $data, TRUE);
        }else{
            $data['title'] = 'Edit Pickup';
            $data['docno'] = $this->input->get('id');
            $data['content'] = $this->load->view('vPickup_form', $data, TRUE);
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
                    $msg="SO ini sudah di Pickup";
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

    function load_gridexpedisi(){
        $gol = $this->input->get('tipe');
        $f = $this->getParamGrid("","id");
        $data = $this->model->get_list_dataexpedisi($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app'],0,$gol);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function save_data_header(){
        $input =$this->input->post();  
        $docno = $this->model->generate_auto_number($input['so_number']);
        $tgl=$this->formatDate("Y-m-d",$input['doc_date']);
        $cscode=$input['customer_code'];

             $data = array( 
                    'tgl' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'ekspedisi' => $input['pickupby'],
                    'fase_pickup' => $input['fase'],
                    'status' => $input['status'], 
                    'line' => $input['fase'],
                    'user' => $input['pickup_by'], 
                    'ekspedisiby' => $input['customer_code'], 
                    'ekspedisiname' => $input['customer_name'],
                     
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                ); 

        $data1 = $this->model->cekdatapickup($tgl)->row();
        $cekrecord=$this->model->cekdatapickup($tgl)->num_rows(); 
        $cekfase = $this->model->cekfase_data($tgl,$cscode);
        if ($cekfase->num_rows() > 0) {
            $id=$cekfase->row()->id;
                $data = array( 
                    'tgl' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'ekspedisi' => $input['pickupby'],
                    'fase_pickup' => $cekfase->row()->fase_pickup,
                    'status' => $input['status'], 
                    'line' => $input['fase'],
                    'user' => $input['pickup_by'], 
                    'ekspedisiby' => $input['customer_code'], 
                    'ekspedisiname' => $input['customer_name'],
                     
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );  
        }else{ 
                $data = array( 
                    'tgl' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'ekspedisi' => $input['pickupby'],
                    'fase_pickup' => $data1->fase,
                    'status' => $input['status'], 
                    'line' => $input['fase'],
                    'user' => $input['pickup_by'], 
                    'ekspedisiby' => $input['customer_code'], 
                    'ekspedisiname' => $input['customer_name'],
                     
                    'crtby' => $this->session->userdata('user_id'),
                    'crtdt' => date('Y-m-d H:i:s'),
                );  
            $id=$this->model->insert_data($data); 
               
        }
         
                $this->insert_log("pickup_h", "ekspedisi", "Add Header Data");
                $result = 0;
                $msg = "OK";
          echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg,
            "data" => $data,
            "id"=>$id
        )); 
    }

    function edit_data_header(){
        try {
            $input =$this->input->post();
            $read = $this->model->read_data($input['id']);

            if ($read->num_rows() > 0) {
                $bf = $read->row();
                $data = array(
                    'tgl' => $this->formatDate("Y-m-d",$input['doc_date']),
                    'ekspedisi' => $input['pickupby'],
                    'fase_pickup' => $input['fase'],
                    'tgl_pickup' => $this->formatDate("Y-m-d",$input['tgl_pickup']), 
//                    'status' => $input['status'],
                    'user' => $input['pickup_by'], 
                    'ekspedisiname' => $input['customer_name'], 
                    'ekspedisiby' => $input['customer_code'],  
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s')
                ); 
                $datadetail = array(  
                    'ekspedisi' => $input['customer_code']
                ); 
                $this->insert_log("pickup_header", $input['docno'], $input['status'].": Update Header Data");
                $this->model->update_data($input['id'], $data); 
                $this->model->update_datadetail($input['id'], $datadetail); 

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
            "msg" => $msg, "message" => $msg, "id"=>$input['id']
        ));
    }

    function read_data($code){
        try {
            $read = $this->model->read_data($code);
            $readdetail = $this->model->read_datadetail($code); 
            if ($read->num_rows() > 0) {
                $result = 0;
                $msg="OK";
                $data = $read->result()[0];
                $total = $readdetail->result()[0];
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
            "total" => $total
        ));
    }

    function delete_data($code){
        try {
            $read = $this->model->read_data($code);
            if ($read->num_rows() > 0) {
 
                $readstatus = $read->row()->status; 
                if ($readstatus=='Pickup') {
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
          $path = FCPATH."assets/barcode/".$docno.".jpg";
          $this->barcode($path,$docno,"40","horizontal","code128","true",1);
          $data=array();
          if ($read->num_rows() > 0) {
              $r = $read->row();
              $this->model->update_data($docno, array("jumlah_print"=>$r->jumlah_print+1));
              $data['so']=$r;
              $f = $this->getParamGrid(" a.docno='$docno' ","seqno");
              $data['det'] = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
              $data['barcode'] = $path;
          }
          $this->load->library('pdf');
          $this->pdf->load_view('print/Pickup', $data);
          $this->pdf->render();
//
          $this->pdf->stream($docno.'.pdf',array("Attachment"=>0));
//                  $this->load->view('print/Pickup',$data);

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

    function get_bypickup(){
       $id = $this->input->post('id'); 
        $special = " a.id='$id'";
        $f = $this->getParamGrid($special,"id");
        $data = $this->model->get_bypickup($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
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
        $f = $this->getParamGrid(" a.pickup_h_id='$docno' "); 
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

            $pickup = $this->input->post("pickup");
            $pickupdate = $this->input->post("pickupdate");
            $data = array(
                    'user' => $pickup,
                    'tgl_pickup' => $this->formatDate("Y-m-d",$pickupdate),
                    'updby' => $this->session->userdata('user_id'),
                    'upddt' => date('Y-m-d H:i:s') 
                );
            $cek = $this->model->cekstatusSOonline($docno);  
           foreach ($cek->result() as $row)
                   { 
                      $msg= $cek->row()->docno; 
                   };
            if($cek->num_rows() > 0){
                $result = 0;
                $msg="SO Online masih keadaan Open, Silahkan melakukan Posting pada No ini ".$cek->row()->docno;
                
            }
            else{

                $result = 0;
                $msg="OK";
                $this->model->updatestatuspickdetail($docno); 
                $this->model->updatestatuspick($docno);
                $this->model->update_data($docno, $data); 
            }
            echo json_encode(array(
                "status" => $result, "isError" => ($result==1),
                "msg" => $msg, "message" => $msg 
            ));
    }
    function delete_data_detail(){
        try {
            $id = $this->input->post("id");
            $input = $this->input->post('reason');
            $code = $this->input->post('row');
            $read = $this->model->read_data_detailID($code)->row();
             
            if ($read->status =='Open') {
                    $this->model->delete_data_detail($code);

                       if($input['reason'] != ""){
                            $this->insert_log("Pickup reason delete", $code, 'Delete'.": ".$input['reason']);
                        }
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
		 $pickupheader = $input['pickupheader'];
		 $barcode = $input['barcode'];
		 $tanggal = $input['tanggal'];
		 $cekSO = $this->model->cekSO($barcode);
		 $read = $this->model->read_datapickdetail($barcode);
		 $readheader = $this->model->read_data($pickupheader)->row();
		 $readfase = $this->model->read_datadetail($pickupheader)->row();
		 $jumfase= $readfase->jumlah;
		 $data = array(
			 'pickup_h_id' => $pickupheader,
			 'tgl' => $tanggal,
			 'barcode' => $barcode,
			 'ekspedisi'=>$readheader->ekspedisiby,
			 'status' => 'Open',
			 'fase_pickup' => $jumfase+1
		 );
		 if($cekSO->num_rows()==0){
			 $stt =0;
			 $msg="Kode status masih Open/ Sudah Terposting";
			 $res="Kode status masih Open/ Sudah Terposting";
		 }else{

			 if($read->num_rows()==0) {
				 $rf = $read->row();
				 $res = $this->model->save_detail($data);
				 $stt = 1;
			 }else {$res = 'Data ini sudah ada'; $stt=0;}
		 }
		 echo json_encode(array(
			 "status" => $stt, "isError" => $res,
			 "msg" => "OK", "message" => $res
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

    function cekdatapickup(){ 

            $tanggal = $this->input->post("tgl"); 
            $data1 = $this->model->cekdatapickup($tanggal)->row();
            $cekrecord=$this->model->cekdatapickup($tanggal)->num_rows(); 
        
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


}
