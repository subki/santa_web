<?php

class Product_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = " select a.product_id id, a.nobar, a.nmbar
                  , a.warna, a.soh, a.min_stock, a.max_stock
                  , a.user_crt, a.date_crt, a.time_crt
	              , (select count(a1.product_id) from product_barang a1 ) as total
	            from product_barang a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_colour($code){
        $sql = "select a.art_colour_code, b.description
                from article_colour a 
                left join product_colour b on a.art_colour_code=b.colour_code 
                where a.article_code = '$code'
                /*AND a.art_colour_code NOT IN(
                    SELECT a1.warna 
                    FROM product_barang a1 
                    INNER JOIN product a2 ON a1.product_id=a2.id 
                    WHERE a2.article_code='$code'
                )*/
                and b.status='Approved' ";
        return $this->db->query($sql);
    }
    function get_product($sku){
        $this->db->where('id',$sku);
        return $this->db->get('product');
    }
    function get_product_by_sku($sku){
        $this->db->where('sku',$sku);
        return $this->db->get('product');
    }
    function read_data($code){
        $this->db->where('nobar',$code);
        return $this->db->get('product_barang');
    }
    function update_data($code, $data){
        $this->db->where('nobar',$code);
        $this->db->update('product_barang',$data);
    }
    function insert_data($data){
        $this->db->insert('product_barang', $data);
    }
    function update_header($sku){
        $sql ="UPDATE product a
               SET a.total_soh = IFNULL((select sum(b.soh) from product_barang b where b.product_id='$sku' group by b.product_id),0)
                        , a.min_stock = IFNULL((select sum(b.min_stock) from product_barang b where b.product_id='$sku' group by b.product_id),0)
                        , a.max_stock = IFNULL((select sum(b.max_stock) from product_barang b where b.product_id='$sku' group by b.product_id),0)
                WHERE a.id = '$sku'";
        $this->db->query($sql);
    }
    function insert_data_article_size_colour($data){
//        $this->db->insert('article_size_colour', $data);
    }
    function delete_data($id){
        $this->db->where('nobar',$id);
        $this->db->delete('product_barang');

//        $this->db->where('nobar',$id);
//        $this->db->delete('article_size_colour');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('product');
    }
    function generate_auto_number($sku){
        $sql = "SELECT CONCAT(b.sku,RIGHT(CONCAT('00',CAST(right(nobar,2) AS UNSIGNED)+1),2)) AS nomor
                FROM product_barang a
                INNER JOIN product b on b.id=a.product_id
                where a.product_id in(select c.id from product c where c.sku='$sku') order by nobar desc";
        return $this->db->query($sql);
    }

    function cek_article($code){
        $sql = "select left(sku,8) as sku, RIGHT(CONCAT('00',CAST(right(sku,2) AS UNSIGNED)+1),2) as seq from product where article_code='$code' order by sku desc";
        return $this->db->query($sql);
    }
}
