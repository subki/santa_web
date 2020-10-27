<?php

class Delivery_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_DO($outlet_code, $offset, $search, $tipe){
	    $sql = "CREATE TEMPORARY TABLE temp AS
                SELECT a.docno, a.trx_no, a.outlet_id, a.do_type, a.status, 
                    a.outlet_src, date_format(str_to_date(a.trx_date,'%d/%m/%Y'),'%Y-%m-%d') as trx_date
                from do_header a";
        $this->db->query($sql);

        $sql = "select a.docno, a.trx_no, a.outlet_id, a.do_type, a.status, 
	              a.outlet_src, date_format(a.trx_date, '%d/%m/%Y') as trx_date
	            from temp a
	            where (
	              a.trx_no like '%$search%' or 
	              a.docno like '%$search%'
	            )
	            and
	             (a.outlet_id = '$outlet_code'
	              OR a.outlet_src = '$outlet_code')
	            and a.do_type = '$tipe'
	            order by a.trx_date desc, a.docno desc
	            limit 20 offset $offset";
        $data = $this->db->query($sql);
        $sql = "drop table temp";
        $this->db->query($sql);
        return $data;
    }

    function get_header_do($docno){
	    $sql = "select * from do_header where docno='$docno'";
	    return $this->db->query($sql);
    }

    function get_detail_do($docno){
        $sql = "select a.id, a.docno, a.trx_no, a.brand_code, a.sku,
                a.article_code, a.uom, a.qty_rcv, 
                a.qty, a.unit_price, b.description brand, p.article_name 
                from do_detail a
                left join brand b on a.brand_code = b.brand_code
                left join product p on a.sku = p.sku
                where a.docno='$docno'";
        return $this->db->query($sql);
    }
    function get_detail_do_sku($docno,$sku){
        $sql = "select *
                from do_detail a
                where a.docno='$docno'
                and a.sku='$sku'";
        return $this->db->query($sql);
    }
    function update_detail_do($docno,$trx_no,$sku,$qty_rcv){
        $sql = "update do_detail set qty_rcv=$qty_rcv
                where docno='$docno'
                and trx_no='$trx_no'
                and sku='$sku'";
        return $this->db->query($sql);
    }
    function get_product($sku){
        $sql = "select * from product where sku='$sku'";
        return $this->db->query($sql);
    }

    function insert_detail_do($data){
        $this->db->insert('do_detail', $data);
    }
    function cek_stock($outlet, $sku, $periode){
        $sql = "select * from stock 
                where sku='$sku' 
                and outlet_code='$outlet'
                and periode='$periode'";
        return $this->db->query($sql);
    }
    function update_stok_sku($sku, $outlet, $periode, $qty_rcv){
        $sql = "update stock set do_masuk=do_masuk+$qty_rcv,
                saldo_akhir = ((saldo_awal+do_masuk+pengembalian) - (do_keluar+penjualan))+penyesuaian
                where sku='$sku'
                and outlet_code='$outlet'
                and periode='$periode'";
        return $this->db->query($sql);
    }
    function cek_stock_before($outlet, $sku){
        $sql = "select * from stock 
                where sku='$sku' 
                and outlet_code='$outlet'
                limit 1";
        return $this->db->query($sql);
    }
    function create_new_stock($stok){
        $this->db->insert('stock', $stok);
    }
    function delete_do_header($docno){
        $this->db->where('docno',$docno);
        $this->db->delete('do_header');

        $this->db->where('docno',$docno);
        $this->db->delete('do_detail');
    }
    function delete_do_detail($docno,$id){
        $this->db->where('id',$id);
        $this->db->where('docno',$docno);
        $this->db->delete('do_detail');
    }
}
