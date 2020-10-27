var options={
    url: base_url+"productsize/load_grid",
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
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            disable_enable(false)
            addnew()
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            disable_enable(false)
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
                let urlss = base_url+"productsize/export_data?field="+x+"&op="+x1+"&value="+x2;
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
        {field:"size_code",   title:"Kode",      width: 70, sortable: true},
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
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        disable_enable(true);
				authbutton();
    },
    onSelect: function(index, row) {

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
}

function addnew(){
    $('#size_code').textbox({
        disabled:false,
        readonly:false,
        width:'100%'
    });
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "productsize/save_data";
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
        url:base_url+"productsize/read_data/"+row.size_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#size_code').textbox({
                disabled:false,
                readonly:true,
                width:'100%'
            });
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "productsize/edit_data";
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
                url:base_url+"productsize/edit_data_status/"+row.size_code+"/"+par,
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
                base_url+"productsize/delete_data/"+row.size_code,function(result){
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
                    // $('#dlg').dialog('close');        // close the dialog
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