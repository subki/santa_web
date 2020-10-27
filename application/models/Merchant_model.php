<?php

class Merchant_model extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

    function update_data($code, $data){
        $this->db->where('kunci',$code);
        $this->db->update('automatic_config',$data);
    }
}
