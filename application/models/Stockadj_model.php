<?php

class Stockadj_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.docno, a.doc_date, a.periode, a.outlet_code
                   , a.remark, a.status, o.outlet_name
                   , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd
                  , DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  from stock_adj a 
                  LEFT JOIN outlet o ON o.outlet_code=a.outlet_code 
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

    function get_list_data_detail($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.id, a.docno, a.sku, a.soh, a.adjust, a.keterangan
                    , p.article_code, p.article_name, p.product_code
                  from stock_adj_detail a 
                  LEFT JOIN product p ON p.sku = a.sku";
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
        $sql = "SELECT IFNULL(
                       CONCAT('ADJ',DATE_FORMAT(NOW(),'%y%m'),LPAD(MAX(RIGHT(docno,4))+1,4,'0')),
                       CONCAT('ADJ',DATE_FORMAT(NOW(),'%y%m'),LPAD(1,4,'0'))
                   ) AS nomor FROM stock_adj order by docno desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function read_data_header($code){
        $sql = " select a.docno, a.doc_date, a.periode, a.outlet_code
                   , a.remark, a.status, o.outlet_name
                   , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd
                  , DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  from stock_adj a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id 
                  LEFT JOIN outlet o ON o.outlet_code=a.outlet_code
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

    function cek_stok($sku, $outlet, $periode){
        $sql = "select * from stock a
                where outlet_code='$outlet' 
                and periode = '$periode' 
                and sku = '$sku'";
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
