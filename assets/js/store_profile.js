var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"storeprofile/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"store_code",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-eye',
        text:'View',
        handler: function(){
            viewdata()
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
        id:'cabang',
        iconCls: 'icon-info',
        text:'Cabang',
        handler: function(){
            openCabang()
        }
    // },{
    //     id:'inventory',
    //     iconCls: 'icon-shelf',
    //     text:'Inventory Location',
    //     handler: function(){
    //         openInvetory()
    //     }
    // },{
    //     id:'showroom',
    //     iconCls: 'icon-cogwheel',
    //     text:'Setup Showroom',
    //     handler: function(){
    //         openSetup()
    //     }
    }],
    loadFilter: function(data){
        if (data.data){
            data.rows = data.data;
        }
        return data;
    },
    rowStyler:function(index,row){
        if (row.status==="Non-Aktif"){
            return 'color:red;';
        }
    },
    columns:[[
        {field:"store_code",   title:"Kode",      width: '5%', sortable: true},
        {field:"store_name",   title:"Nama Store",      width: '20%', sortable: true},
        {field:"store_address",   title:"ALamat",      width: '30%', sortable: true},
        {field:"wilayah",   title:"Wilayah",      width: '12%', sortable: true},
        {field:"zip",   title:"ZIP Code",      width: '8%', sortable: true},
        {field:"phone",   title:"Phone",      width: '9%', sortable: true},
        {field:"fax",   title:"Fax",      width: '9%', sortable: true},
        {field:"email_address",   title:"Email",      width: '12%', sortable: true},
        // {field:"register_name",   title:"Register Name",      width: '15%', sortable: true},
        // {field:"register_date",   title:"Register Date",      width: '12%', sortable: true},
        {field:"default_stock_l",   title:"Default Stock",      width: '8%', sortable: true},
        {field:"crtby",   title:"Create By",      width: '12%', sortable: true},
        {field:"crtdt",   title:"Create Date",      width: '12%', sortable: true},
    ]],
    onLoadSuccess:function(){
        //$('#edit').linkbutton({disabled:true});
        //$('#delete').linkbutton({disabled:true});
        $('#cabang').linkbutton({disabled:true});
        $('#inventory').linkbutton({disabled:true});
        $('#showroom').linkbutton({disabled:true});
        disable_enable(true)
        authbutton();
    },
    onSelect: function(index, row) {
       // $('#edit').linkbutton({disabled:false});
       // $('#delete').linkbutton({disabled:false});
        $('#cabang').linkbutton({disabled:false});
        $('#inventory').linkbutton({disabled:false});
        $('#showroom').linkbutton({disabled:false});
    }
};

function populateRegency(id) {
    if(id==="") return;
    let row = getRow(false);
    $('#regency_id').combobox({
        url: base_url+"customer/get_regency/"+id,
        valueField: 'id',
        textField: 'name',
        prompt:'-Please Select-',
        validType:'inList["#regency_id"]',
        loadFilter: function (data) {
            return data.data;
        }
    });
    if(row!=null) $('#regency_id').combobox('select',row.regency_id)
}
function populateProvinsi() {
    $('#provinsi_id').combobox({
        url: base_url+"customer/get_provinsi",
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
function populateLocation() {
    console.log("disni")
    $('#default_stock_l').combogrid({
        idField: 'location_code',
        textField:'description',
        url:base_url+"location/load_grid",
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
            data.rows = [];
            if (data.data) data.rows = data.data;
            data.data.unshift({location_code:'',description:'-Please Select-'});
            return data;
        },
        columns: [[
            {field:'location_code',title:'Location Code',width:150},
            {field:'description',title:'Location Name',width:250},
        ]],
    });
    var grd = $('#default_stock_l').combogrid('grid');
    // grd.datagrid('destroyFilter');
    grd.datagrid('enableFilter');
}
setTimeout(function () {
    initGrid();
    populateProvinsi();
    populateLocation();
},500);

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}

function openCabang() {
    let row = getRow();
    if(row==null) return
    $('#dlg_cabang').dialog('open').dialog('center').dialog('setTitle','Cabang');
    options2.url = base_url+"cabang/load_grid/"+row.store_code;
    initGrid2()
}

function addnew(){
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Store Profile');
    $('#fm').form('clear');
    disable_enable(false)
    $('#store_code').textbox({disabled:false, readonly:false, width:'100%', required:true});
    flag = "storeprofile/save_data";
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"storeprofile/read_data/"+row.store_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit Store Profile');
            disable_enable(false)
            $('#fm').form('load',data.data);
            $('#store_code').textbox({disabled:false, readonly:true, width:'100%', required:true});
            flag = "storeprofile/edit_data";
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
        }
    });
}
function viewdata(){
    let row = getRow();
    if(row==null) return
    console.log(row);
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','View Store Profile');
    disable_enable(true)
    $('#fm').form('load',row);
    $('#default_stock_l').combogrid('setText',row.location_name)
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:false});
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"storeprofile/delete_data/"+row.store_code,function(result){
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

function getRow(bool=true) {
    var row = $('#dg').datagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data to edit.'
            });
            return null;
        }
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
                    $('#dlg').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
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
