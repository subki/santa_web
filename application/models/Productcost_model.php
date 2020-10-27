<?php

class Productcost_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as 
                select a.purchase_market, a.product_id, p.sku, p.product_name, a.id
                  , a.periode
                  , a.hpp, a.cost1, a.cost2, a.cost3, a.hpp_end
                  , (a.hpp+a.cost1)*a.cost2/100 as cost2_amt
                  , ((a.hpp+a.cost1)*a.cost2/100)*a.cost3/100 cost3_amt
                  , DATE_FORMAT(a.periode, '%d/%m/%Y') periode_ak
	            from add_cost_uom_stock a 
	            left join product p on a.product_id=p.id ";
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

//    function get_customer_type(){
//        return $this->db->get('customer_type');
//    }
    function get_product($sku){
        $this->db->where('id',$sku);
        return $this->db->get('product');
    }
    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('add_cost_uom_stock');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('add_cost_uom_stock',$data);
    }
    function check_insert($product_id, $purchase_market, $periode){
        $this->db->where('product_id',$product_id);
        $this->db->where('purchase_market',$purchase_market);
        $this->db->where('periode',$periode);
        return $this->db->get('add_cost_uom_stock');
    }
    function insert_data($data){
        $this->db->insert('add_cost_uom_stock', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('add_cost_uom_stock');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('product_price');
    }
}
