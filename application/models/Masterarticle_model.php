<?php

class Masterarticle_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.article_code, a.article_name, a.style, a.tipe, a.opsi
                  , a.bom_pcs, a.foh_pcs, a.ongkos_jahit_pcs
                  , a.operation_cost, a.interest_cost
                  , a.buffer_cost, a.ekspedisi, a.hpp1, a.hpp2, a.hpp_ekspedisi, a.keterangan
                  , ifnull(concat(b.path,b.filename),'') as gambar
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from article a 
	            left JOIN attachment b on a.article_code=b.docno and b.tabel='article'
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id 
	            group by a.article_code";
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
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "article_code", "asc","", $app, 1);
    }
    function load_grid_size($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "select a.id, a.art_size_code, a.article_code
                  , b.description as size_name
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            , (select count(a1.article_code) from article_size a1 ) as total
	            from article_size a 
	            left join product_size b on a.art_size_code = b.size_code
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order ";

        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function load_grid_size2($app){
        return $this->load_grid_size(1, 999999999999, "article_code", "asc","", $app, 1);
    }
    function load_grid_colour($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "select a.id, a.art_colour_code, a.article_code
                  , b.description as colour_name
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , (select count(a1.article_code) from article_size a1 ) as total
	            from article_colour a 
	            left join product_colour b on a.art_colour_code = b.colour_code 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order ";

        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function load_grid_colour2($app){
        return $this->load_grid_colour(1, 999999999999, "article_code", "asc","", $app, 1);
    }
    function load_grid_size_colour($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table temp as 
                select b.sku, a.nobar, a.nmbar, b.article_code, b.product_code, b.satuan_jual, c.uom_id, a.soh,
                (select count(a1.product_id) from product_barang a1 ) as total
	            from product_barang a 
	            inner join (
	              product b 
	              inner JOIN product_uom c on b.satuan_jual=c.uom_code
	            ) on a.product_id=b.id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order ";
        $this->db->query($sql);

        $sql = "select a.* from temp a";

        $data = $this->db->query($sql)->result();
        $sql = "drop table temp";
        $this->db->query($sql);
        return $data;
    }
    function get_product_size(){
	    $sql = "select size_code, replace(description,'\"','`') as description from product_size where status='Approved'";
	    return $this->db->query($sql);
    }
    function get_product_colour(){
        $this->db->where('status','Approved');
        return $this->db->get('product_colour');
    }
    function get_product_size_colour($art, $size, $colour){
        $this->db->where('article_code',$art);
        $this->db->where('size_code',$size);
        $this->db->where('colour_code',$colour);
        return $this->db->get('product');
    }

    function read_data($code){
        $this->db->where('article_code',$code);
        return $this->db->get('article');
    }
    function update_data($code, $data){
        $this->db->where('article_code',$code);
        $this->db->update('article',$data);
    }
    function insert_data($data){
        $this->db->insert('article', $data);
    }
    function delete_data($id){
        $this->db->where('article_code',$id);
        $this->db->delete('article');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('article_code',$code);
        return $this->db->get('product');
    }





    function read_data_size($code, $size){
        $this->db->where('article_code',$code);
        $this->db->where('art_size_code',$size);
        return $this->db->get('article_size');
    }
    function read_data_size_id($code){
        $this->db->where('id',$code);
        return $this->db->get('article_size');
    }
    function update_data_size($code, $data){
        $this->db->where('id',$code);
        $this->db->update('article_size',$data);
    }
    function insert_data_size($data){
        $this->db->insert('article_size', $data);
    }
//    function delete_data_size($id){
//        $this->db->where('article_code',$id);
//        $this->db->delete('article');
//    }
//    function read_transactions($code){
//        $this->db->where('article_code',$code);
//        return $this->db->get('article');
//    }


    function get_colour($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.colour_code, a.description, b.article_code 
                  from product_colour a 
                  left join article_colour b on a.colour_code=b.art_colour_code
                  where a.status='Approved'
                   group by a.colour_code ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
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

    function get_size($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                  select a.size_code, a.description, b.article_code 
                  from product_size a 
                  left join article_size b on a.size_code=b.art_size_code 
                  where a.status='Approved'
                   group by a.size_code ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.size_code) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }
    function read_data_colour($code, $size){
        $this->db->where('article_code',$code);
        $this->db->where('art_colour_code',$size);
        return $this->db->get('article_colour');
    }
    function read_data_colour_id($code){
        $this->db->where('id',$code);
        return $this->db->get('article_colour');
    }
    function update_data_colour($code, $data){
        $this->db->where('id',$code);
        $this->db->update('article_colour',$data);
    }
    function insert_data_colour($data){
        $this->db->insert('article_colour', $data);
    }
    function delete_data_size($id){
        $this->db->where('id',$id);
        $this->db->delete('article_size');
    }
//    function read_transactions_colour($code){
//       $sql = "select * from product_barang where warna like '%%'"
//        return $this->db->get('article');
//    }


    function read_data_size_colour($art, $size,$colour, $sku){
        $this->db->where('article_code',$art);
        $this->db->where('art_size_id',$size);
        $this->db->where('art_colour_id',$colour);
        $this->db->where('sku',$sku);
        return $this->db->get('article_size_colour');
    }
    function read_data_size_colour_id($code){
        $this->db->where('id',$code);
        return $this->db->get('article_size_colour');
    }
    function update_data_size_colour($code, $data){
        $this->db->where('id',$code);
        $this->db->update('article_size_colour',$data);
    }
    function insert_data_size_colour($data){
        $this->db->insert('article_size_colour', $data);
    }
    function delete_data_colour($id){
        $this->db->where('id',$id);
        $this->db->delete('article_colour');
    }
//    function read_transactions($code){
//        $this->db->where('article_code',$code);
//        return $this->db->get('article');
//    }
}
