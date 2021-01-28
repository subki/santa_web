<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>

	<?php echo $this->message->display();?>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-datagrid" style="width:100%;height: 90%">
        </table>
    </div>
</div>
<div id="toolbar" style="display: none">
  <input name="location_code" id="location_code" labelPosition="left" tipPosition="bottom" label="Lokasi:" style="width:30%">
    <a href="javascript:void(0)" class="easyui-linkbutton" id="add" onclick="addData()" iconCls="icon-add" plain="true">Add</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" id="edit" onclick="editData()" iconCls="icon-edit" plain="true">Edit</a>
</div>

<script type="text/javascript">

	var boleh = true;
	var dg = null;
	var lokasi = <?php echo json_encode($locations);?>;
	$(document).ready(function() {
		dg = $('#dg').datagrid({
			title:"List Data",
			method:"POST",
			url : base_url+"showroomrecap/grid",
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
			columns:[[
				{field:"no_faktur",   title:"Faktur",  sortable: true},
				{field:"doc_date",   title:"Trx Date",  sortable: true},
				{field:"lokasi_stock",   title:"Location",  sortable: true},
				{field:"customer_name",   title:"Customer",  sortable: true},
				{field:"status",   title:"Status",  sortable: true},
				{field:"sales_after_tax",   title:"Sls Aft Tax",  sortable: true},
			]],
			onLoadSuccess:function(){
				authbutton();
			},
		});
		dg.datagrid('destroyFilter');
		dg.datagrid('enableFilter');
		$('#location_code').combobox({
			valueField:'location_code',
			textField:'description',
			data:lokasi,
			prompt:'-Please Select-',
			validType:'inList["#location_code"]',
			formatter:function (row) {
				return '<table width="100%"><tr><td width="75%" align="left">'+row.description+'</td><td width="25%" align="right">'+row.location_code+'</td></tr></table>'
			},
			onSelect:function(rec){
				if(rec!=null){
					filterData(rec.location_code)
        }
			}
		});
		$("#location_code").combobox('setValue','<?php echo $location_code?>')
	});

	function filterData(location_code){
		dg.datagrid('removeFilterRule','location_code');
		dg.datagrid('addFilterRule', {field: 'lokasi_stock', op: 'equal', value: location_code });
		dg.datagrid('doFilter');
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
	function addData() {
		var loc = $("#location_code").combobox('getValue');
		$.redirect(base_url+"showroomrecap/form/add?location_code="+loc,{},"GET","");
	}
	function editData() {
		var r = getRow();
		if(r===null) return;
		$.redirect(base_url+"showroomrecap/form/edit?id="+row.id,{},"GET","");
	}
</script>