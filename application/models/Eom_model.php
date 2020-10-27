<?php

class Eom_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_nobar($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                select a.nobar, a.location_code, a.periode, a.saldo_awal
                  , a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir
	            from stock a ";
        if($fltr!=''){
            $sql .= $fltr;
        }else $sql .= " where 1=1;";
        $this->db->query($sql);
        $sql = "create temporary table tmp2 as
                select a.*
                  , b.description as location_name
	            from tmp a 
	            inner join location b on a.location_code=b.location_code ";
        $this->db->query($sql);
        $sql = "create temporary table tmp3 as
                select a.*, c.nmbar
	            from tmp2 a 
	            inner join product_barang c on a.nobar=c.nobar ";
        $this->db->query($sql);
        $sql = "select a.*,
	            (select count(a1.nobar) from tmp3 a1 ) as total
	             from tmp3 a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_location($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.location_code, a.description location_name, b.store_name, b.store_code
	            from location a 
	            left join (
	              profile_p b left join cabang c on b.store_code=c.store_code
	            ) on a.location_code=c.location_code ";
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

    function get_location_store($store, $from_location_code,$to_location_code){
        $store1 = $this->session->userdata('kode store pusat');
        if($store==$store1){
            $sql = "select * from location 
                where location_code>='$from_location_code'
                and location_code<='$to_location_code' ";
        }else{
            $sql = "select * from location 
                where store_code='$store' 
                and location_code>='$from_location_code'
                and location_code<='$to_location_code' ";
        }

	    return $this->db->query($sql);
    }

    function get_location_closing($location_code, $prd, $dd){
        $sql = "select * from closing_location
                WHERE location='$location_code'
                and DATE_FORMAT(periode, '%Y%m')=period_add($prd,$dd)";
        return $this->db->query($sql);
    }
    function update_closing_location($id, $data){
        $this->db->where('id',$id);
        $this->db->update('closing_location',$data);
    }
    function insert_closing_location($data){
        $this->db->insert('closing_location', $data);
    }
    function delete_stock_next_month($location_code, $prd, $from_nobar, $to_nobar){
        $sql = "delete from stock 
                where location_code in ('$location_code')
                and periode = period_add($prd,1)
                and nobar>='$from_nobar'
                and nobar<='$to_nobar'";
        return $this->db->query($sql);
    }
    function insert_stock_next_month($location_code, $prd, $from_nobar, $to_nobar){
        $sql = "insert into stock
                (nobar, location_code, periode, saldo_awal, do_masuk
                , do_keluar, penyesuaian, penjualan, pengembalian, saldo_akhir)
                SELECT nobar, location_code, period_add($prd,1)
                , saldo_akhir, 0, 0, 0, 0, 0, saldo_akhir
                FROM stock WHERE location_code in ('$location_code') 
                and periode='$prd'
                and nobar>='$from_nobar'
                and nobar<='$to_nobar'";
        $dt = $this->db->query($sql);
		$sql ="UPDATE product_barang a,
				(
					SELECT b.nobar, SUM(b.saldo_akhir) saldo
					FROM stock b
					WHERE b.periode= period_add($prd,1)
					GROUP BY b.nobar
				) b1
			SET a.soh=b1.saldo
			WHERE a.nobar=b1.nobar;";
		$this->db->query($sql);
		$sql ="UPDATE product a,
				(
					SELECT b.product_id, SUM(b.soh) saldo
					FROM product_barang b
					GROUP BY b.product_id
				) b1
			SET a.total_soh=b1.saldo
			WHERE a.id=b1.product_id;";
		$this->db->query($sql);
		return $dt;
    }
}
