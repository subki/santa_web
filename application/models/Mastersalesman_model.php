<?php

class Mastersalesman_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.salesman_id, a.nik, a.salesman_name
                  , a.address, b.name nm_prov, c.name nm_regency
                  , a.zip, a.phone1, a.phone2, a.head_salesman
                  , a.provinsi_id,  a.regency_id
                   , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from salesman a 
	            left join provinces b on a.provinsi_id = b.id 
	            left join regencies c on a.regency_id=c.id 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.salesman_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "salesman_id", "asc","", $app, 1);
    }
    function get_regency($code){
        $this->db->where('province_id',$code);
        return $this->db->get('regencies');
    }
    function get_provinsi(){
        return $this->db->get('provinces');
    }

    function read_data($code){
        $this->db->where('salesman_id',$code);
        return $this->db->get('salesman');
    }
    function update_data($code, $data){
        $this->db->where('salesman_id',$code);
        $this->db->update('salesman',$data);
    }
    function insert_data($data){
        $this->db->insert('salesman', $data);
    }
    function delete_data($id){
        $this->db->where('salesman_id',$id);
        $this->db->delete('salesman');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('salesman_id',$code);
        return $this->db->get('salesman');
    }
    function generate_auto_number($nama){
        $sql = "SELECT UPPER(IFNULL(CONCAT(LEFT('$nama',1),LPAD(MAX(RIGHT(salesman_id,5))+1,5,'0')), 
                CONCAT(LEFT('$nama',1),LPAD(1,5,'0')))) AS nomor 
                FROM salesman where LEFT(salesman_id,1)=LEFT('$nama',1) order by salesman_id desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function get_region($page,$rows,$sort,$order,$role,$fltr){
        $sql = " create temporary table tmp2 as 
                select a.*, b.name as provinsi_name, c.name as regency_name
	            from salesman_wilayah a 
                left join provinces b on a.provinsi_id=b.id 
                left join regencies c on a.regency_id=c.id";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.salesman_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data_region($code){
        $this->db->where('id',$code);
        return $this->db->get('salesman_wilayah');
    }
    function update_data_region($code, $data){
        $this->db->where('id',$code);
        $this->db->update('salesman_wilayah',$data);
    }
    function insert_data_region($data){
        $this->db->insert('salesman_wilayah', $data);
    }
    function delete_data_region($id){
        $this->db->where('id',$id);
        $this->db->delete('salesman_wilayah');
    }
}
