$(document).ready(function () {
    populateLocation();
    
});
    var options={
    url: base_url+"Somerge/load_grid",
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
    toolbar:[
    // {
    //     iconCls: 'icon-add', id:'add',
    //     text:'New',
    //     handler: function(){
    //         window.location.href = base_url+"Somerge/form/add"
    //     }
    // },{
    //     id:'edit',
    //     iconCls: 'icon-edit',
    //     text:'Edit',
    //     handler: function(){
    //         editData()
    //     }
    // },
    // {
    //     id:'delete',
    //     iconCls: 'icon-remove',
    //     text:'Delete',
    //     handler: function(){
    //        deleteData();
    //     }
    // },
    {
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
    },{
        id:'adjOpn',
        iconCls: 'icon-search',
        text:'Adjustment Opname',
        handler: function(){
            adjOPN()
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
        {field:"location_name",   title:"Location",      width:130, sortable: true},
        {field:"trx_date",   title:"Tanggal",      width:100, sortable: true},
        {field:"on_loc",   title:"Location#",      width: 70, sortable: true},
        {field:"status",   title:"Status",      width: 70, sortable: true}, 
        {field:"useropname",   title:"User",      width: 50, sortable: true}, 
        {field:"action", title:"Action",    width:"50%", formatter: function(value, row){
               var a = `<a href="#" onclick="deleteData('`+row.trx_no+`');" title="Delete" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-remove">&nbsp;</span></span>
                        <label style="cursor:pointer" >Delete</label>
                        </a> 
                        <a href="#" onclick="closeAdj('`+row.trx_no+`');" title="Close Stockopname" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-ok">&nbsp;</span></span>
                        <label style="cursor:pointer">Close</label>
                        </a>
                        <a href="#" onclick="mergeOpname('`+row.trx_no+`');" title="Merge Opname" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-search">&nbsp;</span></span>
                        <label style="cursor:pointer">Merge</label>
                        </a> 
                        `; 
               var b = `<a href="#" onclick="printData('`+row.trx_no+`');" title="Print" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-print">&nbsp;</span></span>
                        <label style="cursor:pointer">Print</label>
                        </a>                        
                        `;
               var c = `<a href="#" onclick="variance('`+row.trx_no+`');" title="Edit" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="generatevariance">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-eye">&nbsp;</span></span>
                        <label style="cursor:pointer">View Variance</label>
                        </a>   
                        <a href="#" onclick="opengenerate('`+row.trx_no+`');" title="Open Generate" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="generatevariance">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-ok">&nbsp;</span></span>
                        <label style="cursor:pointer">Open Generate</label>
                        </a>             
                        `; 
               var d = `<a href="#" onclick="generate('`+row.trx_no+`');" title="Edit" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="generate">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-save">&nbsp;</span></span>
                        <label style="cursor:pointer">Generate Variance</label>
                        </a>                
                        `;  
               var e = `<a href="#" onclick="viewsummary('`+row.trx_no+`');" title="Edit" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="viewsummary">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-eye">&nbsp;</span></span>
                        <label style="cursor:pointer">View Variance Summary</label>
                        </a>                
                        `;    
                    if(row.status==="Open"){  
                        if(row.generate==1){ 
                            return a+b+c;
                        }
                        else{ 
                            return a+b+d;
                        }
                    }
                    else{ 
                        return b+c+e;
                    }
            }
        }
    ]],
    view: detailview,
    detailFormatter:function(index,row){
        return '<div style="padding:2px;position:relative;"><table class="ddv"></table></div>';
    },
    onExpandRow:function (index, row) {
        var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
        ddv.datagrid({
            url:base_url+"Somerge/load_grid_detail/"+row.trx_no,
            method:'GET',
            pagePosition:"top",
            resizeHandle:"right",
            resizeEdge:10,
            pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true,
            sortName:"nobar",
            sortOrder:"asc",
            singleSelect:true,
            loadFilter: function(data){
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            height:'auto', nowrap:false,
            columns:[[ 
                { field: 'trx_no',  title: 'Trx No', width: '15%',  sortable: true},
                { field: 'gondola', title: 'Gondola', width: '25%', sortable: true}, 
                { field: 'remark', title: 'Keterangan', width: '10%'  },  
                { field: 'crtby1', title: 'Create by', width: '18%', sortable: true},
                {field:"action", title:"Action",    width:"8%", formatter: function(value, rr){
                    var a = `<a href="#" onclick="editDatadetail('`+rr.trx_no+`');" title="Edit" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-edit">&nbsp;</span></span>
                        </a>
                        <a href="#" onclick="deleteDataDetail('`+rr.trx_no+`');" title="Delete" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-remove">&nbsp;</span></span>
                        </a>
                        `;
                    var b = `<a href="#" onclick="editDatadetail('`+rr.trx_no+`');" title="Edit" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-edit">&nbsp;</span></span>
                        </a>`;
                    return (row.status==="Posted") ? b:a;
                }
                }
            ]],
            onResize:function(){
                $('#dg').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
                setTimeout(function(){
                    $('#dg').datagrid('fixDetailRowHeight',index);
                },500);
            }
        });
        // ddv.datagrid('enableFilter')
        $('#dg').datagrid('fixDetailRowHeight',index);
    },
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
function variance(){
    let row = getRow();
    if(row==null) return
    window.location.href = base_url+"Somerge/form/edit?id="+row.trx_no
}
function viewsummary(){
    let row = getRow();
    if(row==null) return
    window.location.href = base_url+"Somerge/form/editsummary?id="+row.trx_no
}
function printData(){
    let row = getRow();
    if(row==null) return
         window.open(base_url+'Somerge/print_opfull/'+row.trx_no, '_blank'); 
}
 
function mergeOpname(){
    let row = getRow();
    // console.log(row.store_code)
    // return;
    var store_code=row.store_code;
    var location=row.on_loc;
    var fromdate=row.trx_date;
    if(row==null) return
    $.ajax({
      type: 'POST',
      dataType:"json",
      url:base_url+"Somerge/postdaily", 
       data: {
           store_code:store_code,
           location_code:location,
           from:fromdate,
           to:fromdate
       },
      success: function(result) { 
        if(result.status==1){
          alert(result.msg);
            $('#dg').datagrid('reload'); 
        }else{
          alert(result.msg); 
            $('#dg').datagrid('reload'); 
        }
      }
    });
}
function editDatadetail(trxno){
    // let row = getRow();
    // console.log(trxno);
    // return;
    if(trxno==null) return
    window.location.href = base_url+"Stockopname/form/edit?id="+trxno
}  
function deleteData(){
    let row = getRow(true);  
    if(row==null) return
    $.ajax({
      type: 'GET',
      dataType:"json",
      url:base_url+"Somerge/load_grid_detail/"+row.trx_no,  
      success: function(result) {  
        if(result.total < 1){
            $.messager.confirm('Confirm','Apakah anda ingin menghapus data ini?',function(r){
                    if (r){
                        $.post(
                            base_url+"Somerge/delete_data/"+row.trx_no,function(result){
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
        else{
            $.messager.confirm('Confirm','Apakah anda ingin menghapus data yang memiliki data detail?',function(r){
                if (r){
                    $.post(
                        base_url+"Somerge/delete_data/"+row.trx_no,function(result){
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
      }
    });  
} 

function deleteDataDetail(trxno){ 
    if(trxno==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"Somerge/delete_data_detail/"+trxno,function(result){
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

function opengenerate(trxno){ 
    if(trxno==null) return
    $.messager.confirm('Confirm','Are you sure you want to Open Generate this data?',function(r){
        if (r){
            $.post(
                base_url+"Somerge/opengenerate/"+trxno,function(result){
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

function generate(trxno){ 
 var opn = $("#opn_noadj").numberbox('getValue'); 
     flag = "Somerge/generateopn";   
         $.ajax({
              type: 'POST',
              dataType:"json",
              url: base_url+flag,
              data: {
                   trxno:trxno
               },
               beforeSend:function(){
                    $.messager.progress({height:75, text:'Working'});
                },
                complete: function(){
                    $.messager.progress('close');
                },
                success: function(result) {
                
                    if(result.status===1){
                        alert(res.msg)
                        $('#dg').datagrid('reload');   
                    } else {
                        $('#dg').datagrid('reload');   
                    }
              }
    })  
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
            url:base_url+"Somerge/load_gridopname/"+opn,
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
     flag = "Somerge/save_data_adj";   
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
function populateLocation() {
   $('#on_loc').combogrid({
        idField: 'on_loc',
        textField:'on_locname',
        url:base_url+"delivery/get_location/xxx",
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
          //   console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
             console.log("select",rw);
            if(rw.location_code==="") return
            $('#on_loc').combogrid('setValue',rw.location_code)
            $('#on_locname').textbox('setValue',rw.location_name)
            $("#store_code").textbox('setValue',rw.store_code);
        },
        onLoadSuccess:function(){
                var gr =  $('#on_loc').combogrid('grid')

                var data=gr.edatagrid('getData');
                console.log(data)
             for(var i =0;i < data.rows.length;i++){
                var rw=data.rows[i];
                //console.log('ds',rw)
                if(rw.location_code==on_loc){ 
                     
                    $('#on_locname').textbox('setValue',rw.location_name)
                }
            } 
        },
        columns: [[
            {field:'location_code', title:'', width:75},
            {field:'location_name', title:'Gudang', width:175}, 
        ]]
    });
    var gr =  $('#on_loc').combogrid('grid');
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter'); 
    gr.datagrid('doFilter');
}
function closeAdj(id){  
          myConfirm("Confirmation", "Anda yakin akan close Opname ?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                   $.post(
                        base_url+"Somerge/solose/"+id,function(result){
                            var res = $.parseJSON(result);
                                if (res.status===1){
                                    alert(res.msg)
                                } else {
                                    $('#dg').datagrid('reload'); 
                                }
                        }
                    );
            }
        }) 
} 
