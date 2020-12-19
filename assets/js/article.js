var options={
	url: base_url+"masterarticle/load_grid",
	title:"Master Article",
	method:"POST",
	pagePosition:"top",
	resizeHandle:"right",
	resizeEdge:10,
	pageSize:20,
	clientPaging: false,
	remoteFilter: true,
	rownumbers: false,
	pagination:true, striped:true, nowrap:false,
	sortName:"article_code",
	sortOrder:"asc",
	singleSelect:true,
	toolbar:[
	// 	{
	// 	iconCls: 'icon-eye',
	// 	text:'View',
	// 	handler: function(){
	// 		viewdata()
	// 	}
	// },
		{
		id:'history',
		iconCls: 'icon-eye',
		text:'HPP',
		handler: function(){
			hppHistory()
		}
	},{
		iconCls: 'icon-add', id:'add',
		text:'New',
		handler: function(){
			addnew()
		}
	},
	// 	{
	// 	id:'edit',
	// 	iconCls: 'icon-edit',
	// 	text:'HPP',
	// 	handler: function(){
	// 		editData()
	// 	}
	// },
		{
		id:'delete',
		iconCls: 'icon-remove',
		text:'Delete',
		handler: function(){
			deleteData()
		}
	},{
		id:'product',
		iconCls: 'icon-product',
		text:'Products',
		handler: function(){
			showProduct()
		}
	},{
		iconCls: 'icon-download', id:'download',
		text:'Download',
		handler: function(){
			getParamOption("dg", function (x, x1, x2) {
				let urlss = base_url+"masterarticle/export_data?field="+x+"&op="+x1+"&value="+x2;
				console.log(urlss);
				window.open(urlss, '_blank')
			})
		}
	},{
		iconCls: 'icon-download', id:'download',
		text:'Download Article Size',
		handler: function(){
			let row = getRow(true);
			let urlss = base_url+"masterarticle/export_data_size/"+row.article_code+"?field=article_code&op=equal&value="+row.article_code;
			window.open(urlss, '_blank')
		}
	},{
		iconCls: 'icon-download', id:'download',
		text:'Download Article Colour',
		handler: function(){
			let row = getRow(true);
			let urlss = base_url+"masterarticle/export_data_colour/"+row.article_code+"?field=article_code&op=equal&value="+row.article_code;
			window.open(urlss, '_blank')
		}
	},{
		id:'upload',
		iconCls: 'icon-upload',
		text:'Upload Image',
		handler: function(){
			uploadImage()
		}
	}],
	loadFilter: function(data){
		data.rows = [];
		if (data.data) data.rows = data.data;
		return data;
	},
	columns:[[
		{field:"article_code", title:"Kode Article",          width: '9%', sortable: true},
		{field:"gambar", title:"",          width: '4%', formatter: function (value, row) {
			if(row.gambar===""){
				return `<a href="#" title="Image" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                                            <span class="l-btn-left l-btn-icon-left">
                                            <span class="l-btn-text l-btn-empty">&nbsp;</span>
                                            <span class="l-btn-icon icon-eye-grey">&nbsp;</span></span>
                                            </a>`;
			}else{
				return `<a href="#" onclick="window.open('${base_url}assets/images/${row.gambar}', '_blank');" title="Image" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                                            <span class="l-btn-left l-btn-icon-left">
                                            <span class="l-btn-text l-btn-empty">&nbsp;</span>
                                            <span class="l-btn-icon icon-eye">&nbsp;</span></span>
                                            </a>`;
			}

		}, sortable: true},
		{field:"article_name", title:"Article Name"  , sortable: true},
		// {field:"tipe",        title:"Tipe",     width: '10%', sortable: true},
		{field:"style",        title:"Style", sortable: true},
		// {field:"bom_pcs",       title:"BOM/PCS",     width: '8%', sortable: true},
		// {field:"foh_pcs",         title:"FOH/PCS",     width: '8%', sortable: true},
		// {field:"ongkos_jahit_pcs", title:"Ongkos Jahit/PCS",     width: '8%', sortable: true},
		// {field:"operation_cost",     title:"Operation(%)",     width: '8%', sortable: true},
		// {field:"interest_cost",      title:"Interest(%)",     width: '8%', sortable: true},
		{field:"crtby",   title:"Create By", sortable: true},
		{field:"crtdt",   title:"Create Date", sortable: true},
		{field:"updby",   title:"Update By", sortable: true},
		{field:"upddt",   title:"Update Date", sortable: true},
	]],
	onLoadSuccess:function(){
		// $('#edit').linkbutton({disabled:true});
		// $('#delete').linkbutton({disabled:true});
		$('#product').linkbutton({disabled:true});
		$('#submit').linkbutton({disabled:true});
		$('#cancel').linkbutton({disabled:true});
		disable_enable(true)
		authbutton();
	},
	onSelect: function(index, row) {
		// $('#edit').linkbutton({disabled:false});
		// $('#delete').linkbutton({disabled:false});
		$('#product').linkbutton({disabled:false});

		$('#fm').form('load',row);
		$('#cc').layout('panel', 'east').panel({title:row.article_name});
		$('#prod').edatagrid('loadData', []);
		load_article_size(row.article_code);
		load_article_colour(row.article_code);
		load_article_size_colour(row.article_code);
	}
};

function load_article_size(article_code) {
	$('#size').edatagrid('loadData', []);
	$('#size').edatagrid({
		onError:function(index, e){
			$.messager.show({
				title: 'Error',
				msg: e.message
			});
		},
		onSuccess: function(index, row){
			if(row.status===1) {
				$.messager.show({    // show error message
					title: 'Error',
					msg: row.msg
				});
			}
			$('#size').edatagrid('reload');
		},
		toolbar:[{
			iconCls: 'icon-add', id:'add',
			text:'New',
			disabled:global_auth[global_auth.appId].allow_add==="0",
			handler: function(){
				let row = getRow(true);
				if(row===null){
					$.messager.show({    // show error message
						title: 'Error',
						msg: 'Please select article before add data'
					});
					return;
				}
				$('#size').edatagrid('addRow',0)
			}
		},{
			id:'delete',
			disabled:global_auth[global_auth.appId].allow_delete==="0",
			iconCls: 'icon-remove',
			text:'Delete',
			handler: function(){
				$('#size').edatagrid('destroyRow')
			}
		},{
			id:'submit',
			iconCls: 'icon-save',
			text:'Submit',
			disabled:global_auth[global_auth.appId].allow_add==="0" && global_auth[global_auth.appId].allow_update==="0",
			handler: function(){
				$('#size').edatagrid('saveRow')
			}
		},{
			id:'cancel',
			iconCls: 'icon-undo',
			text:'Cancel',
			handler: function(){
				$('#size').edatagrid('cancelRow')
			}
		}],
		onBeginEdit: function(index,row){
			var editor = $(this).edatagrid('getEditor', {index:index,field:'art_size_code'});
			var grid = $(editor.target).combogrid('grid');
			grid.datagrid('enableFilter');
		},
		onBeforeEdit: function(index, rr){
			if(canEdit()) {
				setTimeout(function () {
					$("#size").edatagrid('cancelRow');
				},150)
				return;
			}
		},
		onLoadSuccess:function(){
			authbutton();
		},
		width:'100%',
		height:'100%',
		url: base_url+"masterarticle/load_grid_size/"+article_code,
		saveUrl: base_url+"masterarticle/save_data_size/"+article_code,
		updateUrl: base_url+"masterarticle/edit_data_size",
		destroyUrl: base_url+"masterarticle/delete_data_size",
		idField:"id",
		rownumbers:true,
		fitColumns:true,
		singleSelect:true,
		loadFilter: function(data){
			data.rows = [];
			if (data.data){
				data.rows = data.data;
			}
			return data;
		},
		onClickCell:function(){
			$(this).edatagrid('saveRow');
		},
		columns:[[
			{field:"art_size_code",   title:"Size Code",      width: '40%', sortable: true,editor:{
				type:'combogrid',
				options:{
					idField: 'size_code',
					textField:'size_code',
					url:base_url+"masterarticle/get_size/"+article_code,
					required:true,
					hasDownArrow: false,
					remoteFilter:true,
					panelWidth: 500,
					multiple:false,
					mode:'remote',
					pagePosition:"top",
					resizeHandle:"right",
					resizeEdge:10,
					pageSize:20,
					clientPaging: false,
					rownumbers: false,
					panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
						mousedown: function(){}
					}),
					editable: false,
					pagination: true,
					loadFilter: function (data) {
						data.rows = [];
						if (data.data){
							data.rows = data.data;
						}
						console.log(data.rows)
						return data;
					},
					columns: [[
						{field:'size_code',title:'Size Code',width:150},
						{field:'description',title:'Nama Size',width:250},
					]],
					fitColumns: true,
					labelPosition: 'center',
					onSelect: function(index, rw) {
						var selectedrow = $("#size").edatagrid("getSelected");
						var rowIndex = $("#size").edatagrid("getRowIndex", selectedrow);

						var ed = $('#size').edatagrid('getEditor',{
							index:rowIndex,
							field:'size_name'
						});
						$(ed.target).textbox('setValue', rw.description)

					}
				}
			}},
			{field:"size_name", title:'Description', width: '70%', sortable: true, fixed:true, editor:{
				type:"textbox",
				options:{
					disabled:true,
					readonly:true
				}
			}},
		]],
	});
}

function load_article_colour(article_code){
	$('#colour').edatagrid('loadData', []);
	$('#colour').edatagrid({
		onError:function(index, e){
			$.messager.show({
				title: 'Error',
				msg: e.message
			});
		},
		onSuccess: function(index, row){
			if(row.status===1) {
				$.messager.show({    // show error message
					title: 'Error',
					msg: row.msg
				});
			}
			$('#colour').edatagrid('reload');
		},
		toolbar:[{
			iconCls: 'icon-add', id:'add',
			disabled:global_auth[global_auth.appId].allow_add==="0",
			text:'New',
			handler: function(){
				let row = getRow(true);
				if(row===null){
					$.messager.show({    // show error message
						title: 'Error',
						msg: 'Please select article before add data'
					});
					return;
				}
				$('#colour').edatagrid('addRow',0)
			}
		},{
			id:'delete',
			iconCls: 'icon-remove',
			disabled:global_auth[global_auth.appId].allow_delete==="0",
			text:'Delete',
			handler: function(){
				$('#colour').edatagrid('destroyRow')
			}
		},{
			id:'submit',
			iconCls: 'icon-save',
			text:'Submit',
			disabled:global_auth[global_auth.appId].allow_add==="0" && global_auth[global_auth.appId].allow_update==="0",
			handler: function(){
				$('#colour').edatagrid('saveRow')
			}
		},{
			id:'cancel',
			iconCls: 'icon-undo',
			text:'Cancel',
			handler: function(){
				$('#colour').edatagrid('cancelRow')
			}
		}],
		onBeginEdit: function(index,row){
			var editor = $(this).edatagrid('getEditor', {index:index,field:'art_colour_code'});
			var grid = $(editor.target).combogrid('grid');
			grid.datagrid('enableFilter');
		},
		onBeforeEdit: function(index, rr){
			if(canEdit()) {
				setTimeout(function () {
					$("#colour").edatagrid('cancelRow');
				},150)
				return;
			}
		},
		onLoadSuccess:function () {
			authbutton();
		},
		width:'100%',
		height:'100%',
		url: base_url+"masterarticle/load_grid_colour/"+article_code,
		saveUrl: base_url+"masterarticle/save_data_colour/"+article_code,
		updateUrl: base_url+"masterarticle/edit_data_colour",
		destroyUrl: base_url+"masterarticle/delete_data_colour",
		idField:"id",
		rownumbers:true,
		fitColumns:true,
		singleSelect:true,
		loadFilter: function(data){
			data.rows = [];
			if (data.data){
				data.rows = data.data;
			}
			return data;
		},
		onClickCell:function(){
			$(this).edatagrid('saveRow');
		},
		columns:[[
			{field:"art_colour_code",   title:"Colour Code",      width: '40%', sortable: true,editor:{
				type:'combogrid',
				options:{
					idField: 'colour_code',
					textField:'colour_code',
					url:base_url+"masterarticle/get_colour/"+article_code,
					required:true,
					hasDownArrow: false,
					remoteFilter:true,
					panelWidth: 500,
					multiple:false,
					mode:'remote',
					pagePosition:"top",
					resizeHandle:"right",
					resizeEdge:10,
					pageSize:20,
					clientPaging: false,
					rownumbers: false,
					panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
						mousedown: function(){}
					}),
					editable: false,
					pagination: true,
					loadFilter: function (data) {
						data.rows = [];
						if (data.data){
							data.rows = data.data;
						}
						console.log(data.rows)
						return data;
					},
					columns: [[
						{field:'colour_code',title:'Colour Code',width:150},
						{field:'description',title:'Colour Name',width:250},
					]],
					fitColumns: true,
					labelPosition: 'center',
					onSelect: function(index, rw) {
						var selectedrow = $("#colour").edatagrid("getSelected");
						var rowIndex = $("#colour").edatagrid("getRowIndex", selectedrow);

						var ed = $('#colour').edatagrid('getEditor',{
							index:rowIndex,
							field:'colour_name'
						});
						$(ed.target).textbox('setValue', rw.description)

					}
				}
			}},
			{field:"colour_name", title:'Description', width: '70%', sortable: true, fixed:true, editor:{
				type:"textbox",
				options:{
					disabled:true,
					readonly:true
				}
			}},
		]],
	});
}

function load_article_size_colour(article_code){
	$('#prod').edatagrid('loadData', []);
	$('#prod').edatagrid({
		url: base_url+"masterarticle/load_grid_size_colour/"+article_code+"/qq/qq",
		saveUrl: base_url+"masterarticle/save_data_size_colour/"+article_code+"/qq/qq",
		onAfterEdit:function(data){
			$('#prod').edatagrid('reload');
		},
		onSave: function(index, row){
			$('#prod').edatagrid('reload');
		},
		updateUrl: base_url+"masterarticle/edit_data_size_colour",
		destroyUrl: 'destroy_user.php',
		toolbar:"#toolbarp",
		idField:"id",
		rownumbers:"true",
		fitColumns:"true",
		singleSelect:"true",
		loadFilter: function(data){
			data.rows = [];
			if (data.data){
				data.rows = data.data;
				return data;
			} else {
				return data;
			}
		},
		onLoadSuccess:function () {
			authbutton();
		},
		columns:[[
			{field:"nobar", title:'SKU', width: '20%', sortable: true },
			{field:"product_code", title:'Product Code', width: '20%', sortable: true },
			{field:"nmbar", title:'Product Name', width: '40%', sortable: true},
			{field:"uom_id", title:'UOM Jual', width: '10%', sortable: true},
			{field:"soh", title:'SOH', width: '10%', formatter:numberFormat, sortable: true},
		]],
	});
}

setTimeout(function () {
	initGrid();
},500);

$(document).ready(function () {
	keyup("bom_pcs");
	keyup("foh_pcs");
	keyup("ongkos_jahit_pcs");
	keyup("ekspedisi");
	keyup("operation_cost");
	keyup("interest_cost");
	keyup("buffer_cost");
});
function keyup(id) {
	$(`#${id}`).numberbox({
		inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
			keyup:function(e){
				hitungHPP();
			},
		})
	});
}
function hitungHPP() {
	var bom = parseFloat($("#bom_pcs").numberbox('getValue')===""?0:$("#bom_pcs").numberbox('getValue'));
	var foh = parseFloat($("#foh_pcs").numberbox('getValue')===""?0:$("#foh_pcs").numberbox('getValue'));
	var ongkos_jahit_pcs = parseFloat($("#ongkos_jahit_pcs").numberbox('getValue')===""?0:$("#ongkos_jahit_pcs").numberbox('getValue'));
	var ekspedisi = parseFloat($("#ekspedisi").numberbox('getValue')===""?0:$("#ekspedisi").numberbox('getValue'));

	console.log("bom",bom);console.log("foh",foh);console.log("ongkos_jahit_pcs",ongkos_jahit_pcs);console.log("ekspedisi",ekspedisi);

	var operation_cost = parseFloat($("#operation_cost").numberbox('getValue')===""?0:$("#operation_cost").numberbox('getValue'));
	var interest_cost = parseFloat($("#interest_cost").numberbox('getValue')===""?0:$("#interest_cost").numberbox('getValue'));
	var buffer_cost = parseFloat($("#buffer_cost").numberbox('getValue')===""?0:$("#buffer_cost").numberbox('getValue'));

	console.log("operation_cost",operation_cost);  console.log("interest_cost",interest_cost);  console.log("buffer_cost",buffer_cost);

	var hpp = bom+foh+ongkos_jahit_pcs;
	var opr_persen = hpp*operation_cost/100;
	var buf_persen = hpp*buffer_cost/100;
	var int_persen = hpp*interest_cost/100;
	var hpp2 = hpp+opr_persen+buf_persen+int_persen;
	var hpp3 = hpp2+ekspedisi;

	$("#hpp1").numberbox('setValue',hpp);
	$("#hpp2").numberbox('setValue',hpp2);
	$("#hpp_ekspedisi").numberbox('setValue',hpp3);
}

var flag = undefined;
function initGrid() {
	$('#dg').datagrid(options);
	$('#dg').datagrid('enableFilter');
}
function clearInput() {
	$('#fm').form('clear');
	$('#submit2').linkbutton({disabled:true});
	$('#cancel2').linkbutton({disabled:true});
	$('#dlg').dialog('close');
	disable_enable(false);
}

function addnew(){
	$('#dlg').dialog('open').dialog('center').dialog('setTitle',`New Article`);
	disable_enable(false);
	$('#article_code').textbox({disabled:false, readonly:false, width:'100%'});
	// $('#avg_cost').numberbox({disabled:true, readonly:true, width:'100%', label:'Average Cost'});
	// $('#price_h1').numberbox({disabled:true, readonly:true, width:'100%', label:'Price HPP H1'});
	$('#submit2').linkbutton({disabled:false});
	$('#cancel2').linkbutton({disabled:false});
	// $('#avg_cost').numberbox({disabled:true, readonly:true, width:'100%', label:''});
	// $('#price_h1').numberbox({disabled:true, readonly:true, width:'100%', label:''});
	// $('#avg_cost').numberbox('hide');
	// $('#price_h1').numberbox('hide');
	$('#fm').form('clear');
	flag = "masterarticle/save_data";
}
function editData(){
	let row = getRow(true);
	if(row==null) return;
	$('#dlg').dialog('open').dialog('center').dialog('setTitle',`Edit Article`);
	// var data = $.parseJSON(result);
	disable_enable(false)
	$('#article_code').textbox({disabled:false, readonly:true, width:'100%'});
	$('#fm').form('load',row);
	flag = "masterarticle/edit_data/"+row.tipe;
	$('#submit2').linkbutton({disabled:false});
	$('#cancel2').linkbutton({disabled:false});
}

function hppHistory(){
	let row = getRow(true);
	if(row==null) return;
	$.redirect(base_url+"Hpp/index/"+row.article_code,null,"GET","_blank")
}

function viewdata(){
	let row = getRow();
	if(row==null) return
	$('#dlg').dialog('open').dialog('center').dialog('setTitle','View Master Article');
	disable_enable(true)
	$('#fm').form('load',row);
	$('#submit2').linkbutton({disabled:true});
	$('#cancel2').linkbutton({disabled:false});

	// $('#avg_cost').numberbox({disabled:true, readonly:true, width:'100%', label:'Average Cost'});
	// $('#price_h1').numberbox({disabled:true, readonly:true, width:'100%', label:'Price HPP H1'});
	// $('#avg_cost').numberbox('show');
	// $('#price_h1price_h1').numberbox('show');
}

function uploadImage() {
	let row = getRow(true);
	if(row==null) return
	$('#dlg4').dialog('open').dialog('center').dialog('setTitle',`Upload Image : ${row.article_code} - ${row.article_name}`);
}

function cancelUpload() {
	$('#dlg4').dialog('close');
}
function submitUpload() {
	let row = getRow(true);
	if(row==null) return
	var iform = $('#formupload')[0];
	var data = new FormData(iform);
	data.append("tabel", "article");
	data.append("docno", row.article_code);
	data.append("path", "attachment/article/");

	$.ajax({
		url: base_url+"attachment/save_data",
		type: 'post',
		enctype: 'multipart/form-data',
		contentType: false,
		processData: false,
		data: data,
		success: function(result){
			var res = $.parseJSON(result);
			if (res.status===1){
				$.messager.alert("Error", res.msg)
			}else{
				$.messager.alert("Success", `Berhasil upload data.`)
			}
			cancelUpload();
		}
	});
}

function showProduct() {
	let row = getRow(true);
	if(row==null) return
	$('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Product : ${row.article_code}`);
	load_article_size_colour(row.article_code)
}

function deleteData(){
	let row = getRow(true);
	if(row==null) return
	$.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
		if (r){
			$.post(
				base_url+"masterarticle/delete_data/"+row.article_code,function(result){
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

function getRow(show) {
	var row = $('#dg').datagrid('getSelected');
	if (!row){
		if(show) {
			$.messager.show({    // show error message
				title: 'Error',
				msg: 'Please select data to edit.'
			});
		}
		return null;
	}else{
		row.record = $('#dg').datagrid("getRowIndex", row);
	}
	return row;
}
function getRowtt(show) {
	var row = $('#size').edatagrid('getSelected');
	if (!row){
		if(show) {
			$.messager.show({    // show error message
				title: 'Error',
				msg: 'Please select data to edit.'
			});
		}
		return null;
	}else{
		row.record = $('#size').edatagrid("getRowIndex", row);
	}
	return row;
}
function getRowdd(show) {
	var row = $('#colour').edatagrid('getSelected');
	if (!row){
		if(show) {
			$.messager.show({    // show error message
				title: 'Error',
				msg: 'Please select data to edit.'
			});
		}
		return null;
	}else{
		row.record = $('#colour').edatagrid("getRowIndex", row);
	}
	return row;
}
function submit(){
	console.log(flag)
	$('#fm').form('submit',{
		url: base_url+flag,
		type: 'post',
		success: function(result){
			console.log(result)
			try {
				var res = $.parseJSON(result);
				console.log(result);
				console.log(res.status);
				if (res.status === 0) {
					// $('#dlg').dialog('close');        // close the dialog
					$('#dg').datagrid('reload');    // reload the user data
					clearInput();
				} else {
					$.messager.show({
						title: 'Error',
						msg: res.msg
					});
				}
			}catch (e) {
				console.log(e)
				$.messager.show({
					title: 'Error',
					msg: e.message
				});
			}
		}
	});
}