<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/location.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
<!--    <div class="easyui-tabs" style="width:100%;height:100%;">-->
<!--        <div title="Location" style="padding:10px;display:none;">-->
            <div class="easyui-layout" style="width:100%;height:100%">
                <div id="p" data-options="region:'west'" style="width:70%;">
                    <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
                    </table>
                </div>
                <div data-options="region:'east'" style="width:30%;">
                    <div id="tb" class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">
                        <table id="dg2" title="Periode" class="easyui-datagrid" style="width:100%;height:100%">
                        </table>
                    </div>
                </div>
            </div>
            <div title="Monitoring" style="padding:10px;display:none;">
                <table id="mm" class="easyui-edatagrid" style="width:100%;height:100%">
                </table>
            </div>
            <div id="dlg" class="easyui-dialog" style="width:620px; height: 300px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
                <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
                    <div data-options="region:'west'" style="width:100%;padding:10px">
                        <table style="width:100%;height:100%">
                            <tr>
                                <td style="margin-bottom:10px; width: 45%; padding: 5px">
                                    <input name="location_code" id="location_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode:" style="width:100%">
                                </td>
                                <td style="margin-bottom:10px; width: 45%; padding: 5px">
                                    <input name="description" id="description" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Location Name:" style="width:100%">
                                </td>
                            </tr>
                            <tr>
                                <td style="margin-bottom:10px; width: 45%; padding: 5px">
                                    <select name="pkp" id="pkp" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="PKP :" style="width:100%;">
                                    </select>
                                </td>
                                <td style="margin-bottom:10px; width: 45%; padding: 5px">
                                    <select name="price_type" id="price_type" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Price Type :" style="width:100%;">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="margin-bottom:10px; width: 45%; padding: 5px">
                                    <select name="check_stock" id="check_stock" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Check Stock :" style="width:100%;">
                                    </select>
                                </td>
                            </tr>
                        </table>
                    <div id="dlg-buttons" style="float: right">
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
                    </div>
                </form>
            </div>
<!--        </div>-->
<!--    </div>-->

</div>