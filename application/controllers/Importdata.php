<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Importdata extends IO_Controller {

	function __construct(){

		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	function index(){
		$data['title']      = 'Import Data From CSV';
		$data['content']    = $this->load->view('vImport',$data,TRUE);

		$this->load->view('main',$data);
	}

	function upload_data() {
		$respon=array();
		$respon_gagal=array();
		$dbg1 = "";
		$dbg2 = "";

		$i=0;
		foreach ( $_FILES['userfile']['tmp_name'] as $file ) {
			$namafile = $_FILES['userfile']['name'][$i];
			$handle = fopen($file, "r");

			// read and ignore headers
			fgetcsv($handle);
			$total_row=0;
			$insert_row=0;
			$nomor_header="";

			while(($val = fgetcsv($handle, 10000, ";")) !== false) {
				if (count($val) == 0) continue;

				if($namafile=="product.csv"){
					$total_row++;
					$read_uombeli = $this->model_productuom->read_data2($val[10]);
					if($read_uombeli->num_rows()>0){
						$beli = $read_uombeli->row()->uom_code;
						$read_uomstk = $this->model_productuom->read_data2($val[11]);
						if($read_uomstk->num_rows()>0){
							$stk = $read_uomstk->row()->uom_code;
							$read_uomjual = $this->model_productuom->read_data2($val[12]);
							if($read_uomjual->num_rows()>0){
								$jual = $read_uomjual->row()->uom_code;
//                            $ss = $this->model_product->generate_auto_number();
//                            $nomor = $ss;

								$read_sku = $this->modul_product->get_product_by_sku($val[0]);
								if($read_sku->num_rows()>0){
									array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"SKU sudah terdaftar"));
								}else{
									$data = array(
										'sku' => isset($val[0])?$val[0]:'',
										'product_code' => isset($val[1])?$val[1]:'',
										'article_code' => isset($val[2])?$val[2]:'',
										'product_name' => isset($val[3])?$val[3]:'',
										'brand_code' => isset($val[4])?$val[4]:'',
										'class_code' => isset($val[5])?$val[5]:'',
										'subclass_code' => isset($val[6])?$val[6]:'',
										'supplier_code' => isset($val[7])?$val[7]:'',
										'size_code' => isset($val[8])?$val[8]:'',
										'colour_code' => isset($val[9])?$val[9]:'',
										'satuan_beli' => $beli,
										'satuan_stock' => $stk,
										'satuan_jual' => $jual,
										'status_product' => isset($val[13])?$val[13]:'',
										'jenis_barang' => isset($val[14])?$val[14]:'',
										'total_soh' => 0,
										'min_stock' => 0,
										'max_stock' => 0,
										'first_production' => date('Y-m-d'),
										'crtby' => $this->session->userdata('user_id'),
										'crtdt' => date('Y-m-d H:i:s')
									);
									$insertId = $this->model_product->insert_data($data);
									$data = array(
										'product_id' => $insertId,
										'nobar' => isset($val[0])?$val[0]:'',
										'nmbar' => isset($val[3])?$val[3]:'',
										'warna' => isset($val[9])?$val[9]:'',
										'soh' => 0,//$input['soh'],
										'min_stock' => 0,//$input['min_stock'],
										'max_stock' => 0,//$input['max_stock'],
										'user_crt' => $this->session->userdata('user_id'),
										'date_crt' => date('Y-m-d'),
										'time_crt' => date('H:i:s'),
									);
									$this->model_subproduct->insert_data($data);
									$this->model_subproduct->update_header($insertId);

									$data2 = array(
										'article_code' => isset($val[2])?$val[2]:'',
										'art_colour_id' => isset($val[9])?$val[9]:'',
										'art_size_id' => isset($val[8])?$val[8]:'',
										'product_id' => $insertId,
										'nobar' => isset($val[0])?$val[0]:'',
										'user_crt' => $this->session->userdata('user_id'),
										'date_crt' => date('Y-m-d'),
										'time_crt' => date('H:i:s'),
									);
									$this->model_subproduct->insert_data_article_size_colour($data2);

									$insert_row++;
								}

							}else{
								array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"UOM Jual tidak ditemukan"));
							}
						}else{
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"UOM Stock tidak ditemukan"));
						}
					}else{
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"UOM Beli tidak ditemukan"));
					}
				}
				else if($namafile=="article.csv"){
					$total_row++;
					$read = $this->model_article->read_data($val[0]);
					if($read->num_rows()==0){
						$data = array(
							'article_code' 		=> isset($val[0])?$val[0]:'',
							'article_name' 		=> isset($val[1])?$val[1]:'',
							'style' 			=> isset($val[2])?$val[2]:'',
							'bom_pcs' 			=> isset($val[3])?$val[3]:'',
							'foh_pcs' 			=> isset($val[4])?$val[4]:'',
							'ongkos_jahit_pcs' 	=> isset($val[5])?$val[5]:'',
							'operation_cost' 	=> isset($val[6])?$val[6]:'',
							'interest_cost' 	=> isset($val[7])?$val[7]:'',
							'crtby' 			=> $this->session->userdata('user_id'),
							'crtdt' 			=> date('Y-m-d H:i:s')
						);
//                        if($this->checkChar($val[0])){
						$this->model_article->insert_data($data);
						$result = 0;
						$msg = "OK";
						$insert_row++;
//                        }else{
//                            $result = 1;
//                            $msg = "Kode hanya boleh karakter huruf dan angka";
//                        }
					}else{
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"Data sudah ada"));
					}
				}
				else if($namafile=="article_colour.csv"){
					$total_row++;
					$read = $this->model_article->read_data_colour($val[1],$val[0]);
					if($read->num_rows()==0){
						$data = array(
							'art_colour_code' => isset($val[0])?$val[0]:'',
							'article_code' => isset($val[1])?$val[1]:'',
							'crtby' => $this->session->userdata('user_id'),
							'crtdt' => date('Y-m-d H:i:s')
						);
						$this->model_article->insert_data_colour($data);
						$result = 0;
						$msg = "OK";
						$insert_row++;
					}else{
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"Data sudah ada"));
					}
				}
				else if($namafile=="article_size.csv"){
					$total_row++;
					$read = $this->model_article->read_data_size($val[1],$val[0]);
					if($read->num_rows()==0){
						$data = array(
							'art_size_code' => isset($val[0])?$val[0]:'',
							'article_code' => isset($val[1])?$val[1]:'',
							'crtby' => $this->session->userdata('user_id'),
							'crtdt' => date('Y-m-d H:i:s')
						);
						$this->model_article->insert_data_size($data);
						$result = 0;
						$msg = "OK";
						$insert_row++;
					}else{
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"Data sudah ada"));
					}
				}
				else if($namafile=="produksi.csv"){
					$total_row++;
					//header
					if($nomor_header=="") {
						$cek = $this->model_delivery->read_data_header_by_field("keterangan",$val[3]);
						if($cek->num_rows()>0){
							$result = 1;
							$msg = "Keterangan Harus Unik";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"Keterangan Harus Unik"));
						}else{
							$nomor_header = $this->model_delivery->generate_auto_number_innerprefix(
								$this->formatDate('Y-m-d', $val[0])
								, $this->session->userdata('lokasi produksi')
								, $this->session->userdata('lokasi barang jadi'));

							$data = array(
								'docno' => $nomor_header,
								'doc_date' => $this->formatDate('Y-m-d', $val[0]),
								'from_store_code' => $this->session->userdata('kode store pusat'),
								'from_location_code' => $this->session->userdata('lokasi produksi'),
								'to_store_code' => $this->session->userdata('kode store pusat'),
								'to_location_code' => $this->session->userdata('lokasi barang jadi'),
								'do_type' => 'DO',
								'golongan_do' => 'prod2pst',
								'keterangan' => isset($val[3])?$val[3]:'',
								'status' => 'ON DELIVERY',
								'crtby' => $this->session->userdata('user_id'),
								'crtdt' => date('Y-m-d H:i:s')
							);

							if ($this->checkPeriod($this->session->userdata('lokasi produksi'), $val[0])) {
								$this->model_delivery->insert_data($data);

								$data = array(
									'docno' => $nomor_header,
									'nobar' => isset($val[1])?$val[1]:'',
									'qty' => isset($val[2])?$val[2]:'',
									'qty_rcv' => 0,
									'qty_rev' => 0,
									'status' => 'new',
									'keterangan' => isset($val[3])?$val[3]:'',
								);
								$this->model_delivery->insert_data_nobar($data);
								$insert_row++;

								$result = 0;
								$msg = "OK";
							} else {
								$nomor_header = "";
								$result = 1;
								$msg = "Transaksi tidak dalam periode berjalan";
								array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"Transaksi tidak dalam periode berjalan"));
							}
						}
					}else{
						$data = array(
							'docno' => $nomor_header,
							'nobar' => isset($val[1])?$val[1]:'',
							'qty' => isset($val[2])?$val[2]:'',
							'qty_rcv' => 0,
							'qty_rev' => 0,
							'status' => 'new',
							'keterangan' => isset($val[3])?$val[3]:'',
						);
						$this->model_delivery->insert_data_nobar($data);
						$insert_row++;
					}
				}
				else if($namafile=="produksi_import.csv"){
					$total_row++;
					//header
					if($nomor_header=="") {
						$cek = $this->model_delivery->read_data_header_by_field("keterangan",$val[3]);
						if($cek->num_rows()>0){
							$result = 1;
							$msg = "Keterangan Harus Unik";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"Keterangan harus unik"));
						}else{
							$nomor_header = $this->model_delivery->generate_auto_number_innerprefix_withI(
								$this->formatDate('Y-m-d', $val[0])
								, $this->session->userdata('lokasi produksi')
								, $this->session->userdata('lokasi barang jadi'));
							$data = array(
								'docno' => $nomor_header,
								'doc_date' => $this->formatDate('Y-m-d', $val[0]),
								'from_store_code' => $this->session->userdata('kode store pusat'),
								'from_location_code' => $this->session->userdata('lokasi produksi'),
								'to_store_code' => $this->session->userdata('kode store pusat'),
								'to_location_code' => $this->session->userdata('lokasi barang jadi'),
								'do_type' => 'DO',
								'golongan_do' => 'iprod2pst',
								'keterangan' => isset($val[3])?$val[3]:'',
								'status' => 'ON DELIVERY',
								'crtby' => $this->session->userdata('user_id'),
								'crtdt' => date('Y-m-d H:i:s')
							);

							if ($this->checkPeriod($this->session->userdata('lokasi produksi'), $val[0])) {
								$this->model_delivery->insert_data($data);

								$data = array(
									'docno' => $nomor_header,
									'nobar' => isset($val[1])?$val[1]:'',
									'qty' => isset($val[2])?$val[2]:'',
									'qty_rcv' => 0,
									'qty_rev' => 0,
									'status' => 'new',
									'keterangan' => isset($val[3])?$val[3]:'',
								);
								$this->model_delivery->insert_data_nobar($data);
								$insert_row++;

								$result = 0;
								$msg = "OK";
							} else {
								$nomor_header = "";
								$result = 1;
								$msg = "Transaksi tidak dalam periode berjalan";
								array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>"Transaksi tidak dalam periode berjalan"));
							}
						}
					}else{
						$data = array(
							'docno' => $nomor_header,
							'nobar' => isset($val[1])?$val[1]:'',
							'qty' => isset($val[2])?$val[2]:'',
							'qty_rcv' => 0,
							'qty_rev' => 0,
							'status' => 'new',
							'keterangan' => isset($val[3])?$val[3]:'',
						);
						$this->model_delivery->insert_data_nobar($data);
						$insert_row++;
					}
				}
				else if($namafile=="brand.csv"){
					$total_row++;
					$read = $this->model_brand->read_data($val[0]);
					if ($read->num_rows() > 0) {
						$result = 1;
						$msg = "Kode brand harus unik";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					}else {
						$data = array(
							'brand_code' => $val[0],
							'description' => isset($val[1])?$val[1]:'',
							'jenis_barang' => '',
							'crtby' => $this->session->userdata('user_id'),
							'crtdt' => date('Y-m-d H:i:s')
						);

						if($this->checkChar($val[0])){
							$this->model_brand->insert_data($data);
							$result = 0;
							$msg = "OK";
							$insert_row++;
						}else{
							$result = 1;
							$msg = "Kode hanya boleh karakter huruf dan angka";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
						}

					}
				}
				else if($namafile=="size.csv"){
					$total_row++;
					$read = $this->model_size->read_data($val[0]);
					if ($read->num_rows() > 0) {
						$result = 1;
						$msg="Kode harus unique";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					}else {
						$data = array(
							'size_code' => $val[0],
							'description' => isset($val[1])?$val[1]:'',
							'crtby' => $this->session->userdata('user_id'),
							'crtdt' => date('Y-m-d H:i:s')
						);

						if($this->checkChar($val[0])){
							$this->model_size->insert_data($data);
							$result = 0;
							$msg = "OK";
							$insert_row++;
						}else{
							$result = 1;
							$msg = "Kode hanya boleh karakter huruf dan angka";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
						}
					}
				}else if($namafile=="colour.csv"){
					$total_row++;
					$read = $this->model_colour->read_data($val[0]);
					if ($read->num_rows() > 0) {
						$result = 1;
						$msg="Kode Colour harus Unique";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					} else {
						$data = array(
							'colour_code' => $val[0],
							'description' => isset($val[1])?$val[1]:'',
							'crtby' => $this->session->userdata('user_id'),
							'crtdt' => date('Y-m-d H:i:s')
						);

						if($this->checkChar($val[0])){
							$this->model_colour->insert_data($data);
							$result = 0;
							$msg = "OK";
							$insert_row++;
						}else{
							$result = 1;
							$msg = "Kode hanya boleh karakter huruf dan angka";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
						}
					}
				}else if($namafile=="uom.csv"){
					$total_row++;
					$read = $this->model_productuom->read_data2($val[0]);
					if ($read->num_rows() > 0) {
						$result = 1;
						$msg="Kode sudah ada, harus unique";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					}else {
						$data = array(
							'uom_id' => $val[0],
							'description' => isset($val[1])?$val[1]:'',
							'crtby' => $this->session->userdata('user_id'),
							'crtdt' => date('Y-m-d H:i:s')
						);


						if($this->checkChar($val[0])){
							$id_no = $this->model_productuom->insert_data($data);
							$data2 = array(
								'uom_from' => $id_no,
								'uom_to' => $id_no,
								'convertion' => 1,
								'crtby' => $this->session->userdata('user_id'),
								'crtdt' => date('Y-m-d H:i:s')
							);

							$this->model_productuom->insert_data_convertion($data2);
							$result = 0;
							$msg = "OK";
							$insert_row++;
						}else{
							$result = 1;
							$msg = "Kode hanya boleh karakter huruf dan angka";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
						}
					}
				}else if($namafile=="uom_convertion.csv"){
					$total_row++;
					$uom_from = $this->model_productuom->read_data2($val[0]);
					if($uom_from->num_rows()>0){
						$uom_to = $this->model_productuom->read_data2($val[1]);
						if($uom_to->num_rows()>0){
							$from = $uom_from->row()->uom_code;
							$to = $uom_to->row()->uom_code;

							$read = $this->model_uom_conv->read_data($from,$to);
							if ($read->num_rows() > 0) {
								$result = 1;
								$msg="UOM From dan UOM To harus Unique";
								$dbg1 .= $from." -> ".$to." (".$val[0]." -> ".$val[1].")";
								array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
							} else {
								$data = array(
									'uom_from' => $from,
									'uom_to' => $to,
									'convertion' => isset($val[2])?$val[2]:1,
									'crtby' => $this->session->userdata('user_id'),
									'crtdt' => date('Y-m-d H:i:s')
								);

								$this->model_uom_conv->insert_data($data);
								$result = 0;
								$msg = "OK";
								$insert_row++;
								$dbg2 .= $from." -> ".$to." (".$val[0]." -> ".$val[1].")";
							}
						}else{
							$result = 1;
							$msg="Kode UOM To tidak ditemukan";
							$dbg1 .= "(".$val[0]." -> ".$val[1].")";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
						}
					}else{
						$result = 1;
						$msg="Kode UOM From tidak ditemukan";
						$dbg1 .= "(".$val[0]." -> ".$val[1].")";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					}
				}else if($namafile=="group.csv"){
					$total_row++;
					$read = $this->model_group->read_data($val[0]);
					if ($read->num_rows() > 0) {
						$result = 1;
						$msg="Kode Class harus Unique";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					} else {
						$data = array(
							'class_code' => $val[0],
							'description' => isset($val[1]) ? $val[1] : '',
							'jenis_barang' => '',
							'addcost' => '',
							'crtby' => $this->session->userdata('user_id'),
							'crtdt' => date('Y-m-d H:i:s')
						);

						if ($this->checkChar($val[0])) {
							$this->model_group->insert_data($data);
							$result = 0;
							$msg = "OK";
							$insert_row++;
						} else {
							$result = 1;
							$msg = "Kode hanya boleh karakter huruf dan angka";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
						}
					}
				}else if($namafile=="subgroup.csv"){
					$total_row++;
					$data = array(
						'class_code' => $val[0],
						'subclass_code' => $val[1],
						'description' => $val[2],
						'crtby' => $this->session->userdata('user_id'),
						'crtdt' => date('Y-m-d H:i:s')
					);

					$read = $this->model_group->read_data2($val[0],$val[1]);
					if ($read->num_rows() > 0) {
						$result = 1;
						$msg="Kode Class dan Kode Sub Class harus Unique";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					}else {
						if ($this->checkChar($val[1])) {
							$this->model_group->insert_data2($data);
							$result = 0;
							$msg = "OK";
							$insert_row++;
						} else {
							$result = 1;
							$msg = "Kode hanya boleh karakter huruf dan angka";
							array_push($respon_gagal, array("data" => $val, "baris" => $total_row, "why" => $msg));
						}
					}
				}else if($namafile=="faktur.csv"){
					$total_row++;
					$data = array(
						'periode' => date('Y'),
						'seqno' => $val[0],
						'refno' => '',
						'inuse' => '0',
						'crtby' => $this->session->userdata('user_id'),
						'crtdt' => date('Y-m-d H:i:s')
					);
					$read = $this->model_faktur->read_data(date('Y'), $val[0]);
					if($read->num_rows()>0){
						$result = 1;
						$msg="Sequence sudah ada.";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					}else {
						if ($this->model_faktur->insert_data($data)) {
							$result = 0;
							$msg = "OK";
							$insert_row++;
						}else{
							$result = 1;
							$msg="Gagal insert data.";
							array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
						}
					}
				}else if($namafile=="marketplace.csv"){
					$total_row++;
					$data = array(
						'no_resi' => $val[0],
						'nama_customer' => $val[1],
						'no_telepon' => $val[2],
						'alamat_kirim' => $val[3],
						'kota' => $val[4],
						'provinsi' => $val[5],
						'order_date' => $val[6],
						'crtby' => $this->session->userdata('user_id'),
						'crtdt' => date('Y-m-d H:i:s')
					);
					if($this->db->replace("resi_marketplace",$data)){
						$result = 0;
						$msg = "OK";
						$insert_row++;
					}else{
						$result = 1;
						$msg="Gagal insert data.";
						array_push($respon_gagal,array("data"=>$val, "baris"=>$total_row, "why"=>$msg));
					}
				}
			}
			array_push($respon,array("nama"=>$namafile,"insert"=>$insert_row,"total"=>$total_row));
			$i++;

			if(fclose($handle)){ $result = 0; $msg = "OK";
			} else { $result = 1; $msg = "Can't close file"; }
		}


		echo json_encode(array(
			"status" => $result, "isError" => ($result==1),
			"msg" => $msg, "message" => $msg,
			"debug1"=>$dbg1,"debug2"=>$dbg2,
			"response"=>$respon,
			"response_gagal"=>$respon_gagal
		));
	}
}
