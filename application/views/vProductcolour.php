<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/colour.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:450px; height: 300px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px">
            <div style="margin-bottom:10px">
                <input name="colour_code" id="colour_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="description" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Name:" style="width:100%">
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
</div>