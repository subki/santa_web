<?php

class Location_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0){
        $sql = "create temporary table tmp2 as
                  select a.location_code, a.description
                  , a.pkp, a.price_type, a.check_stock
                  , a.type, ct.description as price_type_name
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from location a 
	            left join customer_type ct on a.price_type=ct.code 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.location_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }

    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "location_code", "asc","", $app, 1);
    }

    function get_list_data_location_close($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.id, a.location, a.periode, a.status_cl
                  , DATE_FORMAT(a.periode, '%d/%b/%Y') prd
	            from closing_location a ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.location) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function get_customer_type(){
        return $this->db->get('customer_type');
    }
    function read_data($code){
        $this->db->where('location_code',$code);
        return $this->db->get('location');
    }
    function update_data($code, $data){
        $this->db->where('location_code',$code);
        $this->db->update('location',$data);
    }
    function insert_data($data){
        $this->db->insert('location', $data);
    }
    function delete_data($id){
        $this->db->where('location_code',$id);
        $this->db->delete('location');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('location_code',$code);
        return $this->db->get('location');
    }
    function read_data_periode($code, $prd){
        return $this->db->query("select * from closing_location where location='$code' and periode like '$prd%'");
    }
    function read_data_periode2($code, $prd){
        return $this->db->query("select * from closing_location where location='$code' and status_cl='Open' and periode not like '$prd%'");
    }
    function read_data_sub($code){
        $this->db->where('id',$code);
        return $this->db->get('closing_location');
    }

    function update_data_sub($code, $data){
        $this->db->where('id',$code);
        $this->db->update('closing_location',$data);
    }
    function insert_data_sub($data){
        $this->db->insert('closing_location', $data);
    }
}
