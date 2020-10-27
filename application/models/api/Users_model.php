<?php

class Users_model extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

    function getUserByNik($nik){
        $sql = "select * from employee where user_id='$nik'";
        return $this->db->query($sql);
    }
    function cek_username($nik){
        $sql = "select * from employee where username='$nik'";
        return $this->db->query($sql);
    }
    function update_password($nik, $data){
        $this->db->where('user_id',$nik);
        $this->db->update('employee',$data);
    }

    function get_list_employee($outlet_code, $offset, $search, $tipe){
        $sql = "select a.fullname, a.no_telepon, a.alamat
                  , a.outlet_code, a.role, a.req_reset, a.user_id
                from employee a
	            where (
	              a.fullname like '%$search%' or 
	              a.user_id like '%$search%' or
	              a.no_telepon like '%$search%'
	            )
	            and a.req_reset = '$tipe'
	            order by a.fullname asc
	            limit 20 offset $offset";
        $data = $this->db->query($sql);
        return $data;
    }
}
