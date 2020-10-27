var options={
    url: base_url+"faktur/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"seqno",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            disable_enable(false)
            addnew()
        }
    },
    //     {
    //     id:'edit',
    //     iconCls: 'icon-edit',
    //     text:'Edit',
    //     handler: function(){
    //         disable_enable(false)
    //         editData()
    //     }
    // },
        {
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            deleteData()
        }
    },
    //     {
    //     id:'approve',
    //     iconCls: 'icon-ok',
    //     text:'Approve/Draft',
    //     handler: function(){
    //         ApproveData()
    //     }
    // },
        {
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("dg", function (x, x1, x2) {
                let urlss = base_url+"faktur/export_data?field="+x+"&op="+x1+"&value="+x2;
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
        {field:"id",   title:"ID",      width: 70, sortable: true},
        {field:"seqno",   title:"Nomor",      width: 200, sortable: true},
        {field:"periode",   title:"Periode",      width: 90, sortable: true},
        {field:"inuse",   title:"In Use", formatter:function (index, row) {
            if(row.inuse==="0"){
                return "No"
            }else return "Yes"
        },     width: 50, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        disable_enable(true);
        authbutton();
    },
    onSelect: function(index, row) {
        // $('#fm').form('load',row);
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
    // $('#fm').form('clear');
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});
    disable_enable(true)
    $('#fm').form('clear');
}

function addnew(){
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "faktur/save_data";
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"faktur/delete_data/"+row.id,function(result){
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
                if (res.status === 0) {
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