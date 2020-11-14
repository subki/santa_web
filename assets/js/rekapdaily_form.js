var so_item=undefined;
var flag = "";
// (function($){
    function formatValue(target){
        var t = $(target);
        var opts = t.textbox('options');
        var v = t.textbox('getValue');
        console.log("opts",opts.formatter)
        if (opts.formatter && v) {
            var v = opts.formatter.call(target, v);
            t.textbox('setText', v);
        }
    }
//     var focusHandler = $.fn.textbox.defaults.inputEvents.focus;
//     var blurHandler = $.fn.textbox.defaults.inputEvents.blur;
//     $.extend($.fn.textbox.defaults.inputEvents, {
//         focus: function(e){
//             if (focusHandler){focusHandler(e);}
//             var t = $(e.data.target);
//             var v = t.textbox('getValue');
//             if (v){t.textbox('setText', v);}
//         },
//         blur: function(e){
//             if (blurHandler){blurHandler(e);}
//             formatValue(e.data.target);
//         }
//     });
//     var initValue = $.fn.textbox.methods.initValue;
//     $.fn.textbox.methods.initValue = function(jq, value){
//         initValue.call($.fn.textbox.methods, jq, value);
//         jq.each(function(){
//             formatValue(this);
//         })
//     }
// })(jQuery);

$(document).ready(function () {
    so_item = undefined;

    // populateRegency();
    populateCustomer();
    // populateSalesman();
    // populateLocation();
    // populateStore();
    populateJenisSO();
    // populateVerifyFA();
   // populateBaseSO();

    $("#no_faktur").textbox({
        inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
            focus: function(e){ 
                var t = $(e.data.target);
                var v = t.textbox('getValue');
                if (v){t.textbox('setText', v);}
            },
            blur: function(e){
                var t = $(e.data.target);
                // formatValue(e.data.target);
                var v = t.textbox('getValue');
                if (v){
                    if(v.length>7){
                        var seri = v.substring(0, 3)+"."+v.substring(3,7)+"."+v.substring(7,9)+"."+v.substring(9, v.length);
                        t.textbox('setText', seri)
                    }else t.textbox('setText', v);
                }
            }
        })
    })

    $("#no_faktur2").textbox({
        inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
            focus: function(e){
                var t = $(e.data.target);
                var v = t.textbox('getValue');
                if (v){t.textbox('setText', v);}
            },
            blur: function(e){
                var t = $(e.data.target);
                // formatValue(e.data.target);
                var v = t.textbox('getValue');
                if (v){
                    if(v.length>7){
                        var seri = v.substring(0, 3)+"."+v.substring(3,7)+"."+v.substring(7,9)+"."+v.substring(9, v.length);
                        t.textbox('setText', seri)
                    }else t.textbox('setText', v);
                }
            }
        })
    })

    if(aksi==="add"){
        flag = "Rekapdaily/save_data_header";
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
        $("#doc_date").datebox('setValue', tgl);
        $("#doc_date").datebox('setText', tgl);
        $("#faktur_date").datebox('setValue', tgl);
        $("#faktur_date").datebox('setText', tgl);
        $("#dis_faktur1").show();
        $("#dis_faktur2").hide();

        $("#store_code").combogrid('setValue',store_code);
        $("#location_code").combogrid('setValue',location_code);
        $("#status").textbox('setValue','OPEN');
        $("#qty_print").textbox('setValue','0');
        $("#update").hide();
        $("#posting").hide();
        $("#close").hide();
        $("#print").hide();
        $("#customer").hide();
        $("#crt_faktur").hide();
        $("#btn_seri_pajak").hide();
    }else{
        flag = "Rekapdaily/edit_data_header";
        $.ajax({
            type:"POST",
            url:base_url+"Rekapdaily/read_data/"+docno,
            dataType:"json",
            success:function(result){
                console.log(result.data)
                if(result.status===0) {
                    $('#fm').form('load',result.data);
                    so_item = result.data;
                    initHeader()
                }
                else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.msg,
                        handler:function () {
                            window.location.href = base_url+"Rekapdaily";
                        }
                    });
                }

            }
        });
    }
});

function initHeader() {
    $('#regency_id').textbox('setValue',so_item.regency_id)
    $('#provinsi_id').textbox('setValue',so_item.provinsi_id)
    $('#regency_name').combogrid('setValue',so_item.regency)
    $('#provinsi_name').combogrid('setValue',so_item.provinsi)
    $('#customer_code').combogrid('setValue',so_item.customer_code)
    $('#sales').textbox('setText','ONLINE')

    $('#credit_limit').textbox('setValue', numberFormat(so_item.credit_limit))
    $('#outstanding').textbox('setValue', numberFormat(so_item.outstanding))
    $('#credit_remain').textbox('setValue', numberFormat(so_item.credit_remain))

    $('#gross_sales').textbox('setValue', numberFormat(so_item.gross_sales))
    $('#total_discount').textbox('setValue', numberFormat(so_item.total_disc))
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
    if(so_item.status==="CLOSED"){
        $("#posting").linkbutton({text:"Unposting"});
        $("#posting").show();
        $("#close").hide();
        $("#update").show();
    }
    if(so_item.pkp==="YES"){
        if(so_item.beda_fp==="YES") {
            $("#crt_faktur").show();
            $("#dis_faktur1").show();
            $("#dis_faktur2").hide();
        }else {
            $("#crt_faktur").hide();
            $("#dis_faktur1").hide();
            $("#dis_faktur2").show();
        }
        if(so_item.no_faktur.length>7 && so_item.no_faktur2.length>7){
            $("#crt_faktur").hide();
            $("#dis_faktur1").hide();
            $("#dis_faktur2").show();
        }
        if(so_item.seri_pajak!==""){
            var str = so_item.seri_pajak;
            var seri = str.substring(0, 3)+"."+str.substring(3,6)+"-"+str.substring(6,8)+"."+str.substring(8, str.length);
            $("#seri_pajak_formatted").textbox('setValue',seri)
            $("#btn_seri_pajak").hide();
            $("#vseri").show();
        }else{
            $("#btn_seri_pajak").show();
            $("#vseri").hide();
        }
    }else{
        if(so_item.no_faktur.length>7 && so_item.no_faktur2.length>7){
            $("#crt_faktur").hide();
            $("#dis_faktur1").hide();
            $("#dis_faktur2").show();
        }else {
            $("#crt_faktur").hide();
            $("#dis_faktur1").show();
            $("#dis_faktur2").hide();
        }
    }
    console.log(so_item.verifikasi_finance);
    if(so_item.verifikasi_finance==="OPEN" || so_item.verifikasi_finance==="" || so_item.verifikasi_finance===null || so_item.verifikasi_finance===undefined){
        $("#verify_fa").hide();
    }else{
        $("#verify_fa").hide();
    }
    if(so_item.no_faktur.length>7){
        $("#no_faktur").textbox('readonly',true);
    }
    if(so_item.no_faktur.length>7 && so_item.no_faktur2.length>7){
        $("#crt_faktur").hide();
    }else{
        $("#crt_faktur").show();
    }
    $("#close").hide();
    read_packinglist();
    $('#no_faktur').textbox('textbox').focus();
    $('#no_faktur2').textbox('textbox').focus();
    $('#seri_pajak_formatted').textbox('textbox').focus();
}
function verifyFA() {
    $.ajax({
        type:"POST",
        url:base_url+"Rekapdaily/update_finance_verify",
        dataType:"json",
        data:so_item,
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                reload_header()
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"Rekapdaily";
                    }
                });
            }

        }
    });
}

function checkedFP(isChecked) {
    console.log(isChecked);
    if($("#pkp").textbox('getValue')==="YES"){
        var date = new Date();
        var y = date.getFullYear();
        if(isChecked){
            if(aksi==="add"){
                $("#no_faktur").textbox('setValue','SGI'+y)
                $("#no_faktur2").textbox('setValue','')
            }else{
                if(so_item!==undefined){
                    if(so_item.no_faktur===""){
                        $("#no_faktur").textbox('setValue','SGI'+y)
                        $("#no_faktur2").textbox('setValue','SGI'+y)
                    }
                }
            }
            $('#dis_faktur1').show();
            $('#dis_faktur2').hide();
        }else{
            if(aksi==="add"){
                $("#no_faktur").textbox('setValue','')
                $("#no_faktur2").textbox('setValue','IVS'+y)
            }else{
                if(so_item!==undefined){
                    console.log(so_item.no_faktur)
                    console.log(so_item.no_faktur2)
                    // if(so_item.no_faktur.length===7){
                    //     $("#no_faktur").textbox('setValue','SGI'+y)
                    // }
                    if(so_item.no_faktur2.length===7){
                        $("#no_faktur2").textbox('setValue','SGI'+y)
                    }
                }
            }
            $('#dis_faktur1').hide();
            $('#dis_faktur2').show();
        }
    }
}
function createFaktur() {
    checkedFP(false)
}
function createSeriPajak() {
    if(so_item===undefined) return
    $.ajax({
        type:"POST",
        url:base_url+"Rekapdaily/get_seripajak",
        dataType:"json",
        data:so_item,
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                reload_header()
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"Rekapdaily";
                    }
                });
            }

        }
    });
}
function printSOss() {
    window.open(base_url+'Rekapdaily/print_so/'+docno, '_blank');
}


var print_selected = undefined;
function printSO() {
    print_selected = undefined;
    $.messager.confirm({
        title:'Option print',
        msg:`<p>Select Print Option</p>
            <input class="easyui-combobox" data-options="
                valueField: 'label',
                textField: 'value',
                data: [{
                    label: '1',
                    value: 'Invoice',
                    selected:true
                },{
                    label: '2',
                    value: 'Surat Jalan'
                }],
                onSelect:function(rec){
                    print_selected = rec;
                }"
                 />`,
        fn: function(r){
            if (r){
                let urlss = `${base_url}Rekapdaily/print_ws?id=${so_item.id}&tipe=${print_selected.label}&pkp=${so_item.pkp}`;
                window.open(urlss, '_blank')
            }
        }
    });
}

function reload_header() {
    $.ajax({
        type:"POST",
        url:base_url+"Rekapdaily/read_data/"+docno,
        dataType:"json",
        success:function(result){
            console.log(result.data)
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
                        window.location.href = base_url+"Rekapdaily";
                    }
                });
            }

        }
    });
}
var timer=null;
function initGrid() {
    if(!so_item) return
    $("#dg").edatagrid({
        fitColumns: false,
        width: "100%",
        url: base_url + "Rekapdaily/load_grid_detail/"+so_item.id,
        saveUrl: base_url + "Rekapdaily/save_data_detail/"+so_item.id,
        updateUrl: base_url + "Rekapdaily/edit_data_detail",
        destroyUrl: base_url + "Rekapdaily/delete_data_detail",
        idField: 'id',
        method: "POST",
        pagePosition: "top",
        resizeHandle: "right",
        resizeEdge: 10,
        pageSize: 20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination: true,
        sortName: "id",
        sortOrder: "desc",
        singleSelect: true, nowrap:false,
        toolbar: [
            // {
            // iconCls: 'icon-add', id:'add', text:'New',
            // handler: function(){$('#dg').edatagrid('addRow',0)}
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

                var ed = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc1_persen'});
                var disc1 = $(ed.target).numberbox('getValue');

                ed = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc2_persen'});
                var disc2 = $(ed.target).numberbox('getValue');

                ed = $('#dg').edatagrid('getEditor', {index: rowIndex, field: 'disc3_persen'});
                var disc3 = $(ed.target).numberbox('getValue');

                var h_d1 = $("#disc1_persen").numberbox('getValue');
                var h_d2 = $("#disc2_persen").numberbox('getValue');
                var h_d3 = $("#disc3_persen").numberbox('getValue');

                if(disc1==="" || isNaN(disc1)) disc1 = 0;
                if(disc2==="" || isNaN(disc2)) disc2 = 0;
                if(disc3==="" || isNaN(disc3)) disc3 = 0;

                if(h_d1==="" || isNaN(h_d1)) h_d1 = 0;
                if(h_d2==="" || isNaN(h_d2)) h_d2 = 0;
                if(h_d3==="" || isNaN(h_d3)) h_d3 = 0;

                var confirm = false
                if(h_d1!=="" && parseFloat(h_d1)>0 && parseFloat(h_d1) !== parseFloat(disc1)){
                    console.log("true 1")
                    confirm = true
                }else if(h_d2!=="" && parseFloat(h_d2)>0 && parseFloat(h_d2) !== parseFloat(disc2)){
                    console.log("true 2")
                    confirm = true
                }else if(h_d3!=="" && parseFloat(h_d3)>0 && parseFloat(h_d3) !== parseFloat(disc3)){
                    console.log("true 3")
                    confirm = true
                }

                if(confirm){
                    var dlg = $.messager.confirm({
                        title: 'Confirm',
                        msg: 'Discount detail dan header berbeda. tekan Yes untuk tetap melanjutkan?',
                        buttons:[{
                            text: 'Yes',
                            onClick: function(){
                                $('#dg').edatagrid('saveRow')
                                dlg.dialog('destroy')
                            }
                        },{
                            text: 'No',
                            onClick: function(){
                                dlg.dialog('destroy')
                            }
                        }]
                    });
                }else $('#dg').edatagrid('saveRow')

            }
        },{
            id:'cancel', iconCls: 'icon-undo', text:'Cancel',
            handler: function(){$('#dg').edatagrid('cancelRow')}
        },
        ],
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
        onBeginEdit: function (index, row) {
            if (row.isNewRecord ){
                if(so_item.status!=="OPEN") {
                    $.messager.show({
                        title: 'Warning',
                        msg: "Rekapdaily sudah di posting"
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
            console.log("masuk ga")
            var editor = $(this).edatagrid('getEditor', {index: index, field: 'nobar'});
            var grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');


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

                editor = $(this).edatagrid('getEditor', {index: index, field: 'disc3_persen'});
                $(editor.target).numberbox('setValue', so_item.disc3_persen);
                // if(parseFloat(so_item.disc3_persen)>0){
                //     $(editor.target).numberbox({disabled:false, readonly:true});
                // }
            }


        },
        onBeforeEdit: function (index, row) {
            if (row.isNewRecord) return
            if(so_item.status!=="OPEN") {
                $.messager.show({
                    title: 'Warning',
                    msg: "Data tidak bisa di edit"
                });
                setTimeout(function () {
                    $("#dg").edatagrid('cancelRow');
                }, 500)
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
                    type: 'combogrid',
                    options: {
                        readonly: false,
                        idField: 'nobar',
                        textField: 'product_code',
                        url: `${base_url}salesorder/get_product?doc_date=${so_item.doc_date}&lokasi=${so_item.location_code}`,
                        required: true,
                        hasDownArrow: false,
                        remoteFilter: true,
                        panelWidth: 800,
                        multiple: false,
                        panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
                            mousedown: function () {
                            }
                        }),
                        editable: false,
                        pagination: true,
                        loadFilter: function (data) {
                            data.rows = [];
                            if (data.data) {
                                data.rows = data.data;
                            }
                            return data;
                        },
                        onSelect: function (index, row) {
                            console.log(row)
                            var datas = $("#dg").edatagrid("getData");
                            console.log("subki",datas)
                            var jml_flg = 0;
                            for(var i=0; i<datas.data.length; i++){
                                if(datas.data[i].nobar && datas.data[i].nobar===row.nobar){
                                    jml_flg++;
                                }
                            }

                            var selectedrow = $("#dg").edatagrid("getSelected");
                            var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);

                            var ed = $('#dg').edatagrid('getEditor', {
                                index: rowIndex,
                                field: 'nmbar'
                            });
                            $(ed.target).textbox('setValue', row.nmbar);

                            var ed = $('#dg').edatagrid('getEditor', {
                                index: rowIndex,
                                field: 'product_id'
                            });
                            $(ed.target).textbox('setValue', row.product_id);

                            ed = $('#dg').edatagrid('getEditor', {
                                index: rowIndex,
                                field: 'satuan_jual'
                            });
                            $(ed.target).textbox('setValue', row.satuan_jual);
                            $(ed.target).textbox('setText', row.id_jual);


                            if(jml_flg>0){
                                ed = $('#dg').edatagrid('getEditor', {
                                    index: rowIndex,
                                    field: 'tipe'
                                });
                                $(ed.target).textbox('setValue', jml_flg);
                                $(ed.target).textbox('setText', jml_flg);
                            }

                            get_unit_price(row, rowIndex, function(res){})

                        },
                        columns: [[
                            // {field: 'article_code', title: 'Article', width: 100},
                            // {field: 'nobar', title: 'SKU', width: 150},
                            {field: 'product_code', title: 'Product Code', width: 100},
                            {field: 'nmbar', title: 'Product Name', width: 300},
                            {field: 'stock', title: 'Stock', formatter:function (index, row) {
                                    return row.stock+" "+row.uom_stock
                                }, width: 150},
                        ]],
                        fitColumns: true,
                        labelPosition: 'center'
                    }
                }
            },
            {field: "product_id", title: "Product Id", width: '8%', sortable: true, editor: {type: 'textbox',options:{readonly:true}}},
            {field: "nmbar", title: "Product Name", width: '12%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "tipe", title: "Type", width: '5%', sortable: true, editor: {type: 'textbox'}},
            {field: "qty_order", title: "Qty Ord", width: '6%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "qty_on_sales", title: "Qty Sls", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options:{
                        required:true,
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
                                    console.log("uki",prc)
                                    if(prc==null || prc==="" || isNaN(prc) || parseFloat(prc)===0){
                                        console.log("uki","masuk")
                                        ed = $('#dg').edatagrid('getEditor', {
                                            index: rowIndex,
                                            field: 'product_id'
                                        });
                                        var prd_id = $(ed.target).textbox('getValue');
                                        var row = {product_id:prd_id};
                                        get_unit_price(row, rowIndex, function (res) {
                                            console.log("prc", res)
                                            keyupnumber(e,'qty_order');
                                        })
                                    }else{
                                        console.log("uki","masuk luar")
                                        keyupnumber(e,'qty_order');
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
                    options:{
                        min:2, precision:2,
                        formatter:formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                keyupnumber(e,'disc1_persen');
                            },
                        })
                    }
                }
            },
            {field: "disc1_amount", title: "Amt1", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc1_amount);
                }, editor: {type: 'numberbox',options:{readonly:true,min:2, precision:2,}}},
            {field: "disc2_persen", title: "Disc2%", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options: {
                        min: 0, precision: 2,
                        formatter: formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup: function (e) {
                                keyupnumber(e, 'disc2_persen');
                            },
                        })
                    }
                }
            },
            {field: "disc2_amount", title: "Amt2", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc2_amount);
                }, editor: {type: 'numberbox',options:{readonly:true,min:2, precision:2,}}},
            {field: "disc3_persen", title: "Disc3%", width: '6%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options: {
                        min: 0, precision: 2,
                        formatter: formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup: function (e) {
                                keyupnumber(e, 'disc3_persen');
                            },
                        })
                    }
                }
            },
            {field: "disc3_amount", title: "Amt3", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc3_amount);
                }, editor: {type: 'numberbox',options:{readonly:true}}},
            {field: "disc_total", title: "Total Disc", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc_total);
                }, editor: {type: 'numberbox',options:{readonly:true, formatter:formatnumberbox}}},
            {field: "bruto_before_tax", title: "Sls Bfr Tax", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.bruto_before_tax);
                }, editor: {type: 'numberbox',options:{readonly:true,formatter:formatnumberbox}}},
            {field: "total_tax", title: "PPN", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.total_tax);
                }, editor: {type: 'numberbox',options:{readonly:true, formatter:formatnumberbox}}},
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
        }
    })
    $('#dg').edatagrid('hideColumn','product_id');
    $('#dg').edatagrid('hideColumn','net_total_price');
    $('#dg').edatagrid('hideColumn','net_unit_price');
    $('#dg').edatagrid('hideColumn','disc1_amount');
    $('#dg').edatagrid('hideColumn','disc2_amount');
    $('#dg').edatagrid('hideColumn','disc3_amount');
}

function keyupnumber(e, field){
    var selectedrow = $("#dg").edatagrid("getSelected");
    var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);

    let arr = ['qty_order','disc1_persen','disc2_persen','unit_price','disc3_persen'];
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
    var disc1_amount = n[3] * (n[1]/100)
    var tot1 = n[3]-(n[3]-disc1_amount)

    var disc2_amount = tot1 * (n[2]/100)
    var tot2 = tot1+disc2_amount

    var disc3_amount = tot2 * (n[4]/100)
    var tot3 = tot2+disc3_amount

    var disc_tot = tot3
    var gross = n[3]-disc_tot
    var ppn = 0;
    let pkp = $('#pkp').textbox('getValue');
    if(pkp==="YES") ppn = gross * 10/100;
    var net_unit = gross+ppn
    var net_total = net_unit*n[0]

    var ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc1_amount'});
    $(ed.target).numberbox('setValue',isNaN(disc1_amount)?0:disc1_amount);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc2_amount'});
    $(ed.target).numberbox('setValue',isNaN(disc2_amount)?0:disc2_amount);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc3_amount'});
    $(ed.target).numberbox('setValue',isNaN(disc3_amount)?0:disc3_amount);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'disc_total'});
    $(ed.target).numberbox('setValue',isNaN(disc_tot)?0:disc_tot);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'bruto_before_tax'});
    $(ed.target).numberbox('setValue',isNaN(gross)?0:gross);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'total_tax'});
    $(ed.target).numberbox('setValue',isNaN(ppn)?0:ppn);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'net_unit_price'});
    $(ed.target).numberbox('setValue',isNaN(net_unit)?0:net_unit);

    ed = $('#dg').edatagrid('getEditor',{index:rowIndex, field:'net_total_price'});
    $(ed.target).numberbox('setValue',isNaN(net_total)?0:net_total);

}
function get_unit_price(row, index, callback) {
    $.ajax({
        type:"POST",
        url: `${base_url}salesorder/get_unit_price?product_id=${row.product_id}&tanggal=${so_item.doc_date}&lokasi=${so_item.lokasi_stock}&customer_code=${so_item.customer_code}`,
        dataType:"json",
        success:function(result){
            console.log(result)
            var ed = $('#dg').edatagrid('getEditor', {
                index: index,
                field: 'disc1_persen'
            });
            if(parseFloat(result.diskon)>0){
                $(ed.target).numberbox('setValue', parseFloat(result.diskon));
            }
            ed = $('#dg').edatagrid('getEditor', {
                index: index,
                field: 'unit_price'
            });
            if(parseFloat(result.unit_price)>0) {
                var disc = parseFloat(result.unit_price)*(parseFloat(result.diskon)/100);
                var perunit = parseFloat(result.unit_price)-disc;
                $(ed.target).textbox('setValue', perunit);
            }else{
                $.messager.alert("Error","Unit Price Belum tersedia")
                $(ed.target).textbox('setValue', 0);
            }
            callback(result)
        }
    });
}

function submit(stt){
    console.log(stt);
    console.log(base_url+flag)
    let status = (stt==="")?(so_item)?so_item.status:'OPEN':stt;
    console.log(status);
    if(so_item!==undefined && so_item.status==="CLOSED" && status==="CLOSED" && stt!=="") status = 'OPEN';
    console.log(status)
    $('#status').textbox('setValue',status);

    if(aksi==="add"){
        submit_reason("")
    }else{
        if(so_item!==undefined){
            if(so_item.status==="CLOSED" && status==="OPEN"){
                if(global_auth[global_auth.appId].allow_unposting==="0"){
                    $.messager.show({title:'Error', msg:'Anda tidak memiliki otoritas Unposting'});
                    $('#status').textbox('setValue',so_item.status);
                }else {
                    $.messager.prompt({
                        title: 'Reason Unposting',
                        msg: 'Input reason unposting sales:',
                        fn: function (r) {
                            if (r) {
                                submit_reason(r);
                            }
                        }
                    });
                }
            }else if(so_item.status === status){
                submit_reason("")
            }else{
                myConfirm("Confirm", "Anda yakin ingin mengubah status Rekapdaily ini?", "Yes", "No", function (r) {
                    console.log(r)
                    if (r === "Yes") {
                        submit_reason("")
                    }
                })
            }
        }
    }
}
function read_packinglist() {
    if(so_item===undefined) return
    $.ajax({
        type:"POST",
        url:base_url+"Rekapdaily/read_data/"+docno,
        dataType:"json",
        success:function(result){
            console.log(result.data)
            if(result){
                var rw = result.data;
                $('#remark').textbox('setValue',rw.no_faktur+' '+rw.remark)
                $('#customer_name').textbox('setValue',rw.customer_name)
                $('#customer_code').combogrid('setValue',rw.customer_code)
                $('#pkp').textbox('setValue',rw.pkp)
                $('#beda_fp').textbox('setValue',rw.beda_fp)
                $('#salesman_id').textbox('setValue',rw.salesman_id)
                $('#so_number').textbox('setValue',rw.so_number)
                $('#disc1_persen').numberbox('setValue',rw.disc1_persen)
                $('#disc2_persen').numberbox('setValue',rw.disc2_persen)
                $('#disc3_persen').numberbox('setValue',rw.disc3_persen)
                $('#qty_item').textbox('setValue',rw.qty_item)
                $('#qty_order').textbox('setValue',rw.qty_order)
                $('#gross_sales').textbox('setValue',numberFormat(rw.gross_sales))
                $('#total_discount').textbox('setValue',numberFormat(rw.total_disc))
                $('#sales_before_tax').textbox('setValue',numberFormat(rw.sales_before_tax))
                $('#total_ppn').textbox('setValue',numberFormat(rw.total_ppn))
                $('#sales_after_tax').textbox('setValue',numberFormat(rw.sales_after_tax))
            }
        }
    });

}

function cancel(){
    myConfirm("Confirm", "Anda yakin ingin mengubah status sales ini?", "Yes", "No", function (r) {
        if (r === "Yes") {
            $('#status').textbox('setValue',"CANCEL");
            submit_reason("")
        }
    })
}

function openCopy() {
    if(so_item==null) return
    $('#dg').edatagrid({toolbar:'#toolbar23'});
    $("#combo").combogrid({
        idField: 'docno',
        textField:'docno',
        disabled:false,
        required:true,
        readonly:false,
        url:base_url+"Salesonline/load_grid",
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
            console.log(data.rows)
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
    console.log(xx)
    $.ajax({
        url: base_url+"salesorder/copy_detail",
        type: 'post',
        data: {
            from:xx,
            to:so_item.docno
        },
        success: function(result){
            console.log(result);
            var res = $.parseJSON(result);
            if (res.status===1){
                alert(res.msg)
            }
            $('#dd').edatagrid('reload');
            cancelUpload();
        }
    });
    cancelUpload()
}
function cancelUpload() {
    $('#toolbar23').hide();
}
function submit_reason(reason) {
    console.log(reason);
    $("#reason").textbox('setValue', reason);
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    var stt = $('#status').textbox('getValue');
                    if(stt==="POSTING") {
                        myConfirm("Success", "Posting berhasil, apakah anda ingin mencetak doc.?", "Cetak", "Tidak", function (r) {
                            if (r === "Cetak") {
                                printSO()
                                window.location.href = base_url + "Rekapdaily/form/edit?id=" + res.id
                            }else window.location.href = base_url + "Rekapdaily/form/edit?id=" + res.id

                        })
                    }else{
                        window.location.href = base_url + "Rekapdaily/form/edit?id=" + res.id
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
    console.log("disini", so_item)
    if(aksi==="edit"){
        $.ajax({
            type:"POST",
            url:base_url+"customer/read_data/"+so_item.customer_code,
            dataType:"json",
            success:function(result){
                console.log(result.data)
                if(result.status===0) {
                    showCustomer2(result.data)
                }
                else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.message,
                        handler:function () {
                            window.location.href = base_url+"Rekapdaily";
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
function showCustomer2(r) {
    if(!r) return
    var msg = `
    <table>
        <tr style="vertical-align: text-top">
            <td>Name</td>
            <td> : </td>
            <td>${r.customer_name}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Address</td>
            <td> : </td>
            <td>${r.address1}<br />${r.address2}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Wilayah</td>
            <td> : </td>
            <td>${r.kota} - ${r.provinsi}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>ZIP</td>
            <td> : </td>
            <td>${r.zip}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Phone1</td>
            <td> : </td>
            <td>${r.phone1}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Fax</td>
            <td> : </td>
            <td>${r.fax}</td>
        </tr>
    </table>
    `;
    $.messager.alert("Customer Info",msg);
}

// function populateBaseSO() {
//     $('#customer_code').combogrid({
//         idField: 'customer_code',
//         textField:'customer_name',
//         url:base_url+"customer/load_grid",
//         // required:true,
//         labelPosition:'top',
//         tipPosition:'bottom',
//         hasDownArrow: false,
//         remoteFilter:true,
//         panelWidth: 700,
//         multiple:false,
//         panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
//             mousedown: function(){}
//         }),
//         editable: false,
//         pagination: true,
//         fitColumns: true,
//         mode:'remote',
//         loadFilter: function (data) {
//              console.log(data)
//             data.rows = [];
//             if (data.data) data.rows = data.data;
//             return data;
//         },
//         onSelect:function (index, rw) {
//             console.log("select",rw);

//             if(rw.customer_code==="") return
//             $('#remark').textbox('setValue',rw.docno+' '+rw.remark)
//             $('#customer_name').textbox('setValue',rw.customer_name)
//             $('#customer_code').combogrid('setValue',rw.customer_code)
//             $('#pkp').textbox('setValue',rw.pkp)
//             $('#beda_fp').textbox('setValue',rw.beda_fp)
//             $('#sales').textbox('setValue',rw.sales)
//         //    $('#so_no').textbox('setValue',rw.so_no)
//             $('#disc1_persen').numberbox('setValue',rw.disc1_persen)
//             $('#disc2_persen').numberbox('setValue',rw.disc2_persen)
//             $('#disc3_persen').numberbox('setValue',rw.disc3_persen)
//             $('#qty_item').textbox('setValue',rw.qty_item)
//             $('#qty').textbox('setValue',rw.qty)
//             $('#gross_sales').textbox('setValue',numberFormat(rw.gross_sales))
//             $('#total_discount').textbox('setValue',numberFormat(rw.total_discount))
//             $('#sales_before_tax').textbox('setValue',numberFormat(rw.sales_before_tax))
//             $('#total_ppn').textbox('setValue',numberFormat(rw.total_ppn))
//             $('#sales_after_tax').textbox('setValue',numberFormat(rw.sales_after_tax))
//             if(rw.pkp==="YES") {
//                 // $('#qty_deliver').textbox('setValue',rw.qty_deliver)
//                 // $('#service_level').textbox('setValue',rw.service_level)
//                 if (rw.beda_fp === "YES") {
//                     //input nomor sementara (IVS)
//                     checkedFP(false)
//                     // if(aksi=="add"){
//                     //     var date = new Date();
//                     //     var y = date.getFullYear();
//                     //     $("#no_faktur").textbox('setValue','SGI'+y)
//                     // }
//                 } else {
//                     //input nomor faktur (SGI)
//                     checkedFP(true)
//                 }
//             }
//         },
//         columns: [[
//             {field:'customer_code', title:'Kode', width:100},
//             {field:'customer_name', title:'Customer', width:100}, 
//         ]]
//     });
//     var gr =  $('#base_so').combogrid('grid')
//     gr.datagrid('destroyFilter');
//     gr.datagrid('enableFilter');
//     gr.datagrid('removeFilterRule', 'status');
//     gr.datagrid('addFilterRule', {
//         field: 'gol_customer',
//         op: 'equal',
//         value: "Customer Online"
//     });
//     gr.datagrid('doFilter');
//     gr.edatagrid('hideColumn', 'status');
// }

function populateJenisSO() {
    $('#jenis_faktur').combobox({
        data:[ 
            {value:'SALES ONLINE',text:'SALES ONLINE'}
        ],
        prompt:'-Please Select-',
        validType:'inList["#jenis_faktur"]',
    });

    $('#jenis_faktur').combobox('readonly',true)
    $('#jenis_faktur').combobox('setValue','SALES ONLINE')
}
// function populateVerifyFA() {
//     $('#verifikasi_finance').combobox({
//         data:[
//             {value:'OPEN',text:'OPEN'},
//             {value:'VERIFIED',text:'VERIFIED'},
//             {value:'INVOICE',text:'INVOICE'},
//         ],
//         prompt:'-Please Select-',
//         validType:'inList["#verifikasi_finance"]',
//     });
//     $('#verifikasi_finance').combobox('readonly',true)
// }
function populateStore() {
   $('#store_code').combogrid({
        idField: 'store_code',
        textField:'store_name',
        url:base_url+"storeprofile/load_grid",
        // required:true,
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
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);
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
        // required:true,
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
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);
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
        // required:true,
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
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);
        },
        columns: [[
			{field:'salesman_id', title:'Kode', width:200},
			{field:'salesman_name', title:'Customer', width:300},
		]]
    });
    var gr =  $('#salesman_id').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
    $("#salesman_id").combogrid('readonly',true)
}

function populateCustomer() {
  $('#customer_code').combogrid({
        idField: 'customer_code',
        textField:'customer_name',
        url:base_url+"customer/load_grid",
        // required:true,
        labelPosition:'top',
        tipPosition:'bottom',
        hasDownArrow: false,
        remoteFilter:true,
        panelWidth: 700,
        multiple:false,
        panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
            mousedown: function(){}
        }),
        editable: false,
        pagination: true,
        fitColumns: true,
        mode:'remote',
        loadFilter: function (data) {
             console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);

            if(rw.customer_code==="") return
            $('#remark').textbox('setValue','-')
            $('#customer_name').textbox('setValue',rw.customer_name)
            $('#customer_code').combogrid('setValue',rw.customer_code)
            $('#pkp').textbox('setValue',rw.pkp)
            $('#beda_fp').textbox('setValue',rw.beda_fp)
            $('#sales').textbox('setValue',rw.salesman_name)
        //    $('#so_no').textbox('setValue',rw.so_no)
            $('#disc1_persen').numberbox('setValue',rw.disc1_persen)
            $('#disc2_persen').numberbox('setValue',rw.disc2_persen)
            $('#disc3_persen').numberbox('setValue',rw.disc3_persen)
            $('#qty_item').textbox('setValue',rw.qty_item)
            $('#qty').textbox('setValue',rw.qty)
            $('#gross_sales').textbox('setValue',numberFormat(rw.gross_sales))
            $('#total_discount').textbox('setValue',numberFormat(rw.total_discount))
            $('#sales_before_tax').textbox('setValue',numberFormat(rw.sales_before_tax))
            $('#total_ppn').textbox('setValue',numberFormat(rw.total_ppn))
            $('#sales_after_tax').textbox('setValue',numberFormat(rw.sales_after_tax))
            if(rw.pkp==="YES") {
                // $('#qty_deliver').textbox('setValue',rw.qty_deliver)
                // $('#service_level').textbox('setValue',rw.service_level)
                if (rw.beda_fp === "YES") {
                    //input nomor sementara (IVS)
                    checkedFP(false)
                    // if(aksi=="add"){
                    //     var date = new Date();
                    //     var y = date.getFullYear();
                    //     $("#no_faktur").textbox('setValue','SGI'+y)
                    // }
                } else {
                    //input nomor faktur (SGI)
                    checkedFP(true)
                }
            }
        },
        columns: [[
            {field:'customer_code', title:'Kode', width:100},
            {field:'customer_name', title:'Customer', width:100}, 
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
    gr.datagrid('doFilter');
}
function populateRegency() {
    $('#regency_name').combogrid({
        idField: 'id',
        textField:'name',
        url:base_url+"wilayah2/load_grid2",
        // required:true,
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
            console.log(data)
            data.rows = [];
            if (data.data) {
                data.rows = data.data;
            }
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);
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
                value: "Rekapdaily"
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
        // required:true,
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
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);
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
                value: "Rekapdaily"
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