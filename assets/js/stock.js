var options={
    // url: base_url+"productbrand/load_grid",
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
        {field:"nobar",   title:"Nomor Barang",      width:110, sortable: true},
        {field:"nmbar",   title:"Nama Barang",      width: 200, sortable: true},
        {field:"saldo_awal",   title:"Begin",      width: 100, formatter:numberFormat, sortable: true},
        {field:"do_masuk",   title:"DO In",      width: 100, formatter:numberFormat, sortable: true},
        {field:"do_keluar",   title:"DO out",      width: 100, formatter:numberFormat, sortable: true},
        {field:"penyesuaian",   title:"Adjustment",      width: 100, formatter:numberFormat, sortable: true},
        {field:"penjualan",   title:"Sales",      width: 100, formatter:numberFormat, sortable: true},
        {field:"pengembalian",   title:"Return",      width: 100, formatter:numberFormat, sortable: true},
        {field:"saldo_akhir",   title:"Ending",      width: 100, formatter:numberFormat, sortable: true},
    ]],
	onLoadSuccess:function(){
		authbutton();
	}
};

$(document).ready(function () {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
    populateLocation();
    $('#periode').datebox({
        // formatter:function (date) {
        //     var y = date.getFullYear();
        //     var m = date.getMonth()+1;
        //     var d = date.getDate();
        //     return y+(m<10?('0'+m):m);
        // },
        onSelect: function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var prd =  y+(m<10?('0'+m):m);
            var loc = $('#location_code').combogrid('getValue');
            if(loc!==""){
                $('#dg').datagrid({url:base_url+"stock/load_grid/"+loc+"/"+prd});
                $('#dg').datagrid('destroyFilter');
                $('#dg').datagrid('enableFilter');
            }
        }
    });
});

function openUpload() {
    var loc = $('#location_code').combogrid('getValue');
    var prd = $('#periode').datebox('getValue');
    if(loc==="" || prd===""){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select location and period.'
        });
        return;
    }
    $('#dg').datagrid({toolbar:'#toolbar2'});
    $('#toolbar1').hide();
}
function cancelUpload() {
    $('#dg').datagrid({toolbar:'#toolbar1'});
    $('#toolbar2').hide();
}
function submitUpload() {
    console.log("masuk sini");
    var loc = $('#location_code').combogrid('getValue');
    var prd = $('#periode').datebox('getValue');
    if(loc==="" || prd===""){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select location and period.'
        });
        return;
    }
    var iform = $('#formupload')[0];
    var data = new FormData(iform);
    data.append("location_code", loc);
    data.append("periode", prd);

    $.ajax({
        url: base_url+"stock/upload_data",
        type: 'post',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        data: data,
        success: function(result){
            var res = $.parseJSON(result);
            if (res.status===1){
                alert(res.msg)
            }
            $('#dg').datagrid('reload');
            cancelUpload();
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
        ]],
        onSelect:function (index, row) {
            var prd = $('#periode').datebox('getValue');
            if(prd!=="") {
                $('#dg').datagrid({url: base_url + "stock/load_grid/" + row.location_code+"/"+prd});
                $('#dg').datagrid('destroyFilter');
                $('#dg').datagrid('enableFilter');
            }
        }
    });
    $('#location_code').combogrid('grid').datagrid('enableFilter');
}
function getRow(bool) {
    var row = $('#dg').datagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data.'
            });
        }
        return null;
    }else{
        row.record = $('#dg').datagrid("getRowIndex", row);
    }
    return row;
}