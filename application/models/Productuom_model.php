<?php

class Productuom_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.uom_code, a.uom_id, a.description
                  , a.default_unit, a.status
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_uom a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.uom_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }

    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "uom_id", "asc","", $app, 1);
    }

    function read_data($code){
        $this->db->where('uom_code',$code);
        return $this->db->get('product_uom');
    }
    function read_data2($code){
        $this->db->where('uom_id',$code);
        return $this->db->get('product_uom');
    }
    function update_data($code, $data){
        $this->db->where('uom_code',$code);
        $this->db->update('product_uom',$data);
    }
    function insert_data($data){
        $this->db->insert('product_uom', $data);
        $insertId = $this->db->insert_id();
        return  $insertId;
    }
    function insert_data_convertion($data){
        $this->db->insert('product_uom_convertion', $data);
    }
    function delete_data($id){
        $this->db->where('uom_code',$id);
        $this->db->delete('product_uom');
    }
    function read_transactions($code){
	    //nanti diubah
		$sql = "select * from product where satuan_beli='$code' or satuan_stock='$code' or satuan_jual='$code'";
		return $this->db->query($sql);
        //$this->db->where('satuan_jual',$code);
        //return $this->db->get('product');
    }
}
