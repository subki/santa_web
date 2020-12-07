<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>

<table style="width: 90%; margin:1px;">
  <tr style="width: 100%">
    <td style="margin:1px;">
      <input name="periode" id="periode" class="easyui-datebox" labelPosition="left" label="Tanggal:" style="width:25%;">
      </input>
    </td>
  </tr>
</table>
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
		url : base_url+"showroom/grid",
		pagePosition:"top",
		resizeHandle:"right",
		resizeEdge:10,
		pageSize:20,
		clientPaging: false,
		remoteFilter: true,
		rownumbers: false,
		pagination:true, striped:true, nowrap:false,
		sortName:"doc_date",
		sortOrder:"desc",
		toolbar:"#toolbar",
		singleSelect:true,
		loadFilter: function(data){
			data.rows = [];
			if (data.data) data.rows = data.data;
			return data;
		},
		columns:[[
			{field:"docno",   title:"Trx. No",  sortable: true},
			{field:"doc_date",   title:"Trx Date",  sortable: true},
			{field:"location_code",   title:"Lokasi",  sortable: true},
			{field:"store_name",   title:"Store Name",  sortable: true},
			{field:"remark",   title:"Remark",  sortable: true},
			{field:"status",   title:"Status",  sortable: true},
			{field:"sales_after_tax",   title:"Sls Aft Tax",  sortable: true},
		]],
		onLoadSuccess:function(){
			authbutton();
		},
	};
	$(document).ready(function() {
		$('#periode').datebox({
			onSelect: function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
				$('#dg').datagrid('destroyFilter');
				$('#dg').datagrid('enableFilter');
				$('#dg').datagrid('addFilterRule', {field: 'doc_date', op: 'equal', value: prd });
				$('#dg').datagrid('addFilterRule', {field: 'location_code', op: 'equal', value: "<?php echo $this->session->userdata(sess_location_code); ?>" });
				$('#dg').datagrid('doFilter');
			}
		});
		var dt = new Date();
		var y = dt.getFullYear();
		var m = dt.getMonth()+1;
		var d = dt.getDate();
		var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
		$("#periode").datebox('setValue',prd);
		$('#dg').datagrid(options);
		$('#dg').datagrid('destroyFilter');
		$('#dg').datagrid('enableFilter');
		$('#dg').datagrid('addFilterRule', {field: 'doc_date', op: 'equal', value: prd });
		$('#dg').datagrid('addFilterRule', {field: 'location_code', op: 'equal', value: "<?php echo $this->session->userdata(sess_location_code); ?>" });
		$('#dg').datagrid('doFilter');
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
		var vl = {};
		vl['tanggal'] = $("#periode").datebox('getValue');
		$.redirect(base_url+"showroom/form",vl,"post","");
	}
	function editData() {
		var r = getRow();
		if(r===null) return;
		var vl = {};
		$.redirect(base_url+"showroom/form/"+r.docno,vl,"post","");
	}
</script>