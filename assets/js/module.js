var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"module/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"app_id",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
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
        {field:"app_id",   title:"App ID",      width: '10%', sortable: true},
        {field:"seq",   title:"Seq",      width: '8%', sortable: true},
        {field:"app_name",   title:"App Name",      width: '32%', sortable: true},
        // {field:"description",   title:"Description",      width: '20%', sortable: true},
        {field:"url",   title:"URL",      width: '30%', sortable: true},
        {field:"parent_id",   title:"Parent",      width: '10%', sortable: true},
        {field:"icon",   title:"Icon",      width: '10%', sortable: true, formatter: function(value, row){
                return `<a href="#" class="easyui-tooltip l-btn l-btn-small l-btn-plain" >
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon ${row.icon}">&nbsp;</span></span>
                        </a>`;
            }
        }
    ]],
    onLoadSuccess:function(){
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        disable_enable(true);
				authbutton();
    },
    onSelect: function(index, row) {
        $('#fm').form('load',row);
    }
};

setTimeout(function () {
    initGrid();
},500);

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}
function clearInput() {
    $('#fm').form('clear');
    $('#app_id').textbox({disabled:false, readonly:false, width:'100%'});
    $('#seq').textbox({disabled:false, readonly:false, width:'100%'});
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});
    disable_enable(true)
}

function addnew(){
    disable_enable(false)
    $('#app_id').textbox({disabled:true, readonly:true, width:'100%'});
    $('#seq').textbox({disabled:true, readonly:true, width:'100%'});
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "module/save_data";
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"module/read_data/"+row.app_id,
        dataType:"html",
        success:function(result){
            disable_enable(false)
            var data = $.parseJSON(result);
            $('#app_id').textbox({disabled:false, readonly:true, width:'100%'});
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "module/edit_data";
        }
    });
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"module/delete_data/"+row.app_id,function(result){
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

function getRow() {
    var row = $('#dg').datagrid('getSelected');
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
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