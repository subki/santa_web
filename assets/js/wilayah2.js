var ddv = undefined;
var ddv2 = undefined;
var ddv3 = undefined;

var options={
    fitColumns:true,
    width:"100%",
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
        {field:"id",   title:"ID",      width: '40%', sortable: true},
        {field:"name",   title:"Nama Wilayah",      width: '60%', sortable: true},
    ]],
    onLoadSuccess:function(){
        authbutton();
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});

        $('#fm').hide();
        $('#fm2').hide();
        $('#fm3').hide();
        $('#fm4').hide();
    },
    onSelect: function(index, row) {
        $('#fm').form('load',row);
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});

        $('#fm').show();
        $('#fm2').hide();
        $('#fm3').hide();
        $('#fm4').hide();
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
            },
            onSelect: function(index, row) {
                $('#fm2').form('load',row);
                $('#submit2').linkbutton({disabled:true});
                $('#cancel2').linkbutton({disabled:true});

                $('#fm').hide();
                $('#fm2').show();
                $('#fm3').hide();
                $('#fm4').hide();
            },
            columns:[[
                {field:"id",   title:"ID",      width: '40%', sortable: true},
                {field:"name",       title:"Nama",      width: '60%', sortable: true},
            ]],
            view: detailview,
            detailFormatter:function(index,row){
                return '<div style="padding:2px;position:relative;"><table class="ddv2"></table></div>';
            },
            onExpandRow:function (index, row3) {
                ddv2 = $(this).datagrid('getRowDetail',index).find('table.ddv2');
                ddv2.datagrid({
                    url:base_url+"wilayah3/load_grid/"+row3.id,
                    method:'GET',
                    fitColumns:true,
                    singleSelect:true,
                    title: "Kecamatan",
                    toolbar:[{
                        iconCls: 'icon-add', id:'add',
                        text:'New',
                        handler: function(){
                            addnew3()
                        }
                    },{
                        id:'edit3',
                        iconCls: 'icon-edit',
                        text:'Edit',
                        handler: function(){
                            editData3()
                        }
                    },{
                        id:'delete3',
                        iconCls: 'icon-remove',
                        text:'Delete',
                        handler: function(){
                            deleteData3()
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
                    columns:[[
                        {field:"id",   title:"ID",      width: '40%', sortable: true},
                        {field:"name",       title:"Nama",      width: '60%', sortable: true},
                    ]],
                    onLoadSuccess:function(){
                        setTimeout(function(){
                            ddv.datagrid('fixDetailRowHeight',index);
                        },500);
                        $('#edit3').linkbutton({disabled:true});
                        $('#delete3').linkbutton({disabled:true});
                        $('#submit3').linkbutton({disabled:true});
                        $('#cancel3').linkbutton({disabled:true});
                    },
                    onSelect: function(index, row) {
                        $('#edit3').linkbutton({disabled:false});
                        $('#delete3').linkbutton({disabled:false});
                        $('#fm3').form('load',row);
                        $('#submit3').linkbutton({disabled:true});
                        $('#cancel3').linkbutton({disabled:true});

                        $('#fm').hide();
                        $('#fm2').hide();
                        $('#fm3').show();
                        $('#fm4').hide();
                    },
                    view: detailview,
                    detailFormatter:function(index,row){
                        return '<div style="padding:2px;position:relative;"><table class="ddv3"></table></div>';
                    },
                    onExpandRow:function (index, row4) {
                        ddv3 = $(this).datagrid('getRowDetail',index).find('table.ddv3');
                        ddv3.datagrid({
                            url: base_url + "wilayah4/load_grid/" + row4.id,
                            method: 'GET',
                            fitColumns: true,
                            singleSelect: true,
                            title: "Desa / Kelurahan",
                            toolbar:[{
                                iconCls: 'icon-add', id:'add',
                                text:'New',
                                handler: function(){
                                    addnew4()
                                }
                            },{
                                id:'edit4',
                                iconCls: 'icon-edit',
                                text:'Edit',
                                handler: function(){
                                    editData4()
                                }
                            },{
                                id:'delete4',
                                iconCls: 'icon-remove',
                                text:'Delete',
                                handler: function(){
                                    deleteData4()
                                }
                            }],
                            loadFilter: function (data) {
                                if (data.data) {
                                    data.rows = data.data;
                                    return data;
                                } else {
                                    return data;
                                }
                            },
                            height: 'auto',
                            onResize: function () {
                                $('#dg').datagrid('fixDetailRowHeight', index);
                            },
                            onLoadSuccess:function(){
                                setTimeout(function(){
                                    ddv2.datagrid('fixDetailRowHeight',index);
                                },500);
                                $('#edit4').linkbutton({disabled:true});
                                $('#delete4').linkbutton({disabled:true});
                                $('#submit4').linkbutton({disabled:true});
                                $('#cancel4').linkbutton({disabled:true});
                            },
                            onSelect: function(index, row) {
                                $('#edit4').linkbutton({disabled:false});
                                $('#delete4').linkbutton({disabled:false});
                                $('#fm4').form('load',row);
                                $('#submit4').linkbutton({disabled:true});
                                $('#cancel4').linkbutton({disabled:true});

                                $('#fm').hide();
                                $('#fm2').hide();
                                $('#fm3').hide();
                                $('#fm4').show();
                            },
                            columns: [[
                                {field: "id", title: "ID", width: '40%', sortable: true},
                                {field: "name", title: "Nama", width: '60%', sortable: true},
                            ]],
                        })
                        ddv3.datagrid('enableFilter');
                        ddv2.datagrid('fixDetailRowHeight',index);
                    }
                })
                ddv2.datagrid('enableFilter');
                ddv.datagrid('fixDetailRowHeight',index);
            }
        })
        ddv.datagrid('enableFilter');
        $('#dg').datagrid('fixDetailRowHeight',index);
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

function addnew(){
    $('#id').textbox({
        disabled:false,
        readonly:false,
        width:180
    });
    $('#fm').form('clear');
    flag = "wilayah/save_data";

    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
}
function addnew2(){
    $('#id').textbox({
        disabled:false,
        readonly:false,
        width:180
    });
    $('#fm2').form('clear');
    flag = "wilayah2/save_data";

    $('#submit2').linkbutton({disabled:false});
    $('#cancel2').linkbutton({disabled:false});
}
function addnew3(){
    $('#id').textbox({
        disabled:false,
        readonly:false,
        width:180
    });
    $('#fm3').form('clear');
    flag = "wilayah3/save_data";

    $('#submit3').linkbutton({disabled:false});
    $('#cancel3').linkbutton({disabled:false});
}
function addnew4(){
    $('#id').textbox({
        disabled:false,
        readonly:false,
        width:180
    });
    $('#fm4').form('clear');
    flag = "wilayah4/save_data";

    $('#submit4').linkbutton({disabled:false});
    $('#cancel4').linkbutton({disabled:false});
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"wilayah/read_data/"+row.id,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#id').textbox({
                disabled:false,
                readonly:true,
                width:180
            });
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
            var data = $.parseJSON(result);
            $('#id').textbox({
                disabled:false,
                readonly:true,
                width:180
            });
            $('#province_id').textbox({
                disabled:false,
                readonly:true,
                width:180
            });
            $('#fm2').form('load',data.data);
            flag = "wilayah2/edit_data";
            $('#submit2').linkbutton({disabled:false});
            $('#cancel2').linkbutton({disabled:false});
        }
    });
}
function editData3(){
    let row = getRow3();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"wilayah3/read_data/"+row.id,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#id').textbox({
                disabled:false,
                readonly:true,
                width:180
            });
            $('#regency_id').textbox({
                disabled:false,
                readonly:true,
                width:180
            });
            $('#fm3').form('load',data.data);
            flag = "wilayah3/edit_data";
            $('#submit3').linkbutton({disabled:false});
            $('#cancel3').linkbutton({disabled:false});
        }
    });
}
function editData4(){
    let row = getRow4();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"wilayah4/read_data/"+row.id,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#id').textbox({
                disabled:false,
                readonly:true,
                width:180
            });
            $('#district_id').textbox({
                disabled:false,
                readonly:true,
                width:180
            });
            $('#fm4').form('load',data.data);
            flag = "wilayah4/edit_data";
            $('#submit4').linkbutton({disabled:false});
            $('#cancel4').linkbutton({disabled:false});
        }
    });
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

function deleteData3(){
    let row = getRow3();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"wilayah3/delete_data/"+row.id,function(result){
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

function deleteData4(){
    let row = getRow4();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"wilayah4/delete_data/"+row.id,function(result){
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
    // console.log(row);
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
function getRow3() {
    var row = ddv2.datagrid('getSelected');
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
        return null;
    }else{
        row.record = ddv2.datagrid("getRowIndex", row);
    }
    return row;
}
function getRow4() {
    var row = ddv3.datagrid('getSelected');
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
        return null;
    }else{
        row.record = ddv3.datagrid("getRowIndex", row);
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
                    // $('#dlg').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
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
                    // $('#dlg').dialog('close');        // close the dialog
                    ddv.datagrid('reload');    // reload the user data
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
function submit3(){
    console.log(flag)
    $('#fm3').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    // $('#dlg').dialog('close');        // close the dialog
                    ddv2.datagrid('reload');    // reload the user data
                    $('#fm3').form('clear');
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
function submit4(){
    console.log(flag)
    $('#fm4').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    // $('#dlg').dialog('close');        // close the dialog
                    ddv3.datagrid('reload');    // reload the user data
                    $('#fm4').form('clear');
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