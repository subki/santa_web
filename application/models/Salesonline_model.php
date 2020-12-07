<?php

class Salesonline_model extends CI_Model {

    private $table;
    private $query;
	public function __construct(){
        parent::__construct();
        $this->table = "sales_online_header";
        $this->query = "SELECT DATE_FORMAT(p.tgl_pickup, '%d/%b/%Y') tgl_pickup,so.docno,so.remark
                  , a.sales_date, DATE_FORMAT(a.sales_date, '%d/%m/%Y') ak_doc_date
                  , DATE_FORMAT(so.doc_date, '%d/%b/%Y') tgl_so, DATE_FORMAT(so.doc_date, '%d/%m/%Y') ak_tgl_so
                  , a.so_number,so.so_no, so.status, c.address1, c.phone1, c.pkp, c.beda_fp
                  , so.customer customer_code, c.customer_name, so.qty_item, so.qty, so.sales
                  , so.disc1_persen, so.disc2_persen , so.doc_date  
                  , so.gross_sales, so.total_discount, so.sales_before_tax, so.total_ppn, so.sales_after_tax
                  , IFNULL(u1.fullname,a.crtby) AS crtby, IFNULL(u2.fullname, a.updby) AS updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                FROM sales_online_header so
                LEFT JOIN  sales_online_detail a  ON so.docno=a.so_number
                LEFT JOIN pickup_d d ON so.docno=d.barcode
                LEFT JOIN pickup_h p ON p.id=d.pickup_h_id
                LEFT JOIN customer c ON so.customer=c.customer_code
                LEFT JOIN users u1 ON a.crtby=u1.user_id
                LEFT JOIN users u2 ON a.updby=u2.user_id";
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
        $q = $this->query." where so.docno='$code'";
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

        $sql = "select sum(qty_sales) as tot from sales_online_detail where docno='$docno'";
        $rd = $this->db->query($sql)->row();
        if($rd->tot == 0) {
            $sql = "delete from sales_online_detail where docno='$docno'";
            if ($this->db->query($sql)) {
                $sql = "insert into sales_online_detail (docno, so_number, seqno, nobar, qty_order, crtby, crtdt)
                SELECT '$docno','$so_number', seqno, nobar
                , qty_order-ifnull((select sum(qty_sales) from sales_online_detail where so_number='$so_number'),0), '$crtby', now()
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
        $sql = "SELECT IFNULL(CONCAT('$prefix','DS',DATE_FORMAT(NOW(),'%Y'),LPAD(MAX(RIGHT(docno,6))+1,6,'0')),
                CONCAT('$prefix','DS',DATE_FORMAT(NOW(),'%Y'),LPAD(1,6,'0'))) AS nomor 
                FROM sales_online_header 
                WHERE LEFT(docno,LENGTH(CONCAT('$prefix','DS',DATE_FORMAT(NOW(),'%Y')))) = CONCAT('$prefix','DS',DATE_FORMAT(NOW(),'%Y')) ORDER BY docno DESC";
        return $this->db->query($sql)->row()->nomor;
    }


    function get_list_data_detail($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                SELECT a.id, a.docno, a.seqno, a.type, a.nobar, SUM(a.qty_order) qty_order, 
                    SUM(a.unitprice)unit_price, SUM(a.unitprice)+SUM(a.total_tax)pricetax , 
                    SUM(a.disc1_persen) disc1_persen,SUM( a.disc2_persen)disc2_persen, 
                    SUM(a.disc1_amount) disc1_amount,SUM(a.disc2_amount) disc2_amount , 
                    SUM(a.disc_total) disc_total,SUM(a.bruto_before_tax) bruto_before_tax,
                    SUM(a.total_tax) total_tax, SUM(a.net_unit_price) net_unit_price, 
                    SUM(a.net_after_tax) net_total_price , a.status_detail , b.nmbar, c.satuan_jual, 
                    d.description AS uom_jual, c.product_code, d.uom_id , c.product_name, b.product_id , 
                    (SELECT IFNULL(SUM(pl.qty_pl),0) 
                        FROM packing_detail pl 
                        INNER JOIN packing_header ph ON ph.docno=pl.docno 
                            WHERE pl.so_number=a.docno AND pl.seqno=a.seqno AND ph.status IN('POSTING','CLOSED')) AS qty_pl , 
                        COALESCE(a.updby, a.crtby) last_user , COALESCE(a.upddt, a.crtdt) last_time 
                        FROM sales_online_detail a 
                    LEFT JOIN ( product_barang b 
                        INNER JOIN product c ON b.product_id=c.id 
                        INNER JOIN product_uom d ON c.satuan_jual=d.uom_code ) 
                        ON a.nobar=b.nobar ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.nobar) from tmp a1 ) as total
	             from tmp a ";
        $sql .=" order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }


    function cek_detail($docno, $seqno){
        $this->db->where('docno',$docno);
        $this->db->where('seqno',$seqno);
        return $this->db->get('sales_online_detail');
    }
    function read_data_detailID($id){
        $this->db->where('docno',$id);
        return $this->db->get('sales_online_detail');
    }
    // function update_data_detail($docno,$code, $data){
    //     $this->db->where('docno',$docno);
    //     $this->db->update("sales_online_detail",$data);
    //     $this->updateheaderdata($docno);
    // }
    function insert_data_detail($docno,$data){
        $this->db->insert("sales_online_detail", $data);
        //$this->updateheaderdata($docno);
    }
    function delete_data_detail($docno, $id){
        $this->db->where('id',$id);
        $this->db->delete("sales_online_detail");
      //  $this->updateheaderdata($docno);
    }
    function read_transactions_detail($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product');
    }
    function updateheaderdata($docno){
        $sql = "UPDATE sales_online_header AS dest , 
                (SELECT COUNT(nobar) item, SUM(qty_order) qty , 
                    SUM(CEILING(sales_online_detail.unitprice)) bruto , 
                    SUM(CEILING(sales_online_detail.disc_total)) disc , 
                    SUM(CEILING(sales_online_detail.bruto_before_tax)) before_tax , 
                    SUM(CEILING(sales_online_detail.net_after_tax)) after_tax , 
                    SUM(CEILING(sales_online_detail.total_tax)) ppn 
                    FROM sales_online_detail 
                    WHERE docno='$docno') AS src 
                    SET dest.qty_item = src.item , dest.qty=src.qty , 
                        dest.gross_sales = src.bruto , 
                        dest.total_discount = src.disc ,
                        dest.sales_before_tax = src.before_tax , 
                        dest.sales_after_tax = src.after_tax , 
                        dest.total_ppn = src.ppn 
                WHERE dest.docno='$docno'";
        $this->db->query($sql);
    }
    function unpostSO($docno){
        $sql = "UPDATE sales_order_header AS dest
                        , (SELECT sum(qty_order) as orderan, sum(qty) packing, so_number
                          FROM sales_online_detail WHERE docno='$docno' GROUP BY docno) AS src
                        SET dest.service_level = ifnull(src.packing,0)/ifnull(src.orderan,0)*100
                        WHERE dest.docno=src.so_number";
        $this->db->query($sql);
    }

    function update_data_detail_disc($docno,$id,$disc, $nomor,$upd,$updt){ 
        if($nomor==1){
            $this->db->query("update sales_online_detail set disc1_persen=$disc where id='$id'");
        }else if($nomor==2){
            $this->db->query("update sales_online_detail set disc2_persen=$disc where id='$id'");
        }  
        $sql = "update sales_online_detail set disc1_amount=unitprice * (disc1_persen/100) where id='$id'";
        if($this->db->query($sql)) {
            $sql = "update sales_online_detail set disc2_amount=(unitprice-disc1_amount) * (disc2_persen/100) where id='$id'";
             if($this->db->query($sql)){
                     $sql = "update sales_online_detail set disc_total=disc1_amount+disc2_amount where id='$id'";
                     if($this->db->query($sql)){
                        $sql = "update sales_online_detail set bruto_before_tax=unitprice-disc_total where id='$id'"; 
                                if($this->db->query($sql)){
                                    $sql = "update sales_online_detail set net_after_tax=(unitprice+total_tax-disc_total)*qty_order where id='$id'";
                                    if($this->db->query($sql)){
                                        $this->updateheaderdata($docno);
                                    }
                                } 
                        }

            }
        }
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
