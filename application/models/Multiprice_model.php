<?php

class Multiprice_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as 
                select a.product_id, p.sku, p.product_name, a.id, a.customer_type, b.description
                  , a.eff_date as tanggalan
                  , DATE_FORMAT(a.eff_date, '%d/%m/%Y') eff_date
                  , DATE_FORMAT(a.eff_date, '%d/%b/%Y') eff_date2
                  , a.price_non_pkp, a.price_tax, a.price_pkp
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_price a 
	            left join customer_type b on a.customer_type=b.code 
	            left join product p on a.product_id=p.id 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.product_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_customer_type(){
        return $this->db->get('customer_type');
    }
    function get_product($sku){
        $this->db->where('id',$sku);
        return $this->db->get('product');
    }
    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('product_price');
    }
    function read_product($field, $code){
        $this->db->where($field,$code);
        return $this->db->get('product');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('product_price',$data);
    }
    function check_insert($product_id, $customer_type, $eff){
        $this->db->where('product_id',$product_id);
        $this->db->where('customer_type',$customer_type);
        $this->db->where('eff_date',$eff);
        return $this->db->get('product_price');
    }
    function insert_data($data){
        $this->db->insert('product_price', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('product_price');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('product_price');
    }
}
