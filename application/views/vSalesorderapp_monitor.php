<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var title="<?php echo $title;?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/so_monitor.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
</div>
<div id="toolbar">
    <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-close" onclick="submit()" style="width:120px; height: 24ch;">Cancel/Expired</a>
    <a href="javascript:void(0)" id="customer" class="easyui-linkbutton" iconCls="icon-customer" onclick="showCustomer()" style="width:90px; height: 20px;">Customer</a>
</div>