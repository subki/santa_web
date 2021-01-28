<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashbankbalance extends IO_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index(){
		$data['title_main'] = 'Cash Bank Balance';
		$data['content'] = $this->load->view('fa/cashbankbalance', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function grid(){
		$total1 = $this->getParamGrid_BuilderComplete(array(
			"table"=>"master_rekening cb",
			"rows"=>100,
			"sortir"=>"cb.id",
			"select"=>"cb.accno, cb.tipe_rekening, cb.cbaccno, cb.accname, ch.tahun, ch.bulan
								, SUM(CASE WHEN dbcr='DEBET' THEN payment_amount ELSE 0 END) AS debet
								, SUM(CASE WHEN dbcr='CREDIT' THEN payment_amount ELSE 0 END) AS credit",
			"join"=>["cashbank_history ch"=>"ch.no_cb=cb.cbaccno"],
			"posisi"=>["left"],
			"group"=>["cb.cbaccno"]
		));
		$total = $total1->total;
		$data = $total1->data;
		echo json_encode(array(
				"total"=>$total,
				"rows" =>$data)
		);
	}
	public function grid_detail(){
		$total1 = $this->getParamGrid_BuilderComplete(array(
			"table"=>"cashbank_history ch",
			"sortir"=>"ch.payment_date",
			"select"=>"ch.no_cb, ch.docno, ch.payment_date, ch.cbtype, CONCAT(cd.cost_center,cd.gl_account) AS account, cd.remark
								, ch.tahun, ch.bulan
								, CASE WHEN cd.dbcr='DEBET' THEN cd.payment_amt ELSE 0 END AS debet
								, CASE WHEN cd.dbcr='CREDIT' THEN cd.payment_amt ELSE 0 END AS credit
								, case when ch.payment_type='AP PAYMENT' then sp.supplier_name when ch.payment_type='AR RECEIPT' then c.customer_name ELSE '' END as customer_vendor",
			"join"=>["cashbank_detail cd"=>"cd.cbhistoryid=ch.id","customer c"=>"c.customer_code=ch.customer_code","supplier sp"=>"sp.supplier_code=ch.customer_code"],
			"posisi"=>["inner","left","left"]
		));
		$total = $total1->total;
		$data = $total1->data;
		echo json_encode(array(
				"total"=>$total,
				"rows" =>$data)
		);
	}
}
