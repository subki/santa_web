var options={
    title:"List Data",
    method:"POST",
    url : base_url+"wholesales/load_grid",
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
    toolbar:"#toolbar",
    singleSelect:false,
    multiple:true,
    // ctrlSelect:true,
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
var options2={
    title:"List Data",
    method:"POST",
    url : base_url+"wholesales/load_grid",
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
    toolbar:"#toolbar2",
    singleSelect:false,
    multiple:true,
    // ctrlSelect:true,
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

$(document).ready(function() {
    // showData()
});

function initGrid(jenis, prd) {
    $('#dg').datagrid(options);
    $('#dg').datagrid('destroyFilter');
    $('#dg').datagrid('enableFilter');
    $('#dg').datagrid('addFilterRule', {field: 'jenis_faktur', op: 'equal', value: jenis });
    $('#dg').datagrid('addFilterRule', {field: 'doc_date',op: 'beginwith',value: prd});
    $('#dg').datagrid('addFilterRule', {field: 'statushd',op: 'equal',value: 'CLOSED'});
    $('#dg').datagrid('addFilterRule', {field: 'verifikasi_finance',op: 'equal',value: ''});
    // $('#dg').datagrid('addFilterRule', {field: 'sales_invoice_id',op: 'equal',value: 0});
    $('#dg').datagrid('doFilter');

    $('#dg2').datagrid(options2);
    $('#dg2').datagrid('destroyFilter');
    $('#dg2').datagrid('enableFilter');
    $('#dg2').datagrid('addFilterRule', {field: 'jenis_faktur',op: 'equal',value: jenis});
    $('#dg2').datagrid('addFilterRule', {field: 'doc_date',op: 'beginwith',value: prd});
    $('#dg2').datagrid('addFilterRule', {field: 'statushd',op: 'equal',value: 'CLOSED'});
    $('#dg2').datagrid('addFilterRule', {field: 'verifikasi_finance',op: 'equal',value: 'VERIFIED'});
    $('#dg2').datagrid('addFilterRule', {field: 'sales_invoice_id',op: 'greater',value: 0});
    $('#dg2').datagrid('doFilter');
}
function showData(){
    var jenis = $("#jenis").val()
    var tahun = $("#tahun").val()
    var bln = $("#bulan").val();
    if(jenis==="" || tahun==="" || bln===""){
        $.messager.alert("Alert","Filter harus diisi.")
        return
    }
    bln = bln.padStart(2,'0')
    initGrid(jenis, tahun+"-"+bln)
}
function postingWSSelected(){
    var dt = $('#dg').datagrid('getSelections');
    var ids = [];
    for(var i=0; i<dt.length; i++){
        ids.push(dt[i].id)
    }
    $.ajax({
        type:"POST",
        url:base_url+"finance/update_finance_verify",
        dataType:"json",
        data:{
            data:ids
        },
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                $("#dg").datagrid('reload')
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"finance";
                    }
                });
            }

        }
    });
}

function unpostingWSSelected(){
    var dt = $('#dg2').datagrid('getSelections');
    var ids = [];
    for(var i=0; i<dt.length; i++){
        ids.push(dt[i].id)
    }
    $.ajax({
        type:"POST",
        url:base_url+"finance/update_finance_verify_unposting",
        dataType:"json",
        data:{
            data:ids
        },
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                $("#dg2").datagrid('reload')
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"finance";
                    }
                });
            }

        }
    });
}
function viewData(){
    let row = getRow();
    if(row==null) return
    var jenis = $("#jenis").val()
    if(jenis==="WHOLESALES") window.open(base_url+"wholesales/form/view?id="+row.id,'_blank')
    else if(jenis==="SHOWROOM") window.open(base_url+"wholesales/form/view?id="+row.id,'_blank')
    else if(jenis==="SALES ONLINE") window.open(base_url+"wholesales/form/view?id="+row.id,'_blank')
    else if(jenis==="OUTLET") window.open(base_url+"wholesales/form/view?id="+row.id,'_blank')
    else $.messager.alert('Error','Please select the data')
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