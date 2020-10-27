<?php

class Mastersupplier_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.supplier_code, a.tipe_supplier
                  , a.supplier_name, a.allow_return, a.contact_person
                  , a.phone, a.address
                  , b.name as provinsi, c.name as kota
                  , a.zip, a.fax, a.email_address, a.status
                  , a.top_day, a.pkp, a.npwp, a.nama_pkp
                  , a.alamat_pkp, a.bank_name, a.bank_account
                  , a.gl_account
                  , a.provinsi_id, a.regency_id
                  , a.currency, a.lead_day
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from supplier a 
	            left join provinces b on a.provinsi_id=b.id
	            left join regencies c on a.regency_id=c.id and b.id=c.province_id 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.supplier_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "supplier_code", "asc","", $app, 1);
    }
    function get_regency($code){
        $this->db->where('province_id',$code);
        return $this->db->get('regencies');
    }
    function get_provinsi(){
        return $this->db->get('provinces');
    }

    function read_data($code){
        $this->db->where('supplier_code',$code);
        return $this->db->get('supplier');
    }
    function update_data($code, $data){
        $this->db->where('supplier_code',$code);
        $this->db->update('supplier',$data);
    }
    function insert_data($data){
        $this->db->insert('supplier', $data);
    }
    function delete_data($id){
        $this->db->where('supplier_code',$id);
        $this->db->delete('supplier');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('supplier_code',$code);
        return $this->db->get('product');
    }
    function generate_auto_number($nama){
        $sql = "SELECT UPPER(IFNULL(CONCAT(LEFT('$nama',1),LPAD(MAX(RIGHT(supplier_code,5))+1,5,'0')), 
                CONCAT(LEFT('$nama',1),LPAD(1,5,'0')))) AS nomor 
                FROM supplier where LEFT(supplier_code,1)=LEFT('$nama',1) order by supplier_code desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function get_contact($page,$rows,$sort,$order,$role,$fltr){
        $sql = " create temporary table tmp2 as 
                select a.id, a.supplier_code, a.contact, a.no_telp, a.dept, a.keterangan
	            from supplier_contact a ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.supplier_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data_contact($code){
        $this->db->where('id',$code);
        return $this->db->get('supplier_contact');
    }
    function update_data_contact($code, $data){
        $this->db->where('id',$code);
        $this->db->update('supplier_contact',$data);
    }
    function insert_data_contact($data){
        $this->db->insert('supplier_contact', $data);
    }
    function delete_data_contact($id){
        $this->db->where('id',$id);
        $this->db->delete('supplier_contact');
    }


    function get_products($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.id, a.product_code, a.supplier_code, b.supplier_name
                  , c.product_name, c.sku
                  , a.main_supplier, a.uom_code, d.description uom_name, d.uom_id
                  , a.unit_price, a.disc_persen, a.ppn_persen
                  , a.hpp_uom_purchase, a.hpp_uom_stock, a.mu_persen, a.gp_persen, a.std_price
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from purchase a 
	            inner join supplier b on a.supplier_code=b.supplier_code
	            INNER JOIN product c on a.product_code=c.product_code
	            INNER JOIN product_uom d on a.uom_code=d.uom_code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.product_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_sku($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                  select a.product_code, a.sku, a.product_name, a.satuan_jual, b.uom_id as satuan_jual_id
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from product a 
	            INNER JOIN product_uom b on a.satuan_jual=b.uom_code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.product_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data_product($supplier, $product){
        $this->db->where('supplier_code',$supplier);
        $this->db->where('product_code',$product);
        return $this->db->get('purchase');
    }
    function read_data_product2($id){
        $this->db->where('id',$id);
        return $this->db->get('purchase');
    }
    function update_data_product($supplier, $product, $data){
        $this->db->where('supplier_code',$supplier);
        $this->db->where('product_code',$product);
        $this->db->update('purchase',$data);
    }
    function insert_data_product($data){
        $this->db->insert('purchase', $data);
    }
    function delete_data_product($id){
        $this->db->where('id',$id);
        $this->db->delete('purchase');
    }

}
