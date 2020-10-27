<?php

class Attachment_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function read_data($code,$tabel){
        $this->db->where('docno',$code);
        $this->db->where('tabel',$tabel);
        return $this->db->get('attachment');
    }
    function insert_data($data){
        $this->db->insert('attachment', $data);
    }
}
