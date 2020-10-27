var options2={
    fitColumns:true,
    width:"100%",
    // url: base_url+"cabang/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"kode_cabang",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            addnewCabang()
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            editDataCabang()
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            deleteDataCabang()
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
    columns:[[
        {field:"store_code",   title:"Kode",      width: '15%', sortable: true, formatter:function (value) {
                    let x = "000"+value;
                return x.substr(x.length-3,x.length)
            }},
        {field:"kode_cabang",   title:"Kode Cabang",      width: '15%', sortable: true},
        {field:"nama_cabang",   title:"Nama Cabang",      width: '40%', sortable: true},
        {field:"prefix_trx",   title:"Prefix Trx",      width: '10%', sortable: true},
        {field:"type",   title:"Type",      width: '10%', sortable: true},
        {field:"flag",   title:"Flag",      width: '10%', sortable: true},
    ]],
    onLoadSuccess:function(){
        // $('#edit_cabang').linkbutton({disabled:true});
        // $('#delete_cabang').linkbutton({disabled:true});
        $('#store_code2').textbox({disabled:true, width:'100%', value:getRow().store_code});
        $('#submit2').linkbutton({disabled:true});
        $('#cancel2').linkbutton({disabled:true});
        disable_enable(true)
        authbutton();
    },
    onSelect: function(index, row) {
        // $('#edit_cabang').linkbutton({disabled:false});
        // $('#delete_cabang').linkbutton({disabled:false});
        $('#store_code2').textbox({disabled:true, width:'100%', value:getRow().store_code});
        $('#fm2').form('load',row);
        $('#submit2').linkbutton({disabled:true});
        $('#cancel2').linkbutton({disabled:true});
    }
};
var flag2 = undefined;
function initGrid2() {
    $('#dg2').datagrid(options2);
    $('#dg2').datagrid('enableFilter');
    populateLocation2();
}
function populateLocation2() {
    $('#location_code').combogrid({
        idField: 'location_code',
        textField:'description',
        url:base_url+"location/load_grid",
        required:true,
        prompt:'-Please Select-',
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
        mode:'remote',
        loadFilter: function (data) {
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        columns: [[
            {field:'location_code',title:'Location Code',width:150},
            {field:'description',title:'Location Name',width:350},
        ]],
        onSelect:function (index, row) {
            var rows = $('#dg2').datagrid('getRows');
            console.log(rows)
            console.log(row)
            // $('#kode_cabang').textbox('setValue', getRow().store_code+("00"+(rows.length+1)).slice(-2))
            // $('#kode_cabang').textbox('setText', getRow().store_code+("00"+(rows.length+1)).slice(-2))
            $('#kode_cabang').textbox('setValue', getRow().store_code+row.location_code)
            $('#kode_cabang').textbox('setText', getRow().store_code+row.location_code)

            $('#nama_cabang').textbox('setValue', row.description)
            $('#nama_cabang').textbox('setText', row.description)
        }
    });
    var gr = $('#location_code').combogrid('grid');
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
    let row = getRow2(false);
    if(row!==null){
        $('#location_code').combogrid('setValue', row.location_code)
    }
}
function clearInput() {
    $('#fm2').form('clear');
    $('#edit_cabang').linkbutton({disabled:true});
    $('#delete_cabang').linkbutton({disabled:true});
    $('#submit2').linkbutton({disabled:true});
    $('#cancel2').linkbutton({disabled:true});
    disable_enable(true)
}
function addnewCabang(){
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle','New Cabang');
    $('#fm2').form('clear');
    disable_enable(false);
    $('#store_code2').textbox({disabled:false, readonly:true, width:'100%', value:getRow().store_code});
    $('#kode_cabang').textbox({disabled:false, readonly:true, width:'100%'});
    $('#nama_cabang').textbox({disabled:false, readonly:true, width:'100%'});
    $('#submit2').linkbutton({disabled:false});
    $('#cancel2').linkbutton({disabled:false});
    flag2 = "cabang/save_data";
    populateLocation2();
}
function editDataCabang(){
    let row = getRow2(true);
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"cabang/read_data/"+row.store_code+"/"+row.kode_cabang,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            console.log(data.data)
            disable_enable(false)
            $('#dlg2').dialog('open').dialog('center').dialog('setTitle','Edit Cabang');
            $('#fm2').form('load',data.data);
            flag2 = "cabang/edit_data";
            populateLocation2();
            $('#store_code2').textbox({disabled:false, readonly:true, width:'100%', value:getRow().store_code});
            $('#kode_cabang').textbox({disabled:false, readonly:true, width:'100%', value:getRow().kode_cabang});
            $('#submit2').linkbutton({disabled:false});
            $('#cancel2').linkbutton({disabled:false});
        }
    });
}

function deleteDataCabang(){
    let row = getRow2(true);
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"cabang/delete_data/"+row.store_code+"/"+row.kode_cabang,function(result){
                    var res = $.parseJSON(result);
                    if (res.status===1){
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: res.msg
                        });
                    } else {
                        $('#dg2').datagrid('reload');    // reload the user data
                    }
                }
            );
        }
    });
}

function getRow2(bool) {
    var row = $('#dg2').datagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data to edit.'
            });
            return null;
        }
    }else{
        row.record = $('#dg2').datagrid("getRowIndex", row);
    }
    return row;
}
function submit2(){
    console.log(flag2)
    $('#fm2').form('submit',{
        url: base_url+flag2,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    $('#dg2').datagrid('reload');    // reload the user data
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