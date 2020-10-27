<?php

class Log_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('log_update');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('log_update',$data);
    }
    function insert_data($data){
        $this->db->insert('log_update', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('log_update');
    }
}
