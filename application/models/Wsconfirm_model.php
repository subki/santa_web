<?php

class Wsconfirm_model extends CI_Model {

	public function __construct(){

        parent::__construct();
        $this->table = "sales_trans_header";
        $this->query = "select a.id, a.no_faktur,a.no_faktur2, a.seri_pajak
                  , a.doc_date, DATE_FORMAT(a.doc_date, '%d/%b/%Y') ak_doc_date
                  , DATE_FORMAT(a.doc_date, '%d/%m/%Y') ak_doc_date2
                  , a.faktur_date, DATE_FORMAT(a.faktur_date, '%d/%m/%Y') ak_faktur_date
                  , a.verifikasi_finance, c.top_day
                  , so.doc_date tgl_so, DATE_FORMAT(so.doc_date, '%d/%m/%Y') ak_tgl_so
                  , a.base_so, a.remark, a.status, a.qty_print, c.pkp, c.beda_fp, c.npwp, c.nama_pkp, c.alamat_pkp
                  , so.customer_code, c.customer_name, so.store_code, so.location_code
                  , so.salesman_id, sl.salesman_name, c.address1, c.address2, r.name as regency_name
                  , store.store_name, l.description as location_name, c.phone1
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
                  , coalesce(wp.keterangan,'') as ket
	            from $this->table a 
	            left join wholesales_payment wp on wp.sales_trans_header_id=a.id
	            left join (
	              packing_header ph
	              left join sales_order_header so on so.docno = ph.so_number
	              left join salesman sl on sl.salesman_id=so.salesman_id
	              inner join customer c on so.customer_code=c.customer_code
	              left join regencies r on r.id = c.regency_id
	              left join profile_p store on store.store_code=so.store_code
	              left join location l on l.location_code=so.location_code
	            ) on ph.docno = a.base_so
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr,$opt=0){
        $sql = "create temporary table tmp2 as
                $this->query 
                where c.payment_first='Yes' ";
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
    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "sales_trans_header_id", "asc","", $app, 1);
    }

    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('wholesales_payment');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('wholesales_payment',$data);
    }
    function insert_data($data){
        $this->db->insert('wholesales_payment', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('wholesales_payment');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('wholesales_payment');
    }
}
