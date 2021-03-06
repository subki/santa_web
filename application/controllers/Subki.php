<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Subki extends IO_Controller {

	function __construct(){

		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	function index(){
		$data['title']      = 'Cabang';
		$data['content']    = $this->load->view('vTest',$data,TRUE);

		$this->load->view('main',$data);
	}

	function print_direct(){
		$this->load->library('escpos');
		$profile = Escpos\CapabilityProfile::load("TM-U220");
		$connector = new Escpos\PrintConnectors\WindowsPrintConnector("EPSON TM-U220 Receipt");
//        $connector = new Escpos\PrintConnectors\FilePrintConnector("php://stdout");
		$printer = new Escpos\Printer($connector);

		function buatBaris4Kolom($kolom1, $kolom2, $kolom3, $kolom4) {
			// Mengatur lebar setiap kolom (dalam satuan karakter)
			$lebar_kolom_1 = 12;
			$lebar_kolom_2 = 8;
			$lebar_kolom_3 = 8;
			$lebar_kolom_4 = 9;

			// Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
			$kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
			$kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
			$kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
			$kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

			// Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
			$kolom1Array = explode("\n", $kolom1);
			$kolom2Array = explode("\n", $kolom2);
			$kolom3Array = explode("\n", $kolom3);
			$kolom4Array = explode("\n", $kolom4);

			// Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
			$jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

			// Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
			$hasilBaris = array();

			// Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
			for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

				// memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
				$hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
				$hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

				// memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
				$hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
				$hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

				// Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
				$hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
			}

			// Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
			return implode($hasilBaris, "\n") . "\n";
		}

		// Membuat judul
		$printer->initialize();
		$printer->setLineSpacing(25);
		$printer->selectPrintMode(Escpos\Printer::MODE_DOUBLE_HEIGHT); // Setting teks menjadi lebih besar
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
		$printer->text("Nama Toko\n");
		$printer->text("\n");

		// Data transaksi
		$printer->initialize();
		$printer->setLineSpacing(25);
		$printer->text("Kasir : Badar Wildanie\n");
		$printer->text("Waktu : 13-10-2019 19:23:22\n");

		// Membuat tabel
		$printer->initialize(); // Reset bentuk/jenis teks
		$printer->setLineSpacing(25);
		$printer->setFont(Escpos\Printer::FONT_B);
		$printer->text("----------------------------------------\n");
		$printer->text(buatBaris4Kolom("Barang", "qty", "Harga", "Subtotal"));
		$printer->text("----------------------------------------\n");
		$printer->text(buatBaris4Kolom("Makaroni 250gr", "2pcs", "15.000", "30.000"));
		$printer->text(buatBaris4Kolom("Telur", "2pcs", "5.000", "10.000"));
		$printer->text(buatBaris4Kolom("Tepung terigu", "1pcs", "8.200", "16.400"));
		$printer->text("----------------------------------------\n");
		$printer->text(buatBaris4Kolom('', '', "Total", "56.400"));
		$printer->text("\n");

//         Pesan penutup
		$printer->initialize();
		$printer->setLineSpacing(25);
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		$printer->text("Terima kasih telah berbelanja\n");
		$printer->text("http://badar-blog.blogspot.com\n");
		var_dump(FCPATH. 'assets\barcode\200918000001.jpg');
//        die;
//        $printer->initialize();
//        $tux = Escpos\EscposImage::load("resources/barcodes.png", false);
//        $printer -> bitImage($tux);
//        $printer -> text("Regular Tux (bit image).\n");
//        $printer -> feed();
//
//        $printer -> bitImage($tux, Escpos\Printer::IMG_DOUBLE_WIDTH);
//        $printer -> text("Wide Tux (bit image).\n");
//        $printer -> feed();
//
//        $printer -> bitImage($tux, Escpos\Printer::IMG_DOUBLE_HEIGHT);
//        $printer -> text("Tall Tux (bit image).\n");
//        $printer -> feed();
//
//        $printer -> bitImage($tux, Escpos\Printer::IMG_DOUBLE_WIDTH | Escpos\Printer::IMG_DOUBLE_HEIGHT);
//        $printer -> text("Large Tux in correct proportion (bit image).\n");

//        $printer -> cut();
//        $printer -> pulse();
		$printer->close();
	}

	public function bulk_change_collation(){
		$this->db->query('USE information_schema');
		$database = $this->db->query('SELECT CONCAT("ALTER DATABASE ",table_schema," CHARACTER SET = utf8 COLLATE = utf8_swedish_ci;") AS _sql 
FROM TABLES WHERE table_schema LIKE "u845881379_santa" GROUP BY table_schema;')->row()->_sql;
		$tabel = $this->db->query('SELECT CONCAT("ALTER TABLE ",table_schema,".",table_name," CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci, ENGINE=INNODB;") AS _sql  
FROM TABLES WHERE table_schema LIKE "u845881379_santa" GROUP BY table_schema, table_name;')->result();
		$field1 = $this->db->query('SELECT CONCAT("ALTER TABLE ",table_schema,".",table_name, " CHANGE `",column_name,"` `",column_name,"` ",data_type,"(",character_maximum_length,") CHARACTER SET utf8 COLLATE utf8_swedish_ci",IF(is_nullable="YES"," NULL"," NOT NULL"),";") AS _sql 
FROM COLUMNS WHERE table_schema LIKE "u845881379_santa" AND data_type IN (\'varchar\',\'char\');')->result();
		$field2 = $this->db->query('SELECT CONCAT("ALTER TABLE ",table_schema,".",table_name, " CHANGE ",column_name," ",column_name," ",data_type," CHARACTER SET utf8 COLLATE utf8_swedish_ci",IF(is_nullable="YES"," NULL"," NOT NULL"),";") AS _sql 
FROM COLUMNS WHERE table_schema LIKE "u845881379_santa" AND data_type IN (\'text\',\'tinytext\',\'mediumtext\',\'longtext\');')->result();
		$field3 = $this->db->query('SELECT CONCAT("ALTER TABLE ",table_schema,".",table_name, " CHANGE `",column_name,"` `",column_name,"` ",column_type," CHARACTER SET utf8 COLLATE utf8_swedish_ci",IF(is_nullable="YES"," NULL"," NOT NULL"),";") AS _sql 
FROM COLUMNS WHERE table_schema LIKE "u845881379_santa" AND data_type IN (\'enum\');')->result();
		$this->db->reset_query();
		$this->db->query($database);
		$merge = array_merge($tabel,$field1,$field2, $field3);
//		pre($merge);
		foreach ($tabel as $row){
//			$this->db->query($row->_sql);
			echo $row->_sql."<br />";
		}
		foreach ($merge as $row){
//			$this->db->query($row->_sql);
			echo $row->_sql."<br />";
		}
//		var_dump($database);
//		var_dump($tabel);
//		var_dump($field1);
//		pre(array_column($tabel,"_sql"));
//		$res  = $this->db->query('show tables')->result();
//		foreach ($res as $v){
//			$sql = "ALTER TABLE $v->Tables_in_u845881379_santa CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci;";
//			echo $sql."<br />";
//			$this->db->query($sql);
//		}
		echo "done";
	}

	public function compare_db_server($dbgroup1,$dbgroup2){
		$group1=$this->load->database($dbgroup1,true);
		$group2=$this->load->database($dbgroup2,true);

		$dbname1=$group1->database;
		$dbname2=$group2->database;

		$showtables1=$group1->query("SHOW TABLES")->result();
		$showtables2=$group2->query("SHOW TABLES")->result();

		$result_tbls=[];
		$result_cols=[];


		$col_show1='Tables_in_'.$dbname1;
		$col_show2='Tables_in_'.$dbname2;

		$tables1=[];
		$tables2=[];
		foreach($showtables1 as $tblname_row){
			$tblname=$tblname_row->$col_show1;
			$cols1=$group1->query("DESCRIBE `".$tblname."`")->result();
			$tables1[$tblname]=[];
			foreach($cols1 as $col_row){
				$tables1[$tblname][]=$col_row;
			}
		}
		foreach($showtables2 as $tblname_row){
			$tblname=$tblname_row->$col_show2;
			$cols2=$group2->query("DESCRIBE `".$tblname."`")->result();
			$tables2[$tblname]=[];
			foreach($cols2 as $col_row){
				$tables2[$tblname][]=$col_row;
			}
		}
		// echo "<pre>";var_dump($tables1);echo "</pre>";
		// echo "<pre>";var_dump($tables2);echo "</pre>";
		$sql_for_group1='';
		$sql_for_group2='';
		$create_table_colname='Create Table';
		foreach($tables1 as $tblname=>$cols){
			if(substr($tblname,0,1)=="_")continue;
			if(!isset($tables2[$tblname])){
				$result_tbls[]="<b>".$tblname."</b> exist in ".$dbgroup1." but not in ".$dbgroup2;
				$showcreatetable=$group1->query("SHOW CREATE TABLE `".$tblname."`")->result();
				$sql_for_group2.=$showcreatetable[0]->$create_table_colname.";<br>";
			}
			else{
				foreach($cols as $col){
					$colfound=false;
					foreach($tables2[$tblname] as $cols_compare){
						if($col->Field==$cols_compare->Field){
							$colfound=true;
							break;
						}
					}
					if(!$colfound){
						$altertable="ALTER TABLE ".$tblname." ADD ".$col->Field." ".$col->Type." DEFAULT ".(($col->Default!=null)?"'".$col->Default."'":'NULL')." ".(($col->Extra==null)?'NULL':$col->Extra).";";
						$sql_for_group2.=$altertable."<br>";
						$result_cols[]="col:<b>".$col->Field."</b> in tbl:<b>".$tblname."</b> exist in ".$dbgroup1." but not in ".$dbgroup2." || <span style='color:blue;'>".$altertable."</span>";
					}
				}
			}
		}
		foreach($tables2 as $tblname=>$cols){
			if(substr($tblname,0,1)=="_")continue;
			if(!isset($tables1[$tblname])){
				$result_tbls[]="<b>".$tblname."</b> exist in ".$dbgroup2." but not in ".$dbgroup1;
				$showcreatetable=$group2->query("SHOW CREATE TABLE `".$tblname."`")->result();
				$sql_for_group1.=$showcreatetable[0]->$create_table_colname.";<br>";
			}
			else{
				foreach($cols as $col){
					$colfound=false;
					foreach($tables1[$tblname] as $cols_compare){
						if($col->Field==$cols_compare->Field){
							$colfound=true;
							break;
						}
					}
					if(!$colfound){
						$altertable="ALTER TABLE ".$tblname." ADD ".$col->Field." ".$col->Type." DEFAULT ".(($col->Default!=null)?"'".$col->Default."'":'NULL')." ".(($col->Extra==null)?'NULL':$col->Extra).";";
						$sql_for_group1.=$altertable."<br>";
						$result_cols[]="col:<b>".$col->Field."</b> in tbl:<b>".$tblname."</b> exist in ".$dbgroup2." but not in ".$dbgroup1." || <span style='color:blue;'>".$altertable."</span>";
					}
				}
			}
		}
		echo "<b>Comparing DB Group: ".$dbgroup1.".".$dbname1." WITH ".$dbgroup2.".".$dbname2."</b><br><br>";
		if(!empty($result_tbls)){
			echo "<b>DIFFERENCE IN TABLES:</b><br>";

			foreach($result_tbls as $result){
				echo "\t- ".$result."<br>";
			}
		}
		if(!empty($result_cols)){
			echo "<b>DIFFERENCE IN COLUMNS:</b><br>";

			foreach($result_cols as $result){
				echo "\t- ".$result."<br>";
			}
		}
		echo "SQL FOR ".$dbgroup1.".".$dbname1." : <div style='width:100%;height:auto;background-color:lightgrey;'>".$sql_for_group1."</div>";
		echo "SQL FOR ".$dbgroup2.".".$dbname2." : <div style='width:100%;height:auto;background-color:lightgrey;'>".$sql_for_group2."</div>";
	}

	public function menu_finance(){
//		$menu_lokal = $this->db->like("app_id","M10")->get("app")->result();
//		$dbon=$this->load->database('defaultx',true);
//		$dbon->trans_start();
//		foreach ($menu_lokal as $row){
//			$dbon->replace("app",$row);
//		}
//		if ($dbon->trans_status() === FALSE){
//			echo "Error";
//		}else{
//			$dbon->trans_complete();
//			echo  "Done";
//		}
		$dbon=$this->load->database('defaultx',true);
		$menu = $dbon->like("app_id","M10")->get("app")->result();
		$this->db->trans_start();
		foreach ($menu as $row){
			$this->db->replace("app",$row);
		}
		if ($this->db->trans_status() === FALSE){
			echo "Error";
		}else{
			$this->db->trans_complete();
			echo  "Done";
		}
	}

	public function sync_menu_lokal_ke_hosting(){
		$dbon=$this->load->database('defaultx',true);
		$dbon->trans_start();

		$menu_lokal = $this->db->get("app")->result();
		foreach ($menu_lokal as $row){
			$dbon->replace("app",$row);
		}

		$menu_lokal = $this->db->get("users_group")->result();
		foreach ($menu_lokal as $row){
			$dbon->replace("users_group",$row);
		}

		$menu_lokal = $this->db->get("users_group_detail")->result();
		foreach ($menu_lokal as $row){
			$dbon->replace("users_group_detail",$row);
		}

		$menu_lokal = $this->db->get("users_group_detail_user")->result();
		foreach ($menu_lokal as $row){
			$dbon->replace("users_group_detail_user",$row);
		}

		if ($dbon->trans_status() === FALSE){
			echo "Error";
		}else{
			$dbon->trans_complete();
			echo  "Done";
		}
	}
	public function sync_menu_hosting_ke_lokal(){
		$dbon=$this->load->database('defaultx',true);
		$this->db->trans_start();

		$menu = $dbon->get("app")->result();
		foreach ($menu as $row){
			$this->db->replace("app",$row);
		}

		$menu = $dbon->get("users_group")->result();
		foreach ($menu as $row){
			$this->db->replace("users_group",$row);
		}

		$menu = $dbon->get("users_group_detail")->result();
		foreach ($menu as $row){
			$this->db->replace("users_group_detail",$row);
		}

		$menu = $dbon->get("users_group_detail_user")->result();
		foreach ($menu as $row){
			$this->db->replace("users_group_detail_user",$row);
		}

		if ($this->db->trans_status() === FALSE){
			echo "Error";
		}else{
			$this->db->trans_complete();
			echo  "Done";
		}
	}

	public function test($p){
		echo date("Y-m-d",strtotime("+0 month",strtotime($p."25")));
	}

	public function calendar(){
		$data['data'] = $this->db->where('end_date>=',date('Y-m-d', strtotime('2020-12-07')))
			->where("parent >","0")
			->order_by('end_date asc')
			->get("timeline")->result();
		$this->load->view("calendar", $data);
	}


}
