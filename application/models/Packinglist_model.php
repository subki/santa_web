<?php

class Packinglist_model extends CI_Model {

    private $table;
    private $query;
	public function __construct(){
        parent::__construct();
        $this->table = "packing_header";
        $this->query = "select a.docno
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%b/%Y') ak_doc_date
                  , so.doc_date tgl_so, DATE_FORMAT(so.doc_date, '%d/%m/%Y') ak_tgl_so
                  , a.so_number, a.remark, a.status, c.address1, c.phone1, c.pkp, c.beda_fp
                  , so.customer_code, c.customer_name, a.qty_item, a.qty_pl, so.salesman_id
                  , so.disc1_persen, so.disc2_persen, so.disc3_persen
                  , so.qty_order, so.qty_deliver, so.service_level
                  , so.gross_sales, so.total_discount, so.sales_before_tax, so.total_ppn, so.sales_after_tax
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from $this->table a 
	            left join (
	              sales_order_header so
	              inner join customer c on so.customer_code=c.customer_code
	            ) on a.so_number=so.docno
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
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
	            (select count(a1.docno) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data($code){
        $q = $this->query." where a.docno='$code'";
        return $this->db->query($q);
    }
    function read_data_by_so($code){
        $q = $this->query." where a.so_number='$code'";
        return $this->db->query($q);
    }
    function update_data($code, $data){
        $this->db->where('docno',$code);
        $this->db->update($this->table,$data);
    }
    function insert_data($data){
        $this->db->insert($this->table, $data);
        $this->copySOtoPL($data);
    }
    function copySOtoPL($data){
        $docno = $data['docno'];
        $so_number = $data['so_number'];
        $crtby = $data['crtby'];

        $sql = "select sum(qty_pl) as tot from packing_detail where docno='$docno'";
        $rd = $this->db->query($sql)->row();
        if($rd->tot == 0) {
            $sql = "delete from packing_detail where docno='$docno'";
            if ($this->db->query($sql)) {
                $sql = "insert into packing_detail (docno, so_number, seqno, nobar, qty_order, crtby, crtdt)
                SELECT '$docno','$so_number', seqno, nobar
                , qty_order-ifnull((select sum(qty_pl) from packing_detail where so_number='$so_number'),0), '$crtby', now()
                FROM sales_order_detail WHERE docno='$so_number'";
                $this->db->query($sql);
            }
        }
        return $rd->tot;
    }
    function delete_data($id){
        $this->db->where('docno',$id);
        $this->db->delete($this->table);
    }
    function read_transactions($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product');
    }
    function generate_auto_number($so_number){
        $prefix = "";
        $sql = "select * from sales_order_header where docno='$so_number'";
        $dt = $this->db->query($sql);
        if($dt->num_rows()>0){
            $prefix = $dt->row()->store_code;
        }
        if($prefix=="") return "";
        $sql = "SELECT IFNULL(CONCAT('$prefix','PL',DATE_FORMAT(NOW(),'%Y'),LPAD(MAX(RIGHT(docno,6))+1,6,'0')),
                CONCAT('$prefix','PL',DATE_FORMAT(NOW(),'%Y'),LPAD(1,6,'0'))) AS nomor 
                FROM packing_header 
                WHERE LEFT(docno,LENGTH(CONCAT('$prefix','PL',DATE_FORMAT(NOW(),'%Y')))) = CONCAT('$prefix','PL',DATE_FORMAT(NOW(),'%Y')) ORDER BY docno DESC";
        return $this->db->query($sql)->row()->nomor;
    }


    function get_list_data_detail($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                SELECT a.id, a.docno, a.seqno, a.nobar, a.qty_order, a.qty_pl
                    , b.nmbar, c.satuan_jual, d.description AS uom_jual, c.product_code, d.uom_id
                    , c.product_name, sd.tipe, c.article_code
                FROM packing_detail a
                left join sales_order_detail sd on a.so_number=sd.docno and a.seqno=sd.seqno
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
	            (select count(a1.nobar) from tmp a1 ) as total
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
        return $this->db->get('packing_detail');
    }
    function update_data_detail($docno,$code, $data){
        $this->db->where('id',$code);
        $this->db->update("packing_detail",$data);
        $this->updateheaderdata($docno);
    }
    function insert_data_detail($docno,$data){
        $this->db->insert("packing_detail", $data);
        $this->updateheaderdata($docno);
    }
    function delete_data_detail($docno, $id){
        $this->db->where('id',$id);
        $this->db->delete("packing_detail");
        $this->updateheaderdata($docno);
    }
    function read_transactions_detail($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product');
    }
    function updateheaderdata($docno){
        $sql = "UPDATE packing_header AS dest
                , (SELECT COUNT(nobar) item, SUM(qty_pl) qty
                  FROM packing_detail WHERE docno='$docno') AS src
                SET dest.qty_item = src.item
                    , dest.qty_pl=src.qty
                WHERE dest.docno='$docno'";
        $this->db->query($sql);
        $sql = "SELECT * FROM packing_header
                WHERE docno='$docno'";
        $rd = $this->db->query($sql);
        if($rd->num_rows()>0){
            if($rd->row()->status=="POSTING"){
                $sql = "UPDATE sales_order_header AS dest
                        , (SELECT sum(qty_order) as orderan, sum(qty_pl) packing, so_number
                          FROM packing_detail WHERE docno='$docno' GROUP BY docno) AS src
                        SET dest.service_level = ifnull(src.packing,0)/ifnull(src.orderan,0)*100
                        WHERE dest.docno=src.so_number";
                $this->db->query($sql);
                $sql = "UPDATE sales_order_header AS dest
                        , (SELECT sum(qty_order) as orderan, sum(qty_pl) packing, so_number
                          FROM packing_detail group by so_number) AS src
                        SET dest.service_level = ifnull(src.packing,0)/ifnull(src.orderan,0)*100,
                          dest.qty_deliver = src.packing
                        WHERE dest.docno=src.so_number";
                $this->db->query($sql);
            }
        }
    }
    function unpostSO($docno){
        $sql = "UPDATE sales_order_header AS dest
                        , (SELECT sum(qty_order) as orderan, sum(qty_pl) packing, so_number
                          FROM packing_detail WHERE docno='$docno' GROUP BY docno) AS src
                        SET dest.service_level = ifnull(src.packing,0)/ifnull(src.orderan,0)*100
                        WHERE dest.docno=src.so_number";
        $this->db->query($sql);
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
