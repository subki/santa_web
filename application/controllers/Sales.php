<?php
/**
 * Created by PhpStorm.
 * User: user_1
 * Date: 11/05/19
 * Time: 11.27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends IO_Controller {

    function __construct(){

        parent::__construct();
//        $this->load->model('Eom_model','model');
        $this->load->library('form_validation');
        $this->load->helper('file');
    }

    function index(){
        $this->load->view('vSales');
    }
}
