<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Wholesales_model','model');
        $this->load->model('Finance_model','fa');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title'] = 'Verifikasi Finance OK';
        $data['content'] = $this->load->view('vFinanceOK', $data, TRUE);
        $this->load->view('main',$data);
    }
    function ar($aksi=""){
			if($aksi=="") {
				$data['title'] = 'AR Receipt';
				$data['content'] = $this->load->view('vFinanceAR', $data, TRUE);
			}else if($aksi=="add"){
				$data['title'] = 'Add AR Receipt';
				$data['aksi'] = $aksi;
				$data['content'] = $this->load->view('vFinanceAR_form', $data, TRUE);
			}else if($aksi=="edit"){
				$data['title'] = 'Edit AR Receipt';
				$data['aksi'] = $aksi;
				$data['id'] = $this->input->get('id');
				$data['content'] = $this->load->view('vFinanceAR_form', $data, TRUE);
			}
				$this->load->view('main', $data);
    }
    function invoice(){
        $data['title'] = 'Sales Invoice';
        $data['content'] = $this->load->view('vFinanceInv', $data, TRUE);
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
    function ar_grid(){
        $f = $this->getParamGrid("","doc_date");
        $data = $this->fa->ar_grid($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

    function update_finance_verify(){
        try {
            $input = $this->input->post();

            $data = array(
                'verifikasi_finance' => 'VERIFIED',
                'updby' => $this->session->userdata('user_id'),
                'upddt' => date('Y-m-d H:i:s')
            );

            $this->db->where_in('id', $input['data'])
                ->update('sales_trans_header', $data);
            $ws = $this->db->where_in('id', $input['data'])->get('sales_trans_header')->result();
            if($this->db->insert_batch('sales_invoice',$ws)){
                $result = 0;
                $msg="OK";
            }else{
                $result = 1;
                $msg="Failed Insert Batch Sales Invoice";
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

    function create_proforma(){
        try {
            $input = $this->input->post();

            $ctr = "00001";
            $yymm = date('ym');

            $this->db->select('docno');
            $this->db->where('docno like', "INV-P/".$yymm."/"."%");
            $this->db->order_by('docno', 'desc');
            $gen = $this->db->get('sales_proforma', 1)->row();

            if (isset($gen)) {
                $lastID = $gen->docno;
                $temp = explode('/', $lastID);
                $ctr = $temp[2] + 1;
                $ctr = str_pad($ctr, 5, "0", STR_PAD_LEFT);
            }
            $code = "INV-P/$yymm/".$ctr;

            $data = array(
                "docno"=>$code,
                "doc_date"=>date("Y-m-d"),
                "sales_invoice_data"=>json_encode($input['data']),
                "total_invoice"=>$input['total_invoice']
            );

            $this->db->where_in('id', $input['data'])
                ->update('sales_invoice', array("sales_proforma_id"=>$code));
            if($this->db->insert('sales_proforma',$data)){
                $result = 0;
                $msg="OK";
            }else{
                $result = 1;
                $msg="Failed Insert Sales Proforma";
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

    function update_finance_verify_unposting(){
        try {
            $input = $this->input->post();

            $data = array(
                'verifikasi_finance' => '',
                'updby' => $this->session->userdata('user_id'),
                'upddt' => date('Y-m-d H:i:s')
            );

            $this->db->where_in('id', $input['data'])
                ->update('sales_trans_header', $data);
            if($this->db->where_in('id',$input['data'])->delete('sales_invoice')){
                $result = 0;
                $msg="OK";
            }else{
                $result = 1;
                $msg="Failed Unposting Batch Sales Invoice";
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

    public function print_proforma(){
        $input = $this->input->post();
//        pre($input);
        $dt = $this->db->where('docno',$input['docno'])->get('sales_proforma')->row();
        $invoice = json_decode($dt->sales_invoice_data);
        $query = $this->db->select('a.*, c.customer_name, c.address1, c.address2, rg.name as regency_name')
            ->where_in('a.id',$invoice)
            ->join('customer c', 'c.customer_code=a.customer_code')
            ->join('regencies rg', 'rg.id=c.regency_id');
//        pre($sales_inv);

        $data['header'] = $query->get('sales_invoice a')->row();
        $data['detail'] = $query->get('sales_invoice a')->result();
        $this->load->library('pdf');
        $this->pdf->load_view('print/proforma_invoice', $data);
        $this->pdf->render();

        $this->pdf->stream($input['docno'].'.pdf',array("Attachment"=>0));
//        $this->load->view('print/proforma_invoice',$data);
    }

}
