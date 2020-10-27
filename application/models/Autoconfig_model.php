<?php

class Autoconfig_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0){
        $sql = "create temporary table tmp as
                  select a.id, a.kunci, a.nilai
	            from automatic_config a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);
		
        $sql = "select a.*,
	            (select count(a1.id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        $data = $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
		
        $sql = "drop table tmp";
        $this->db->query($sql);
		
		return $data;
    }
    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('automatic_config');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('automatic_config',$data);
    }
   
}
