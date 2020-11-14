<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var aksi = "<?php echo $aksi; ?>";
    var docno = "<?php echo $docno; ?>";
 
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/salesonline_form.js"></script>
<style>
    .panel-titleq .panel-tool{
        height:50px;
        line-height: 50px;
    }
</style>
<div id="tt">
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Save</a>
        <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-posting" onclick="submit('POSTING')" style="width:90px; height: 20px;">Posting</a>
        <a href="javascript:void(0)" id="close" class="easyui-linkbutton" iconCls="icon-close" onclick="submit('CLOSE')" style="width:90px; height: 20px;">Close</a>
        <a href="javascript:void(0)" id="print" class="easyui-linkbutton" iconCls="icon-print" onclick="printSO()" style="width:90px; height: 20px;">Print</a>
        <a href="javascript:void(0)" id="customer" class="easyui-linkbutton" iconCls="icon-customer" onclick="showCustomer()" style="width:90px; height: 20px;">Customer</a>
    </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
         style="width:100%;height:100%;background:#fafafa;"
         data-options="iconCls:'icon-packing',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                <div style="width: 50%; padding: 10px;">
                    <div style="margin-bottom:1px">
                        <div style="float:left; width: 65%; padding-right: 5px;">
                            <input name="docno" id="docno" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Trx. No:" style="width:100%">
                        </div>
                        <div style="float:left; width: 65%; padding-right: 5px;">
                            <input name="so_no" id="so_no" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="SO Number:" style="width:100%">
                        </div>
                        <div style="float:left; width: 35%; padding-right: 5px; display:none">
                            <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label="" style="width:100%;">
                        </div>
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="status2" id="status2" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label="Status:" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="ak_tgl_so" id="ak_tgl_so" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                               required="true" readonly="true" label="Trx. Date:" style="width:100%">
                    </div> 
                    <div style="margin-bottom:1px">
                        <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               required="true" label="Keterangan:" style="width:100%; height: 100px;">
                    </div>
                </div>
                <div style="width: 100%; padding: 10px;">
					<!-- <div style="margin-bottom:1px">
                        <input name="ak_tgl_so" id="ak_tgl_so" class="easyui-datebox" labelPosition="top" tipPosition="bottom" readonly="true" label="Tanggal SO:" style="width:100%;">
                    </div> -->
                    <div style="margin-bottom:1px; display: none">
                        <input name="reason" id="reason" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Reason:" style="width:100%">
                    </div>
                    <div style="width: 100%; padding: 10px;" class="border-kotak">
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="customer_code" id="customer_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Customer Code:" style="width:100%">
                        </div>
                        <div style="float:left; width: 25%; padding-right: 5px;">
                            <input name="customer_name" id="customer_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Customer Name:" style="width:100%">
                        </div>

                       <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                            <div style=" padding-right: 10px; width: 25%">
                                <input name="qty_item" id="qty_item" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       readonly="true" label="#IT:" style="width:100%">
                            </div>
                            <div style=" padding-right: 10px; width: 25%">
                                <input name="qty" id="qty" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       readonly="true" label="Qty:" style="width:100%">
                            </div>
                            <div style=" padding-right: 10px; width: 25%">
                                <input name="disc1_persen" id="disc1_persen" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       readonly="true" label="Disc%1:" style="width:100%">
                            </div>
                            <div style=" padding-right: 10px; width: 25%">
                                <input name="disc2_persen" id="disc2_persen" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       readonly="true" label="Disc%2:" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div style="width: 38%; padding: 10px;" class="border-kotak">

                        <div style="margin-bottom:1px">
                            <input name="gross_sales" id="gross_sales" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Bruto:" style="width:100%; text-align: right;">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="total_discount" id="total_discount" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Discount:" style="width:100%; text-align: right;">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="sales_before_tax" id="sales_before_tax" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Subtotal:" style="width:100%; text-align: right;">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="total_ppn" id="total_ppn" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Total PPN:" style="width:100%; text-align: right;">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="sales_after_tax" id="sales_after_tax" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Netto:" style="width:100%; text-align: right;">
                        </div>
                    </div>
                </div>
                <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
            </div>
            <div data-options="region:'west'" style="width:100%;">
                <table id="dg" class="easyui-edatagrid" style="width:100%;height: 300px">
                </table>
            </div>
        </form>
    </div>
</div>
