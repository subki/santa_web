<?php

class Productcolour_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0){
        $sql = "create temporary table tmp as
                select a.colour_code, a.description, 
                case when (select count(b.id) from product_colour_detail b where colour_code=a.colour_code and b.status='Draft')>0 then 
                  'Draft' 
                else a.status end as status
                , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_colour a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.colour_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "colour_code", "asc","", $app, 1);
    }

    function read_data($code){
        $this->db->where('colour_code',$code);
        return $this->db->get('product_colour');
    }
    function update_data($code, $data){
        $this->db->where('colour_code',$code);
        $this->db->update('product_colour',$data);
    }
    function insert_data($data){
        $this->db->insert('product_colour', $data);
    }
    function delete_data($id){
        $this->db->where('colour_code',$id);
        $this->db->delete('product_colour');
    }
    function read_transactions($code){
        //nanti diubah
        $this->db->where('colour_code',$code);
        return $this->db->get('product');
    }

    function get_list_data_sub($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp as
                 select a.id, a.description, a.colour_code, a.status
                , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product_colour_detail a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.colour_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }

    function get_list_data_sub2($app){
        return $this->get_list_data_sub(1, 999999999999, "id", "asc","", $app, 1);
    }

    function read_data_sub($code){
        $this->db->where('id',$code);
        return $this->db->get('product_colour_detail');
    }
    function update_data_sub($code, $data){
        $this->db->where('id',$code);
        $this->db->update('product_colour_detail',$data);
    }
    function insert_data_sub($data){
        $this->db->insert('product_colour_detail', $data);
    }
    function delete_data_sub($id){
        $this->db->where('id',$id);
        $this->db->delete('product_colour_detail');
    }
    function read_transactions_sub($code){
        //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('product_colour_detail');
    }
}
