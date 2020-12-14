var options={
    title:"List Data",
    method:"POST",
    url : base_url+"fa/ap/grid",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"payment_date",
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
        {field:"trx_type",   title:"Tipe",      width:0, sortable: true, formatter:function (index,row) {
          if(parseInt(row.trx_type)===1){
              return "S";
          }else if(parseInt(row.trx_type)===2){
              return "M"
          }else return " ";
				}},
        {field:"docno",   title:"No Trx",      width:120, sortable: true},
        {field:"store_code",   title:"Store",      width: 100, sortable: true},
        {field:"customer_code",   title:"Cust/Vendor",      width: 120, sortable: true},
        {field:"payment_amount",   title:"Amount",      width: 300, sortable: true},
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
    showData()
});

function initGrid(tahun, bulan) {
    $('#dg').datagrid(options);
    $('#dg').datagrid('destroyFilter');
    $('#dg').datagrid('enableFilter');
    $('#dg').datagrid('addFilterRule', {field: 'payment_type', op: 'equal', value: 'AP PAYMENT' });
    $('#dg').datagrid('addFilterRule', {field: 'tahun',op: 'equal',value: tahun});
    $('#dg').datagrid('addFilterRule', {field: 'bulan',op: 'equal',value: bulan});
    $('#dg').datagrid('doFilter');
}
function showData(){
    var tahun = $("#tahun").val()
    var bln = $("#bulan").val();
    if(tahun==="" || bln===""){
        $.messager.alert("Alert","Filter harus diisi.")
        return
    }
    bln = bln.padStart(2,'0')
    initGrid(tahun,bln)
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
function addData() {
    window.location.href = base_url+"fa/ap/index/add"
}
function editData() {
    var r = getRow();
    if(r===null) return;
    window.location.href = base_url+"fa/ap/index/edit?id="+r.id;
}