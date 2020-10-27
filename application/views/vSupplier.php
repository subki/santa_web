<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/supplier.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" fit="true" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
<!--    <div data-options="region:'east'" style="width:30%;">-->
<!--        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">-->
<!--            <h3>Supplier</h3>-->
<!--            -->
<!--            <div id="dlg-buttons" style="float: right; margin: 10px">-->
<!--                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>-->
<!--                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>-->
<!--            </div>-->
<!--        </form>-->
<!--    </div>-->
    <div id="dlg2" class="easyui-dialog" style="width:99%; height: 95%" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" fit="true" novalidate style="margin:0;padding:5px 5px">
            <div data-options="region:'west'" style="width:100%;padding:10px">
                <table style="width:100%;height:100%">
                    <tr>
                        <td style="margin-bottom:10px; width: 30%; padding: 5px">
                            <input name="supplier_code" id="supplier_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" labelPosition="top" tipPosition="bottom" required="true" label="Kode Supplier:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px; width: 30%; padding: 5px">
                            <input name="provinsi_id" id="provinsi_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Provinsi:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px; width: 30%; padding: 5px">
                            <select name="pkp" id="pkp" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="PKP:" style="width:100%;">
                                <option value="">- Please select -</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="margin-bottom:10px">
                            <select name="tipe_supplier" id="tipe_supplier" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Tipe Supplier:" style="width:100%;">
                            </select>
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="regency_id" id="regency_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Kota/Kabupaten:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="npwp" id="npwp" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="NPWP:" style="width:100%">
                        </td>
                    </tr>
                    <tr>
                        <td style="margin-bottom:10px">
                            <input name="supplier_name" id="supplier_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Nama Supplier:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="zip" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="ZIP Code:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="nama_pkp" id="nama_pkp" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Nama PKP:" style="width:100%">
                        </td>
                    </tr>
                    <tr>
                        <td style="margin-bottom:10px">
                            <input name="contact_person" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Contact Person:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="fax" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Fax:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="alamat_pkp" id="alamat_pkp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   data-options="multiline:true"
                                   style="width:100%;height:100px" label="Alamat PKP:">
                        </td>
                    </tr>
                    <tr>
                        <td style="margin-bottom:10px">
                            <input name="phone" class="easyui-textbox" validType="isNumberOnly" labelPosition="top" tipPosition="bottom" required="true" label="Phone:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="email_address" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Email:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="bank_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Nama Bank:" style="width:100%">
                        </td>
                    </tr>
                    <tr>
                        <td style="margin-bottom:10px">
                            <select name="allow_return" id="allow_return" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Allow Retur:" style="width:100%;">
                                <option value="">- Please select -</option>
                                <option value="YES">YES</option>
                                <option value="NO">NO</option>
                            </select>
                        </td>
                        <td style="margin-bottom:10px">
                            <select name="status" id="status" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Status:" style="width:100%;">
                                <option value="">-Please Select-</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="bank_account" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="No. Rekening:" style="width:100%">
                        </td>
                    </tr>
                    <tr>
                        <td style="margin-bottom:10px">
                            <input class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                            data-options="multiline:true"
                            style="width:100%;height:100px" name="address" required="true" label="Alamat:">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="top_day" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" required="true" label="TOP:" style="width:100%">
                        </td>
                        <td style="margin-bottom:10px">
                            <input name="gl_account" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="GL Account:" style="width:100%">
                        </td>
                    </tr>
                </table>
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit2" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel2" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:800px" data-options="closed:true,modal:true,border:'thin'">
        <table id="tt" class="easyui-edatagrid" style="width:100%;height: 300px"
               toolbar="#toolbar22" >
        </table>
        <div id="toolbar22">
            <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#tt').edatagrid('addRow',0)">Add</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#tt').edatagrid('destroyRow')">Del</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#tt').edatagrid('saveRow')">Submit</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#tt').edatagrid('cancelRow')">Cancel</a>
        </div>
    </div>
</div>