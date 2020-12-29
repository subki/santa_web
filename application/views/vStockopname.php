<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
	var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sp_grid.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
  <div id="p" data-options="region:'west'" style="width:100%;">
    <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
    </table>
  </div>
</div>
<!--<div id="toolbar">-->
<!--    <a href="--><?php //echo base_url(); ?><!--Salesorder/form/add" class="easyui-linkbutton" iconCls="icon-add" plain="true">New</a>-->
<!--    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="editData()" iconCls="icon-edit" plain="true">Edit</a>-->
<!--</div>-->
<?php echo $this->message->display();?>
<div id="wprint" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:80%;height:300px;padding:10px;">
  <form action="" name="frm2" class="frm2" method="POST">
    <div style="margin-bottom:20px">
      <input id="opn_no"  class="easyui-textbox" label="No. Merge Opname/Ref.No:" data-options="labelPosition:'top',onSelect:onSelect"  labelPosition="top" style="width:50%;">
    </div>
    <div style="margin-bottom:20px">
      <a href="javascript:void(0)" id="searchdate"  class="easyui-linkbutton printOP" iconCls="icon-print"  style="width:90px; height: 20px;">Print</a>
      <a href="javascript:void(0)" id="searchdate"  class="easyui-linkbutton printOPexcel" iconCls="icon-print"  style="width:150px; height: 20px;">Print Excel</a>
    </div>
  </form>
</div>
<div id="w" class="easyui-dialog" data-options="iconCls:'icon-save'
,closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'"
     style="width: 50%;padding:10px;">
  <form action="" name="frm2" class="frm2" method="POST">
    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: center;">
      <div style="margin-bottom:20px">
        <div style="float:left; width: 40%; padding-right: 5px;">
          <input id="dd"  class="easyui-datebox" label="Search Date:" data-options="labelPosition:'top',onSelect:onSelect"  labelPosition="top" style="width:100%;">
        </div>
        <div style="float:left; width: 40%; padding-right: 5px;">
          <input id="tdd" class="easyui-datebox" label="End Date:" labelPosition="top" style="width:100%;">
        </div>
        <div style="float:right; width: 20%; margin-top: 20px;">
          <a href="javascript:void(0)" id="searchdate" class="easyui-linkbutton save" iconCls="icon-search"  style="width:90px; height: 20px;">Search</a>
        </div>
      </div>
    </div>
    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: center;">
      <div style="margin-bottom:5px">
        <span id="l"></span> <br>
        <a href="javascript:void(0)" id="postdetail" class="easyui-linkbutton postdetail" iconCls="icon-posting" style="width:290px; height: 20px;">Merge Opname</a>
      </div>
    </div>
      <div style="margin-bottom:5px">
        <table id="merge" title="Merge Opname" class="easyui-datagrid" style="width:100%;">
        </table>
    </div>
  </form>
</div>
<script type="text/javascript">
  var boolLoad = false;
//	function printOPN(){
//		$('#wprint').dialog('open').dialog('center').dialog('setTitle','Merge Opname');
//	}
	function Opendialog(){
		$('#w').dialog('open').dialog('center').dialog('setTitle','Merge Opname');
	}
	function onSelect(date){

		var y = date.getFullYear();
		var m = date.getMonth()+1;
		var d = date.getDate();
		var fulldate=y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
		getdatefrom(fulldate);
	}
	function getdatefrom(fulldate){

		//console.log(fulldate);
		$('#tdd').datebox().datebox('calendar').calendar({
			validator: function(date){

				$("#l").text("");
				$("#postdetail").hide();
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				var fulldateto=y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
				return fulldateto>=fulldate;
			}
		});
	}
	$("#postdetail").hide();
	$(".save").click(function(){
		var fromdate = $('#dd').datebox('getValue');
		var todate = $('#tdd').datebox('getValue');
		$.ajax({
			type: 'POST',
			dataType:"json",
			url:base_url+"Stockopname/getDate",
			data: {
				store_code:store_code,
				location_code:location_code,
				from:fromdate,
				to:todate
			},
			success: function(result) {
				console.log(result);
				$("#l").text(result.total+" Data Dok. Opname ");
				if(result.total >= 1){
					$("#postdetail").show();
					if(boolLoad){
						$("#merge").datagrid("loadData",result.data)
          }else {
						$("#merge").datagrid({
              width:"100%",
							pagePosition: "top",
							resizeHandle: "right",
							resizeEdge: 10,
							pageSize: 20,
							clientPaging: true,
							remoteFilter: false,
							rownumbers: false,
							pagination: false, striped: true, nowrap: false,
							singleSelect: false,
							multiple:true,
							columns: [[
								{field:'', checkbox:true, sortable: true},
								{field: "trx_no", title: "No. Trx", sortable: true},
								{field: "gondola", title: "Hal. Gondola", sortable: true},
								{field: "trx_date", title: "Tanggal", sortable: true},
								{field: "useropname", title: "Opname By", sortable: true},
								{field: "remark", title: "Keterangan", sortable: true},
							]]
						})
            boolLoad = true;
						$("#merge").datagrid("loadData",result.data)
					}
				} else{
					$("#postdetail").hide();
				}
			}
		});
	});
	$(".postdetail").click(function(){
		var data = $("#merge").datagrid('getSelections');
		var fromdate = $('#dd').datebox('getValue');
		var todate = $('#tdd').datebox('getValue');
		if(data.length==0) $.messager.alert("Error","Please select data Gondola")
		var val = {};
		val['store_code']=store_code;
		val['location_code']=location_code;
		val['from']=fromdate;
		val['to']=todate;
		var det = {};
		for(var i=0; i<data.length; i++){
			det[i]=data[i].trx_no;
    }
		val['selections']=det;
		$.redirect(base_url+"Stockopname/postdaily",val,"POST","");
	});
	$(".printOP").click(function(){
		var docno = $('#opn_no').textbox('getValue');
		window.open(base_url+'Stockopname/print_opfull/'+docno, '_blank');
	});
	$(".printOPexcel").click(function(){
		var docno = $('#opn_no').textbox('getValue');
		window.open(base_url+'Stockopname/print_opfullexcel/'+docno, '_blank');
	});
</script>