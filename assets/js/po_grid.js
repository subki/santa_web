$(document).ready(function () {     
 // var date = new Date();
 //        var y = date.getFullYear();
 //        var m = date.getMonth()+1;
 //        var d = date.getDate();
 //        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
 //        $("#doc_date").datebox('setValue', tgl);
 //        $("#doc_date").datebox('setText', tgl);
    $('#periode').datebox({
        // formatter:function (date) {
        //     var y = date.getFullYear();
        //     var m = date.getMonth()+1;
        //     var d = date.getDate();
        //     return y+(m<10?('0'+m):m);
        // }, 
        onSelect: function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            var prd =  y+""+(m<10?('0'+m):m)+""+(d<10?('0'+d):d); 
            var status = $('#jenis_status').combogrid('getValue'); 
            var supplier_code = $('#supplier_code').val();  
                console.log(prd)
            if(status!==""){
                $('#dg').datagrid({url:base_url+"Purchaseorder/load_grid/"+status+"/"+supplier_code+"/"+prd});
               
                // $('#dg').datagrid({url:base_url+"Purchaseorder/load_grid/", 
                //    data: {
                //        prd:prd,
                //        status:status 
                //    }});
               // $('#dg').datagrid('destroyFilter');
                $('#dg').datagrid('enableFilter');
            }
        }
    });
        
   populateSupplier();
var options={ 
   // url: base_url+"Purchaseorder/load_grid/ALL/Customer/ALL1",
    title:"Purchase Order",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"status_po",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[
    {
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            addPurchaseorder();
        }
    },
    {
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            editData()
        }
    },
    {
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
           deleteData();
        }
    },
    {
        id:'customer',
        iconCls: 'icon-customer',
        text:'Customer Info',
        handler: function(){
            showCustomer()
        }
    }
    //     {
    //     iconCls: 'icon-download', id:'download',
    //     text:'Export',
    //     handler: function(){
    //         getParamOption("dg", function (x, x1, x2) {
    //             let urlss = base_url+"salesorder/export_data?field="+x+"&op="+x1+"&value="+x2;
    //             window.Open(urlss, '_blank')
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
        {field:"po_no",   title:"No. Po",      width:130, sortable: true}, 
        {field:"po_date",   title:"Po Date",      width: 100, sortable: true},
        {field:"status_po",   title:"Status",      width: 90, sortable: true},
        {field:"tipe_supplier",   title:"Type Supplier",      width: 100, sortable: true},
        {field:"supplier_name",   title:"Supplier Name",      width: 200, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 160, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 160, sortable: true},
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

function editData(){
    let row = getRow();
    if(row==null) return
    window.location.href = base_url+"Purchaseorder/form/edit?docno="+row.po_no
}


 
function deleteData(){
    let row = getRow(true);
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"Purchaseorder/delete_data/"+row.po_no,function(result){
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

function showCustomer() {
    var row = getRow();
    if(row===null) return
    $.ajax({
        type:"POST",
        url:base_url+"customer/read_data/"+row.customer_code,
        dataType:"json",
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                showCustomer2(result.data)
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: result.message,
                    handler:function () {
                        window.location.href = base_url+"salesorder";
                    }
                });
            }

        }
    });
} 
function showCustomer2(r) {
    if(!r) return
    var msg = `
    <table>
        <tr style="vertical-align: text-top">
            <td>Name</td>
            <td> : </td>
            <td>${r.customer_name}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Address</td>
            <td> : </td>
            <td>${r.address1}<br />${r.address2}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Wilayah</td>
            <td> : </td>
            <td>${r.kota} - ${r.provinsi}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>ZIP</td>
            <td> : </td>
            <td>${r.zip}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Phone1</td>
            <td> : </td>
            <td>${r.phone1}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Fax</td>
            <td> : </td>
            <td>${r.fax}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Credit Limit</td>
            <td> : </td>
            <td>${numberFormat(r.credit_limit)}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Outstanding</td>
            <td> : </td>
            <td>${numberFormat(r.outstanding)}</td>
        </tr>
        <tr style="vertical-align: text-top">
            <td>Credit Remain</td>
            <td> : </td>
            <td>${numberFormat(r.credit_limit-r.outstanding)}</td>
        </tr>
    </table>
    `;
    $.messager.alert("Customer Info",msg);
}

});   
// function addPurchaseorder(){
//     var tglnow = $('#remarkd').datebox('getValue'); 
//             $.redirect(base_url+"Purchaseorder/form/add", {'tglnow': tglnow});  
// }
function Refresh(){ 
    window.location.href = base_url+"Purchaseorder";
}