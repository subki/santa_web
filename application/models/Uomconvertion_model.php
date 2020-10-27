<?php

class Uomconvertion_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0){
        $sql = "create temporary table temp as 
                select a.id, a.uom_from, a.uom_to
                , p1.description as uom_from_desc
                , p2.description as uom_to_desc
                , p1.uom_id as code1, p2.uom_id as code2
                , a.convertion, a.status
                , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_uom_convertion a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id 
	            LEFT JOIN product_uom p1 on a.uom_from=p1.uom_code 
	            LEFT JOIN product_uom p2 on a.uom_to=p2.uom_code ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from temp ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.uom_from) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "uom_from", "asc","", $app, 1);
    }

    function get_uom(){
        $this->db->where('status','Approved');
        return $this->db->get('product_uom');
    }
    function read_data($code,$code2){
        $this->db->where('uom_from',$code);
        $this->db->where('uom_to',$code2);
        return $this->db->get('product_uom_convertion');
    }
    function read_data2($code){
        $this->db->where('id',$code);
        return $this->db->get('product_uom_convertion');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('product_uom_convertion',$data);
    }
    function insert_data($data){
        $this->db->insert('product_uom_convertion', $data);
    }
    function delete_data($id,$id2){
        $this->db->where('uom_from',$id);
        $this->db->where('uom_to',$id2);
        $this->db->delete('product_uom_convertion');
    }
    function read_transactions($code,$code2){
	    //nanti diubah
        $this->db->where('satuan_jual',$code);
//        $this->db->where('uom_to',$code2);
        return $this->db->get('product');
    }
}
