var so_item=undefined;
var flag = "";
$(document).ready(function () {
	so_item = undefined;

	populateCustomer();
	populateStore();
	populateCBType();
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
});
function initHeader() {
	$('#customer_code').combogrid('setValue',so_item.customer_code)

	$("#payment_date").datebox('setValue', formattanggal(so_item.payment_date,{}));
	$("#due_date").datebox('setValue', formattanggal(so_item.due_date,{}));
	$("#cleared_date").datebox('setValue', formattanggal(so_item.cleared_date,{}));

	for(var i=0; i<so_item.detail_ar.length; i++){
		addDetail(so_item.detail_ar[i])
	}

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
		prompt:'-Please Select-',
		validType:'inList["#cbtype"]',
	});

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
		columns: [[
			{field:'customer_code', title:'Kode', width:200},
			{field:'customer_name', title:'Customer', width:300},
		]]
	});
	var gr =  $('#customer_code').combogrid('grid')
	gr.datagrid('destroyFilter');
	gr.datagrid('enableFilter');
	gr.datagrid('addFilterRule', {
		field: 'gol_customer',
		op: 'equal',
		value: "Wholesales"
	});
	gr.datagrid('addFilterRule', {
		field: 'status',
		op: 'equal',
		value: "Aktif"
	});
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

var counter = 0;
function addDetail(e) {
	counter++;
	var d = {
			id: e === null ? 0 : e.id === null ? 0 : e.id,
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
		'		 	<input name="detail['+counter+'][id]" id="detail['+counter+'][id]" type="hidden" value="'+d.id+'" > ' +
		'		 	<input name="detail['+counter+'][remark]" id="detail['+counter+'][remark]" value="'+d.remark+'" class="easyui-textbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" label="Keterangan:" style="width:100%; height: 90px;"> ' +
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
		'				<input data-options="onChange:function(n,o){hitungPayment(n, o, this, '+counter+')}" value="'+d.payment_amt+'" name="detail['+counter+'][payment_amt]" id="payment_amt'+counter+'" class="easyui-numberbox'+counter+' paymentamt" labelPosition="top" tipPosition="bottom" required="true" label="Payment:" style="width:100%"> ' +
		'			</div> ' +
		'			<div style="float:left; width:50%;"> ' +
		'				<input value="'+d.outstanding_amt+'" name="detail['+counter+'][outstanding_amt]" id="outstanding_amt'+counter+'" class="easyui-numberbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" label="Outstanding:" style="width:100%"> ' +
		'			</div> ' +
		'		</div>' +
		' 	<div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: flex-end"> ' +
		' 		<a href="javascript:void(0)" class="easyui-linkbutton'+counter+'" iconCls="icon-remove" onclick="removeItem('+counter+', '+d.id+')" ></a> ' +
		'		</div> ' +
		' 	<div style="display:inline-block; width:100%; height:2px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"></div>' +
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