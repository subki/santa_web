var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"users/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"user_id",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            disable_enable(false)
            addnew()
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            disable_enable(false)
            editData()
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            deleteData()
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data){
            data.rows = data.data;
        }
            return data;
    },
    columns:[[
        {field:"user_id",   title:"Kode",      width: '13%', sortable: true, formatter:function (value) {
            let x = "00000000000"+value;
            return x.substr(x.length-6,x.length)
        }},
        {field:"kode_otoritas",   title:"Kode Autorisasi",      width: '10%', sortable: true},
        {field:"nik",   title:"NIK",      width: '15%', sortable: true},
        {field:"fullname",   title:"Fullname",      width: '25%', sortable: true},
        {field:"store_name",   title:"Store",      width: '20%', sortable: true},
        {field:"location_name",   title:"Location",      width: '20%', sortable: true},
        {field:"crtby",   title:"Create By",      width: '10%', sortable: true},
        {field:"crtdt",   title:"Create Date",      width: '25%', sortable: true},
        {field:"updby",   title:"Update By",      width: '10%', sortable: true},
        {field:"upddt",   title:"Update Date",      width: '25%', sortable: true},
    ]],
    onLoadSuccess:function(){
        $('#edit').linkbutton({disabled:true});
        $('#delete').linkbutton({disabled:true});
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        disable_enable(true)
    },
    onSelect: function(index, row) {
        $('#edit').linkbutton({disabled:false});
        $('#delete').linkbutton({disabled:false});

        $('#fm').form('load',row);
    }
};

setTimeout(function () {
    initGrid();
    populateStore();
    populateLocation();
},500);

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}
function clearInput() {
    $('#fm').form('clear');
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});
}

function populateStore() {
    let row = getRow(false);
    $('#store_code').combobox({
        url: base_url+"users/get_store",
        valueField: 'store_code',
        textField: 'store_name',
        prompt:'-Please Select-',
        validType:'inList["#store_code"]',
        loadFilter: function (data) {
            return data.data;
        },
        onSelect:function (rw) {
            if(rw.store_code!==""){
                // populateLocation(rw.store_code)
                $('#location_code').combobox('reload',base_url+"users/get_location/"+rw.store_code);
            }
        }
    });
    if(row!=null) {
        $('#store_code').combobox('select',row.store_code)
        // populateLocation(row.store_code)
    }
}

function populateLocation() {
    let row = getRow(false);
    $('#location_code').combobox({
        // url: base_url+"users/get_location",
        valueField: 'location_code',
        textField: 'location_name',
        prompt:'-Please Select-',
        validType:'inList["#location_code"]',
        loadFilter: function (data) {
            return data.data;
        },
    });
    if(row!=null) $('#location_code').combobox('select',row.location_code)
}

function addnew(){
    $('#user_id').textbox({disabled:true, readonly:false, width:'100%'});
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "users/save_data";
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"users/read_data/"+row.user_id,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            console.log(data);
            $('#user_id').textbox({disabled:false, readonly:true, width:'100%'});
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "users/edit_data";
        }
    });
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"users/delete_data/"+row.user_id,function(result){
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
                    // $('#dlg').dialog('close');        // close the dialog
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