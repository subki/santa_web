<?php

class Module_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "select a.app_id, a.app_name, ifnull(a.description,'') description
                , a.url, a.icon, a.parent_id, a.seq
	            , (select count(a1.app_id) from app a1 ) as total
	            from app a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_app(){
        $sql = "
            select 
                a.app_id, 
                a.app_name, 
                a.parent_id
            from app a
            order by a.app_id
        ";
        return $this->db->query($sql)->result();
    }
    function get_app_parent($parent_id){
        $sql = "
            select 
                a.app_id
            from app a
            where a.parent_id = '$parent_id'
            order by a.seq asc
        ";
        return $this->db->query($sql)->result();
    }
    function get_new_app_id($parent_id){
        $sql = "select right(app_id,2) no, ifnull(seq+1, 1) as seq from app where parent_id='$parent_id' order by app_id desc limit 1";
        return $this->db->query($sql)->row();
    }
    function insert_data($data){
        $this->db->insert('app', $data);
    }
    function update_data($code, $data){
        $this->db->where('app_id',$code);
        $this->db->update('app',$data);
    }
    function read_data($code){
        $this->db->where('app_id',$code);
        return $this->db->get('app');
    }
    function delete_data($id){
        $this->db->where('app_id',$id);
        $this->db->delete('app');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('app_id',$code);
        return $this->db->get('app');
    }
    function update_data_seq_minus($parent_id, $seq_lama, $seq_baru){
	    $sql = "update app set seq=seq-1 where parent_id='$parent_id' and seq > $seq_lama and seq <= $seq_baru";
	    $this->db->query($sql);
    }
    function update_data_seq_plus($parent_id, $seq_lama, $seq_baru){
	    $sql = "update app set seq=seq+1 where parent_id='$parent_id' and seq < $seq_lama and seq >= $seq_baru";
	    $this->db->query($sql);
    }
}
