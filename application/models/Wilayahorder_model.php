<?php

class Wilayahorder_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as 
                select a.regency_id, a.nilai_minimal, p.name as kota, a.id, a.customer_type, b.description
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from wilayah_by_amount a 
	            left join customer_type b on a.customer_type=b.code 
	            left join regencies p on a.regency_id=p.id 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.regency_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_customer_type(){
        return $this->db->get('customer_type');
    }
    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('wilayah_by_amount');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('wilayah_by_amount',$data);
    }
    function check_insert($product_id, $customer_type){
        $this->db->where('regency_id',$product_id);
        $this->db->where('customer_type',$customer_type);
        return $this->db->get('wilayah_by_amount');
    }
    function insert_data($data){
        $this->db->insert('wilayah_by_amount', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('wilayah_by_amount');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('wilayah_by_amount');
    }
}
