<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var golongan = "<?php echo $golongan; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/customer.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" fit="true" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div id="dlg2" class="easyui-dialog" style="width:1020px; height: 500px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:10px 50px">
            <div data-options="region:'west'" style="width:100%;padding:10px">
                <table style="width:100%;height:100%">
                    <tr>
                    <td style="width: 30%; vertical-align: top; padding-left: 5px; padding-right: 5px">
                        <table>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="customer_code" id="customer_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="head_customer_id" id="head_customer_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Head Customer:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="parent_cust" id="parent_cust" class="easyui-combobox" labelPosition="top" tipPosition="bottom" label="Parent:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="customer_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Nama:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       data-options="multiline:true"
                                       style="width:100%;height:100px" name="address1" required="true" label="Alamat1:">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       data-options="multiline:true"
                                       style="width:100%;height:100px" name="address2" label="Alamat2:">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="provinsi_id" id="provinsi_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Provinsi:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="regency_id" id="regency_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Kota/Kabupaten:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="zip" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="ZIP Code:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="fax" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Fax:" style="width:100%">
                            </tr>
                        </table>
                    </td>
                    <td style="width: 30%; vertical-align: top; padding-left: 5px; padding-right: 5px">
                        <table>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="contact_person" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Contact Person:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="phone1" class="easyui-textbox" validType="isNumberOnly" labelPosition="top" tipPosition="bottom" required="true" label="Phone1:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="phone2" class="easyui-textbox" validType="isNumberOnly" labelPosition="top" tipPosition="bottom" label="Phone2:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="phone3" class="easyui-textbox" validType="isNumberOnly" labelPosition="top" tipPosition="bottom" label="Phone3:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="salesman_id" id="salesman_id" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Salesman:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <select name="status" id="status" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Status:" style="width:100%;">
                                    <option value="">-Please Select-</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Non-Aktif">Non-Aktif</option>
                                    <option value="Block">Block</option>
                                </select>
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="info_status" id="info_status" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Status Reason:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="info_cust" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       data-options="multiline:true"
                                       style="width:100%;height:100px" label="Keterangan:">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="top_day" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" <?php if($golongan=="Wholesales" || $golongan=="Customer Online") echo "required='true'"; ?> label="Term of Payment:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <select name="pkp" id="pkp" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="PKP:" style="width:100%;">
                                </select>
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px;">
                                <div id="display_beda_fp" style="display: none">
                                    <select name="beda_fp" id="beda_fp" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Checklist Tgl FP:" style="width:100%;">
                                    </select>
                                </div>
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="npwp" id="npwp" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="NPWP:" style="width:100%">
                            </tr>

                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="margin_persen" id="margin_persen" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2, formatter:formatnumberbox" label="Margin %:" style="width:100%">
                            </tr>
                        </table>
                    </td>
                    <td style="width: 30%; vertical-align: top; padding-left: 5px; padding-right: 5px">
                        <table>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="nama_pkp" id="nama_pkp" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Nama PKP:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="alamat_pkp" id="alamat_pkp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       data-options="multiline:true"
                                       style="width:100%;height:70px" label="Alamat PKP:">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="customer_type" id="customer_type" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Customer Type:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <select name="gol_customer" id="gol_customer" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Golongan Customer:" style="width:100%;">
                                    <option value="">- Please select -</option>
                                    <option value="Wholesales">Wholesales</option>
                                    <option value="Counter">Counter</option>
                                    <option value="Showroom">Showroom</option>
                                    <option value="Customer Online">Customer Online</option>
                                </select>
                            </tr>

                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <select name="payment_first" id="payment_first" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Bayar dimuka:" style="width:100%;">
                                </select>
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="lokasi_stock" id="lokasi_stock" class="easyui-combobox" labelPosition="top" tipPosition="bottom" label="Lokasi Stok:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="kode_lokasi" id="kode_lokasi" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="New Location Code:" style="width:100%">
                            </tr>
<!--                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">-->
<!--                                <input name="kode_store" id="kode_store" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="New Store Code:" style="width:100%">-->
<!--                            </tr>-->
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="check_stock" id="check_stock" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Check Stok:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="credit_limit" id="credit_limit" class="easyui-numberbox" labelPosition="top" tipPosition="bottom"
                                       data-options="precision:4, formatter:formatnumberbox, inputEvents:$.extend({},$.fn.numberbox.defaults.inputEvents,{keyup:changeLimit})" required="true" label="Credit Limit:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="outstanding" id="outstanding" class="easyui-numberbox" labelPosition="top" tipPosition="bottom"
                                       data-options="precision:4, formatter:formatnumberbox, inputEvents:$.extend({},$.fn.numberbox.defaults.inputEvents,{keyup:changeOutstanding})" label="Outstanding:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="credit_remain" id="credit_remain" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:4, formatter:formatnumberbox" label="Sisa Limit:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="gl_account" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="GL Account:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <input name="cust_fk" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Customer Faktur:" style="width:100%">
                            </tr>
                            <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                <select name="customer_class" id="customer_class" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Customer Class:" style="width:100%;">
                                </select>
                            </tr>
                        </table>
                    </td>
                    </tr>
                </table>
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:800px" data-options="closed:true,modal:true,border:'thin'">
        <table id="tt" class="easyui-edatagrid" style="width:100%;height: 300px"
               toolbar="#toolbar22" idField="id"
               rownumbers="true" fitColumns="true" singleSelect="true">
        </table>
        <div id="toolbar22">
            <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#tt').edatagrid('addRow',0)">Add</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#tt').edatagrid('destroyRow')">Delete</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#tt').edatagrid('saveRow')">Submit</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#tt').edatagrid('cancelRow')">Cancel</a>
            <a href="#" id="history" class="easyui-linkbutton" iconCls="icon-info" plain="true" onclick="openHistory();">History Promo</a>
            <a href="#" id="kopi" class="easyui-linkbutton" iconCls="icon-copy" plain="true" onclick="openCopy();">Copy</a>
        </div>
    </div>
    <div id="dlg3" class="easyui-dialog" style="width:800px" data-options="closed:true,modal:true,border:'thin'">
        <table id="tt2" class="easyui-datagrid" style="width:100%;height: 300px">
        </table>
    </div>

    <div id="toolbar23" style="padding:2px;">
        <form id="fromcopy" style="margin-bottom:-0px;">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                <input class="easyui-combogrid" id="combo" name="combo"
                       label="Customer:" label-position="top" style="width:10%">
                <a href="#" onclick="submitCopy()" class="easyui-linkbutton" iconCls="icon-save" plain="true">Submit</a>
                <a href="#" onclick="cancelUpload()" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Cancel</a>
            </div>
        </form>
    </div>
</div>