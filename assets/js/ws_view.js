var so_item=undefined;
var flag = "";
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

$(document).ready(function () {
    so_item = undefined;
    populateJenisSO();
    populateBaseSO();

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
    $.ajax({
        type:"POST",
        url:base_url+"wholesales/read_data/"+docno,
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
                        window.location.href = base_url+"wholesales";
                    }
                });
            }

        }
    });

});

function initHeader() {
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
    disable_enable(true)
}
var timer=null;
function initGrid() {
    if(!so_item) return
    $("#dg").edatagrid({
        fitColumns: false,
        width: "100%",
        url: base_url + "wholesales/load_grid_detail/"+so_item.id,
        saveUrl: base_url + "wholesales/save_data_detail/"+so_item.id,
        updateUrl: base_url + "wholesales/edit_data_detail",
        destroyUrl: base_url + "wholesales/delete_data_detail",
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
            $("#dg").edatagrid('cancelRow');
        },
        onBeforeEdit: function (index, row) {
            $("#dg").edatagrid('cancelRow');
        },
        columns: [[
            {
                field: "nobar",
                title: "Article#",
                width: '9%',
                sortable: true,
                formatter: function (value, row) {
                    return row.product_code;
                }
            },
            {field: "product_id", title: "Product Id", width: '8%', sortable: true},
            {field: "nmbar", title: "Product Name", width: '12%', sortable: true},
            {field: "tipe", title: "Type", width: '5%', sortable: true},
            {field: "qty_order", title: "Qty Ord", width: '6%', sortable: true},
            {field: "qty_on_sales", title: "Qty Sls", width: '6%', sortable: true},
            {field: "satuan_jual", title: "UOM", width: '6%', sortable: true, formatter:function(index, row){
                    return row.uom_id;
                }},
            {field: "unit_price", title: "Retail", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.unit_price);
                }},
            {field: "disc1_persen", title: "Disc1%", width: '6%', sortable: true},
            {field: "disc1_amount", title: "Amt1", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc1_amount);
                }},
            {field: "disc2_persen", title: "Disc2%", width: '6%', sortable: true,},
            {field: "disc2_amount", title: "Amt2", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc2_amount);
                }},
            {field: "disc3_persen", title: "Disc3%", width: '6%', sortable: true,
            },
            {field: "disc3_amount", title: "Amt3", width: '9%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc3_amount);
                }, editor: {type: 'numberbox',options:{readonly:true}}},
            {field: "disc_total", title: "Total Disc", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.disc_total)}},
            {field: "bruto_before_tax", title: "Sls Bfr Tax", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.bruto_before_tax)}},
            {field: "total_tax", title: "PPN", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.total_tax)}},
            {field: "net_unit_price", title: "Net Aft PPN", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.net_unit_price)}},
            {field: "net_total_price", title: "Sls Aft PPN", width: '10%', sortable: true, formatter:function (index, row) {
                    return numberFormat(row.net_total_price)}},
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

function read_packinglist() {
    if(so_item===undefined) return
    $.ajax({
        type:"POST",
        url:base_url+"packinglist/read_data/"+so_item.base_so,
        dataType:"json",
        success:function(result){
            console.log(result)
            if(result){
                var rw = result.data;
                $('#remark').textbox('setValue',rw.docno+' '+rw.remark)
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
                $('#total_discount').textbox('setValue',numberFormat(rw.total_discount))
                $('#sales_before_tax').textbox('setValue',numberFormat(rw.sales_before_tax))
                $('#total_ppn').textbox('setValue',numberFormat(rw.total_ppn))
                $('#sales_after_tax').textbox('setValue',numberFormat(rw.sales_after_tax))
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
                            window.location.href = base_url+"wholesales";
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

function populateBaseSO() {
    $('#base_so').combogrid({
        idField: 'docno',
        textField:'docno',
        url:base_url+"packinglist/load_grid",
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
            $('#remark').textbox('setValue',rw.docno+' '+rw.remark)
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
            {field:'so_number', title:'SO Number', width:100},
            {field:'ak_tgl_so', title:'Tanggal SO', width:90},
            {field:'docno', title:'PL Number', width:100},
            {field:'ak_doc_date', title:'Tanggal PL', width:90},
            {field:'remark', title:'Keterangan', width:130},
            {field:'status', title:'Status', width:100},
        ]]
    });
    var gr =  $('#base_so').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
    gr.datagrid('removeFilterRule', 'status');
    gr.datagrid('addFilterRule', {
        field: 'status',
        op: 'equal',
        value: "POSTING"
    });
    gr.datagrid('doFilter');
    gr.edatagrid('hideColumn', 'status');
}

function populateJenisSO() {
    $('#jenis_faktur').combobox({
        data:[
            {value:'SHOWROOM',text:'SHOWROOM'},
            {value:'WHOLESALES',text:'WHOLESALES'},
            {value:'CONSIGNMENT',text:'CONSIGNMENT'},
            {value:'SALES ONLINE',text:'SALES ONLINE'}
        ],
        prompt:'-Please Select-',
        validType:'inList["#jenis_faktur"]',
    });

    $('#jenis_faktur').combobox('readonly',true)
    $('#jenis_faktur').combobox('setValue','WHOLESALES')
}