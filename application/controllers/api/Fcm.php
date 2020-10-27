<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Fcm extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');

        $this->AUTH_KEY="AIzaSyDn24Gw6MzBlWzcyJIhRagzq3A2xtN709A";
        $this->URL_API="https://fcm.googleapis.com/fcm/send";
        $this->SERVER_KEY = "AAAATz6kz4U:APA91bFAORTAspGiYlHlTPYk94YRKUKVawNpWxE7WFNim-LvliGh5Pfv8XdVBeCAL5XjyWwSXJyW2LqJXCihsr6wGR0TBBij53IN-C_x4HRelZ6HYPV9_PVg-zLxygEzG0H8jqZ3u5sa";
    }

    function index(){
        echo json_encode(array(
                "status" => 1,
                "msg" => "403 Forbidden"
            )
        );
    }

    function push_topic(){

//        date_default_timezone_set('Asia/Jakarta');
//        header('Content-Type: application/json');
        $this->input->raw_input_stream;
        $input_data = json_decode($this->input->raw_input_stream, true);
//        var_dump($input_data);
//        die();

        $fields = array (
            'to'=>'/topics/news-counter',
            'priority'=>'high',
            "mutable_content"=>true,
            'data' => array (
                "title" => $input_data['title'],
                "message" => $input_data['message'],
                "jenis_tr" => $input_data['jenis_tr'],
                "data" => $input_data['data'],
            )
        );
        $headers = array (
            'Authorization: key=' . $this->AUTH_KEY,
            'Content-Type: application/json'
        );

//        var_dump($fields);
//        die();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->URL_API);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        echo json_encode(array(
            "result" => 0,
            "msg" => $result
        ));

    }

}
