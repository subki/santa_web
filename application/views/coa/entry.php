<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
	var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
	var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
	var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
	var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
	var otoritas = "<?php echo $this->session->userdata('kode otoritas'); ?>";
	var aksi = "<?php echo $aksi; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
<style>
  .panel-titleq .panel-tool{
    height:50px;
    line-height: 50px;
  }
  }
  .textbox-readonly,
  .textbox-label-readonly {
    opacity: 0.6;
    filter: alpha(opacity=60);
  }
</style>
<div id="tt">
  <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <a href="<?php echo base_url('fa/Coa')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()" style="width:90px; height: 20px;">Submit</a>
  </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
	<?php echo $this->message->display();?>
  <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
       style="width:100%;height:100%;background:#fafafa;"
       data-options="iconCls:'icon-finance-ar',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
    <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div style="width: 20%; padding: 10px;">
        </div>
        <div style="width: 60%; padding: 10px;">
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="account_no" id="account_no" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Account#:" style="width:100%">
            </div>
            <div style="float:left; width: 30%; padding-right: 5px;">
              <select id="header_detail" name="header_detail" class="easyui-combobox" labelPosition="top" tipPosition="right" label="Header/Detail:" style="width:100%">
                <option value="HEADER">HEADER</option>
                <option value="DETAIL">DETAIL</option>
              </select>
            </div>
            <div style="float:left; width: 20%; padding-right: 5px;">
              <input name="level" id="level" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" label="Level:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <input name="acc_description" id="acc_description" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Account Name:" style="width:100%">
          </div>
          <div id="divParent" style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="parent" id="parent" labelPosition="top" tipPosition="bottom" label="Parent#:" style="width:100%">
              </select>
            </div>
            <div style="float:left; width: 50%; padding-right: 5px;">
            </div>
          </div>
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style="float:left; width: 30%; padding-right: 5px;">
                <select id="normal_balance" name="normal_balance" class="easyui-combobox" labelPosition="top" tipPosition="right" label="Normal Balance:" style="width:100%">
                  <option value="CREDIT">CREDIT</option>
                  <option value="DEBET">DEBET</option>
                </select>
            </div>
            <div style="float:right; width: 30%; padding-left: 5px;">
              <select id="account_type" name="account_type" class="easyui-combobox" labelPosition="top" tipPosition="right" label="Account Type:" style="width:100%">
                <option value="ASSETS">ASSETS</option>
                <option value="CAPITAL">CAPITAL</option>
                <option value="EXPENSE">EXPENSE</option>
                <option value="LIABILITIES">LIABILITIES</option>
                <option value="REVENUE">REVENUE</option>
                <option value="OTHERS">OTHERS</option>
              </select>
            </div>
            <div style="float:left; width: 30%; padding-right: 5px;">
              <select id="pro_beg_bal" name="pro_beg_bal" class="easyui-combobox" labelPosition="top" tipPosition="right" label="Begin Balance:" style="width:100%">
                <option value="YES">YES</option>
                <option value="NO">NO</option>
              </select>
            </div>
          </div>
        </div>
        <div style="width: 20%; padding: 10px;">
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
		populateCOA();
		$('#fm').form('load',<?php echo json_encode($item);?>);
		$("#header_detail").combobox({
      onSelect:function (row,index) {
        if(row.value==="DETAIL"){
        	$("#divParent").show()
        }else{
					$("#divParent").hide()
        }
			}
    })
	})
  function populateCOA() {
		$('#parent').combogrid({
			idField: 'account_no',
			textField:'acc_description',
			url:base_url+"fa/coa/grid",
			method:'post',
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
			clientPaging: false,
			loadFilter: function (data) {
				if (data.data) data.rows = data.data;
				return data;
			},
			onSelect:function (index, row) {
//				 if(row===null) return
				// console.log(row);
//				 $("#cbtype").combobox('setValue',row.tipe_rekening)
			},
			columns: [[
				{field:'account_no', title:'No Akun', width:200},
				{field:'acc_description', title:'Account Name', width:300},
			]]
		});
		var gr =  $('#parent').combogrid('grid')
		gr.datagrid('destroyFilter');
		gr.datagrid('enableFilter');
		gr.datagrid('addFilterRule', [
			{field: 'header_detail', op: 'equal', value: "HEADER"},
		]);
		gr.datagrid('doFilter');
	}
	function submitForm(){
  	var dh = $("#header_detail").combobox('getValue');
  	if(dh==="DETAIL"){
  		if($("#parent").combogrid('getValue') === "") {
  			$.messager.alert("Error", "Parent Account harus di pilih"); return;
      }
    }else{
			if($("#account_no").textbox('getValue')===""){
				$.messager.alert("Error", "Nomor Akun Harus diisi"); return;
			}
  	}
  	$.redirectForm("<?php echo base_url('fa/Coa/entryp/'.$aksi)?>","#fm","post","")
  }
</script>
