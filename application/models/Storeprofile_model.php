<?php

class Storeprofile_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.store_code, a.store_name, a.store_address
                  , a.provinsi_id, a.regency_id, a.zip, a.phone, a.fax, a.email_address, a.status
                 /* , a.register_name
                  , DATE_FORMAT(a.register_date, '%d/%m/%Y') as register_date */
                  , a.default_stock_l, l.description location_name
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , concat(p.name,' - ',r.name) as wilayah 
	            from profile_p a 
	            left join provinces p on a.provinsi_id=p.id
	            left join regencies r on a.regency_id=r.id and p.id=r.province_id 
				left join location l on a.default_stock_l = l.location_code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id  ";
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

    function read_data($code){
        $this->db->where('store_code',$code);
        return $this->db->get('profile_p');
    }
    function update_data($code, $data){
        $this->db->where('store_code',$code);
        $this->db->update('profile_p',$data);
    }
    function insert_data($data){
        $this->db->insert('profile_p', $data);
    }
    function delete_data($id){
        $this->db->where('store_code',$id);
        $this->db->delete('profile_p');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('store_code',$code);
        return $this->db->get('users');
    }

    function generate_auto_number(){
        $sql = "CREATE TEMPORARY TABLE tmp2 AS
                SELECT CAST(store_code AS UNSIGNED) sd
                FROM profile_p;";
        $this->db->query($sql);
        $sql = "SELECT ifnull(MAX(sd)+1,1) as nomor FROM tmp2 ORDER BY sd DESC";
        return $this->db->query($sql)->row()->nomor;
    }
}
