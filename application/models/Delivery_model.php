<?php

class Delivery_model extends CI_Model {

    public $query;
	public function __construct(){

        parent::__construct();
        $this->query = " select a.docno
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%m/%Y') ak_doc_date
                  , a.receive_date, DATE_FORMAT(a.receive_date, '%d/%m/%Y %T') ak_recv_date
                  , a.tgl_promo, DATE_FORMAT(a.tgl_promo, '%d/%m/%Y') ak_tgl_promo
                  
                  , a.from_store_code
                  , p1.store_name as from_store_name, p1.store_address from_store_address
                  , c1.pkp from_pkp, c1.customer_name from_customer_name, c1.address1 from_customer_address
                  
                  , a.from_location_code
                  , l1.description as from_location_name
                  
                  , a.to_store_code
                  , p2.store_name as to_store_name, p2.store_address to_store_address
                  , c2.pkp to_pkp, c2.customer_name to_customer_name, c2.address1 to_customer_address
                  
                  , a.to_location_code
                  , l2.description as to_location_name
                  
                  , a.do_type
                  , a.status
                  , a.golongan_do
                  , a.keterangan
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , ifnull(u3.fullname,a.rcvby) as rcvby
	            from do_header a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id 
	            left join users u3 on a.rcvby=u3.user_id 
	            left join location l1 on a.from_location_code=l1.location_code
	            left join location l2 on a.to_location_code=l2.location_code
	            left join profile_p p1 on a.from_store_code=p1.store_code
	            left join profile_p p2 on a.to_store_code=p2.store_code 
	            left join customer c1 on a.from_location_code=c1.lokasi_stock
	            left join customer c2 on a.to_location_code=c2.lokasi_stock ";
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                $this->query";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.docno) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_location($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.location_code, a.description location_name, b.store_name, b.store_code
	            from location a 
	            inner join (
	              profile_p b inner join cabang c on b.store_code=c.store_code
	            ) on a.location_code=c.location_code ";
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
        return $this->db->query($sql)->result();
    }
    function get_store($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                select a.store_code, a.store_name
	            from profile_p a ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.store_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function generate_auto_number($prefix,$tgl,$loc){
        $sql = "SELECT IFNULL(CONCAT('$prefix',RPAD('$loc',5,' '),DATE_FORMAT('$tgl','%m%y'),LPAD(MAX(RIGHT(docno,3))+1,3,'0')),
                CONCAT('$prefix',RPAD('$loc',5,' '),DATE_FORMAT('$tgl','%m%y'),LPAD(1,3,'0'))) AS nomor 
                FROM do_header WHERE RIGHT(LEFT(docno,11),4)=DATE_FORMAT('$tgl','%m%y') ORDER BY docno DESC";
        return $this->db->query($sql)->row()->nomor;
    }
    function generate_auto_number_innerprefix($tanggal,$from,$to){
        $sql = "SELECT IFNULL(CONCAT(RPAD('$from',3,' '),'2',RPAD('$to',3,' '),DATE_FORMAT('$tanggal','%y%m'),LPAD(MAX(RIGHT(docno,3))+1,3,'0')),
                CONCAT(RPAD('$from',3,' '),'2',RPAD('$to',3,' '),DATE_FORMAT('$tanggal','%y%m'),LPAD(1,3,'0'))) AS nomor 
                FROM do_header 
                WHERE LEFT(docno,7) = CONCAT(RPAD('$from',3,' '),'2',RPAD('$to',3,' '))
                AND RIGHT(LEFT(docno,11),4)=DATE_FORMAT('$tanggal','%y%m') ORDER BY docno DESC";
        return $this->db->query($sql)->row()->nomor;
    }
    function generate_auto_number_innerprefix_withI($tanggal,$from,$to){
        $sql = "SELECT IFNULL(CONCAT(RPAD('$from',3,' '),'I2',RPAD('$to',3,' '),DATE_FORMAT('$tanggal','%y%m'),LPAD(MAX(RIGHT(docno,3))+1,3,'0')),
                CONCAT(RPAD('$from',3,' '),'I2',RPAD('$to',3,' '),DATE_FORMAT('$tanggal','%y%m'),LPAD(1,3,'0'))) AS nomor 
                FROM do_header 
                WHERE LEFT(docno,8) = CONCAT(RPAD('$from',3,' '),'I2',RPAD('$to',3,' '))
                AND RIGHT(LEFT(docno,12),4)=DATE_FORMAT('$tanggal','%y%m') ORDER BY docno DESC";
        return $this->db->query($sql)->row()->nomor;
    }

    function read_data($code){
        return $this->db->query($this->query." where a.docno='$code'");
//        $this->db->where('docno',$code);
//        return $this->db->get('do_header');
    }
    function read_data_header_by_field($field,$val){
        $this->db->where($field,$val);
        return $this->db->get('do_header');
    }
    function update_data($code, $data){
        $this->db->where('docno',$code);
        $this->db->update('do_header',$data);
    }
    function insert_data($data){
        $this->db->insert('do_header', $data);
    }

    function delete_data($id){
        $this->db->where('docno',$id);
        $this->db->delete('do_header');

        $this->db->where('docno',$id);
        $this->db->delete('do_detail');
    }
    function read_transactions($code){
        $this->db->where('docno',$code);
        $this->db->where('status','transfered');
        return $this->db->get('do_detail');
    }

    function load_grid_nobar2($app){
        return $this->load_grid_nobar(1, 999999999999, "id", "asc","", $app,1);
    }

    function load_grid_nobar($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table temp as
                select a.docno
                  , DATE_FORMAT(dh.doc_date, '%d/%m/%Y') ak_doc_date, dh.doc_date
                  , DATE_FORMAT(dh.tgl_promo, '%d/%m/%Y') ak_tgl_promo, dh.tgl_promo
                  , a.nobar, d.uom_id, a.qty, dh.keterangan as ket_header
                  , a.id, c.product_code, b.nmbar, a.qty_rcv, a.qty_rev, a.status, a.keterangan
                  , c.satuan_stock, d1.uom_id uom_jual
                  , e.article_name, e.article_code
                  , a.retail_price, a.discount, a.net_price
                  , e.hpp1,e.hpp2, e.hpp_ekspedisi
	              /**, (select count(a1.nobar) from do_detail a1 ) as total**/
	            from do_detail a 
	            INNER JOIN do_header dh on a.docno=dh.docno
	            inner join (
	              product_barang b
	              inner join product c on b.product_id=c.id
	              left join product_uom d on c.satuan_stock=d.uom_code
	              left join product_uom d1 on c.satuan_jual=d1.uom_code
	              left join article e on c.article_code = e.article_code
	            ) on a.nobar = b.nobar ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from temp a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);


        $sql = "select a.*,
	            (select count(a1.nobar) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;

        $data = $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
        $sql = "drop table tmp";
        $this->db->query($sql);
        $sql = "drop table temp";
        $this->db->query($sql);
        return $data;
    }


    function get_product($page,$rows,$sort,$order,$role,$fltr,$code,$loc,$prd){
        $sql = "create temporary table tmp2 as
                select a.product_id, a.nobar, a.nmbar, b.satuan_jual, c.uom_id, c.description as satuan_jual_desc
                , c1.uom_id uom_stock, c1.description as satuan_stock_desc, b.product_code
	            from product_barang a 
	            INNER JOIN product b on a.product_id = b.id 
	            INNER JOIN product_uom c on b.satuan_jual=c.uom_code
	            INNER JOIN product_uom c1 on b.satuan_stock=c1.uom_code
	            INNER JOIN stock s ON a.nobar=s.nobar and s.periode='$prd' and s.location_code='$loc'
	            where a.nobar not in (select c.nobar from do_detail c where c.docno='$code')";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.product_id, a.nobar, a.nmbar, a.satuan_jual, a.uom_id, a.satuan_jual_desc
                , a.product_code
	            , (select count(a1.nobar) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function insert_data_nobar($data){
        $this->db->insert('do_detail', $data);
    }

    function read_data_nobar($code){
        $this->db->where('id',$code);
        return $this->db->get('do_detail');
    }

    function edit_data_nobar($code, $data){
        $this->db->where('id',$code);
        $this->db->update('do_detail',$data);
    }
    function update_status_data_detail($code, $data){
        $this->db->where('docno',$code);
        $this->db->update('do_detail',$data);
    }



















    function load_grid_location($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table temp as
                select a.id, a.discount_id, a.location_code, b.description location_name
	              , (select count(a1.location_code) from discount_for a1 ) as total
	            from discount_for a 
	            left join location b on a.location_code = b.location_code ";
        $this->db->query($sql);
        $sql = "select a.* from temp a";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;

        $data = $this->db->query($sql)->result();
        $sql = "drop table temp";
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

    function insert_data_all_location($code, $tipe){
        $sql = "insert into discount_for (discount_id, location_code) select '$code', location_code from location where price_type='$tipe'";
        $this->db->query($sql);
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
    function delete_data_nobar($id){
        $this->db->where('id',$id);
        $this->db->delete('do_detail');
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

    function checkQtyReceive($code){
        $sql = "select * from do_detail where docno='$code'";
        $data =  $this->db->query($sql);
        if($data->num_rows()>0){
            $sql .= " and qty_rcv=0";
            $data =  $this->db->query($sql);
            if($data->num_rows()>0){
                return 2;
            }else return 0;
        }else{
            return 1;
        }
    }
    function checkItemsDO($code){
        $sql = "select * from do_detail where docno='$code'";
        return $this->db->query($sql);
    }
    function updateQtyRecv($docno){
        $sql = "update do_detail set qty_rcv=qty where docno='$docno'";
        $this->db->query($sql);
    }
}
