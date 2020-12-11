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
        <div style="float:left; width: 40%; padding-right: 5px;">
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
<!--  <a href="javascript:void(0)" class="easyui-linkbutton" id="add" onclick="addData()" iconCls="icon-add" plain="true">Add</a>-->
<!--  <a href="javascript:void(0)" class="easyui-linkbutton" id="edit" onclick="editData()" iconCls="icon-edit" plain="true">Edit</a>-->
  <a href="javascript:void(0)" class="easyui-linkbutton" id="edit" onclick="printRekap()" iconCls="icon-print" plain="true">Print</a>
</div>

<script type="text/javascript">
	var options={
		title:"List Data",
		method:"POST",
		pagePosition:"top",
		resizeHandle:"right",
		resizeEdge:10,
		pageSize:20,
		clientPaging: false,
		remoteFilter: true,
		rownumbers: false,
		pagination:true, striped:true, nowrap:false,
		sortName:"tanggal",
		sortOrder:"desc",
		toolbar:"#toolbar",
		singleSelect:true,
		columns:[[
			{field:"tanggal",   title:"Trx Date",  sortable: true},
			{field:"location_code",   title:"Lokasi",  sortable: true},
			{field:"locname",   title:"Store Name",  sortable: true},
			{field:"ptname",   title:"Payment Method",  sortable: true},
			{field:"total_bayar",   title:"Total Bayar",  sortable: true},
			{field:"adminfee",   title:"Admin %",  sortable: true, styler:function(value,row){
				return 'background-color:#ffee00;color:red;';
			}},
			{field:"adminamt",   title:"Admin Amount",  sortable: true},
		]],
		onLoadSuccess:function(){
			authbutton();
		},
		onClickCell:function(index, field, value){
			if(field === "adminfee"){
				var row = $('#dg').datagrid('getRows')[index];
				inputReason("Edit Admin Fee","Input Admin Fee (%) : ", function (res) {
          if(!isNaN(res)){
						$.ajax({
							type:"POST",
							url:base_url+"showroom/editfee",
							dataType:"json",
							data:{
								id:row.id,
                adminfee:res
              },
							success:function(result){
								console.log(result.data)
								$('#dg').datagrid('reload');
							}
						});
          }
				})
			}
		}
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
				$('#dg').datagrid({url:base_url+"showroom/gridrekap?location_code="+location_code+"&tanggal="+prd});
				$('#dg').datagrid('destroyFilter');
				$('#dg').datagrid('enableFilter');
				$('#dg').datagrid('addFilterRule', {field: 'tanggal', op: 'equal', value: prd });
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
				$('#dg').datagrid({url:base_url+"showroom/gridrekap?location_code="+location_code+"&tanggal="+prd});
				$('#dg').datagrid('destroyFilter');
				$('#dg').datagrid('enableFilter');
				$('#dg').datagrid('addFilterRule', {field: 'tanggal', op: 'equal', value: prd });
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
		var location_code = $("#location_code").combobox('getValue')
		$('#dg').datagrid({url:base_url+"showroom/gridrekap?location_code="+location_code+"&tanggal="+prd});
		$('#dg').datagrid(options);
		$('#dg').datagrid('destroyFilter');
		$('#dg').datagrid('enableFilter');
		$('#dg').datagrid('addFilterRule', {field: 'tanggal', op: 'equal', value: prd });
		$('#dg').datagrid('addFilterRule', {field: 'location_code', op: 'equal', value: location_code });
		$('#dg').datagrid('doFilter');
	});

	function printRekap() {
		var date  = $("#periode").datebox('getDate');
		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		var prd =  y+"-"+(m<10?('0'+m):m)+"-"+(d<10?('0'+d):d);
		var location_code = $("#location_code").combobox('getValue')
		$.ajax({
			type:"POST",
			url:base_url+'showroom/print_rekap/',
			dataType:"json",
			data:{
				tanggal:prd,
        location_code:location_code
      },
			success:function(result){
				console.log(result.data)
			}
		});
	}
</script>