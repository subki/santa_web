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
<body>


<script type="text/javascript">
    var global_auth = {};
    var fullname="<?php echo $this->session->userdata('fullname');?>";
    global_auth.appId ="<?php echo $this->session->userdata('app');?>";
</script>
<div id="header" class="group wrap header">
    <div class="content">
        <div class="navigation-toggle" data-tools="navigation-toggle" data-target="#navbar-1">
            <span>Santa Great Industry</span>
        </div>
        <div id="elogo" class="navbar navbar-left">
            <ul>
                <li>
                    <a href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>assets/images/santa.png" style="width: 40px; height: 40px;" alt="Santa Great Industry"/>  Santa Great Industry</a>
                </li>
            </ul>
        </div>
        <div id="navbar-1" class="navbar navbar-right">
            <?php require_once('navbar.php'); ?>
        </div>
        <div style="clear:both"></div>
    </div>
    <script type="text/javascript">
        var base_url="<?php echo base_url();?>";
    </script>
    <script type="text/javascript">
        function setNav(){
            var demosubmenu = $('#demo-submenu');
            if (demosubmenu.length){
                if ($(window).width() < 450){
                    demosubmenu.find('a:last').hide();
                } else {
                    demosubmenu.find('a:last').show();
                }
            }
            if ($(window).width() < 767){
                $('.navigation-toggle').each(function(){
                    $(this).show();
                    var target = $(this).attr('data-target');
                    $(target).hide();
                    setDemoNav();
                });
            } else {
                $('.navigation-toggle').each(function(){
                    $(this).hide();
                    var target = $(this).attr('data-target');
                    $(target).show();
                });
            }
        }
        function setDemoNav(){
            $('.navigation-toggle').each(function(){
                var target = $(this).attr('data-target');
                if (target == '#navbar-demo'){
                    if ($(target).is(':visible')){
                        $(this).css('margin-bottom', 0);
                    } else {
                        $(this).css('margin-bottom', '2.3em');
                    }
                }
            });
        }
        $(function(){
            setNav();
            $(window).bind('resize', function(){
                setNav();
            });
            $('.navigation-toggle').bind('click', function(){
                var target = $(this).attr('data-target');
                $(target).toggle();
                setDemoNav();
            });
        })
    </script>		</div>
<div id="mainwrap">
    <div id="content">
        <link id="dlink" rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/ui-cupertino/easyui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/color.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/demo.css">

        <script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.easyui.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/jquery.etree.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/jquery.edatagrid.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/datagrid-filter.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/datagrid-detailview.js"></script>

        <div id="main-layout" class="easyui-layout" style="width: 100%; height: 600px;">
            <div region="west" split="true" style="width:200px">
                <?php require_once('sidemenu.php'); ?>
            </div>
            <div region="center" title=" " style="padding:5px;" id="contentdata">
                <?php if(isset($content)) echo $content; ?>
            </div>
        </div>

        <script type="text/javascript">

            $(function(){
                if ($(window).width() < 767){
                    $('#main-layout').layout('collapse','west');
                }

                $('.easyui-dialog').dialog({
                    height:'92%',
                    width:'99%',
                    closed:true,
                    modal:true,
                    border:'thin'
                });
            });

            function generate_submenu() {
                $.ajax({
                    url : base_url+"welcome/load_menu",
                    type: "POST",
                    dataType: "JSON",
                    success: function(data){
                        // console.log(data)
                        let main_menu = data.data.default.main_menu;
                        let sub_menu = data.data.default.sub_menu;
                        let subsub_menu = data.data.default.subsub_menu;

                        main_menu.forEach(item=>{global_auth[item.id] = item;});

                        // console.log(subsub_menu)
                        $('#submenu').accordion({
                            onSelect:function (name,index) {
                                // console.log(name);
                                // console.log(index);
                                localStorage['menu'] = name;
                            }
                        });
                        for(let i=0; i<main_menu.length; i++){
                            let main = main_menu[i];
                            let a ='';
                            if(sub_menu[main.id]) {
                                sub_menu[main.id].forEach(item=>{global_auth[item.id] = item;});
                                for (let x = 0; x < sub_menu[main.id].length; x++) {
                                    let sub = sub_menu[main.id][x];
                                    a += '<li><span>' + sub.name + '</span></li>';
                                }
                            }
                            var html = `<ul id="${main.id}" class="easyui-tree">`;
                            let ls = localStorage['menu'] || 'subki';
                            $('#submenu').accordion('add',{
                                title: main.name,
                                selected: ls===main.name,
                                'data-option':{
                                    iconCls:main.icon,
                                },
                                content:html
                            });
                            let js = [];
                            if(sub_menu[main.id]) {
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
                            }
                            // console.log(js);
                            $(`#${main.id}`).tree({
                                data: js,
                                onClick:function (node) {
                                    localStorage['submenuw'] = node.id;
                                    if(node.data.url!=='#') window.location = base_url+node.data.url
                                },
                                onLoadSuccess:function () {
                                    let ls1 = localStorage['submenuw'] || 'subsubki';
                                    var node = $(this).tree('find', ls1);
                                    if (node){
                                        $(this).tree('select', node.target);
                                    }
                                }
                            });

                        }
                        // var panels = $('#submenu').accordion('panels');
                        // $.map(panels, function(p){
                        //     console.log(p)
                        //     p.panel('collapse');
                        // })
                    }
                });
            }

            $(document).ready(function () {
                generate_submenu();
                let he = document.getElementById('header').offsetHeight;
                $('#main-layout').layout({
                    height:$(window).height()-he,
                    width:$(window).width()
                })
                $('#main-layout').layout('panel', 'west').panel({
                    title:fullname,
                    onCollapse:function(){
                        var title = $('#main-layout').layout('panel','west').panel('options').title;  // get the west panel title
                        var p = $('#main-layout').data('layout').panels['expandWest'];  // the west expand panel
                        p.html('<div class="panel-title" style="-moz-transform: rotate(90deg);padding:6px 2px;-ms-transform: rotate(90deg);-webkit-transform: rotate(90deg);width: 400px; height: 400px;position:absolute;top:0;left:-368px">'+title+'</div>');
                    },
                    onExpand:function(){
                        // alert('expand');
                    },
                    onResize:function(){
                        // alert('resize')
                    }
                });
            });
        </script>

        ﻿			</div>
</div>
<div id="footer">
    <!--    <div class="units-row text-centered">Copyright © 2019 www.flymannathalie.com</div>-->
</div>
</body>
</html>