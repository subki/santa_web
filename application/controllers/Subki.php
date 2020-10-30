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
//        $printer->initialize();
//        $printer->setLineSpacing(25);
//        $printer->selectPrintMode(Escpos\Printer::MODE_DOUBLE_HEIGHT); // Setting teks menjadi lebih besar
//        $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
//        $printer->text("Nama Toko\n");
//        $printer->text("\n");

        // Data transaksi
//        $printer->initialize();
//        $printer->setLineSpacing(25);
//        $printer->text("Kasir : Badar Wildanie\n");
//        $printer->text("Waktu : 13-10-2019 19:23:22\n");

        // Membuat tabel
//        $printer->initialize(); // Reset bentuk/jenis teks
//        $printer->setLineSpacing(25);
//        $printer->setFont(Escpos\Printer::FONT_B);
//        $printer->text("----------------------------------------\n");
//        $printer->text(buatBaris4Kolom("Barang", "qty", "Harga", "Subtotal"));
//        $printer->text("----------------------------------------\n");
//        $printer->text(buatBaris4Kolom("Makaroni 250gr", "2pcs", "15.000", "30.000"));
//        $printer->text(buatBaris4Kolom("Telur", "2pcs", "5.000", "10.000"));
//        $printer->text(buatBaris4Kolom("Tepung terigu", "1pcs", "8.200", "16.400"));
//        $printer->text("----------------------------------------\n");
//        $printer->text(buatBaris4Kolom('', '', "Total", "56.400"));
//        $printer->text("\n");

//         Pesan penutup
//        $printer->initialize();
//        $printer->setLineSpacing(25);
//        $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
//        $printer->text("Terima kasih telah berbelanja\n");
//        $printer->text("http://badar-blog.blogspot.com\n");
//        var_dump(FCPATH. 'assets\barcode\200918000001.jpg');
//        die;
        $printer->initialize();
        $tux = Escpos\EscposImage::load("resources/barcodes.png", false);
        $printer -> bitImage($tux);
        $printer -> text("Regular Tux (bit image).\n");
        $printer -> feed();

        $printer -> bitImage($tux, Escpos\Printer::IMG_DOUBLE_WIDTH);
        $printer -> text("Wide Tux (bit image).\n");
        $printer -> feed();

        $printer -> bitImage($tux, Escpos\Printer::IMG_DOUBLE_HEIGHT);
        $printer -> text("Tall Tux (bit image).\n");
        $printer -> feed();

        $printer -> bitImage($tux, Escpos\Printer::IMG_DOUBLE_WIDTH | Escpos\Printer::IMG_DOUBLE_HEIGHT);
        $printer -> text("Large Tux in correct proportion (bit image).\n");

//        $printer -> cut();
//        $printer -> pulse();
        $printer->close();
    }


}
