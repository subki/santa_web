<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="html5,jquery,angular,vue,react,ui,widgets,ajax,ria,web framework,web development,easy,easyui,datagrid,treegrid,tree">
    <meta name="description" content="Theme Builder is focused on design and development of your own themes.">
    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/kube.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/main.css" type="text/css" />
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
        height:30px;
    }
    .sidemenu .tree-node-hover{
        background: #2c3b41;
        color: #fff;
    }
    .sidemenu .tree-node-selected{
        background: #2c3b41;
        color: #fff;
    }
    .sidemenu .accordion-header .panel-icon{
        font-size: 14px;
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
        background: #3c8dbc;
        color: #fff;
        line-height: 50px;
        height: 50px;;
    }
    .main-title{
        background: #367fa9;
        font-size: 20px;
        text-align: center;
        overflow: hidden;
    }
    .main-bar{
        background: #3c8dbc;
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

</style>
<link id="dlink" rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/ui-cupertino/easyui.css">
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
                    let mn = {};
                    mn.id = main.id;
                    mn.text = main.name;
//                        mn.iconCls = main.icon;
                    mn.state = ls===main.name?'open':'close';
                    mn.selected = ls===main.name;
                    mn.data = main;

                    let js = [];
                    if(sub_menu[main.id]) {
                        sub_menu[main.id].forEach(item=>{global_auth[item.id] = item;});
                        for (let x = 0; x < sub_menu[main.id].length; x++) {
                            let sub = sub_menu[main.id][x];
                            if (subsub_menu && subsub_menu[sub.id] !== undefined) {
                                let jsub = [];
                                subsub_menu[sub.id].forEach(item=>{global_auth[item.id] = item;});
                                for (let x2 = 0; x2 < subsub_menu[sub.id].length; x2++) {
                                    let sub2 = subsub_menu[sub.id][x2];
                                    jsub.push({
                                        id: sub2.id,
                                        text: sub2.name,
                                        iconCls: sub2.icon,
                                        data: sub2
                                    })
                                }
                                js.push({
                                    id: sub.id,
                                    text: sub.name,
                                    iconCls: sub.icon,
                                    data: sub,
                                    children: jsub
                                })
                            } else {
                                js.push({
                                    id: sub.id,
                                    text: sub.name,
                                    iconCls: sub.icon,
                                    data: sub
                                })
                            }
                        }
                        mn.children = js;
                    }
                    menuu.push(mn);
                }

                $('#sm').sidemenu({
                    data:menuu,
                    border:true,
                    onSelect:function (node) {
                        console.log(node);
                        localStorage['menu'] = node.data.name;
                        localStorage['submenuw'] = node.id;
                        if(node.data.url!=='#') window.location = base_url+node.data.url
                    }
                });
                // var panels = $('#submenu').accordion('panels');
                // $.map(panels, function(p){
                //     console.log(p)
                //     p.panel('collapse');
                // })
            }
        });
    }


</script>
<body style="height:100%;min-height:100%">
<div class="f-column" style="height:100%">
    <div class="main-header f-row">
        <div class="f-row f-full">
            <div class="main-title f-animate f-row" style="width:200px">
                <img class="app-logo" src="<?php echo base_url(); ?>assets/images/santa.png">
                <span>Santa Web</span>
            </div>
            <div class="main-bar f-full">
                <span class="main-toggle fa fa-bars" onclick="toggle()"></span>
            </div>
        </div>
    </div>
    <div class="f-row f-full">
        <div class="sidebar-body f-animate panel-noscroll" style="width:200px">
            <!--            <div class="sidebar-user">-->
            <!--                User Panel-->
            <!--            </div>-->
            <div id="sm" class="easyui-sidemenu" data-options="border:true"></div>
        </div>
        <div class="main-body f-full">
            <div id="main-layout" class="easyui-layout" style="width: 100%; height: 600px;">
                <div region="center" title=" " style="padding:5px;" id="contentdata">
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