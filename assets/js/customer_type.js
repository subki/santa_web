var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"customertype/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"code",
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
        id:'detail',
        iconCls: 'icon-product',
        text:'Info Product',
        handler: function(){
            openProduct();
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
        {field:"code",   title:"Kode",      width: 80, sortable: true},
        {field:"description",   title:"Deskripsi",      width: 200, sortable: true},
        {field:"pkp",   title:"PKP",      width: 60, sortable: true},
        // {field:"auto_create",   title:"Auto Create Location",      width: 100, sortable: true},
        {field:"diskon",   title:"Discount",      width: 100, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        $('#detail').linkbutton({disabled:true});
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        disable_enable(true);
			authbutton();
    },
    onSelect: function(index, row) {
        $('#detail').linkbutton({disabled:false});

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
    disable_enable(false)
    // $('#code').textbox({
    //     disabled:true,
    //     readonly:false,
    //     width:'100%'
    // });
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "customertype/save_data";
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"customertype/read_data/"+row.code,
        dataType:"html",
        success:function(result){
            disable_enable(false)
            var data = $.parseJSON(result);
            $('#code').textbox({
                disabled:false,
                readonly:true,
                width:'100%'
            });
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "customertype/edit_data";
        }
    });
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"customertype/delete_data/"+row.code,function(result){
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
function openProduct(){
    let row = getRow();
    if(row==null) return;
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Product from : ${row.description}`);
    $('#tt').datagrid({
        fitColumns:true,
        width:"100%",
        height:"100%",
        url: base_url+"customertype/get_products/"+row.code,
        method:"POST",
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:false,
        sortName:"product_code",
        sortOrder:"asc",
        singleSelect:true,
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
            {field:"product_code",   title:"Kode Produk",      width: '20%', sortable: true},
            {field:"product_name",   title:"Nama Produk",      width: '30%', sortable: true},
            {field:"effective",   title:"Effective",      width: '30%', sortable: true},
            net_price(row),
        ]],
    })
}
function net_price(row) {
    return {field:row.pkp==="Include"?"price_pkp":"price_non_pkp",   title:"Harga_NP",      width: '25%', sortable: true, formatter:numberFormat}
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