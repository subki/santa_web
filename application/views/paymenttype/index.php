<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-datagrid" style="width:100%;height: 90%">
        </table>
    </div>
</div>
<div id="toolbar" style="display: none">
    <a href="javascript:void(0)" class="easyui-linkbutton" id="add" onclick="addData()" iconCls="icon-add" plain="true">Add</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" id="edit" onclick="editData()" iconCls="icon-edit" plain="true">Edit</a>
</div>

<script type="text/javascript">
	var options={
		title:"List Data",
		method:"POST",
		url : base_url+"fa/paymenttype/grid",
		pagePosition:"top",
		resizeHandle:"right",
		resizeEdge:10,
		pageSize:20,
		clientPaging: false,
		remoteFilter: true,
		rownumbers: false,
		pagination:true, striped:true, nowrap:false,
		sortName:"id",
		sortOrder:"desc",
		toolbar:"#toolbar",
		singleSelect:true,
		loadFilter: function(data){
			data.rows = [];
			if (data.data) data.rows = data.data;
			return data;
		},
		columns:[[
			{field:"id",   title:"ID",       width:'10%', sortable: true},
			{field:"tipe",   title:"Tipe",       width:'10%', sortable: true},
			{field:"description",   title:"Deskripsi",       width:'50%', sortable: true},
			{field:"accno",   title:"Account No",       width:'30%', sortable: true},
		]],
		onLoadSuccess:function(){
			authbutton();
		},
	};
	$(document).ready(function() {
		$('#dg').datagrid(options);
		$('#dg').datagrid('destroyFilter');
		$('#dg').datagrid('enableFilter');
	});

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
	function addData() {
		window.location.href = base_url+"fa/Paymenttype/index/add"
	}
	function editData() {
		var r = getRow();
		if(r===null) return;
		window.location.href = base_url+"fa/Paymenttype/index/edit?id="+r.id;
	}
</script>