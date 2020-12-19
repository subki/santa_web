var so_item=undefined;
var flag = "";
var counter = 0;
var barisSelect = 0;
var counterSelect = 0;
var options={
	method:"POST",
	url:base_url+"fa/ar/getFaktur",
	pagePosition:"top",
	resizeHandle:"right",
	resizeEdge:10,
	pageSize:20,
	clientPaging: false,
	remoteFilter: true,
	rownumbers: false,
	pagination:true, striped:true, nowrap:false,
	sortName:"crtdt",
	sortOrder:"desc",
	singleSelect:true,
	multiple:false,
	columns:[[
		{field:"no_faktur",   title:"No Trx",  sortable: true},
		{field:"doc_date",   title:"Trx Date",      sortable: true},
		{field:"crtby",   title:"Create By",       sortable: true},
		{field:"crtdt",   title:"Create Date",       sortable: true},
		{field:"updby",   title:"Update By",       sortable: true},
		{field:"upddt",   title:"Update Date",       sortable: true},
	]],
	onDblClickRow:onDblClick
};
$(document).ready(function () {
	so_item = undefined;

	populateCustomer();
	// populateStore();
	populateTrxType();
	populateCBType();
	populateCBNumber();
	populateDBCR('dbcr');
	populatePaymentBy();

	if(aksi==="add"){
		flag = "fa/ar/save_header";
		var date = new Date();
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
		$("#payment_date").datebox('setValue', tgl);
		$("#due_date").datebox('setValue', tgl);
		$("#cleared_date").datebox('setValue', tgl);

		$("#status").textbox('setValue','OPEN');
		$("#printno").textbox('setValue','0');
		$("#update").hide();
		$("#posting").hide();
		$("#unposting").hide();
		$("#submit").show();
	}else{
		flag = "fa/ar/edit_header";
		reload_header()
	}
	$("#dg").datagrid(options)
});
function initHeader() {
	$('#customer_code').combogrid('setValue',so_item.customer_code)

	$("#payment_date").datebox('setValue', formattanggal(so_item.payment_date,{}));
	$("#due_date").datebox('setValue', formattanggal(so_item.due_date,{}));
	$("#cleared_date").datebox('setValue', formattanggal(so_item.cleared_date,{}));

	for(var i=0; i<so_item.detail_ar.length; i++){
		addDetail(so_item.detail_ar[i])
	}
	$("#docno").textbox({readonly:so_item.status!=="OPEN"})

	if(so_item.status==="OPEN") {
		$("#posting").show();
		$("#unposting").hide();
		$("#update").show();

	}else if(so_item.status==="ON PROGRESS") {
		$("#unposting").show();
		$("#posting").hide();
		$("#update").hide();
	}
	$("#submit").hide();
}
function printAR() {
	window.open(base_url+'finance/print_ar/'+id, '_blank');
}

function reload_header() {
	$.ajax({
		type:"POST",
		url:base_url+"fa/ar/read_data/"+id,
		dataType:"json",
		success:function(result){
			console.log(result.data)
			if(result.status===0) {
				$('#fm').form('load',result.data);
				so_item = result.data;
				initHeader()
			}
			else {
				$.messager.show({
					title: 'Error',
					msg: e.message,
					handler:function () {
						window.location.href = base_url+"finance/ar";
					}
				});
			}

		}
	});
}
function submitAR(){
	$.redirectForm(base_url+flag,'#fm',"post","")
}
function updateAR(){
	$.redirectForm(base_url+"fa/ar/edit_header",'#fm',"post","")
}
function postingAR(){
	myConfirm("Alert","Anda yakin ingin Posting?","Ya","Tidak",function (r) {
		if(r==="Ya"){
			$("#status").textbox('setValue','ON PROGRESS')
			$.redirectForm(base_url+"fa/ar/edit_header",'#fm',"post","")
		}
	})
}
function unpostingAR(){
	myConfirm("Alert","Anda yakin ingin Unposting?","Ya","Tidak",function (r) {
		if(r==="Ya"){
			$("#status").textbox('setValue','OPEN')
			$.redirectForm(base_url+"fa/ar/edit_header",'#fm',"post","")
		}
	})
}

function populatePaymentBy() {
	$('#payment_by').combobox({
		data:[
			{value:'CASH',text:'CASH'},
			{value:'GIRO',text:'GIRO'},
			{value:'TRANSFER',text:'TRANSFER'}
		],
		prompt:'-Please Select-',
		validType:'inList["#payment_by"]',
	});

}
function populateDBCR(id) {
	$('#'+id).combobox({
		data:[
			{value:'DEBET',text:'DEBET'},
			{value:'CREDIT',text:'CREDIT'}
		],
		prompt:'-Please Select-',
		validType:'inList["#dbcr"]',
	});
	if(id==="dbcr"){
		$('#dbcr').combobox({readonly:true})
		$('#dbcr').combobox('setValue','DEBET')
	}
}
function populateTrxType() {
	$('#trx_type').combobox({
		data:[
			{value:1,text:'Single Payment'},
			{value:2,text:'Multi Payment'},
		],
		prompt:'-Please Select-',
		validType:'inList["#trx_type"]',
	});

}
function populateCBType() {
	$('#cbtype').combobox({
		data:[
			{value:'CASH',text:'CASH'},
			{value:'BANK',text:'BANK'},
			{value:'CEK GIRO',text:'CEK GIRO'},
			{value:'PETTY CASH',text:'PETTY CASH'},
			{value:'DEPOSITO',text:'DEPOSITO'},
			{value:'CN',text:'CN'}
		],
		onSelect:function (row, index) {
			if(row===null) return
			if(row.value==="CASH"||row.value==="PETTY CASH"){
				$("#payment_by").combobox('setValue','CASH');
			}else{
				$("#payment_by").combobox('setValue','TRANSFER');
			}
			// var gr =  $('#no_cb').combogrid('grid')
			// gr.datagrid('destroyFilter');
			// gr.datagrid('enableFilter');
			// gr.datagrid('addFilterRule', {field: 'tipe_rekening', op: 'equal', value: row.value});
			// gr.datagrid('doFilter');
		},
		prompt:'-Please Select-',
		validType:'inList["#cbtype"]',
	});

}
function populateCBNumber() {
	$('#no_cb').combogrid({
		idField: 'cbaccno',
		textField:'cbaccno',
		url:base_url+"fa/rekening/grid",
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
			if (data.data) data.rows = data.data;
			return data;
		},
		onSelect:function (index, row) {
			if(row===null) return
			$("#cbtype").combobox('setValue',row.tipe_rekening)
			// $("#reff").textbox('setValue',row.tr_code)
			// $("#bg_no").textbox('setValue',row.cbaccno)
		},
		columns: [[
			// {field:'accno', title:'Acc No', width:100},
			{field:'cbaccno', title:'No Rekening', width:300},
			{field:'accname', title:'Account Name', width:300},
			{field:'tr_code', title:'Trx Code', width:100},
		]]
	});
	var gr =  $('#no_cb').combogrid('grid')
	gr.datagrid('destroyFilter');
	gr.datagrid('enableFilter');
}
function populateStore() {
	$('#store_code').combogrid({
		idField: 'store_code',
		textField:'store_name',
		url:base_url+"storeprofile/load_grid",
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
			// console.log(data)
			data.rows = [];
			if (data.data) data.rows = data.data;
			data.data.unshift({customer_code:'~',customer_name:'All Customer'});
			return data;
		},
		columns: [[
			{field:'store_code', title:'', width:75},
			{field:'store_name', title:'Sales Toko', width:175},
		]]
	});
	var gr =  $('#store_code').combogrid('grid')
	gr.datagrid('destroyFilter');
	gr.datagrid('enableFilter');
}

function populateCustomer() {
	$('#customer_code').combogrid({
		idField: 'customer_code',
		textField:'customer_name',
		url:base_url+"customer/load_grid",
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
			if (data.data) data.rows = data.data;
			return data;
		},
		columns: [[
			{field:'customer_code', title:'Kode', width:200},
			{field:'customer_name', title:'Customer', width:300},
		]]
	});
	var gr =  $('#customer_code').combogrid('grid')
	gr.datagrid('destroyFilter');
	gr.datagrid('enableFilter');
	gr.datagrid('addFilterRule', {field: 'gol_customer', op: 'equal', value: "Wholesales"});
	gr.datagrid('addFilterRule', {field: 'status', op: 'equal', value: "Aktif"});
	gr.datagrid('doFilter');
}
function removeItem(baris, idd) {
	if(idd>0){
		myConfirm("Alert","Anda yakin ingin hapus?","Ya","Tidak",function (r) {
			if(r==="Ya"){
				$.redirect(base_url+"fa/ar/delete_detail/"+idd,{id_det:idd,id_head:id},"POST","")
			}
		})
	}else{
		$('#detailBaris'+baris).remove();
	}
}
function getFaktur(baris, idd) {
	var cust = $("#customer_code").combogrid('getValue');
	if(cust===""){
		$.messager.alert("Error","Customer harus dipilih terlebih dahulu")
		return
	}
	counterSelect = baris;
	barisSelect = idd;
	$('#dlg').dialog('open');
	$("#dg").datagrid('destroyFilter');
	$("#dg").datagrid('enableFilter');
	$("#dg").datagrid('addFilterRule', {field: 'customer_code',op: 'equal',value: cust});
	$("#dg").datagrid('addFilterRule', {field: 'status',op: 'equal',value: "CLOSED"});
	$("#dg").datagrid('addFilterRule', {field: 'verifikasi_finance',op: 'equal',value: "VERIFIED"});
	$("#dg").datagrid('doFilter');
}
function onDblClick (index, row) {
	console.log(index)
	console.log(row)
	console.log(counterSelect)
	$("#dbcr" + counterSelect).combobox('setValue',"CREDIT")
	$("#associatedid" + counterSelect).val(row.id)
	$("#associatedwith" + counterSelect).val("sales_invoice")
	$("#remark" + counterSelect).textbox('setValue',row.no_faktur)
	$("#payment_amt" + counterSelect).numberbox('setValue',0)
	$("#outstanding_amt" + counterSelect).numberbox('setValue',(row.sisa_faktur)?row.sisa_faktur:0)
	$("#cost_center" + counterSelect).textbox('setValue',row.location_code)
	$("#gl_account" + counterSelect).textbox('setValue',row.gl_account)
	counterSelect="";
	barisSelect = "";
	$('#dlg').dialog('close');
}

function hitungPayment(newValue, oldValue, e, nomor){
	console.log("masuk");
	console.log(e);
	console.log(nomor);
	var l = $('.paymentamt').length; var result = [];
	console.log(l)
	for (var i = 0; i < l; i++) result.push(parseFloat($('.paymentamt').eq(i).val()));
	var total = 0;
	for (var i = 0; i < result.length; i++) total += isNaN(result[i])?0:result[i];
	$("#payment_amount").numberbox('setValue', total);
}
function addDetail(e) {
	var tipe = $("#trx_type").combobox('getValue')
	if(tipe === ""){
		$.messager.alert("Error","Tipe Transaksi harus dipilih terlebih dahulu")
		return
	}
	counter++;
	var d = {
			id: e === null ? 0 : e.id === null ? 0 : e.id,
		trx_type: e === null ? tipe : e.trx_type === null ? tipe : e.trx_type,
		associatedid: e === null ? '' : e.associatedid === null ? '' : e.associatedid,
		associatedwith: e === null ? '' : e.associatedwith === null ? '' : e.associatedwith,
			cbhistoryid: e === null ? '' : e.cbhistoryid === null ? '' : e.cbhistoryid,
			seqno: e === null ? '' : e.seqno === null ? '' : e.seqno,
			tipe: e === null ? '' : e.tipe === null ? '' : e.tipe,
			dbcr: e === null ? '' : e.dbcr === null ? '' : e.dbcr,
			cost_center: e === null ? '' : e.cost_center === null ? '' : e.cost_center,
			gl_account: e === null ? '' : e.gl_account === null ? '' : e.gl_account,
			remark: e === null ? '' : e.remark === null ? '' : e.remark,
			outstanding_amt: e === null ? '' : e.outstanding_amt === null ? '' : e.outstanding_amt,
			payment_amt: e === null ? '' : e.payment_amt === null ? '' : e.payment_amt,
			customer_code: e === null ? '' : e.customer_code === null ? '' : e.customer_code,
			status: e === null ? '' : e.status === null ? '' : e.status
		}
	var html =' ' +
		'<div id="detailBaris'+counter+'"' +
		'	<div style="margin-top:10px"> ' +
		'		<div style="float:left; width: 45%; padding-right: 10px;">' +
		'		 	<input name="detail['+counter+'][id]" id="detail['+counter+'][id]" type="hidden" value="'+d.id+'"  > ' +
		'		 	<input name="detail['+counter+'][associatedid]" id="associatedid'+counter+'" type="hidden" value="'+d.associatedid+'"  > ' +
		'		 	<input name="detail['+counter+'][associatedwith]" id="associatedwith'+counter+'" type="hidden" value="'+d.associatedwith+'" > ' +
		'		 	<input name="detail['+counter+'][remark]" id="remark'+counter+'" value="'+d.remark+'" class="easyui-textbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" label="Keterangan:" style="width:100%; height: 90px;"> ' +
		'		</div> ' +
		'		<div style="float:left; width: 45%;">' +
		'			<div style="float:left; width: 10%; padding-right: 10px;"> ' +
		'				<input class="easyui-checkbox'+counter+'" name="detail['+counter+'][tipe]" id="tipe'+counter+'" value="rounded" label="Round:"> ' +
		'			</div> ' +
		'			<div style="float:left; width: 30%; padding-right: 10px;"> ' +
		'				<input name="detail['+counter+'][dbcr]" id="dbcr'+counter+'" class="easyui-combobox'+counter+'" labelPosition="top" tipPosition="right" required="true" label="Debit / Credit:" style="width:100%"> ' +
		'			</div> ' +
		'			<div style="float:left; width:30%; padding-right: 10px;"> ' +
		'				<input value="'+d.cost_center+'" name="detail['+counter+'][cost_center]" id="cost_center'+counter+'" class="easyui-textbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" label="Cost Center:" style="width:100%"> ' +
		'			</div> ' +
		'			<div style="float:left; width:30%;"> ' +
		'				<input value="'+d.gl_account+'" name="detail['+counter+'][gl_account]" id="gl_account'+counter+'" class="easyui-textbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" label="GL Account:" style="width:100%"> ' +
		'			</div> ' +
		'		</div>' +
		'		<div style="float:left; width: 45%;">' +
		'			<div style="float:left; width:50%; padding-right: 10px;"> ' +
		'				<input required data-options="onChange:function(n,o){hitungPayment(n, o, this, '+counter+')}" value="'+d.payment_amt+'" name="detail['+counter+'][payment_amt]" id="payment_amt'+counter+'" class="easyui-numberbox'+counter+' paymentamt" labelPosition="top" tipPosition="bottom" required="true" label="Payment:" style="width:100%"> ' +
		'			</div> ' +
		'			<div style="float:left; width:50%;"> ' +
		'				<input value="'+d.outstanding_amt+'" name="detail['+counter+'][outstanding_amt]" id="outstanding_amt'+counter+'" class="easyui-numberbox'+counter+'" labelPosition="top" tipPosition="bottom" label="Outstanding:" style="width:100%"> ' +
		'			</div> ' +
		'		</div>' +
		' 	<div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: flex-end"> ' +
		' 		<a href="javascript:void(0)" class="easyui-linkbutton'+counter+'" iconCls="icon-remove" onclick="removeItem('+counter+', '+d.id+')" ></a> ' +
		'		</div> ';
	if(parseInt(d.trx_type)===1 && d.id===0){
		html+=' 	<div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: flex-end"> ' +
		' 		<a href="javascript:void(0)" class="easyui-linkbutton'+counter+'" iconCls="icon-eye" onclick="getFaktur('+counter+', '+d.id+')" ></a> ' +
		'		</div> ';
	}
		html+=' 	<div style="display:inline-block; width:100%; height:2px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"></div>' +
		'	</div>' +
		'</div>';
	$("#detail").append(html);
	$(".easyui-textbox"+counter).textbox();
	$(".easyui-numberbox"+counter).numberbox();
	$(".easyui-combobox"+counter).combobox();
	$(".easyui-checkbox"+counter).checkbox();
	$(".easyui-linkbutton"+counter).linkbutton();
	// $("#payment_amt"+counter).numberbox({
	// 	onChange:function (newValue, oldValue) {
	// 		console.log(counter+"old", oldValue)
	// 		console.log(counter+"new", newValue)
	// 	}
	// });
	populateDBCR('dbcr'+counter)
	if(e!==null){
		$('#dbcr'+counter).combobox('setValue',e.dbcr)
		$('#tipe'+counter).checkbox({checked: e.tipe==="ROUNDED"});
	}
}