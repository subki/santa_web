<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/uom_convertion.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:70%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div data-options="region:'east'" style="width:30%;">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <h3>UOM Convertion</h3>
            <div style="margin-bottom:10px">
                <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="ID:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <select name="uom_from" id="uom_from" class="easyui-combobox"
				labelPosition="top" tipPosition="bottom" required="true"
				label="UOM From :" style="width:100%;"
				prompt="-Please Select-", validType="inList['#uom_from']">
                    <?php foreach ($uom as $row) { ?>
                        <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"><?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div style="margin-bottom:10px">
                <select name="uom_to" id="uom_to" class="easyui-combobox" 
				labelPosition="top" tipPosition="bottom" 
				required="true" label="UOM To :" style="width:100%;"
				prompt="-Please Select-", validType="inList['#uom_to']">
                    <?php foreach ($uom as $row) { ?>
                        <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"><?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div style="margin-bottom:10px">
                <input name="convertion" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Nilai Konversi:" style="width:100%">
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
</div>