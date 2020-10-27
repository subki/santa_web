<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/discount.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="tt_disc" title="Set Discount Periode" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:900px" data-options="closed:true,modal:true,border:'thin'">
        <div class="easyui-panel" style="width:100%;height:100%">
            <table id="dd" class="easyui-edatagrid" style="width:100%;height:400px"></table>
        </div>
    </div>
</div>
<div id="toolbar2" style="padding:2px;">
    <form id="fromcopy" style="margin-bottom:-0px;">
        <input class="easyui-combogrid" id="combo" name="combo" label="Select Trx:" style="width:30%">
<!--        <input class="easyui-combogrid" id="combo2" name="combo2[]" label="Select Article:" style="width:30%">-->
        <a href="#" onclick="submitCopy()" class="easyui-linkbutton" iconCls="icon-save" plain="true">Submit</a>
        <a href="#" onclick="cancelUpload()" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Cancel</a>
    </form>
</div>