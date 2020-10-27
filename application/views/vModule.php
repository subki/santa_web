<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/module.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:70%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div data-options="region:'east'" style="width:30%;">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <h3>Module</h3>
            <div style="margin-bottom:10px">
                <input name="app_id" id="app_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="App ID:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="app_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="App Name:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="url" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Url:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="icon" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Icon:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="seq" id="seq" type="number" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Seq.:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <select name="parent_id" id="parent_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Parent :" style="width:100%;">
                    <?php foreach ($apps as $row) { ?>
                        <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"><?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
</div>