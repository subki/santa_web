var options={
    url: base_url+"wsconfirm/load_grid",
    title:"Wholesales Confirmation",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"no_faktur",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:"#toolbar",
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"no_faktur",   title:"No Faktur",      width:120, sortable: true},
        // {field:"seri_pajak",   title:"Seri Pajak",      width: 100, sortable: true},
        {field:"doc_date",   title:"Trx Date",      width: 100, sortable: true, formatter:function (index, row) {
            return row.ak_doc_date;
        }},
        // {field:"base_so",   title:"Base SO",      width: 120, sortable: true},
        // {field:"remark",   title:"Remark",      width: 300, sortable: true},
        // {field:"status",   title:"Status",      width: 100, sortable: true},
        {field:"customer_name",   title:"Customer",      width: 280, sortable: true},
        {field:"ket",   title:"Remark",      width: 190, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 160, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 160, sortable: true},
    ]],
    onLoadSuccess:function(){
        authbutton();
    },
};

setTimeout(function () {
    initGrid();
},500);

function confirmWS() {
    let row = getRow();
    if(row==null) return
    $.messager.prompt({
        title: 'Confirm Remark',
        msg: 'Input remark for confirmation wholesales : '+row.no_faktur,
        fn: function (r) {
            if (r) {
                submit(r, row);
            }
        }
    });
}

function submit(ket, row) {
    $.ajax({
        type:"POST",
        url:base_url+"wsconfirm/save_data",
        dataType:"json",
        data:{
            sales_trans_header_id:row.id,
            keterangan:ket
        },
        success:function(result){
            if(result.status===0) {
                //reload
                $('#dg').datagrid('reload');
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: result.message,
                    handler:function () {
                        window.location.href = base_url+"Wsconfirm";
                    }
                });
            }

        }
    });
}

function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
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