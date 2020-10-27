var ddv = undefined;

var index1;
var index2;

var options={
    title:"Provinsi",
    url: base_url+"wilayah/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"id",
    sortOrder:"asc",
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
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("dg", function (x, x1, x2) {
                let urlss = base_url+"wilayah/export_data?field="+x+"&op="+x1+"&value="+x2;
                window.open(urlss, '_blank')
            })
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"id",   title:"ID",      width: 80, sortable: true},
        {field:"name",   title:"Provinsi",      width: 320, sortable: true},
        {field:"crtby",   title:"Create By",      width: 130, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 180, sortable: true},
        {field:"updby",   title:"Update By",      width: 130, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 180, sortable: true},
    ]],
    onLoadSuccess:function(){
		authbutton();
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
    },
    onSelect: function(index, row) {
        $('#fm').form('load',row);
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});

        index1 = index;
    },
    view: detailview,
    detailFormatter:function(index,row){
        return '<div style="padding:2px;position:relative;"><table class="ddv"></table></div>';
    },
    onExpandRow:function (index, row2) {
        ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
        ddv.datagrid({
            url:base_url+"wilayah2/load_grid/"+row2.id,
            method:'GET',
            fitColumns:true,
            singleSelect:true,
            title: "Kota / Kabupaten",
            toolbar:[{
                iconCls: 'icon-add', id:'add',
                text:'New',
                handler: function(){
                    addnew2()
                }
            },{
                id:'edit2',
                iconCls: 'icon-edit',
                text:'Edit',
                handler: function(){
                    editData2()
                }
            },{
                id:'delete2',
                iconCls: 'icon-remove',
                text:'Delete',
                handler: function(){
                    deleteData2()
                }
            },{
                id:'min_order',
                iconCls: 'icon-coins',
                text:'Minimum Order',
                handler: function(){
                    openMinimumOrder()
                }
            }],
            loadFilter: function(data){
                if (data.data){
                    data.rows = data.data;
                    return data;
                } else {
                    return data;
                }
            },
            height:'auto',
            onResize:function(){
                $('#dg').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
                setTimeout(function(){
                    $('#dg').datagrid('fixDetailRowHeight',index);
                },500);
				authbutton();
                $('#submit2').linkbutton({disabled:true});
                $('#cancel2').linkbutton({disabled:true});
                disable_enable(true)
                ddv.datagrid('enableFilter');
            },
            onSelect: function(index, row) {
                $('#fm2').form('load',row);
                $('#submit2').linkbutton({disabled:true});
                $('#cancel2').linkbutton({disabled:true});

                index2 = index;
            },
            columns:[[
                {field:"id",   title:"ID",      width: '40%', sortable: true},
                {field:"name",       title:"Nama",      width: '60%', sortable: true},
            ]],
        });
        ddv.datagrid('enableFilter');
        $('#dg').datagrid('fixDetailRowHeight',index);
    }
};

function openMinimumOrder(){
    let row = getRow2(true);
    if(row==null) return
    console.log(row)
    $('#dlg3').dialog('open').dialog('center').dialog('setTitle',`Minimum Order : ${row.name}`);
    $('#prc').edatagrid('loadData', []);
    $('#prc').edatagrid({
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#prc').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
            $('#prc').edatagrid('reload');
        },
        toolbar:[{
            iconCls: 'icon-add', id:'add',
            text:'New',
            handler: function(){
                $('#prc').edatagrid('addRow',0)
            }
        },{
            id:'delete',
            iconCls: 'icon-remove',
            text:'Delete',
            handler: function(){
                $('#prc').edatagrid('destroyRow')
            }
        },{
            id:'submit',
            iconCls: 'icon-save',
            text:'Submit',
            handler: function(){
                $('#prc').edatagrid('saveRow')
            }
        },{
            id:'cancel',
            iconCls: 'icon-undo',
            text:'Cancel',
            handler: function(){
                $('#prc').edatagrid('cancelRow')
            }
        }],
        onBeforeEdit: function(index, row){
            if(row.isNewRecord) return
            $.messager.show({
                title: 'Warning',
                msg: "Tidak boleh di edit, hanya bisa di hapus"
            });
            setTimeout(function () {
                $("#prc").edatagrid('cancelRow');
            },500)
        },
        url: base_url+"wilayahorder/load_grid/"+row.id,
        saveUrl: base_url+"wilayahorder/save_data/"+row.id,
        updateUrl: base_url+"wilayahorder/edit_data",
        destroyUrl: base_url+"wilayahorder/delete_data",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"customer_type",
        sortOrder:"desc",
        height:'100%',
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        onLoadSuccess:function(){
            authbutton();
        },
        columns:[[
            {field:"customer_type", title:'Price Type', width: '12%', sortable: true, formatter:function (index, row) {
                return row.description;
            }, editor:{
                type:"combobox",
                options:{
                    url:base_url+"multiprice/get_customer_type",
                    valueField:'code',
                    textField:'description',
                    multiple:false,
                    panelHeight:'auto',
                    required:true,
                    prompt:'-Please Select-',
                    loadFilter: function (data) {
                        return data.data;
                    },
                }
            }},
            {field:"nilai_minimal", title:'Nilai Minimum', width: '10%', sortable: true, formatter:numberFormat, editor:{
                type:"numberbox",
                options:{
                    required:true
                }
            }},
            {field:"crtby",   title:"Create By",      width: '6%', sortable: true},
            {field:"crtdt",   title:"Create Date",      width: '7%', sortable: true},
            {field:"updby",   title:"Update By",      width: '6%', sortable: true},
            {field:"upddt",   title:"Update Date",      width: '7%', sortable: true},
        ]],
    });
    $('#prc').edatagrid('enableFilter');
}

setTimeout(function () {
    initGrid();
},500);

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}

function addnew(){
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`New Provinsi`);
    disable_enable(false)
    $('#id').textbox({disabled:false, readonly:false, width:'100%'});
    $('#fm').form('clear');
    flag = "wilayah/save_data";

    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
}
function addnew2(){
    let row = getRow();
    if(row==null) return
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`New Kota/Kabupaten`);
    disable_enable(false)
    $('#id').textbox({disabled:false, readonly:false, width:'100%'});
    $('#province_id').textbox({disabled:false, readonly:true, width:'100%'});
    $('#fm2').form('clear');
    $('#province_id').textbox('setText',row.id);
    $('#province_id').textbox('setValue',row.id);
    flag = "wilayah2/save_data";

    $('#submit2').linkbutton({disabled:false});
    $('#cancel2').linkbutton({disabled:false});
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"wilayah/read_data/"+row.id,
        dataType:"html",
        success:function(result){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Edit Provinsi`);
            disable_enable(false)
            var data = $.parseJSON(result);
            $('#id').textbox({disabled:false, readonly:true, width:'100%'});
            $('#fm').form('load',data.data);
            flag = "wilayah/edit_data";
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
        }
    });
}
function editData2(){
    let row = getRow2();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"wilayah2/read_data/"+row.id,
        dataType:"html",
        success:function(result){
            $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Edit Kota/Kabupaten`);
            disable_enable(false)
            var data = $.parseJSON(result);
            $('#id').textbox({disabled:false, readonly:true, width:'100%'});
            $('#province_id').textbox({disabled:false, readonly:true, width:'100%'});
            $('#fm2').form('load',data.data);
            flag = "wilayah2/edit_data";
            $('#submit2').linkbutton({disabled:false});
            $('#cancel2').linkbutton({disabled:false});
        }
    });
}
function clearInput() {
    $('#fm').form('clear');

    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});

    disable_enable(true)
    $('#dlg').dialog('close');
}
function clearInput2() {
    $('#fm2').form('clear');

    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});

    disable_enable(true)
    $('#dlg2').dialog('close');
}
function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"wilayah/delete_data/"+row.id,function(result){
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

function deleteData2(){
    let row = getRow2();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"wilayah2/delete_data/"+row.id,function(result){
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
function getRow2() {
    // var row = $('#dg').datagrid().datagrid('subgrid', 'ddv').datagrid('getSelected');
    var row = ddv.datagrid('getSelected');
    console.log(row);
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
        return null;
    }else{
        row.record = ddv.datagrid("getRowIndex", row);
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
                    $('#dlg').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
                    // var collapsed = $('#dg').datagrid('options').collapsed;
                    // if(collapsed){
                    //     $('#dg').datagrid('refreshRow',index1);
                    // }else {
                    //     $('#dg').datagrid('refreshRow', index1).datagrid('collapseRow', index1).datagrid('expandRow', index1);
                    // }
                    // $('#dg').datagrid('selectRow', index1);
                    $('#fm').form('clear');
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
function submit2(){
    console.log(flag)
    $('#fm2').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    $('#dlg2').dialog('close');
                    $('#dg').datagrid('refreshRow', index1).datagrid('collapseRow', index1).datagrid('expandRow', index1);
                    setTimeout(function () {
                        ddv.datagrid('selectRow', index2);
                    },500);
                    $('#fm2').form('clear');
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
