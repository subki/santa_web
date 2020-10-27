<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment extends IO_Controller {

    function __construct(){

        parent::__construct();
        $this->load->model('Attachment_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    private function checkFile($path,$target_file,$imageFileType){
        $check = getimagesize($_FILES["userfile"]["tmp_name"]);

        // Check if image file is a actual image or fake image
        if(!$check) return "File is not an image";

        // Check if file already exists
        if (file_exists($target_file)) return "Sorry, file already exists.";

        // Check file size
        if ($_FILES["userfile"]["size"] > 500000)  return "Sorry, your file is too large. Maximum 1MB";

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            return "Sorry, only JPG, JPEG, PNG file are allowed.";
        }

        return "";
    }
    function save_data(){
        try {
            $input = $this->input->post();

            $path          = './assets/images/'.$input['path'];
            $target            = $path . basename($_FILES['userfile']['name']);
            $imageFileType = strtolower(pathinfo($target,PATHINFO_EXTENSION));

            $cek = $this->checkFile($path,$target,$imageFileType);

            if($cek==""){
                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target)) {
                    $data = array(
                        'docno' => $input['docno'],
                        'tabel' => $input['tabel'],
                        'path' => $input['path'],
                        'filename' => $_FILES['userfile']['name'],
                        'crtby' => $this->session->userdata('user_id'),
                        'crtdt' => date('Y-m-d H:i:s')
                    );
                    $this->model->insert_data($data);
                    $result = 0;
                    $msg = "OK";
                } else {
                    $result = 1;
                    $msg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $msg = $cek;
                $result = 1;
            }

        }catch (Exception $e){
            $result = 1;
            $msg=$e->getMessage();
        }
        echo json_encode(array(
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg
        ));
    }

    function read_data(){
        try {
            $input = $this->input->post();
            $read = $this->model->read_data($input['docno'],$input['tabel']);
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
            "status" => $result, "isError" => ($result==1),
            "msg" => $msg, "message" => $msg,
            "data" => $data
        ));
    }

}
