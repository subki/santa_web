<?php

class Wilayah_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.id, a.name
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from provinces a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "id", "asc","", $app, 1);
    }
    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('provinces');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('provinces',$data);
    }
    function insert_data($data){
        $this->db->insert('provinces', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('provinces');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('provinces');
    }
}
