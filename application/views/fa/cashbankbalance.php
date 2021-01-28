<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<div id="tt" class="easyui-tabs" style="width:100%;height:100%;">
  <div title="Yearly" style="padding:5px;display:none">
    <table id="dg" class="easyui-datagrid" style="width:auto;height: 45%"></table>
    <br />
    <table id="dg2" class="easyui-datagrid" style="width:auto;height: 45%"></table>
  </div>
  <div title="Monthly" style="padding:5px;display:none;">
    <table id="dd" class="easyui-datagrid" style="width:auto;height: 45%"></table>
    <br />
    <table id="dd2" class="easyui-datagrid" style="width:auto;height: 45%"></table>
  </div>
</div>

<script type="text/javascript">
	var options={
		method:"POST",
		url : base_url+"fa/cashbankbalance/grid",
		pagePosition:"top",
		resizeHandle:"right",
		clientPaging: false,
		remoteFilter: true,
		rownumbers: true,
		pagination:false, striped:true, nowrap:false,
		sortName:"accno",
		sortOrder:"asc",
		singleSelect:true,
		columns:[[
			{field:"tahun",   title:"Tahun",       sortable: true},
			{field:"tipe_rekening",   title:"Tipe",       sortable: true},
			{field:"accno",   title:"Account#",       sortable: true},
			{field:"accname",   title:"Acc Name",       sortable: true},
			{field:"debet",   title:"Debet",   align:'right',    sortable: true, formatter:numberFormat},
			{field:"credit",   title:"Credit",   align:'right',    sortable: true, formatter:numberFormat}
		]],
		onLoadSuccess:function(){
			authbutton();
		},
		onSelect:function (index, row) {
			$("#dg2").datagrid('removeFilterRule')
			$('#dg2').datagrid('addFilterRule', {field: 'no_cb',op: 'equal',value: row.cbaccno});
			$("#dg2").datagrid('doFilter')
		}
	};
	var options2={
		method:"POST",
		url : base_url+"fa/cashbankbalance/grid_detail",
		pagePosition:"top",
		resizeHandle:"right",
		clientPaging: false,
		remoteFilter: true,
		rownumbers: true,
		pagination:false, striped:true, nowrap:false,
		sortName:"payment_date",
		sortOrder:"desc",
		singleSelect:true,
		loadFilter: function(data){
			var row = getRow(false);
			if(row==null){
				data.rows = [];
				return data;
			}
			if (data.rows){
				var head = {
					account:row.accno,
					payment_date:'-',
					customer_vendor:row.accname,
					debet:row.debet,
					credit:row.credit,
				}
				data.rows.unshift(head);
			}
			return data;
		},
		columns:[[
			{field:"account",   title:"Account#",       sortable: true},
			{field:"payment_date",   title:"Trx Date",       sortable: true},
			{field:"customer_vendor",   title:"Customer/Vendor",       sortable: true},
			{field:"debet",   title:"Debet",   align:'right',    sortable: true, formatter:numberFormat},
			{field:"credit",   title:"Credit",   align:'right',    sortable: true, formatter:numberFormat}
		]],
		onLoadSuccess:function(){
			authbutton();
		}
	};
	$(document).ready(function() {
		$('#dg').datagrid(options);
		$('#dg').datagrid('destroyFilter');
		$('#dg').datagrid('enableFilter');
		$('#dg').datagrid('addFilterRule', {field: 'tahun',op: 'equal',value: 2020});
		$("#dg").datagrid('doFilter')
		$('#dg2').datagrid(options2);
		$('#dg2').datagrid('destroyFilter');
		$('#dg2').datagrid('enableFilter');
	});

	function getRow(bool=true) {
		var row = $('#dg').datagrid('getSelected');
		if (!row){
			if(bool) {
				$.messager.show({    // show error message
					title: 'Error',
					msg: 'Please select data to edit.'
				});
				return null;
			}
		}else{
			row.record = $('#dg').datagrid("getRowIndex", row);
		}
		return row;
	}
</script>