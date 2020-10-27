<?php

class Stock_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                select a.id, a.nobar, a.location_code, a.periode, a.saldo_awal
                  , a.do_masuk, a.do_keluar, a.penyesuaian, a.penjualan, a.pengembalian, a.saldo_akhir
                  , b.description as location_name, c.nmbar
	            from stock a 
	            inner join location b on a.location_code=b.location_code
	            inner join product_barang c on a.nobar=c.nobar ";
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

    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('stock');
    }
    function read_data_by_nobar($loc, $prd, $nobar){
        $this->db->where('location_code',$loc);
        $this->db->where('periode',$prd);
        $this->db->where('nobar',$nobar);
        return $this->db->get('stock');
    }

    function insert_data($data){
        $this->db->insert('stock', $data);
        $sql = "UPDATE product_barang a,
				(
					SELECT b.nobar, SUM(b.saldo_akhir) saldo
					FROM stock b
					WHERE b.periode=DATE_FORMAT(NOW(),'%Y%m')
					GROUP BY b.nobar
				) b1
			SET a.soh=b1.saldo
			WHERE a.nobar=b1.nobar";
        $this->db->query($sql);

        $sql = "UPDATE product a,
				(
					SELECT b.product_id, SUM(b.soh) saldo
					FROM product_barang b
					GROUP BY b.product_id
				) b1
			SET a.total_soh=b1.saldo
			WHERE a.id=b1.product_id";
        $this->db->query($sql);
    }
}
