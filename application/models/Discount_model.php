<?php

class Discount_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = " create temporary table tmp2 as
                  select a.discount_id, a.customer_type, b.description
                  , a.customer_code, c.customer_name, c.lokasi_stock, a.print_barcode
                  , a.start_date
                  , a.end_date
                  , DATE_FORMAT(a.start_date, '%d/%m/%Y') ak_start_date
                  , DATE_FORMAT(a.end_date, '%d/%m/%Y') ak_end_date
                  ,  a.discount1, ifnull(a.margin_persen,0) margin_persen, a.keterangan, a.status
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from discount a 
	            left join customer_type b on a.customer_type = b.code 
	            left join customer c on a.customer_code=c.customer_code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.discount_id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function load_grid_nobar($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.id, a.discount_id, a.article_code, b.article_name
                , a.print_barcode, a.discount, ifnull(a.margin_persen,0) margin_persen
                , c.customer_code, c.customer_type, d.customer_name
	            from discount_item a 
	            left join article b on a.article_code = b.article_code 
	            left join discount c on a.discount_id=c.discount_id
	            left join customer d on c.customer_code=d.customer_code";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.article_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);
        $sql = "drop table tmp";
        $this->db->query($sql);
        return $data;
    }

    function load_grid_nobar_by_article_customer($page,$rows,$sort,$order,$role,$fltr, $sp){
        $sql = "create temporary table tmp2 as
                SELECT a.article_code, b.customer_code, c.discount_id
                , d.description AS customer_type, a.print_barcode, a.discount, ifnull(a.margin_persen,0) margin_persen
                  , DATE_FORMAT(c.start_date, '%d/%m/%Y') start_date
                  , DATE_FORMAT(c.end_date, '%d/%m/%Y') end_date
                FROM discount_item a 
                INNER JOIN discount_for b ON a.discount_id=b.discount_id
                INNER JOIN discount c ON a.discount_id = c.discount_id
                LEFT JOIN customer_type d ON c.customer_type=d.code 
                WHERE $sp";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.article_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);
        $sql = "drop table tmp";
        $this->db->query($sql);
        return $data;
    }
    function load_grid_location($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.id, a.discount_id, a.location_code, b.description location_name
                  , c.customer_name, c.customer_code
	            from discount_for a
	            left join location b on a.location_code = b.location_code 
	            left join customer c on a.customer_code=c.customer_code";
        $this->db->query($sql);
        $sql = "create temporary table temp as
                select a.*
	            from tmp2 a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);
        $sql = "select a.*,
	            (select count(a1.discount_id) from temp a1 ) as total 
	            from temp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;

        $data = $this->db->query($sql)->result();
        $sql = "drop table temp";
        $this->db->query($sql);
        return $data;
    }
    function get_product($page,$rows,$sort,$order,$role,$fltr, $customer_code){
        $sql = "create temporary table tmp2 as
                select a.article_code, a.customer_code, a.customer_type, b.article_name
	            from category_article a 
	            left join article b on a.article_code=b.article_code 
	            where a.customer_code='$customer_code'
	            GROUP BY a.article_code, a.customer_type";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.article_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);
        $sql = "drop table tmp";
        $this->db->query($sql);
        return $data;
    }
    function get_location($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.customer_code, a.customer_name, a.customer_type, a.gol_customer, b.location_code, b.description location_name
                , concat(a.customer_code,'||',b.location_code) as id
	            from customer a 
	            INNER join location b on a.lokasi_stock=b.location_code ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.location_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);
        $sql = "drop table tmp";
        $this->db->query($sql);
        return $data;
    }
//    function get_regency($code){
//        $this->db->where('province_id',$code);
//        return $this->db->get('regencies');
//    }
    function get_customer_type(){
        return $this->db->get('customer_type');
    }

    function read_data($code){
        $this->db->where('discount_id',$code);
        return $this->db->get('discount');
    }
    function update_data($code, $data){
        $this->db->where('discount_id',$code);
        $this->db->update('discount',$data);
    }
    function insert_data($data){
        $this->db->insert('discount', $data);
    }
    function insert_data_all_location($code, $tipe){
        $sql = "insert into discount_for (discount_id, location_code) select '$code', location_code from location where price_type='$tipe'";
        $this->db->query($sql);
    }
    function delete_data($id){
        $this->db->where('discount_id',$id);
        $this->db->delete('discount');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('discount_id',$code);
        return $this->db->get('discount');
    }
    function generate_auto_number(){
        $sql = "SELECT UPPER(IFNULL(CONCAT('PC4',DATE_FORMAT(NOW(),'%Y'),LPAD(MAX(RIGHT(discount_id,4))+1,4,'0')), 
                CONCAT('PC4',DATE_FORMAT(NOW(),'%Y'),LPAD(1,4,'0')))) AS nomor 
                FROM discount where LEFT(discount_id,7)=CONCAT('PC4',DATE_FORMAT(NOW(),'%Y')) order by discount_id desc";
        return $this->db->query($sql)->row()->nomor;
    }

//    function get_region($page,$rows,$sort,$order,$role,$fltr){
//        $sql = "create temporary table temp as
//                select a.*
//	              , (select count(a1.salesman_id) from salesman_wilayah a1 ) as total
//	            from salesman_wilayah a ";
//        if($fltr!=''){
//            $sql .= $fltr;
//        }
//        $sql .="order by " .$sort." $order
//	            limit ".($page-1)*$rows.",".$rows;
//        $this->db->query($sql);
//        $sql = "select a.*, b.name as provinsi_name, c.name as regency_name
//                from temp a
//                left join provinces b on a.provinsi_id=b.id
//                left join regencies c on a.regency_id=c.id ";
//        return $this->db->query($sql)->result();
//    }
//
    function read_data_nobar($code){
        $this->db->where('id',$code);
        return $this->db->get('discount_item');
    }
    function update_data_nobar($code, $data){
        $this->db->where('id',$code);
        $this->db->update('discount_item',$data);
    }
    function insert_data_nobar($data){
        $this->db->insert('discount_item', $data);
    }
    function delete_data_nobar($id){
        $this->db->where('id',$id);
        $this->db->delete('discount_item');
    }
    function read_data_location($code){
        $this->db->where('id',$code);
        return $this->db->get('discount_for');
    }
//    function update_data_region($code, $data){
//        $this->db->where('id',$code);
//        $this->db->update('salesman_wilayah',$data);
//    }
    function insert_data_location($data){
        $this->db->insert('discount_for', $data);
    }
    function delete_data_location($id){
        $this->db->where('id',$id);
        $this->db->delete('discount_for');
    }
    function copy_discount($from,$code, $user, $tgl){
        $sql = "insert into discount(discount_id, customer_code, customer_type, start_date, end_date, discount1, discount_type, print_barcode, keterangan, status, crtby, crtdt, duplicate_from, margin_persen)
                select '$code', customer_code, customer_type, '', '', discount1, discount_type, print_barcode, keterangan, 'OPEN', '$user', '$tgl', '$from', margin_persen
                from discount
                where discount_id='$from';";
        if($this->db->query($sql)){
            $sql = "insert into discount_for (discount_id, customer_code, location_code)
                    select '$code', customer_code, location_code from discount_for where discount_id='$from';";
            if($this->db->query($sql)){
                $sql = "insert into discount_item (discount_id, article_code, print_barcode, discount, margin_persen)
                        select '$code', article_code, print_barcode, discount, margin_persen from discount_item where discount_id='$from';";
                if($this->db->query($sql)){
                    return true;
                }else {
                    $this->db->query("delete from discount where discount_id='$code'");
                    $this->db->query("delete from discount_for where discount_id='$code'");
                }
            }else {
                $this->db->query("delete from discount where discount_id='$code'");
                return false;
            }
        }else return false;
    }
}
