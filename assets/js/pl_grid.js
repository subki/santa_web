var options={
    url: base_url+"packinglist/load_grid",
    title:"Packing List",
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
        {field:"docno",   title:"Trx No",      sortable: true},
        {field:"doc_date",   title:"Trx Date",      sortable: true, formatter:function (index, row) {
            return row.ak_doc_date;
        }},
        {field:"so_number",   title:"Base SO",       sortable: true},
        {field:"tgl_so",   title:"Tgl SO",       sortable: true, formatter:function (index, row) {
            return row.ak_tgl_so;
        }},
        {field:"status",   title:"Status",       sortable: true, formatter:function (index, row) {
            return (row.status==="POSTING")?"Ready to Post":row.status;
        }},
        {field:"crtby",   title:"Create By",      sortable: true},
        {field:"crtdt",   title:"Create Date",      sortable: true},
        {field:"updby",   title:"Update By",      sortable: true},
        {field:"upddt",   title:"Update Date",      sortable: true},
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
    window.location.href = base_url+"packinglist/form/edit?docno="+row.docno
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