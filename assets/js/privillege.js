
setTimeout(function () {
    initGrid();
},500);

var flag = undefined;
function initGrid() {
    $('#tts').accordion('add',{
        title: "Group Privillege",
        content:`<ul id="tree_group">`
    });
    $('#app').accordion('add',{
        title: "Module",
        content:`<div id="p" class="easyui-panel" style="width:100%;height:100%;padding:10px;">
                    <input class="easyui-searchbox" data-options="prompt:'Enter something here',searcher:search_app" style="width:100%; margin-right: 10px">
                    <ul id="tree_app">
                </div>`
    });
    $('#priv').accordion('add',{
        title: "Privillege Module",
        content:`<div id="p" class="easyui-panel" style="width:100%;height:100%;padding:10px;">
                    <input class="easyui-searchbox" data-options="prompt:'Enter something here',searcher:search_priv" style="width:100%; margin-right: 10px">
                    <ul id="tree_priv"></ul>
                </div>`
    });
    $('#app2').accordion('add',{
        title: "Users",
        content:`<div id="p" class="easyui-panel" style="width:100%;height:100%;padding:10px;">
                    <input class="easyui-searchbox" data-options="prompt:'Enter something here',searcher:search_app2" style="width:100%; margin-right: 10px">
                    <ul id="tree_app2">
                </div>`
    });
    $('#priv2').accordion('add',{
        title: "Privillege User",
        content:`<div id="p" class="easyui-panel" style="width:100%;height:100%;padding:10px;">
                    <input class="easyui-searchbox" data-options="prompt:'Enter something here',searcher:search_priv2" style="width:100%; margin-right: 10px">
                    <ul id="tree_priv2"></ul>
                </div>`
    });
    get_group();
}
function search_priv(value,name){
    var grp = $('#tree_group').tree('getSelected');
    if(!grp) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group.'
        });
        return
    }
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid/"+grp.id,
        success:function(result){
            $('#priv').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            for (let i = 0; i < data.data.length; i++) {
                if(data.data[i].text===null) continue;
                if(data.data[i].text.toLowerCase().includes(value.toLowerCase())){
                    dx.push(data.data[i]);
                }
            }
            $(`#tree_priv`).tree({
                data: dx,
                onDblClick:remove_from_priv,
                // onSelect:show_users_group
            });
        }
    });
}

function search_app(value,name){
    var grp = $('#tree_group').tree('getSelected');
    if(!grp) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group.'
        });
        return
    }
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid2/"+grp.id,
        success:function(result){
            $('#app').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            for (let i = 0; i < data.data.length; i++) {
                if(data.data[i].text.toLowerCase().includes(value.toLowerCase())){
                    dx.push(data.data[i]);
                }
            }
            $(`#tree_app`).tree({
                data: dx,
                onDblClick:add_to_priv
            });
        }
    });
}
function search_priv2(value,name){
    var grp = $('#tree_group').tree('getSelected');
    if(!grp) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group.'
        });
        return
    }
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid_user/"+grp.id,
        success:function(result){
            $('#priv2').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            for (let i = 0; i < data.data.length; i++) {
                if(data.data[i].text===null) continue;
                if(data.data[i].text.toLowerCase().includes(value.toLowerCase())){
                    dx.push(data.data[i]);
                }
            }
            $(`#tree_priv2`).tree({
                data: dx,
                onDblClick:remove_from_priv2,
                // onSelect:show_users_group
            });
        }
    });
}

function search_app2(value,name){
    var grp = $('#tree_group').tree('getSelected');
    if(!grp) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group.'
        });
        return
    }
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid2_user/"+grp.id,
        success:function(result){
            $('#app2').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            for (let i = 0; i < data.data.length; i++) {
                if(data.data[i].text.toLowerCase().includes(value.toLowerCase())){
                    dx.push(data.data[i]);
                }
            }
            $(`#tree_app2`).tree({
                data: dx,
                onDblClick:add_to_priv2
            });
        }
    });
}
var selectedNodeId;
function get_group() {
    $('#tree_group').etree({
        url: base_url+"privillege/load_grid",
        createUrl:base_url+"privillege/save_group",
        updateUrl:base_url+"privillege/update_group",
        destroyUrl:base_url+"privillege/delete_group",
        onLoadSuccess:function(){
            var node = $('#tree_group').etree('find', selectedNodeId);
            if (node){
                $('#tree_group').etree('select', node.target);
            }
        },
        onClick:function (node) {
            selectedNodeId = node.id;
            console.log(node)
            var vallow_add = parseInt(node.allow_add)>0?'checked':'';
            var vallow_edit = parseInt(node.allow_edit)>0?'checked':'';
            var vallow_delete = parseInt(node.allow_delete)>0?'checked':'';
            var vallow_print = parseInt(node.allow_print)>0?'checked':'';
            var vallow_download = parseInt(node.allow_download)>0?'checked':'';
            var vallow_unposting = parseInt(node.allow_unposting)>0?'checked':'';
            var vallow_approve = parseInt(node.allow_approve)>0?'checked':'';
            var vallow_approve2 = parseInt(node.allow_approve2)>0?'checked':'';
            var vallow_approve3 = parseInt(node.allow_approve3)>0?'checked':'';
            var vallow_approve4 = parseInt(node.allow_approve4)>0?'checked':'';
            var vallow_approve5 = parseInt(node.allow_approve5)>0?'checked':'';
            var a = `
                <tr>>
                <td style="width:50px;">Add <br/><input type="checkbox" ${vallow_add} onchange="changePermission(this);" name="allow_add"></td>
                <td style="width:50px;">Edit <br/><input type="checkbox" ${vallow_edit} onchange="changePermission(this);" name="allow_edit"></td>
                <td style="width:50px;">Delete <br/><input type="checkbox" ${vallow_delete} onchange="changePermission(this);" name="allow_delete"></td>
                <td style="width:50px;">Print <br/><input type="checkbox" ${vallow_print} onchange="changePermission(this);" name="allow_print"></td>
                <td style="width:50px;">Download <br/><input type="checkbox" ${vallow_download} onchange="changePermission(this);" name="allow_download"></td>
                <td style="width:50px;">Unposting <br/><input type="checkbox" ${vallow_unposting} onchange="changePermission(this);" name="allow_unposting"></td>
                <td style="width:50px;">App <br/><input type="checkbox" ${vallow_approve} onchange="changePermission(this);" name="allow_approve"></td>
                <td style="width:50px;">App 2 <br/><input type="checkbox" ${vallow_approve2} onchange="changePermission(this);" name="allow_approve2"></td>
                <td style="width:50px;">App 3 <br/><input type="checkbox" ${vallow_approve3} onchange="changePermission(this);" name="allow_approve3"></td>
                <td style="width:50px;">App 4 <br/><input type="checkbox" ${vallow_approve4} onchange="changePermission(this);" name="allow_approve4"></td>
                <td style="width:50px;">App 5 <br/><input type="checkbox" ${vallow_approve5} onchange="changePermission(this);" name="allow_approve5"></td>
                </tr>
            `;
            $("#isi").html(a);
            get_sub_priv(node.id);
            get_sub_app(node.id);
            get_sub_priv2(node.id);
            get_sub_app2(node.id);
        },
        onDblClick:function (node) {
            console.log(node)
            $(this).etree('edit')
        },
    });
}
function changePermission(e) {
    var grp = $('#tree_group').tree('getSelected');

    if(!grp) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group / privilege.'
        });
        return
    }
    console.log(e.name)
    console.log(e.checked)
    console.log(grp.id)
    $.ajax({
        type:"POST",
        url:base_url+"privillege/change_permission",
        data:{
            group_id : grp.id,
            field:e.name,
            nilai:e.checked?1:0
        },
        success:function(result){
            $('#tree_group').tree('reload');
        }
    });
}
function get_sub_app(id) {
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid2/"+id,
        success:function(result){
            $('#app').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            if(data.data.length > 0) dx = data.data;
            $(`#tree_app`).tree({
                data: dx,
                onDblClick:add_to_priv
            });
        }
    });
}
function get_sub_priv(id) {
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid/"+id,
        success:function(result){
            $('#priv').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            if(data.data.length > 0) dx = data.data;
            $(`#tree_priv`).tree({
                data: dx,
                onDblClick:remove_from_priv,
                // onSelect:show_users_group
            });
        }
    });
}
function get_sub_app2(id) {
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid2_user/"+id,
        success:function(result){
            $('#app2').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            if(data.data.length > 0) dx = data.data;
            $(`#tree_app2`).tree({
                data: dx,
                onDblClick:add_to_priv2
            });
        }
    });
}
function get_sub_priv2(id) {
    $.ajax({
        type:"POST",
        url:base_url+"privillege/get_subgrid_user/"+id,
        success:function(result){
            $('#priv2').accordion({
                onSelect:function (name,index) {
                    console.log(name);
                    console.log(index);
                }
            });
            let dx = [];
            var data = $.parseJSON(result);
            if(data.data.length > 0) dx = data.data;
            $(`#tree_priv2`).tree({
                data: dx,
                onDblClick:remove_from_priv2,
                // onSelect:show_users_group
            });
        }
    });
}
function add_to_priv(){
    var grp = $('#tree_group').tree('getSelected');
    var app = $('#tree_app').tree('getSelected');

    if(!grp || !app) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group / module.'
        });
        return
    }

    $.ajax({
        method:"POST",
        url:base_url+"privillege/save_users_group_detail",
        data:{
            group_id:grp.id,
            app_id:app.id
        },
        success:function(result){
            get_sub_app(grp.id)
            get_sub_priv(grp.id)
        }
    });
}

function remove_from_priv(){
    var grp = $('#tree_group').tree('getSelected');
    var app = $('#tree_priv').tree('getSelected');

    if(!grp || !app) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group / privilege.'
        });
        return
    }

    $.ajax({
        method:"POST",
        url:base_url+"privillege/remove_users_group_detail",
        data:{
            group_id:grp.id,
            app_id:app.id
        },
        success:function(result){
            get_sub_app(grp.id)
            get_sub_priv(grp.id)
        }
    });
}

function add_to_priv2(){
    var grp = $('#tree_group').tree('getSelected');
    var app = $('#tree_app2').tree('getSelected');

    if(!grp || !app) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group / user.'
        });
        return
    }

    $.ajax({
        method:"POST",
        url:base_url+"privillege/save_users_group_detail2",
        data:{
            group_id:grp.id,
            user_id:app.id
        },
        success:function(result){
            get_sub_app2(grp.id)
            get_sub_priv2(grp.id)
        }
    });
}

function remove_from_priv2(){
    var grp = $('#tree_group').tree('getSelected');
    var app = $('#tree_priv2').tree('getSelected');

    console.log(app)

    if(!grp || !app) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group / user.'
        });
        return
    }

    $.ajax({
        method:"POST",
        url:base_url+"privillege/remove_users_group_detail2",
        data:{
            group_id:grp.id,
            user_id:app.user_id
        },
        success:function(result){
            get_sub_app2(grp.id)
            get_sub_priv2(grp.id)
        }
    });
}
function show_users_group(){
    var grp = $('#tree_group').tree('getSelected');
    var app = $('#tree_priv').tree('getSelected');

    if(!grp || !app) {
        $.messager.show({
            title: 'Error',
            msg: 'Please select group / privilege.'
        });
        return
    }

    $('#dg').edatagrid({
        title:grp.text+" - "+app.text,
        url: base_url+"privillege/show_users_group/"+grp.id+"/"+app.users_group_detail_id,
        saveUrl: `${base_url}privillege/save_users_group_det_user/${grp.id}/${app.users_group_detail_id}`,
        onAfterEdit:function(data){
            $('#dg').edatagrid('reload');
        },
        onSave: function(index, row){
            $('#dg').edatagrid('reload');
        },
        updateUrl: `${base_url}privillege/update_users_group_det_user`,
        destroyUrl: `${base_url}privillege/delete_users_group_det_user`,
        toolbar:"#toolbar",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        loadFilter: function(data){
            data.rows = [];
            data.total = 0;
            if (data.data) data.rows = data.data;
            return data;
        },
        columns:[[
            {field:"id",   title:"ID",      width: '8%', sortable: true},
            {field:"user_id",   title:"UserId",      width: '20%', sortable: true, editor:{
                    type:'combobox',
                    options:{
                        url:base_url+'privillege/get_users/'+app.users_group_detail_id,
                        valueField:'user_id',
                        textField:'fullname',
                        required:true,
                        prompt:'-Please Select-',
                        validType:'cekKeberadaan["#dg","user_id"]',
                        loadFilter: function(data){
                            return data.data;
                        }
                    }
                }},
            {field:"fullname",   title:"Nama",      width: '30%', sortable: true},
            {field:"allow_add",   title:"Add",    align:"center",  width: '8%', editor:{
                    type:'checkbox',
                    options:{on:'1',off:'0'}
                }},
            {field:"allow_edit",   title:"Edit",    align:"center",  width: '8%', editor:{
                    type:'checkbox',
                    options:{on:'1',off:'0'}
                }},
            {field:"allow_delete",   title:"Delete",    align:"center",  width: '8%', editor:{
                    type:'checkbox',
                    options:{on:'1',off:'0'}
                }},
            {field:"allow_print",   title:"Print",    align:"center",  width: '8%', editor:{
                    type:'checkbox',
                    options:{on:'1',off:'0'}
                }},
            {field:"allow_approve",   title:"App 1",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'1',off:'0'}
            }},
            {field:"allow_approve2",   title:"App 2",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'1',off:'0'}
            }},
            {field:"allow_approve3",   title:"App 3",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'1',off:'0'}
            }},
            {field:"allow_approve4",   title:"App 4",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'1',off:'0'}
            }},
            {field:"allow_approve5",   title:"App 5",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'1',off:'0'}
            }},
            {field:"allow_download",   title:"Download",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'1',off:'0'}
            }},
            {field:"allow_unposting",   title:"Unposting",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'1',off:'0'}
            }},
        ]],
        // rowStyler:function(index,row){
        //     return 'background-color:white;color:black;';
        // },
        // onselect:function (index, row) {
        //
        // }
    });
    // console.log(grp);
    // console.log(app);
    // $.ajax({
    //     method:"POST",
    //     url:base_url+"privillege/show_users_group",
    //     data:{
    //         group_id:grp.id,
    //         group_det_id:app.users_group_detail_id
    //     },
    //     success:function(result){
    //         console.log(result)
    //         // get_sub_app(grp.id)
    //         // get_sub_priv(grp.id)
    //     }
    // });
}

//
// function clearInput() {
//     $('#fm').form('clear');
//     $('#submit').linkbutton({disabled:true});
//     $('#cancel').linkbutton({disabled:true});
// }
//
// function addnew(){
//     $('#user_id').textbox({
//         disabled:true,
//         readonly:false,
//         width:'100%'
//     });
//     $('#submit').linkbutton({disabled:false});
//     $('#cancel').linkbutton({disabled:false});
//     $('#fm').form('clear');
//     flag = "users/save_data";
// }
// function editData(){
//     let row = getRow();
//     if(row==null) return
//     $.ajax({
//         type:"POST",
//         url:base_url+"users/read_data/"+row.user_id,
//         dataType:"html",
//         success:function(result){
//             var data = $.parseJSON(result);
//             $('#user_id').textbox({
//                 disabled:false,
//                 readonly:true,
//                 width:'100%'
//             });
//             $('#submit').linkbutton({disabled:false});
//             $('#cancel').linkbutton({disabled:false});
//             $('#fm').form('load',data.data);
//             flag = "users/edit_data";
//         }
//     });
// }
//
// function deleteData(){
//     let row = getRow();
//     if(row==null) return
//     $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
//         if (r){
//             $.post(
//                 base_url+"users/delete_data/"+row.user_id,function(result){
//                     var res = $.parseJSON(result);
//                     if (res.status===1){
//                         $.messager.show({    // show error message
//                             title: 'Error',
//                             msg: res.msg
//                         });
//                     } else {
//                         $('#dg').datagrid('reload');    // reload the user data
//                     }
//                 }
//             );
//         }
//     });
// }
//
function getRow() {
    var row = $('#dg').edatagrid('getSelected');
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
        return null;
    }else{
        row.record = $('#dg').edatagrid("getRowIndex", row);
    }
    return row;
}
// function submit(){
//     console.log(flag)
//     $('#fm').form('submit',{
//         url: base_url+flag,
//         type: 'post',
//         success: function(result){
//             console.log(result)
//             try {
//                 var res = $.parseJSON(result);
//                 console.log(result);
//                 console.log(res.status);
//                 if (res.status === 0) {
//                     // $('#dlg').dialog('close');        // close the dialog
//                     $('#dg').datagrid('reload');    // reload the user data
//                     clearInput();
//                 } else {
//                     $.messager.show({
//                         title: 'Error',
//                         msg: res.msg
//                     });
//                 }
//             }catch (e) {
//                 console.log(e)
//                 $.messager.show({
//                     title: 'Error',
//                     msg: e.message
//                 });
//             }
//         }
//     });
// }