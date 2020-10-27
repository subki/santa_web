<?php

class Welcome_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role){
        $sql = "select a.*,
	            (select count(a1.store_code) from profile_p a1 ) as total
	            from profile_p a 
	            order by ".$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    public function getMenu($param, $param1, $param2) {
        $q = "SELECT a.users_group_id, a.app_id, c.app_name, c.url, c.icon, u.user_id, c.parent_id
                , sum(ifnull(u.allow_add,0)) tambah
                , sum(ifnull(u.allow_edit,0)) ubah
                , sum(ifnull(u.allow_delete,0)) hapus
                , sum(ifnull(u.allow_print,0)) cetak
                , sum(ifnull(u.allow_approve,0)) approve
                , sum(ifnull(u.allow_approve2,0)) approve2
                , sum(ifnull(u.allow_approve3,0)) approve3
                , sum(ifnull(u.allow_approve4,0)) approve4
                , sum(ifnull(u.allow_approve5,0)) approve5
                , sum(ifnull(u.allow_download,0)) download
                , sum(ifnull(u.allow_unposting,0)) unposting
            FROM users_group_detail a
            LEFT JOIN app c ON c.app_id = a.app_id
            RIGHT JOIN users_group_detail_user u on a.users_group_detail_id=u.users_group_detail_id
            WHERE c.parent_id='$param' ";
        if($param!="root"){
            $q .= " AND a.users_group_id = '$param1'
                    AND u.user_id='$param2' ";
        }
        $q .= " GROUP BY a.app_id
                ORDER BY c.seq ASC ";
        return $this->db->query($q)->result_array();
    }
    public function getMenu33($param, $param1, $param2) {
        $q = "SELECT a.users_group_id, a.app_id, c.app_name, c.url, c.icon, a.allow_add, a.allow_update, a.allow_delete,
                b.user_id,
                c.parent_id
            FROM users_group_detail a
            LEFT JOIN users_group_user b ON b.users_group_id=a.users_group_id
            LEFT JOIN app c ON c.app_id = a.app_id
            WHERE c.parent_id='$param'
            AND a.users_group_id = '$param1'
            AND b.user_id = '$param2'
            ORDER BY c.seq ASC ";
        return $this->db->query($q)->result_array();
    }

    function getUserGroupId($users_id) {
        $q = "select a.* 
                from users_group a
                right join users_group_detail_user b on a.users_group_id=b.users_group_id
                where b.user_id='$users_id'
                group by a.users_group_id";
        return $this->db->query($q)->result_array();
    }

}
