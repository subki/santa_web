var pl_item=undefined;
var flag = "";
$(document).ready(function () {
    pl_item = undefined;

    populateBaseSO();

    if(aksi==="add"){
        flag = "packinglist/save_data_header";
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
        $("#doc_date").datebox('setValue', tgl);

        $("#status").textbox('setValue','OPEN');
        $("#status2").textbox('setValue','OPEN');
        $("#posting").hide();
        $("#close").hide();
        $("#print").hide();
        $("#customer").hide();
    }else{
        flag = "packinglist/edit_data_header";
        $("#submit").linkbutton({text:"Update"});
        $.ajax({
            type:"POST",
            url:base_url+"packinglist/read_data/"+docno,
            dataType:"json",
            success:function(result){
                console.log(result.data)
                if(result.status===0) {
                    pl_item = result.data;
                    if(pl_item.status==="POSTING"){
                        $("#so_number").combogrid({readonly:true});
                    }
                    $('#fm').form('load',result.data);
                    initHeader()
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
});
function initHeader() {
    initGrid();
    $("#status2").textbox('setValue', (pl_item.status==="POSTING")?"Ready to Post":pl_item.status);
    if(pl_item.status==="POSTING"){
        $("#submit").hide();
        $("#posting").linkbutton({text:"Unposting"});
    }
    if(parseInt(pl_item.qty_pl)===0){
			$("#posting").hide();
		}else{
			$("#posting").show();
    }
    if(pl_item.status==="CLOSE"){
        $("#submit").hide();
        $("#posting").hide();
    }
    $("#close").hide();
    if(aksi=="view"){
			$("#submit").hide();
			$("#posting").hide();
			$("#close").hide();
			$("#print").hide();
			$("#customer").hide();
    }
}
function printSO() {
    window.open(base_url+'packinglist/print_pl/'+docno, '_blank');
}

function reload_header() {
    $.ajax({
        type:"POST",
        url:base_url+"packinglist/read_data/"+docno,
        dataType:"json",
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                $('#fm').form('load',result.data);
                pl_item = result.data;
                initHeader()
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"packinglist";
                    }
                });
            }

        }
    });
}

function initGrid() {
    if(!pl_item) return
    $("#dg").edatagrid({
        fitColumns: true,
        width: "100%",
        url: base_url + "packinglist/load_grid_detail/"+pl_item.docno,
        saveUrl: base_url + "packinglist/save_data_detail/"+pl_item.docno,
        updateUrl: base_url + "packinglist/edit_data_detail",
        destroyUrl: base_url + "packinglist/delete_data_detail",
        idField: 'id',
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
			sortName: "seqno",
			sortOrder: "asc",
        singleSelect: true,
        toolbar: [
        //     {
        //     iconCls: 'icon-add', id:'add', text:'New',
        //     handler: function(){$('#dg').edatagrid('addRow',0)}
        // },
            {
            id:'delete', iconCls: 'icon-remove', text:'Delete',
            handler: function(){
                if (pl_item.status!=="OPEN"){
                    $.messager.show({
                        title: 'Warning',
                        msg: `Detail tidak bisa di hapus (status : ${pl_item.status})`
                    });
                    return
                }
                $('#dg').edatagrid('destroyRow')
            }
        },{
            id:'submit', iconCls: 'icon-save', text:'Submit',
            handler: function(){$('#dg').edatagrid('saveRow')}
        },{
            id:'cancel', iconCls: 'icon-undo', text:'Cancel',
            handler: function(){$('#dg').edatagrid('cancelRow')}
        }],
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
            if (row.isNewRecord && pl_item.status!=="OPEN"){
                $.messager.show({
                    title: 'Warning',
                    msg: "PL sudah di posting"
                });
                setTimeout(function () {
                    $("#dg").edatagrid('cancelRow');
                }, 500)
                return
            }
            console.log("masuk ga")
            // var editor = $(this).edatagrid('getEditor', {index: index, field: 'nobar'});
            // var grid = $(editor.target).combogrid('grid');
            // grid.datagrid('enableFilter');
        },
        onBeforeEdit: function (index, row) {
            if (row.isNewRecord) return
            if(pl_item.status!=="OPEN") {
                $.messager.show({
                    title: 'Warning',
                    msg: "Data tidak bisa di edit"
                });
                setTimeout(function () {
                    $("#dg").edatagrid('cancelRow');
                }, 500)
            }
        },
        columns: [[
            {field: "nobar", title: "Article#", width: '15%', formatter:function(index, row){return row.product_code;}, sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "nmbar", title: "Product Name", width: '30%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "tipe", title: "Type", width: '5%', sortable: true, editor: {type: 'textbox', options:{disabled:true}}},
            {field: "qty_order", title: "Qty Order", width: '20%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "qty_pl", title: "Qty Deliver", width: '20%', sortable: true,
                editor: {
                    type: 'numberbox',
                    options:{
                        required:true
                    }
                }
            },
            {field: "uom_id", title: "UOM", width: '10%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
        ]],
        onSuccess: function (index, row) {
            if (row.status === 1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#dg').edatagrid('reload');
            reload_header()
        },
        onError: function (index, e) {
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    })
  $("#dg").edatagrid('hideColumn','tipe');
	if(aksi=="view"){
		$('#dg').edatagrid({toolbar:null})
	}
}

function submit(stt){
    console.log(base_url+flag)
    let status = (stt==="")?(pl_item!==undefined)?pl_item.status:'OPEN':stt;
    if(pl_item!==undefined && pl_item.status==="POSTING" && status==="POSTING") status = 'OPEN';
    $('#status').textbox('setValue',status);

    var det = $("#dg").edatagrid('getData');
    var dt = det.rows;
    console.log(dt)
    var x = false;
    var qtypl = 0;
    var qtyord= 0;
    for(var i=0; i<dt.length; i++){
        var pl = dt[i].qty_pl;
        if(pl==="") pl = "0"
        qtypl += parseInt(pl)
        qtyord += parseInt(dt[i].qty_order)
    }
    for(var i=0; i<dt.length; i++){
        if(parseInt(dt[i].qty_pl)<parseInt(dt[i].qty_order)){
            x = true;
            break
        }
    }
    if(aksi==="add") {
        submit_header("",0)
    }else{
        if(pl_item!==undefined){
            if(pl_item.status==="POSTING" && status==="OPEN"){
                read_wholesales(function (res) {
                    if(res==="OK"){
                        if(parseInt(global_auth[global_auth.appId].allow_unposting)===0){
                            $.messager.show({title:'Error', msg:'Anda tidak memiliki otoritas Unposting'});
                            $('#status').textbox('setValue',pl_item.status);
                        }else {
                            $.messager.prompt({
                                title: 'Reason Unposting',
                                msg: 'Input reason unposting picking list:',
                                fn: function (r) {
                                    if (r) {
                                        submit_header(r,0)
                                    }
                                }
                            });
                        }
                    }else{
                        $.messager.show({title:'Error', msg:res});
                    }
                })
            }else if(pl_item.status === status){
                    submit_header("",0)
            }else{
                if(x){
                    myConfirm("Confirm", "Anda yakin ingin mengubah status packing ini?", "Yes", "No", function (r) {
                        if (r === "Yes") {
                            myConfirm3("Warning", "Qty Packing list kurang dari qty order. Apakah anda ingin back order atau closing transaksi?","Back Order","Closing","Batal", function (r) {
                                if(r==="Closing"){
                                    //close sales order
                                    $.ajax({
                                        type:"POST",
                                        url:base_url+"salesapp/edit_data_header",
                                        dataType:"json",
                                        data:{
                                            docno : pl_item.so_number,
                                            status : "CLOSE"
                                        },
                                        success:function(result){
                                            console.log(result.data)
                                            submit_header("",0)
                                        }
                                    });
                                }else if(r==="Back Order"){
                                    submit_header("",0)
                                }
                            })
                        }
                    })
                }else{
                    if(qtypl===qtyord){
											submit_header("",1)
                    }else submit_header("",0);
                }
            }
        }
    }
}

function read_wholesales(callback) {
    $.ajax({
        type:"POST",
        url:base_url+"wholesales/read_data_by_so/"+pl_item.docno,
        dataType:"json",
        success:function(result){
            console.log(result.data)
            if(result){
                var x = 0;
                for( var i=0; i<result.data; i++){
                    if(result.data[i].status !== "OPEN"){
                        x++;
                    }
                }
                if(x>0) callback("Sudah ada transaksi Wholesales yang sudah di Posting. Unposting transaksi gagal.")
                else callback("OK")
            }
        }
    });

}

function submit_header(reason, close_so) {
    $("#reason").textbox('setValue', reason);
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        dataType:'json',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    // if(close_so>0){
											// $.ajax({
											// 	type:"POST",
											// 	url:base_url+"salesapp/edit_data_header",
											// 	dataType:"json",
											// 	data:{
											// 		docno : pl_item.so_number,
											// 		status : "CLOSE"
											// 	},
											// 	success:function(result){
											// 	    window.location.href = base_url+"packinglist/form/edit?docno="+res.docno
											// 	}
											// });
                    // }else{
											window.location.href = base_url+"packinglist/form/edit?docno="+res.docno
                    // }
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: res.msg
                    });
                }
            }catch (e) {
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            }
        }
    });

}

function showCustomer() {
    console.log("disini", aksi)
    var code = ""
    if(aksi==="edit"){
        code = pl_item.customer_code;
    }else{
        var g = $('#customer_code').textboxt('getValue');
        code = g;
    }
    $.ajax({
        type:"POST",
        url:base_url+"customer/read_data/"+code,
        dataType:"json",
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                showCustomer2(result.data)
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"packinglist";
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
    </table>
    `;
    $.messager.alert("Customer Info",msg);
}

function populateBaseSO() {
   $('#so_number').combogrid({
        idField: 'docno',
        textField:'docno',
        url:base_url+"salesorder/load_grid",
        required:true,
        labelPosition:'top',
        tipPosition:'bottom',
        hasDownArrow: false,
        remoteFilter:true,
        panelWidth: 500,
        multiple:false,
		    sortName: "doc_date",
		    sortOrder: "desc",
        panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
            mousedown: function(){}
        }),
        editable: false,
        pagination: true,
        fitColumns: true,
        mode:'remote',
        loadFilter: function (data) {
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);
            if(rw.docno==="") return
            $('#ak_tgl_so').datebox('setValue',rw.ak_doc_date)
            $('#customer_code').textbox('setValue',rw.customer_code)
            $('#customer_name').textbox('setValue',rw.customer_name)
        },
        columns: [[
			{field:'docno', title:'Base SO'},
			{field:'ak_doc_date', title:'Tanggal SO'},
			{field:'status', title:'Status'},
			{field:'customer_name', title:'Customer', formatter:function (index, row) {
                return row.customer_code+" | "+row.customer_name;
            }},
		]]
    });
    var gr =  $('#so_number').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
    gr.datagrid('removeFilterRule', 'status');
    gr.datagrid('addFilterRule', {
        field: 'status',
        op: 'equal',
        value: "ON ORDER"
    });
    gr.datagrid('doFilter');
    gr.edatagrid('hideColumn', 'status');
}