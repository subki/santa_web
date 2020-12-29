<?php

class Customer_model extends CI_Model {

    public $query;
	public function __construct(){

        parent::__construct();
        $this->query="select 
				  a.status, a.gol_customer, c.description customer_type_name
				  , a.customer_class, a.customer_code
                  , a.customer_name, d.nama_company head_customer_name
                  , a.parent_cust, h.customer_name as parent_name
                  , a.address1, a.address2, e.name provinsi, f.name kota
                  , a.zip, a.fax, a.contact_person, a.phone1, a.phone2, a.phone3
                  , b.salesman_name, a.top_day
                  , a.pkp, a.npwp, a.nama_pkp, a.alamat_pkp
                  , g.description lokasi
                  , a.credit_limit, a.outstanding, a.gl_account, a.cust_fk, a.info_cust
                  , (a.credit_limit-a.outstanding) credit_remain
                  , a.toc_day
				  , a.provinsi_id
				  , a.regency_id
				  , a.customer_type
				  , a.lokasi_stock, g.description as lokasi_stock_name
				  , a.head_customer_id
                  , a.salesman_id
                  , a.margin_persen
                  , a.beda_fp
                  , c.diskon 
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from customer a 
	            left join salesman b on a.salesman_id=b.salesman_id
				left join customer_type c on a.customer_type=c.code
				left join head_company_customer d on a.head_customer_id=d.head_customer_id
				left join provinces e on a.provinsi_id=e.id
				left join regencies f on a.regency_id=f.id and a.provinsi_id=f.province_id
				left join location g on a.lokasi_stock=g.location_code 
				left join customer h on a.parent_cust=h.customer_code 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0, $golongan=""){
        $sql = "create temporary table tmp2 as $this->query ";
        if($golongan!=""){
            $sql .= "where a.gol_customer='$golongan'";
        }
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
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "customer_code", "asc","", $app, 1,"");
    }
    function get_regency($code){
        $this->db->where('province_id',$code);
        return $this->db->get('regencies');
    }
    function get_provinsi(){
        return $this->db->get('provinces');
    }
    function get_customer_type(){
        return $this->db->get('customer_type');
    }
    function get_salesman($prov, $regency){
//        $sql = "select a.* from salesman a
//                left join salesman_wilayah b on a.salesman_id=b.salesman_id
//                where b.provinsi_id='$prov' and b.regency_id='$regency'
//                group by a.salesman_id";
        $sql = "select a.* from salesman a ";
        return $this->db->query($sql);
    }
    function get_location_stock(){
        return $this->db->get('location');
    }
    function get_head_customer(){
        return $this->db->get('head_company_customer');
    }
    function get_parent_cust(){
        $this->db->where('status','Aktif');
        return $this->db->get('customer');
    }

    function read_data($code){
//        $this->db->where('customer_code',$code);
//        return $this->db->get('customer');
        return $this->db->query($this->query." where a.customer_code='$code'");
    }
    function read_data_by_lokasi($code){
        return $this->db->query($this->query." where a.lokasi_stock='$code'");
    }
    function update_data($code, $data){
        $this->db->where('customer_code',$code);
        $this->db->update('customer',$data);
    }
    function insert_data($data){
        $this->db->insert('customer', $data);
    }
    function insert_status($data){
        $this->db->insert('customer_reason', $data);
    }
    function delete_data($id){
        $this->db->where('customer_code',$id);
        $this->db->delete('customer');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('customer_code',$code);
        return $this->db->get('customer');
    }
    function generate_auto_number($nama, $gol){
        $p1 = $nama[0];
        $p2 = "";
        if($gol=="WHOLESALES") $p2="W";
        else if($gol=="COUNTER") $p2="C";
        else if($gol=="SHOWROOM") $p2="S";
        else if($gol=="CUSTOMER ONLINE") $p2="O";
        else $p2="X";
        $sql = "SELECT UPPER(IFNULL(CONCAT('$p1','$p2',LPAD(MAX(RIGHT(customer_code,3))+1,3,'0')), 
                CONCAT('$p1','$p2',LPAD(1,3,'0')))) AS nomor 
                FROM customer where LEFT(customer_code,2)='".$p1.$p2."' order by customer_code desc";
        return $this->db->query($sql)->row()->nomor;
    }

    function get_customer_sales($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as 
                 select a.id, a.customer_code, a.salesman_id, b.salesman_name
                 , a.periode as tanggalan
                 , DATE_FORMAT(a.periode, '%d/%m/%Y') periode 
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from customer_salesman a 
	            left join salesman b on a.salesman_id=b.salesman_id
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function check_insert_salesman($code,$salesman,$prd){
        $sql = "select * from customer_salesman where customer_code='$code' and salesman_id='$salesman' and periode='$prd'";
        return $this->db->query($sql);
    }
    function read_data_customer_sales($code){
        $this->db->where('id',$code);
        return $this->db->get('customer_salesman');
    }
    function update_data_customer_sales($code, $data){
        $this->db->where('id',$code);
        $this->db->update('customer_salesman',$data);
    }
    function insert_data_customer_sales($data){
        $this->db->insert('customer_salesman', $data);
    }
    function delete_data_customer_sales($id){
        $this->db->where('id',$id);
        $this->db->delete('customer_salesman');
    }

    function update_sales_customer($customer_code){
        $sql ="select * from customer_salesman where customer_code='$customer_code' order by periode desc limit 1";
        $re =$this->db->query($sql);
        if($re->num_rows()>0){
            $sql ="update customer set salesman_id='".$re->row()->salesman_id."' 
             where customer_code='$customer_code' ";
            $this->db->query($sql);
        }
    }





    function get_contact($page,$rows,$sort,$order,$role,$fltr){
        $sql = " create temporary table tmp2 as 
                select a.id, a.customer_code, a.contact, a.no_telp, a.dept, a.keterangan
	            from contact_customer a ";
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

    function read_data_contact($code){
        $this->db->where('id',$code);
        return $this->db->get('contact_customer');
    }
    function update_data_contact($code, $data){
        $this->db->where('id',$code);
        $this->db->update('contact_customer',$data);
    }
    function insert_data_contact($data){
        $this->db->insert('contact_customer', $data);
    }
    function delete_data_contact($id){
        $this->db->where('id',$id);
        $this->db->delete('contact_customer');
    }


    function get_article($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as 
                select a.id, a.customer_code, a.article_code, a.customer_type, a.discount, a.level_category, a.print_barcode
                  , b.customer_name, c.article_name, d.description
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt 
	            from category_article a 
	            left join customer b on a.customer_code=b.customer_code
	            left join article c on a.article_code=c.article_code
	            left join customer_type d on a.customer_type=d.code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id  ";
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

    function copy_article($from, $to, $user, $tgl){
        $sql = "insert into category_article (article_code, customer_code, discount, level_category, print_barcode, crtby, crtdt)
                select article_code, '$to', discount, level_category, print_barcode, '$user', '$tgl'
                from category_article where customer_code='$from'
                and article_code not in(select article_code from category_article where customer_code='$to')";
        return $this->db->query($sql);
    }

    function read_data_article($code){
        $this->db->where('id',$code);
        return $this->db->get('category_article');
    }
    function update_data_article($code, $data){
        $this->db->where('id',$code);
        $this->db->update('category_article',$data);
    }
    function insert_data_article($data){
        $this->db->insert('category_article', $data);
    }
    function delete_data_article($id){
        $this->db->where('id',$id);
        $this->db->delete('category_article');
    }
}
