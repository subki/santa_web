<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/class.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:50%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div data-options="region:'east'" style="width:50%;">
        <table id="tt" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
</div>

<div id="dlg" class="easyui-dialog" style="width:350px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
    <form id="fm" method="post" novalidate style="margin:20px;padding:20px">
        <h3>Product Group</h3>
        <div style="margin-bottom:10px">
            <input name="class_code" id="class_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="description" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Name:" style="width:30%">
        </div>
        <div style="margin-bottom:10px; display: none;">
            <input name="addcost" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="false" label="Add. Cost:" style="width:30%">
        </div>
        <div style="margin-bottom:10px; display: none;">
            <select name="jenis_barang" id="jenis_barang" class="easyui-combobox" labelPosition="top" tipPosition="bottom" label="Jenis Barang:" style="width:30%;">
            </select>
        </div>
    </form>
</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<div id="dlg2" class="easyui-dialog" style="width:350px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons2'">
    <form id="fm2" method="post" novalidate style="margin:20px;padding:20px">
        <h3>Product Sub Class</h3>
        <div style="margin-bottom:10px">
            <input name="class_code" id="class_code2" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Class:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="subclass_code" id="subclass_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Subclass:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="description" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Name:" style="width:100%">
        </div>
    </form>
</div>
<div id="dlg-buttons2">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit2()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg2').dialog('close')" style="width:90px">Cancel</a>
</div>