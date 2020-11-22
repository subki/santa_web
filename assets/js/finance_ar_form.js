var so_item=undefined;
var flag = "";
$(document).ready(function () {
    so_item = undefined;

    $('.divide').divide({
        delimiter: '.',
        divideThousand: true, // 1,000..9,999
        delimiterRegExp: /[\.\,\s]/g
    });

    populateCustomer();
    populateStore();
    populateCBType();
    populateDBCR();
    populatePaymentBy();

    if(aksi==="add"){
        flag = "finance/save_header_ar";
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
        $("#payment_date").datebox('setValue', tgl);
        $("#due_date").datebox('setValue', tgl);
        $("#cleared_date").datebox('setValue', tgl);

        $("#status").textbox('setValue','OPEN');
        $("#printno").textbox('setValue','0');
        $("#update").hide();
        $("#submit").show();
    }else{
        flag = "finance/edit_header_ar";
        reload_header()
    }
});
function initHeader() {
    $('#customer_code').combogrid('setValue',so_item.customer_code)

    $("#payment_date").datebox('setValue', formattanggal(so_item.payment_date,{}));
    $("#due_date").datebox('setValue', formattanggal(so_item.due_date,{}));
    $("#cleared_date").datebox('setValue', formattanggal(so_item.cleared_date,{}));

    initGrid();
    $("#update").show();
    $("#submit").hide();
}
function printAR() {
    window.open(base_url+'finance/print_ar/'+docno, '_blank');
}

function reload_header() {
    $.ajax({
        type:"POST",
        url:base_url+"finance/read_data_ar/"+docno,
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
                        window.location.href = base_url+"finance/ar";
                    }
                });
            }

        }
    });
}
function submitAR(){
    console.log(base_url+flag)
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                if (res.status === 0) {
                    window.location.href = base_url + "finance/ar/edit?docno=" + res.docno
                } else {
                    $.messager.show({title: 'Error', msg: res.msg});
                }
            }catch (e) {
                $.messager.show({title: 'Error', msg: e.message});
            }
        }
    });

}

function populatePaymentBy() {
    $('#payment_by').combobox({
        data:[
            {value:'CASH',text:'CASH'},
            {value:'GIRO',text:'GIRO'},
            {value:'TRANSFER',text:'TRANSFER'}
        ],
        prompt:'-Please Select-',
        validType:'inList["#payment_by"]',
    });

}
function populateDBCR() {
    $('#dbcr').combobox({
        data:[
            {value:'DEBET',text:'DEBET'},
            {value:'CREDIT',text:'CREDIT'}
        ],
        prompt:'-Please Select-',
        validType:'inList["#dbcr"]',
    });

}
function populateCBType() {
    $('#cbtype').combobox({
        data:[
            {value:'CASH',text:'CASH'},
            {value:'BANK',text:'BANK'},
            {value:'CEK GIRO',text:'CEK GIRO'},
            {value:'PETTY CASH',text:'PETTY CASH'},
            {value:'DEPOSITO',text:'DEPOSITO'},
            {value:'CN',text:'CN'}
        ],
        prompt:'-Please Select-',
        validType:'inList["#cbtype"]',
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
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        columns: [[
			{field:'store_code', title:'', width:75},
			{field:'store_name', title:'Sales Toko', width:175},
		]]
    });
    var gr =  $('#store_code').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
}

function populateCustomer() {
   $('#customer_code').combogrid({
        idField: 'customer_code',
        textField:'customer_name',
        url:base_url+"customer/load_grid",
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
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
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
        value: "Wholesales"
    });
    gr.datagrid('doFilter');
}