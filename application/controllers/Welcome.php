<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends IO_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Welcome_model', 'mod');
    }

	public function index()
	{

		$data['content'] = $this->load->view('welcome',null,TRUE);
		$data['title'] = 'Selamat Datang';

		$this->load->view('main',$data);

	}

	public function load_menu(){
        $data = array(
            "base" => base_url(),
            "site" => site_url(),
        );

        $users_id=$this->session->userdata('user_id');
        $userGroupId = $this->mod->getUserGroupId($users_id);
        $x =0;
//        var_dump($userGroupId);
//        die();
        foreach ($userGroupId as $s) {
            $app = $this->mod->getMenu('root', $s['users_group_id'], $users_id);
            $i = 0;
//            var_dump($s);
//            die('qoi');
            foreach ($app as $r1) {

                $flag =0;
//                var_dump($r1);
//                die();

                $idMenu = $r1['app_id'];
                if(isset($data['default'])) {
                    for ($y = 0; $y < count($data['default']['main_menu']); $y++) {
                        if ($data['default']['main_menu'][$y]['id'] == $idMenu) {
                            $flag = 1;
                            break;
                        }
                    }
                }

                if($flag==0) {

                    $data['default']['main_menu'][$i]['id'] = $idMenu;
                    $data['default']['main_menu'][$i]['name'] = $r1['app_name'];
                    $data['default']['main_menu'][$i]['url'] = $r1['url'];
                    $data['default']['main_menu'][$i]['icon'] = $r1['icon'];
                    $data['default']['main_menu'][$i]['allow_add'] =    $r1['tambah'];
                    $data['default']['main_menu'][$i]['allow_update'] = $r1['ubah'];
                    $data['default']['main_menu'][$i]['allow_delete'] = $r1['hapus'];
                    $data['default']['main_menu'][$i]['allow_print'] = $r1['cetak'];
                    $data['default']['main_menu'][$i]['allow_approve'] = $r1['approve'];
                    $data['default']['main_menu'][$i]['allow_approve2'] = $r1['approve2'];
                    $data['default']['main_menu'][$i]['allow_approve3'] = $r1['approve3'];
                    $data['default']['main_menu'][$i]['allow_approve4'] = $r1['approve4'];
                    $data['default']['main_menu'][$i]['allow_approve5'] = $r1['approve5'];
                    $data['default']['main_menu'][$i]['allow_download'] = $r1['download'];
                    $data['default']['main_menu'][$i]['allow_unposting'] = $r1['unpoting'];

                    $app2 = $this->mod->getMenu($idMenu, $s['users_group_id'], $users_id);
                    $i2 = 0;
                    foreach ($app2 as $r2) {
                        $data['default']['sub_menu'][$idMenu][$i2]['id'] = $r2['app_id'];
                        $data['default']['sub_menu'][$idMenu][$i2]['name'] = $r2['app_name'];
                        $data['default']['sub_menu'][$idMenu][$i2]['url'] = $r2['url'];
                        $data['default']['sub_menu'][$idMenu][$i2]['icon'] = $r2['icon'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_add'] = $r2['tambah'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_update'] = $r2['ubah'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_delete'] = $r2['hapus'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_print'] = $r2['cetak'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_approve'] = $r2['approve'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_approve2'] = $r2['approve2'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_approve3'] = $r2['approve3'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_approve4'] = $r2['approve4'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_approve5'] = $r2['approve5'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_download'] = $r2['download'];
                        $data['default']['sub_menu'][$idMenu][$i2]['allow_unposting'] = $r2['unposting'];

                        $app3 = $this->mod->getMenu($r2['app_id'], $s['users_group_id'], $users_id);
                        $i3 = 0;
                        foreach ($app3 as $r3) {
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['id'] = $r3['app_id'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['name'] = $r3['app_name'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['url'] = $r3['url'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['icon'] = $r3['icon'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_add'] = $r3['tambah'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_update'] = $r3['ubah'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_delete'] = $r3['hapus'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_print'] = $r3['cetak'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_approve'] = $r3['approve'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_approve2'] = $r3['approve2'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_approve3'] = $r3['approve3'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_approve4'] = $r3['approve4'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_approve5'] = $r3['approve5'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_download'] = $r3['download'];
                            $data['default']['subsub_menu'][$r2['app_id']][$i3]['allow_unposting'] = $r3['unposting'];
                            $i3++;
                        }
                        $i2++;
                    }
                    $i++;
                }
            }
            $x++;
        }
        echo json_encode(array(
                "status" => 1,
                "msg" => "OK",
                "data" =>$data)
        );
    }
}
