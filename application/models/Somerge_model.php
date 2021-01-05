<?php

class Somerge_model extends CI_Model {

    private $table;
    private $query;
	public function __construct(){
        parent::__construct();
        $this->table = "adjustment_hdr";
        $this->tabledetail = "adjustment_dtl";
        $this->table2 = "hal_gondola";
        $this->table3 = "dtl_gondola"; 
        $this->queryheaderadj = "SELECT a.*,ifnull(u1.fullname,a.crtby) as useropname from $this->table a                
                            left join users u1 on a.crtby=u1.user_id
                            left join users u2 on a.updby=u2.user_id ";
        $this->queryheader = "SELECT a.*,ifnull(u1.fullname,a.crtby) as useropname,l.description location_name from $this->table2 a  
                            INNER JOIN location l on a.on_loc=l.location_code              
                            left join users u1 on a.crtby=u1.user_id
                            left join users u2 on a.updby=u2.user_id ";
        $this->querydetail = "SELECT b.* from $this->tabledetail b INNER JOIN $this->table a ON a.trx_no=b.trx_no ";
        $this->querydetailOp = "SELECT b.*,ifnull(u1.fullname,b.crtby) as crtby1,ifnull(u2.fullname, b.updby) as updby1
                          , a.crtdt tanggal_crt, DATE_FORMAT(b.crtdt, '%d/%b/%Y %T') crtdt1
                          , DATE_FORMAT(b.upddt, '%d/%b/%Y %T') upddt1 
                          from $this->table3 b 
                            INNER JOIN $this->table2 a ON a.trx_no=b.trx_no               
                            left join users u1 on a.crtby=u1.user_id
                            left join users u2 on a.updby=u2.user_id 
                              ";
        $this->query2 = "SELECT * from $this->table2";
        $this->query3 = "SELECT d.*,s.status onlinestatus FROM pickup_d d
                        INNER JOIN so_online_header s ON s.docno=d.barcode";
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                $this->queryheader ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.trx_no) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }

    function read_data_by_so($code){
        $q = $this->query3." where barcode='$code' AND s.status IN('OPEN','ON ORDER')";
        return $this->db->query($q);
    } 
    function cekOPadjust($barcode,$trx_no,$gondola){
        $q =" SELECT * FROM dtl_gondola WHERE trx_no='$trx_no' AND gondola='$gondola' AND barcode='$barcode' ";
        return $this->db->query($q);
    } 
    function cekOP($barcode,$tanggal,$gondola){ 
         $q ="  SELECT a.nobar, c.product_code,d.uom_id AS uom,a.location_code,
                  FLOOR(a.saldo_akhir/(IFNULL((SELECT convertion FROM product_uom_convertion WHERE uom_from=c.satuan_jual AND uom_to=c.satuan_stock LIMIT 1),0))) stock
                  ,FLOOR(a.saldo_akhir/(IFNULL((SELECT convertion FROM product_uom_convertion WHERE uom_from=c.satuan_jual AND uom_to=c.satuan_stock LIMIT 1),0))) taking
                  ,p.price_pkp, 
                  FLOOR(a.saldo_akhir/(IFNULL((SELECT convertion FROM product_uom_convertion WHERE uom_from=c.satuan_jual AND uom_to=c.satuan_stock LIMIT 1),0)))
                  * p.price_pkp total_cost FROM stock a
                                INNER JOIN (
                                    product_barang b 
                                    INNER JOIN product c ON b.product_id=c.id
                                    INNER JOIN product_uom d ON c.satuan_stock=d.uom_code
                                    INNER JOIN product_uom e ON c.satuan_jual=e.uom_code
                                    INNER JOIN product_price p ON p.product_id=b.product_id
                                ) ON a.nobar=b.nobar
                                WHERE a.periode='$tanggal'  AND a.nobar='$barcode'
                                GROUP BY a.nobar ";
        return $this->db->query($q);
    }
    function read_dataopname($from,$to,$store_code,$location_code){
        $q = $this->queryheader." where a.trx_date between '$from' and '$to' and a.store_code='$store_code' and a.on_loc='$location_code' and a.status='Posted' and (a.ref_no IS NULL OR a.ref_no ='')";
        return $this->db->query($q);
    }
    function update_refno($docno,$from,$to,$location_code,$store_code){
        $q = "UPDATE hal_gondola a set a.ref_no='$docno' where a.trx_date between '$from' and '$to' and a.store_code='$store_code' and a.on_loc='$location_code' and a.status='Posted' and (a.ref_no IS NULL OR a.ref_no ='') ";
        return $this->db->query($q);
    }
    function read_data($code){
        $q = $this->queryheaderadj." where a.trx_no='$code'";
        return $this->db->query($q);
    }
    function read_dataadj($code){
        $q = $this->queryheaderadj." where a.trx_no='$code'";
        return $this->db->query($q);
    } 
    function cekOpname($from,$to,$location_code,$store_code){
        $q = $this->queryheaderadj." WHERE a.trx_date BETWEEN '$from' AND '$to' AND a.store_code='$store_code' AND a.on_loc='$location_code' AND a.status='Open'";
        return $this->db->query($q);
    } 
    function read_datadetail($code){
        $q = "SELECT count(*) jumlah from $this->tabledetail  where trx_no='$code'";
        return $this->db->query($q);
    }
    function read_opnamepost($from,$to,$location_code,$store_code){
        $q = "SELECT (  SELECT COUNT(DISTINCT d.item )FROM dtl_gondola d INNER JOIN hal_gondola h ON h.trx_no=d.trx_no
                   WHERE h.trx_date BETWEEN '$from' AND '$to' AND h.store_code='$store_code' AND h.on_loc='$location_code' AND h.status='Open' AND (h.ref_no IS NULL OR h.ref_no ='')
                     ) tot_item, SUM(d.taking_qty) tot_qty 
                  FROM dtl_gondola d
                  INNER JOIN hal_gondola h ON h.trx_no=d.trx_no
                    WHERE h.trx_date BETWEEN '$from' AND '$to' AND h.store_code='$store_code' AND h.on_loc='$location_code' AND h.status='Open' AND (h.ref_no IS NULL OR h.ref_no ='')";
        return $this->db->query($q);
    }
    function read_datadetailopname($code){
        $q = "SELECT ( SELECT COUNT(item) FROM
                    ( SELECT DISTINCT item FROM dtl_gondola WHERE trx_no = '$code'
                    ) AS item) tot_item, SUM(taking_qty) tot_qty 
                  FROM dtl_gondola WHERE trx_no='$code'";
        return $this->db->query($q);
    }
    function update_data($code, $data){
        $this->db->where('trx_no',$code);
        $this->db->update($this->table2,$data); 
    }
    function update_dataclose($code, $data){
        $this->db->where('trx_no',$code);
        $this->db->update($this->table,$data); 
    }
    function update_dataprint($code, $data){
        $this->db->where('trx_no',$code);
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
    function insert_datagondola($datagondola){ 
        $this->db->insert($this->table2, $datagondola); 
    }
    function delete_data($id){  
          $sql1 = " DELETE FROM adjustment_hdr WHERE trx_no='$id'";
        $this->db->query($sql1);

          $sql2 = " DELETE FROM adjustment_dtl WHERE trx_no='$id'";
        $this->db->query($sql2); 

          $sql4 = " DELETE FROM generate_opn WHERE trx_no='$id'";
        $this->db->query($sql4); 
          $sql5 = " DELETE FROM generate_opndetail WHERE trx_no='$id'";
        $this->db->query($sql5); 
        
            $sql3 = "UPDATE hal_gondola set ref_no='',status='Open' where ref_no='$id'";
        $this->db->query($sql3); 
    }
    function read_transactions($code){
        $this->db->where('id',$code);
        return $this->db->get('product');
    }
    function generate_auto_number(){ 
        $sql = "SELECT IFNULL(CONCAT(DATE_FORMAT(NOW(),'%y%m%d'),LPAD(MAX(RIGHT(trx_no,6))+1,6,'0')),
                CONCAT(DATE_FORMAT(NOW(),'%y%m%d'),LPAD(1,6,'0'))) AS nomor 
                FROM hal_gondola WHERE LEFT(trx_no,6)= CONCAT(DATE_FORMAT(NOW(),'%y%m%d')) ORDER BY trx_no DESC";
        return $this->db->query($sql)->row()->nomor;
    }
    function generate_auto_numberadj(){ 
        $sql = "SELECT IFNULL(CONCAT('OPN',DATE_FORMAT(NOW(),'%Y'),LPAD(MAX(RIGHT(trx_no,6))+1,6,'0')),
                CONCAT('OPN',DATE_FORMAT(NOW(),'%Y'),LPAD(1,6,'0'))) AS nomor 
                FROM adjustment_hdr 
                WHERE LEFT(trx_no,LENGTH(CONCAT('OPN',DATE_FORMAT(NOW(),'%Y')))) = CONCAT('OPN',DATE_FORMAT(NOW(),'%Y')) ORDER BY trx_no DESC";
        return $this->db->query($sql)->row()->nomor;
    }
  function get_list_data_detail($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table tmp2 as
                $this->querydetailOp ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
                (select count(a1.trx_no) from tmp a1 ) as total
                 from tmp a ";
        $sql .="order by " .$sort." $order
                limit ".($page-1)*$rows.",".$rows;
        return $this->db->query($sql)->result();
    }
 
    function get_list_dataopname($page,$rows,$sort,$order,$role,$fltr){ 
             $sql = "create temporary table tmp2 as 
               SELECT a.uom,a.trx_no,a.item,a.product_code,a.qty 'QTYStock' ,
                IFNULL(SUM(b.taking_qty),0) 'QTYScan',SUM(b.taking_qty)-a.qty Selisih,a.crtdt,b.product_code productscan,b.crtdt crtdtscan 
                FROM adjustment_dtl a 
                INNER JOIN hal_gondola g ON a.trx_no=g.ref_no  
                INNER JOIN dtl_gondola b ON b.item=a.item AND b.trx_no=g.trx_no  
                GROUP BY a.item 
                ORDER BY b.crtdt DESC,b.product_code ASC  ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from tmp2 ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);
        $sql = "select a.*,
                (select count(a1.trx_no) from tmp a1 ) as total
                 from tmp a ";
        $sql .="HAVING Selisih <> 0 order by trx_no $order
                limit ".($page-1)*$rows.",".$rows;
        $data = $this->db->query($sql)->result();
        $sql = "drop table tmp2";
        $this->db->query($sql);

        return $data;
    }
    function get_list_data_detailall($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table temp as
                SELECT a.uom,a.trx_no,a.item,a.product_code,a.qty 'QTYStock' ,
                IFNULL(SUM(b.taking_qty),0) 'QTYScan',SUM(b.taking_qty)-a.qty  Selisih,a.crtdt,b.product_code productscan,b.crtdt crtdtscan 
                FROM adjustment_dtl a 
                INNER JOIN hal_gondola g ON a.trx_no=g.ref_no  
                LEFT JOIN dtl_gondola b ON b.item=a.item AND b.trx_no=g.trx_no 
                GROUP BY a.item 
                ORDER BY b.crtdt DESC,b.product_code ASC  ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from temp a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);


        $sql = "select a.*,
                (select count(a1.trx_no) from tmp a1 ) as total
                 from tmp a ";
        $sql .=" limit ".($page-1)*$rows.",".$rows;

        $data = $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
        $sql = "drop table tmp";
        $this->db->query($sql); 
        return $data;
    }
    function get_list_data_detailgondola($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table temp as
                SELECT b.uom,a.trx_no,b.item,b.product_code,b.qty 'QTYStock' ,IFNULL(a.taking_qty,0) 'QTYScan',
                a.taking_qty-b.qty Selisih,b.crtdt,a.product_code productscan,a.crtdt crtdtscan 
                FROM dtl_gondola a 
                INNER JOIN adjustment_dtl b ON b.item=a.item  
                GROUP BY a.item
                ORDER BY a.crtdt DESC,a.product_code ASC ";
        $this->db->query($sql);
        $sql = "create temporary table tmp as select * from temp a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);


        $sql = "select a.*,
                (select count(a1.trx_no) from tmp a1 ) as total
                 from tmp a ";
        $sql .=" limit ".($page-1)*$rows.",".$rows;

        $data = $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
        $sql = "drop table tmp";
        $this->db->query($sql);
        $sql = "drop table temp";
        $this->db->query($sql);
        return $data;
    }
    function gettotalvariance($trxno){
        $sql = "SELECT  CONCAT(FORMAT(SUM(total_net), 2)) total_net FROM generate_opndetail WHERE trx_no='$trxno'
                GROUP BY trx_no";
        return $this->db->query($sql);
    }
    function opnametotal($docno){
        $sql = " SELECT count(a.item) totaldata FROM adjustment_dtl a 
                LEFT JOIN dtl_gondola b ON b.item=a.item 
                WHERE a.trx_no='$docno'";
        return $this->db->query($sql);
    }
    function generate_seqno($docno){
        $sql = "SELECT IFNULL(LPAD(MAX(seqno)+1,3,'0'), LPAD(1,3,'0')) AS seqno
                FROM so_online_detail
                WHERE docno='$docno'";
        return $this->db->query($sql)->row()->seqno;
    }

 
    function cek_detail($docno, $nobar, $tipe){
        $this->db->where('docno',$docno);
        $this->db->where('nobar',$nobar);
        $this->db->where('tipe',$tipe);
        return $this->db->get('so_online_detail');
    }
    function read_data_detailID($id,$code){
        $this->db->where('item',$code);
        $this->db->where('trx_no',$id);
        return $this->db->get('dtl_gondola');
    }
    function update_data_detail($trx_no,$item, $disc, $margin,$type){
        $sql = "UPDATE generate_opn_varience set disc='$disc',margin='$margin',disc_amaount=($disc*retail_price)/100 where trx_no='$trx_no' and item='$item'";
        if($this->db->query($sql)){
             $sql2 = "UPDATE generate_opn_varience set margin_amaount=($margin*disc_amaount)/100 where trx_no='$trx_no' and item='$item'";
                 if($this->db->query($sql2)){ 
                    $sql3 = "UPDATE generate_opn_varience set subtotal_retail=(qty*retail_price)-(qty*disc_amaount)-(qty*margin_amaount) where trx_no='$trx_no' and item='$item'";
                    if($this->db->query($sql3)){ 
                         $this->updateheaderdata($trx_no,$type);
                     }else return false;
             }else return false;
        }else return false;
    }
  
    function updateheaderdata($trx_no,$type){  
        if($type=='PLUS'){ 
             $qplus = "UPDATE generate_opndetail AS dest
                , (SELECT SUM(IF(qty >= 0,TRUE,FALSE)) total_item,SUM(IF(qty>= 0,qty,0)) total_qty ,  
            SUM(IF(qty>= 0,CEILING(generate_opn_varience.qty*retail_price),0)) total_gross , 
            SUM(IF(qty>= 0,CEILING(generate_opn_varience.disc),0)) total_disc , 
            SUM(IF(qty>= 0,CEILING(generate_opn_varience.subtotal_retail),0)) total_net  
            FROM generate_opn_varience 
            WHERE trx_no='$trx_no') AS src
                SET dest.total_qty = src.total_qty
                    , dest.total_gross=src.total_gross
                    , dest.total_disc = src.total_disc
                    , dest.total_net = src.total_net 
             WHERE dest.trx_no='$trx_no' AND dest.varian_type='Plus'"; 
                $this->db->query($qplus);
            }else{
 
            $qminus= "UPDATE generate_opndetail AS dest
                 ,(SELECT SUM(IF(qty < 0,TRUE,FALSE)) total_item,SUM(IF(qty < 0,qty,0)) total_qty ,  
                    SUM(IF(qty <0,CEILING(generate_opn_varience.qty*retail_price),0)) total_gross , 
                    SUM(IF(qty < 0,CEILING(generate_opn_varience.disc),0)) total_disc , 
                    SUM(IF(qty <0,CEILING(generate_opn_varience.subtotal_retail),0)) total_net  
                    FROM generate_opn_varience 
                    WHERE trx_no='$trx_no') AS src
                        SET dest.total_qty = src.total_qty
                        , dest.total_gross=src.total_gross
                        , dest.total_disc = src.total_disc
                        , dest.total_net = src.total_net 
                    WHERE dest.trx_no='$trx_no' AND dest.varian_type='Minus'"; 
                $this->db->query($qminus); 
            }
        
    }
    function save_detail($data){
      
        $this->db->insert($this->tabledetail, $data);
    } 
    function delete_data_detail($trxno){
        $sql = "UPDATE hal_gondola set ref_no='',status='Open' where trx_no='$trxno'";
        return $this->db->query($sql); 
    }
    function updatestatusstokopname($docno, $data){ 
        $this->db->where('trx_no',$docno);
        $this->db->update("hal_gondola",$data);
    }  
    function read_discount($trxno){
        $sql = "SELECT  a.trx_no,a.on_loc,f.discount_id,f.customer_code,d.status FROM adjustment_hdr a
                LEFT JOIN discount_for f ON f.location_code=a.on_loc
                LEFT JOIN discount d ON d.customer_code=f.customer_code AND d.discount_id=f.discount_id 
                WHERE a.trx_no='$trxno' AND d.status IN('OPEN','POSTING')
                ORDER BY d.discount_id DESC 
                LIMIT 1";
        return $this->db->query($sql); 
    }
    function opengenerate($trxno){
       $delete_a="DELETE FROM generate_opn where trx_no='$trxno'";
        $delete_b="DELETE FROM generate_opn_varience where trx_no='$trxno'";
        $delete_c="DELETE FROM generate_opndetail where trx_no='$trxno'";
                $this->db->query($delete_a);
                $this->db->query($delete_b);
                return $this->db->query($delete_c); 
    }
    function generateopn($trxno,$id){ 
        $delete_a="DELETE FROM generate_opn where trx_no='$trxno'";
        $delete_b="DELETE FROM generate_opn_varience where trx_no='$trxno'";
        $delete_c="DELETE FROM generate_opndetail where trx_no='$trxno'";
                $this->db->query($delete_a);
                $this->db->query($delete_b);
                $this->db->query($delete_c);
        $q = "INSERT INTO generate_opn(trx_no,trx_date,STATUS,periode,location_code,store_code)
                SELECT trx_no,trx_date,1,DATE_FORMAT(trx_date, '%Y%c'),on_loc,store_code
                  FROM adjustment_hdr
                WHERE trx_no='$trxno'"; 
                $this->db->query($q);

            $q2 = " UPDATE adjustment_dtl AS t
                            LEFT JOIN (
                                SELECT c.discount,b.sku,b.article_code FROM category_article c
                                INNER JOIN product b ON b.article_code = c.article_code 
                                WHERE c.customer_code ='$id' 
                                GROUP BY c.article_code
                            ) AS m ON
                                m.sku = t.item AND
                                m.article_code = t.product_code
                        SET
                            t.disc = IFNULL(m.discount,0) 
                        WHERE
                            t.trx_no ='$trxno'";  
                        if($this->db->query($q2)) {
                        $q3 = "UPDATE adjustment_dtl AS dest 
                                    INNER JOIN adjustment_hdr a ON a.trx_no=dest.trx_no
                                    INNER JOIN hal_gondola h ON h.ref_no=a.trx_no
                                    LEFT JOIN dtl_gondola d  ON dest.product_code=d.product_code AND d.trx_no=h.trx_no
                                SET dest.soh = IFNULL(d.taking_qty,0),
                                    dest.varience=(IFNULL(d.taking_qty,0)-dest.qty) 
                                WHERE dest.trx_no='$trxno'"; 
                                    if($this->db->query($q3)) {
                                         $q4 = "UPDATE adjustment_dtl AS dest 
                                                LEFT JOIN product p ON p.product_code=dest.product_code
                                                LEFT JOIN product_price pr ON pr.product_id=p.id 
                                                SET dest.unit_retail = pr.price_pkp 
                                                WHERE dest.trx_no='$trxno'"; 
                                                 if($this->db->query($q4)){
                                                      $q5 = "INSERT INTO generate_opn_varience (retail_price,trx_no,item,product_code,qty,uom,disc,disc_amaount,subtotal_retail)
                                                            SELECT unit_retail,trx_no,item,product_code,varience,uom,disc,unit_retail*disc disc_amaount,(varience*unit_retail)-((unit_retail*disc)*varience)subtotal_retail
                                                            FROM adjustment_dtl
                                                            WHERE trx_no='$trxno'"; 
                                                            if($this->db->query($q5)){
                                                                $qplus = "INSERT INTO generate_opndetail(trx_no,varian_type,total_item,total_qty,total_gross,total_disc,total_net)
                                                                     SELECT '$trxno','Plus',SUM(IF(qty >= 0,TRUE,FALSE)) total_item,SUM(IF(qty>= 0,qty,0)) total_qty ,  
                                                                                        SUM(IF(qty>= 0,CEILING(generate_opn_varience.qty*retail_price),0)) total_gross , 
                                                                                        SUM(IF(qty>= 0,CEILING(generate_opn_varience.disc),0)) total_disc , 
                                                                                        SUM(IF(qty>= 0,CEILING(generate_opn_varience.subtotal_retail),0)) total_net  
                                                                                        FROM generate_opn_varience 
                                                                                        WHERE trx_no='$trxno'"; 
                                                                    $this->db->query($qplus);
                                                                $qminus= "INSERT INTO generate_opndetail(trx_no,varian_type,total_item,total_qty,total_gross,total_disc,total_net)
                                                                     SELECT '$trxno','Minus',SUM(IF(qty < 0,TRUE,FALSE)) total_item,SUM(IF(qty < 0,qty,0)) total_qty ,  
                                                                        SUM(IF(qty <0,CEILING(generate_opn_varience.qty*retail_price),0)) total_gross , 
                                                                        SUM(IF(qty < 0,CEILING(generate_opn_varience.disc),0)) total_disc , 
                                                                        SUM(IF(qty <0,CEILING(generate_opn_varience.subtotal_retail),0)) total_net  
                                                                        FROM generate_opn_varience 
                                                                        WHERE trx_no='$trxno'"; 
                                                                    $this->db->query($qminus); 
                                                                return true;
                                                            }  else return false; 
                                                        }else return false; 
                                    }else return false;
                        }else return false;  
    }  
    function insert_datadetail($periode,$location_code,$store_code,$trx_no){ 
        $sql = "INSERT INTO adjustment_dtl(store_code,trx_no,item,product_code,UOM,location,qty,taking,unit_cost,total_cost)
                  SELECT '$store_code','$trx_no',a.nobar, c.product_code,d.uom_id AS id_stock,a.location_code,
                  FLOOR(a.saldo_akhir/(IFNULL((SELECT convertion FROM product_uom_convertion WHERE uom_from=c.satuan_jual AND uom_to=c.satuan_stock LIMIT 1),0))) stock
                  ,FLOOR(a.saldo_akhir/(IFNULL((SELECT convertion FROM product_uom_convertion WHERE uom_from=c.satuan_jual AND uom_to=c.satuan_stock LIMIT 1),0))) taking
                  ,p.price_pkp, 
                  FLOOR(a.saldo_akhir/(IFNULL((SELECT convertion FROM product_uom_convertion WHERE uom_from=c.satuan_jual AND uom_to=c.satuan_stock LIMIT 1),0)))
                  * p.price_pkp total_cost FROM stock a
                                INNER JOIN (
                                    product_barang b 
                                    INNER JOIN product c ON b.product_id=c.id
                                    INNER JOIN product_uom d ON c.satuan_stock=d.uom_code
                                    INNER JOIN product_uom e ON c.satuan_jual=e.uom_code
                                    INNER JOIN product_price p ON p.product_id=b.product_id
                                ) ON a.nobar=b.nobar
                                WHERE a.periode='$periode' AND a.location_code='$location_code'
                                GROUP BY nobar";
        return $this->db->query($sql);
    }    
    function insert_data_opname($data){ 
        $this->db->insert($this->table3, $data);
    }   
    function update_data_opname($barcode,$trx_no,$gondola, $data){ 

        $this->db->where('barcode',$barcode);
        $this->db->where('trx_no',$trx_no);
        $this->db->where('gondola',$gondola);
        $this->db->update($this->table3,$data); 
    }   
    function get_list_dataloc($page,$rows,$sort,$order,$role,$fltr, $opt=0, $golongan=""){
        $sql = "create temporary table tmp2 as
                SELECT * from location ";
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
}
