var options={
    url: base_url+"wholesales/load_grid",
    title:"Wholesales",
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
        {field:"seri_pajak",   title:"Seri Pajak",      width: 100, sortable: true},
        {field:"doc_date",   title:"Trx Date",      width: 100, sortable: true, formatter:function (index, row) {
            return row.ak_doc_date;
        }},
        {field:"base_so",   title:"Base SO",      width: 120, sortable: true},
        {field:"remark",   title:"Remark",      width: 300, sortable: true},
        {field:"status",   title:"Status",      width: 100, sortable: true},
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

function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}

function editData(){
    let row = getRow();
    if(row==null) return
    // console.log(row)
    window.location.href = base_url+"wholesales/form/edit?id="+row.id
}
function exportData() {
    getParamOption("dg", function (x, x1, x2) {
        if(x.length>0) {
            x += ",pkp";
            x1 += ",equal";
            x2 += ",YES";
        }else{
            x += "pkp";
            x1 += "equal";
            x2 += "YES";
        }
        let urlss = base_url+"wholesales/export_data?field="+x+"&op="+x1+"&value="+x2;
        window.open(urlss, '_blank')
    })
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