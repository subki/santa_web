<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
    var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
    var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
    var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
    var max_transaksi = "<?php echo $auto['maksimal transaksi']; ?>";
    var aksi = "<?php echo $aksi; ?>";
    var docno = "<?php echo $docno; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ws_view.js"></script>
<style>
    .panel-titleq .panel-tool{
        height:50px;
        line-height: 50px;
    }
    }
    .textbox-readonly,
    .textbox-label-readonly {
        opacity: 0.6;
        filter: alpha(opacity=60);
    }
</style>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
         style="width:100%;height:100%;background:#fafafa;"
         data-options="iconCls:'icon-sales',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                <div style="width: 40%; padding: 10px;">
                    <div style="margin-bottom:1px">
                        <div id="dis_faktur1" style="float:left; width: 55%; padding-right: 5px;">
                            <input name="no_faktur" id="no_faktur" class="easyui-textbox khusus" labelPosition="top" tipPosition="bottom"
                                   label="Trx. No:" style="width:100%">
                        </div>
                        <div id="dis_faktur2" style="float:left; width: 55%; padding-right: 5px;">
                            <input name="no_faktur2" id="no_faktur2" class="easyui-textbox khusus" labelPosition="top" tipPosition="bottom"
                                   label="Trx. No1:" style="width:100%">
                        </div>
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label="Status:" style="width:100%">
                        </div>
                        <div style="float:right; width:10%;">
                            <input name="qty_print" id="qty_print" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label=" " style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px; display: none">
                        <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="ID:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px; display: none">
                        <input name="reason" id="reason" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Reason:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px; display:none">
                        <input name="seri_pajak" id="seri_pajak" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Seri Pajak:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px; display:block" id="vseri">
                        <input name="seri_pajak_formatted" id="seri_pajak_formatted" readonly="true" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Seri Pajak:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="doc_date" id="doc_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                                   required="true" label="Trx. Date:" style="width:100%">
                        </div>
                        <div style="float:right; width: 50%; padding-right: 5px;">
                            <input name="faktur_date" id="faktur_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                                   required="true" label="Faktur Date:" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px; display:none">
                        <div style="margin-bottom:1px; display: none">
                            <input name="regency_id" id="regency_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="" style="width:100%">
                        </div>
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="regency_name" id="regency_name" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Kabupaten:" style="width:100%">
                        </div>
                        <div style="margin-bottom:1px; display: none">
                            <input name="provinsi_id" id="provinsi_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="" style="width:100%">
                        </div>
                        <div style="float:right; width: 50%; padding-left: 5px;">
                            <input name="provinsi_name" id="provinsi_name" class="easyui-combogrid" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Provinsi:"style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="base_so" id="base_so" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Packing List Number:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input readonly="true" name="so_number" id="so_number" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="SO Number:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <div style="float:left; width: 85%; padding-right: 5px;">
                            <input name="customer_code" readonly="true" id="customer_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Customer:" style="width:100%">
                        </div>
                        <div style="float:right; width: 15%; padding-right: 5px;">
                            <input name="beda_fp" id="beda_fp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="Beda FP" readonly="true" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px">
                        <div style="float:left; width: 85%; padding-right: 5px;">
                            <input name="customer_name" id="customer_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" disabled="true" label="" style="width:100%">
                        </div>
                        <div style="float:right; width: 15%; padding-right: 5px;">
                            <input name="pkp" id="pkp" readonly="true" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px;">
                        <input readonly="true" name="salesman_id" id="salesman_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Salesman:" style="width:100%">
                    </div>
                </div>
                <div style="width: 40%; padding: 10px;">
                    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                        <div style=" padding-right: 10px; width: 30%">
                            <input readonly="true" name="disc1_persen" id="disc1_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 1:" style="width:100%">
                        </div>
                        <div style="width: 30%">
                            <input readonly="true" name="disc2_persen" id="disc2_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" label="Disc 2:" style="width:100%">
                        </div>
                        <div style=" padding-right: 10px; width: 30%">
                            <input readonly="true" name="disc3_persen" id="disc3_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 3:" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                        <div style=" padding-right: 10px; width: 50%">
                            <input name="qty_item" id="qty_item" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="#ITEM:" style="width:100%">
                        </div>
                        <div style=" padding-right: 10px; width: 50%">
                            <input name="qty_order" id="qty_order" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="QTY SALES:" style="width:100%">
                        </div>
<!--                        <div style=" padding-right: 10px; width: 25%">-->
<!--                            <input name="qty_deliver" id="qty_deliver" class="easyui-textbox" labelPosition="top" tipPosition="bottom"-->
<!--                                   readonly="true" label="QTY DO:" style="width:100%">-->
<!--                        </div>-->
<!--                        <div style=" padding-right: 10px; width: 25%">-->
<!--                            <input name="service_level" id="service_level" class="easyui-textbox" labelPosition="top" tipPosition="bottom"-->
<!--                                   readonly="true" label="SL:" style="width:100%">-->
<!--                        </div>-->
                    </div>
					<div style="margin-bottom:1px">
                        <div style="float:left; width: 50%; padding-right: 5px;">
							<input readonly="true" name="store_code" id="store_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Sales Toko:" style="width:100%">
                        </div>
                        <div style="float:right; width: 50%; padding-left: 5px;">
                            <input readonly="true" name="location_code" id="location_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Gudang:" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               required="true" label="Keterangan:" style="width:100%; height: 100px;">
                    </div>
                    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                        <div style=" padding-right: 10px; width: 50%">
                            <input name="verifikasi_finance" id="verifikasi_finance" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Finance:" style="width:100%;">
                        </div>
                        <div style="width: 50%">
                            <input name="jenis_faktur" id="jenis_faktur" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Jenis Faktur:" style="width:100%;">
                        </div>

                    </div>
                </div>
                <div style="width: 20%; padding: 10px;">

                    <div style="margin-bottom:1px">
                        <input name="gross_sales" id="gross_sales" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Subtotal:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="total_discount" id="total_discount" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Discount:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="sales_before_tax" id="sales_before_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="DPP:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="total_ppn" id="total_ppn" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="PPN:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="sales_after_tax" id="sales_after_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Total:" style="width:100%; text-align: right;">
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
