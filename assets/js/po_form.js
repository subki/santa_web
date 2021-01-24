var so_item=undefined;
var flag = "";
$(document).ready(function () {
    so_item = undefined;
 
    populateSupplier();   

    if(aksi==="add"){
        flag = "Purchaseorder/save_data_header";
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
        // $("#doc_date").datebox('setValue', tgl);
        // $("#doc_date").datebox('setText', tgl);

        $("#new").hide(); 
        $("#store_code").combogrid('setValue',store_code);
        $("#location_code").combogrid('setValue',location_code);
        $("#status_po").textbox('setValue','Open');
        $("#jumlah_print").textbox('setValue','0');
        $("#update").hide();
        $("#posting").hide();
        $("#cancel").hide();
        $("#print").hide();
    }else{
        flag = "Purchaseorder/edit_data_header";
        $.ajax({
            type:"POST",
            url:base_url+"Purchaseorder/read_data/"+docno,
            dataType:"json",
            success:function(result){ 
             if(result.total >=1){  
                $("#update").hide();
             }
                if(result.status===0) {
                    $('#fm').form('load',result.data); 
                    so_item = result.data;
                    initHeader();
                }
                else {
                    $.messager.show({
                        title: 'Error',
                        msg: e.message,
                        handler:function () {
                            window.location.href = base_url+"Purchaseorder";
                        }
                    });
                }

            }
        });
    }

    $('#province_name').combogrid({"readonly":true});
    $('#regency_name').combogrid({"readonly":true});
});
function initHeader() {
    $('#province_name').combogrid({"readonly":true});
    $('#regency_name').combogrid({"readonly":true});
    $('#regency_id').textbox('setValue',so_item.regency_id)
    $('#provinsi_id').textbox('setValue',so_item.provinsi_id)
    $('#regency_name').combogrid('setValue',so_item.regency)
    $('#provinsi_name').combogrid('setValue',so_item.provinsi)
    $('#supplier_code').combogrid('setValue',so_item.supplier_id) 
    $('#tot_item').textbox('setValue', numberFormat(so_item.tot_item)) 
    $('#tot_qty_order').textbox('setValue', numberFormat(so_item.tot_qty_order)) 
    $('#subtotal').textbox('setValue', numberFormat(so_item.subtotal))
    $('#total_purch').textbox('setValue', numberFormat(so_item.total_purch))
 
    initGrid();
 
    $("#update").show();
    $("#submit").hide();
    $("#new").hide();
    if(so_item.status_po==="On Order"){
        $("#posting").linkbutton({text:"Un Approve",width: '20%'});
        $("#update").hide();
    }
    if(so_item.status_po==="Closed" || so_item.status_po==="Cancel"){
        $("#posting").hide();
        $("#cancel").hide();
        $("#update").hide();
    }

}


function addform() {
         window.location.href = base_url+"Purchaseorder/form/add?customer_code="+$('#customer_code').combogrid('getValue')+"&customer_name="+$('#customer_name').textbox('getValue');
   // window.Open(base_url+'Purchaseorder/print_so/'+docno, '_blank');
}function printSO() {
        // window.Open(base_url+'Purchaseorder/print_so/'+docno, '_blank', 'location=yes,height=400,width=500,scrollbars=yes,status=yes');
   // window.Open(base_url+'Purchaseorder/print_so/'+docno, '_blank');
    $.ajax({
        type:"get",
        url:base_url+"Purchaseorder/print_so/"+docno,
        dataType:"json",
        success:function(result){
            //console.log(result.data)
            if(result.status===0) {
                $.messager.alert("Success","Print Berhasil")
            }
            else {
                $.messager.alert("Info","Print Gagal")
            }

        }
    });
}
function reload_header() {
    $.ajax({
        type:"POST",
        url:base_url+"Purchaseorder/read_data/"+docno,
        dataType:"json",
        success:function(result){
            //console.log(result.data)
            if(result.status===0) {
                $('#fm').form('load',result.data);
                so_item = result.data;
                initHeader() 
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"Purchaseorder";
                    }
                });
            }

        }
    });
}
var timer=null;
var product_selected=null;
var sku_scanned='';
function initGrid() { 
    
    if(!so_item) return
 
    $("#dg").edatagrid({ 
        fitColumns: false,
        width: "100%",
        url: base_url + "Purchaseorder/load_grid_detail/"+so_item.po_no,
        saveUrl: base_url + "Purchaseorder/save_data_detail/"+so_item.po_no+"/"+so_item.seqno,
        updateUrl: base_url + "Purchaseorder/edit_data_detail/"+so_item.po_no+"/"+so_item.seqno,
        destroyUrl: base_url + "Purchaseorder/delete_data_detail/"+so_item.po_no+"/"+so_item.seqno,
        idField: 'seqno',
        method: "POST",
        pagePosition: "top",
        resizeHandle: "right",
        resizeEdge: 10,
        pageSize: 20,
        clientPaging: true,
        remoteFilter: true,
        rownumbers: false,
        pagination: true, 
        sortName: "seqno",
        sortOrder: "desc",
        singleSelect: true, nowrap:true,
        toolbar: [
        {
            iconCls: 'icon-add', id:'add', text:'New',
            handler: function(){
                if (so_item.status_po!=="Open"){
                    $.messager.show({
                        title: 'Warning',
                        msg: `Detail tidak bisa di Tambah (status : ${so_item.status_po})`
                    });
                    return
                } 
                 addnew();
             }
           // handler: function(){$('#dg').edatagrid('addRow',0)}
        },
        {
            id:'edit', iconCls: 'icon-edit', text:'Edit',
            handler: function(){
                if (so_item.status_po!=="Open"){
                    $.messager.show({
                        title: 'Warning',
                        msg: `Detail tidak bisa di Edit (status : ${so_item.status_po})`
                    });
                    return
                } 
                    var row = $('#dg').datagrid('getSelected');
                    var rowIndex = $("#dg").datagrid("getRowIndex", row);
                    if (!row){
                          $.messager.show({
                                title: 'Warning',
                                msg: `Please select data to edit`
                            });
                            return;
                    } 
                    var seqno=row.seqno; 
                    editnew(seqno);
            }
        },
        {
            id:'delete', iconCls: 'icon-remove', text:'Delete',
            handler: function(){
                if (so_item.status_po!=="Open"){
                    $.messager.show({
                        title: 'Warning',
                        msg: `Detail tidak bisa di hapus (status : ${so_item.status_po})`
                    });
                    return
                }
                var row = $('#dg').datagrid('getSelected');
                var rowIndex = $("#dg").datagrid("getRowIndex", row);
                if (!row){
                      $.messager.show({
                            title: 'Warning',
                            msg: `Please select data to delete`
                        });
                        return;
                } 
                var seqno=row.seqno; 
                deletedetil(seqno);
            }
        }],
        loadFilter: function (data) {
            data.rows = [];
            if (data.data) {
                data.rows = data.data;
            }
            return data;
        },
        onLoadSuccess: function () {
            authbutton(); 
        }, 
        onBeforeEdit: function (index, row) { 
            //if (row.isNewRecord) return 
            if(so_item.status_po!=="Open") {
                $.messager.show({
                    title: 'Warning',
                    msg: "Data tidak bisa di edit"
                });
                setTimeout(function () {
                    $("#dg").edatagrid('cancelRow');
                }, 500)
            }
            else if(so_item.status_po=="Open"){
                console.log(so_item.status_po)
            }
        }, 
        columns: [[
            {field: "sku", title: "Product_code#", width: '9%', sortable: true, editor: {type: 'textbox',options:{readonly:true}}}, 
            {field: "nmbar", title: "Product Name", width: '20%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}}, 
            {field: "seqno", title: "Seqno", width: '8%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}}, 
            {field: "qty_order", title: "Qty Order", width: '8%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "unit_price", title: "Unit Price", width: '9%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.unit_price);
            }, editor: {type: 'textbox',options:{readonly:true}}}, 
            {field: "uom", title: "UOM", width: '6%', sortable: true, formatter:function(index, row){
                return row.uom_id;
                },editor: {type: 'textbox',options:{disabled:true}}}, 
            {field: "disc", title: "Disc%", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options:{
                        min:2, precision:2,
                        formatter:formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                keyupnumber(e,'disc');
                            },
                        })
                    }
                }
            },     
            {field: "ppn", title: "PPN%", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options:{
                        min:2, precision:2,
                        formatter:formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                keyupnumber(e,'ppn');
                            },
                        })
                    }
                }
            },     
            {field: "net_unit_price", title: "Net Unit Price",align:"right",  width: '15%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.net_unit_price);
            }, editor: {type: 'textbox',options:{readonly:true}}},  
            {field: "net_purchase", title: "Net Aft PPN",align:"right",  width: '15%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.net_purchase);
            }, editor: {type: 'textbox',options:{readonly:true}}},   
        ]],
        onSuccess: function (index, row) {
            if (row.status === 1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }

            $('#dg').edatagrid('reload');
            reload_header()

        },
        onError: function (index, e) {
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        },
        onDestroy: function (index, row) {
            if (row.status === 1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }

            $('#dg').edatagrid('reload');
            reload_header()

        },
    }) 
} 
function addnew(){
                clearFormInput();
    flag = "Purchaseorder/save_data_detail"; 
                $("#po_no").textbox('setValue',so_item.po_no);
                $("#datepo").textbox('setValue',so_item.datepo);
                $("#supplier_id").textbox('setValue',so_item.supplier_id); 
                $("#seqno").textbox('setValue',"");
                $("#qty_order").textbox('setValue',0);
                $("#unit_price").textbox('setValue',0); 
                $("#discdetail").textbox('setValue',0);
                $("#ppndetail").textbox('setValue',0);  
        $('#modal_edit').dialog('open').dialog('center').dialog('setTitle','PO Detail'); 
    } 
function editnew(seqno){
        clearFormInput(); 
        console.log(seqno) 
             $.ajax({
                    type:"POST",
                    url:base_url+"Purchaseorder/read_data_detail/"+so_item.po_no+"/"+seqno,
                    dataType:"json",
                    success:function(result){
                        console.log(result.data)
                            flag ="Purchaseorder/edit_data_detail/"+so_item.po_no+"/"+seqno; 
                                $("#po_no").textbox('setValue',result.data.po_no);
                                $("#datepo").textbox('setValue',result.data.datepo);
                                $("#sku").textbox('setValue',result.data.product_code); 
                                $("#uom").textbox('setValue',result.data.uom); 
                                $("#skucode").textbox('setValue',result.data.sku); 
                                $("#supplier_id").textbox('setValue',result.data.supplier_id); 
                                $("#seqno").textbox('setValue',result.data.seqno); 
                                $("#qty_order").textbox('setValue',result.data.qty_order);
                                $("#unit_price").textbox('setValue',result.data.unit_price); 
                                $("#discdetail").textbox('setValue',result.data.disc);
                                $("#ppndetail").textbox('setValue',result.data.ppn);  
                        $('#modal_edit').dialog('open').dialog('center').dialog('setTitle','PO Detail');   
                    }
                });

    } 
function clearFormInput() {
    $("#sku").textbox('setValue',"");
    $("#skucode").textbox('setValue',"");
    $("#uom").textbox('setValue',"");
    $("#qty_order").textbox('setValue',0);
    $("#unit_price").textbox('setValue',0);
    $("#seqno").textbox('setValue',"");
    $("#discdetail").textbox('setValue',0);
    $("#ppndetail").textbox('setValue',0);  
    $("#datepo").textbox('setValue',so_item.datepo);
    $("#supplier_id").textbox('setValue',so_item.supplier_id); 
    $("#po_no").textbox('setValue',so_item.po_no);
}
function deletedetil(seqno){
          myConfirm("Confirmation", "Anda yakin akan menghapus data ini ?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                   $.post(
                        base_url+"Purchaseorder/delete_data_detail/"+so_item.po_no+"/"+seqno,function(result){
                            var res = $.parseJSON(result);
                            if (res.status===1){
                                alert(res.msg)
                            } else {
                            var selectedrow = $("#dg").datagrid("getSelected");
                            var rowIndex = $("#dg").datagrid("getRowIndex", selectedrow);
                            $('#dg').datagrid('refreshRow',rowIndex).datagrid('collapseRow',rowIndex).datagrid('expandRow',rowIndex);
                            reload_header();
                            }
                        }
                    );
            }
        }) 
} 
function submit_detail() { 
     var sku = $("#sku").numberbox('getValue');
     var qty_order = $("#qty_order").numberbox('getValue');
     var unit_price = $("#unit_price").numberbox('getValue');
     var disc = $("#discdetail").numberbox('getValue');
     var ppn = $("#ppn").numberbox('getValue'); 
    if(qty_order===""){
        alert('Please input Qty');
        return
    }
    if(unit_price===""){
        alert('Please input Unit Price');
        return
    }
    if(sku===""){
        alert('Please Pilih Nama Barang');
        return
    }
     var data = $('#form_editing').serialize();
      $.ajax({
          type: 'POST',
          dataType:"json",
          url: base_url+flag,
          data: data,
          success: function(result) {
                 var res = result;
                console.log(result);
                // console.log(res.status);
                if (res.status===1){
                    alert(res.msg)
                } else {
                    var selectedrow = $("#dg").datagrid("getSelected");
                    var rowIndex = $("#dg").datagrid("getRowIndex", selectedrow);
                    $('#dg').datagrid('refreshRow',rowIndex).datagrid('collapseRow',rowIndex).datagrid('expandRow',rowIndex); 
                    $('#modal_edit').dialog('close'); 
                    $('#dg').datagrid('reload');
                     reload_header();
                     clearFormInput();
                }
          }
        }); 
}
function addnewsku() {   
    $('#modal_edit_detail_sku').dialog('open').dialog('center').dialog('setTitle',' Form Data'); 
 
        $('#tt_sku').datagrid({ 
            url:`${base_url}Purchaseorder/get_product?doc_date=${so_item.datepo}&lokasi=${so_item.store_name}`,
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
            columns: [[
                        // {field: 'article_code', title: 'Article', width: 100},
                        {field: 'nobar', title: 'SKU', width: 150},
                        {field: 'product_code', title: 'Product Code', width: 100},
                        {field: 'nmbar', title: 'Product Name', width: 300}, 
                        {field: 'uom_jual', title: 'UOM', width: 100},
                    ]], 
            onLoadSuccess:function(){
                authbutton();
            },
            onClickCell:function(index, field, value){
                var rr =  $('#tt_sku').datagrid('getRows')[index];
                console.log(rr)
                $("#sku").textbox('setValue',rr.nmbar);
                $("#skucode").textbox('setValue',rr.nobar); 
                $("#uom").textbox('setValue',rr.uom_stock); 
                $('#modal_edit_detail_sku').dialog('close');
  
            }
        }); 
    $('#tt_sku').datagrid('enableFilter'); 
    $('#tt_sku').datagrid('destroyFilter');
    $('#tt_sku').datagrid('enableFilter');
}   
function submit_cancel() {
    var dt = $("#dg").edatagrid('getData');
    var status = "Cancel"

    if(status!==""){
        myConfirm("Confirmation", "Anda yakin ingin mengubah status transaksi ini?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                console.log(so_item);
                $.ajax({
                    type:"POST",
                    url:base_url+"Purchaseorder/edit_data_header",
                    dataType:"json",
                    data:{
                        docno:so_item.po_no,
                        po_date:so_item.po_date,
                        store_code:so_item.store_code, 
                        wilayah:so_item.wilayah, 
                        supplier_code:so_item.supplier_id, 
                        currency:so_item.currency, 
                        rate:so_item.rate, 
                        eta:so_item.eta, 
                        expired_date:so_item.expired_date, 
                        ref_no:so_item.ref_no, 
                        remark:so_item.remark, 
                        tot_item:so_item.tot_item, 
                        tot_qty_order:so_item.tot_qty_order, 
                        tot_qty_recv:so_item.tot_qty_recv, 
                        subtotal:so_item.subtotal, 
                        disc:so_item.disc,  
                        ppn:so_item.ppn, 
                        ppn:so_item.ppn, 
                        status_po:status, 
                        po_type:so_item.po_type, 
                        jumlah_print:so_item.jumlah_print  
                    },
                    success:function(result){
                        console.log(result.data)
                        if(result.status===0) {
                            window.location.href = base_url + "Purchaseorder/form/edit?docno=" + so_item.po_no
                        }
                        else {
                            $.messager.show({
                                title: 'Error',
                                msg: e.message,
                                handler:function () {
                                    window.location.href = base_url + "Purchaseorder/form/edit?docno=" + so_item.po_no
                                }
                            });
                        }

                    }
                });
            }
        })
    }
}
function submit(stt){ 
    let status = (stt==="")?(so_item)?so_item.status_po:'Open':stt;
    if(so_item!==undefined && so_item.status_po==="On Order" && status_po==="On Order") status_po = 'Open'; 
    $('#status').textbox('setValue',status);

    if(aksi==="add") {
        submit_reason("") 
    }else{
        if(so_item!==undefined){  
            if(so_item.status_po==="On Order"){
                  $("#status_po").textbox('setValue', 'Open');
                             $('#posting').click(function(){  
                                 $.messager.prompt({
                                        title: 'Reason UnApprove',
                                        msg: 'Input reason Un Approve Purchase order:',
                                        fn: function (r) {
                                            if (r) {
                                            $.ajax({  
                                             url:base_url+"Purchaseorder/edit_data_header",
                                             method:"POST",  
                                             data:$('#fm').serialize(), 
                                             success: function(result){   
                                                console.log(result);
                                                try {
                                                    var res = $.parseJSON(result);  
                                                    if (res.status === 0) {
                                                        var stt = $('#status_po').textbox('getValue');
                                                        if(stt=="On Order") { 
                                                            window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                                                        }else{
                                                            window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                                                        }
                                                    } else {
                                                        $.messager.show({
                                                            title: 'Error',
                                                            msg: res.msg
                                                        });
                                                    }
                                                }catch (e) {
                                                    $.messager.show({
                                                        title: 'Error',
                                                        msg: e.message
                                                    });
                                                }
                                            }
                                        }); 
                                            }
                                        }
                                    }); 
                              });
               
            }else{
                 submit_reason(stt);
            }
        }else{
            var psn = 'Invalid add/edit data, please refresh your browser.';
            $.messager.show({title: 'Error', msg: psn});
            $('#status_po').textbox('setValue', so_item.status_po);
        }
    }

}  

function OpenCopy() {
    if(so_item==null) return
    $('#dg').edatagrid({toolbar:'#toolbar23'});
    $("#combo").combogrid({
        idField: 'docno',
        textField:'docno',
        disabled:false,
        required:true,
        readonly:false,
        url:base_url+"Purchaseorder/load_grid",
        hasDownArrow: false,
        remoteFilter:true,
        panelWidth: 500,
        multiple:false,
        panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
            mousedown: function(){}
        }),
        editable: false,
        pagination: true,
        loadFilter: function (data) {
            data.rows = [];
            if (data.data){
                data.rows = data.data;
            }
            //console.log(data.rows)
            return data;
        },
        columns: [[
            {field:'docno',title:'No Trx',width:150},
            {field:'doc_date',title:'Tgl Trx',width:100},
            {field:'customer_code',title:'Kode Customer',width:100},
            {field:'customer_name',title:'Customer Name',width:150},
        ]],
        fitColumns: true,
        labelPosition: 'center'
    });
    var grid = $("#combo").combogrid('grid');
    grid.datagrid('enableFilter');
}

function submitCopy() {
    if(so_item==null) return
    var xx = $('#combo').combogrid('getValue');
    //console.log(xx)
    $.ajax({
        url: base_url+"Purchaseorder/copy_detail",
        type: 'post',
        data: {
            from:xx,
            to:so_item.docno
        },
        success: function(result){
            //console.log(result);
            var res = $.parseJSON(result);
            if (res.status===1){
                alert(res.msg)
            }
            $('#dg').edatagrid('reload');
            cancelUpload();
        }
    });
    cancelUpload()
}
function newrefresh() {
    $('#toolbar23').hide();
}
function cancelUpload() {
    $('#toolbar23').hide();
}
function submit_reason(reason,r) {  
    console.log(reason)
    $("#reason").textbox('setValue', r);  
    if(reason=="Update"){
       $("#status_po").textbox('setValue', 'Open');
       $('#update').click(function(){  
           $.ajax({  
                     url:base_url+"Purchaseorder/edit_data_header",
                     method:"POST",  
                     data:$('#fm').serialize(), 
                     success: function(result){   
                        try {
                            var res = $.parseJSON(result);  
                            if (res.status === 0) {
                                var stt = $('#status_po').textbox('getValue');
                                if(stt=="On Order") { 
                                    window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                                }else{
                                    window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                                }
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: res.msg
                                });
                            }
                        }catch (e) {
                            $.messager.show({
                                title: 'Error',
                                msg: e.message
                            });
                        }
                    }
                }); 
      });
    }
    else if(reason==="On Order"){
        $("#status_po").textbox('setValue', reason);
       $('#posting').click(function(){  
           $.ajax({  
                     url:base_url+"Purchaseorder/edit_data_header",
                     method:"POST",  
                     data:$('#fm').serialize(), 
                     success: function(result){   
                        try {
                            var res = $.parseJSON(result);  
                            if (res.status === 0) {
                                var stt = $('#status_po').textbox('getValue');
                                if(stt=="On Order") { 
                                    window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                                }else{
                                    window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                                }
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: res.msg
                                });
                            }
                        }catch (e) {
                            $.messager.show({
                                title: 'Error',
                                msg: e.message
                            });
                        }
                    }
                }); 
      });
    }
    else{
        $('#fm').form('submit',{
            url: base_url+flag,
            type: 'POST',
            success: function(result){ 
                console.log(result);
                try {
                    var res = $.parseJSON(result); 
                    if (res.status === 0) {
                        var stt = $('#status_po').textbox('getValue');
                        if(stt=="On Order") { 
                            window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                        }else{
                            window.location.href = base_url + "Purchaseorder/form/edit?docno=" + res.docno
                        }
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: res.msg
                        });
                    }
                }catch (e) {
                    $.messager.show({
                        title: 'Error',
                        msg: e.message
                    });
                }
            }
        });
    } 
}


function infoData() {
    //console.log("disini", so_item)
    if(aksi==="edit"){
        $.ajax({
            type:"POST",
            url:base_url+"Purchaseorder/read_history/"+so_item.docno,
            dataType:"json",
            success:function(result){
                //console.log(result.data)
                if(result.status===0) {
                    var dt = '';
                    dt += `<tr style="vertical-align: text-top">
                            <td>Date</td>
                            <td>User</td>
                            <td>Remark</td>
                        </tr>`;
                    for(var i=0; i<result.data.length; i++){
                        dt += `<tr style="vertical-align: text-top">
                            <td>${result.data[i].log_date}</td>
                            <td>${result.data[i].user_id}</td>
                            <td>${result.data[i].data_after}</td>
                        </tr>`;
                    }
                    var msg = `
                    <table>
                        ${dt}
                    </table>
                    `;
                    $.messager.alert({
                        title: 'Info Data',
                        msg: msg,
                        width: 400
                    })
                }
                else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.message,
                        handler:function () {
                            window.location.href = base_url+"Purchaseorder";
                        }
                    });
                }

            }
        });
    }
}
function showSupplier(r) {
    if(!r) return
    var msg = `
    <table style='width:100%'>
        <tr style="vertical-align: text-top">
            <td>Name</td>
            <td> : </td>
            <td>${r.customer_name}/${r.nama_customer}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Address</td>
            <td> : </td>
            <td>${r.alamat_kirimcust}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Wilayah</td>
            <td> : </td>
            <td>${r.kotacust} - ${r.provcust}</td>
        </tr> 
        <tr style="vertical-align: text-top">
            <td>Phone1</td>
            <td> : </td>
            <td>${r.tlpcust}</td>
        </tr> 
    </table>
    `;
    $.messager.alert("Customer Info",msg);
}   
function populateSupplier() {
   $('#supplier_code').combogrid({
        idField: 'supplier_code',
        textField:'supplier_code',
        url:base_url+"Purchaseorder/load_gridsupp",
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
        loadFilter: function (data) {
            //console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data; 
            return data;
        },
        onSelect:function (index, rw) {
             console.log("select",rw);
            if(rw.supplier_code==="") return
                if(rw.currency=="" || rw.currency==null || rw.currency=="IDR" ){
                    var curr='IDR'; 
                    $('#rate').textbox('setValue',1)  
                    $('#rate').textbox('readonly',true);
                }
                else{
                    var curr=rw.currency;
                    $('#rate').textbox('setValue',0) 
                    $('#rate').textbox('readonly',false);
                }
                
                $('#supplier_name').textbox('setValue',rw.supplier_name)
                $('#currency').textbox('setValue',curr) 
                $('#provinsi_id').textbox('setValue',rw.provinsi_id)
                $('#provinsi_name').combogrid('setValue',rw.provinsi)
                $('#regency_id').textbox('setValue',rw.regency_id)
                $('#regency_name').combogrid('setValue',rw.regency)
                if(rw.tipe_supplier=="Barang Jadi"){
                    var type_po='PO BJ';
                }
                else{
                    var type_po='PO BB';
                }
                
                $('#po_typename').combogrid('setValue',rw.tipe_supplier)
                $('#po_type').textbox('setValue',type_po)
            // $('#outstanding').textbox('setValue',numberFormat(rw.outstanding))
            // $('#credit_remain').textbox('setValue',numberFormat(rw.credit_remain))
            // //$('#pkp').textbox('setValue',rw.pkp)

            // $('#lokasi_stock').textbox('setValue',rw.lokasi_stock)
            // $('#provinsi_id').textbox('setValue',rw.provinsi_id)
            // $('#regency_id').textbox('setValue',rw.regency_id)
            // $('#regency_name').combogrid('setValue',rw.kota)

            // $('#customer_code').textbox('setValue',rw.customer_code)
            // $('#customer_name').textbox('setValue',rw.customer_name)
            // $("#customer").show(); 
            // $('#so_no').textbox('textbox').focus(); 
          
            // var d = $("#dg").edatagrid('getData');
            // if(d.data.length>0){
            //     $("#customer_name").combogrid({'readonly':true})
            // }

        },
        onLoadSuccess:function(){
                var gr =  $('#supplier_code').combogrid('grid')

                var data=gr.edatagrid('getData');
               // console.log(data)
             for(var i =0;i < data.rows.length;i++){
                var rw=data.rows[i];
                 // console.log('ds',rw)
                if(rw.supplier_code==supplier_code){
                    $('#supplier_name').textbox('setValue',rw.supplier_name)
                    $('#currency').textbox('setValue',rw.currency) 
                    $('#provinsi_id').textbox('setValue',rw.provinsi_id)
                    $('#provinsi_name').combogrid('setValue',rw.provinsi)
                    $('#regency_id').textbox('setValue',rw.regency_id)
                    $('#regency_name').combogrid('setValue',rw.regency) 
                //     $('#credit_limit').textbox('setValue',numberFormat(rw.credit_limit))
                //     $('#outstanding').textbox('setValue',numberFormat(rw.outstanding))
                //     $('#credit_remain').textbox('setValue',numberFormat(rw.credit_remain))
                //     //$('#pkp').textbox('setValue',rw.pkp)

                //     $('#lokasi_stock').textbox('setValue',rw.lokasi_stock) 
                //     $('#regency_id').textbox('setValue',rw.regency_id)
                //     $('#regency_name').combogrid('setValue',rw.kota)

                //     $('#customer_code').textbox('setValue',rw.customer_code)
                //     $('#customer_name').textbox('setValue',rw.customer_name)
                //     $("#customer").show(); 
                //     $('#so_no').textbox('textbox').focus(); 
                 }
            }
          // if(customer_code!==''){
          //        $('#customer_code').combogrid('setValue',customer_code);
          //        $('#customer_name').textbox('setValue',customer_name);
          //   }
        },
        columns: [[
            {field:'supplier_code', title:'Kode', width:100},
            {field:'supplier_name', title:'Supplier', width:300},
        ]]
    });
    var gr =  $('#supplier_code').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');  
} 