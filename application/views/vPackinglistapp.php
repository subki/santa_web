<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
		var max_transaksi = "<?php echo $this->session->userdata('maksimal transaksi'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/pl_approval.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
	<?php echo $this->message->display();?>
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
</div>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="viewData()" iconCls="icon-eye" plain="true">View</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="postingPL()" iconCls="icon-ok" plain="true">Posting</a>
</div>