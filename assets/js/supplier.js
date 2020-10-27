var options={
    url: base_url+"mastersupplier/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"supplier_code",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-eye',
        text:'View',
        handler: function(){
            viewData()
        }
    },{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            addnew()
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            editData()
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            deleteData()
        }
    },{
        id:'detail',
        iconCls: 'icon-product',
        text:'Detail Product',
        handler: function(){
            openProduct()
        }
    },{
        id:'info',
        iconCls: 'icon-info',
        text:'Contact Supplier',
        handler: function(){
            openContact()
        }
    },{
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("dg", function (x, x1, x2) {
                let urlss = base_url+"mastersupplier/export_data?field="+x+"&op="+x1+"&value="+x2;
                window.open(urlss, '_blank')
            })
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"supplier_code",   title:"Kode Supplier",      width: '10%', sortable: true},
        {field:"tipe_supplier",   title:"Tipe",      width: '10%', sortable: true},
        {field:"supplier_name",   title:"Supplier",      width: '35%', sortable: true},
        {field:"address",   title:"Alamat",      width: '40%', sortable: true},
        {field:"status",   title:"Status",      width: '7%', sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        $('#detail').linkbutton({disabled:true});
        $('#info').linkbutton({disabled:true});
        $('#info2').linkbutton({disabled:true});
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        // disable_enable(true);
        populateProvinsi();
		authbutton();
    },
    onSelect: function(index, row) {
        $('#detail').linkbutton({disabled:false});
        $('#info').linkbutton({disabled:false});
        $('#info2').linkbutton({disabled:false});
        console.log(row);
        $('#fm').form('load',row);
    }
};

setTimeout(function () {
    initGrid();
    $('#pkp').combobox({
        onSelect:function(row){
            console.log(row)
            if(row.value !== '') {
                $('#npwp').textbox({required:(row.value === 'Yes'),readonly:(row.value === 'No')});
                $('#nama_pkp').textbox({required:(row.value === 'Yes'),readonly:(row.value === 'No')});
                $('#alamat_pkp').textbox({required:(row.value === 'Yes'),readonly:(row.value === 'No')});
            }
        }
    })
    populateJenisBarang();
},500);

function populateJenisBarang() {
    $('#tipe_supplier').combobox({
        data:[
            {value:'Barang Jadi',text:'Barang Jadi'},
            {value:'Bahan Baku',text:'Bahan Baku'},
            {value:'Accessories',text:'Accessories'},
            {value:'Packing',text:'Packing'},
            {value:'Spare Part',text:'Spare Part'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#tipe_supplier"]',
    });
}

function populateRegency(id) {
    if(id==="") return;
    let row = getRow(false);
    $('#regency_id').combobox({
        url: base_url+"mastersupplier/get_regency/"+id,
        valueField: 'id',
        textField: 'name',
        prompt:'-Please Select-',
        validType:'inList["#regency_id"]',
        loadFilter: function (data) {
            return data.data;
        }
    });
    if(row!=null) $('#regency_id').combobox('select',row.id)
}
function populateProvinsi() {
    $('#provinsi_id').combobox({
        url: base_url+"mastersupplier/get_provinsi",
        valueField: 'id',
        textField: 'name',
        prompt:'-Please Select-',
        validType:'inList["#provinsi_id"]',
        loadFilter: function (data) {
            return data.data;
        },
        onSelect:function (row) {
            populateRegency(row.id);
        }
    });
}

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}

function clearInput() {
    // $('#fm').form('clear');
    // $('#submit').linkbutton({disabled:true});
    // $('#cancel').linkbutton({disabled:true});
    // disable_enable(true);
    $('#dlg2').dialog('close');
}

function addnew(){
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`New Supplier`);
    // disable_enable(false)
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "mastersupplier/save_data";
    $('#supplier_code').textbox({disabled:true, readonly:true, width:'100%'});
}
function editData(){
    let row = getRow(true);
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"mastersupplier/read_data/"+row.supplier_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#fm').form('load',data.data);
            $('#submit').linkbutton({disabled:true});
            $('#cancel').linkbutton({disabled:false});
            flag = "mastersupplier/edit_data";
            disable_enable(false)
            $('#supplier_code').textbox({disabled:false, readonly:true, width:'100%'});
            $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Product from : ${row.description}`).panel('resize');
        }
    });
}
function viewData(){
    let row = getRow(true);
    if(row===null) return
    $.ajax({
        type:"POST",
        url:base_url+"mastersupplier/read_data/"+row.supplier_code,
        dataType:"html",
        success:function(result){
            disable_enable(true)
            var data = $.parseJSON(result);
            $('#submit').linkbutton({disabled:true});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`View Supplier`).panel('resize');
            // $('#pp').panel('open').panel('refresh');
        }
    });
}

function deleteData(){
    let row = getRow(true);
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"mastersupplier/delete_data/"+row.supplier_code,function(result){
                    var res = $.parseJSON(result);
                    if (res.status===1){
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: res.msg
                        });
                    } else {
                        $('#dg').datagrid('reload');    // reload the user data
                    }
                }
            );
        }
    });
}

function openProduct(){
    let row = getRow();
    if(row==null) return;
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Product : ${row.supplier_name}`);
    $('#tt').edatagrid({
        height:'100%',
        width:'100%',
        idField:"id",
        rownumbers:false,
        fitColumns:true,
        singleSelect:true,
        toolbar:'#toolbar22',
        url: base_url+"mastersupplier/get_products/"+row.supplier_code,
        saveUrl: base_url+"mastersupplier/save_data_product/"+row.supplier_code,
        updateUrl: base_url+"mastersupplier/edit_data_product",
        destroyUrl: base_url+"mastersupplier/delete_data_product",
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        onBeginEdit:function (index, row) {
            var editor = $(this).edatagrid('getEditor', {index: index, field: 'sku'});
            var grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');
        },
        onBeforeEdit: function (index, row) {
            if (row.isNewRecord) return
            // if(so_item.status!=="OPEN") {
            //     $.messager.show({
            //         title: 'Warning',
            //         msg: "Data tidak bisa di edit"
            //     });
            //     setTimeout(function () {
            //         $("#dg").edatagrid('cancelRow');
            //     }, 500)
            // }
            setTimeout(function () {
                $("#tt").edatagrid('cancelRow');
            }, 500)
        },
        columns:[[
            {field:"sku",   title:"Item#",      width: '10%', sortable: true,editor: {
                type: 'combogrid',
                options: {
                    readonly: false,
                    idField: 'sku',
                    textField: 'sku',
                    url: `${base_url}mastersupplier/get_sku/${row.supplier_code}`,
                    required: true,
                    hasDownArrow: false,
                    remoteFilter: true,
                    panelWidth: 500,
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
                        var selectedrow = $("#tt").edatagrid("getSelected");
                        var rowIndex = $("#tt").edatagrid("getRowIndex", selectedrow);

                        var ed = $('#tt').edatagrid('getEditor', {
                            index: rowIndex,
                            field: 'product_code'
                        });
                        $(ed.target).textbox('setValue', row.product_code);

                        ed = $('#tt').edatagrid('getEditor', {
                            index: rowIndex,
                            field: 'product_name'
                        });
                        $(ed.target).textbox('setValue', row.product_name);

                        ed = $('#tt').edatagrid('getEditor', {
                            index: rowIndex,
                            field: 'uom_code'
                        });
                        $(ed.target).textbox('setValue', row.satuan_jual);
                        $(ed.target).textbox('setText', row.satuan_jual_id);

                    },
                    columns: [[
                        {field: 'sku', title: 'SKU', width: 100},
                        {field: 'product_code', title: 'Product Code', width: 100},
                        {field: 'product_name', title: 'Product Name', width: 200},
                        {field: 'satuan_jual_id', title: 'Satuan Beli', width: 100},
                    ]],
                    fitColumns: true,
                    labelPosition: 'center'
                }
            }},
            {field:"product_code",   title:"Kode Produk",      width: '10%', sortable: true, editor:{
                type:'textbox',
                options:{
                    readonly:true,
                    required:true
                }
            }},
            {field:"product_name",   title:"Nama Produk",      width: '15%', sortable: true, editor:{
                type:'textbox',
                options:{
                    readonly:true,
                    disabled:true
                }
            }},
            {field:"uom_code",   title:"Satuan",      width: '8%', sortable: true, formatter:function (index, row) {
                return row.uom_id;
            }, editor:{
                type:'textbox',
                options:{
                    readonly:true,
                    required:true
                }
            }},
            {field:"unit_price",   title:"Harga Beli", width: '11%', formatter:numberFormat, sortable: true, editor:{
                type:'numberbox',
                options:{
                    required:true,
                    min:0, precision:2, formatter:formatnumberbox
                }
            }},
            {field:"std_price",   title:"Harga Jual Min", width: '11%', formatter:numberFormat, sortable: true, editor:{
                type:'numberbox',
                options:{
                    required:true,
                    min:0, precision:2, formatter:formatnumberbox
                }
            }},
            {field:"mu_persen",   title:"MU%",      width: '10%', formatter:numberFormat, sortable: true, editor:{
                type:'numberbox',
                options:{
                    required:true,
                    min:0, precision:2, formatter:formatnumberbox
                }
            }},
            {field:"gp_persen",   title:"GP%",      width: '10%', formatter:numberFormat, sortable: true, editor:{
                type:'numberbox',
                options:{
                    required:true,
                    min:0, precision:2, formatter:formatnumberbox
                }
            }},
            {field:"crtby",   title:"Create By",      width: '6%', sortable: true},
            {field:"crtdt",   title:"Create Date",      width: '7%', sortable: true},
            {field:"updby",   title:"Update By",      width: '6%', sortable: true},
            {field:"upddt",   title:"Update Date",      width: '7%', sortable: true},
        ]],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
                return
            }
            $('#tt').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        },
    })
    $('#tt').datagrid('enableFilter');
}

function openContact(){
    let row = getRow(true);
    if(row==null) return
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Supplier Contact : ${row.supplier_name}`);
    $('#tt').edatagrid({
        height:'100%',
        width:'100%',
        idField:"id",
        rownumbers:false,
        fitColumns:true,
        singleSelect:true,
        toolbar:'#toolbar22',
        url: base_url+"mastersupplier/get_contact/"+row.supplier_code,
        saveUrl: base_url+"mastersupplier/save_data_contact/"+row.supplier_code,
        onAfterEdit:function(data){
            $('#tt').edatagrid('reload');
        },
        onSave: function(index, row){
            $('#tt').edatagrid('reload');
        },
        updateUrl: base_url+"mastersupplier/edit_data_contact",
        destroyUrl: base_url+"mastersupplier/delete_data_contact",
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        columns:[[
            {field:"id",   title:"ID",      width: '7%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"supplier_code",   title:"Supplier",      width: '10%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"contact",   title:"Kontak",      width: '20%', sortable: true, editor:{type:'textbox'}},
            {field:"no_telp",   title:"No Telepon",      width: '20%', sortable: true, editor:{type:'textbox'}},
            {field:"dept",   title:"Bagian",      width: '15%', sortable: true, editor:{type:'textbox'}},
            {field:"keterangan",   title:"Keterangan",      width: '30%', sortable: true, editor:{type:'textbox'}}
        ]],
    });
    $('#tt').edatagrid('enableFilter');
}

function getRow(bool) {
    var row = $('#dg').datagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data to edit.'
            });
        }
        return null;
    }else{
        row.record = $('#dg').datagrid("getRowIndex", row);
    }
    return row;
}
function submit(){
    console.log(flag)
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
                    $('#dlg2').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
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