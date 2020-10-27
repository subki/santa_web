<?php

class Headcustomer_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as 
                select a.head_customer_id, a.nama_company
                  , b.description, a.customer_type, a.market_type
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from head_company_customer a 
	            left join customer_type b on a.customer_type=b.code 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.head_customer_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "head_customer_id", "asc","", $app, 1);
    }
    function get_customers($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as 
                select a.head_customer_id, a.customer_code, a.customer_name, a.address1
                  , pr.name as prov, rg.name as regency
	            from customer a 
	            left join provinces pr on a.provinsi_id=pr.id
	            left join regencies rg on a.regency_id=rg.id ";
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
    }
    function get_regency($code){
        $this->db->where('province_id',$code);
        return $this->db->get('regencies');
    }
    function get_provinsi(){
        return $this->db->get('provinces');
    }
    function get_customertype(){
        return $this->db->get('customer_type');
    }

    function read_data($code){
        $this->db->where('head_customer_id',$code);
        return $this->db->get('head_company_customer');
    }
    function update_data($code, $data){
        $this->db->where('head_customer_id',$code);
        $this->db->update('head_company_customer',$data);
    }
    function insert_data($data){
        $this->db->insert('head_company_customer', $data);
    }
    function delete_data($id){
        $this->db->where('head_customer_id',$id);
        $this->db->delete('head_company_customer');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('head_customer_id',$code);
        return $this->db->get('customer');
    }
    function generate_auto_number($nama){
        $sql = "SELECT UPPER(IFNULL(CONCAT(LEFT('$nama',1),LPAD(MAX(RIGHT(salesman_id,5))+1,5,'0')), 
                CONCAT(LEFT('$nama',1),LPAD(1,5,'0')))) AS nomor 
                FROM salesman where LEFT(salesman_id,1)=LEFT('$nama',1) order by salesman_id desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function get_region($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table temp as 
                select a.*
	              , (select count(a1.salesman_id) from salesman_wilayah a1 ) as total
	            from salesman_wilayah a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        $this->db->query($sql);
        $sql = "select a.*, b.name as provinsi_name, c.name as regency_name 
                from temp a 
                left join provinces b on a.provinsi_id=b.id 
                left join regencies c on a.regency_id=c.id ";
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
