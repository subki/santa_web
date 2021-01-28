$(document).ready(function () { 
    populateLocation(); 
});
var options={
    url: base_url+"Stockatk/load_grid", 
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true,
    sortName:"doc_date",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:'#toolbar',
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
  columns:[[
        {field:"docno",   title:"Nomor Trx",      width: '12%', sortable: true},
        {field:"doc_date",   title:"Tanggal",      width: '9%', sortable: true},
        {field:"outlet_code",   title:"Location Code",      width: '10%', sortable: true},
        {field:"description",   title:"Nama Location",      width: '15%', sortable: true},
        {field:"status",   title:"Status",      width: '7%', sortable: true},
        {field:"remark",   title:"Remark",      width: '13%', sortable: true},
        {field:"action", title:"Action",    width:"30%", formatter: function(value, row){
               var a = `<a href="#" onclick="editData('`+row.docno+`');" title="Edit" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-edit">&nbsp;</span></span>
                        </a>
                        <a href="#" onclick="addnew2();" title="Add Detail" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-add">&nbsp;</span></span>
                        </a>
                        <a href="#" onclick="deleteData('`+row.docno+`');" title="Delete" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-remove">&nbsp;</span></span>
                        </a>
                        <a href="#" onclick="closeAdj('`+row.docno+`');" title="Close Adjustment" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-ok">&nbsp;</span></span>
                        </a>
                        
                        `;
               var c = `<a href="#" onclick="Opendialog('`+row.docno+`');" title="Generate From Opname" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-search">&nbsp;</span></span>
                        </a>                       
                        `;
               var b = `<a href="#" onclick="printData();" title="Print" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-print">&nbsp;</span></span>
                        </a>                        
                        `;
                return (row.status==="CLOSED") ? b:a+b;
            }
        }
    ]],
    view: detailview,
    detailFormatter:function(index,row){
        return '<div style="padding:2px;position:relative;"><table class="ddv"></table></div>';
    },
    onExpandRow:function (index, row) {
        var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
        ddv.datagrid({
            url:base_url+"Stockatk/load_grid_detail/"+row.docno,
            method:'GET',
            pagePosition:"top",
            resizeHandle:"right",
            resizeEdge:10,
            pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true,
            sortName:"nobar",
            sortOrder:"asc",
            singleSelect:true,
            loadFilter: function(data){
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            height:'auto', nowrap:false,
            columns:[[ 
                { field: 'nobar',  title: 'Kode Barang',    width: '15%',  sortable: true},
                { field: 'nmbar',      title: 'Nama Barang',        width: '25%', sortable: true},
                // { field: 'unit_price',      title: 'Harga',        width: '10%', sortable: true, align:"right", formatter:numberFormat},
                { field: 'soh',      title: 'SOH',        width: '10%', sortable: true, align:"right", formatter:numberFormat},
                { field: 'adjust',      title: 'Adjustment',        width: '10%', sortable: true, align:"right", formatter:numberFormat},
                { field: 'keterangan',      title: 'Keterangan',        width: '18%', sortable: true},
                {field:"action", title:"Action",    width:"8%", formatter: function(value, rr){
                    var a = `<a href="#" onclick="deleteDataDetail('`+rr.id+`');" title="Delete" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-remove">&nbsp;</span></span>
                        </a>
                        `;
                    return (row.status==="CLOSED") ? '':a;
                }
                }
            ]],
            onResize:function(){
                $('#dg').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
                setTimeout(function(){
                    $('#dg').datagrid('fixDetailRowHeight',index);
                },500);
            }
        });
        // ddv.datagrid('enableFilter')
        $('#dg').datagrid('fixDetailRowHeight',index);
    },
    onLoadSuccess:function(){
        authbutton();
    },
};

setTimeout(function () {
    initGrid();
},500);
function numberFormat(x){
    return parseFloat(x).toLocaleString('en')
}
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}
function addnew(){
    clearFormInput();
    flag = "Stockatk/save_data_header";
        $('#modal_edit').dialog('open').dialog('center').dialog('setTitle','Add Adj'); 
    } 
 
function clearFormInput() {
    document.getElementById("form_editing").reset();
}
function clearFormInputDetail() {
    document.getElementById("form_editing_detail").reset();
    document.getElementById("form_stockadjopname").reset();
} 
function Opendialog(){
    let row = getRow(true);
    console.log(row)
    if(row===null) return;

    if(row.status==="OPEN") {
        //clearFormInputDetail();
        flag = "Stockatk/save_data_detail2"; 
        $("#docno_idopn").textbox('setValue',row.docno);
        $('#modal_adj_stockopname').dialog('open').dialog('center').dialog('setTitle','Adjustment Stock Opname'); 
    }else{
        alert("tidak bisa tambah detail, "+row.status)
    }
}
function submit(){
    //console.log(flag)
    var data = $('#form_editing').serialize();
      $.ajax({
          type: 'POST',
          dataType:"json",
          url: base_url+flag,
          data: data,
          success: function(result) {
                var res = result;
                // console.log(result);
                // console.log(res.status);
                if (res.status===1){
                    alert(res.msg)
                } else {
                    $('#dg').datagrid('reload');    //
                    clearFormInputDetail(); 
                    $('#modal_edit').dialog('close');  
                }
          }
        });
}
function submitadjopn(){
     console.log(flag)
    var data = $('#form_stockadjopname').serialize();
     $.ajax({
          type: 'POST',
          dataType:"json",
          url: base_url+flag,
          data: data,
          success: function(result) {
                var res = result;
                console.log(result);
                console.log(res.status);
                if (res.status===1){
                    alert(res.msg)
                } else {
                    $('#dg').datagrid('reload');  
                    $('#modal_adj_stockopname').dialog('close');   //
                    clearFormInputDetail();
                }
          }
        });
      
}
function populateLocation() {
    $('#location_code').combogrid({
        idField: 'location_code',
        textField:'location_name',
        url:base_url+"stock/get_location",
        required:true,
        labelPosition:'top',
        tipPosition:'bottom',
        hasDownArrow: false,
        remoteFilter:true,
        panelWidth: 500,
        multiple:false,
        panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
            mousedown: function(){}
        }),
        editable: false,
        pagination: true,
        fitColumns: true,
        mode:'remote',
        prompt:'-Please Select-',
        loadFilter: function (data) {
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        columns: [[
            {field:'location_code',title:'Location Code',width:150},
            {field:'location_name',title:'Location Name',width:250},
        ]]
    });
    $('#location_code').combogrid('grid').datagrid('enableFilter');
}
function getRow(show) {
    var row = $('#dg').datagrid('getSelected');
    var rowIndex = $("#dg").datagrid("getRowIndex", row);
    if (!row){
        if(show) {
            alert('Please select data to edit');
        }
        return null;
    }
    row.index = rowIndex;
    return row;
}

function deleteData(id){
          myConfirm("Confirmation", "Anda yakin akan menghapus data ini ?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                   $.post(
                        base_url+"Stockatk/delete_data_header/"+id,function(result){
                            var res = $.parseJSON(result);
                            if (res.status===1){
                                alert(res.msg)
                            } else {
                                $('#dg').datagrid('reload');    // reload the user data
                                clearFormInput();
                            }
                        }
                    );
            }
        }) 
}
function deleteDataDetail(id){
          myConfirm("Confirmation", "Anda yakin akan menghapus data ini ?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                   $.post(
                        base_url+"Stockatk/delete_data_detail/"+id,function(result){
                            var res = $.parseJSON(result);
                            if (res.status===1){
                                alert(res.msg)
                            } else {
                            var selectedrow = $("#dg").datagrid("getSelected");
                            var rowIndex = $("#dg").datagrid("getRowIndex", selectedrow);
                            $('#dg').datagrid('refreshRow',rowIndex).datagrid('collapseRow',rowIndex).datagrid('expandRow',rowIndex);
                            }
                        }
                    );
            }
        }) 
} 
function closeAdj(id){ 
          myConfirm("Confirmation", "Anda yakin akan close dan adjust stock ?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                   $.post(
                        base_url+"Stockatk/adjustclose/"+id,function(result){
                            var res = $.parseJSON(result);
                                if (res.status===1){
                                    alert(res.msg)
                                } else {
                                    $('#dg').datagrid('reload');    // reload the user data
                                    clearFormInput();
                                }
                        }
                    );
            }
        }) 
} 

function printData(){
    let row = getRow(true);
    if(row===null) return;
    let urlss = `${base_url}Stockatk/report_adjustment/${row.docno}`;
    window.open(urlss, '_blank')
}
function editData(docno){
    $.ajax({
        type:"POST",
        url:base_url+"Stockatk/read_data_header/"+docno,
        dataType:"html",
        success:function(result){
            flag = "Stockatk/edit_data_header";
            // clearFormInput();
          //  console.log(result);
            var data = $.parseJSON(result);
            $("#docno").textbox('setValue',data.data.docno);
            //$("#location_code").combogrid({readonly:true}); 
            $("#location_code").combogrid('setValue', data.data.description);    
            $("#doc_date").datebox('setValue',data.data.doc_date); 
            $("#remark").textbox('setValue', data.data.remark); 
 
            $('#modal_edit').dialog('open').dialog('center').dialog('setTitle','Edit Adjustment'); 

        }
    });
}
function addnew2(){
    let row = getRow(true);
    console.log(row)
    if(row===null) return;

    if(row.status==="OPEN") {
         clearFormInputDetail();
        flag = "Stockatk/save_data_detail"; 
        $("#docno_id").textbox('setValue',row.docno);
        $('#modal_edit_detail').dialog('open').dialog('center').dialog('setTitle','Add Detail Adj'); 
    }else{
        alert("tidak bisa tambah detail, "+row.status)
    }
}
function addnewsku() {
    let row = getRow(true);
    console.log(row)
    if(row===null) return; 
    $('#modal_edit_detail_sku').dialog('open').dialog('center').dialog('setTitle',' Form Data'); 
        $('#tt_sku').datagrid({ 
            url:base_url+"Stockatk/load_gridstock/"+row.outlet_code+"/"+row.periode,
            method:"POST",
            pagePosition:"top",
            resizeHandle:"right",
            resizeEdge:10,
            pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true, striped:true, nowrap:false,
            sortName:"nobar",
            sortOrder:"asc",
            singleSelect:true,
            toolbar:'#toolbar1',
            loadFilter: function(data){
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            columns:[[
                { field: 'nobar',      title: 'Kode Barang',        width: '18%', sortable: true},
                { field: 'nmbar',      title: 'Nama Barang',        width: '50%', sortable: true},
                { field: 'saldo_akhir',      title: 'Saldo',    formatter:numberFormat,    width: '14%', sortable: true}, 
            ]],
            onLoadSuccess:function(){
                authbutton();
            },
            onClickCell:function(index, field, value){
                var rr =  $('#tt_sku').datagrid('getRows')[index];
                console.log(rr)
                $("#sku").textbox('setValue',rr.nmbar);
                $("#skucode").textbox('setValue',rr.nobar);
                $("#soh").textbox('setValue',rr.saldo_akhir); 
                $('#modal_edit_detail_sku').dialog('close');  
            }
        }); 
    $('#tt_sku').datagrid('enableFilter'); 
    $('#tt_sku').datagrid('destroyFilter');
    $('#tt_sku').datagrid('enableFilter');
}
function submit_detail() {
    console.log(flag)
    let row = getRow(true);
    if(row===null) return;
     var adj = $("#adjust").numberbox('getValue');
     var sku = $("#sku").numberbox('getValue'); 
    if(adj===""){
        alert('Please input Qty');
        return
    }
    if(sku===""){
        alert('Please Pilih Nama Barang');
        return
    }
     var data = $('#form_editing_detail').serialize();
      $.ajax({
          type: 'POST',
          dataType:"json",
          url: base_url+flag,
          data: data,
          success: function(result) {
                 var res = result;
                console.log(result);
                console.log(res.status);
                if (res.status===1){
                    alert(res.msg)
                } else {
                    var selectedrow = $("#dg").datagrid("getSelected");
                    var rowIndex = $("#dg").datagrid("getRowIndex", selectedrow);
                    $('#dg').datagrid('refreshRow',rowIndex).datagrid('collapseRow',rowIndex).datagrid('expandRow',rowIndex); 
                    $('#modal_edit_detail').dialog('close'); 
                    //clearFormInputDetail();
                }
          }
        }); 
}
 

function addOpn() {   
    let row = getRow(true);
    console.log(row)
    if(row===null) return; 

    $('#modal_detailOpname').dialog('open').dialog('refresh').dialog('center').dialog('setTitle',' Form Data');  
    //             $("#skuopn").textbox('setValue',"");
    //             $("#skucodeopn").textbox('setValue',"");
    //             $("#sohopn").textbox('setValue',"");  
    //             $("#adjustopn").textbox('setValue',""); 
    //             $("#remarkopn").textbox('setValue',""); 
        $('#tt_opn').datagrid({ 
            url:base_url+"Stockatk/load_gridopname/"+row.outlet_code,
            method:"POST", 
            fitColumns: true,
            pagePosition:"top",
            resizeHandle:"right",
            resizeEdge:10,
            pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true, striped:true, nowrap:false,
            sortName:"nobar",
            sortOrder:"asc",
            singleSelect:true,
            toolbar:'#toolbar1',
            loadFilter: function(data){
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            columns:[[
                { field: 'trx_no',      title: 'Nomor Opname',        width: '18%', sortable: true},
                { field: 'item',      title: 'Kode Barang',        width: '18%', sortable: true},
                { field: 'product_code',      title: 'Nama Barang',        width: '30%', sortable: true},
                { field: 'QTYStock',      title: 'Qty Stock',        width: '10%', sortable: true},
                { field: 'QTYScan',      title: 'Qty Scan',        width: '10%', sortable: true},
                { field: 'Selisih',      title: 'Variant',    formatter:numberFormat,    width: '10%', sortable: true}, 
            ]], 
            onClickCell:function(index, field, value){
                var rr =  $('#tt_opn').datagrid('getRows')[index];
                console.log(rr)
                $("#skuopn").textbox('setValue',rr.product_code);
                $("#skucodeopn").textbox('setValue',rr.item);
                $("#sohopn").textbox('setValue',rr.QTYStock);  
                $("#remarkopn").textbox('setValue',rr.trx_no);  

               // let resulty =  Math.abs(rr.Selisih); 
                $("#adjustopn").textbox('setValue',rr.Selisih);  
                $('#modal_detailOpname').dialog('close');  
            },
            onSuccess: function (index, row) {
                if (row.status === 1) {
                    $.messager.show({    // show error message
                        title: 'Error',
                        msg: row.msg
                    });
                }
                $('#tt_opn').edatagrid('reload');   
            },
            onError: function (index, e) {
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            }
        }); 
    $('#tt_opn').datagrid('enableFilter'); 
    $('#tt_opn').datagrid('destroyFilter');
    $('#tt_opn').datagrid('enableFilter');
    $('#modal_detailOpname').dialog('open').dialog('refresh').dialog('center').dialog('setTitle',' Form Data');  
}