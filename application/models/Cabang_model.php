<?php

class Cabang_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.store_code
                  , a.kode_cabang, a.nama_cabang, a.prefix_trx, a.type, a.flag, a.location_code, b.description location_name
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from cabang a 
	            left join location b on a.location_code=b.location_code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.store_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data($code, $code1){
        $this->db->where('store_code',$code);
        $this->db->where('kode_cabang',$code1);
        return $this->db->get('cabang');
    }
    function update_data($code, $code1, $data){
        $this->db->where('store_code',$code);
        $this->db->where('kode_cabang',$code1);
        $this->db->update('cabang',$data);
    }
    function insert_data($data){
        $this->db->insert('cabang', $data);
    }
    function delete_data($code, $code1){
        $this->db->where('store_code',$code);
        $this->db->where('kode_cabang',$code1);
        $this->db->delete('cabang');
    }
    function read_transactions($code){
        $sql = "select a.*
	            FROM users a WHERE a.store_code = (SELECT IFNULL(store_code,'') FROM cabang WHERE kode_cabang='$code' LIMIT 1)";
        return $this->db->query($sql);
    }
}
