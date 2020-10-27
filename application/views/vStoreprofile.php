<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/store_profile.js"></script>
<script src="<?php echo base_url(); ?>assets/js/cabang.js"></script>
<table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
</table>


<div id="dlg" class="easyui-dialog" style="width:800px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
<!--    <div class="easyui-layout" style="width:100%;height:400px">-->
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <div id="p" data-options="region:'center'" style="width:100%; padding: 20px 20px">
                <table width="100%">
                    <tr style="vertical-align: top">
                        <td style="width: 50%; padding: 5px 5px">
                            <table width="90%">
                                <tr style="margin-bottom:10px">
                                    <input name="store_code" id="store_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Store Code:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px">
                                    <input name="store_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Nama Store:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px">
                                    <input name="store_address" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Alamat:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px">
                                    <input name="provinsi_id" id="provinsi_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Provinsi:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px">
                                    <input name="regency_id" id="regency_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Kota/Kab:" style="width:100%">
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%; padding: 5px 5px">
                            <table width="90%">
                                <tr style="margin-bottom:10px">
                                    <input name="zip" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="ZIP Code:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px">
                                    <input name="phone" class="easyui-textbox" validType="isNumberOnly" labelPosition="top" tipPosition="bottom" required="true" label="Phone:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px">
                                    <input name="fax" class="easyui-textbox" validType="isNumberOnly" labelPosition="top" tipPosition="bottom" required="true" label="Fax:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px">
                                    <input name="email_address" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" validType="email" label="Email:" style="width:100%">
                                </tr>
<!--                                <tr style="margin-bottom:10px">-->
<!--                                    <input name="register_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Register Name:" style="width:100%">-->
<!--                                </tr>-->
<!--                                <tr style="margin-bottom:10px">-->
<!--                                    <input name="register_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom" required="true" label="Register Date:" style="width:100%">-->
<!--                                </tr>-->
                                <tr style="margin-bottom:10px">
                                    <input name="default_stock_l" id="default_stock_l" labelPosition="top" tipPosition="bottom" required="true" label="Default Stock Location:" style="width:100%">
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
<!--    </div>-->

</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
    <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<div id="dlg_cabang" class="easyui-dialog" style="width:80%" data-options="closed:true,modal:true,border:'thin'">
    <div class="easyui-layout" style="width:100%;height:450px">
    <?php require_once ('vCabang.php')?>
    </div>
</div>
<!--<div id="dlg2-buttons">-->
<!--    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit2()" style="width:90px">Save</a>-->
<!--    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_cabang').dialog('close')" style="width:90px">Cancel</a>-->
<!--</div>-->