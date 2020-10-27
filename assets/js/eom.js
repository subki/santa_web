
$(document).ready(function () {
    populateLocation();
    populateSKU();
    var date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    // var prd =  (m<10?('0'+m):m)+"/"+y;
    var prd2 =  y+(m<10?('0'+m):m);
    // $('#prd').text('Periode : '+prd);
    // $('#periode').textbox('setValue',prd2)
    // $('#periode').textbox('hide')
    $('#periode').datebox({
        value:prd2,
        required:true,
        formatter:function (date) {
            console.log("uki", date)
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'/'+(m<10?('0'+m):m);
        },
        parser:function (s) {
            console.log("ukis", s)
            if (!s) return new Date();
            var ss = s.split('/');
            var d = 1;
            var m = parseInt(ss[1],10);
            var y = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
    })
});

function populateLocation() {
    $('#from_location_code').combogrid({
        idField: 'location_code',
        textField:'location_name',
        url:base_url+"eom/get_location",
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
        sortName:"location_code",
        sortOrder:"asc",
        mode:'remote',
        prompt:'-Please Select-',
        loadFilter: function (data) {
            data.rows = [];
            if (data.data) {
                data.data.unshift({location_code:'~',location_name:'All Location'});
                data.rows = data.data;
            }
            return data;
        },
        columns: [[
            {field:'location_code',title:'Location Code',width:150},
            {field:'location_name',title:'Location Name',width:250},
        ]],
        onSelect:function (index, row) {
            var loc = $('#to_location_code').combogrid('getValue');
            var prd = $('#periode').datebox('getValue').replace("/","");
            if(loc!==""){
                var dg = $('#from_nobar').combogrid('grid');
                dg.datagrid({url:base_url+"eom/get_nobar/"+loc+"/"+row.location_code+"/"+prd});
                dg.datagrid('destroyFilter');
                dg.datagrid('enableFilter');

                dg = $('#to_nobar').combogrid('grid');
                dg.datagrid({url:base_url+"eom/get_nobar/"+loc+"/"+row.location_code+"/"+prd});
                dg.datagrid('destroyFilter');
                dg.datagrid('enableFilter');
            }
        }
    });
    $('#from_location_code').combogrid('grid').datagrid('enableFilter');

    $('#to_location_code').combogrid({
        idField: 'location_code',
        textField:'location_name',
        url:base_url+"eom/get_location",
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
        sortName:"location_code",
        sortOrder:"asc",
        mode:'remote',
        prompt:'-Please Select-',
        loadFilter: function (data) {
            data.rows = [];
            if (data.data){
                data.data.unshift({location_code:'~',location_name:'All Location'});
                data.rows = data.data;
            }
            return data;
        },
        columns: [[
            {field:'location_code',title:'Location Code',width:150},
            {field:'location_name',title:'Location Name',width:250},
        ]],
        onSelect:function (index, row) {
            var loc = $('#from_location_code').combogrid('getValue');
            var prd = $('#periode').datebox('getValue').replace("/","");
            if(loc!==""){
                var dg = $('#from_nobar').combogrid('grid');
                dg.datagrid({url:base_url+"eom/get_nobar/"+loc+"/"+row.location_code+"/"+prd});
                dg.datagrid('destroyFilter');
                dg.datagrid('enableFilter');

                dg = $('#to_nobar').combogrid('grid');
                dg.datagrid({url:base_url+"eom/get_nobar/"+loc+"/"+row.location_code+"/"+prd});
                dg.datagrid('destroyFilter');
                dg.datagrid('enableFilter');
            }
        }
    });
    $('#to_location_code').combogrid('grid').datagrid('enableFilter');
}

function populateSKU() {
    $('#from_nobar').combogrid({
        idField: 'nobar',
        textField:'nmbar',
        // url:base_url+"eom/get_nobar",
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
            if (data.data) {
                data.data.unshift({nobar:'~',nmbar:'All SKU'});
                data.rows = data.data;
            }
            return data;
        },
        columns: [[
            {field:'nobar',title:'Nomor Barang',width:150},
            {field:'nmbar',title:'Nama Barang',width:250},
        ]],
        onSelect:function (index, row) {
        }
    });
    $('#from_nobar').combogrid('grid').datagrid('enableFilter');
    $('#from_nobar').combogrid('setValue','~');

    $('#to_nobar').combogrid({
        idField: 'nobar',
        textField:'nmbar',
        // url:base_url+"eom/get_nobar",
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
            if (data.data) {
                data.data.unshift({nobar:'~',nmbar:'All SKU'});
                data.rows = data.data;
            }
            return data;
        },
        columns: [[
            {field:'nobar',title:'Nomor Barang',width:150},
            {field:'nmbar',title:'Nama Barang',width:250},
        ]],
        onSelect:function (index, row) {
        }
    });
    $('#to_nobar').combogrid('grid').datagrid('enableFilter');
    $('#to_nobar').combogrid('setValue','~');

}

function clearInput() {
    $('#fm').form('clear');
}
function submit(){
    $('#fm').form('submit',{
        url: base_url+'eom/execute_eom',
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                if (res.status === 0) {
                    clearInput();
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: res.msg
                    });
                }
            }catch (e) {
                console.log(e)
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            }
        }
    });
}
