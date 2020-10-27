var options={
    url: base_url+"headcustomer/load_grid",
    saveUrl:base_url+"headcustomer/save_data",
    updateUrl:base_url+"headcustomer/edit_data",
    destroyUrl:base_url+"headcustomer/delete_data",
    onAfterEdit:function(data){
        $('#tt').edatagrid('reload');
    },
    onError:function (index, row) {
        $.messager.show({    // show error message
            title: 'Error2',
            msg: row.msg
        });
    },
    onSave: function(index, row){
        $('#tt').edatagrid('reload');
    },
    onDestroy:function(index, row){
        $('#tt').edatagrid('reload');
    },
    onBeforeEdit:function(index, data){
        // setTimeout(function () {
        //     let ed = $('#tt').edatagrid('getEditor',{
        //         index:index,
        //         field:'head_customer_id'
        //     });
        //     $(ed.target).textbox({disabled:false, readonly:true, width:'100%'});
        // },1000)
    },
    title:"Head Customer",
    idField:'head_customer_id',
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"head_customer_id",
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
        iconCls: 'icon-customer',
        text:'Customers',
        handler: function(){
            openWilayah();
        }
    },{
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("tt", function (x, x1, x2) {
                let urlss = base_url+"headcustomer/export_data?field="+x+"&op="+x1+"&value="+x2;
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
    columns:[[
        {field:"head_customer_id",   title:"Kode",      width: '10%', sortable: true, editor:{
                type:'textbox',
                options:{
                    required:true
                }
            }},
        {field:"nama_company",   title:"Head Customer",      width: '20%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        {field:"customer_type",   title:"Price Type",      width: '20%', sortable: true, formatter: function(value, row){
            return row.description;
        },editor:{
                type:'combobox',
                options:{
                    valueField:'code',
                    textField:'description',
                    url:base_url+"headcustomer/get_customertype",
                    required:true,
                    prompt:'-Please Select-',
                    validType:'cekKeberadaan["#tt","customer_type"]',
                    loadFilter: function (data) {
                        return data.data;
                    },
                }
            }},
        // {field:"description",   title:"Price Type Description",      width: '20%', sortable: true},
        {field:"market_type",   title:"Market Type",      width: '20%', sortable: true, editor:{type:'textbox', options:{required:true}}},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
};

setTimeout(function () {
    $('#tt').edatagrid(options);
    $('#tt').edatagrid('enableFilter');
},500);

function openWilayah(){
    let row = getRow(true);
    if(row==null) return
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Customers : ${row.nama_company}`);
    $('#dd').datagrid({
        url: base_url+"customer/load_grid",
        height:'100%',
        method:"POST",
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        idField:'customer_code',
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:false,
        sortName:"head_customer_id",
        sortOrder:"asc",
        singleSelect:true,
        columns:[[
            {field:"head_customer_id",   title:"Head ID",      width: '10%', sortable: true},
            {field:"customer_code",   title:"Kode Customer",      width: '10%', sortable: true},
            {field:"customer_name",   title:"Customer",      width: '30%', sortable: true},
            {field:"address1",   title:"Alamat",      width: '30%', sortable: true},
            {field:"provinsi",   title:"Provinsi",      width: '15%', sortable: true},
            {field:"kota",   title:"Kota/Kabupaten",      width: '15%', sortable: true},
        ]],
    });

    $('#dd').datagrid('enableFilter',[{
        field:'head_customer_id',
        type:'textbox',
        options: {
            disabled: true,
        },
    }]);
    $('#dd').datagrid('addFilterRule', {
        field: 'head_customer_id',
        op: 'equal',
        value: row.head_customer_id
    });

    $('#dd').datagrid('doFilter');

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
        row.record = $('#tt').edatagrid("getRowIndex", row);
    }
    return row;
}