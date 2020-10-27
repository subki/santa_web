<?php

class Location_monitoring_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table temp as 
                select a.*, b.description,
	            (select count(a1.id) from closing_location a1 ) as total
	            from closing_location a 
	            left join location b on a.location = b.location_code";
        $this->db->query($sql);
        $sql = "select * from temp ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data_code($code){
        $this->db->where('location',$code);
        return $this->db->get('closing_location');
    }
    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('closing_location');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('closing_location',$data);
    }
    function insert_data($data){
        $this->db->insert('closing_location', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('closing_location');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('closing_location');
    }
}
