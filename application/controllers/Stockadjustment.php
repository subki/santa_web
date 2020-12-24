<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockadjustment extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Stockadj_model','model');
        $this->load->model('Stock_model','modelstock');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $data['title']      = 'Stock Adjustment';
        $data['docno']      = $this->model->getAutoNumber();
        $data['content']    = $this->load->view('vAdjustment',$data,TRUE);

        $this->load->view('main',$data);
    }

    function load_grid(){
        $f = $this->getParamGrid("","doc_date");
        $data = $this->model->get_list_data($f['page'],$f['rows'],'doc_date',$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }
    function load_gridstock($location, $prd){
        $f = $this->getParamGrid(" a.location_code='$location' and a.periode='$prd' ","a.nobar");
        $data = $this->model->get_list_datastock($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }

    function load_gridopname($location){
        $prd=date('Y-m');
        $f = $this->getParamGrid(" on_loc='$location' and trx_date LIKE '$prd%' ","trx_no");
        $data = $this->model->get_list_dataopname($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );

    }
    function load_grid_detail($docno){
        $f = $this->getParamGrid(" docno='$docno' ","nobar");
        $data = $this->model->get_list_data_detail($f['page'],$f['rows'],$f['sort'],$f['order'],$f['role'], $f['app']);

        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "total"=>(count($data)>0)?$data[0]->total:0,
                "data" =>$data)
        );
    }

    function save_data_header(){
        try {
            $input = $this->input->post();

             $docno = $this->model->getAutoNumber();
            $data = array(
                'docno' => $docno,
                'doc_date' =>$this->formatDate("Y-m-d",$input['doc_date']),
                'periode' =>$this->formatDate("Ym",$input['doc_date']),
                'outlet_code' => $input['location_code'],
                'remark' => $input['remark'],
                'status' => 'OPEN',
                'crtby' => $this->session->userdata('user_id'),
                'crtdt' => date('Y-m-d H:i:s')
            );
            $this->model->insert_data_header($data);
            $result = 0;
            $msg="OK";
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
        ));
    }

    function edit_data_header(){
        try {
            $input = $this->input->post();

            $read = $this->model->read_data_header($input['docno']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'remark' => $input['remark'],
                );

                $this->model->update_data_header($input['docno'], $data);
                $result = 0;
                $msg="OK";
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
        ));
    }

    function read_data_header($code){
        try {
            $read = $this->model->read_data_header($code);
            if ($read->num_rows() > 0) {
                $result = 0;
                $msg="OK";
                $data = $read->result()[0];
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
                $data = null;
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
            "data" => $data
        ));
    }

    function delete_data_header($code){
        try {
            $read = $this->model->read_data_header($code);
            if ($read->num_rows() > 0) {

                $read = $this->model->read_transactions($code);
                if ($read->num_rows() > 0) {
                    $result = 1;
                    $msg="Data tidak bisa dihapus, sudah ada transaksi";
                }else{
                    $this->model->delete_data_header($code);
                    $result = 0;
                    $msg="OK";
                }
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
        ));
    }

    function save_data_detail(){
        try {
            $input = $this->input->post();
            $data = array(
                'docno' => $input['docno_id'],
                'sku' => $input['skucode'],
                'soh' => $input['soh'],
                'adjust' => $input['adjust'],
                'keterangan' => $input['remark'],
            );

            $this->model->insert_data_detail($data);
            $result = 0;
            $msg="OK";
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
        ));
    }

    function save_data_detail2(){
        try {
            $input = $this->input->post(); 
            $data = array(
                'docno' => $input['docno_idopn'],
                'sku' => $input['skucodeopn'],
                'soh' => $input['sohopn'],
                'adjust' => $input['adjustopn'],
                'keterangan' => $input['remarkopn'],
                'so_number' => $input['remarkopn'],
            );

            $this->model->insert_data_detail($data);
            $result = 0;
            $msg="OK";
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
        ));
    }
    function edit_data_detail(){
        try {
            $input = $this->input->post();

            $read = $this->model->read_data_detail($input['id']);
            if ($read->num_rows() > 0) {
                $data = array(
                    'keterangan' => $input['keterangan'],
                );

                $this->model->update_data_detail($input['id'], $data);
                $result = 0;
                $msg="OK";
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
        ));
    }

    function read_data_detail($code){
        try {
            $read = $this->model->read_data_detail($code);
            if ($read->num_rows() > 0) {
                $result = 0;
                $msg="OK";
                $data = $read->result()[0];
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
                $data = null;
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
            "data" => $data
        ));
    }

    function delete_data_detail($code){
        try {
            $read = $this->model->read_data_detail($code);
            if ($read->num_rows() > 0) {
                $docno = $read->row()->docno;
                $read = $this->model->read_data_header($docno);
                if($read->num_rows()>0){
                    if($read->row()->status == "OPEN"){
//                        $read = $this->model->read_transactions_detail($code);
//                        if ($read->num_rows() > 0) {
//                            $result = 1;
//                            $msg="Data tidak bisa dihapus, sudah ada transaksi";
//                        }else{
                            $this->model->delete_data_detail($code);
                            $result = 0;
                            $msg="OK";
//                        }
                    }else{
                        $result = 1;
                        $msg="Transaksi sudah close, tidak bisa hapus";
                    }
                }else{
                    $result = 1;
                    $msg="Header tidak ditemukan";
                }
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
        ));
    }

    function adjustclose($code){
        try {
            $read = $this->model->read_data_header($code);
            if ($read->num_rows() > 0) {
                $header = $read->result()[0];
                $nobarqty = [];
                $f = $this->getParamGrid(" docno='$header->docno' ","sku");
                $data = $this->model->get_list_data_detail(1,999999999,$f['sort'],$f['order'],$f['role'], $f['app']);
                foreach ($data as $row){
                    $nobarqty[$row->sku] = $row->adjust;
                    $cek_stok = $this->model->cek_stok($row->sku, $header->outlet_code, $header->periode);
                    if ($cek_stok->num_rows() > 0) {
                        //update 
                    $this->updateStock($header->outlet_code
                        ,$header->periode
                        ,$nobarqty,'penyesuaian'
                        , array("docno"=>$header->docno,"tanggal"=>$header->doc_date,"remark"=>$header->remark));
                        $this->model->update_data_adjOpname($header->docno);
// var_dump($r);
// var_dump(array("docno"=>$header->docno,"tanggal"=>$header->doc_date,"remark"=>$header->remark));
// die();
                       // $this->model->update_adjustment($header->periode, $header->outlet_code, $row->sku, $row->adjust);
//                    }else{
//                        //insert
//                        $this->model->insert_adjustment($header->periode, $header->outlet_code, $row->sku, $row->adjust);
                    }
                }
                $datae = array(
                    'status' => 'CLOSED',
                );
                 //$this->model->update_data_header($header->docno, $datae);
                $result = 0;
                $msg="OK";
            } else {
                $result = 1;
                $msg="Kode tidak ditemukan";
                $data = null;
            }
        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result,
            "msg" => $msg,
            "data" => $data
        ));
    }

    function report_adjustment($docno){
        $header = $this->model->read_data_header($docno)->row();
        $f = $this->getParamGrid(" docno='$header->docno' ","nobar");
        $data = $this->model->get_list_data_detail(1,999999999,$f['sort'],$f['order'],$f['role'], $f['app']);

        $file = "Stock Adjustment ".$docno.".xls";

        $this->load->library('PHPExcel');
        $excel = new PHPExcel();
        $excel->getProperties()->setCreator($file);

        $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        $style_col_header = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );
        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $i=0;
        $no = 1;
        $numrow=7;
        foreach ($data as $row){
            if($i==0) {
                $excel->getActiveSheet()->setCellValue('B2', "STOCK ADJUSTMENT");
                $excel->getActiveSheet()->setCellValue('B3', "location");
                $excel->getActiveSheet()->setCellValue('B4', "Remark");
                $excel->getActiveSheet()->setCellValue('D3', ": ".$header->description);
                $excel->getActiveSheet()->setCellValue('D4', ": ".$header->remark);

                $excel->getActiveSheet()->mergeCells('B2:H2');
                $excel->getActiveSheet()->getStyle('B2:B4')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(14);
                $excel->getActiveSheet()->getStyle('B3:D4')->getFont()->setSize(12);
                $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel->getActiveSheet()->getStyle('B3:D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                $excel->setActiveSheetIndex($i)->setCellValue('B6', "No");
                $excel->setActiveSheetIndex($i)->setCellValue('C6', "Kode Barang");
                $excel->setActiveSheetIndex($i)->setCellValue('D6', "Nama Barang");
                $excel->setActiveSheetIndex($i)->setCellValue('E6', "SOH");
                $excel->setActiveSheetIndex($i)->setCellValue('F6', "Adjustment");
                $excel->setActiveSheetIndex($i)->setCellValue('G6', "Remark");

                $excel->getActiveSheet()->getStyle('B6:G6')->getFont()->setSize(11);
                $excel->getActiveSheet()->getStyle('B6:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel->getActiveSheet()->getStyle('B6:G6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $excel->getActiveSheet()->getStyle('B6:G6')->getAlignment()->setWrapText(true);

                $excel->getActiveSheet()->getStyle('B6:G6')->applyFromArray($style_col);
            }


            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $row->nobar);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $row->nmbar);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $row->soh);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $row->adjust);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $row->keterangan);

            $excel->getActiveSheet()->getStyle('B'.$numrow.':G'.$numrow)->applyFromArray($style_row);

            //format number
            $excel->getActiveSheet()->getStyle('B'.$numrow.':H'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $excel->getActiveSheet()->getStyle('F'.$numrow.':G'.$numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

            $no += 1;
            $numrow += 1;
            $i +=1;
        }
        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(4);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

        // Set judul file excel nya
//        $excel->getActiveSheet(0)->setTitle("Nilai");


        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$file.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $write->save('php://output');
    }
}
