<?php

class Auth_model extends CI_Model {

	public function __construct(){
        
        parent::__construct();
    }
	
	function check_user($uid){

        $sql = "select a.user_id, a.nik, a.fullname, a.store_code,a.location_code
                , b.store_name, c.description location_name, b.default_stock_l as lokasi_sales
                , AES_DECRYPT(a.user_password,'KEYAES838431') as pass1, a.user_password as pass
                from users a 
                left join profile_p b on a.store_code=b.store_code
                left join location c on a.location_code = c.location_code
                where a.user_name='$uid' ";

        return $this->db->query($sql)->row();
	}

	function pindah($prd){
	    if($prd=="201501"){
	        $sql = "INSERT INTO stock (location_code, nobar, periode)
                    SELECT location_code, sku AS nobar, '201501' AS periode
                    FROM product_history
                    GROUP BY location_code, sku;";
        }else {
	        $sql = "INSERT INTO stock (location_code, nobar, periode, saldo_awal)
                SELECT location_code, nobar, '$prd', saldo_akhir
                FROM stock where periode= period_add('$prd',-1)";
        }
            $this->db->query($sql);
    }
	function awal($prd){
        $sql = "UPDATE stock a
                LEFT JOIN (
                    SELECT DATE_FORMAT(b.trx_date,'%Y%m') periode, b.sku, b.location_code, b.trx_type, SUM(b.qty) AS qty FROM product_history b
                    GROUP BY DATE_FORMAT(b.trx_date,'%Y%m'), b.sku, b.location_code, b.trx_type
                ) b ON a.periode = b.periode AND b.sku=a.nobar AND b.location_code=a.location_code AND b.trx_type='BEGINNING'
                SET
                    a.saldo_awal  = a.saldo_awal+IFNULL(b.qty,0)
                WHERE a.periode='$prd' ";
        $this->db->query($sql);
    }
	function masuk($prd){
        $sql = "UPDATE stock a
                LEFT JOIN (
                    SELECT DATE_FORMAT(b.trx_date,'%Y%m') periode, b.sku, b.location_code, b.trx_type, SUM(b.qty) AS qty FROM product_history b
                    GROUP BY DATE_FORMAT(b.trx_date,'%Y%m'), b.sku, b.location_code, b.trx_type
                ) b ON a.periode = b.periode AND b.sku=a.nobar AND b.location_code=a.location_code AND b.trx_type IN('DO IN','RECEIVING')
                SET
                    a.do_masuk  = IFNULL(b.qty,0)
                WHERE a.periode='$prd' ";
        $this->db->query($sql);
    }
	function keluar($prd){
        $sql = "UPDATE stock a
                LEFT JOIN (
                    SELECT DATE_FORMAT(b.trx_date,'%Y%m') periode, b.sku, b.location_code, b.trx_type, SUM(b.qty) AS qty FROM product_history b
                    GROUP BY DATE_FORMAT(b.trx_date,'%Y%m'), b.sku, b.location_code, b.trx_type
                ) b ON a.periode = b.periode AND b.sku=a.nobar AND b.location_code=a.location_code AND b.trx_type ='DO OUT'
                SET
                    a.do_keluar  = IFNULL(b.qty,0)
                WHERE a.periode='$prd' ";
        $this->db->query($sql);
    }
	function adjust($prd){
        $sql = "UPDATE stock a
                LEFT JOIN (
                    SELECT DATE_FORMAT(b.trx_date,'%Y%m') periode, b.sku, b.location_code, b.trx_type, SUM(b.qty) AS qty FROM product_history b
                    GROUP BY DATE_FORMAT(b.trx_date,'%Y%m'), b.sku, b.location_code, b.trx_type
                ) b ON a.periode = b.periode AND b.sku=a.nobar AND b.location_code=a.location_code AND b.trx_type ='ADJUSTMENT'
                SET
                    a.penyesuaian  = IFNULL(b.qty,0)
                WHERE a.periode='$prd' ";
        $this->db->query($sql);
    }
	function jual($prd){
        $sql = "UPDATE stock a
                LEFT JOIN (
                    SELECT DATE_FORMAT(b.trx_date,'%Y%m') periode, b.sku, b.location_code, b.trx_type, SUM(b.qty) AS qty FROM product_history b
                    GROUP BY DATE_FORMAT(b.trx_date,'%Y%m'), b.sku, b.location_code, b.trx_type
                ) b ON a.periode = b.periode AND b.sku=a.nobar AND b.location_code=a.location_code AND b.trx_type ='PENJUALAN'
                SET
                    a.penjualan  = IFNULL(b.qty,0)
                WHERE a.periode='$prd' ";
        $this->db->query($sql);
    }
	function retur($prd){
        $sql = "UPDATE stock a
                LEFT JOIN (
                    SELECT DATE_FORMAT(b.trx_date,'%Y%m') periode, b.sku, b.location_code, b.trx_type, SUM(b.qty) AS qty FROM product_history b
                    GROUP BY DATE_FORMAT(b.trx_date,'%Y%m'), b.sku, b.location_code, b.trx_type
                ) b ON a.periode = b.periode AND b.sku=a.nobar AND b.location_code=a.location_code AND b.trx_type ='RETUR PENJUALAN'
                SET
                    a.pengembalian  = IFNULL(b.qty,0)
                WHERE a.periode='$prd' ";
        $this->db->query($sql);
    }
	function akhir($prd){
        $sql = "UPDATE stock SET saldo_akhir=saldo_awal+do_masuk+do_keluar+penyesuaian+penjualan+pengembalian
                WHERE periode='$prd' ";
        $this->db->query($sql);
    }
}