<?php

class Regencies_model extends CI_Model {

	public function __construct(){

        parent::__construct();
    }

    function get_list_data($page,$rows,$sort,$order,$role,$fltr){
        $sql = "create temporary table temp as 
                select a.*
	            from regencies_amount a ";
        if($fltr!=''){
            $sql .= $fltr;
        }
        $sql .="order by " .$sort." $order ";
        $this->db->query($sql);

        $sql = "select a.id, a.regencies_id, a.customer_type_code, a.nilai_minimum,
                a.user_crt, a.date_crt, a.time_crt, b.name as regencies_name, c.description as customer_type_name,
	            (select count(a1.id) from temp a1 ) as total
	            from temp a 
	            left join regencies b on a.regencies_id=b.id 
	            left join customer_type c on a.customer_type_code = c.code";

        $data = $this->db->query($sql)->result();
        $sql = "drop table temp";
        $this->db->query($sql);
        return $data;
//        $sql = "create temporary table temp as
//                select a.id, a.regencies_id, a.customer_type_code, a.nilai_minimum,
//                a.user_crt, a.date_crt, a.time_crt, b.name as regencies_name, c.description as customer_type_name
//	            (select count(a1.id) from regencies_amount a1 ) as total
//	            from regencies_amount a
//	            left join regencies b on a.regencies_id=b.id
//	            left join customer_type c on a.customer_type_code = c.code ";
//        if($fltr!=''){
//            $sql .= $fltr;
//        }
//        $sql .="order by " .$sort." $order
//	            limit ".($page-1)*$rows.",".$rows;
//        return $this->db->query($sql)->result();
    }

    function read_data($code){
        $this->db->where('id',$code);
        return $this->db->get('regencies_amount');
    }
    function update_data($code, $data){
        $this->db->where('id',$code);
        $this->db->update('regencies_amount',$data);
    }
    function insert_data($data){
        $this->db->insert('regencies_amount', $data);
    }
    function delete_data($id){
        $this->db->where('id',$id);
        $this->db->delete('regencies_amount');
    }
    function read_transactions($code){
	    //nanti diubah
        $this->db->where('id',$code);
        return $this->db->get('regencies_amount');
    }
}
