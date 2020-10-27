var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"productcolour/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"status",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            addnew()
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            editData()
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            deleteData()
        }
    },{
        id:'approve',
        iconCls: 'icon-ok',
        text:'Approve/Draft',
        handler: function(){
            ApproveData()
        }
    },{
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("dg", function (x, x1, x2) {
                let urlss = base_url+"productcolour/export_data?field="+x+"&op="+x1+"&value="+x2;
                window.open(urlss, '_blank')
            })
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    view: detailview,
    detailFormatter:function(index,row){
        return '<div style="padding:2px;position:relative;"><table class="ddv"></table></div>';
    },
    onExpandRow:function (index, row) {
        let ddv = $(this).datagrid('getRowDetail', index).find('table.ddv');
        ddv.edatagrid({
            url: base_url+"productcolour/load_grid_sub/"+row.colour_code,
            saveUrl: base_url+"productcolour/save_data_sub/"+row.colour_code,
            updateUrl: base_url+"productcolour/edit_data_sub",
            destroyUrl: base_url+"productcolour/delete_data_sub",
            fitColumns:true,
            singleSelect:true,
            onResize:function(){
                ddv.edatagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
                setTimeout(function(){
                    ddv.edatagrid('fixDetailRowHeight',index);
                },500);
                authbutton();
            },
            onAfterEdit:function(data){
                ddv.edatagrid('reload');
            },
            onSave: function(index, row){
                ddv.edatagrid('reload');
            },
            loadFilter: function(data){
                data.rows = [];
                if (data.data){
                    data.rows = data.data;
                }
                console.log(data.rows)
                    return data;
            },
            onBeforeEdit: function(index, row){
                if(row.status==="Approved"){
                    $.messager.show({
                        title: 'Warning',
                        msg: "Data sudah di approve, tidak bisa di edit/delete"
                    });
                    setTimeout(function () {
                        ddv.edatagrid('cancelRow');
                    },500)
                }
            },
            toolbar:[
                {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
                    ddv.edatagrid('addRow',0);
                        ddv.edatagrid('fixDetailRowHeight',index);
                }},
                {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
                    let row =ddv.edatagrid('getSelected');
                        if(row.status==="Approved"){
                            $.messager.show({
                                title: 'Warning',
                                msg: "Data sudah di approve, tidak bisa di edit/delete"
                            });
                            setTimeout(function () {
                                ddv.edatagrid('cancelRow');
                            },500)
                        }else{
                            ddv.edatagrid('destroyRow');
                            ddv.edatagrid('fixDetailRowHeight',index);
                        }
                }},
                {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
                    ddv.edatagrid('saveRow');
                        ddv.edatagrid('fixDetailRowHeight',index);
                }},
                {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function(){
                    ddv.edatagrid('cancelRow');
                        ddv.edatagrid('fixDetailRowHeight',index);
                }},{
                    id:'approve2',
                    iconCls: 'icon-ok',
                    text:'Approve/Draft',
                    handler: function(){
                        ApproveDataSub(ddv.edatagrid('getSelected'))
                    }
                },{
                    iconCls: 'icon-download', id:'download',
                    text:'Download',
                    handler: function(){
                        let urlss = `${base_url}productcolour/export_data_sub/${row.colour_code}?field=colour_code&op=equal&value=${row.colour_code}`;
                        // let urlss = base_url+"productcolour/export_data_sub/"+row.colour_code;
                        window.open(urlss, '_blank')
                    }
                }],
            columns:[[
                {field:"id",   title:"ID",      width: '10%', sortable: true, editor:{
                        type:'textbox',
                        options:{disabled:false, readonly:true}}},
                {field:"description",   title:"Keterangan",      width: '60%', sortable: true, editor:{type:'textbox',options:{required:true}}},
                {field:"status",   title:"Status",      width: '20%', sortable: true},
            ]],
            rowStyler:function(index,row){
                if (row.status==="Draft"){
                    return 'color:red;';
                }
            },
            onSuccess: function(index, row){
                if(row.status===1) {
                    $.messager.show({    // show error message
                        title: 'Error',
                        msg: row.msg
                    });
                }
            }
        })
        ddv.edatagrid('fixDetailRowHeight',index);
    },
    columns:[[
        {field:'e',expander:true},
        {field:"colour_code",   title:"Kode",      width: 70, sortable: true},
        {field:"description",   title:"Deskripsi",      width: 200, sortable: true},
        {field:"status",   title:"Status",      width: 90, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    rowStyler:function(index,row){
        if (row.status==="Draft"){
            return 'color:red;';
        }
    },
    onLoadSuccess:function(){
        // $('#edit').linkbutton({disabled:true});
        // $('#delete').linkbutton({disabled:true});
        // $('#approve').linkbutton({disabled:true});
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        disable_enable(true)
        authbutton();
    },
    onSelect: function(index, row) {
        // $('#edit').linkbutton({disabled:false});
        // $('#delete').linkbutton({disabled:false});
        // $('#approve').linkbutton({disabled:false});

        $('#fm').form('load',row);
    }
};

setTimeout(function () {
    initGrid();
},500);

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}
function clearInput() {
    $('#fm').form('clear');
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});
    disable_enable(true)
    $('#dlg').dialog('close');
}

function addnew(){
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`New Colour`);
    disable_enable(false)
    $('#colour_code').textbox({disabled:false, readonly:false, width:'100%'});
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "productcolour/save_data";
}
function editData(){
    let row = getRow();
    if(row==null) return
    if(row.status==="Approved"){
        $.messager.show({
            title: 'Warning',
            msg: "Data sudah di approve, tidak bisa di edit/delete"
        });
        clearInput();
        return;
    }
    $.ajax({
        type:"POST",
        url:base_url+"productcolour/read_data/"+row.colour_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Edit Colour`);
            disable_enable(false);
            $('#colour_code').textbox({disabled:false, readonly:true, width:'100%'});
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "productcolour/edit_data";
        }
    });
}

function ApproveData(){
    let row = getRow();
    if(row==null) return
    let par = (row.status==="Draft") ? "Approved":"Draft";
    $.messager.confirm('Confirm',`Are you sure you want to ${row.status==='Draft'?'Approve':'set to Draft'} this item?`,function(r){
        if (r){
            $.ajax({
                type:"POST",
                url:base_url+"productcolour/edit_data_status/"+row.colour_code+"/"+par,
                dataType:"html",
                success:function(result){
                    var data = $.parseJSON(result);
                    if (data.status === 0) {
                        $('#dg').datagrid('reload');
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: data.msg
                        });
                    }
                }
            });
        }
    });
}

function ApproveDataSub(row){
    if(row==null) return
    let par = (row.status==="Draft") ? "Approved":"Draft";
    $.messager.confirm('Confirm',`Are you sure you want to ${row.status==='Draft'?'Approve':'set to Draft'} this item?`,function(r){
        if (r){
            $.ajax({
                type:"POST",
                url:base_url+"productcolour/edit_data_status_sub/"+row.id+"/"+par,
                dataType:"html",
                success:function(result){
                    var data = $.parseJSON(result);
                    if (data.status === 0) {
                        $('#dg').datagrid('reload');
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: data.msg
                        });
                    }
                }
            });
        }
    });
}
function deleteData(){
    let row = getRow();
    if(row==null) return
    if(row.status==="Approved"){
        $.messager.show({
            title: 'Warning',
            msg: "Data sudah di approve, tidak bisa di edit/delete"
        });
        return;
    }
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"productcolour/delete_data/"+row.colour_code,function(result){
                    var res = $.parseJSON(result);
                    if (res.status===1){
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: res.msg
                        });
                    } else {
                        $('#dg').datagrid('reload');    // reload the user data
                    }
                }
            );
        }
    });
}

function getRow() {
    var row = $('#dg').datagrid('getSelected');
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
        return null;
    }else{
        row.record = $('#dg').datagrid("getRowIndex", row);
    }
    return row;
}
function submit(){
    console.log(flag)
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    $('#dlg').dialog('close');
                    $('#dg').datagrid('reload');    // reload the user data
                    clearInput();
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: res.msg
                    });
                }
            }catch (e) {
                console.log(e)
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            }
        }
    });
}