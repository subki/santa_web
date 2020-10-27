<?php

class Customertype_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                select a.code, a.description, a.pkp, a.diskon
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from customer_type a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }

    function get_products($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.product_code product_code, a.product_name, b.customer_type AS type_barang, a.supplier_code
                , ifnull(DATE_FORMAT(b.eff_date, '%d/%m/%Y'),'') as effective
                , ifnull(b.price_pkp,0) price_pkp, ifnull(b.price_tax,0) price_tax
                , ifnull(b.price_non_pkp,0) price_non_pkp
	            from product a 
	            inner join product_price b on a.id = b.product_id
	            GROUP BY a.sku
	            ORDER BY b.eff_date DESC";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.product_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data($code){
        $this->db->where('code',$code);
        return $this->db->get('customer_type');
    }
    function update_data($code, $data){
        $this->db->where('code',$code);
        $this->db->update('customer_type',$data);
    }
    function insert_data($data){
        $this->db->insert('customer_type', $data);
    }
    function delete_data($id){
        $this->db->where('code',$id);
        $this->db->delete('customer_type');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('price_type',$code);
        return $this->db->get('location');
    }
}
