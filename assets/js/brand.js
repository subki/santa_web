var options={
    url: base_url+"productbrand/load_grid",
    title:"Product Brand",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"brand_code",
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
    },{
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("dg", function (x, x1, x2) {
                let urlss = base_url+"productbrand/export_data?field="+x+"&op="+x1+"&value="+x2;
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
        {field:"brand_code",   title:"Kode",      width:60, sortable: true},
        {field:"description",   title:"Nama Brand",      width: 200, sortable: true},
        // {field:"jenis_barang",   title:"Jenis Brg",      width: 100, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        // $('#edit').linkbutton({disabled:true});
        // $('#delete').linkbutton({disabled:true});
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
        disable_enable(true)
        authbutton();
    },
    onSelect: function(index, row) {
        // $('#edit').linkbutton({disabled:false});
        // $('#delete').linkbutton({disabled:false});

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
    populateJenisBarang();
}
function clearInput() {
    disable_enable(true)
    $('#fm').form('clear');
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});
}

function populateJenisBarang() {
    $('#jenis_barang').combobox({
        data:[
            {value:'Barang Jadi',text:'Barang Jadi'},
            {value:'Bahan Baku',text:'Bahan Baku'},
            {value:'Accessories',text:'Accessories'},
            {value:'Packing',text:'Packing'},
            {value:'Spare Part',text:'Spare Part'},
            {value:'Other',text:'Other'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#jenis_barang"]',
    });
}

function addnew(){
    disable_enable(false);
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    flag = "productbrand/save_data";
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"productbrand/read_data/"+row.brand_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            disable_enable(false)
            $('#brand_code').textbox({disabled:false, readonly:true, width:'100%'});
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "productbrand/edit_data";
        }
    });
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"productbrand/delete_data/"+row.brand_code,function(result){
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