var options={
    url: base_url+"Somerge/load_grid_variance/"+docno, 
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"varian_type",
    sortOrder:"DESC",
    singleSelect:true, 
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"trx_no",   title:"Base On Taking#", sortable: true},
        {field:"variant_type",   title:"Variance Type", sortable: true},
        {field:"on_loc",   title:"Location", sortable: true}, 
        {field: "total_item", title: "Total Item" , sortable: true, formatter:function (index, row) {
                return numberFormat(row.total_item);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}},  
        {field: "total_qty", title: "Total Qty(Pcs)" , sortable: true, formatter:function (index, row) {
                return numberFormat(row.total_qty);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}},  
        {field: "total_retail", title: "Total Gross Retail" , sortable: true, formatter:function (index, row) {
                return numberFormat(row.total_retail);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}},  
        {field: "diskon", title: "Total Disc" , sortable: true, formatter:function (index, row) {
                return numberFormat(row.diskon);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}},  
        {field: "nett", title: "Total Net Retail" , sortable: true, formatter:function (index, row) {
                return numberFormat(row.nett);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}}, 
        {field:"action", title:"Action",    width:"20%", formatter: function(value, row){  
               var a = `<a href="#" onclick="variance('`+row.variant_type+`');" title="Edit" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="generatevariance">
                        <span class="l-btn-left l-btn-icon-left" style="margin-top: -5px;">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-eye">&nbsp;</span></span>
                        <label style="cursor:pointer">View Variance</label>
                        </a>               
                        `;  
                    return a;
            }
        }   
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
}

function variance(type){
    let row = getRow(); 
    if(row==null) return 
    $("#dgdetail").edatagrid({
        fitColumns: false,
        width: "100%",
        url: base_url + "Somerge/load_varian_detail/"+row.trx_no+"/"+type, 
        updateUrl: base_url + "Somerge/edit_data_detail",
        idField: 'item',
        method: "POST",
        pagePosition: "top",
        resizeHandle: "right",
        resizeEdge: 10,
        pageSize: 20,
        striped:true, nowrap:false,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination: true,
        sortName: "item",
        sortOrder: "asc",
        singleSelect: true,
        loadFilter: function (data) {
            data.rows = [];
            if (data.data) {
                data.rows = data.data;
            }
            return data;
        },
        onLoadSuccess: function () { 
            authbutton();
        },
        onBeginEdit: function (index, row) {
            console.log(row.trx_no)
             
        },
        onBeforeEdit: function (index, row) { 
           
            console.log(row.trx_no)
        },
        columns: [
        [
            {field: "item", title: "Product Code", width: '10%', formatter:function(index, row){return row.item;}, sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "product_code", title: "Product Code", width: '12%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
           // {field: "tipe", title: "Type", width: '5%', sortable: true, editor: {type: 'textbox', options:{disabled:true}}},
            {field: "qty" , title: "Qty", width: '8%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "uom", title: "UOM", width: '5%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}}, 
            {field: "unit_retail",  title: "Retail Price",width: '8%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.unit_retail);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}}, 
            {field: "disc",title: "Disc%",  sortable: true, editor: {type: 'textbox',options:{disabled:false}}}, 
            {field: "disc_amaount", title: "Disc Amount",width: '8%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.disc_amaount);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}}, 
            {field: "margin",title: "Margin%",  sortable: true, editor: {type: 'textbox',options:{disabled:false}}},    
            {field: "margin_amaount", title: "Margin Amount",width: '10%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.margin_amaount);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}}, 
            {field: "total_cost",align:"right", title: "Subtotal",width: '15%', sortable: true, formatter:function (index, row) {
                return numberFormat(row.total_cost);
            }, editor: {type: 'textbox',options:{disabled:true,readonly:true}}}, 
        ]],
        onSuccess: function (index, row) {
            if (row.status === 1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#dg').edatagrid('reload');
            $('#dgdetail').edatagrid('reload');
            //reload_header()
        },
        onError: function (index, e) {
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    }) 
    $('#dgdetail').datagrid('enableFilter'); 
    $('#dgdetail').datagrid('destroyFilter');
    $('#dgdetail').datagrid('enableFilter');
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
 