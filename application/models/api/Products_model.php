<?php

class Products_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_products($outlet_code, $offset, $search, $periode){
	    $sql = "create temporary table temp as 
                select d.discount_id, d.start_date, d.end_date, d.discount1, d.discount2, df.outlet_code, di.sku
                from discount d
                left join discount_for df on d.discount_id=df.discount_id
                left join discount_item di on d.discount_id = di.discount_id
                where now() >= d.start_date
                and now() <= d.end_date
                and d.principle_code in (select o.principle_code from outlet o where o.outlet_code='$outlet_code')
                and df.outlet_code='$outlet_code'
                and di.sku in (select sku from stock where outlet_code='$outlet_code' and periode='$periode')
                group by d.discount_id, df.outlet_code, di.sku";
        $this->db->query($sql);
        $sql = "create temporary table temp2 as 
                  SELECT * from temp group by sku";
        $this->db->query($sql);

        $sql = "select a.sku, a.product_code, a.article_code, a.article_name,
                a.brand, b.description as brand_name, a.class noclass, a.sub_class, a.texture, a.supplier, a.color, a.size,
                a.jenis_barang, a.uom_beli, a.uom_jual, a.uom_stock, a.article_status,
                a.stock_on_hand, a.stock_min, a.stock_max, ifnull(s.saldo_akhir,0) stok, s.unit_price,
                t.discount_id, t.start_date, t.end_date, t.discount1, t.discount2, t.outlet_code
	            from product a
	            inner join stock s on a.sku = s.sku and s.outlet_code = '$outlet_code'
	            left join brand b on a.brand = b.brand_code
	            left join temp2 t on a.sku=t.sku
	            where (
	              a.sku like '%$search%' or 
	              a.product_code like '%$search%' or 
	              a.article_code like '%$search%' or 
	              a.article_name like '%$search%' 
	            )
	            and s.periode = '$periode'
	            and s.outlet_code = '$outlet_code'
	            limit 20 offset $offset";

        $data = $this->db->query($sql);
        $sql = "drop table temp";
        $this->db->query($sql);
        $sql = "drop table temp2";
        $this->db->query($sql);
        return $data;
    }

}
