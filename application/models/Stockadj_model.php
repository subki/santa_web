<?php

class Stockadj_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.docno, a.doc_date, a.periode, a.outlet_code, o.description,a.jenis_barang
                   , a.remark, a.status 
                   , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd
                  , DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  from stock_adj a  
                LEFT JOIN location o ON o.location_code=a.outlet_code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
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
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);

        return $data;
    }

    function get_list_datastock($page,$rows,$sort,$order,$role,$fltr){ 
             $sql = "create temporary table tmp as 
                select a.id, a.nobar, a.location_code, a.periode, a.saldo_awal,p.jenis_barang
                  , a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir
                  , b.description as location_name, c.nmbar
                from stock a 
                inner join location b on a.location_code=b.location_code
                inner join product_barang c on a.nobar=c.nobar
                inner join product p on p.id=c.product_id ";
//        $this->db->query($sql);
//        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }else $sql .= " where 1=1;";
        $this->db->query($sql);

        $sql = "select a.*,
                (select count(a1.id) from tmp a1 ) as total
                 from tmp a ";
        $sql .="order by " .$sort." $order
                limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }


    function get_list_dataopname($page,$rows,$sort,$order,$role,$fltr){ 
             $sql = "create temporary table tmp2 as 
               SELECT g.on_loc,g.trx_date,a.uom,a.trx_no,a.item,a.product_code,a.qty 'QTYStock' ,
                IFNULL(SUM(b.taking_qty),0) 'QTYScan',SUM(b.taking_qty)-a.qty+IFNULL(k.adjust,0) Selisih,a.crtdt,b.product_code productscan,b.crtdt crtdtscan 
                FROM adjustment_dtl a 
                INNER JOIN hal_gondola g ON a.trx_no=g.ref_no 
                COLLATE utf8mb4_general_ci 
                INNER JOIN dtl_gondola b ON b.item=a.item AND b.trx_no=g.trx_no 
                LEFT JOIN stock_adj_detail k ON k.sku=a.item 
                COLLATE utf8mb4_unicode_ci
                AND k.so_number=a.trx_no  
                COLLATE utf8mb4_unicode_ci  
                GROUP BY a.item 
                ORDER BY b.crtdt DESC,b.product_code ASC  ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);
        $sql = "select a.*,
                (select count(a1.trx_no) from tmp a1 ) as total
                 from tmp a ";
        $sql .="HAVING Selisih <> 0 order by trx_no $order
                limit ".($page-1)*$rows.",".$rows;
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);

        return $data;
    }

    function get_list_data_detail($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                 SELECT a.id, a.docno, a.sku, a.soh, a.adjust, a.keterangan , p.nobar, p.nmbar, p.product_id from stock_adj_detail a 
                  LEFT JOIN product_barang p ON p.nobar = a.sku";
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
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);

        return $data;
    }


    function getAutoNumber(){
        $year=date("Ym"); 
        $sql = "SELECT IFNULL(
                       CONCAT('ADJ',DATE_FORMAT(NOW(),'%y%m'),LPAD(MAX(RIGHT(docno,4))+1,4,'0')),
                       CONCAT('ADJ',DATE_FORMAT(NOW(),'%y%m'),LPAD(1,4,'0'))
                   ) AS nomor FROM stock_adj where periode =$year order by docno desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function read_data_header($code){
        $sql = " select a.docno, a.doc_date, a.periode, a.outlet_code
                   , a.remark, a.status, o.description
                   , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd
                  , DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  from stock_adj a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id 
                  LEFT JOIN location o ON o.location_code=a.outlet_code
                  WHERE a.docno='$code'";
        return $this->db->query($sql);
    }
    function update_data_header($code, $data){
        $this->db->where('docno',$code);
        $this->db->update('stock_adj',$data);
    }
    function insert_data_header($data){
        $this->db->insert('stock_adj', $data);
    }
    function delete_data_header($id){
        $this->db->where('docno',$id);
        $this->db->delete('stock_adj');
    }
    function read_transactions($code){
        //nanti diubah
        $this->db->where('docno',$code);
        return $this->db->get('do_detail');
    }


    function read_data_detail($code){
        $this->db->where('id',$code);
        return $this->db->get('stock_adj_detail');
    }
    function update_data_detail($code, $data){
        $this->db->where('id',$code);
        $this->db->update('stock_adj_detail',$data);
    }
    function insert_data_detail($data){
        $this->db->insert('stock_adj_detail', $data);
    }
    function delete_data_detail($id){
        $this->db->where('id',$id);
        $this->db->delete('stock_adj_detail');
    }
    function read_transactions_detail($code){
        //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('stock_adj_detail');
    }

    function update_data_adjOpname($docno){
        $sql = "UPDATE adjustment_dtl AS b
                INNER JOIN stock_adj_detail AS g ON b.trx_no = g.so_number 
                        COLLATE utf8mb4_general_ci 
                AND g.sku=b.item
                                COLLATE utf8mb4_general_ci 
                INNER JOIN stock_adj AS f ON f.docno = g.docno
                                COLLATE utf8mb4_general_ci 
                SET b.qty = b.qty+g.adjust ,
                b.taking = b.qty+g.adjust ,
                b.total_cost = qty*unit_cost
                WHERE  g.docno='$docno'";
        return $this->db->query($sql);
    }

    function cek_stok($sku, $outlet, $periode){
        $sql = "select * from stock a
                where location_code='$outlet' 
                and periode = '$periode' 
                and nobar = '$sku'";
        return $this->db->query($sql);
    }

    function update_adjustment($periode, $outlet, $sku, $qty){
        $sql = "update stock set penyesuaian=penyesuaian+$qty 
                where nobar='$sku' and periode='$periode' and location_code='$outlet'";
        if($this->db->query($sql)) {
            $sql = "update stock set 
                saldo_akhir = ((saldo_awal+do_masuk+pengembalian) - (do_keluar+penjualan))+penyesuaian
                where nobar='$sku' and periode='$periode' and location_code='$outlet'";
            $this->db->query($sql);
        }
    }

    function insert_adjustment($periode, $outlet, $sku, $qty){
        $sql = "insert into stock (nobar, location_code, periode, saldo_awal, do_masuk, do_keluar, penyesuaian, penjualan, pengembalian, saldo_akhir) 
                values ('$sku', '$outlet', '$periode',0,0,0,$qty,0,0,((do_masuk+pengembalian) - (do_keluar+penjualan))+penyesuaian)";
        $this->db->query($sql);
    }

}
