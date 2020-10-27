<?php

class Customeraddress_model extends CI_Model {

    public $query;
	public function __construct(){

        parent::__construct();
        $this->query="select 
				  a.id, a.customer_code, b.customer_name, a.alias_name, a.alamat1, a.alamat2
				  , a.province_id, e.name as province_name, a.regency_id, f.name as regency_name
				  , a.zip, a.phone, a.fax
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd
                  , DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , a.crtdt as tanggalan
	            from customer_address a 
	            left join customer b on a.customer_code=b.customer_code
				left join provinces e on a.province_id=e.id
				left join regencies f on a.regency_id=f.id and a.province_id=f.province_id
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as $this->query ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.customer_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function read_data($code){
        return $this->db->query($this->query." where a.id='$code'");
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('customer_address',$data);
    }
    function insert_data($data){
        $this->db->insert('customer_address', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('customer_address');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('customer_code',$code);
        return $this->db->get('customer');
    }
}
