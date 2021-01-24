<?php

class Po_model extends CI_Model {

    private $table;
    private $query;
	public function __construct(){
        parent::__construct();
        $this->table = "po_hdr";
        $this->query = "select a.po_no,a.po_date as datepo,a.eta ,DATE_FORMAT(a.po_date, '%d/%b/%Y') po_date
                  , a.store_code, b.store_name, a.wilayah , a.currency, a.rate, c.name as provinsi
                  , a.expired_date, d.name as regency, a.supplier_id
                  , a.remark, a.ref_no, IFNULL(a.disc,0)disc ,IFNULL(a.ppn ,0)ppn,s.supplier_name,a.po_type ,s.tipe_supplier AS po_typename
                  , a.tot_item, a.tot_qty_order, a.tot_qty_recv, a.subtotal, a.total_purch
                  , a.status_po, a.po_type,ifnull(a.print,0) jumlah_print,ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from $this->table a 
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id 
	            LEFT JOIN profile_p b on a.store_code=b.store_code
                INNER JOIN supplier s ON s.supplier_code=a.supplier_id
	            LEFT JOIN (provinces c 
	              INNER JOIN regencies d on c.id=d.province_id 
	            ) ON d.id=a.wilayah AND c.id=s.provinsi_id AND d.id=s.regency_id ";
        $this->querysupp="select a.*, c.name as provinsi ,c.name as regency from supplier a  
                    LEFT JOIN (provinces c 
                  INNER JOIN regencies d on c.id=d.province_id 
                ) ON c.id=a.provinsi_id AND d.id=a.regency_id  ";
    }

    function get_list_datasupp($page,$rows,$sort,$order,$role,$fltr, $opt=0, $golongan=""){
        $sql = "create temporary table tmp2 as
                $this->querysupp ";
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
        $q = $this->query." where po_no='$code'";
        return $this->db->query($q);
    }

    function read_datacustomer($code,$so_no){
    $q = "SELECT IFNULL(rs.nama_customer,'') nama_customer,IFNULL(rs.alamat_kirim,'') alamat_kirimcust
                  , IFNULL(rs.kota,'') kotacust,IFNULL(rs.provinsi,'') provcust,IFNULL(rs.no_telepon,'') tlpcust
                  , a.status, a.gol_customer, c.description customer_type_name
                  , a.customer_class, a.customer_code
                  , a.customer_name, d.nama_company head_customer_name
                  , a.parent_cust, h.customer_name AS parent_name
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
                  , a.lokasi_stock, g.description AS lokasi_stock_name
                  , a.head_customer_id
                  , a.salesman_id
                  , a.margin_persen
                  , a.beda_fp
                  , c.diskon 
                  , IFNULL(u1.fullname,a.crtby) AS crtby, IFNULL(u2.fullname, a.updby) AS updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                FROM customer a 
                LEFT JOIN salesman b ON a.salesman_id=b.salesman_id
                LEFT JOIN customer_type c ON a.customer_type=c.code
                LEFT JOIN head_company_customer d ON a.head_customer_id=d.head_customer_id
                LEFT JOIN provinces e ON a.provinsi_id=e.id
                LEFT JOIN regencies f ON a.regency_id=f.id AND a.provinsi_id=f.province_id
                LEFT JOIN location g ON a.lokasi_stock=g.location_code 
                LEFT JOIN customer h ON a.parent_cust=h.customer_code 
                LEFT JOIN users u1 ON a.crtby=u1.user_id
                LEFT JOIN users u2 ON a.updby=u2.user_id 
                LEFT JOIN so_online_header so ON so.customer_code=a.customer_code
                LEFT JOIN resi_marketplace rs ON rs.no_resi=so.so_no ";
        return $this->db->query($q." WHERE a.customer_code='$code' AND so.so_no='$so_no'");
    }
    function read_totaldetail($code){
        $q = "SELECT count(*) total from po_dtl where po_no='$code'";
        return $this->db->query($q);
    }
      function count_data($code){
        $q = "  SELECT COUNT(DISTINCT nobar) item,SUM(qty_order) qty 
                    FROM so_online_detail 
                    WHERE docno='$code'";
        return $this->db->query($q);
    }
    // function count_data($code){
    //     $q = "SELECT ( SELECT COUNT(nobar) AS duplicate_count 
    //             FROM (
    //              SELECT nobar,docno FROM so_online_detail
    //              GROUP BY nobar HAVING COUNT(nobar) >= 1
    //             ) AS t  WHERE  docno='$code') item, SUM(qty_order) qty 
    //                               FROM so_online_detail WHERE docno='$code' ";
    //     return $this->db->query($q);
    // }
    function update_data($code, $data){
        $this->db->where('po_no',$code);
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
        $this->db->where('docno',$code);
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
        $sql = "SELECT IFNULL(CONCAT(DATE_FORMAT(NOW(),'PB%y'),LPAD(MAX(RIGHT(po_no,6))+1,6,'0')),
                CONCAT(DATE_FORMAT(NOW(),'PB%y'),LPAD(1,6,'0'))) AS nomor 
                FROM po_hdr ORDER BY po_no DESC";
        return $this->db->query($sql)->row()->nomor;
    }


    function get_product($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp as
                SELECT b.nobar,b.nmbar, b.product_id, c.product_code, c.article_code
                    , c.jenis_barang, c.satuan_stock, c.satuan_jual
                    , d.description AS uom_stock, d.uom_id as id_stock, e.description AS uom_jual, e.uom_id as id_jual
                FROM product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_stock=d.uom_code
                    INNER JOIN product_uom e ON c.satuan_jual=e.uom_code  ";
        if($fltr!=''){
            $flt = str_replace("nobar","b.nobar",$fltr);
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
                SELECT a.*
                    , b.nmbar, c.satuan_jual, d.description AS uom_jual, d.uom_id
                    , c.product_name, b.product_id 
                    , COALESCE(a.updby, a.crtby) last_user
                    , COALESCE(a.upddt, a.crtdt) last_time
                FROM po_dtl a
                LEFT JOIN (
                    product_barang b 
                    INNER JOIN product c ON b.product_id=c.id
                    INNER JOIN product_uom d ON c.satuan_jual=d.uom_code
                ) ON a.sku=b.nobar";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);
        $sql = "select a.*,
                (select count(a1.po_no) from tmp a1 ) as total
                 from tmp a ";
        $sql .="order by po_no"." $order
                limit ".($page-1)*$rows.",".$rows; 
        return $this->db->query($sql)->result();
    }

    function get_list_data_detailprint($page,$rows,$sort,$order,$role,$fltr){
       
        $sql = "create temporary table tmp as
                SELECT SUM(a.qty_order) qty_order,c.product_code, d.uom_id, COALESCE(a.updby, a.crtby) last_user 
                FROM so_online_detail a
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
                FROM po_dtl
                WHERE po_no='$docno'";
        return $this->db->query($sql)->row()->seqno;
    }
 
    function read_data_detailID($docno,$seqno){
        $this->db->where('po_no',$docno);
        $this->db->where('seqno',$seqno);
        return $this->db->get('po_dtl');
    }
    function update_data_detail($docno,$seqno, $data){
        $this->db->where('po_no',$docno);
        $this->db->where('seqno',$seqno);
        $this->db->update("po_dtl",$data);
        $this->updateheaderdata($docno);
    }
    function update_data_detail_disc($docno, $disc, $nomor, $pkp){
//            'disc_total' => $input['disc_total'],
//                    'bruto_before_tax' => $input['bruto_before_tax'],
//                    'total_tax' => $input['total_tax'],
//                    'net_unit_price' => $input['net_unit_price'],
//                    'net_total_price' => $input['net_total_price'],
	    if($nomor==1){
            $this->db->query("update so_online_detail set disc1_persen=$disc where docno='$docno'");
        }else if($nomor==2){
            $this->db->query("update so_online_detail set disc2_persen=$disc where docno='$docno'");
        } 
//        $sql = "update so_online_detail set
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
        $sql = "update so_online_detail set disc1_amount=unit_price * (disc1_persen/100) where docno='$docno'";
        if($this->db->query($sql)) {
            $sql = "update so_online_detail set disc2_amount=(unit_price-disc1_amount) * (disc2_persen/100) where docno='$docno'";
             if($this->db->query($sql)){
                        $sql = "update so_online_detail set bruto_before_tax=unit_price-disc_total where docno='$docno'";
                        if($this->db->query($sql)){
                            $sql = "update so_online_detail set total_tax=(case when '$pkp'='YES' then (bruto_before_tax*qty_order/1.1)*10/100 else 0 end) where docno='$docno'";
                            if($this->db->query($sql)){
                                $sql = "update so_online_detail set net_unit_price=unit_price-disc_total where docno='$docno'";
                                if($this->db->query($sql)){
                                    $sql = "update so_online_detail set net_total_price=(unit_price-disc_total)*qty_order where docno='$docno'";
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
        $this->db->insert("po_dtl", $data);
        $this->updateheaderdata($docno);
    }
    function delete_data_detail($po_no, $seqno){
        $this->db->where('po_no',$po_no);
        $this->db->where('seqno',$seqno);
        $this->db->delete("po_dtl");
        $this->updateheaderdata($po_no);
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
        $sql = "UPDATE po_hdr AS dest
                    , (SELECT (  SELECT COUNT(sku) FROM
                        ( SELECT DISTINCT sku FROM po_dtl WHERE po_no = '$docno'
                        ) AS item) item, SUM(qty_order) qty
                        , SUM(CEILING(po_dtl.net_unit_price)) subtotal
                        , SUM(CEILING(po_dtl.disc*po_dtl.qty_order)) disc 
                        , SUM(CEILING(po_dtl.net_purchase)) total_purch
                        , SUM(CEILING(po_dtl.ppn)) ppn
                      FROM po_dtl WHERE po_no='$docno') AS src
                    SET dest.tot_item = src.item
                        , dest.tot_qty_order=src.qty
                        , dest.subtotal = src.subtotal
                        , dest.disc = src.disc
                        , dest.ppn = src.ppn
                        , dest.total_purch = src.total_purch 
                    WHERE dest.po_no='$docno'";
        $this->db->query($sql);
    }

    function copy_detail($from, $to, $user, $tgl, $pkp){
        $sql = "insert into so_online_detail (docno, seqno, nobar, tipe, crtby, crtdt
                , unit_price, disc1_persen, disc1_amount, disc2_persen, disc2_amount 
                , disc_total, bruto_before_tax, total_tax, net_unit_price, net_total_price)
                select '$to', a.seqno, a.nobar, a.tipe, '$user', '$tgl'
                 , a.unit_price, b.disc1_persen, 0, b.disc2_persen, 0,  0
                 , 0, 0, 0, 0, 0 FROM so_online_detail a
                 LEFT JOIN so_online_header b ON b.docno='$to' 
                 WHERE a.docno='$from'";
        if($this->db->query($sql)){
            $sql = "update so_online_detail set disc1_amount=unit_price * (disc1_persen/100) where docno='$docno'";
            if($this->db->query($sql)) {
                $sql = "update so_online_detail set disc2_amount=(unit_price-disc1_amount) * (disc2_persen/100) where docno='$docno'";
                if($this->db->query($sql)){
                            $sql = "update so_online_detail set bruto_before_tax=unit_price-disc_total where docno='$docno'";
                            if($this->db->query($sql)){
                                $sql = "update so_online_detail set total_tax=(case when '$pkp'='YES' then (bruto_before_tax*qty_order/1.1)*10/100 else 0 end) where docno='$docno'";
                                if($this->db->query($sql)){
                                    $sql = "update so_online_detail set net_unit_price=unit_price-disc_total where docno='$docno'";
                                    if($this->db->query($sql)){
                                        $sql = "update so_online_detail set net_total_price=(unit_price-disc_total)*qty_order where docno='$docno'";
                                        if($this->db->query($sql)){
                                            $sql = "SET @rank:=0;";
                                            if($this->db->query($sql)){
                                                $sql = "update so_online_detail
                                                        set seqno=@rank:=@rank+1
                                                        where docno='$to';";
                                                if($this->db->query($sql)){
                                                    $sql = "update so_online_detail
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
