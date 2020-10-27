var options={
    url: base_url+"mastersalesman/load_grid",
    saveUrl:base_url+"mastersalesman/save_data",
    updateUrl:base_url+"mastersalesman/edit_data",
    destroyUrl:base_url+"mastersalesman/delete_data",
    // onAfterEdit:function(data){
    //     $('#tt').edatagrid('reload');
    // },
    // onSave: function(index, row){
    //     $('#tt').edatagrid('reload');
    // },
    // onDestroy:function(index, row){
    //     $('#tt').edatagrid('reload');
    // },
    idField:'salesman_id',
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"salesman_id",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            $('#tt').edatagrid('addRow',0)
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            $('#tt').edatagrid('destroyRow')
        }
    },{
        id:'submit',
        iconCls: 'icon-save',
        text:'Submit',
        handler: function(){
            $('#tt').edatagrid('saveRow')
        }
    },{
        id:'cancel',
        iconCls: 'icon-undo',
        text:'Cancel',
        handler: function(){
            $('#tt').edatagrid('cancelRow')
        }
    },{
        id:'open_wilayah',
        iconCls: 'icon-wilayah',
        text:'Support Region',
        handler: function(){
            openWilayah();
        }
    },{
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("tt", function (x, x1, x2) {
                let urlss = base_url+"mastersalesman/export_data?field="+x+"&op="+x1+"&value="+x2;
                window.open(urlss, '_blank')
            })
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
	onLoadSuccess:function(){
		authbutton();
	},
    onBeginEdit: function(index,row){

        // var editor = $(this).edatagrid('getEditor', {index:index,field:'head_salesman'});
        // var grid = $(editor.target).combogrid('grid');
        // grid.datagrid('enableFilter');

    },
    columns:[[
        {field:"salesman_id",   title:"Nama Alias",      width: '10%', sortable: true, editor:{
                type:'textbox',
                options:{
                    disabled:false,
                    readonly:true
                }
            }},
        // {field:"nik",   title:"Nama Alias",      width: '20%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        {field:"salesman_name",   title:"Nama",      width: '20%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        // {field:"address",   title:"Alamat",      width: '20%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        // {field:"nm_prov",   title:"Provinsi",      width: '15%', sortable: true, editor:{
        //         type:'combobox',
        //         options:{
        //             valueField:'id',
        //             textField:'name',
        //             url:base_url+"mastersalesman/get_provinsi",
        //             required:true,
        //             prompt:'-Please Select-',
        //             validType:'cekKeberadaan["#tt","nm_prov"]',
        //             loadFilter: function (data) {
        //                 return data.data;
        //             },
        //             onSelect:function (row) {
        //                 console.log(row)
        //                 var selectedrow = $("#tt").edatagrid("getSelected");
        //                 var rowIndex = $("#tt").edatagrid("getRowIndex", selectedrow);
        //
        //                 var ed = $('#tt').edatagrid('getEditor',{
        //                     index:rowIndex,
        //                     field:'nm_regency'
        //                 });
        //                 $(ed.target).combobox('reload',base_url+"mastersalesman/get_regency/"+row.id);
        //             }
        //         }
        //     }},
        // {field:"nm_regency",   title:"Kota/Kabupaten",      width: '15%', sortable: true,editor:{
        //         type:'combobox',
        //         options:{
        //             valueField:'id',
        //             textField:'name',
        //             required:true,
        //             prompt:'-Please Select-',
        //             validType:'cekKeberadaan["#tt","nm_regency"]',
        //             loadFilter: function (data) {
        //                 return data.data;
        //             }
        //         }
        //     }},
        // {field:"zip",   title:"ZIP",      width: '10%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        {field:"phone1",   title:"Phone 1",      width: '12%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        // {field:"phone2",   title:"Phone 2",      width: '12%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        // {field:"head_salesman",   title:"Head",      width: '20%', sortable: true
        //     , editor:
        //     {
        //         type:'combogrid',
        //         options:{
        //             readonly:false,
        //             idField: 'salesman_id',
        //             textField:'salesman_id',
        //             url:base_url+"mastersalesman/load_grid",
        //             required:true,
        //             hasDownArrow: false,
        //             remoteFilter:true,
        //             panelWidth: 500,
        //             multiple:false,
        //             panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
        //                 mousedown: function(){}
        //             }),
        //             editable: false,
        //             pagination: true,
        //             loadFilter: function (data) {
        //                 data.rows = [];
        //                 if (data.data){
        //                     data.rows = data.data;
        //                 }
        //                 console.log(data.rows)
        //                 return data;
        //             },
        //             columns: [[
        //                 {field:'salesman_id',title:'Kode Sales',width:150},
        //                 {field:'salesman_name',title:'Nama',width:350},
        //             ]],
        //             fitColumns: true,
        //             labelPosition: 'center'
        //         }
        //     }},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 180, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 180, sortable: true},
    ]],
    onSuccess: function(index, row){
        if(row.status===1) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: row.msg
            });
            $('#tt').edatagrid('reload');
        }
    },
    onError:function(index, e){
        $.messager.show({
            title: 'Error',
            msg: e.message
        });
        $('#tt').edatagrid('reload');
    }
};

setTimeout(function () {
    $('#tt').edatagrid(options);
    $('#tt').edatagrid('enableFilter');
},500);

function openWilayah(){
    let row = getRow(true);
    if(row==null) return
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Support Region: ${row.salesman_name}`);
    $('#dd').edatagrid({
        url: base_url+"mastersalesman/get_region/"+row.salesman_id,
        saveUrl: base_url+"mastersalesman/save_data_region/"+row.salesman_id,
        updateUrl: base_url+"mastersalesman/edit_data_region",
        destroyUrl: base_url+"mastersalesman/delete_data_region",
        onAfterEdit:function(data){
            $('#dd').edatagrid('reload');
        },
        onSave: function(index, row){
            $('#dd').edatagrid('reload');
        },
        toolbar:[{
            iconCls: 'icon-add', id:'add',
            text:'New',
            handler: function(){
                $('#dd').edatagrid('addRow',0)
            }
        },{
            iconCls: 'icon-remove',
            text:'Delete',
            handler: function(){
                $('#dd').edatagrid('destroyRow')
            }
        },{
            iconCls: 'icon-save',
            text:'Submit',
            handler: function(){
                $('#dd').edatagrid('saveRow')
            }
        },{
            iconCls: 'icon-undo',
            text:'Cancel',
            handler: function(){
                $('#dd').edatagrid('cancelRow')
            }
        }],
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        height:'100%',
        idField:'id',
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:false,
        sortName:"id",
        sortOrder:"asc",
        singleSelect:true,
		onLoadSuccess:function(){
			authbutton();
		},
        columns:[[
            {field:"id",   title:"ID",      width: '15%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:true,
                        readonly:true
                    }
                }},
            {field:"salesman_id",   title:"Kode Sales",      width: '25%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:true,
                        readonly:true
                    }
                }},
            {field:"provinsi_id",   title:"Provinsi",      width: '30%', sortable: true, formatter: function(value, row){
                    return row.provinsi_name;
                }, editor:{
                    type:'combobox',
                    options:{
                        valueField:'id',
                        textField:'name',
                        url:base_url+"mastersalesman/get_provinsi",
                        required:true,
                        prompt:'-Please Select-',
                        validType:'cekKeberadaan["#dd","provinsi_id"]',
                        loadFilter: function (data) {
                            return data.data;
                        },
                        onSelect:function (row) {
                            console.log(row)
                            var selectedrow = $("#dd").edatagrid("getSelected");
                            var rowIndex = $("#dd").edatagrid("getRowIndex", selectedrow);
                            var ed = $('#dd').edatagrid('getEditor',{
                                index:rowIndex,
                                field:'regency_id'
                            });
                            $(ed.target).combobox('reload',base_url+"mastersalesman/get_regency/"+row.id);
                        }
                    }
                }},
            // {field:"provinsi_name",   title:"Nama",      width: '30%', sortable: true},
            {field:"regency_id",   title:"Kota/Kabupaten",      width: '32%', sortable: true, formatter: function(value, row){
                return row.regency_name;
            }, editor:{
                    type:'combobox',
                    options:{
                        valueField:'id',
                        textField:'name',
                        required:true,
                        prompt:'-Please Select-',
                        validType:'cekKeberadaan["#dd","regency_id"]',
                        loadFilter: function (data) {
                            return data.data;
                        }
                    }
                }},
            // {field:"regency_name",   title:"Nama",      width: '30%', sortable: true},
        ]],
    });
    $('#dd').edatagrid('enableFilter');
}

function getRow(bool) {
    var row = $('#tt').edatagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data.'
            });
        }
        return null;
    }else{
        row.record = $('#tt').datagrid("getRowIndex", row);
    }
    return row;
}