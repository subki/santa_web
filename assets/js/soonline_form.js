var so_item=undefined;
var flag = "";
$(document).ready(function () {

 $('#so_no').textbox('textbox').focus();   
    so_item = undefined;

    populateRegency();
    populateCustomer();
    populateSalesman();
    populateLocation();
    populateStore();
    populateJenisSO();
 
    if(aksi==="add"){
        flag = "Online/save_data_header";
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
        // $("#doc_date").datebox('setValue', tgl);
        // $("#doc_date").datebox('setText', tgl);

        $("#store_code").combogrid('setValue',store_code);
        $("#location_code").combogrid('setValue',location_code);
        $("#status").textbox('setValue','OPEN');
        $("#jumlah_print").textbox('setValue','0');
        $("#update").hide();
        $("#posting").hide();
        $("#cancel").hide();
        $("#print").hide();
        $("#new").hide();
        $("#customer").hide();
    }else{
        flag = "Online/edit_data_header";
        $.ajax({
            type:"POST",
            url:base_url+"Online/read_data/"+docno,
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
                            window.location.href = base_url+"Online";
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
    $('#customer_code').combogrid('setValue',so_item.customer_code)
    // $('#customer_code').combogrid('setText',so_item.customer_name)

    $('#credit_limit').textbox('setValue', numberFormat(so_item.credit_limit))
    $('#outstanding').textbox('setValue', numberFormat(so_item.outstanding))
    $('#credit_remain').textbox('setValue', numberFormat(so_item.credit_remain))

    $('#gross_sales').textbox('setValue', numberFormat(so_item.gross_sales))
    $('#total_discount').textbox('setValue', numberFormat(so_item.total_discount))
    $('#sales_before_tax').textbox('setValue', numberFormat(so_item.sales_before_tax))
    $('#total_ppn').textbox('setValue', numberFormat(so_item.total_ppn))
    $('#sales_after_tax').textbox('setValue', numberFormat(so_item.sales_after_tax))
    $('#pkp').textbox('setValue',so_item.pkp)

    var date = new Date(so_item.doc_date);
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
    $("#doc_date").datebox('setValue', tgl);
    $("#doc_date").datebox('setText', tgl);

    initGrid();
 
    $("#update").show();
    $("#submit").hide();
    if(so_item.status==="ON ORDER"){
        $("#posting").linkbutton({text:"UnSubmit"});
        $("#update").hide();
    }
    if(so_item.status==="CLOSE" || so_item.status==="EXPIRED"|| so_item.status==="CANCEL"){
        $("#posting").hide();
        $("#cancel").hide();
        $("#update").hide();
    }

}


function addform() {
         window.location.href = base_url+"Online/form/add?customer_code="+$('#customer_code').combogrid('getValue')+"&customer_name="+$('#customer_name').textbox('getValue');
   // window.open(base_url+'Online/print_so/'+docno, '_blank');
}function printSO() {
        // window.open(base_url+'Online/print_so/'+docno, '_blank', 'location=yes,height=400,width=500,scrollbars=yes,status=yes');
   // window.open(base_url+'Online/print_so/'+docno, '_blank');
    $.ajax({
        type:"get",
        url:base_url+"Online/print_so/"+docno,
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
        url:base_url+"Online/read_data/"+docno,
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
                        window.location.href = base_url+"Online";
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
        url: base_url + "Online/load_grid_detail/"+so_item.docno,
        saveUrl: base_url + "Online/save_data_detail/"+so_item.docno,
        updateUrl: base_url + "Online/edit_data_detail",
        destroyUrl: base_url + "Online/delete_data_detail",
        idField: 'id',
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
        // {
        //     iconCls: 'icon-add', id:'add', text:'New',
        //     handler: function(){$('#dg').edatagrid('addRow',0)}
        // },
        {
            id:'delete', iconCls: 'icon-remove', text:'Delete',
            handler: function(){
                if (so_item.status!=="OPEN"){
                    $.messager.show({
                        title: 'Warning',
                        msg: `Detail tidak bisa di hapus (status : ${so_item.status})`
                    });
                    return
                }
                $('#dg').edatagrid('destroyRow')
            }
        },{
            id:'submit', iconCls: 'icon-save', text:'Submit',
            handler: function(){
                 var selectedrow = $("#dg").edatagrid("getSelected");
                var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);

                // var ed = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc1_persen'});
                // var disc1 = $(ed.target).numberbox('getValue'); 
                // var ed = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc2_persen'});
                // var disc2 = $(ed.target).numberbox('getValue'); 
                // var confirm = false
                //  var disc2 = $(ed.target).numberbox('getValue');
  
                // var confirm = false
                // if(disc1==0){
                //     //console.log("true 1")   
                //             var dlg = $.messager.confirm({
                //                 title: 'Confirm',
                //                 msg: 'Apakah ingin melanjutkan untuk tidak ada diskon 1. tekan Yes untuk mengisi?',
                //                 buttons:[{
                //                     text: 'Yes',
                //                     onClick: function(){
                                       
                //                         // $('#dg').edatagrid('saveRow')
                //                          dlg.dialog('destroy')
                //                          var editor = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc1_persen'});
                //                         var grid =$(editor.target).numberbox('setValue','');
                //                         var grid = $(editor.target).textbox('textbox').focus();
                //                         grid.focus(); 
                //                     }
                //                 },{
                //                     text: 'No',
                //                     onClick: function(){  
                //                     dlg.dialog('destroy')

                //                     var dlg2 = $.messager.confirm({
                //                             title: 'Confirm',
                //                             msg: 'Apakah ingin melanjutkan untuk tidak ada diskon 2 . tekan Yes untuk mengisi?',
                //                             buttons:[{
                //                                 text: 'Yes',
                //                                 onClick: function(){
                                                   
                //                                     // $('#dg').edatagrid('saveRow')
                //                                      dlg2.dialog('destroy')
                //                                      var editor2 = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc2_persen'});
                //                                     var grid2 =$(editor2.target).numberbox('setValue','');
                //                                     var grid2 = $(editor2.target).textbox('textbox').focus();
                //                                     grid2.focus();
                //                                 keyupnumberf(e,'disc2_persen');
                //                                 }
                //                             },{
                //                                 text: 'No',
                //                                 onClick: function(){ 
                //                                      $('#dg').edatagrid('saveRow') 
                //                                         dlg2.dialog('destroy');
                //                                 }
                //                             }]
                //                         });
                //                     }
                //                 }]
                //             }); 
                // }else if(disc2==0){
                //        var dlg2 = $.messager.confirm({
                //                             title: 'Confirm',
                //                             msg: 'Apakah ingin melanjutkan untuk tidak ada diskon 2 . tekan Yes untuk mengisi?',
                //                             buttons:[{
                //                                 text: 'Yes',
                //                                 onClick: function(){
                                                   
                //                                     // $('#dg').edatagrid('saveRow')
                //                                      dlg2.dialog('destroy')
                //                                      var editor2 = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc2_persen'});
                //                                     var grid2 =$(editor2.target).numberbox('setValue','');
                //                                     var grid2 = $(editor2.target).textbox('textbox').focus();
                //                                     grid2.focus();
                //                                 }
                //                             },{
                //                                 text: 'No',
                //                                 onClick: function(){ 
                //                                    $('#dg').edatagrid('saveRow') 
                //                                         dlg2.dialog('destroy')
                //                                 }
                //                             }]
                //                         });
                // }   
                // else  $('#dg').edatagrid('saveRow');
 
            }
        },{
            id:'cancel', iconCls: 'icon-undo', text:'Cancel',
            handler: function(){$('#dg').edatagrid('cancelRow')}
        },{
            id:'kopi', iconCls: 'icon-copy', text:'Copy',
            handler: function(){openCopy()}
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

            $('#dg').edatagrid('addRow',0);
            //$('#dg').edatagrid('saveRow');
            var dt = $("#dg").edatagrid('getData');
            if(dt.data.length>0){
                // $('#customer_name').combogrid({"readonly":true});
                // $('#customer_name').combogrid('setValue', so_item.customer_name);
                $("#kopi").linkbutton({"disabled":true})
                // $('#open_cust').show();
            }else {
                $("#kopi").linkbutton({"disabled":false})
                // $('#open_cust').hide();
            } 
        },
        onBeginEdit: function (index, row) {
            if (row.isNewRecord ){

                if(so_item.status!=="OPEN") {
                    $.messager.show({
                        title: 'Warning',
                        msg: "SO sudah di On Order"
                    });
                    setTimeout(function () {
                        $("#dg").edatagrid('cancelRow');
                    }, 500)
                    return
                }

                if(so_item.location_code===null){
                    $.messager.show({
                        title: 'Warning',
                        msg: "Lokasi stock belum ada."
                    });
                    setTimeout(function () {
                        $("#dg").edatagrid('cancelRow');
                    }, 500)
                    return
                }
            }   
            var editor = $(this).edatagrid('getEditor', {index: index, field: 'nobar'});
            var grid = $(editor.target).combogrid('textbox').focus();
            grid.focus();
            if (row.isNewRecord) {
                editor = $(this).edatagrid('getEditor', {index: index, field: 'disc1_persen'});
                $(editor.target).numberbox('setValue', so_item.disc1_persen);
                // if(parseFloat(so_item.disc1_persen)>0){
                //     $(editor.target).numberbox({disabled:false, readonly:true});
                // }

                editor = $(this).edatagrid('getEditor', {index: index, field: 'disc2_persen'});
                $(editor.target).numberbox('setValue', so_item.disc2_persen);
                // if(parseFloat(so_item.disc2_persen)>0){
                //     $(editor.target).numberbox({disabled:false, readonly:true});
                // }
 
                // if(parseFloat(so_item.disc3_persen)>0){
                //     $(editor.target).numberbox({disabled:false, readonly:true});
                // }
            }


        },
        onBeforeEdit: function (index, row) { 
            //if (row.isNewRecord) return 
            if(so_item.status!=="OPEN") {
                $.messager.show({
                    title: 'Warning',
                    msg: "Data tidak bisa di edit"
                });
                setTimeout(function () {
                    $("#dg").edatagrid('cancelRow');
                }, 500)
            }
            else if(so_item.status=="OPEN"){
                console.log('a')
            }
        },
        columns: [[
            {
                field: "nobar",
                title: "Article#",
                width: '9%',
                sortable: true, 
                formatter: function (value, row) {
                    return row.product_code;
                },
                editor: {
                    type: 'textbox',
                    options: {
                        readonly: false,
                        idField: 'nobar',
                        textField: 'product_code', 
                        required: false,
                        hasDownArrow: false,  
                        multiple: false,
                        editable: true, 
                        loadFilter: function (data) {
                            data.rows = [];
                            if (data.data) {
                                data.rows = data.data;
                            }
                            return data;
                        },  
                         inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
                                keyup:function(e){
                               
                                var row = $(this).val(); 
                                console.log('tes',row);
                            if(e.key==='Enter' || e.keyCode===13){ 

                                console.log('tes2',row);
                                $.ajax({
                                    type: 'POST',
                                    url: `${base_url}Online/get_byproduct`, 
                                    data: {
                                        doc_date: so_item.doc_date,
                                        lokasi: so_item.location_code,
                                        docno: row, 
                                    },
                                    dataType: 'json',
                                    success: function(data){
                                         var selectedrow = $("#dg").edatagrid("getSelected");
                                         var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);
                                        
                                         //console.log(data);
                                        if(data.data==''){
                                         console.log("Data Ini Tidak ditemukan");
                                         
                                            var ed = $('#dg').edatagrid('getEditor', {
                                                index: rowIndex,
                                                field: 'nobar'
                                            }); 
                                            $(ed.target).textbox('setValue').focus();
                                            ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'qty_order'});
                                            $(ed.target).numberbox('setValue',0);
                                            // ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'unit_price'});
                                            // $(ed.target).numberbox('setValue',0);
                                            ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'satuan_jual'});
                                            $(ed.target).textbox('textbox','');
                                             return;
                                        } 
                                        else{
                                            var datas = data.data[0]; 
                                            var jml_flg = 0;
                                            for(var i=0; i<datas; i++){
                                                if(datas.data[i].nobar && datas.data[i].nobar===row.nobar){
                                                    jml_flg++;
                                                }
                                            }
 
                                            // /dg.datagrid('gotoCell','right');
                                             
                                            // /dg.datagrid('gotoCell','right');
                                            var ed = $('#dg').edatagrid('getEditor', {
                                                index: rowIndex,
                                                field: 'nmbar'
                                            }); 
                                            $(ed.target).textbox('setValue', datas.nmbar);

                                            var ed = $('#dg').edatagrid('getEditor', {
                                                index: rowIndex,
                                                field: 'product_id'
                                            });
                                            $(ed.target).textbox('setValue', datas.product_id);

                                            ed = $('#dg').edatagrid('getEditor', {
                                                index: rowIndex,
                                                field: 'satuan_jual'
                                            });
                                            $(ed.target).textbox('setValue', datas.satuan_jual);
                                            $(ed.target).textbox('setText', datas.id_jual);
          

                                            ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'qty_order'});
                                            $(ed.target).numberbox('setValue',1);

                                            // ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'total_tax'});
                                            // $(ed.target).numberbox('setValue', datas.id_jual);
                                               
                                            if(jml_flg>0){
                                                ed = $('#dg').edatagrid('getEditor', {
                                                    index: rowIndex,
                                                    field: 'tipe'
                                                });
                                                $(ed.target).textbox('setValue', jml_flg);
                                                $(ed.target).textbox('setText', jml_flg);
                                            }  
                                            get_unit_price(datas.product_id, rowIndex, function(res){
                                                if(res.diskon==1000)return;


                                                console.log(parseFloat(res.diskon))
                                                var ed = $('#dg').edatagrid('getEditor', {
                                                    index: rowIndex,
                                                    field: 'disc1_persen'
                                                });
                                                if(parseFloat(res.diskon)>0){ 
                                                    $(ed.target).numberbox('setValue', parseFloat(res.diskon));
                                                } 
                                                else{ 
                                                   $(ed.target).numberbox('setValue', 0);
                                                }

                                                //ed = $('#dg').edatagrid('getEditor', { index: rowIndex,field: 'disc1_persen'});
                                                var disc1_amount = res.unit_price * ($(ed.target).numberbox('getValue')/100); //console.log("disc1", disc1_amount)
                                                // var tot1 = n[3]-(n[3]-disc1_amount)
                                                var ed = $('#dg').edatagrid('getEditor', {
                                                    index: rowIndex,
                                                    field: 'disc2_persen'
                                                });
                                                $(ed.target).numberbox('setValue', 0);
 
                                                var disc2_amount = res.unit_price * ($(ed.target).numberbox('getValue')/100); //console.log("disc2", disc2_amount)
                                                // var tot2 = (n[3]-tot1)+disc2_amount

                                                var disc_tot = disc1_amount+disc2_amount;
 
                                                var gross = res.unit_price-disc_tot
                                                var ppn = 0;
                                               // var pkp = $('#pkp').textbox('getValue');
                                                 ppn = (gross*1/1.1) * 10/100;
                                                // var net_unit = (gross-ppn)*n[0]
                                                var net_unit = gross
                                                //var net_total = gross*1
                                                var getgross = parseFloat(res.unit_price)-disc_tot;
                                                var net_total = getgross+parseFloat(ppn);
                                                var grss = net_total-ppn;
                                                // console.log(net_total);
                                                  console.log(ppn);
                                                // console.log(grss);
                                                var ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc1_amount'});
                                                $(ed.target).numberbox('setValue',isNaN(disc1_amount)?0:disc1_amount);

                                                ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc2_amount'});
                                                $(ed.target).numberbox('setValue',isNaN(disc2_amount)?0:disc2_amount);

                                                ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc_total'});
                                                $(ed.target).numberbox('setValue',isNaN(disc_tot)?0:disc_tot);

                                                ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'bruto_before_tax'});
                                                $(ed.target).numberbox('setValue',isNaN(grss)?0:grss);

                                                ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'total_tax'});
                                                $(ed.target).numberbox('setValue',isNaN(ppn)?0:ppn);

                                                ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'net_unit_price'});
                                                $(ed.target).numberbox('setValue',isNaN(net_unit)?0:net_unit);

                                                ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'net_total_price'});
                                                $(ed.target).numberbox('setValue',isNaN(net_total)?0:net_total);  
                                                $("#dg").edatagrid("saveRow");
                                            })

                                        }
                                        
                                    },
                                    error: function(){
                                        error.apply(this, arguments);
                                    }
                                }); 
                            }
                            else{
                                sku_scanned+=row;
                            }
                            //$("#dg").edatagrid('gotoCell', 'right');
                            //$("#dg").edatagrid('editCell',$("#dg").edatagrid('cell')); 
                            

                                }
                            }),
                        onChange: function (index, row) { 


                        }
                    }
                }
            },
            {field: "product_id", title: "Product Id", width: '8%', sortable: true, editor: {type: 'textbox',options:{readonly:true}}},
            {field: "nmbar", title: "Product Name", width: '20%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            // {field: "tipe", title: "Type", width: '5%', sortable: true, editor: {type: 'textbox'}},
            // {field: "qty_pl", title: "Qty PL", width: '6%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "qty_order", title: "Qty Ord", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options:{ readonly:true,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                var selectedrow = $("#dg").edatagrid("getSelected");
                                var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);

                                var ed = $('#dg').edatagrid('getEditor', {
                                    index: rowIndex,
                                    field: 'unit_price'
                                });

                                if(timer!=null){
                                    clearTimeout(timer)
                                }

                                timer = setTimeout(function () {
                                    var prc = $(ed.target).textbox('getValue');
                                    //console.log("uki",prc)
                                    if(product_selected!==null){
                                        if(parseInt(product_selected.stock)<parseInt(e.target.value)){
                                            $.messager.alert("Warning","Qty Stock lebih kecil dari Qty Order")
                                        }else{
                                            if(prc==null || prc==="" || isNaN(prc) || parseFloat(prc)===0){
                                                //console.log("uki","masuk")
                                                ed = $('#dg').edatagrid('getEditor', {
                                                    index: rowIndex,
                                                    field: 'product_id'
                                                });
                                                var prd_id = $(ed.target).textbox('getValue');
                                                var row = {product_id:prd_id}; 
                                                // get_unit_price(row, rowIndex, function (res) {
                                                //      console.log("prc", row);
                                                //     keyupnumber(e,'qty_order');
                                                // })
                                            }else{
                                                //console.log("uki","masuk luar")
                                                keyupnumber(e,'qty_order');
                                            }
                                        }
                                    }
                                }, 1500);
                            },
                        })
                    }
                }
            },
            {field: "satuan_jual", title: "UOM", width: '6%', sortable: true, formatter:function(index, row){
                return row.uom_id;
                },editor: {type: 'textbox',options:{disabled:true}}},
            {field: "unit_price", title: "Retail", width: '9%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.unit_price);
            }, editor: {type: 'textbox',options:{readonly:true}}}, 
            {field: "disc1_persen", title: "Disc1%", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options: {
                        min: 0, precision: 2,
                        formatter: formatnumberbox 
                    }
                }
            }, 
            {field: "disc1_amount", title: "Amt1", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc1_amount);
                }, editor: {type: 'numberbox',options:{readonly:true}}},
            {field: "disc2_persen", title: "Disc2%", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options: {
                        min: 0, precision: 2,
                        formatter: formatnumberbox 
                    }
                }
            },
            {field: "disc2_amount", title: "Amt2", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc2_amount);
                }, editor: {type: 'numberbox',options:{readonly:true}}}, 
            {field: "disc_total", title: "Total Disc", width: '10%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.disc_total);
            }, editor: {type: 'numberbox',options:{readonly:true, formatter:formatnumberbox}}},
            {field: "bruto_before_tax", title: "Sls Bfr Tax", width: '10%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.bruto_before_tax);
            }, editor: {type: 'numberbox',options:{readonly:true,formatter:formatnumberbox}}},
            {field: "total_tax", title: "PPN", width: '10%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.total_tax);
            }, editor: {type: 'numberbox',options:{readonly:true,formatter:formatnumberbox}}},
            {field: "net_unit_price", title: "Net Aft PPN", width: '10%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.net_unit_price);
            }, editor: {type: 'numberbox',options:{readonly:true, formatter:formatnumberbox}}},
            {field: "net_total_price", title: "Sls Aft PPN", width: '10%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.net_total_price);
            }, editor: {type: 'numberbox',options:{readonly:true, formatter:formatnumberbox}}},
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

    $('#dg').edatagrid('hideColumn','product_id');
    $('#dg').edatagrid('hideColumn','bruto_before_tax'); 
    $('#dg').edatagrid('hideColumn','net_unit_price');
    $('#dg').edatagrid('hideColumn','disc1_amount');
    $('#dg').edatagrid('hideColumn','disc2_amount'); 
    $('#dg').edatagrid('hideColumn','disc1_persen');
    $('#dg').edatagrid('hideColumn','disc2_persen'); 

}

function get_unit_price(row, index, callback) { 
    $.ajax({
        type:"POST",
        url: `${base_url}Online/get_unit_price?product_id=${row}&tanggal=${so_item.doc_date}&lokasi=${so_item.lokasi_stock}&customer_code=${so_item.customer_code}`,
        dataType:"json",
        success:function(result){
         console.log(result)
         
            if(parseFloat(result.unit_price)>0) {
                 var ed = $('#dg').edatagrid('getEditor', {
                index: index,
                field: 'unit_price'
            });
                var disc = parseFloat(result.unit_price)*(parseFloat(result.diskon)/100);
             
                var perunit = parseFloat(result.unit_price)-disc;
             
                $(ed.target).textbox('setValue', result.unit_price);
                if(result.diskon==1000){ 
                    $.messager.show({    // show error message
                        title: 'Error',
                        msg: "Data tidak berada di Customer ini",
                        timeout:1000
                    });
                    $('#dg').edatagrid('cancelRow')
                    $('#dg').edatagrid('addRow',0)
                }  
              // $('#dg').edatagrid('saveRow')
              //   ed = $('#dg').edatagrid('getEditor', {
              //       index: index,
              //       field: 'total_tax'
              //   });   
               
              //   $(ed.target).numberbox('setValue', 1);
            }else{
                //$.messager.alert("Error","Unit Price Belum tersedia")
                $(ed.target).textbox('setValue', 0);
            }
                //alertnotif();
              // $('#dg').edatagrid('saveRow')  

            callback(result)
        }
    });
}
function alertnotif(){ 
}
function keyupnumber(e, field,callback){
 
    var selectedrow = $("#dg").edatagrid("getSelected");
    var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);

    let arr = ['qty_order','disc1_persen','disc2_persen','unit_price'];
    let val = [0,0,0,0,0];

    for(let i=0; i<arr.length; i++){
        if(arr[i] === field) val[i] = e.target.value;
        else {
            var edt = $('#dg').edatagrid('getEditor',{
                index:rowIndex,
                field:arr[i]
            });
            let s = $(edt.target).numberbox('getValue');
            if(s==='') val[i] = 0;
            else val[i]=s;
        }
    }

    let n = val.map(Number);
    var disc1_amount = n[3] * (n[1]/100);  console.log("disc1", disc1_amount)
    // var tot1 = n[3]-(n[3]-disc1_amount)
    //
    var disc2_amount = (n[3]-disc1_amount) * (n[2]/100); //console.log("disc2", disc2_amount)
    // var tot2 = (n[3]-tot1)+disc2_amount
 
    var disc_tot = disc1_amount+disc2_amount
    var gross = n[3]-disc_tot
    var ppn = 0;
    //var pkp = $('#pkp').textbox('getValue');
  //  if(pkp==="YES") ppn = (gross*n[0]/1.1) * 10/100;
    // var net_unit = (gross-ppn)*n[0]
    var net_unit = gross
    var net_total = gross*n[0]
    var grss = net_total-ppn; 
    // console.log(gross);
    // console.log(net_unit);
    var ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc1_amount'});
    $(ed.target).numberbox('setValue',isNaN(disc1_amount)?0:disc1_amount);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc2_amount'});
    $(ed.target).numberbox('setValue',isNaN(disc2_amount)?0:disc2_amount);
 
    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc_total'});
    $(ed.target).numberbox('setValue',isNaN(disc_tot)?0:disc_tot);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'bruto_before_tax'});
    $(ed.target).numberbox('setValue',isNaN(grss)?0:grss);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'total_tax'});
    $(ed.target).numberbox('setValue',isNaN(ppn)?0:ppn);


    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'net_total_price'});
    $(ed.target).numberbox('setValue',isNaN(grss)?0:grss);
    // ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'net_total_price'});
    // $(ed.target).numberbox('setValue',isNaN(net_total)?0:net_total);
 
    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'net_unit_price'});
    $(ed.target).numberbox('setValue',isNaN(net_unit)?0:net_unit);
   callback();
}

function submit_cancel() {
    var dt = $("#dg").edatagrid('getData');
    var status = "";
    if(dt.data.length>0){
        var qty_pl = 0;
        var qty_ord= 0;
        for(var i=0; i<dt.data.length; i++){
            var pl = dt.data[i].qty_pl;
            if(pl==="") pl = "0"
            qty_pl += parseInt(pl)
            qty_ord += parseInt(dt.data[i].qty_order)
        }
        //console.log(qty_pl)
        //console.log(qty_ord)
        if(qty_pl>0){
            if(qty_pl<qty_ord){
                status = "EXPIRED"
            }
            if(qty_pl === qty_ord){
                status = "CLOSE"
            }
        }else{
            status = "BATAL"
        }
    }else {
        status = "BATAL"
    }

    if(status!==""){
        myConfirm("Confirmation", "Anda yakin ingin mengubah status transaksi ini?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                $.ajax({
                    type:"POST",
                    url:base_url+"Online/edit_data_header",
                    dataType:"json",
                    data:{
                        docno:so_item.docno,
                        doc_date:so_item.doc_date,
                        store_code:so_item.store_code,
                        location_code:so_item.location_code,
                        so_no:so_item.so_no,
                        disc1_persen:so_item.disc1_persen,
                        disc2_persen:so_item.disc2_persen,
                        status:status
                    },
                    success:function(result){
                        //console.log(result.data)
                        if(result.status===0) {
                            window.location.href = base_url + "Online/form/edit?docno=" + so_item.docno
                        }
                        else {
                            $.messager.show({
                                title: 'Error',
                                msg: e.message,
                                handler:function () {
                                    window.location.href = base_url + "Online/form/edit?docno=" + so_item.docno
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
    //console.log(base_url+flag)
    let status = (stt==="")?(so_item)?so_item.status:'OPEN':stt;
    if(so_item!==undefined && so_item.status==="ON ORDER" && status==="ON ORDER") status = 'OPEN';
    $('#status').textbox('setValue',status);

    if(aksi==="add") {
        submit_reason("")

          
    }else{
        if(so_item!==undefined){
            if(so_item.status==="ON ORDER" && status==="OPEN"){
                read_pickinglist(function (res) {
                    console.log(res);
                    if(res==="OK"){
                        $.messager.prompt({
                                title: 'Reason UnSubmit',
                                msg: 'Input reason UnSubmit sales:',
                                fn: function (r) {
                                    if (r) {
                                         submit_reason(r);
                                    }
                                }
                            });
                    }else{
                        $.messager.show({title:'Error', msg:res});
                    }
                })
            }else if(so_item.status === status){
                submit_reason("")
            }else{
                myConfirm("Confirm", "Anda yakin ingin mengubah status sales ini?", "Yes", "No", function (r) {
                    if (r === "Yes") {
                        if (so_item.credit_limit==="" || isNaN(so_item.credit_limit) ||
                            so_item.sales_after_tax==="" || isNaN(so_item.sales_after_tax) ||
                            so_item.credit_limit-so_item.outstanding<=0 ||
                            (parseFloat(so_item.credit_limit) - parseFloat(so_item.outstanding))< parseFloat(so_item.sales_after_tax)) {
                            //cek otoritas
                             submit_reason("")
                            // if(parseInt(global_auth[global_auth.appId].allow_approve)>0){
                            //     submit_reason("")
                            // }else {
                            //     var psn = 'Nilai transaksi melebihi limit customer, dibutuhkan otorisasi untuk memposting.';
                            //     $.messager.show({title: 'Error', msg: psn});
                            //     $('#status').textbox('setValue', so_item.status);
                            // }
                        } else {
                            submit_reason("")
                        }
                    }
                })
            }
        }else{
            var psn = 'Invalid add/edit data, please refresh your browser.';
            $.messager.show({title: 'Error', msg: psn});
            $('#status').textbox('setValue', so_item.status);
        }
    }

}
function read_pickinglist(callback) {
    $.ajax({
        type:"POST",
        url:base_url+"Pickup/read_data_by_so/"+so_item.docno,
        dataType:"json",
        success:function(result){
         // console.log(result)
           if(result.result > 0) callback("Transaksi sudah Pickup. Unposting transaksi gagal.")
           else callback("OK")
        }
    });

}

// function cancel(){
//     myConfirm("Confirm", "Anda yakin ingin mengubah status sales ini?", "Yes", "No", function (r) {
//         if (r === "Yes") {
//             $('#status').textbox('setValue',"CANCEL");
//             submit_reason("")
//         }
//     })
// }

function openCopy() {
    if(so_item==null) return
    $('#dg').edatagrid({toolbar:'#toolbar23'});
    $("#combo").combogrid({
        idField: 'docno',
        textField:'docno',
        disabled:false,
        required:true,
        readonly:false,
        url:base_url+"Online/load_grid",
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
        url: base_url+"Online/copy_detail",
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
function cancelUpload() {
    $('#toolbar23').hide();
}
function submit_reason(reason) {
   // console.log(reason);
   //  console.log(base_url+flag);
    $("#reason").textbox('setValue', reason);
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
          //  console.log(result)
            try {
                var res = $.parseJSON(result);
                
                if (res.status === 0) {
                    var stt = $('#status').textbox('getValue');
                    if(stt=="ON ORDER") {
                        printSO()
                        window.location.href = base_url + "Online/form/edit?docno=" + res.docno
                    }else{
                        window.location.href = base_url + "Online/form/edit?docno=" + res.docno
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

function showCustomer() {
    //console.log("disini", so_item)
    if(aksi==="edit"){
        $.ajax({
            type:"POST",
            url:base_url+"Online/read_datacustomer/"+so_item.customer_code+"/"+so_item.so_no,
            dataType:"json",
            success:function(result){
                //console.log(result.data)
                if(result.status===0) {
                    showCustomer2(result.data)
                }
                else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.message,
                        handler:function () {
                            window.location.href = base_url+"Online";
                        }
                    });
                }

            }
        });
    }else{
        var g = $('#customer_code').combogrid('grid');
        var r = g.datagrid('getSelected'); 
        showCustomer2(r)
    }
}

function infoData() {
    //console.log("disini", so_item)
    if(aksi==="edit"){
        $.ajax({
            type:"POST",
            url:base_url+"Online/read_history/"+so_item.docno,
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
                            window.location.href = base_url+"Online";
                        }
                    });
                }

            }
        });
    }
}
function showCustomer2(r) {
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

function populateJenisSO() {
    $('#jenis_so').combobox({
        data:[
            {value:'PROMO',text:'PROMO'},
            {value:'NORMAL',text:'NORMAL'}
        ],
        prompt:'-Please Select-',
        validType:'inList["#jenis_so"]',
    });

}
function populateStore() {
   $('#store_code').combogrid({
        idField: 'store_code',
        textField:'store_name',
        url:base_url+"storeprofile/load_grid",
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
            //console.log("select",rw);
            if(rw.store_code==="") return
            $('#location_code').combogrid('setValue',rw.default_stock_l)
        },
        columns: [[
            {field:'store_code', title:'', width:75},
            {field:'store_name', title:'Sales Toko', width:175},
            {field:'default_stock_l', title:'', width:75},
            {field:'location_name', title:'Gudang', width:175},
        ]]
    });
    var gr =  $('#store_code').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
}

function populateLocation() {
   $('#location_code').combogrid({
        idField: 'location_code',
        textField:'location_name',
        url:base_url+"delivery/get_location/xxx",
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
            //console.log("select",rw);
            if(rw.location_code==="") return
            $('#store_code').combogrid('setValue',rw.store_code)
        },
        columns: [[
            {field:'location_code', title:'', width:75},
            {field:'location_name', title:'Gudang', width:175},
            {field:'store_code', title:'', width:75},
            {field:'store_name', title:'Sales Toko', width:175},
        ]]
    });
    var gr =  $('#location_code').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
}

function populateSalesman() {
   $('#salesman_id').combogrid({
        idField: 'salesman_id',
        textField:'salesman_name',
        url:base_url+"mastersalesman/load_grid",
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
            //console.log("select",rw);
        },
        columns: [[
            {field:'salesman_id', title:'Kode', width:200},
            {field:'salesman_name', title:'Customer', width:300},
        ]]
    });
    var gr =  $('#salesman_id').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
}

function populateCustomer() {
   $('#customer_code').combogrid({
        idField: 'customer_code',
        textField:'customer_code',
        url:base_url+"customer/load_grid?golongan=Customer Online",
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
            //console.log("select",rw);
            if(rw.customer_code==="") return
            $('#salesman_id').combogrid('setValue',rw.salesman_id)
            $('#disc1_persen').numberbox('setValue',rw.diskon)
            $('#credit_limit').textbox('setValue',numberFormat(rw.credit_limit))
            $('#outstanding').textbox('setValue',numberFormat(rw.outstanding))
            $('#credit_remain').textbox('setValue',numberFormat(rw.credit_remain))
            //$('#pkp').textbox('setValue',rw.pkp)

            $('#lokasi_stock').textbox('setValue',rw.lokasi_stock)
            $('#provinsi_id').textbox('setValue',rw.provinsi_id)
            $('#provinsi_name').combogrid('setValue',rw.provinsi)
            $('#regency_id').textbox('setValue',rw.regency_id)
            $('#regency_name').combogrid('setValue',rw.kota)

            $('#customer_code').textbox('setValue',rw.customer_code)
            $('#customer_name').textbox('setValue',rw.customer_name)
            $("#customer").show(); 
            $('#so_no').textbox('textbox').focus(); 
          
            // var d = $("#dg").edatagrid('getData');
            // if(d.data.length>0){
            //     $("#customer_name").combogrid({'readonly':true})
            // }

        },
        onLoadSuccess:function(){
                var gr =  $('#customer_code').combogrid('grid')

                var data=gr.edatagrid('getData');
               // console.log(data)
             for(var i =0;i < data.rows.length;i++){
                var rw=data.rows[i];
                //console.log('ds',rw)
                if(rw.customer_code==customer_code){
                    $('#salesman_id').combogrid('setValue',rw.salesman_id)
                    $('#disc1_persen').numberbox('setValue',rw.diskon)
                    $('#credit_limit').textbox('setValue',numberFormat(rw.credit_limit))
                    $('#outstanding').textbox('setValue',numberFormat(rw.outstanding))
                    $('#credit_remain').textbox('setValue',numberFormat(rw.credit_remain))
                    //$('#pkp').textbox('setValue',rw.pkp)

                    $('#lokasi_stock').textbox('setValue',rw.lokasi_stock)
                    $('#provinsi_id').textbox('setValue',rw.provinsi_id)
                    $('#provinsi_name').combogrid('setValue',rw.provinsi)
                    $('#regency_id').textbox('setValue',rw.regency_id)
                    $('#regency_name').combogrid('setValue',rw.kota)

                    $('#customer_code').textbox('setValue',rw.customer_code)
                    $('#customer_name').textbox('setValue',rw.customer_name)
                    $("#customer").show(); 
                    $('#so_no').textbox('textbox').focus(); 
                }
            }
          // if(customer_code!==''){
          //        $('#customer_code').combogrid('setValue',customer_code);
          //        $('#customer_name').textbox('setValue',customer_name);
          //   }
        },
        columns: [[
            {field:'customer_code', title:'Kode', width:200},
            {field:'customer_name', title:'Customer', width:300},
        ]]
    });
    var gr =  $('#customer_code').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
    gr.datagrid('addFilterRule', {
        field: 'gol_customer',
        op: 'equal',
        value: "Customer Online"
    });    
     if(customer_code!==''){
                gr.datagrid('addFilterRule', {
                    field: 'customer_code',
                    op: 'equal',
                    value:customer_code
                });  
            }
     
    gr.datagrid('doFilter');
}
function populateRegency() {
    $('#regency_name').combogrid({
        idField: 'id',
        textField:'name',
        url:base_url+"wilayah2/load_grid2",
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
            if (data.data) {
                data.rows = data.data;
            }
            return data;
        },
        onSelect:function (index, rw) {
            //console.log("select",rw);
            if(rw.regency_id==="") return
            $('#provinsi_id').textbox('setValue',rw.province_id)
            $('#provinsi_name').combogrid('setValue',rw.province_name)

            var gr =  $('#customer_code').combogrid('grid');
            gr.datagrid('addFilterRule', {
                field: 'regency_id',
                op: 'equal',
                value: rw.id
            });
            gr.datagrid('addFilterRule', {
                field: 'gol_customer',
                op: 'equal',
                value: "Wholesales"
            });
            gr.datagrid('doFilter');
        },
        columns: [[
            {field:'id', title:'', width:75},
            {field:'name', title:'Kabupatan', width:175},
            {field:'province_id', title:'', width:75},
            {field:'province_name', title:'Provinsi', width:175}
        ]]
    });
    var gr =  $('#regency_name').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');

    $('#provinsi_name').combogrid({
        idField: 'province_id',
        textField:'province_name',
        url:base_url+"wilayah2/load_grid2",
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
            //console.log("select",rw);
            if(rw.province_id==="") return
            $('#regency_id').textbox('setValue',rw.id)
            $('#regency_name').combogrid('setValue',rw.name)

            var gr =  $('#customer_code').combogrid('grid');
            gr.datagrid('addFilterRule', {
                field: 'regency_id',
                op: 'equal',
                value: rw.id
            });
            gr.datagrid('addFilterRule', {
                field: 'gol_customer',
                op: 'equal',
                value: "Wholesales"
            });
            gr.datagrid('doFilter');
        },
        columns: [[
            {field:'province_id', title:'', width:75},
            {field:'province_name', title:'Provinsi', width:175},
            {field:'id', title:'', width:75},
            {field:'name', title:'Kabupatan', width:175}
        ]]
    });
    var gr =  $('#provinsi_name').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
}