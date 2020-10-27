<?php

class Privillege_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "select a.users_group_id as id, a.group_name as text, 0 as parent, 0 chk
                  , a.allow_add, a.allow_edit, a.allow_delete, a.allow_print
                  , a.allow_approve
                  , a.allow_approve2
                  , a.allow_approve3
                  , a.allow_approve4
                  , a.allow_approve5
                  , a.allow_download
                  , a.allow_unposting
	            from users_group a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function show_users_group($page,$rows,$sort,$order,$role,$fltr){
        $sql = "select a.id, a.users_group_id, a.users_group_detail_id, a.user_id
                  , a.allow_add, a.allow_edit, a.allow_delete, a.allow_print
                  , a.allow_approve
                  , a.allow_approve2
                  , a.allow_approve3
                  , a.allow_approve4
                  , a.allow_approve5
                  , a.allow_download
                  , a.allow_unposting, u.fullname
	            from users_group_detail_user a 
	            left join users u on a.user_id = u.user_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
//        var_dump($sql);
//        die();
        return $this->db->query($sql)->result();
    }
    function get_children($users_group_id){
        $sql = "select a.app_id as id, CONCAT(a.app_id,'-',b.app_name) as text, b.icon as iconCls, a.users_group_detail_id
                from users_group_detail a
                left join app b on a.app_id=b.app_id
                where  a.users_group_id=$users_group_id
                order by a.app_id";
        return $this->db->query($sql);
    }
    function get_app($users_group_id){
        $sql = "select a.app_id as id, CONCAT(a.app_id,'-',a.app_name) as text, a.icon as iconCls
                from app a
                where a.app_id not in (select app_id from users_group_detail where users_group_id=$users_group_id)
                order by a.app_id";
        return $this->db->query($sql);
    }
    function get_children2($users_group_id){
        $sql = "SELECT a.users_group_id AS id, CONCAT(a.user_id,'-',u.fullname) AS text, b.icon AS iconCls, a.user_id
                FROM users_group_detail_user a
                LEFT JOIN app b ON a.users_group_id=b.app_id
                INNER JOIN users u ON u.user_id=a.user_id
                WHERE  a.users_group_id=$users_group_id
                GROUP BY a.user_id
                ORDER BY a.users_group_id";
        return $this->db->query($sql);
    }
    function get_app2($users_group_id){
        $sql = "select a.user_id as id, CONCAT(a.user_id,'-',a.fullname) as text
                from users a
                where a.user_id not in (select user_id from users_group_detail_user where users_group_id=$users_group_id)
                order by a.user_id";
        return $this->db->query($sql);
    }
    function read_data($code){
        $sql = "select a.user_id, a.nik, a.fullname, a.user_name
                , AES_DECRYPT(a.user_password,'KEYAES838431') as user_password1
                , a.user_password
                from users a 
                where a.user_id='$code' ";
        return $this->db->query($sql);
    }
    function read_app_by_name($name){
        $sql = "select a.*
                from app a 
                where a.url='$name' ";
        return $this->db->query($sql);
    }
    function update_data($code, $data){
        $this->db->where('user_id',$code);
        $this->db->update('users',$data);
    }
    function update_users_group_det_user($code, $data){
        $this->db->where('id',$code);
        $this->db->update('users_group_detail_user',$data);
    }
    function update_group($code, $data){
        $this->db->where('users_group_id',$code);
        $this->db->update('users_group',$data);
    }
    function update_data2($code, $data){
        $pwd = md5($data);
//        $sql = "update users set user_password=AES_ENCRYPT('$data','KEYAES838431')
        $sql = "update users set user_password='$data' 
                where user_id='$code' ";
        $this->db->query($sql);
    }
    function insert_data($data){
        $this->db->insert('users_group', $data);
    }
    function save_users_group_detail($data, $grp){
        if($this->db->insert('users_group_detail', $data)){
            $insert_id = $this->db->insert_id();
            $sql = "insert into users_group_detail_user (users_group_id, users_group_detail_id, user_id)
                    select $grp, $insert_id, user_id from users_group_detail_user where users_group_id=$grp group by user_id ";
            if($this->db->query($sql)) {
                $this->db->query("update users_group_detail_user a, users_group g 
                            set a.allow_add=g.allow_add
                              , a.allow_edit=g.allow_edit
                              , a.allow_print=g.allow_print
                              , a.allow_delete=g.allow_delete
                              , a.allow_download=g.allow_download
                              , a.allow_unposting=g.allow_unposting
                              , a.allow_approve=g.allow_approve
                              , a.allow_approve2=g.allow_approve2
                              , a.allow_approve3=g.allow_approve3
                              , a.allow_approve4=g.allow_approve4
                              , a.allow_approve5=g.allow_approve5
                           where a.users_group_id = g.users_group_id 
                           and a.users_group_id=$grp ");
            }
        }
    }
    function change_permission($grp, $field, $nilai){
        $sql = "update users_group set $field=$nilai where users_group_id=$grp ";
        if($this->db->query($sql)) {
            $this->db->query("update users_group_detail_user a, users_group g 
                            set a.allow_add=g.allow_add
                              , a.allow_edit=g.allow_edit
                              , a.allow_print=g.allow_print
                              , a.allow_delete=g.allow_delete
                              , a.allow_download=g.allow_download
                              , a.allow_unposting=g.allow_unposting
                              , a.allow_approve=g.allow_approve
                              , a.allow_approve2=g.allow_approve2
                              , a.allow_approve3=g.allow_approve3
                              , a.allow_approve4=g.allow_approve4
                              , a.allow_approve5=g.allow_approve5
                           where a.users_group_id = g.users_group_id 
                           and a.users_group_id=$grp ");
        }
    }
    function save_users_group_detail2($grp, $user){
        $sql = "insert into users_group_detail_user (users_group_id, users_group_detail_id, user_id) 
                          select $grp, users_group_detail_id, '$user' from users_group_detail where users_group_id=$grp";
        if($this->db->query($sql)) {
            $this->db->query("update users_group_detail_user a, users_group g 
                            set a.allow_add=g.allow_add
                              , a.allow_edit=g.allow_edit
                              , a.allow_print=g.allow_print
                              , a.allow_delete=g.allow_delete
                              , a.allow_download=g.allow_download
                              , a.allow_unposting=g.allow_unposting
                              , a.allow_approve=g.allow_approve
                              , a.allow_approve2=g.allow_approve2
                              , a.allow_approve3=g.allow_approve3
                              , a.allow_approve4=g.allow_approve4
                              , a.allow_approve5=g.allow_approve5
                           where a.users_group_id = g.users_group_id 
                           and a.users_group_id=$grp ");
        }
    }
    function save_users_group_det_user($data){
        $this->db->insert('users_group_detail_user', $data);
    }
    function remove_users_group_detail($group_id, $app_id){
        $sql = "delete from users_group_detail_user where users_group_id=$group_id and users_group_detail_id IN 
                (select users_group_detail_id from users_group_detail where users_group_id=$group_id and app_id='$app_id' ) ";
        if($this->db->query($sql)) {
            $this->db->where('users_group_id', $group_id);
            $this->db->where('app_id', $app_id);
            $this->db->delete('users_group_detail');
        }
    }
    function remove_users_group_detail2($group_id, $app_id){
        $this->db->where('users_group_id',$group_id);
        $this->db->where('user_id',$app_id);
        $this->db->delete('users_group_detail_user');
    }
    function delete_users_group_det_user($id){
        $this->db->where('id',$id);
        $this->db->delete('users_group_detail_user');
    }
    function delete_data($id){
        $this->db->where('users_group_id',$id);
        $this->db->delete('users_group');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('users_group_id',$code);
        return $this->db->get('users_group_detail');
    }
    function get_users($code){
        $sql = "select a.user_id, a.nik, a.fullname, a.user_name
                from users a 
                where a.user_id not in (select user_id from users_group_detail_user where users_group_detail_id=$code) ";
        return $this->db->query($sql);
    }
}
