var options={
    url: base_url+"salesorder/load_grid",
    title:"Sales Order",
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
            window.location.href = base_url+"salesorder/form/add"
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            editData()
        }
    },{
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
        {field:"docno",   title:"Trx No", sortable: true},
        {field:"ak_doc_date",   title:"Trx Date", sortable: true},
        {field:"status",   title:"Status", sortable: true},
        {field:"customer_code",   title:"Code", sortable: true},
        {field:"customer_name",   title:"Customer", sortable: true},
        {field:"crtby",   title:"Create By", sortable: true},
        {field:"crtdt",   title:"Create Date", sortable: true},
        {field:"updby",   title:"Update By", sortable: true},
        {field:"upddt",   title:"Update Date", sortable: true},
    ]],
    rowStyler:function(index,row){
        if (row.status==="OPEN"){
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
    window.location.href = base_url+"salesorder/form/edit?docno="+row.docno
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
