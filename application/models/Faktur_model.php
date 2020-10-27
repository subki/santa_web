<?php

class Faktur_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function insert_batch($data){
        return $this->db->insert_batch('seri_pajak', $data);
    }

    function insert_data($data){
        return $this->db->insert('seri_pajak', $data);
    }

    function read_available_faktur($prd){
        $sql = "select  id, periode, LPAD(seqno,13,'0') seqno from seri_pajak where inuse=0 and periode='$prd' order by seqno asc";
        return $this->db->query($sql);
    }
    function read_data($prd,$seq){
        $sql = "select  * from seri_pajak where periode='$prd' and seqno='$seq'";
        return $this->db->query($sql);
    }
    function read_data2($id){
        $sql = "select  * from seri_pajak where id=$id";
        return $this->db->query($sql);
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('seri_pajak',$data);
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr, $opt=0){
        $sql = "create temporary table tmp as
                  select a.id, a.periode, a.seqno, a.refno, a.inuse
                  , ifnull(u1.fullname,a.crtby) as crtby, ifnull(u2.fullname, a.updby) as updby
                  , a.crtdt tanggal_crt, a.upddt tanggal_upd, DATE_FORMAT(a.crtdt, '%d/%b/%Y %T') crtdt
                  , DATE_FORMAT(a.upddt, '%d/%b/%Y %T') upddt
	            from seri_pajak a
	            left join users u1 on a.crtby=u1.user_id
	            left join users u2 on a.updby=u2.user_id ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $this->db->query($sql);

        $sql = "select a.*,
	            (select count(a1.id) from tmp a1 ) as total
	             from tmp a ";
        $sql .="order by " .$sort." $order
	            limit ".($page-1)*$rows.",".$rows;
        return $opt==1?$this->db->query($sql) : $this->db->query($sql)->result();
    }

    function get_list_data2($app){
        return $this->get_list_data(1, 999999999999, "seqno", "asc","", $app, 1);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('seri_pajak');
    }
    function read_transactions($code){
        $this->db->where('inuse',1);
        $this->db->where('id',$code);
        return $this->db->get('seri_pajak');
    }
}
