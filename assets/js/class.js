var options={
    url: base_url+"productgroup/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"class_code",
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
                let urlss = base_url+"productgroup/export_data?field="+x+"&op="+x1+"&value="+x2;
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
        {field:"class_code",   title:"Kode",      width: 70, sortable: true},
        {field:"description",   title:"Product Group",      width: 150, sortable: true},
        // {field:"addcost",   title:"Add. Cost",      width: 60, sortable: true},
        // {field:"jenis_barang",   title:"Jenis Brg",      width: 90, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        // $('#edit').linkbutton({disabled:true});
        // $('#delete').linkbutton({disabled:true});
        authbutton();
    },
    onSelect: function(index, row) {
        // $('#edit').linkbutton({disabled:false});
        // $('#delete').linkbutton({disabled:false});

        options2.url = base_url+"productgroup/load_grid2/"+row.class_code;
        options2.title = row.description;
        initGridSubClass();
    }
};
var options2={
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"subclass_code",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            addnew2()
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            editData2()
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            deleteData2()
        }
    },{
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            var row = getRow();
            let urlss = `${base_url}productgroup/export_data_sub/${row.class_code}?field=class_code&op=equal&value=${row.class_code}`;
            window.open(urlss, '_blank')
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data){
            data.rows = data.data;
            return data;
        } else {
            return data;
        }
    },
    columns:[[
        {field:"subclass_code",   title:"Kode",      width: 70, sortable: true},
        {field:"description",   title:"Description",      width: 150, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        // $('#edit2').linkbutton({disabled:true});
        // $('#delete2').linkbutton({disabled:true});
        authbutton()
    },
    onSelect: function(index, row) {
        // $('#edit2').linkbutton({disabled:false});
        // $('#delete2').linkbutton({disabled:false});
    }
};

setTimeout(function () {
    initGrid();
    populateJenisBarang();
},500);

function populateJenisBarang() {
    $('#jenis_barang').combobox({
        data:[
            {value:'Barang Jadi',text:'Barang Jadi'},
            {value:'Bahan Baku',text:'Bahan Baku'},
            {value:'Accessories',text:'Accessories'},
            {value:'Packing',text:'Packing'},
            {value:'Spare Part',text:'Spare Part'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#jenis_barang"]',
    });
}
var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}
function initGridSubClass() {
    $('#tt').datagrid(options2);
    $('#tt').datagrid('enableFilter');
}


function addnew(){
    $('#class_code').textbox({
        disabled:false, readonly:false, width:200
    });
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Product Group');
    $('#fm').form('clear');
    flag = "productgroup/save_data";
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"productgroup/read_data/"+row.class_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#class_code').textbox({
                disabled:false, readonly:true, width:200
            });
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit Product Group');
            $('#fm').form('load',data.data);
            flag = "productgroup/edit_data";
        }
    });
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"productgroup/delete_data/"+row.class_code,function(result){
                    var res = $.parseJSON(result);
                    if (res.status===1){
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: res.msg
                        });
                    } else {
                        $('#dg').datagrid('reload');    // reload the user data
                        $('#tt').datagrid('reload');    // reload the user data
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
                    $('#dlg').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
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
function addnew2(){
    var row = getRow();
    $('#fm2').form('clear');
    $('#subclass_code').textbox({
        disabled:true, readonly:true, width:250
    });
    $('#class_code2').textbox({
        disabled:false, readonly:true, width:250, value:row.class_code
    });
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle','New SubClass');

    flag = "productgroup/save_data2";
}
function editData2(){
    let row = getRow2();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"productgroup/read_data2/"+row.class_code+"/"+row.subclass_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#subclass_code').textbox({
                disabled:false, readonly:true, width:250
            });
            $('#class_code2').textbox({
                disabled:false, readonly:true, width:250
            });
            $('#dlg2').dialog('open').dialog('center').dialog('setTitle','Edit SubClass');
            $('#fm2').form('load',data.data);
            flag = "productgroup/edit_data2";
        }
    });
}

function deleteData2(){
    let row = getRow2();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"productgroup/delete_data2/"+row.class_code+"/"+row.subclass_code,function(result){
                    var res = $.parseJSON(result);
                    if (res.status===1){
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: res.msg
                        });
                    } else {
                        $('#tt').datagrid('reload');    // reload the user data
                    }
                }
            );
        }
    });
}

function getRow2() {
    var row = $('#tt').datagrid('getSelected');
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
        return null;
    }else{
        row.record = $('#tt').datagrid("getRowIndex", row);
    }
    return row;
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
                    $('#dlg2').dialog('close');        // close the dialog
                    $('#tt').datagrid('reload');    // reload the user data
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
