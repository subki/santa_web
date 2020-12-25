<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var title="<?php echo $title;?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var max_transaksi = "<?php echo $auto['maksimal transaksi']; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ws_approval.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
</div>
<div id="toolbar">
    <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-posting" onclick="submit('ON ORDER')" style="width:90px; height: 20px;">Posting</a>
<!--    <a href="javascript:void(0)" id="customer" class="easyui-linkbutton" iconCls="icon-customer" onclick="showCustomer()" style="width:90px; height: 20px;">Customer</a>-->
</div>