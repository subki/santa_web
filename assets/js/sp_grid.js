var options={
    url: base_url+"Stockopname/load_grid",
    title:"StockOpname Gondola List",
    method:"POST",
    width: "100%",
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
    }
    // ,{
    //     id:'mergeopname',
    //     iconCls: 'icon-search',
    //     text:'Merge Opname',
    //     handler: function(){
    //         Opendialog()
    //     }
    // },{
    //     id:'printOPN',
    //     iconCls: 'icon-print',
    //     text:'Print Opname',
    //     handler: function(){
    //         printOPN()
    //     }
    // },{
    //     id:'adjOpn',
    //     iconCls: 'icon-search',
    //     text:'Adjustment Opname',
    //     handler: function(){
    //         adjOPN()
    //     }
    // }
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
        {field:"trx_no",   title:"Trx No.",sortable: true},
        {field:"gondola",   title:"Gondola",sortable: true},
        {field:"trx_date1",   title:"Tanggal",sortable: true},
        {field:"on_loc",   title:"Location#",sortable: true},
        {field:"status",   title:"Status",sortable: true}, 
        {field:"ref_no",   title:"Ref No.",sortable: true}, 
        {field:"useropname",   title:"Create By", sortable: true}, 
        {field:"crtdt1",   title:"Creat Time", width:150, sortable: true}, 
        {field:"updopname",   title:"Update By", sortable: true}, 
        {field:"upddt1",   title:"Update Time", width:150, sortable: true}, 
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
 
 

function addOpn() {   

     var opn = $("#opn_noadj").numberbox('getValue'); 
        $('#tt_opn').datagrid({ 
            url:base_url+"Stockopname/load_gridopname/"+opn,
            method:"POST", 
            fitColumns: true,
            pagePosition:"top",
            resizeHandle:"right",
            resizeEdge:10,
            pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true, striped:true, nowrap:false,
            sortName:"nobar",
            sortOrder:"asc",
            singleSelect:true,
            toolbar:'#toolbar1',
            loadFilter: function(data){
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            columns:[[
                { field: 'trx_no',      title: 'Nomor Opname',        width: '18%', sortable: true},
                { field: 'item',      title: 'Kode Barang',        width: '18%', sortable: true},
                { field: 'product_code',      title: 'Nama Barang',        width: '30%', sortable: true},
                { field: 'QTYStock',      title: 'Qty Stock',        width: '10%', sortable: true},
                { field: 'QTYScan',      title: 'Qty Scan',        width: '10%', sortable: true},
                { field: 'Selisih',      title: 'Variant',    formatter:numberFormat,    width: '10%', sortable: true}, 
            ]],  
            onSuccess: function (index, row) {
                if (row.status === 1) {
                    $.messager.show({    // show error message
                        title: 'Error',
                        msg: row.msg
                    });
                }
                $('#tt_opn').edatagrid('reload');   
            },
            onError: function (index, e) {
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            }
        }); 
    $('#tt_opn').datagrid('enableFilter'); 
    $('#tt_opn').datagrid('destroyFilter');
    $('#tt_opn').datagrid('enableFilter');
    $('#modal_detailOpname').dialog('open').dialog('center').dialog('setTitle',' Form Data'); 
}
function submitadjopn(){

     var opn = $("#opn_noadj").numberbox('getValue'); 
     flag = "Stockopname/save_data_adj";   
         $.ajax({
              type: 'POST',
              dataType:"json",
              url: base_url+flag,
              data: {
                   opn:opn
               },
              success: function(result) {
                    var res = result;
                    console.log(result);
                    console.log(res.status);
                    if (res.status===1){
                        alert(res.msg)
                    } else {
                        $('#dg').datagrid('reload');  
                        $('#modal_detailOpname').dialog('close'); 
                        $('#wadj').dialog('close'); 
                    }
              }
            });
          
}