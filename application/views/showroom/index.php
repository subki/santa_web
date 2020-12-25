<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>

<table style="width: 90%; margin:1px;">
	<?php echo $this->message->display();?>
  <tr style="width: 100%">
    <td style="margin:1px;">
      <div style="margin-bottom:1px">
        <div style="float:left; width: 40%; padding-right: 5px;">
          <input name="periode" id="periode" value="<?php echo isset($tanggal)?$tanggal:''?>" class="easyui-datebox" labelPosition="left" label="Tanggal:" style="width:100%;">
          </input>
        </div>
        <div style="float:left; width: 40%; padding-right: 5px; display: none">
          <input name="location_code" id="location_code" labelPosition="left" tipPosition="bottom" label="Lokasi:" style="width:100%">
        </div>
      </div>
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
    <a href="javascript:void(0)" class="easyui-linkbutton" id="edit" onclick="rekap()" iconCls="icon-posting" plain="true">End of Day</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" id="edit" onclick="summary()" iconCls="icon-posting" plain="true">Summary</a>
</div>

<script type="text/javascript">
	var options={
		title:"List Data",
		method:"POST",
//		url : base_url+"showroom/grid",
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
			{field:"docno",   title:"Trx. No",  sortable: true},
			{field:"doc_date",   title:"Trx Date",  sortable: true},
			{field:"location_code",   title:"Lokasi",  sortable: true},
			{field:"store_name",   title:"Store Name",  sortable: true},
//			{field:"remark",   title:"Remark",  sortable: true},
			{field:"status",   title:"Status",  sortable: true},
			{field:"sales_after_tax",   title:"Sls Aft Tax",  sortable: true},
		]],
		onLoadSuccess:function(){
			authbutton();
		},
	};
	var lokasi = <?php echo json_encode($lokasi);?>;
	$(document).ready(function() {
		console.log('lokasi',lokasi)
		$('#periode').datebox({
			onSelect: function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
				var location_code = $("#location_code").combobox('getValue')
				$('#dg').datagrid({url:base_url+"showroom/grid?location_code="+location_code+"&tanggal="+prd});
				$('#dg').datagrid('destroyFilter');
				$('#dg').datagrid('enableFilter');
				$('#dg').datagrid('addFilterRule', {field: 'doc_date', op: 'equal', value: prd });
				$('#dg').datagrid('addFilterRule', {field: 'location_code', op: 'equal', value: location_code });
				$('#dg').datagrid('doFilter');
			}
		});
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
				console.log("disini",rec)
				var date = $("#periode").datebox('getDate');
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
				$('#dg').datagrid({url:base_url+"showroom/grid?location_code="+rec.location_code+"&tanggal="+prd});
				$('#dg').datagrid('destroyFilter');
				$('#dg').datagrid('enableFilter');
				$('#dg').datagrid('addFilterRule', {field: 'doc_date', op: 'equal', value: prd });
				$('#dg').datagrid('addFilterRule', {field: 'location_code', op: 'equal', value: rec.location_code });
				$('#dg').datagrid('doFilter');
			}
		});
		$("#location_code").combobox('setValue','<?php echo $location_code?>')

		var dt = new Date();
		var y = dt.getFullYear();
		var m = dt.getMonth()+1;
		var d = dt.getDate();
		var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
		$("#periode").datebox('setValue',prd);
		var location_code = $("#location_code").val()
		$('#dg').datagrid({url:base_url+"showroom/grid?location_code="+location_code+"&tanggal="+prd});
		$('#dg').datagrid(options);
		$('#dg').datagrid('destroyFilter');
		$('#dg').datagrid('enableFilter');
		$('#dg').datagrid('addFilterRule', {field: 'doc_date', op: 'equal', value: prd });
		$('#dg').datagrid('addFilterRule', {field: 'location_code', op: 'equal', value: location_code });
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
		var tgl = $("#periode").datebox('getValue');
		var loc = $("#location_code").combobox('getValue');
		$.redirect(base_url+"showroom/form?tanggal="+tgl+"&location_code="+loc,{},"GET","");
	}
	function editData() {
		var r = getRow();
		if(r===null) return;
		$.redirect(base_url+"showroom/form/"+r.docno+"?tanggal="+r.doc_date+"&location_code="+r.location_code,{},"GET","");
	}
	function rekap() {
		var date = $("#periode").datebox('getDate');
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
		var location_code = $("#location_code").combobox('getValue');
		var v = {};
		v['location_code'] = location_code;
		v['tanggal'] = prd;
		console.log(prd);
		$.redirect(base_url+"showroom/rekap",v,"post","");
	}
	function summary() {
		var date = $("#periode").datebox('getDate');
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
		var location_code = $("#location_code").combobox('getValue');
		var v = {};
		v['location_code'] = location_code;
		v['tanggal'] = prd;
		$.ajax({
			url: base_url+"showroom/getsummary",
			type: 'post',
			dataType:'json',
			data: {
				location_code:location_code,
				tanggal:prd
			},
			success: function(res){
				console.log(res);
				if (res.status===0){
					$.messager.alert("Information","Nilai total sales : "+numberFormat(res.summary))
				}
			}
		});
	}
</script>