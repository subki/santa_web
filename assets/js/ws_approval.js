var options={
    url: base_url+"wholesalesapp/load_grid",
    title:"Wholesales Approval",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"no_faktur",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:"#toolbar",
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"no_faktur",   title:"No Faktur",      sortable: true},
        // {field:"seri_pajak",   title:"Seri Pajak",      width: 100, sortable: true},
        {field:"doc_date",   title:"Trx Date",      sortable: true, formatter:function (index, row) {
            return row.ak_doc_date;
        }},
			{field:"customer_code",   title:"Code",      sortable: true},
			{field:"customer_name",   title:"Customer",      sortable: true},
			{field:"sales_after_tax",   title:"Total Sales",      sortable: true, formatter:numberFormat},
			{field:"credit_limit",   title:"Credit Limit",      sortable: true, formatter:numberFormat},
			{field:"outstanding",   title:"Credit Outstanding",      sortable: true, formatter:numberFormat},
			{field:"credit_remain",   title:"Credit Remain",      sortable: true, formatter:numberFormat},
        {field:"ket",   title:"Remark",      sortable: true},
        {field:"crtby",   title:"Create By",      sortable: true},
        {field:"crtdt",   title:"Create Date",      sortable: true},
        {field:"updby",   title:"Update By",      sortable: true},
        {field:"upddt",   title:"Update Date",      sortable: true},
    ]],
    onLoadSuccess:function(){
        authbutton();
    },
};

setTimeout(function () {
    initGrid();
},500);

function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('destroyFilter');
    $('#dg').datagrid('enableFilter');
}
var flag = "";
function submit(status) {
    flag = "";
    var row = getRow();
    if(row===null) return

    if ((parseFloat(so_item.credit_limit) - parseFloat(so_item.outstanding))< parseFloat(so_item.sales_after_tax)
        && parseFloat(so_item.sales_after_tax) > parseFloat(max_transaksi)) {
        if(so_item.creditby==="") {
            if (parseInt(global_auth[global_auth.appId].allow_approve) === 0) {
                $.messager.show({title: 'Error', msg: 'Anda tidak memiliki otoritas Posting Credit Limit'});
                return
            }
            flag = "wholesalesapp/edit_data_header_credit";
            submit_confirm("APPROVED CR")
        }else{
            if (parseInt(global_auth[global_auth.appId].allow_approve2) === 0) {
                $.messager.show({title: 'Error', msg: 'Anda tidak memiliki otoritas Posting Maksimal Sales'});
                return
            }
            flag = "wholesalesapp/edit_data_header_maximum";
            submit_confirm("APPROVED MS")
        }
    }else{
        if((parseFloat(so_item.credit_limit) - parseFloat(so_item.outstanding))< parseFloat(so_item.sales_after_tax)){
            if (parseInt(global_auth[global_auth.appId].allow_approve) === 0) {
                $.messager.show({title: 'Error', msg: 'Anda tidak memiliki otoritas Posting Credit Limit'});
                return
            }
            flag = "wholesalesapp/edit_data_header_credit";
        }
        if(parseFloat(so_item.sales_after_tax) > parseFloat(max_transaksi)){
            if (parseInt(global_auth[global_auth.appId].allow_approve2) === 0) {
                $.messager.show({title: 'Error', msg: 'Anda tidak memiliki otoritas Posting Maksimal Sales'});
                return
            }
            flag = "wholesalesapp/edit_data_header_maximum";
        }
        if(flag===""){
            $.messager.show({title: 'Error', msg: 'Flag kondisi tidak terpenuhi'});
            return
        }
        submit_confirm("CLOSED")
    }
}
function submit_confirm(status) {
    myConfirm("Confirmation", "Anda yakin ingin memposting transaksi ini?","Ya","Tidak", function (r) {
        if(r==="Ya"){
            $.ajax({
                type:"POST",
                url:base_url+flag,
                dataType:"json",
                data:{
                    docno:row.docno,
                    status:status
                },
                success:function(result){
                    console.log(result.data)
                    if(result.status===0) {
                        $('#dg').datagrid('reload');
                    }
                    else {
                        $.messager.show({
                            title: 'Error',
                            msg: e.message,
                            handler:function () {
                                $('#dg').datagrid('reload');
                            }
                        });
                    }

                }
            });
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
