<?php

class Pickup_model extends CI_Model {

    private $table;
    private $query;
	public function __construct(){
        parent::__construct();
        $this->table = "pickup_h";
        $this->tabledetail = "pickup_d";
        $this->table2 = "ekspedisi";
        $this->query = "SELECT a.*, DATE_FORMAT(a.tgl, '%d/%b/%Y') tglformat, DATE_FORMAT(a.tgl_pickup, '%d/%b/%Y') tgl_pickupformat,
         DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddtformat,IFNULL(u1.fullname, a.updby) AS updby2 from $this->table  a
                LEFT JOIN users u1 ON a.updby=u1.user_id";
        $this->query2 = "SELECT * from $this->table2";
        $this->query3 = "SELECT d.*,s.status onlinestatus FROM pickup_d d
                        INNER JOIN so_online_header s ON s.docno=d.barcode";
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
	            (select count(a1.id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data_by_so($code){
        $q = $this->query3." where barcode='$code' AND s.status IN('OPEN','ON ORDER')";
        return $this->db->query($q);
    }
    function get_list_dataexpedisi($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                $this->query2";
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

    function cekSO($code){
        $q ="SELECT * from so_online_header where docno='$code' and status='ON ORDER'";
        return $this->db->query($q);
    }
    function read_datapickdetail($code){
        $q = $this->query3." where barcode='$code'";
        return $this->db->query($q);
    }
    function read_data($code){
        $q = $this->query." where id='$code'";
        return $this->db->query($q);
    }
    function read_datadetail($code){
        $q = "SELECT count(*) jumlah from pickup_d where pickup_h_id='$code'";
        return $this->db->query($q);
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update($this->table,$data); 
    }
    function update_datadetail($code, $data){
        $this->db->where('pickup_h_id',$code);
        $this->db->update($this->tabledetail,$data); 
    }
    function insert_data($data){
      
        $this->db->insert($this->table, $data);
           $insert_id = $this->db->insert_id();

           return  $insert_id;
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
                FROM so_online_header WHERE LEFT(docno,6)= CONCAT(DATE_FORMAT(NOW(),'%y%m%d')) ORDER BY docno DESC";
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
    function get_bypickup($page,$rows,$sort,$order,$role,$fltr){
         $sql = "create temporary table tmp as
                SELECT a.*
                FROM pickup_d a
                INNER JOIN  pickup_h p ON a.pickup_h_id=p.id ";
        if($fltr!=''){
            $flt = str_replace("id","a.id",$fltr);
            $sql .= $flt;
        }
        $this->db->query($sql);

        $sql = "select a.*,
                (select count(a1.barcode) from tmp a1 ) as total
                 from tmp a ";
        $sql .="order by barcode $order
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
                        }else return 0;
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
                                }else return 0;
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
                            }else return 0;
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
                    }else return 0;
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
                }else return 0;
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
            }else return 0;
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
           SELECT a.*,p.crtby,p.crtdt,p.Phone,p.line,p.user,p.ekspedisiby,ekspedisiname, 
                DATE_FORMAT(a.upddt,'%H:%i:%s') time
                FROM pickup_h p
                LEFT JOIN  pickup_d a ON p.id=a.pickup_h_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*, b.fullname, date_format(a.upddt,'%T') last_jam, 
	            (select count(a1.tgl) from tmp a1 ) as total
	             from tmp a 
	             left join users b on a.crtby=b.user_id ";
        $sql .="order by a.tgl  $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }


    function generate_seqno($docno){
        $sql = "SELECT IFNULL(LPAD(MAX(seqno)+1,3,'0'), LPAD(1,3,'0')) AS seqno
                FROM so_online_detail
                WHERE docno='$docno'";
        return $this->db->query($sql)->row()->seqno;
    }

    function cekstatusSOonline($docno){
        $sql = "SELECT s.docno,s.status FROM pickup_h h
                INNER JOIN pickup_d d ON d.pickup_h_id=h.id
                INNER JOIN so_online_header s ON s.docno=d.barcode
                WHERE h.id='$docno' AND s.status='OPEN'";
        return $this->db->query($sql);
    }

    function cek_detail($docno, $nobar, $tipe){
        $this->db->where('docno',$docno);
        $this->db->where('nobar',$nobar);
        $this->db->where('tipe',$tipe);
        return $this->db->get('so_online_detail');
    }
    function read_data_detailID($id){
        $this->db->where('barcode',$id);
        return $this->db->get('pickup_d');
    }
    function update_data_detail($docno,$code, $data){
        $this->db->where('id',$code);
        $this->db->update("so_online_detail",$data);
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
    function save_detail($data){
      
        $this->db->insert($this->tabledetail, $data);
    }
    function insert_data_detail($docno,$data){ 
        $this->db->insert("so_online_detail", $data);
        $this->updateheaderdata($docno);
    }
    function delete_data_detail($docno){
        $this->db->where('barcode',$docno);
        $this->db->delete("pickup_d"); 
    }
    function updatestatuspick($docno){ 
        $sql = " UPDATE pickup_h set status='Pickup'
                WHERE id='$docno'";
        return $this->db->query($sql);
    }
    function updatestatuspickdetail($docno){ 

       $this->db->query("INSERT INTO sales_online_header(docno,doc_date,store_code,so_number,jenis_faktur,remark,customer,sales,tipe_komisi,komisi,
                        disc1_persen,disc2_persen,qty_item,qty,gross_sales,total_ppn,total_discount,sales_before_tax,sales_after_tax,posting_date,
                        STATUS,sales_toko,so_no,jumlah_print,crtby,crtdt,updby,upddt) 
                        SELECT h.docno,h.doc_date,h.store_code,h.so_no,h.jenis_faktur,h.remark,h.customer_code,h.salesman_id,
                        h.tipe_komisi,h.komisi_persen,h.disc1_persen,h.disc2_persen,h.qty_item,h.qty_order,h.gross_sales,h.total_ppn,h.total_discount,
                        h.sales_before_tax,h.sales_after_tax,
                        h.posting_date,'OPEN',h.sales_pada_toko,h.so_no,h.jumlah_print,h.crtby,h.crtdt,h.updby,h.upddt FROM pickup_d d
                        INNER JOIN so_online_header h ON h.docno= d.barcode 
                        WHERE d.pickup_h_id='$docno'"); 
        $this->db->query("INSERT INTO sales_online_detail(docno,sales_date,so_number,seqno,product_tipe,nobar,product_code,TYPE,komisi,qty_order,qty_sales,UOM,
                                stock_location,unitprice,disc1_persen,disc1_amount,disc2_persen,disc2_amount,disc_total,net_unit_price,bruto_before_tax,total_tax,
                                net_after_tax,status_detail,crtby,crtdt,updby,upddt) 
                                SELECT d.docno,h.doc_date,h.so_no,ROW_NUMBER()OVER(ORDER BY d.nobar) seqno,d.product_tipe,d.nobar,d.product_code,d.tipe,d.komisi,
                                SUM(d.qty_order),SUM(d.qty_sales),d.UOM,d.stock_location,d.unit_price,d.disc1_persen,d.disc1_amount,d.disc2_persen,d.disc2_amount,d.disc_total,
                                d.net_unit_price,d.bruto_before_tax,d.total_tax,d.net_total_price,'Open',d.crtby,d.crtdt,d.updby,d.upddt FROM pickup_d p
                                INNER JOIN so_online_header h ON h.docno= p.barcode 
                                INNER JOIN so_online_detail d ON d.docno= p.barcode 
                                WHERE p.pickup_h_id='$docno'
                                GROUP BY d.nobar");
        $sql = "UPDATE so_online_header
                    INNER JOIN
                pickup_d ON so_online_header.docno = pickup_d.barcode 
            SET 
                so_online_header.status = 'CLOSED' ,
                pickup_d.status = 'Pick Up' 
            WHERE pickup_d.pickup_h_id='$docno'";
        return $this->db->query($sql);
    }
    function read_transactions_detail($pickup_h_id){ 
        $sql = "SELECT *
                FROM pickup_h
                WHERE id='$pickup_h_id'";
        return $this->db->query($sql);
    }
    function updateheaderdata($docno){
        $sql = "UPDATE so_online_header AS dest
                , (SELECT COUNT(nobar) item, SUM(qty_order) qty
                        , SUM(CEILING(so_online_detail.unit_price*so_online_detail.qty_order)) bruto
                        , SUM(CEILING(so_online_detail.disc_total*so_online_detail.qty_order)) disc
                        , SUM(CEILING(so_online_detail.bruto_before_tax)) before_tax
                        , SUM(CEILING(so_online_detail.net_unit_price*so_online_detail.qty_order)) after_tax
                        , SUM(CEILING(so_online_detail.total_tax)) ppn
                  FROM so_online_detail WHERE docno='$docno') AS src
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

    function cekdatapickup($tanggal){
         $sql = "SELECT IFNULL(MAX(fase_pickup),0)+1 fase from pickup_h where tgl='$tanggal'"; 
        $this->db->query($sql); 
        return $this->db->query($sql);
    }
}
