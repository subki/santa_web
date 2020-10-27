<?php

class Productgroup_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0){
        $sql = "create temporary table tmp2 as
                  select a.class_code, a.description, a.addcost
                  , a.jenis_barang
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_class a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.class_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data_2($app){
        return $this->get_list_data(1, 999999999999, "class_code", "asc","", $app, 1);
    }
    function get_list_data2($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.subclass_code, a.class_code
                  , a.description
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_subclass a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.subclass_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data_sub2($app){
        return $this->get_list_data2(1, 999999999999, "class_code", "asc","", $app, 1);
    }

    function read_data($code){
        $this->db->where('class_code',$code);
        return $this->db->get('product_class');
    }
    function update_data($code, $data){
        $this->db->where('class_code',$code);
        $this->db->update('product_class',$data);
    }
    function insert_data($data){
        $this->db->insert('product_class', $data);
    }
    function delete_data($id){
        $this->db->where('class_code',$id);
        $this->db->delete('product_class');


        $this->db->where('class_code',$id);
        $this->db->delete('product_subclass');
    }
    function read_transactions($code){
        //nanti diubah
        $this->db->where('class_code',$code);
        return $this->db->get('product');
    }
    function generate_auto_number($class_code){
        $sql = "SELECT IFNULL(
                    RIGHT(CONCAT('000',LPAD(MAX(RIGHT(subclass_code,3))+1,3,'0')),3),
                    RIGHT(CONCAT('000',LPAD(1,3,'0')),3)
                ) AS nomor FROM product_subclass WHERE class_code='$class_code' order by subclass_code desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function read_data2($code,$code2){
        $this->db->where('class_code',$code);
        $this->db->where('subclass_code',$code2);
        return $this->db->get('product_subclass');
    }
    function update_data2($code,$code2, $data){
        $this->db->where('class_code',$code);
        $this->db->where('subclass_code',$code2);
        $this->db->update('product_subclass',$data);
    }
    function insert_data2($data){
        $this->db->insert('product_subclass', $data);
    }
    function delete_data2($id,$code){
        $this->db->where('class_code',$id);
        $this->db->where('subclass_code',$code);
        $this->db->delete('product_subclass');
    }
    function read_transactions2($code,$code2){
        //nanti diubah
        $this->db->where('class_code',$code);
        $this->db->where('subclass_code',$code2);
        return $this->db->get('product');
    }
}
