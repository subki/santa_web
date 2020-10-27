<?php

class Users_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.user_id, a.store_code, a.location_code, a.nik
                , a.fullname, a.user_name, a.user_password, a.token, a.kode_otoritas
                , b.store_name, c.description as location_name
                , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from users a 
	            left join profile_p b on a.store_code = b.store_code 
	            left join location c on a.location_code= c.location_code 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.user_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function read_data($code){
        $sql = "select a.user_id, a.nik, a.fullname, a.user_name, a.store_code
                , AES_DECRYPT(a.user_password,'KEYAES838431') as user_password1
                , a.user_password 
                from users a 
                where a.user_id='$code' ";
        return $this->db->query($sql);
    }
    function cekOtoritas($kode_otoritas){
        $sql = "select a.* 
                from users a 
                where a.kode_otoritas='$kode_otoritas' ";
        return $this->db->query($sql);
    }
    function update_data($code, $data){
        $this->db->where('user_id',$code);
        $this->db->update('users',$data);
    }
    function update_data2($code, $data){
        $pwd = md5($data);
//        $sql = "update users set user_password=AES_ENCRYPT('$data','KEYAES838431')
        $sql = "update users set user_password='$pwd'
                where user_id='$code' ";
        $this->db->query($sql);
    }
    function insert_data($data){
        $sql = "insert into users (nik, fullname, store_code, location_code
                , user_name, user_password, crtby, crtdt) values
                ('".$data['nik']."'
                , '".$data['fullname']."'
                , '".$data['store_code']."'
                , '".$data['location_code']."'
                , '".$data['user_name']."'
                , '".md5($data['user_password'])."'
                , '".$data['crtby']."'
                , '".$data['crtdt']."') ";
//        var_dump($sql);
//        die();
        $this->db->query($sql);
    }
    function delete_data($id){
        $this->db->where('user_id',$id);
        $this->db->delete('users');
    }
    function get_store(){
        return $this->db->get('profile_p');
    }
    function get_location($store){
        $sql = "select a.location_code, a.description location_name, b.store_name
	            from location a 
	            left join (
	              profile_p b inner join cabang c on b.store_code=c.store_code
	            ) on a.location_code=c.location_code 
	            where b.store_code='$store'";
        return $this->db->query($sql);
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('user_id',$code);
        return $this->db->get('users');
    }
}
