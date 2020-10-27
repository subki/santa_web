<?php

class Stockopname_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_stockopname($outlet, $offset, $search){
        $sql = "select a.docno, a.outlet_code, a.doc_date, (select group_concat(e.fullname) from stockopname_user u left join employee e on u.user_id=e.user_id where docno=a.docno) as user_id, a.status
                from stockopname a
	            where (
	              a.docno like '%$search%' or 
	              a.doc_date like '%$search%' or 
	              a.status like '%$search%'
	            )
	            and a.outlet_code = '$outlet'
	            order by a.doc_date desc
	            limit 20 offset $offset";
        return $this->db->query($sql);
    }

    function get_stockopname_id($docno){
	    $sql = "select a.docno, a.outlet_code, a.doc_date, (select group_concat(e.fullname) from stockopname_user u left join employee e on u.user_id=e.user_id where docno=a.docno) as user_id, a.status
                  from stockopname a where a.docno='$docno'";
        return $this->db->query($sql);
    }
    function get_stock_by_sku($outlet, $sku, $periode){
	    $sql = "select * from stock where outlet_code='$outlet' and periode='$periode' and sku='$sku'";
        return $this->db->query($sql);
    }

    function get_stockopname_detail($docno){
	    $sql = "select a.id, a.docno, a.outlet_code, a.sku, a.qty, s.saldo_akhir as on_hand, (a.qty-s.saldo_akhir) as variant,
                    p.article_code, p.article_name, a.note
                from stockopname_detail a 
                left join product p on a.sku = p.sku
                left join stockopname so on a.docno=so.docno
                left join stock s on a.sku=s.sku and a.outlet_code=s.outlet_code and REPLACE(LEFT(so.doc_date,7),'-','')=s.periode
                where a.docno='$docno'
                order by a.id desc";
	    return $this->db->query($sql);
    }

    function generate_auto_number(){
        $sql = "SELECT IFNULL(
                       CONCAT('OP',DATE_FORMAT(NOW(),'%y%m'),LPAD(MAX(RIGHT(docno,5))+1,5,'0')),
                       CONCAT('OP',DATE_FORMAT(NOW(),'%y%m'),LPAD(1,5,'0'))
                   ) AS nomor FROM stockopname order by docno desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function insert_header($data){
        $this->db->insert('stockopname', $data);
    }
    function insert_stock($data){
        $this->db->insert('stock', $data);
    }

    function insert_detail($data){
        $this->db->insert('stockopname_detail', $data);
    }
    function insert_detail_pelaksana($data){
        $this->db->insert('stockopname_user', $data);
    }
    function get_stockopname_detail_id($id){
        $sql = "select * from stockopname_detail where id='$id'";
        return $this->db->query($sql);
    }
    function delete_detail($id){
        $this->db->where('id',$id);
        $this->db->delete('stockopname_detail');
    }
    function delete_detail_pelaksana($id){
        $this->db->where('docno',$id);
        $this->db->delete('stockopname_user');
    }
    function cek_detail_id($id){
        $sql = "select * from stockopname_detail where id=$id";
        return $this->db->query($sql);
    }
    function update_detail($id, $data){
        $this->db->where('id',$id);
        $this->db->update('stockopname_detail',$data);
    }

    function get_list_data_note($page,$rows,$sort,$order,$role){
        $sql = "select a.id, a.keterangan,
	            (select count(a1.id) from note a1 ) as total
	            from note a 
	            order by ".$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

}
