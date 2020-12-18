var options={
    url: base_url+"Stockopname/load_grid",
    title:"StockOpname List",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"status",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            window.location.href = base_url+"Stockopname/form/add"
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
           deleteData();
        }
    },{
        id:'mergeopname',
        iconCls: 'icon-search',
        text:'Merge Opname',
        handler: function(){
            Opendialog()
        }
    },{
        id:'printOPN',
        iconCls: 'icon-print',
        text:'Print Opname',
        handler: function(){
            printOPN()
        }
    }
    //     {
    //     iconCls: 'icon-download', id:'download',
    //     text:'Export',
    //     handler: function(){
    //         getParamOption("dg", function (x, x1, x2) {
    //             let urlss = base_url+"salesorder/export_data?field="+x+"&op="+x1+"&value="+x2;
    //             window.open(urlss, '_blank')
    //         })
    //     }
    // }
    ],
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"trx_no",   title:"Trx No.",      width:130, sortable: true},
        {field:"gondola",   title:"Gondola",      width:130, sortable: true},
        {field:"trx_date",   title:"Tanggal",      width:130, sortable: true},
        {field:"on_loc",   title:"location#",      width: 100, sortable: true},
        {field:"status",   title:"Status",      width: 130, sortable: true}, 
        {field:"ref_no",   title:"Ref No.",      width: 130, sortable: true}, 
    ]],
    rowStyler:function(index,row){
        if (row.status==="Open"){
            return 'color:red;';
        }
    },
    onLoadSuccess:function(){
        authbutton();
    },
};

setTimeout(function () {
    initGrid();
},500);

function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}

function addnew(){

}
function editData(){
    let row = getRow();
    if(row==null) return
    window.location.href = base_url+"Stockopname/form/edit?id="+row.trx_no
}
 
function deleteData(){
    let row = getRow(true);
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"Stockopname/delete_data/"+row.trx_no,function(result){
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
 
 