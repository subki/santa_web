var tm = undefined;
var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"transferlocation/load_grid",
    saveUrl:base_url+"transferlocation/save_data",
    updateUrl:base_url+"transferlocation/edit_data",
    destroyUrl:base_url+"transferlocation/delete_data",
    onAfterEdit:function(index, row){
        console.log('onAfterEdit',row);
        if(row.isNewRecord) {
            $('#tt_disc').edatagrid('reload');
        }
    },
    idField:'docno',
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"doc_date",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:toolbarDO(),
    loadFilter: function(data){
        data.rows = [];
        if (data.data){
            data.rows = data.data;
        }
        return data;
    },
	onLoadSuccess:function(){
		authbutton();
	},
    onBeginEdit: function(index,row){
        var editor = $(this).edatagrid('getEditor', {index:index,field:'to_store_code'});
        var grid = $(editor.target).combogrid('grid');
        grid.datagrid('enableFilter');
        editor = $(this).edatagrid('getEditor', {index:index,field:'to_location_code'});
        grid = $(editor.target).combogrid('grid');
        console.log("row", row);
        if(!row.isNewRecord) {
            grid.datagrid({url: base_url + "delivery/get_location/" + row.to_store_code});
        }
        grid.datagrid('enableFilter');

        editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
        grid = $(editor.target).combogrid('grid');
        grid.datagrid('enableFilter');
        editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
        grid = $(editor.target).combogrid('grid');
        if(!row.isNewRecord) {
            grid.datagrid({url: base_url + "delivery/get_location/" + row.from_store_code});
        }
        grid.datagrid('enableFilter');

        editor = $(this).edatagrid('getEditor', {index:index,field:'status'});
        $(editor.target).combobox('loadData', statusDO(true));

        editor = $(this).edatagrid('getEditor', {index:index,field:'receive_date'});
        if(row.isNewRecord) {
            $(editor.target).datebox({required:!row.isNewRecord, readonly:row.isNewRecord, disabled:row.isNewRecord});
        }


    },
    onBeforeEdit: function(index, row){
        console.log("bct")
        var cant = false;
        if(row.from_store_code===store) cant = true;
        if(row.status==="DRAFT") cant = false;
        if(cant){
            if(row.isNewRecord) return
            $.messager.show({
                title: 'Warning',
                msg: "Data tidak bisa di edit"
            });
            setTimeout(function () {
                $("#tt_disc").edatagrid('cancelRow');
            },500)
        }
    },
    columns:[[
        {field:"docno",   title:"Nomor Dokumen",      width: '14%', sortable: true, editor:{
                type:'textbox',
                options:{
                    readonly:true
                }
            }},
        {field:"doc_date",   title:"Date",      width: '10%', sortable: true, formatter:function (value, row) {
            return row.ak_doc_date;
        },
            editor:{type:'datebox', options:{required:true, readonly:false}}},
        {field:"from_store_code",   title:"From Store",      width: '14%', sortable: true, formatter: function(value, row){
            return row.from_store_name;
        },editor:{
            type:'combogrid',
            options:{
                readonly:false,
                idField: 'store_code',
                textField:'store_name',
                url:base_url+"transferlocation/get_store",
                required:true,
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
                onSelect:function (index,row) {
                    console.log(row)
                    var selectedrow = $("#tt_disc").edatagrid("getSelected");
                    var rowIndex = $("#tt_disc").edatagrid("getRowIndex", selectedrow);

                    var ed = $('#tt_disc').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'from_location_code'
                    });
                    var dg = $(ed.target).combogrid('grid');
                    dg.datagrid({url:base_url+"delivery/get_location/"+row.store_code});
                },
                columns: [[
                    {field:'store_code',title:'Kode Store',width:150},
                    {field:'store_name',title:'Nama Store',width:350},
                ]],
                fitColumns: true,
                labelPosition: 'center'
            }
        }},
        {field:"from_location_code",   title:"From Location",      width: '18%', sortable: true, formatter: function(value, row){
            return row.from_location_name;
        },editor:{
            type:'combogrid',
            options:{
                readonly:false,
                idField: 'location_code',
                textField:'location_code',
                // url:base_url+"delivery/get_location/from",
                required:true,
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
                    {field:'location_code',title:'Kode Lokasi',width:100},
                    {field:'location_name',title:'Nama Lokasi',width:150},
                    {field:'store_name',title:'Nama Store',width:250},
                ]],
                fitColumns: true,
                labelPosition: 'center'
            }
        }},

        {field:"to_store_code",   title:"To Store",      width: '14%', sortable: true, formatter: function(value, row){
            return row.to_store_name;
        },editor:{
            type:'combogrid',
            options:{
                readonly:false,
                idField: 'store_code',
                textField:'store_name',
                url:base_url+"transferlocation/get_store",
                required:true,
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
                onSelect:function (index,row) {
                    console.log(row)
                    var selectedrow = $("#tt_disc").edatagrid("getSelected");
                    var rowIndex = $("#tt_disc").edatagrid("getRowIndex", selectedrow);

                    var ed = $('#tt_disc').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'to_location_code'
                    });
                    var dg = $(ed.target).combogrid('grid');
                    dg.datagrid({url:base_url+"delivery/get_location/"+row.store_code});
                },
                columns: [[
                    {field:'store_code',title:'Kode Store',width:150},
                    {field:'store_name',title:'Nama Store',width:350},
                ]],
                fitColumns: true,
                labelPosition: 'center'
            }
        }},
        {field:"to_location_code",   title:"Location",      width: '18%', sortable: true, formatter: function(value, row){
            return row.to_location_name;
        },editor:{
            type:'combogrid',
            options:{
                readonly:false,
                idField: 'location_code',
                textField:'location_code',
                // url:base_url+"delivery/get_location/from",
                required:true,
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
                    {field:'location_code',title:'Kode Lokasi',width:100},
                    {field:'location_name',title:'Nama Lokasi',width:150},
                    {field:'store_name',title:'Nama Store',width:250},
                ]],
                fitColumns: true,
                labelPosition: 'center'
            }
        }},
        {field:"receive_date",   title:"Receive Date",      width: '10%', sortable: true, formatter:function (value, row) {
            return row.ak_receive_date;
        },
            editor:{type:'datebox'}},
        {field:"status",   title:"Status",      width: '10%', sortable: true, editor:{
                type:'combobox',
                options:{
                    valueField:'status',
                    textField:'name',
                    prompt:'-Please Select-',
                    validType:'cekKeberadaan["#tt_disc","status"]',
                    data:statusDO(false),
                    required:true,
                    onChange:function (va) {
                        var selectedrow = $("#tt_disc").edatagrid("getSelected");
                        var rowIndex = $("#tt_disc").edatagrid("getRowIndex", selectedrow);

                        var ed = $('#tt_disc').edatagrid('getEditor',{
                            index:rowIndex,
                            field:'receive_date'
                        });
                        if(va==="APPROVED"){
                            $(ed.target).datebox({required:true, readonly:false, disabled:false});
                        }else{
                            $(ed.target).datebox({required:false, readonly:true, disabled:true});
                        }
                    }
                }
            }},
    ]],
    onSuccess: function(index, row){
        if(row.status===1) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: row.msg
            });
            $('#tt_disc').edatagrid('reload');
        }
    },
    onError:function(index, e){
        $.messager.show({
            title: 'Error',
            msg: e.message
        });
        $('#tt_disc').edatagrid('reload');
    },
};
function statusDO(e) {
    console.log("dosa")
    var xx = [];
    xx.push({status: 'DRAFT', name: 'DRAFT'});
    if(e) {
        let row = getRow(false);
        if (row !== null) {
            if (row.status === 'DRAFT') {
                xx.push({status: 'APPROVED', name: 'APPROVED'})
            }
        }
    }
    return xx;
}
$(document).ready(function () {
    $('#tt_disc').edatagrid(options);
    $('#tt_disc').edatagrid('enableFilter');
});

function toolbarDO() {
    let tool = [];
    tool.push({
        iconCls: 'icon-add', id:'add', text:'New',
        handler: function(){$('#tt_disc').edatagrid('addRow',0)}
    },{
        id:'delete', iconCls: 'icon-remove', text:'Delete',
        handler: function(){$('#tt_disc').edatagrid('destroyRow')}
    },{
        id:'submit', iconCls: 'icon-save', text:'Submit',
        handler: function(){$('#tt_disc').edatagrid('saveRow')}
    },{
        id:'cancel', iconCls: 'icon-undo', text:'Cancel',
        handler: function(){$('#tt_disc').edatagrid('cancelRow')}
    },{
        iconCls: 'icon-shelf', text:'DO Items',
        handler: function(){
            let row = getRow(true);
            if(row == null) return;
            openBarangDO()
        }
    })
    return tool;
}

function openBarangDO() {
    let row = getRow(true);
    if(row===null) return
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','DO Items ('+row.docno+')');
    $("#dd").edatagrid({
        url: base_url+"transferlocation/load_grid_nobar/"+row.docno,
        saveUrl: base_url+"transferlocation/save_data_nobar/"+row.docno,
        updateUrl: base_url+"transferlocation/edit_data_nobar",
        destroyUrl: base_url+"transferlocation/delete_data_nobar",
        fitColumns:true,
        idField:'id',
        height:'100%',
        singleSelect:true,
        onAfterEdit:function(index, row){
            if(row.isNewRecord) $('#dd').edatagrid('reload');
        },
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
            }
            return data;
        },
        onBeginEdit: function(index,row){
            var editor = $(this).edatagrid('getEditor', {index:index,field:'nobar'});
            var grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');
        },
        onBeforeEdit: function(index, rr){
            if(row.status === 'APPROVED'){
                $.messager.show({
                    title: 'Warning',
                    msg: "Data tidak bisa di edit"
                });
                setTimeout(function () {
                    $("#dd").edatagrid('cancelRow');
                },500)
            }
        },
        toolbar:[
            {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
            console.log(row)
            console.log(loc)
                    if(row.status==="DRAFT") {
                        if(row.from_location_code===loc) {
                            $("#dd").edatagrid('addRow',0);
                        }else{
                            $.messager.show({    // show error message
                                title: 'Error',
                                msg: `Hanya ${row.from_location_name} yang dapat Add`
                            });
                        }
                    }else{
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: `Status DO sudah ${row.status}, tidak boleh Add`
                        });
                    }
                }},
            {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
                if(row.status==="DRAFT"){
                    if(row.from_location_code===loc) {
                        $("#dd").edatagrid('destroyRow');
                    }else{
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: `Hanya ${row.from_location_name} yang dapat menghapus`
                        });
                    }
                }else{
                    $.messager.show({    // show error message
                        title: 'Error',
                        msg: 'Data tidak bisa dihapus'
                    });
                }
                }},
            {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
                    $("#dd").edatagrid('saveRow');
                }},
            {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function(){
                    $("#dd").edatagrid('cancelRow');
                },
            }],
        columns:[
            fields(row),
        ],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    })
}

function fields(row) {
    var ff = [];
    ff.push({field:"id",   title:"ID",      width: '5%', sortable: true, editor:{type:'textbox', options:{disabled:false, readonly:true}}});
    ff.push({field:"docno",   title:"Nomor DO",      width: '8%', sortable: true, editor:{type:'textbox', options:{disabled:false, readonly:true}}})
    let nobar = false;
    let qty_send = false;
    let qty_recv = false;
    let qty_rev = false;

    let r_send = false;
    let r_recv = false;
    let r_revs = false;
    if(row.status==="DRAFT"){
        nobar = false;
        qty_send = false;
        qty_recv = true;
        qty_rev = true;

        if(row.from_store_code===store){
            r_send = false;
            r_recv = r_revs = false;
        }
    }else{
        nobar = true;
        qty_send = true;
        qty_recv = true;
        qty_rev = true;
    }
    ff.push({field:"nobar",   title:"Kode Barang",      width: '10%', sortable: true,editor:{
        type:'combogrid',
        options:{
            readonly:nobar,
            idField: 'nobar',
            textField:'nobar',
            url:base_url+"delivery/get_product/"+row.docno,
            required:true,
            remoteFilter:true,
            hasDownArrow: false,
            panelWidth: 500,
            multiple:false,
            panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
                mousedown: function(){}
            }),
            editable: false,
            pagination: true,
            loadFilter: function (data) {
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            columns: [[
                {field:'nobar',title:'No Item',width:150},
                {field:'nmbar',title:'Product Name',width:250},
                {field:'satuan_jual_desc',title:'UOM',width:100},
            ]],
            fitColumns: true,
            labelPosition: 'center',
            onSelect: function(index, rr) {
                var selectedrow = $("#dd").edatagrid("getSelected");
                var rowIndex = $("#dd").edatagrid("getRowIndex", selectedrow);

                var ed = $('#dd').edatagrid('getEditor',{
                    index:rowIndex,
                    field:'nmbar'
                });
                $(ed.target).textbox('setValue', rr.nmbar)

            }
        }
    }});
    ff.push({field:"nmbar",   title:"Nama Barang",      width: '20%', sortable: true, editor:{type:'textbox', options:{disabled:false, readonly:true}}});
    ff.push({field:"qty",   title:"Qty Transfer",      width: '10%', sortable: true, formatter:numberFormat, editor:{
        type:'numberbox',
        options:{disabled:false, readonly:qty_send, required:r_send}}});
    ff.push({field:"qty_rcv",   title:"Qty Receive",      width: '10%', sortable: true, formatter:numberFormat, editor:{
        type:'numberbox',
        options:{disabled:false, readonly:qty_recv, required:r_recv}}});
    // ff.push({field:"qty_rev",   title:"Qty Revisi",      width: '10%', sortable: true, formatter:numberFormat, editor:{
    //     type:'numberbox',
    //     options:{disabled:false,readonly:qty_rev, required:r_revs}}});
    ff.push({field:"status",   title:"Status",      width: '8%', sortable: true, editor:{
        type:'textbox',
        options:{disabled:false, readonly:true}}});
    ff.push({field:"keterangan",   title:"Keterangan",      width: '19%', sortable: true, editor:{
        type:'textbox',
        options:{disabled:false, readonly:false}}});
    return ff;
}

function getRow(bool) {
    var row = $('#tt_disc').edatagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data.'
            });
        }
        return null;
    }else{
        row.record = $('#tt_disc').edatagrid("getRowIndex", row);
    }
    return row;
}