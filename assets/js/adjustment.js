var timer=null;
$(document).ready(function () {
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
    });

    setTimeout(function () {
        initGrid();
        populateOutlet()
    },500);

});
//
// function populateStatus(){
//
//     var $el 	= $("#status");
//
//     $el.empty();
//
//     $el.append($("<option></option>")
//         .attr("value", '').text('- Pilih -'));
//
//     $el.append($("<option></option>")
//         .attr("value", "OPEN").text("Nota Sementara"));
//     $el.append($("<option></option>")
//         .attr("value", "Nota Terbayar").text("Nota Terbayar"));
//     $el.append($("<option></option>")
//         .attr("value", "Nota Batal").text("Nota Batal"));
//
//     $("#status").select2({
//         dropdownParent: $("#modal_edit")
//     });
// }
var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"stockadj/load_grid",
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
        {field:"outlet_code",   title:"Outlet",      width: '6%', sortable: true},
        {field:"outlet_name",   title:"Nama Outlet",      width: '30%', sortable: true},
        {field:"status",   title:"Status",      width: '7%', sortable: true},
        {field:"remark",   title:"Remark",      width: '13%', sortable: true},
        {field:"action", title:"Action",    width:"18%", formatter: function(value, row){
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
                        <a href="#" onclick="closeAdj();" title="Close Adjustment" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-ok">&nbsp;</span></span>
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
            url:base_url+"stockadj/load_grid_detail/"+row.docno,
            method:'GET',
            pagePosition:"top",
            resizeHandle:"right",
            resizeEdge:10,
            pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true,
            sortName:"sku",
            sortOrder:"asc",
            singleSelect:true,
            loadFilter: function(data){
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            height:'auto', nowrap:false,
            columns:[[
                { field: 'sku',  title: 'SKU',    width: '12%',  sortable: true},
                { field: 'product_code',  title: 'Kode Barang',    width: '15%',  sortable: true},
                { field: 'article_name',      title: 'Nama Barang',        width: '25%', sortable: true},
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
    }
};
function numberFormat(x){
    return parseFloat(x).toLocaleString('en')
}

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('destroyFilter');
    $('#dg').datagrid('enableFilter');
}
function populateOutlet() {
    var $el 	= $("#outlet_code");
    $el.empty();
    $el.append($("<option></option>")
        .attr("value", '').text('- Pilih -'));
    $.ajax({
        type: "POST",
        url: base_url+'stock/get_location',
        dataType: 'json',
        success: function(json) {
            console.log(json);
            $.each(json, function(index, value) {
                $el.append($("<option></option>")
                    .attr("value", value['location_code']).text(value['location_code']+' - '+value['location_name']));
            });
            $("#outlet_code").select2();
        }
    });
}
function submit_detail() {
    let row = getRow(true);
    if(row===null) return;
    let adj = $("#adjust").val();
    let sku = $("#sku").val();
    if(adj===""){
        alert('Please input Qty');
        return
    }
    if(sku===""){
        alert('Please input SKU');
        return
    }
    $("#form_editing_detail").ajaxSubmit({
        url: base_url+flag,
        type: 'post',
        success: function(result){
            var res = result;
            console.log(result);
            console.log(res.status);
            if (res.status===1){
                alert(res.msg)
            } else {
                var selectedrow = $("#dg").datagrid("getSelected");
                var rowIndex = $("#dg").datagrid("getRowIndex", selectedrow);
                $('#dg').datagrid('refreshRow',rowIndex).datagrid('collapseRow',rowIndex).datagrid('expandRow',rowIndex);
                $('#modal_edit_detail').modal('toggle');
                clearFormInputDetail();
            }
        }
    });
}

function addnew(){
    clearFormInput();
    flag = "stockadj/save_data_header";
    $('#modal_edit').modal('show');
}
function addnew2(){
    let row = getRow(true);
    if(row===null) return;

    if(row.status==="OPEN") {
        clearFormInputDetail();
        flag = "stockadj/save_data_detail";
        $("input[name='docno']").val(row.docno);
        $('#modal_edit_detail').modal('show')
    }else{
        alert("tidak bisa tambah detail, "+row.status)
    }
}

function addnewsku() {
    let row = getRow(true);
    if(row===null) return;
    $('#modal_edit_detail_sku').on('shown.bs.modal', function () {
        $('#tt_sku').datagrid({
            url:base_url+`stock/load_grid3`,
            fitColumns:true,
            width:"100%",
            method:"GET",
            pagePosition:"top",
            resizeHandle:"right",
            resizeEdge:10,
            pageSize:20,
            sortName:"article_name",
            sortOrder:"asc",
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true,
            singleSelect:true,
            loadFilter: function(data){
                if (data.data) data.rows = data.data;
                return data;
            },
            height:'auto',
            columns:[[
                { field: 'sku',  title: 'SKU',    width: '18%',  sortable: true},
                { field: 'product_code',      title: 'Kode Barang',        width: '18%', sortable: true},
                { field: 'article_name',      title: 'Nama Barang',        width: '22%', sortable: true},
                { field: 'saldo_akhir',      title: 'Saldo',    formatter:numberFormat,    width: '14%', sortable: true},
                { field: 'unit_price',      title: 'Price',    formatter:numberFormat,      width: '14%', sortable: true},
            ]],
            onClickCell:function(index, field, value){
                var rr =  $('#tt_sku').datagrid('getRows')[index];
                $("#sku").val(rr.sku);
                $("#soh").val(rr.saldo_akhir);
                $('#modal_edit_detail_sku').modal('toggle');
            }
        });
        $('#tt_sku').datagrid('destroyFilter')
        $('#tt_sku').datagrid('enableFilter')
        $('#tt_sku').datagrid('addFilterRule',
            {
                field: 'periode',
                op: 'equal',
                value: row.periode
            }
        );
        $('#tt_sku').datagrid('addFilterRule',
            {
                field: 'outlet_code',
                op: 'equal',
                value: row.outlet_code
            }
        );
        $('#tt_sku').datagrid('doFilter');
    });
    $('#modal_edit_detail_sku').modal('show');
}

function editData(docno){
    $.ajax({
        type:"POST",
        url:base_url+"stockadj/read_data_header/"+docno,
        dataType:"html",
        success:function(result){
            flag = "stockadj/edit_data_header";
            clearFormInput();
            console.log(result);
            var data = $.parseJSON(result);
            $("input[name='docno']").val(data.data.docno);
            $("#outlet_code").val(data.data.outlet_code).trigger("change");
            $("input[name='doc_date']").val(data.data.doc_date);
            $("input[name='periode']").val(data.data.periode);
            $("input[name='remark']").val(data.data.remark);
            // $("#status").val(data.data.status).trigger("change");
            $('#modal_edit').modal('show');

        }
    });
}

function deleteData(id){
    bootbox.confirm("Anda yakin akan menghapus data ini ?",
        function(result){
            if(result==true){

                $.post(
                    base_url+"stockadj/delete_data_header/"+id,function(result){
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
        }
    );
}
function deleteDataDetail(id){
    bootbox.confirm("Anda yakin akan menghapus data ini ?",
        function(result){
            if(result==true){

                $.post(
                    base_url+"stockadj/delete_data_detail/"+id,function(result){
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
        }
    );
}
function closeAdj(){
    let row = getRow(true);
    if(row===null) return;
    bootbox.confirm("Anda yakin akan close dan adjust stock ?",
        function(result){
            if(result==true){

                $.post(
                    base_url+"stockadj/adjustclose/"+row.docno,function(result){
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
        }
    );
}
function printData(){
    let row = getRow(true);
    if(row===null) return;
    let urlss = `${base_url}stockadj/report_adjustment/${row.docno}`;
    window.open(urlss, '_blank')
}
function submit(){
    console.log(flag)
    $("#form_editing").ajaxSubmit({
        url: base_url+flag,
        type: 'post',
        success: function(result){
            var res = result;
            console.log(result);
            console.log(res.status);
            if (res.status===1){
                alert(res.msg)
            } else {
                $('#dg').datagrid('reload');    // reload the user data
                $('#modal_edit').modal('toggle');
                clearFormInput();
            }
        }
    });
}

function clearFormInput() {
    document.getElementById("form_editing").reset();
}
function clearFormInputDetail() {
    document.getElementById("form_editing_detail").reset();
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