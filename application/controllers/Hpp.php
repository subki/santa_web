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
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function index($article_code){
		$data['title'] = 'Article HPP History';
		$data['article_code'] = $article_code;
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
}
