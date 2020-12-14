<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Hpp extends IO_Controller {

	var $table;
	var $table_field;
	function __construct(){
		parent::__construct();
		$this->table = "article_hpp";
		$this->table_field = ["id","article_code","opsi","tipe","keterangan", "effdate", "product_qty", "product_price"
			, "disc1_persen", "disc1_amt", "disc2_persen", "disc2_amt", "product_amount", "product_pcs", "bom_pcs"
			, "foh_pcs", "ongkos_jahit_pcs", "hpp1", "interest_cost", "interest_cost_amt", "buffer_cost", "buffer_cost_amt"
			, "hpp2", "ekspedisi", "hpp_ekspedisi"];
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($article_code){
		$item = $this->db->select('article_code,article_name,opsi,tipe')->get_where("article",["article_code"=>$article_code])->row();
		$data['title'] = 'Article HPP History';
		$data['article_code'] = $article_code;
		$data['item'] = $item;
		$data['opsi'][] = ["id"=>1,"description"=>"OPSI 1"];
		$data['opsi'][] = ["id"=>2,"description"=>"OPSI 2"];
		$data['opsi'][] = ["id"=>3,"description"=>"OPSI 3"];
		$data['tipe'][] = ["id"=>"LOCAL","description"=>"LOCAL"];
		$data['tipe'][] = ["id"=>"IMPORT","description"=>"IMPORT"];
		$data['content'] = $this->load->view('hpp/index', $data, TRUE);
		$this->load->view('main', $data);
	}

	public function grid(){
		$total = $this->db->get($this->table)->num_rows();
		$this->db->select("a.*, b.article_name");
		$this->getParamGrid_Builder("","id");
		$this->db->join("article b", "b.article_code=a.article_code");
		$data = $this->db->get($this->table." a")->result();
		echo json_encode(array(
				"status" => 1,
				"msg" => "OK",
				"total"=>$total,
				"data" =>$data)
		);
	}

	public function entryp(){
		$param = $this->toUpper($this->input->post());
		$param['effdate'] = $this->formatDate("Y-m-d",$param['effdate']);
		$param['article_code'] = $param['header']['article_code'];
//		pre($param);
		foreach ($param as $key=> $r){
			if(!in_array($key,$this->table_field)) unset($param[$key]);
		}

//		pre($param);
		$this->db->trans_start();
		if($param['id']==""||$param['id']=="0"||$param['id']==0){
			unset($param['id']);
			$param['crtdt'] = date('Y-m-d H:i:s');
			$param['crtby'] = $this->session->userdata('user_id');
//			pre($input);
			$this->db->insert($this->table, $param);
			$param['id'] = $this->db->insert_id();
//			pre($input);
		}else{
			$param['upddt'] = date('Y-m-d H:i:s');
			$param['updby'] = $this->session->userdata('user_id');
			$this->db->update($this->table,$param,["id"=>$param['id']]);
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			$this->set_error("Insert failed");
		}else{
			$this->set_success("Add/Edit success...");
		}
		redirect("hpp/index/".$param['article_code']);
	}
}
