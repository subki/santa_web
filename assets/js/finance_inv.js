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

$(document).ready(function() {
    // showData()
});

function initGrid(jenis, prd) {
    $('#dg').datagrid(options);
    $('#dg').datagrid('destroyFilter');
    $('#dg').datagrid('enableFilter');
    $('#dg').datagrid('addFilterRule', {field: 'jenis_faktur',op: 'equal',value: jenis});
    $('#dg').datagrid('addFilterRule', {field: 'doc_date',op: 'beginwith',value: prd});
    $('#dg').datagrid('addFilterRule', {field: 'statushd',op: 'equal',value: 'CLOSED'});
    $('#dg').datagrid('addFilterRule', {field: 'verifikasi_finance',op: 'equal',value: 'VERIFIED'});
    $('#dg').datagrid('addFilterRule', {field: 'sales_invoice_id',op: 'greater',value: 0});
    $('#dg').datagrid('doFilter');
}
function proformaInvoice(){
    myConfirm("Confirm","Are you sure to create proforma invoice?","Yes","No", function (res) {
        if(res==="Yes"){
            var dt = $('#dg').datagrid('getSelections');
            var ids = [];
            var tot_inv = 0;
            var customer_code = "";
            for(var i=0; i<dt.length; i++){
                if(customer_code!=="" && customer_code!==dt[i].customer_code){
                    customer_code = "XXX";
                    break;
                }
                if(dt[i].proforma_no!==""){
                    customer_code = "XXX-1";
                    break;
                }
                ids.push(dt[i].id)
                tot_inv += dt[i].sales_after_tax;
                customer_code = dt[i].customer_code;
            }
            if(customer_code==="XXX"){
                $.messager.alert("Warning", "Proforma Invoice hanya bisa di tujukan kepada 1 Customer.")
                return
            }
            if(customer_code==="XXX-1"){
                $.messager.alert("Warning", "Sales Invoice sudah ada proforma.")
                return
            }
            $.ajax({
                type:"POST",
                url:base_url+"finance/create_proforma",
                dataType:"json",
                data:{
                    data:ids,
                    total_invoice:tot_inv
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
    })
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