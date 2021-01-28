<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<style type="text/css">
  .textbox-editable true{
    background: blue;
  }
  .textbox-editable false{
    background: yellow;
  }
</style>
<?php
$sesi = $this->session->userdata('auto_config');
?>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
  <div class="easyui-layout" fit="true" style="width:100%;height:100%;">
    <div data-options="region:'west'" style="width:100%;padding:0px">
      <div class="easyui-panel" title="Configuration Static">
        <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
          <div style="width: 100%; padding: 10px;">
            <?php foreach ($sesi as $row) { ?>
              <div style="margin-bottom:1px">
                <input name="<?php echo $row->id?>" id="<?php echo $row->id?>" class="easyui-textbox" multiline="true" labelPosition="top" tipPosition="bottom"
                       label="<?php echo ucwords($row->kunci)?> :" style="width:100%" value="<?php echo $row->nilai;?>">
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		<?php foreach ($sesi as $row) {?>
      init('<?php echo $row->id ?>');
    <?php } ?>
	});
	function init(id) {
		$(`#${id}`).textbox({icons:[{iconCls:'icon-edit', handler: function () {editData(id)}}]});
		$(`#${id}`).textbox({editable:false});
	}
	function editData(id) {
		$(`#${id}`).textbox({icons:[{iconCls:'icon-ok', handler: function () {
			saveData(id)
		}}]});
		$(`#${id}`).textbox({editable:true});
	}
	function saveData(id) {
		$.ajax({
			type:"POST",
			url:base_url+"Autoconfig/edit_data",
			data:{
				id:id,
				nilai:$(`#${id}`).textbox('getValue')
			},
			dataType:"json",
			success:function(result){
				console.log(result.data)
				if(result.status===0) {
					$(`#${id}`).textbox({icons:[{iconCls:'icon-edit', handler: function () {
						editData(id)
					}}]});
					$(`#${id}`).textbox({editable:false});
				}
				else {
					$.messager.show({
						title: 'Error',
						msg: e.message,
						handler:function () {
							window.location.href = base_url+"merchant";
						}
					});
				}

			}
		});
	}
</script>
