<?php

class Productbrand_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0){
        $sql = "create temporary table tmp as
                  select a.brand_code, a.description, a.jenis_barang
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_brand a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.brand_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }

    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "brand_code", "asc","", $app, 1);
    }

    function read_data($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product_brand');
    }
    function update_data($code, $data){
        $this->db->where('brand_code',$code);
        $this->db->update('product_brand',$data);
    }
    function insert_data($data){
        $this->db->insert('product_brand', $data);
    }
    function delete_data($id){
        $this->db->where('brand_code',$id);
        $this->db->delete('product_brand');
    }
    function read_transactions($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product');
    }
}
