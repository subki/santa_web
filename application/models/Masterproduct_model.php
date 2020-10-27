<?php

class Masterproduct_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0, $kel=""){
//	    $sql = "create temporary table tmp as
//                  select product_id, purchase_market
//                  from add_cost_uom_stock
//                  group by product_id order by periode desc ";
//            $this->db->query($sql);
        $sql = "create temporary table temp as 
                select a.id, a.sku, a.product_code, a.article_code, aa.article_name
                  , a.product_name
                  , a.brand_code, b.description brand_name
                  , a.class_code, c.description class_name
                  , a.subclass_code, d.description subclass_name
                  , a.type_barang
				  , a.supplier_code, f.supplier_name
				  , a.size_code, e.description size_name
                  , a.jenis_barang
                  , a.satuan_beli
                  , u1.description unit_beli
                  , a.satuan_stock
                  , a.satuan_jual
                  , a.status_product, a.total_soh, a.min_stock, a.max_stock
                  , DATE_FORMAT(a.first_production, '%d/%m/%Y') first_production
                  , DATE_FORMAT(a.last_production, '%d/%m/%Y') last_production
                  , a.avg_cost, a.price_h1
                  , (SELECT IFNULL(purchase_market,'') FROM add_cost_uom_stock WHERE product_id=a.id AND periode<=NOW() AND NOW()<=periode) AS purchase_market
                  , CONCAT('". base_url()."','assets/images/',atc.path,atc.filename) AS gambar
                  , a.sales_market
                  , ifnull(us1.fullname,a.crtby) as crtby, ifnull(us2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , u2.description unit_stock, u3.description unit_jual
                  , a.colour_code
	            from product a 
	            LEFT JOIN attachment atc ON a.article_code=atc.docno AND atc.tabel='article'
	            LEFT JOIN article aa ON a.article_code=aa.article_code
	            left join product_brand b on a.brand_code=b.brand_code
	            left join product_class c on a.class_code=c.class_code
				left join product_subclass d on a.subclass_code=d.subclass_code and c.class_code=d.class_code
				left join product_size e on a.size_code=e.size_code
				left join supplier f on a.supplier_code=f.supplier_code 
				left join product_uom u1 on a.satuan_beli=u1.uom_code
				left join product_uom u2 on a.satuan_stock=u2.uom_code
				left join product_uom u3 on a.satuan_jual=u3.uom_code
	            left join users us1 on a.crtby=us1.user_id
	            left join users us2 on a.updby=us2.user_id 
	            where a.jenis_barang='$kel' ";
            $this->db->query($sql);
//        $sql = "create temporary table temp1 as
//                  select a.*
//                  , u1.description unit_stock
//                    from temp a
//				left join product_uom u1 on a.satuan_stock=u1.uom_code";
//        $this->db->query($sql);
//        $sql = "create temporary table temp2 as
//                  select a.*
//                  , u1.description unit_jual
//                    from temp1 a
//				left join product_uom u1 on a.satuan_jual=u1.uom_code";
//        $this->db->query($sql);
//        $sql = "create temporary table temp3 as
//                  select a.*
//                  , (select convertion
//                      from product_uom_convertion
//                      where uom_from=a.satuan_jual
//                      and uom_to=(select uom_code from product_uom where default_unit=1)
//                      limit 1
//                    ) as convertion
//                    from temp a";
//        $this->db->query($sql);
        $sql = "create temporary table temp4 as
                  select a.*
	              from temp a ";
        if($fltr!=''){
            $sql .= $fltr;
        }else  $sql .= " where 1=1; ";
        $this->db->query($sql);
        $sql ="select a.jenis_barang, a.id, a.sku, a.product_code, a.product_name, a.article_code
                  , a.brand_name, a.size_name, a.class_name, a.subclass_name, a.supplier_name
                  , ifnull(a.unit_jual,'')unit_jual, ifnull(a.unit_beli,'') unit_beli, ifnull(a.unit_stock,'') unit_stock, ifnull(a.total_soh,0) total_soh, a.status_product
                  , a.purchase_market, a.crtby, a.updby, a.crtdt, a.upddt
                  
                  , a.brand_code, a.class_code, a.subclass_code, a.colour_code
                  , a.type_barang, a.supplier_code, a.size_code
                  , a.satuan_beli, a.satuan_stock, a.satuan_jual
                  , a.min_stock, a.max_stock, a.first_production
                  , a.last_production, a.avg_cost, a.price_h1, a.sales_market
                  , a.tanggal_crt, a.tanggal_upd, ifnull(a.gambar,'') gambar
	              , (select count(a1.sku) from temp4 a1 ) as total  from temp4 a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }

    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "article_code", "asc","", $app, 1);
    }

    function stock_sku($page,$rows,$sort,$order,$role,$fltr, $code, $prd){
        $sql = "create temporary table tmp2 as 
                SELECT a.nobar, b.nmbar, SUM(a.saldo_akhir) soh
                FROM stock a
                LEFT JOIN product_barang b ON a.nobar=b.nobar
                WHERE a.nobar IN (SELECT nobar FROM product_barang WHERE product_id='$code')
                AND a.periode='$prd'
                GROUP BY a.nobar  
	             ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.nobar) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function stock_location_d($page,$rows,$sort,$order,$role,$fltr, $product_id, $location_code, $prd){
        $sql = "create temporary table tmp2 as 
                SELECT a.nobar, b.nmbar, SUM(a.saldo_akhir) soh
                FROM stock a
                LEFT JOIN product_barang b ON a.nobar=b.nobar
                WHERE a.nobar IN (SELECT nobar FROM product_barang WHERE product_id='$product_id')
                AND a.periode='$prd'
                AND a.location_code='$location_code'
                GROUP BY a.nobar,a.location_code  
	             ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.nobar) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function stock_location($page,$rows,$sort,$order,$role,$fltr,$code, $prd){
        $sql = "create temporary table tmp2 as 
                SELECT a.location_code, b.description location_name, SUM(a.saldo_akhir) soh
                FROM stock a
                LEFT JOIN location b ON a.location_code=b.location_code
                WHERE a.nobar IN (SELECT nobar FROM product_barang WHERE product_id='$code')
                AND a.periode='$prd'
                GROUP BY a.location_code  
	             ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.location_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_mutasi($page,$rows,$sort,$order,$role,$fltr,$nobar, $loc){
        $sql = "create temporary table tmp2 as 
                SELECT a.periode, a.saldo_awal, a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir
                FROM stock a
                WHERE a.nobar = '$nobar'
                AND a.location_code = '$loc'
	             ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.periode) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_mutasi_trx($page,$rows,$sort,$order,$role,$fltr,$periode,$location,$nobar){
        $sql = "create temporary table tmp2 as 

                /*SELECT a.doc_date as tanggal, DATE_FORMAT(a.doc_date, '%T') jam, 'DO Out' as tipe, a.docno as trx
                , CASE WHEN a.status = 'RECEIVED' THEN SUM(b.qty)*-1 ELSE 0 END as qty
                FROM do_header a
                INNER JOIN do_detail b ON a.docno=b.docno AND b.nobar='$nobar'
                WHERE a.from_location_code='$location'
                AND DATE_FORMAT(a.doc_date, '%Y%m')='$periode'
                AND a.do_type='DO'
                GROUP BY a.docno
                UNION*/
                SELECT trx_date as tanggal, trx_time as jam, trx_type as tipe, trx_no as trx, qty, remark
                FROM product_history
                WHERE location_code='$location'
                AND DATE_FORMAT(trx_date, '%Y%m')='$periode'
                AND sku='$nobar'
                /*UNION*/
                /*SELECT a.receive_date as tanggal, DATE_FORMAT(a.receive_date, '%T') jam, 'DO In' as tipe, a.docno as trx
                , CASE WHEN a.status = 'RECEIVED' THEN SUM(b.qty) ELSE 0 END as qty
                FROM do_header a
                INNER JOIN do_detail b ON a.docno=b.docno AND b.nobar='$nobar'
                WHERE a.to_location_code='$location'
                AND DATE_FORMAT(a.doc_date, '%Y%m')='$periode'
                AND a.do_type='DO'
                GROUP BY a.docno
                UNION*/
                /*SELECT trx_date, trx_time, trx_type, trx_no, SUM(qty)
                FROM product_history
                WHERE location_code='$location'
                AND DATE_FORMAT(trx_date, '%Y%m')='$periode'
                AND trx_type IN('DO IN','RECEIVING')
                AND sku='$nobar'
                GROUP BY trx_no
                UNION*/
                /*SELECT a.doc_date as tanggal, DATE_FORMAT(a.crtdt, '%T') jam, 'Adjustment' as tipe, a.docno as trx
                , CASE WHEN a.status = 'APPROVED' THEN SUM(b.qty) ELSE 0 END as qty
                FROM do_header a
                INNER JOIN do_detail b ON a.docno=b.docno AND b.nobar='$nobar'
                WHERE a.from_location_code='$location'
                AND DATE_FORMAT(a.doc_date, '%Y%m')='$periode'
                AND a.do_type='ADJ'
                GROUP BY a.docno
                UNION*/
                /*SELECT trx_date, trx_time, trx_type, trx_no, SUM(qty)
                FROM product_history
                WHERE location_code='$location'
                AND DATE_FORMAT(trx_date, '%Y%m')='$periode'
                AND trx_type='ADJUSTMENT'
                AND sku='$nobar'
                GROUP BY trx_no
                UNION*/
                /*SELECT a.doc_date as tanggal, DATE_FORMAT(a.crtdt, '%T') jam, 'Transfer Out' as tipe, a.docno as trx
                , CASE WHEN a.status = 'APPROVED' THEN SUM(b.qty)*-1 ELSE 0 END as qty
                FROM do_header a
                INNER JOIN do_detail b ON a.docno=b.docno AND b.nobar='$nobar'
                WHERE a.from_location_code='$location'
                AND DATE_FORMAT(a.doc_date, '%Y%m')='$periode'
                AND a.do_type='TRF'
                GROUP BY a.docno
                UNION*/
                /*SELECT a.doc_date as tanggal, DATE_FORMAT(a.crtdt, '%T') jam, 'Transfer In' as tipe, a.docno as trx
                , CASE WHEN a.status = 'APPROVED' THEN SUM(b.qty) ELSE 0 END as qty
                FROM do_header a
                INNER JOIN do_detail b ON a.docno=b.docno AND b.nobar='$nobar'
                WHERE a.to_location_code='$location'
                AND DATE_FORMAT(a.doc_date, '%Y%m')='$periode'
                AND a.do_type='TRF'
                GROUP BY a.docno
                UNION*/
                /*SELECT trx_date, trx_time, trx_type, trx_no, SUM(qty)
                FROM product_history
                WHERE location_code='$location'
                AND DATE_FORMAT(trx_date, '%Y%m')='$periode'
                AND trx_type='PENJUALAN'
                AND sku='$nobar'
                GROUP BY trx_no
                UNION
                SELECT trx_date, trx_time, trx_type, trx_no, SUM(qty)
                FROM product_history
                WHERE location_code='$location'
                AND DATE_FORMAT(trx_date, '%Y%m')='$periode'
                AND trx_type='RETURN PENJUALAN'
                AND sku='$nobar'
                GROUP BY trx_no*/
	             ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.tanggal, DATE_FORMAT(a.tanggal, '%d/%b/%Y') tanggal2, a.jam, a.tipe, a.trx, a.qty, a.remark,
	            (select count(a1.tanggal) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_uom(){
        $id =$this->session->userdata('uom stock');
        $sql = "select a.*
                , b.convertion 
                from product_uom a
                LEFT JOIN product_uom_convertion b ON a.uom_code=b.uom_from AND b.uom_to=$id
                where a.status='Approved'";
        return $this->db->query($sql);
    }
    function get_size($code){
        $sql = "select a.art_size_code as size_code, concat(b.size_code,' - ', b.description) description
              , (select size_code from product where article_code='$code' and size_code=a.art_size_code) as sel
              from article_size a
              left join product_size b on a.art_size_code = b.size_code
              where a.article_code='$code' 
              and b.status='Approved' ";
        return $this->db->query($sql);
    }
    function get_colour(){
        $this->db->where('status','Approved');
        return $this->db->get('product_colour');
    }
    function get_supplier(){
        $this->db->where('status','Aktif');
        return $this->db->get('supplier');
    }
    function get_customer_type(){
        $sql = "select code as type_barang, description from customer_type";
        return $this->db->query($sql);
    }
    function get_subclass($code){
        $this->db->where('class_code',$code);
        return $this->db->get('product_subclass');
    }
    function get_class(){
        return $this->db->get('product_class');
    }
    function get_brand(){
        return $this->db->get('product_brand');
    }
    function get_article($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as 
                    SELECT a.article_code, a.article_name, b.art_size_code as size_code, b1.description as size_name
                    , CONCAT(a.article_code,b.art_size_code) con_art
                    , IFNULL(CONCAT(c.article_code,c.size_code),'') con_brg
                    FROM article a
                    LEFT JOIN (article_size b INNER JOIN product_size b1 ON b.art_size_code=b1.size_code ) ON a.article_code=b.article_code
                    LEFT JOIN product c ON a.article_code=c.article_code AND b.art_size_code=c.size_code ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.article_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function get_colour_article($art_code){
        $this->db->where('article_code',$art_code);
        return $this->db->get('article_colour');
    }
    function get_size_article($art_code){
        $this->db->where('article_code',$art_code);
        return $this->db->get('article_size');
    }
    function read_data($code){
        $sql = "select a.*
                ,
                d.uom_id as satuan_jual_code,
                e.uom_id as satuan_beli_code
                , c.uom_id, c.description as uom_name
                /*, SUBSTRING_INDEX(SUBSTRING_INDEX(a.colour_code, ',', n), ',', -1) AS cc*/
                , GROUP_CONCAT(b.description) colour_name
                from product a 
                JOIN 
                (SELECT @row := @row + 1 AS n FROM 
                (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) t,
                (SELECT @row:=0) r) AS numbers
                  ON CHAR_LENGTH(a.colour_code) 
                    - CHAR_LENGTH(REPLACE(a.colour_code, ',', ''))  >= n - 1
                LEFT JOIN product_colour b ON SUBSTRING_INDEX(SUBSTRING_INDEX(a.colour_code, ',', n), ',', -1)=b.colour_code
                left join product_uom c on a.satuan_stock=c.uom_code
                left join product_uom d on a.satuan_jual=d.uom_code
                left join product_uom e on a.satuan_beli=e.uom_code
                where a.id=$code";
        return $this->db->query($sql);
//        $this->db->where('id',$code);
//        return $this->db->get('product');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('product',$data);
    }
    function insert_data($data){
        $this->db->insert('product', $data);
        return $this->db->insert_id();
    }
    function insert_data_article_size_colour($data){
//        $this->db->insert('article_size_colour', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('product');

//        $this->db->where('id',$id);
//        $this->db->delete('article_size_colour');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('product');
    }
    function generate_auto_number(){
        $sql = "SELECT IFNULL(CONCAT(RIGHT((DATE_FORMAT(NOW(),'%y')),2),LPAD(MAX(RIGHT(sku,6))+1,6,'0')), 
                CONCAT(RIGHT((DATE_FORMAT(NOW(),'%y')),2),LPAD(1,6,'0'))) AS nomor 
                FROM product order by sku desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function cek_article($code){
        $sql = "select sku from product where article_code='$code' order by sku desc";
        return $this->db->query($sql);
    }
}
