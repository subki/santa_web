var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"discount/load_grid",
    saveUrl:base_url+"discount/save_data",
    updateUrl:base_url+"discount/edit_data",
    destroyUrl:base_url+"discount/delete_data",
    idField:'discount_id',
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"start_date",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            $('#tt_disc').edatagrid('addRow',0)
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            $('#tt_disc').edatagrid('destroyRow')
        }
    },{
        id:'submit',
        iconCls: 'icon-save',
        text:'Submit',
        handler: function(){
            $('#tt_disc').edatagrid('saveRow')
        }
    },{
        id:'cancel',
        iconCls: 'icon-undo',
        text:'Cancel',
        handler: function(){
            $('#tt_disc').edatagrid('cancelRow')
        }
    },{iconCls: 'icon-copy', text:'Copy', handler: function(){
            // let row = getRow(true);
            // if(row === null) return;
            openCopy()
        },
    },{
        iconCls: 'icon-shelf',
        text:'Discount Items',
        handler: function(){
            let row = getRow(true);
            if(row === null) return;
            openBarangDiskon()
        }
    },{
        iconCls: 'icon-grocery1',
        text:'Set Kelompok Promo',
        handler: function(){
            let row = getRow(true);
            if(row === null) return;
            openLocationDiskon()
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data){
            data.rows = data.data;
        }
        return data;
    },
    onBeginEdit:function (index, row) {
        var editor = $(this).edatagrid('getEditor', {index:index,field:'customer_code'});
        var grid = $(editor.target).combogrid('grid');
        grid.datagrid('enableFilter');
        if(row.isNewRecord) {
            editor = $(this).edatagrid('getEditor', {index: index, field: 'status'});
            $(editor.target).combobox('setValue','OPEN');
        }
    },
	onLoadSuccess:function(){
		authbutton();
	},
    columns:[[
        {field:'e', expander:true},
        {field:"discount_id",   title:"No. Trx",      width: '10%', sortable: true, editor:{
                type:'textbox',
                options:{
                    disabled:false,
                    readonly:true
                }
            }},
        {field:"customer_code",   title:"Customer",      width: '20%', sortable: true, formatter:function (value, rr) {
            return rr.customer_name;
        }, editor:{
            type:'combogrid',
            options:{
                idField: 'customer_code',
                textField:'customer_name',
                url:base_url+"customer/load_grid",
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
                    {field:'customer_code',title:'Customer Code',width:150},
                    {field:'customer_name',title:'Customer Name',width:250},
                    {field:'customer_type',title:'Price Type',width:100},
                ]],
                fitColumns: true,
                labelPosition: 'center',
                onSelect:function (index,row) {
                    console.log(row)
                    var selectedrow = $("#tt_disc").edatagrid("getSelected");
                    var rowIndex = $("#tt_disc").edatagrid("getRowIndex", selectedrow);

                    var ed = $('#tt_disc').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'customer_type'
                    });
                    $(ed.target).textbox('setValue', row.customer_type);
                    $(ed.target).textbox('setText', row.customer_type_name);

                    ed = $('#tt_disc').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'lokasi_stock'
                    });
                    $(ed.target).textbox('setValue', row.lokasi_stock);
                    $(ed.target).textbox('setText', row.lokasi_stock_name);
                }
            }
        }},
        {field:"customer_type",   title:"Price Type",      width: '20%', sortable: true, formatter: function(value, row){
            return row.description;
        },editor:{type:'textbox', options:{required:true, readonly:true}}},
        {field:"lokasi_stock",   title:"Lokasi",      width: '8%', sortable: true,
            editor:{type:'textbox', options:{required:true}}},
        {field:"start_date",   title:"Periode Awal",      width: '12%', sortable: true, formatter:function (value, rr) {
            return rr.ak_start_date;
        },
            editor:{type:'datebox', options:{required:true}}},
        {field:"end_date",   title:"Periode Akhir",      width: '12%', sortable: true, formatter:function (value, rr) {
            return rr.ak_end_date;
        },
            editor:{type:'datebox', options:{required:true}}},
        {field:"discount1",   title:"Diskon 1",      width: '8%', sortable: true, formatter:numberFormat,
            editor:{type:'numberbox', options:{min:0, precision:2, formatter:formatnumberbox}}},
        {field:"margin_persen",   title:"Margin %",      width: '8%', sortable: true, formatter:numberFormat,
            editor:{type:'numberbox', options:{min:0, precision:2, formatter:formatnumberbox}}},
        {field:"print_barcode",   title:"Print Barcode",    align:"center",  width: '8%', editor:{
            type:'checkbox',
            options:{on:'YES',off:'NO'}
        }},
        {field:"keterangan",   title:"Keterangan",      width: '20%', sortable: true, editor:{
            type:'textbox',
            options:{
                required:true
            }
        }},
        {field:"status",   title:"Status",      width: '10%', sortable: true, editor:{
            type:'combobox',
            options:{
                valueField:'id',
                textField:'text',
                prompt:'-Please Select-',
                validType:'cekKeberadaan["#tt_disc","status"]',
                data:[
                    {id:'OPEN',text:'OPEN'},
                    {id:'POSTING',text:'POSTING'}
                    ],
                required:true
            }
        }}
    ]],
    onSuccess: function(index, row){
        if(row.status===1) {
            $.messager.show({title: 'Error', msg: row.msg});
        }
        $('#tt_disc').edatagrid('reload');
    },
    onError:function(index, e){
        $.messager.show({title: 'Error', msg: e.message});
        $('#tt_disc').edatagrid('reload');
    }
};
$(document).ready(function () {
    $('#tt_disc').edatagrid(options);
    $('#tt_disc').edatagrid('destroyFilter');
    $('#tt_disc').edatagrid('enableFilter');

    $('#toolbar2').hide();
});

function openBarangDiskon() {
    let row = getRow(true);
    if(row===null) return
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','Diskon Items ('+row.discount_id+')');
    $("#dd").edatagrid({
        url: base_url+"discount/load_grid_nobar/"+row.discount_id,
        saveUrl: base_url+"discount/save_data_nobar/"+row.discount_id,
        updateUrl: base_url+"discount/edit_data_nobar",
        destroyUrl: base_url+"discount/delete_data_nobar",
        idField:'id',
        width:"100%",
        height:"100%",
        singleSelect:true,
        fitColumns:true,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pageSize:20,
        pagination:true, striped:true, nowrap:false,
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
            }
            return data;
        },
        onBeforeEdit: function(index, row){
            var rr = getRow(true);
            if(row.isNewRecord) {
                if(rr.status==="OPEN") return;
                $.messager.show({
                    title: 'Warning',
                    msg: "Set Discount sudah di posting, tidak bisa tambah/ubah detail"
                });
                setTimeout(function () {
                    $("#dd").edatagrid('cancelRow');
                },500)
            }else {
                if(rr.status==="OPEN") return;
                $.messager.show({
                    title: 'Warning',
                    msg: "Tidak boleh di edit, hanya bisa di hapus"
                });
                setTimeout(function () {
                    $("#dd").edatagrid('cancelRow');
                }, 500)
            }
        },
        onBeginEdit: function(index,rr){
            var editor = $(this).edatagrid('getEditor', {index:index,field:'article_code'});
            var grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');
            if(rr.isNewRecord){
                editor = $(this).edatagrid('getEditor', {index:index,field:'print_barcode'});
                $(editor.target).checkbox({label:row.print_barcode, value:row.print_barcode, checked:row.print_barcode==="YES"})
                editor = $(this).edatagrid('getEditor', {index:index,field:'customer_code'});
                $(editor.target).textbox('setValue',row.customer_code)
                $(editor.target).textbox('setText',row.customer_code+"  -  "+row.customer_name)
                editor = $(this).edatagrid('getEditor', {index:index,field:'customer_type'});
                $(editor.target).textbox('setValue',row.customer_type)
                $(editor.target).textbox('setText',row.customer_type)
                editor = $(this).edatagrid('getEditor', {index:index,field:'discount'});
                $(editor.target).numberbox('setValue',row.discount1)
                $(editor.target).numberbox('setText',row.discount1)
            }
        },
        toolbar:[
            {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
                    $("#dd").edatagrid('addRow',0);
                }},
            {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
                    $("#dd").edatagrid('destroyRow');
                }},
            {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
                    $("#dd").edatagrid('saveRow');
                }},
            {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function(){
                $("#dd").edatagrid('cancelRow');
            }},
            // {id:'copy', iconCls: 'icon-copy', text:'Copy', handler: function(){
            //    openCopy(row)
            // },
            // }
            ],
		onLoadSuccess:function(){
			authbutton();
		},
        columns:[[
            {field:"id",   title:"ID",      width: '10%', sortable: true, editor:{
                    type:'textbox',
                    options:{disabled:false, readonly:true}}},
            {field:"discount_id",   title:"Kode Diskon",      width: '15%', sortable: true, editor:{
                    type:'textbox',
                    options:{disabled:false, readonly:true}}},
            {field:"article_code",   title:"Article code",      width: '10%', sortable: true,editor:{
                    type:'combogrid',
                    options:{
                        idField: 'article_code',
                        textField:'article_name',
                        url:base_url+"discount/get_product/"+row.discount_id+"/"+row.customer_type+"?customer="+row.customer_code,
                        required:true,
                        hasDownArrow: false,
                        remoteFilter:true,
                        panelWidth: 500,
                        multiple:true,

                        mode:'remote',
                        pagePosition:"top",
                        resizeHandle:"right",
                        resizeEdge:10,
                        pageSize:20,
                        clientPaging: false,
                        rownumbers: false,
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
                            {field:'',checkbox:true,width:150},
                            {field:'article_code',title:'Article Code',width:150},
                            {field:'article_name',title:'Article Name',width:250},
                        ]],
                        fitColumns: true,
                        labelPosition: 'center'
                    }
                }},
            {field:"article_name",   title:"Article Name",      width: '15%', sortable: true, editor:{
                type:'textbox',
                options:{disabled:false, readonly:true}}},
            {field:"customer_code",   title:"Customer",      width: '25%', sortable: true, formatter:function(index, rr){
                return rr.customer_code+"  -  "+rr.customer_name;
            }, editor:{type:'textbox', options:{disabled:false, readonly:true}}},
            {field:"customer_type",   title:"Price Type",      width: '5%', sortable: true,
                editor:{type:'textbox', options:{disabled:false, readonly:true}}},
            {field:"print_barcode",   title:"Print Barcode",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'YES',off:'NO'}
            }},
            {field:"discount",   title:"Disc Article",      width: '10%', sortable: true, editor:{type:'numberbox', options:{min:0, precision:2, formatter:formatnumberbox}}},
            {field:"margin_persen",   title:"Margin %",      width: '10%', sortable: true, editor:{type:'numberbox', options:{min:0, precision:2, formatter:formatnumberbox}}},
        ]],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#dd').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
            $('#dd').edatagrid('reload');
        }
    })
}
function cancelUpload() {
    $('#toolbar2').hide();
    rowx = undefined;
}
function openCopy() {
    $('#tt_disc').edatagrid({toolbar:'#toolbar2'});
    $('#tt_disc').edatagrid('destroyFilter');
    $('#tt_disc').edatagrid('enableFilter');
    $("#combo").combogrid({
        idField: 'discount_id',
        textField:'discount_id',
        url:base_url+"discount/load_grid",
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
            if (data.data)data.rows = data.data;
            return data;
        },
        // onSelect:function (index, rw) {
        //     selectArticle(rw, row);
        // },
        columns: [[
            {field:'discount_id',title:'Trx. No',width:150},
            {field:'customer_name',title:'Customer Name',width:250},
            {field:'customer_type',title:'Price Type',width:100},
            {field:'lokasi_stock',title:'Lokasi',width:100},
        ]],
        fitColumns: true,
        labelPosition: 'center'
    });
    var grid = $("#combo").combogrid('grid');
    grid.datagrid('enableFilter');
}
var rowx = undefined
function selectArticle(row, row_h) {
    rowx = row_h;
    $("#combo2").combogrid({
        idField: 'id',
        textField:'id',
        url:base_url+"discount/load_grid_nobar/"+row.discount_id,
        required:true,
        hasDownArrow: false,
        remoteFilter:true,
        panelWidth: 500,
        multiple:true,
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
        onSelect:function (index, rw) {
            console.log("select",rw);
        },
        columns: [[
            {field:'',checkbox:true,width:150},
            {field:'id',title:'id',width:100},
            {field:'discount_id',title:'Trx. No',width:100},
            {field:'article_code',title:'Article Code',width:100},
            {field:'article_name',title:'Article Name',width:200},
            {field:'customer_name',title:'Customer Name',width:200, formatter:function(index, rr){
                return rr.customer_code+"  -  "+rr.customer_name;
            }},
            {field:'customer_type',title:'Price Type',width:120},
            {field:'print_barcode',title:'Print Barcode',width:100},
            {field:'discount',title:'Disc Article',width:110},
        ]],
        fitColumns: true,
        labelPosition: 'center'
    });
    var grid = $("#combo2").combogrid('grid');
    grid.datagrid('enableFilter');
}
function submitCopy() {
    // var iform = $('#fromcopy')[0];
    // var data = new FormData(iform);
    //
    var xx = $('#combo').combogrid('getValue');
    // console.log(xx)
    $.ajax({
        url: base_url+"discount/copy_article",
        type: 'post',
        data: {
            docno:xx
        },
        success: function(result){
            console.log(result);
            var res = $.parseJSON(result);
            if (res.status===1){
                alert(res.msg)
            }else{
                $.messager.alert("Success", `Berhasil copy transaksi <br/> ${res.msg}.`)
            }
            $('#tt_disc').edatagrid('reload');
            cancelUpload();
        }
    });
    cancelUpload()
}
function openLocationDiskon() {
    let row = getRow(true);
    if(row===null) return
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','Set Kelompok Promo ('+row.discount_id+')');
    $("#dd").edatagrid({
        url: base_url+"discount/load_grid_location/"+row.discount_id,
        saveUrl: base_url+"discount/save_data_location/"+row.discount_id,
        updateUrl: base_url+"discount/edit_data_location",
        destroyUrl: base_url+"discount/delete_data_location",
        idField:'id',
        width:"100%",
        height:"100%",
        singleSelect:true,
        fitColumns:true,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pageSize:20,
        pagination:true, striped:true, nowrap:false,
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
            }
            return data;
        },
        onBeforeEdit: function(index, row){
            var rr = getRow(true);
            if(row.isNewRecord) {
                if(rr.status==="OPEN") return;
                $.messager.show({
                    title: 'Warning',
                    msg: "Set Discount sudah di posting, tidak bisa tambah/ubah detail"
                });
                setTimeout(function () {
                    $("#dd").edatagrid('cancelRow');
                },500)
            }else {
                if(rr.status==="OPEN") return;
                $.messager.show({
                    title: 'Warning',
                    msg: "Tidak boleh di edit, hanya bisa di hapus"
                });
                setTimeout(function () {
                    $("#dd").edatagrid('cancelRow');
                }, 500)
            }
        },
        onBeginEdit: function(index,row){
            var editor = $(this).edatagrid('getEditor', {index:index,field:'location_code'});
            var grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');
        },
        toolbar:[
            {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
                    $("#dd").edatagrid('addRow',0);
                }},
            {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
                    $("#dd").edatagrid('destroyRow');
                }},
            {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
                    $("#dd").edatagrid('saveRow');
                }},
            {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function(){
                    $("#dd").edatagrid('cancelRow');
                },
            }],
		onLoadSuccess:function(){
			authbutton();
		},
        columns:[[
            {field:"id",   title:"ID",      width: '10%', sortable: true, editor:{
                    type:'textbox',
                    options:{disabled:false, readonly:true}}},
            {field:"discount_id",   title:"Kode Diskon",      width: '15%', sortable: true, editor:{
                    type:'textbox',
                    options:{disabled:false, readonly:true}}},
            {field:"customer_code",   title:"Customer",      width: '30%', sortable: true, formatter:function(index, rr){
                return rr.customer_code+"   "+rr.customer_name;
            }, editor:{
                    type:'textbox',
                    options:{disabled:false, readonly:true}}},
            {field:"location_code",   title:"Lokasi",      width: '30%', sortable: true, formatter:function(index, rr){
                return rr.location_code+"    "+rr.location_name;
            },editor:{
                    type:'combogrid',
                    options:{
                        idField: 'id',
                        textField:'location_name',
                        url:base_url+"discount/get_location/"+row.discount_id+"/"+row.customer_type+"?customer="+row.customer_code,
                        required:true,
                        hasDownArrow: false,
                        panelWidth: 500,
                        remoteFilter:true,
                        multiple:true,
                        filterable:true,


                        mode:'remote',
                        pagePosition:"top",
                        resizeHandle:"right",
                        resizeEdge:10,
                        pageSize:20,
                        clientPaging: false,
                        rownumbers: false,
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
                            // console.log(data.rows)
                            return data;
                        },
                        columns: [[
                            {field:'',checkbox:true,width:150},
                            {field:'customer_name',title:'Customer',width:450, formatter:function(index,rr){
                                return rr.customer_code+"   ||   "+rr.customer_name;
                            }},
                            {field:'location_code',title:'Kode Lokasi',width:100},
                        ]],
                        fitColumns: true,
                        labelPosition: 'center'
                    }
                }}
        ]],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#dd').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
            $('#dd').edatagrid('reload');
        }
    })
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