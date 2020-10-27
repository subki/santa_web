<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/auto.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:70%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div data-options="region:'east'" style="width:30%;">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <h3>Auto Config</h3>
            <div style="margin-bottom:10px">
                <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="ID:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="kunci" id="kunci" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Key:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="nilai" id="nilai" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Value:" style="width:100%">
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
</div>