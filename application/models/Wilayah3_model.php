<?php

class Wilayah3_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "select a.*,
	            (select count(a1.id) from districts a1 ) as total
	            from districts a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('districts');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('districts',$data);
    }
    function insert_data($data){
        $this->db->insert('districts', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('districts');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('districts');
    }
}
