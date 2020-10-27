<?php

class Wholesales_model extends CI_Model {

    private $table;
    private $query;
	public function __construct(){
        parent::__construct();
        $this->table = "sales_trans_header";
        $this->query = "select a.id, a.no_faktur,a.no_faktur2, a.seri_pajak
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%b/%Y') ak_doc_date, a.jenis_faktur
                  , DATE_FORMAT(a.doc_date, '%d/%m/%Y') ak_doc_date2
                  , a.faktur_date, DATE_FORMAT(a.faktur_date, '%d/%m/%Y') ak_faktur_date
                  , IFNULL(a.verifikasi_finance,'') verifikasi_finance, c.top_day
                  , so.doc_date tgl_so, DATE_FORMAT(so.doc_date, '%d/%m/%Y') ak_tgl_so
                  , a.base_so, a.remark, a.status, a.qty_print, c.pkp, c.beda_fp, c.npwp, c.nama_pkp, c.alamat_pkp
                  , so.customer_code, c.customer_name, so.store_code, so.location_code, c.payment_first
                  , so.salesman_id, sl.salesman_name, c.address1, c.address2, r.name as regency_name
                  , store.store_name, l.description as location_name, c.phone1
                  , c.credit_limit, c.outstanding, (c.credit_limit-c.outstanding) credit_remain
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , coalesce(ifnull(u3.fullname,a.app_creditby),'') as creditby
                  , coalesce(ifnull(u4.fullname, a.app_maxslsby),'') as maxslsby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , coalesce(wp.keterangan,'') as ket
                  , coalesce(wp.id,0) id_confirm
                  , a.sales_after_tax
                  , a.status as statushd
                  , ifnull(si.id,0) sales_invoice_id
                  , ifnull(spi.docno,'') as proforma_no
	            from $this->table a 
	            left join wholesales_payment wp on wp.sales_trans_header_id=a.id
	            left join sales_invoice si on si.id=a.id
	            left join sales_proforma spi on spi.docno=si.sales_proforma_id
	            left join (
	              packing_header ph
	              left join sales_order_header so on so.docno = ph.so_number
	              left join salesman sl on sl.salesman_id=so.salesman_id
	              inner join customer c on so.customer_code=c.customer_code
	              left join regencies r on r.id = c.regency_id
	              left join profile_p store on store.store_code=so.store_code
	              left join location l on l.location_code=so.location_code
	            ) on ph.docno = a.base_so
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id
	            left join users u3 on a.app_creditby=u3.user_id
	            left join users u4 on a.app_maxslsby=u3.user_id ";
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                $this->query";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.no_faktur) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data($code){
        $q = $this->query." where a.id='$code'";
        return $this->db->query($q);
    }
    function cek_nofaktur($code){
        $q = $this->query." where a.no_faktur='$code'";
        return $this->db->query($q);
    }
    function cek_nofaktur2($code){
        $q = $this->query." where a.no_faktur2='$code'";
        return $this->db->query($q);
    }
    function read_data_by_so($code){
        $q = $this->query." where a.base_so='$code'";
        return $this->db->query($q);
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        return $this->db->update($this->table,$data);
    }
    function insert_data($data){
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        $this->copyPLtoWS($data, $insert_id);
        return $insert_id;
    }
    function copyPLtoWS($data, $insert_id){
        $base_so = $data['base_so'];
        $crtby = $data['crtby'];
        $sql = "insert into sales_trans_detail (
                    sales_trans_header_id, base_so, item, nobar, tipe
                    , komisi_persen, qty_order, qty_on_sales, qty_refund, uom_code, location_code
                    , unit_price, disc1_persen, disc1_amount, disc2_persen, disc2_amount
                    , disc3_persen, disc3_amount, disc_total, disc_open, net_unit_price
                    , bruto_before_tax, total_tax, netto_after_tax, status_detail, crtby, crtdt)
                SELECT $insert_id,'$base_so', p.product_code, bg.nobar, b.tipe
                  , 0, b.qty_order, a.qty_pl, 0, p.satuan_jual, b1.location_code
                  , b.unit_price, b.disc1_persen, b.disc1_amount, b.disc2_persen, b.disc2_amount
                  , b.disc3_persen, b.disc3_amount, b.disc_total, 0, b.net_unit_price
                  , b.bruto_before_tax, b.total_tax, b.bruto_before_tax-b.total_tax, 'OPEN', '$crtby', NOW()
                FROM packing_detail a
                LEFT JOIN sales_order_detail b on b.docno = a.so_number and b.seqno = a.seqno
                LEFT JOIN sales_order_header b1 ON b1.docno = b.docno
                LEFT JOIN product_barang bg ON bg.nobar=a.nobar
                LEFT JOIN product p ON p.id = bg.product_id
                WHERE a.docno='$base_so'";
        $this->db->query($sql);
    }
    function delete_data($id){
        $this->db->where('docno',$id);
        $this->db->delete($this->table);
    }
    function read_transactions($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product');
    }
    function generate_auto_number($store_code){
        $prefix="";
        if($store_code=="001") $prefix="FAK";
        else if($store_code=="002") $prefix="BDG";
        else if($store_code=="003") $prefix="SBY";

        if($prefix=="") return "";
        $sql = "SELECT IFNULL(CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y'),LPAD(MAX(RIGHT(no_faktur,6))+1,6,'0')),
                CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y'),LPAD(1,6,'0'))) AS nomor 
                FROM sales_trans_header 
                WHERE LEFT(no_faktur,LENGTH(CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y')))) = CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y')) ORDER BY no_faktur DESC";
        return $this->db->query($sql)->row()->nomor;
    }

    function generate_auto_number_ivs(){
        $prefix="IVS";

        if($prefix=="") return "";
        $sql = "SELECT IFNULL(CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y'),LPAD(MAX(RIGHT(no_faktur,6))+1,6,'0')),
                CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y'),LPAD(1,6,'0'))) AS nomor 
                FROM sales_trans_header 
                WHERE LEFT(no_faktur,LENGTH(CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y')))) = CONCAT('$prefix',DATE_FORMAT(NOW(),'%Y')) ORDER BY no_faktur DESC";
        return $this->db->query($sql)->row()->nomor;
    }

    function get_list_data_detail($page,$rows,$sort,$order,$role,$fltr){
        $sql = "DROP TABLE IF EXISTS tmp;";
        $this->db->query($sql);
        $sql = "create temporary table tmp as
                SELECT a.id, a.sales_trans_header_id, a.base_so, a.item, a.nobar, a.tipe
                    , a.komisi_persen, a.qty_order, a.qty_on_sales, a.qty_refund, a.uom_code, a.location_code
                    , a.unit_price, a.disc1_persen, a.disc1_amount, a.disc2_persen, a.disc2_amount
                    , a.disc3_persen, a.disc3_amount, a.disc_total, a.disc_open, a.net_unit_price
                    , a.bruto_before_tax, a.total_tax, a.netto_after_tax, a.status_detail, a.crtby, a.crtdt
                    , b.nmbar, c.satuan_jual, d.description AS uom_jual, c.product_code, d.uom_id
                    , c.product_name, c.article_code, c.size_code
                FROM sales_trans_detail a
                LEFT JOIN (
                    product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_jual=d.uom_code
                ) ON a.nobar=b.nobar";

        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }


    function cek_detail($docno, $seqno){
        $this->db->where('docno',$docno);
        $this->db->where('seqno',$seqno);
        return $this->db->get('packing_detail');
    }
    function read_data_detailID($id){
        $this->db->where('id',$id);
        return $this->db->get('sales_trans_detail');
    }
    function update_data_detail($docno,$code, $data){
        $this->db->where('id',$code);
        $this->db->update("sales_trans_detail",$data);
        $this->updateheaderdata($docno);
    }
    function insert_data_detail($docno,$data){
        $this->db->insert("sales_trans_detail", $data);
        $this->updateheaderdata($docno);
    }
    function delete_data_detail($docno, $id){
        $this->db->where('id',$id);
        $this->db->delete("sales_trans_detail");
        $this->updateheaderdata($docno);
    }
    function read_transactions_detail($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product');
    }
    function updateheaderdata($docno){
//        $sql = "UPDATE packing_header AS dest
//                , (SELECT COUNT(nobar) item, SUM(qty_pl) qty
//                  FROM packing_detail WHERE docno='$docno') AS src
//                SET dest.qty_item = src.item
//                    , dest.qty_pl=src.qty
//                WHERE dest.docno='$docno'";
//        $this->db->query($sql);
//        $sql = "UPDATE sales_order_header AS dest
//                , (SELECT sum(qty_order) as orderan, sum(qty_pl) packing, so_number
//                  FROM packing_detail WHERE docno='$docno' GROUP BY docno) AS src
//                SET dest.service_level = ifnull(src.packing,0)/ifnull(src.orderan,0)*100
//                WHERE dest.docno=src.so_number";
//        $this->db->query($sql);
    }


    function get_product($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                SELECT a.nobar, a.saldo_akhir stock, b.nmbar, b.product_id, c.product_code, c.article_code
                    , c.jenis_barang, c.satuan_stock, c.satuan_jual
                    , d.description AS uom_stock, d.uom_id as id_stock, e.description AS uom_jual, e.uom_id as id_jual
                FROM stock a
                INNER JOIN (
                    product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_stock=d.uom_code
                    INNER JOIN product_uom e ON c.satuan_jual=e.uom_code
                ) ON a.nobar=b.nobar";
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
}
