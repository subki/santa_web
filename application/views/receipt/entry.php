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
    <a href="<?php echo base_url('setupreceipt')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
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
          <div style="margin-bottom:1px">
              <input name="id" id="id" type="hidden" value="<?php echo isset($id)?$id:'0'; ?>">
            <select id="location_code" name="location_code" class="easyui-combobox" labelPosition="top" tipPosition="right"  label="Lokasi:" style="width:100%">
							<?php foreach ($location as $d) { ?>
                <option value="<?php echo $d->location_code ?>"><?php echo $d->description?></option>
							<?php } ?>
            </select>
          </div>
          <div style="margin-bottom:1px">
            <input name="headerf" id="headerf" class="easyui-textbox" data-options="{multiline:true}" labelPosition="top" tipPosition="bottom" label="Header:" style="width:100%; height:100px;">
          </div>
          <div style="margin-bottom:1px">
            <input name="footerf" id="footerf" class="easyui-textbox" data-options="{multiline:true}" labelPosition="top" tipPosition="bottom" label="Footer:" style="width:100%; height:100px;">
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
		$('#fm').form('load',<?php echo json_encode($item);?>);
	})
	function submitForm(){
  	$.redirectForm("<?php echo base_url('setupreceipt/entryp/'.$aksi)?>","#fm","post","")
  }
</script>
