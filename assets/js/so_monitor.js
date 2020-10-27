var options={
    url: base_url+"salesapp/load_grid_monitor",
    title:title,
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"docno",
    sortOrder:"desc",
    singleSelect:true,
    toolbar:"#toolbar",
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"docno",   title:"Trx No",      width:130, sortable: true},
        {field:"ak_doc_date",   title:"Trx Date",      width: 100, sortable: true},
        {field:"customer_code",   title:"Customer",      width: 100, sortable: true},
        {field:"sales_after_tax",   title:"Total Trx",   formatter:numberFormat,   width: 100, sortable: true},
        {field:"status",   title:"Status",      width: 90, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 160, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 160, sortable: true},
    ]],
    rowStyler:function(index,row){
        if (row.qty_deliver<row.qty_order){
            return 'color:red;';
        }
    },
    view: detailview,
    detailFormatter:function(index,row){
        return '<div style="width: 100%; height: 100%; vertical-align: top"><table class="ddvx"></table></div>';
    },
    onExpandRow:function (index, row_l) {
        let ddvx = $(this).datagrid('getRowDetail', index).find('table.ddvx');
        ddvx.datagrid({
            url:base_url+"salesorder/load_grid_detail/"+row_l.docno,
            method:'POST',
            pagePosition:"top",
            resizeHandle:"right",
            fitColumns:true,
            resizeEdge:10,
            pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            pagination:true, striped:true, nowrap:false,
            sortName:"seqno",
            sortOrder:"desc",
            singleSelect:true,
            onResizeColumn:function(field, width){
                var state = $.data(this, 'datagrid');
                var opts = state.options;

                var col = $(this).datagrid('getColumnOption', field);
                col.width = width/(state.dc.view.width()-opts.scrollbarSize)*100+'%';
                $(this).datagrid('resize');
            },
            loadFilter: function(data){
                data.rows = [];
                if (data.data){
                    data.rows = data.data;
                }
                return data;
            },
            height:'auto',
            columns: [[
                {
                    field: "nobar",
                    title: "Article#",
                    width: '9%',
                    sortable: true,
                    formatter: function (value, row) {
                        return row.product_code;
                    }},
                {field: "nmbar", title: "Product Name", width: '12%', sortable: true},
                {field: "tipe", title: "Type", width: '5%', sortable: true},
                {field: "qty_pl", title: "Qty PL", width: '6%', sortable: true},
                {field: "qty_order", title: "Qty Ord", width: '6%', sortable: true},
                {field: "satuan_jual", title: "UOM", width: '6%', sortable: true, formatter:function(index, row){
                        return row.uom_id;
                    }},
                {field: "unit_price", title: "Retail", width: '9%', sortable: true, formatter:function (index, row) {
                        return numberFormat(row.unit_price);
                    }},
                {field: "disc1_persen", title: "Disc1%", width: '6%', sortable: true},
                {field: "disc2_persen", title: "Disc2%", width: '6%', sortable: true},
                {field: "disc3_persen", title: "Disc3%", width: '6%', sortable: true},
                {field: "disc_total", title: "Total Disc", width: '10%', sortable: true, formatter:function (index, row) {
                        return numberFormat(row.disc_total);
                    }},
                {field: "bruto_before_tax", title: "Sls Bfr Tax", width: '10%', sortable: true, formatter:function (index, row) {
                        return numberFormat(row.bruto_before_tax);
                    }},
                {field: "total_tax", title: "PPN", width: '10%', sortable: true, formatter:function (index, row) {
                        return numberFormat(row.total_tax);
                    }}
            ]],
            rowStyler:function(index,row){
                if (parseInt(row.qty_pl)<parseInt(row.qty_order)){
                    return 'color:red;';
                }
            },
            onResize:function(){
                $('#dg').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
                setTimeout(function(){
                    $('#dg').datagrid('fixDetailRowHeight',index);
                },500);
            }
        });
        $('#dg').datagrid('fixDetailRowHeight',index);
    }
};

setTimeout(function () {
    initGrid();
},500);

function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('destroyFilter');
    $('#dg').datagrid('enableFilter');
}

function submit() {
    if(parseInt(global_auth[global_auth.appId].allow_approve)===0){
        $.messager.show({title:'Error', msg:'Anda tidak memiliki otoritas Posting'});
        return
    }
    var row = getRow();
    if(row===null) return

    $.ajax({
        type:"POST",
        url:base_url+"salesorder/load_grid_detail/"+row.docno,
        dataType:"json",
        success:function(result){
            console.log(result.data)
            var status = "";
            if(result.data.length>0){
                var qty_pl = 0;
                var qty_ord= 0;
                for(var i=0; i<result.data.length; i++){
                    var pl = result.data[i].qty_pl;
                    if(pl==="") pl = "0"
                    qty_pl += parseInt(pl)
                    qty_ord += parseInt(result.data[i].qty_order)
                }
                if(qty_pl>0){
                    if(qty_pl<qty_ord){
                        status = "EXPIRED"
                    }
                    if(qty_pl === qty_ord){
                        status = "CLOSE"
                    }
                }else{
                    status = "CANCEL"
                }
            }else{
                status = "CANCEL"
            }
            if(status!==""){
                myConfirm("Confirmation", "Anda yakin ingin mengubah status transaksi ini?","Ya","Tidak", function (r) {
                    if(r==="Ya"){
                        $.ajax({
                            type:"POST",
                            url:base_url+"salesapp/edit_data_header",
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
