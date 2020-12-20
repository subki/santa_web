<?php

class Stockopname_model extends CI_Model {

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
        $this->queryheader = "SELECT a.*,ifnull(u1.fullname,a.crtby) as useropname from $this->table2 a                
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
                                WHERE a.periode='$tanggal' AND a.location_code='$gondola' AND a.nobar='$barcode'
                                GROUP BY a.nobar ";
        return $this->db->query($q);
    }
    function read_dataopname($from,$to,$store_code,$location_code){
        $q = $this->queryheader." where a.trx_date between '$from' and '$to' and a.store_code='$store_code' and a.on_loc='$location_code' and a.status='Open' and (a.ref_no IS NULL OR a.ref_no ='')";
        return $this->db->query($q);
    }
    function update_refno($docno,$from,$to,$location_code,$store_code){
        $q = "UPDATE hal_gondola a set a.ref_no='$docno' where a.trx_date between '$from' and '$to' and a.store_code='$store_code' and a.on_loc='$location_code' and a.status='Open' and (a.ref_no IS NULL OR a.ref_no ='') ";
        return $this->db->query($q);
    }
    function read_data($code){
        $q = $this->queryheader." where a.trx_no='$code'";
        return $this->db->query($q);
    }
    function read_dataadj($code){
        $q = $this->queryheaderadj." where a.trx_no='$code'";
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
        $this->db->where('trx_no',$id);
        $this->db->delete($this->table);
          $sql1 = " DELETE FROM $this->tabledetail WHERE trx_no='$id'";
        $this->db->query($sql1);

          $sql2 = " DELETE FROM $this->table2 WHERE trx_no='$id'";
        $this->db->query($sql2);

          $sql3 = " DELETE FROM $this->table3 WHERE trx_no='$id'";
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
 
    function get_list_data_detailall($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table temp as
                SELECT a.uom,a.trx_no,a.item,a.product_code,a.qty 'QTYStock' ,IFNULL(SUM(b.taking_qty),0) 'QTYScan',SUM(b.taking_qty)-a.qty Selisih,a.crtdt,b.product_code productscan,b.crtdt crtdtscan
                FROM adjustment_dtl a 
                INNER JOIN hal_gondola g ON a.trx_no=g.ref_no  
                COLLATE utf8mb4_general_ci
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
        $sql = "drop table temp";
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
    function update_data_detail($docno,$code, $data){
        $this->db->where('id',$code);
        $this->db->update("so_online_detail",$data);
        $this->updateheaderdata($docno);
    }
  
    function save_detail($data){
      
        $this->db->insert($this->tabledetail, $data);
    }
    function insert_data_detail($docno,$data){ 
        $this->db->insert("so_online_detail", $data);
        $this->updateheaderdata($docno);
    }
    function delete_data_detail($id,$code){
        $this->db->where('trx_no',$id); 
        $this->db->where('barcode',$code);  
        $this->db->delete("dtl_gondola"); 
    }
    function updatestatusstokopname($docno, $data){ 
        $this->db->where('trx_no',$docno);
        $this->db->update("adjustment_hdr",$data);
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
