<?php

class Salesorder_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_order($outlet, $offset, $search, $status){
        $sql = "select a.docno, a.doc_date, a.user_id, e.fullname, a.nomor_struk, a.keterangan, a.status, a.total,
                ifnull(a.discount_id,0) discount_id, a.grand_total, d.barcode, d.discount1 discount,
                a.outlet_code, RTRIM(o.outlet_name) as outlet_name, o.principle_code, p.principle_name
	            from sales_order a
	            left join discount d on a.discount_id = d.discount_id
	            left join employee e on a.user_id = e.user_id
	            left join (
	              outlet o inner join principle p on o.principle_code = p.principle_code
	            )on a.outlet_code = o.outlet_code
	            where (
	              a.docno like '%$search%' or 
	              a.doc_date like '%$search%' or 
	              a.nomor_struk like '%$search%' or 
	              d.barcode like '%$search%' 
	            )
	            and a.outlet_code = '$outlet'";
//                if($status == ""){
//                    $sql .= "and a.status != 'Nota Sementara'";
//                }else{
//                    $sql .= "and a.status = '$status'";
//                }
                if($status != ""){
                    $sql .= " and a.status = '$status'";
                }
	           $sql .=" order by a.docno desc limit 20 offset $offset";
        return $this->db->query($sql);
    }
    function get_order_id($docno){
        $sql = "select a.docno, DATE_FORMAT(a.doc_date,'%Y-%m-%d %T') as doc_date, a.user_id, e.fullname, a.nomor_struk, a.keterangan, a.status, a.total,
                ifnull(a.discount_id,0) discount_id, a.grand_total, d.barcode, d.discount1 discount,
                a.outlet_code
	            from sales_order a
	            left join discount d on a.discount_id = d.discount_id
				left join employee e on a.user_id = e.user_id
	            where a.docno='$docno'";
        return $this->db->query($sql);
    }
    function get_order_detail($docno){
        $sql = "select a.id, a.outlet_code, a.sku, a.discount_id, a.qty, a.unit_price,
                case when a.status='Unpaid' then a.qty
				else a.qty_paid end as qty_paid, a.unit_price_paid, a.sub_total, a.status,
                d.barcode, d.discount1, d.discount2, d.discount3, 
                p.article_code, p.article_name, p.uom_jual uom
	            from sales_order_detail a
	            left join discount d on a.discount_id = d.discount_id
	            left join product p on a.sku = p.sku
	            where a.docno='$docno'";
        return $this->db->query($sql);
    }
    function generate_auto_number(){
        $sql = "SELECT IFNULL(
                       CONCAT('SO',DATE_FORMAT(NOW(),'%y%m'),LPAD(MAX(RIGHT(docno,5))+1,5,'0')),
                       CONCAT('SO',DATE_FORMAT(NOW(),'%y%m'),LPAD(1,5,'0'))
                   ) AS nomor FROM sales_order order by docno desc";
        return $this->db->query($sql)->row()->nomor;
    }
    function insert_header($data){
        $this->db->insert('sales_order', $data);
    }
    function cek_stok($sku, $outlet, $periode){
	    $sql = "select * from stock a
                where outlet_code='$outlet' 
                and periode = '$periode' 
                and sku = '$sku'";
        return $this->db->query($sql);
    }
    function cek_diskon_detail($dtime, $outlet, $sku){
	    $sql = "select a.* 
                from discount a
                left join discount_for df on a.discount_id = df.discount_id
                left join discount_item di on a.discount_id=di.discount_id
                where start_date <= '$dtime' 
                and end_date >= '$dtime' 
                and (df.principal_code = (select o.principle_code from outlet o where o.outlet_code='$outlet') 
                 and df.outlet_code = '$outlet') 
                and di.sku = '$sku'";
        return $this->db->query($sql);
    }
    function insert_detail($data){
        $this->db->insert('sales_order_detail', $data);
    }
    function update_total_belanja($docno){
	    $sql = "update sales_order a 
                set a.total=(select sum(sd.sub_total) 
                  from sales_order_detail sd
                  where sd.docno = a.docno),
                  a.grand_total=(select sum(sd.sub_total) 
                  from sales_order_detail sd
                  where sd.docno = a.docno)
                where a.docno = '$docno'";
        $this->db->query($sql);
    }
    function cek_diskon_header($dtime, $head, $outlet){
        $sql = "select a.* 
                from discount a
                left join discount_for df on a.discount_id = df.discount_id
                left join discount_item di on a.discount_id=di.discount_id
                where discount_type='$head'
                and start_date <= '$dtime' 
                and end_date >= '$dtime' 
                and (df.principal_code = (select o.principle_code from outlet o where o.outlet_code='$outlet') 
                 or df.outlet_code = '$outlet')";
        return $this->db->query($sql);
	}
	function update_header($docno, $arr){
        $this->db->where('docno',$docno);
        $this->db->update('sales_order',$arr);
    }
	function update_stock_jual($periode, $sku, $outlet, $qty){
        $sql = "update stock set penjualan=penjualan+$qty, 
                saldo_akhir=((saldo_awal+do_masuk+pengembalian)-(do_keluar+penjualan))+penyesuaian
                where sku='$sku' and periode='$periode' and outlet_code='$outlet'";
        $this->db->query($sql);
    }
	function update_stock_kembali($periode, $sku, $outlet, $qty){
        $sql = "update stock set pengembalian=pengembalian+$qty, 
                saldo_akhir=((saldo_awal+do_masuk+pengembalian)-(do_keluar+penjualan))+penyesuaian
                where sku='$sku' and periode='$periode' and outlet_code='$outlet'";
        $this->db->query($sql);
    }
    function cek_detail_id($id){
	    $sql = "select * from sales_order_detail where id=$id";
        return $this->db->query($sql);
    }
    function update_detail($id, $data){
        $this->db->where('id',$id);
        $this->db->update('sales_order_detail',$data);
    }


//    function cek_product($outlet_code, $sku){
//        $sql = "select a.sku, a.product_code, a.article_code, a.article_name,
//                a.brand, a.class noclass, a.sub_class, a.texture, a.supplier, a.color, a.size,
//                a.jenis_barang, a.uom_beli, a.uom_jual, a.uom_stock, a.article_status,
//                a.stock_on_hand, a.stock_min, a.stock_max, ifnull(s.saldo_akhir,0) stok, s.unit_price
//	            from product a
//	            left join stock s on a.sku = s.sku and s.outlet_code = '$outlet_code'
//	            where a.sku ='$sku'
//	            and s.outlet_code = '$outlet_code'";
//        return $this->db->query($sql);
//    }


    function cek_product2($outlet_code, $sku, $periode, $tipe){
        $sql = "create temporary table temp as 
                select d.discount_id, d.start_date, d.end_date, d.discount1, d.discount2, df.outlet_code, di.sku
                from discount d
                inner join discount_for df on d.discount_id=df.discount_id
                inner join discount_item di on d.discount_id = di.discount_id
                where now() >= d.start_date
                and now() <= d.end_date
                and d.principle_code in (select o.principle_code from outlet o where o.outlet_code='$outlet_code')
                and df.outlet_code='$outlet_code'
                and di.sku in (select sku from stock where outlet_code='$outlet_code' and periode='$periode') ";
        if($tipe!="") {
            $sql .= " and d.discount_type = '$tipe' ";
        }
                $sql .= " group by d.discount_id, df.outlet_code, di.sku ";
        $this->db->query($sql);

        $sql = "select a.sku, a.product_code, a.article_code, a.article_name,
                a.brand, b.description as brand_name, a.class noclass, a.sub_class, a.texture, a.supplier, a.color, a.size,
                a.jenis_barang, a.uom_beli, a.uom_jual, a.uom_stock, a.article_status,
                a.stock_on_hand, a.stock_min, a.stock_max, ifnull(s.saldo_akhir,0) stok, s.unit_price,
                t.discount_id, t.start_date, t.end_date, t.discount1, t.discount2, s.outlet_code
	            from product a
	            inner join stock s on a.sku = s.sku and s.outlet_code = '$outlet_code'
	            inner join brand b on a.brand = b.brand_code
	            left join temp t on a.sku=t.sku
	            where s.periode = '$periode'
	            and s.outlet_code = '$outlet_code'
	            and s.sku = '$sku'";

        $data = $this->db->query($sql);
        $sql = "drop table temp";
        $this->db->query($sql);
        return $data;
    }

    function cek_product($outlet_code, $sku, $periode){
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
	            where a.sku = '$sku'
	            and s.periode = '$periode'
	            and s.outlet_code = '$outlet_code'";

        $data = $this->db->query($sql);
        $sql = "drop table temp";
        $this->db->query($sql);
        $sql = "drop table temp2";
        $this->db->query($sql);
        return $data;
    }



    function get_list_return($outlet, $offset, $search, $status){
        $sql = "select a.id, a.outlet_code, a.sku, p.article_name, a.unit_price, a.qty, a.tanggal,
                a.status, a.nomor_struk, a.nomor_so
	            from return_customer a
	            left join product p on a.sku = p.sku
	            where (
	              a.sku like '%$search%' or 
	              p.article_name like '%$search%' 
	            )
	            and a.outlet_code = '$outlet'";
        if($status != ""){
            $sql .= "and a.status = '$status'";
        }
        $sql .=" limit 20 offset $offset";
        return $this->db->query($sql);
    }

    function insert_return($data){
        $this->db->insert('return_customer', $data);
    }

    function update_return($id, $data){
        $this->db->where('id',$id);
        $this->db->update('return_customer',$data);
    }

}
