<?php

class Showroomsales_model extends CI_Model {

    private $table;
    private $query;
	public function __construct(){
        parent::__construct();
        $this->table = "so_showroom_header";
        $this->query = "select a.docno,a.so_no
                  , CONCAT(LEFT(a.docno,3),'.',RIGHT(LEFT(a.docno,7),4),'.',RIGHT(LEFT(a.docno,9),2),'.',RIGHT(a.docno,4)) AS ak_docno
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%b/%Y') ak_doc_date
                  , b.store_name, a.location_code , a.provinsi_id, c.name as provinsi
                  , a.regency_id, d.name as regency, a.jenis_so
                  , a.remark, a.customer_code, e.customer_name, e.phone1, a.salesman_id, f.salesman_name
                  , e.lokasi_stock, e.customer_type
                  , a.tipe_komisi, a.komisi_persen, a.disc1_persen, a.disc2_persen 
                  , a.qty_item, a.qty_order, a.gross_sales, a.total_ppn, a.total_discount
                  , a.sales_before_tax, a.sales_after_tax
                  , a.status, a.sales_pada_toko, e.pkp
                  , ifnull(a.jumlah_print,0) jumlah_print, e.credit_limit, e.outstanding, (e.credit_limit-e.outstanding) credit_remain
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from $this->table a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id 
	            LEFT JOIN profile_p b on a.store_code=b.store_code
	            LEFT JOIN (provinces c 
	              INNER JOIN regencies d on c.id=d.province_id 
	            ) ON c.id=a.provinsi_id AND d.id=a.regency_id
	            LEFT JOIN customer e on a.customer_code=e.customer_code 
	            LEFT JOIN salesman f on a.salesman_id=f.salesman_id";
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
        $sql .="order by docno"." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data($code){
        $q = $this->query." where docno='$code'";
        return $this->db->query($q);
    }
    function read_totaldetail($code){
        $q = "SELECT count(*) total from so_showroom_detail where docno='$code'";
        return $this->db->query($q);
    }
      function count_data($code){
        $q = "  SELECT COUNT(DISTINCT nobar) item,SUM(qty_order) qty 
                    FROM so_showroom_detail 
                    WHERE docno='$code'";
        return $this->db->query($q);
    }
    // function count_data($code){
    //     $q = "SELECT ( SELECT COUNT(nobar) AS duplicate_count 
    //             FROM (
    //              SELECT nobar,docno FROM so_showroom_detail
    //              GROUP BY nobar HAVING COUNT(nobar) >= 1
    //             ) AS t  WHERE  docno='$code') item, SUM(qty_order) qty 
    //                               FROM so_showroom_detail WHERE docno='$code' ";
    //     return $this->db->query($q);
    // }
    function update_data($code, $data){
        $this->db->where('docno',$code);
        $this->db->update($this->table,$data);
        $this->updateheaderdata($code);
    }
    function insert_data($data){
      
        $this->db->insert($this->table, $data);
    }
    function delete_data($id){
        $this->db->where('docno',$id);
        $this->db->delete($this->table);
    }
    function read_transactions($code){
        $this->db->where('brand_code',$code);
        return $this->db->get('product');
    }
    function generate_auto_number(){
//         $prefix = "";
//         if($pkp=="YES"){
// //            if($store_code == $this->session->userdata('kode store pusat')){
// //                $prefix="SOP";
// //            }else if($store_code == $this->session->userdata('kode store bandung')){
// //                $prefix="BOP";
// //            }else if($store_code == $this->session->userdata('kode store surabaya')){
// //                $prefix="YOP";
// //            }
//                 $prefix="SOP";
//         }else{
//             if($store_code == $this->session->userdata('kode store pusat')){
//                 $prefix="SOS";
//             }else if($store_code == $this->session->userdata('kode store bandung')){
//                 $prefix="BOS";
//             }else if($store_code == $this->session->userdata('kode store surabaya')){
//                 $prefix="YOS";
//             }
//         }
//         if($prefix=="") return "";
        $sql = "SELECT IFNULL(CONCAT(DATE_FORMAT(NOW(),'%y%m%d'),LPAD(MAX(RIGHT(docno,6))+1,6,'0')),
                CONCAT(DATE_FORMAT(NOW(),'%y%m%d'),LPAD(1,6,'0'))) AS nomor 
                FROM so_showroom_header WHERE LEFT(docno,6)= CONCAT(DATE_FORMAT(NOW(),'%y%m%d')) ORDER BY docno DESC";
        return $this->db->query($sql)->row()->nomor;
    }


    function get_product($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                SELECT a.nobar, FLOOR(a.saldo_akhir/(ifnull((select convertion from product_uom_convertion where uom_from=c.satuan_jual and uom_to=c.satuan_stock limit 1),0))) stock
                    , b.nmbar, b.product_id, c.product_code, c.article_code
                    , c.jenis_barang, c.satuan_stock, c.satuan_jual
                    , d.description AS uom_stock, d.uom_id as id_stock, e.description AS uom_jual, e.uom_id as id_jual
                FROM stock a
                INNER JOIN (
                    product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_stock=d.uom_code
                    INNER JOIN product_uom e ON c.satuan_jual=e.uom_code
                ) ON a.nobar=b.nobar ";
        if($fltr!=''){
            $flt = str_replace("nobar","a.nobar",$fltr);
            $sql .= $flt;
        }
        $this->db->query($sql);

        $sql = "select a.*,
                (select count(a1.nobar) from tmp a1 ) as total
                 from tmp a ";
        $sql .="order by " .$sort." $order
                limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function get_byproduct($page,$rows,$sort,$order,$role,$fltr){
         $sql = "create temporary table tmp as
                SELECT a.nobar, FLOOR(a.saldo_akhir/(ifnull((select convertion from product_uom_convertion where uom_from=c.satuan_jual and uom_to=c.satuan_stock limit 1),0))) stock
                    , b.nmbar, b.product_id, c.product_code, c.article_code
                    , c.jenis_barang, c.satuan_stock, c.satuan_jual
                    , d.description AS uom_stock, d.uom_id as id_stock, e.description AS uom_jual, e.uom_id as id_jual
                FROM stock a
                INNER JOIN (
                    product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_stock=d.uom_code
                    INNER JOIN product_uom e ON c.satuan_jual=e.uom_code
                ) ON a.nobar=b.nobar ";
        if($fltr!=''){
            $flt = str_replace("nobar","a.nobar",$fltr);
            $sql .= $flt;
        }
        $this->db->query($sql);

        $sql = "select a.*,
                (select count(a1.nobar) from tmp a1 ) as total
                 from tmp a ";
        $sql .="order by " .$sort." $order
                limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
    function get_discount($product_id,$tgl,$lokasi,$customer_code){
        $sql = "SELECT * FROM customer where customer_code='$customer_code'";
        $dtx = $this->db->query($sql);
        if($dtx->num_rows()>0){
            $sql = "SELECT a.* FROM discount a
                    INNER JOIN discount_for b ON a.discount_id=b.discount_id
                    WHERE a.start_date<='$tgl' AND a.end_date>='$tgl'
                    AND b.customer_code='$customer_code'
                    ORDER BY a.start_date DESC";
            $dt = $this->db->query($sql);
            if($dt->num_rows()>0){
                $disc_id = $dt->row()->discount_id;
                $sql = "SELECT a.article_code, b.id product_id
                    FROM discount_item a
                    LEFT JOIN product b ON a.article_code=b.article_code
                    WHERE a.discount_id='$disc_id'";
                $dt2 = $this->db->query($sql);
                if($dt2->num_rows()>0){
                    $lup = $dt2->result();
                    $prd_id="";
                    foreach ($lup as $row){
                        if($row->product_id==$product_id){
                            $prd_id=$row->product_id;
                            break;
                        }
                    }
                    if($prd_id==""){
                        //cek customer article
                        $sql = "SELECT b.id,a.*
                            FROM category_article a
                            INNER JOIN product b ON a.article_code=b.article_code
                            INNER JOIN customer c ON a.customer_code=a.customer_code AND a.customer_type=c.customer_type
                            WHERE a.customer_code='$customer_code'
                            AND b.id='$product_id'
                            GROUP BY a.id;";
                        $cs1 = $this->db->query($sql);
                        if($cs1->num_rows()>0){
                            return $cs1->row()->discount;
                        }else return 1000;
                    }else{
                        $sql="select * from discount_for where discount_id='$disc_id'";
                        $dt3 = $this->db->query($sql);
                        if($dt3->num_rows()>0){
                            $lup2 = $dt3->result();
                            $lksi ="";
                            foreach ($lup2 as $row2){
                                if($row2->location_code==$lokasi){
                                    $lksi=$row2->location_code;
                                    break;
                                }
                            }
                            if($lksi==""){
                                //cek customer article
                                $sql = "SELECT b.id,a.*
                            FROM category_article a
                            INNER JOIN product b ON a.article_code=b.article_code
                            INNER JOIN customer c ON a.customer_code=a.customer_code AND a.customer_type=c.customer_type
                            WHERE a.customer_code='$customer_code'
                            AND b.id='$product_id'
                            GROUP BY a.id;";
                                $cs1 = $this->db->query($sql);
                                if($cs1->num_rows()>0){
                                    return $cs1->row()->discount;
                                }else return 1000;
                            }else{
                                return $dt->row()->discount1;
                            }
                        }else{
                            //cek customer article
                            $sql = "SELECT b.id,a.*
                            FROM category_article a
                            INNER JOIN product b ON a.article_code=b.article_code
                            INNER JOIN customer c ON a.customer_code=a.customer_code AND a.customer_type=c.customer_type
                            WHERE a.customer_code='$customer_code'
                            AND b.id='$product_id'
                            GROUP BY a.id;";
                            $cs1 = $this->db->query($sql);
                            if($cs1->num_rows()>0){
                                return $cs1->row()->discount;
                            }else return 1000;
                        }
                    }
                }else{
                    //cek customer article
                    $sql = "SELECT b.id,a.*
                            FROM category_article a
                            INNER JOIN product b ON a.article_code=b.article_code
                            INNER JOIN customer c ON a.customer_code=a.customer_code AND a.customer_type=c.customer_type
                            WHERE a.customer_code='$customer_code'
                            AND b.id='$product_id'
                            GROUP BY a.id;";
                    $cs1 = $this->db->query($sql);
                    if($cs1->num_rows()>0){
                        return $cs1->row()->discount;
                    }else return 1000;
                }
            }else{
                //cek customer article
                $sql = "SELECT b.id,a.*
                            FROM category_article a
                            INNER JOIN product b ON a.article_code=b.article_code
                            INNER JOIN customer c ON a.customer_code=a.customer_code AND a.customer_type=c.customer_type
                            WHERE a.customer_code='$customer_code'
                            AND b.id='$product_id'
                            GROUP BY a.id;";
                $cs1 = $this->db->query($sql);
                if($cs1->num_rows()>0){
                    return $cs1->row()->discount;
                }else return 1000;
            }
        }else{
            //cek customer article
            $sql = "SELECT b.id,a.*
                            FROM category_article a
                            INNER JOIN product b ON a.article_code=b.article_code
                            INNER JOIN customer c ON a.customer_code=a.customer_code AND a.customer_type=c.customer_type
                            WHERE a.customer_code='$customer_code'
                            AND b.id='$product_id'
                            GROUP BY a.id;";
            $cs1 = $this->db->query($sql);
            if($cs1->num_rows()>0){
                return $cs1->row()->discount;
            }else return 1000;
        }

    }

    function get_unit_price($product_id,$customer_code,$tgl){
     
        $sql = "SELECT * FROM customer WHERE customer_code='$customer_code'";
        $dt = $this->db->query($sql);
        if($dt->num_rows()>0){
            $cs = $dt->row();
            $sql = "SELECT * FROM product_price 
                    WHERE eff_date<='$tgl'
                    AND customer_type='$cs->customer_type'
                    AND product_id='$product_id'
                    ORDER BY eff_date DESC
                    LIMIT 1";
            $dt2 = $this->db->query($sql);
            if($dt2->num_rows()>0){
                $cs2=$dt2->row();
                if($cs->pkp=="YES") return $cs2->price_pkp;
                else return $cs2->price_non_pkp;
            }else return 0;
        }else return 0;
    }

    function get_list_data_detail($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                SELECT a.id, a.docno, a.seqno, tipe, a.nobar, a.qty_order, a.unit_price
                    , a.disc1_persen, a.disc2_persen,  a.disc1_amount, a.disc2_amount 
                    , a.disc_total, a.bruto_before_tax, a.total_tax, a.net_unit_price, a.net_total_price,a.qty_sales,(a.net_unit_price-a.total_tax)bfr_ppn
                    , a.status_detail
                    , b.nmbar, c.satuan_jual, d.description AS uom_jual, c.product_code, d.uom_id
                    , c.product_name, b.product_id
                    , (select ifnull(sum(pl.qty_pl),0) from packing_detail pl inner join packing_header ph on ph.docno=pl.docno where pl.so_number=a.docno and pl.seqno=a.seqno and ph.status IN('POSTING','CLOSED')) AS qty_pl
                    , COALESCE(a.updby, a.crtby) last_user
                    , COALESCE(a.upddt, a.crtdt) last_time
                FROM so_showroom_detail a
                LEFT JOIN (
                    product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_jual=d.uom_code
                ) ON a.nobar=b.nobar";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*, b.fullname, date_format(a.last_time,'%T') last_jam, 
                (select count(a1.nobar) from tmp a1 ) as total
                 from tmp a 
                 left join users b on a.last_user=b.user_id 
                GROUP BY a.id, a.docno, a.seqno, a.tipe, a.nobar, a.qty_order, a.unit_price , a.disc1_persen, 
                a.disc2_persen, a.disc1_amount, a.disc2_amount , a.disc_total, a.bruto_before_tax, 
                a.total_tax, a.net_unit_price, a.net_total_price , a.status_detail , a.nmbar, a.satuan_jual, uom_jual, 
                a.product_code, a.uom_id ,a.product_name  ";
        $sql .="order by " .$sort." $order
                limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function get_list_data_detailprint($page,$rows,$sort,$order,$role,$fltr){
       
        $sql = "create temporary table tmp as
                SELECT SUM(a.qty_order) qty_order,c.product_code, d.uom_id, COALESCE(a.updby, a.crtby) last_user 
                FROM so_showroom_detail a
                LEFT JOIN (
                    product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_jual=d.uom_code
                ) ON a.nobar=b.nobar";
        if($fltr!=''){
            $sql .= $fltr ."  AND c.status_product='Active' group by c.product_code,d.uom_id  ";
        }
        $this->db->query($sql); 

        $sql = "select a.*, b.fullname
	             from tmp a 
	             left join users b on a.last_user=b.user_id ";
        $sql .="order by a.product_code $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }


    function generate_seqno($docno){
        $sql = "SELECT IFNULL(LPAD(MAX(seqno)+1,3,'0'), LPAD(1,3,'0')) AS seqno
                FROM so_showroom_detail
                WHERE docno='$docno'";
        return $this->db->query($sql)->row()->seqno;
    }

    function cek_detail($docno, $nobar, $tipe){
        $this->db->where('docno',$docno);
        $this->db->where('nobar',$nobar);
        $this->db->where('tipe',$tipe);
        return $this->db->get('so_showroom_detail');
    }
    function read_data_detailID($id){
        $this->db->where('id',$id);
        return $this->db->get('so_showroom_detail');
    }
    function update_data_detail($docno,$code, $data){
        $this->db->where('id',$code);
        $this->db->update("so_showroom_detail",$data);
        $this->updateheaderdata($docno);
    }
    function update_data_detail_disc($docno, $disc, $nomor, $pkp){
//            'disc_total' => $input['disc_total'],
//                    'bruto_before_tax' => $input['bruto_before_tax'],
//                    'total_tax' => $input['total_tax'],
//                    'net_unit_price' => $input['net_unit_price'],
//                    'net_total_price' => $input['net_total_price'],
	    if($nomor==1){
            $this->db->query("update so_showroom_detail set disc1_persen=$disc where docno='$docno'");
        }else if($nomor==2){
            $this->db->query("update so_showroom_detail set disc2_persen=$disc where docno='$docno'");
        } 
//        $sql = "update so_showroom_detail set
//	               disc1_amount=unit_price-(unit_price-(unit_price*(disc1_persen/100)))
//	              , disc2_amount=disc1_amount+(disc1_amount*(disc2_persen/100))
//	              , disc3_amount=disc2_amount+(disc2_amount*(disc3_persen/100))
//	              , disc_total=disc1_amount+disc2_amount+disc3_amount
//	              , bruto_before_tax=unit_price-disc_total
//	              , total_tax=(case when '$pkp'='YES' then bruto_before_tax*(10/100) else 0 end)
//	              , net_unit_price=bruto_before_tax+total_tax
//	              , net_total_price=net_unit_price*qty_order  (gross*n[0]/1.1) * 10/100
//	              where docno='$docno'";
//        $this->db->query($sql);
        $sql = "update so_showroom_detail set disc1_amount=unit_price * (disc1_persen/100) where docno='$docno'";
        if($this->db->query($sql)) {
            $sql = "update so_showroom_detail set disc2_amount=(unit_price-disc1_amount) * (disc2_persen/100) where docno='$docno'";
             if($this->db->query($sql)){
                        $sql = "update so_showroom_detail set bruto_before_tax=unit_price-disc_total where docno='$docno'";
                        if($this->db->query($sql)){
                            $sql = "update so_showroom_detail set total_tax=(case when '$pkp'='YES' then (bruto_before_tax*qty_order/1.1)*10/100 else 0 end) where docno='$docno'";
                            if($this->db->query($sql)){
                                $sql = "update so_showroom_detail set net_unit_price=unit_price-disc_total where docno='$docno'";
                                if($this->db->query($sql)){
                                    $sql = "update so_showroom_detail set net_total_price=(unit_price-disc_total)*qty_order where docno='$docno'";
                                    if($this->db->query($sql)){
                                        $this->updateheaderdata($docno);
                                    }
                                }
                            }
                        } 
            }
        }
    }
    function insert_data_detail($docno,$data){ 
        $this->db->insert("so_showroom_detail", $data);
        $this->updateheaderdata($docno);
    }
    function delete_data_detail($docno, $id){
        $this->db->where('id',$id);
        $this->db->delete("so_showroom_detail");
        $this->updateheaderdata($docno);
    }
    function read_transactions_detail($so, $seqno){
//        $this->db->where('brand_code',$code);
//        return $this->db->get('product');
        $sql = "SELECT *
                FROM packing_detail
                WHERE so_number='$so' and seqno='$seqno'";
        return $this->db->query($sql);
    }
    function updateheaderdata($docno){
        $sql = "UPDATE so_showroom_header AS dest
                , (SELECT (  SELECT COUNT(nobar) FROM
                    ( SELECT DISTINCT nobar FROM so_showroom_detail WHERE docno = '$docno'
                    ) AS item) item, SUM(qty_order) qty
                        , SUM(CEILING(so_showroom_detail.unit_price*so_showroom_detail.qty_order)) bruto
                        , SUM(CEILING(so_showroom_detail.disc_total*so_showroom_detail.qty_order)) disc
                        , SUM(CEILING(so_showroom_detail.bruto_before_tax)) before_tax
                        , SUM(CEILING(so_showroom_detail.net_total_price*so_showroom_detail.qty_order)) after_tax
                        , SUM(CEILING(so_showroom_detail.total_tax)) ppn
                  FROM so_showroom_detail WHERE docno='$docno') AS src
                SET dest.qty_item = src.item
                    , dest.qty_order=src.qty
                    , dest.gross_sales = src.bruto
                    , dest.total_discount = src.disc
                    , dest.sales_before_tax = src.before_tax
                    , dest.sales_after_tax = src.after_tax
                    , dest.total_ppn = src.ppn
                WHERE dest.docno='$docno'";
        $this->db->query($sql);
    }

    function copy_detail($from, $to, $user, $tgl, $pkp){
        $sql = "insert into so_showroom_detail (docno, seqno, nobar, tipe, crtby, crtdt
                , unit_price, disc1_persen, disc1_amount, disc2_persen, disc2_amount 
                , disc_total, bruto_before_tax, total_tax, net_unit_price, net_total_price)
                select '$to', a.seqno, a.nobar, a.tipe, '$user', '$tgl'
                 , a.unit_price, b.disc1_persen, 0, b.disc2_persen, 0,  0
                 , 0, 0, 0, 0, 0 FROM so_showroom_detail a
                 LEFT JOIN so_showroom_header b ON b.docno='$to' 
                 WHERE a.docno='$from'";
        if($this->db->query($sql)){
            $sql = "update so_showroom_detail set disc1_amount=unit_price * (disc1_persen/100) where docno='$docno'";
            if($this->db->query($sql)) {
                $sql = "update so_showroom_detail set disc2_amount=(unit_price-disc1_amount) * (disc2_persen/100) where docno='$docno'";
                if($this->db->query($sql)){
                            $sql = "update so_showroom_detail set bruto_before_tax=unit_price-disc_total where docno='$docno'";
                            if($this->db->query($sql)){
                                $sql = "update so_showroom_detail set total_tax=(case when '$pkp'='YES' then (bruto_before_tax*qty_order/1.1)*10/100 else 0 end) where docno='$docno'";
                                if($this->db->query($sql)){
                                    $sql = "update so_showroom_detail set net_unit_price=unit_price-disc_total where docno='$docno'";
                                    if($this->db->query($sql)){
                                        $sql = "update so_showroom_detail set net_total_price=(unit_price-disc_total)*qty_order where docno='$docno'";
                                        if($this->db->query($sql)){
                                            $sql = "SET @rank:=0;";
                                            if($this->db->query($sql)){
                                                $sql = "update so_showroom_detail
                                                        set seqno=@rank:=@rank+1
                                                        where docno='$to';";
                                                if($this->db->query($sql)){
                                                    $sql = "update so_showroom_detail
                                                            set seqno=RIGHT(CONCAT('0000',seqno),3)
                                                            where docno='$to';";
                                                    if($this->db->query($sql)){
                                                        $this->updateheaderdata($docno);
                                                        return true;
                                                    }else return false;
                                                }else return false;
                                            }else return false;
                                        }else return false;
                                    }else return false;
                                }else return false;
                            }else return false;
                        }else return false; 
            }else return false;
        }else return false;
    }
}
