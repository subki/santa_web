<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/eom.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
	<?php echo $this->message->display();?>
    <form id="fm" method="post" novalidate style="margin:0;padding:10px 50px">
        <table style="width: 100%; margin:10px;">
            <tr style="width: 100%">
                <td style="margin:10px; width: 25%;">
                </td>
                <td style="margin:10px; width: 25%;">
                    <input name="periode" id="periode" class="easyui-datebox" style="width:100%;">
                    </input>
                </td>
                <td style="margin:10px; width: 25%;">
                </td>
                <td style="margin:10px; width: 25%;">
                </td>
            </tr>
            <tr style="width: 100%">
                <td style="margin:10px; width: 25%;">
                </td>
                <td style="margin:10px; width: 25%;">
                    <select name="from_location_code" id="from_location_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="From Location:" style="width:100%;">
                    </select>
                </td>
                <td style="margin:10px; width: 25%;">
                    <select name="to_location_code" id="to_location_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="To Location:" style="width:100%;">
                    </select>
                </td>
                <td style="margin:10px; width: 25%;">
                </td>
            </tr>
            <tr style="width: 100%">
                <td style="margin:10px; width: 25%;">
                </td>
                <td style="margin:10px; width: 25%;">
                    <select name="from_nobar" id="from_nobar" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="From SKU:" style="width:100%;">
                    </select>
                </td>
                <td style="margin:10px; width: 25%;">
                    <select name="to_nobar" id="to_nobar" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="To SKU:" style="width:100%;">
                    </select>
                </td>
                <td style="margin:10px; width: 25%;">
                </td>
            </tr>
        </table>
        <div id="dlg-buttons" style="text-align: center">
            <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
            <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
        </div>
    </form>
</div>