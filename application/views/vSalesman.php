<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/salesman.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="tt" title="Master Salesman" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:900px" data-options="closed:true,modal:true,border:'thin'">
        <div class="easyui-panel" style="width:100%;height:100%">
            <table id="dd" class="easyui-datagrid" style="width:100%;height:400px"></table>
        </div>
    </div>
</div>