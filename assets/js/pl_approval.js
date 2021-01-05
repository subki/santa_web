var options={
	url: base_url+"packinglist/load_grid",
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
		{field:"docno",   title:"Trx No",      sortable: true},
		{field:"doc_date",   title:"Trx Date",      sortable: true, formatter:function (index, row) {
			return row.ak_doc_date;
		}},
		{field:"so_number",   title:"Base SO",       sortable: true},
		{field:"tgl_so",   title:"Tgl SO",       sortable: true, formatter:function (index, row) {
			return row.ak_tgl_so;
		}},
		{field:"status",   title:"Status",       sortable: true, formatter:function (index, row) {
			return (row.status==="POSTING")?"Ready to Post":row.status;
		}},
		{field:"sales_total_pl",   title:"Sales Packing",      sortable: true, formatter:numberFormat},
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
	$('#dg').datagrid('addFilterRule', {field: 'sales_total_pl',op: 'greaterorequal',value: max_transaksi});
	$('#dg').datagrid('addFilterRule', {field: 'status',op: 'equal',value: 'OPEN'});
	$('#dg').datagrid('doFilter');
}

function viewData(){
	let row = getRow();
	if(row==null) return
	window.location.href = base_url+"packinglist/form/view?docno="+row.docno
}

function postingPL(){
	let row = getRow();
	if(row==null) return
	if (parseInt(global_auth[global_auth.appId].allow_approve) == 0) {
		$.messager.show({title: 'Error', msg: 'Anda tidak memiliki otoritas Posting Maksimal Sales'});
		return
	}
	myConfirm("Confirmation", "Anda yakin ingin memposting transaksi ini?","Ya","Tidak", function (r) {
		if(r==="Ya"){
			var vl = {};
			vl['docno'] = row.docno;
			vl['status'] = 'POSTING';
			$.redirect(base_url+"packinglist/posting_max_sales",vl,"POST","");
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