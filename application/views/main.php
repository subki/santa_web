<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="html5,jquery,angular,vue,react,ui,widgets,ajax,ria,web framework,web development,easy,easyui,datagrid,treegrid,tree">
    <meta name="description" content="Theme Builder is focused on design and development of your own themes.">
    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/kube.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/kube.css" type="text/css" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url() ?>assets/images/favicon.png" />
    <script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.min.js"></script>


</head>


<style>
    @import 'https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

    *{
        box-sizing: border-box;
    }
    .f-block{
        display: block;
        position: relative;
    }
    .f-row{
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flexbox;
        display: flex;
        position: relative;
    }
    .f-column{
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-direction: normal;
        -webkit-box-orient: vertical;
        -webkit-flex-direction: column;
        -moz-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
        position: relative;
    }
    .f-full{
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
    }
    .f-animate{
        transition: all .3s;
    }

    .sidemenu .accordion .panel-title{
        color: #b8c7ce;
    }
    .sidemenu .accordion .accordion-header{
        background: #222d32;
        color: #b8c7ce;
    }
    .sidemenu .accordion .accordion-body{
        background: #2c3b41;
        color: #8aa4af;
    }
    .sidemenu .accordion .accordion-header-selected{
        background: #1e282c;
    }
    .sidemenu .accordion .accordion-collapse{
        background: transparent;
    }
    .sidemenu .tree-node{
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;

        -ms-flex-align: center;
        -webkit-align-items: center;
        -webkit-box-align: center;

        align-items: center;
        height:20px;
        background: #2c3b41;
    }
    .sidemenu .tree-node-hover{
        background: #2c3b41;
        color: #fff;
    }
    .sidemenu .tree-node-selected{
        background: #2c3b41;
        color: #fff;
    }
    .sidemenu .accordion-header .panel-header{
        width: 90%;
    }
    .sidemenu .accordion-header .panel-icon{
        font-size: 12px;
        margin-right: 15px;
    }
    .sidemenu .accordion-header .panel-tool{
        display: none;
    }
    .sidemenu .accordion-header::after,
    .sidemenu .tree-node-nonleaf::after{
        display: inline-block;
        vertical-align: center;
        border-style: solid;
        transform:rotate(45deg);
        width: 4px;
        height: 4px;
        content: '';
        position: absolute;
        right: 10px;
        top: 50%;
        margin-top: -3px;
        border-width: 0 1px 1px 0;
    }
    .sidemenu .accordion-header-selected::after{
        transform:rotate(-135deg);
    }
    .sidemenu .tree-node-nonleaf::after{
        transform:rotate(-135deg);
    }
    .sidemenu .tree-node-nonleaf-collapsed::after{
        transform:rotate(45deg);
    }
    .sidemenu-collapsed .accordion-header::after{
        display: none;
    }
    .sidemenu-tooltip .accordion{
        border-color: #1e282c;
    }
    html,body{
        margin: 0;
        padding: 0;
    }
    .app-logo{
        width: 24px;
        height: 24px;
        color: #fff;
        margin: 13px 10px;
    }
    .main-header{
        background: #dd4b39;
        color: #fff;
        line-height: 50px;
        height: 50px;;
    }
    .main-title{
        background: #d73925;
        font-size: 20px;
        text-align: center;
        overflow: hidden;
    }
    .main-bar{
        background: #dd4b39;
    }
    .main-toggle{
        position: relative;
        display: inline-block;
        width: 16px;
        height: 16px;
        cursor: pointer;
        color: #fff;
        margin: 0 10px;
    }
    .main-logout{
        position: relative;
        display: inline-block;
        width: 24px;
        height: 24px;
        cursor: pointer;
        color: #fff;
      margin-right:10px;
    }
    .main-body{
        background: #ecf0f5;
        min-height: 400px;
    }
    .sidebar-body{
        background: #222d32;
    }
    .sidebar-user{
        color: #fff;
        padding: 20px;
        line-height: 20px;
    }
    .table_log {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }
    .td_log {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    .th_log {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    .th_log {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    .tr_log:nth-child(even) {
      background-color: #dddddd;
    }

</style>
<link id="dlink" rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/ui-cupertino/easyui.css">
<link id="dlink" rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/ui-cupertino/linkbutton.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/color.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/demo.css">

<script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-3.0.0.js"></script>-->
<!--<script src="https://code.jquery.com/jquery-migrate-3.3.1.js"></script>-->
<script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.easyui.min.js"></script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/jquery.etree.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/jquery.edatagrid.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/datagrid-filter.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/datagrid-detailview.js"></script>

<script type="text/javascript">
    var global_auth = {};
    var fullname="<?php echo $this->session->userdata('fullname');?>";
    global_auth.appId ="<?php echo $this->session->userdata('app');?>";
    var base_url="<?php echo base_url();?>";

    $(document).ready(function () {
        generate_submenu();
    });
    function toggle(){
        var opts = $('#sm').sidemenu('options');
        $('#sm').sidemenu(opts.collapsed ? 'expand' : 'collapse');
        opts = $('#sm').sidemenu('options');
        $('#sm').sidemenu('resize', {
            width: opts.collapsed ? 50 : 200
        });
        $('.main-title,.sidebar-body').css('width', opts.collapsed?50:200+'px');
        $('.main-title span,.sidebar-body .sidebar-user').css('display', opts.collapsed?'none':'');
    }

    function generate_submenu() {
        $.ajax({
            url : base_url+"welcome/load_menu",
            type: "POST",
            dataType: "JSON",
            success: function(data){
                console.log(data)
                let main_menu = data.data.default.main_menu;
                let sub_menu = data.data.default.sub_menu;
                let subsub_menu = data.data.default.subsub_menu;
                let menuu = [];

                main_menu.forEach(item=>{global_auth[item.id] = item;});

                for(let i=0; i<main_menu.length; i++){
                    let main = main_menu[i];
                    let ls = localStorage['menu'] || 'subki';
                    let ms = localStorage['menu_selected'];
//                    console.log("local", ms)
                    let mn = {};
                    mn.id = main.id;
                    mn.text = main.name;
                    mn.iconCls = main.icon;
//                    mn.state = ls===main.name?'open':'close';
                    if(main.id===ms) {
                        mn.selected = true;
                    }
                    mn.data = main;

                    let js = [];
                    if(sub_menu[main.id]) {
                        sub_menu[main.id].forEach(item=>{global_auth[item.id] = item;});
                        for (let x = 0; x < sub_menu[main.id].length; x++) {
                            let sub = sub_menu[main.id][x];

                            var mn_sub = {
                                id: sub.id,
                                text: sub.name,
                                iconCls: sub.icon,
                                data:sub
                            }
//                            console.log("sub main", sub.id)
                            if(sub.id===ms) {
//                                console.log("sub main", "masuk")
                                mn.state = 'open';
                                mn_sub.selected = true;
                            }
                            if (subsub_menu && subsub_menu[sub.id] !== undefined) {
                                let jsub = [];
                                subsub_menu[sub.id].forEach(item=>{global_auth[item.id] = item;});
                                for (let x2 = 0; x2 < subsub_menu[sub.id].length; x2++) {
                                    let sub2 = subsub_menu[sub.id][x2];
                                    var mn_sub_sub = {
                                        id: sub2.id,
                                        text: sub2.name,
                                        iconCls: sub2.icon,
                                        data: sub2
                                    };
                                    if(sub2.id===ms) {
                                        mn.state = 'open';
                                        mn_sub.state = 'open';
                                        mn_sub_sub.selected = true;
                                    }
                                    jsub.push(mn_sub_sub)
                                }
                                mn_sub.children = jsub;

                                js.push(mn_sub)
                            } else {
                                js.push(mn_sub)
                            }
                        }
                        mn.children = js;
                    }
                    menuu.push(mn);
                }

                $('#sm').sidemenu({
                    data:menuu,
                    width:250,
                    selected:{text:localStorage['menu']},
                    onSelect:function (node) {
                        console.log("onSelect")
                        console.log(node);
                        localStorage['menu'] = node.data.name;
                        localStorage['menu_selected'] = node.id;
                        localStorage['submenuw'] = node.id;
                        if(node.data.url!=='#') window.location = base_url+node.data.url
                    },
                    onLoadSuccess:function () {
                        console.log("onLoadSuccess")
                        let ls1 = localStorage['submenuw'] || 'subsubki';
                        var node = $(this).sidemenu('find', ls1);
                        if (node){
                            $(this).sidemenu('select', node.target);
                        }
                    }
                });
//                console.log("onLoadSuccess")
//                let ls1 = localStorage['menu'];
//                var node = $("#sm").sidemenu('find', ls1);
//                if (node){
//                    $('#sm').sidemenu('select', node.target);
//                }
            }
        });
    }


</script>
<body style="height:100%;min-height:100%">
<div class="f-column" style="height:100%">
    <div class="main-header f-row">
        <div class="f-row f-full">
            <div class="main-title f-animate f-row" style="width:250px">
                <img class="app-logo" src="<?php echo base_url(); ?>assets/images/santa.png">
                <span>Santa Web</span>
            </div>
            <div class="main-bar f-full">
                <span class="main-toggle fa fa-bars" onclick="toggle()"></span>
              <?php echo "Welcome, ".$this->session->userdata("fullname")." to ".$this->session->userdata("store_name")."-".$this->session->userdata("location_code") ?>
            </div>
            <div class="main-bar">
              <a href="<?php echo base_url(); ?>auth/logout_act"><span title="Logout" class="main-logout fa fa-key"></span></a>
            </div>
        </div>
    </div>
    <div class="f-row f-full">
        <div class="sidebar-body f-animate panel-noscroll" style="width:250px">
            <div id="sm"></div>
        </div>
        <div class="main-body f-full">
            <div id="main-layout" class="easyui-layout" fit="true" style="width: 100%; height: 720px;">
                <div region="center" title="<?php echo isset($title_main) ? $title_main : ' ' ?>" style="padding:5px;" id="contentdata">
                    <?php if(isset($content)) echo $content; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<div id="footer">
    <!--        <div class="units-row text-centered">Copyright © 2019 www.flymannathalie.com</div>-->
</div>
</html>