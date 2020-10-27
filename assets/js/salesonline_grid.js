var options={
    url: base_url+"Salesonline/load_grid",
    title:"Daily Sales Online",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"docno",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:"#toolbar",
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"tgl_so",   title:"Trx Date",      width: 100, sortable: true, formatter:function (index, row) {
            return row.tgl_so;
        }},
        {field:"docno",   title:"Sales No#",      width:200, sortable: true}, 
        {field:"customer_code",   title:"Cust#",      width: 100, sortable: true},
        {field:"tgl_pickup",   title:"Pickup Date",      width: 100, sortable: true, formatter:function (index, row) {
            return row.tgl_pickup;
        }},
        {field:"customer_name",   title:"Nama Pembeli",        width:200, sortable: true, formatter:function (index, row) {
            return row.customer_name;
        }},
        {field:"status",   title:"Status",      width: 100, sortable: true, formatter:function (index, row) {
            return (row.status==="POSTING")?"Ready to Post":row.status;
        }} 
    ]],
    onLoadSuccess:function(){
        authbutton();
    },
};

setTimeout(function () {
    initGrid();
},500);

function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}

function editData(){
    let row = getRow();
    if(row==null) return
    window.location.href = base_url+"Salesonline/form/edit?docno="+row.docno
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"productbrand/delete_data/"+row.brand_code,function(result){
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