<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
	var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
	var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
	var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
	var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
	var otoritas = "<?php echo $this->session->userdata('kode otoritas'); ?>";
	var aksi = "<?php echo $aksi; ?>";
	var docno = "<?php echo $docno; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/so_form.js"></script>
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
  .border-kotak {
    border: solid;
    border-width: 1px !important;
  }
</style>
<div id="tt">
  <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <a href="<?php echo base_url('salesorder')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Save</a>
    <a href="javascript:void(0)" id="update" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Update</a>
    <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-posting" onclick="submit('ON ORDER')" style="width:90px; height: 20px;">Posting</a>
    <a href="javascript:void(0)" id="close" class="easyui-linkbutton" iconCls="icon-close" onclick="submit('CLOSE')" style="width:90px; height: 20px;">Close</a>
    <a href="javascript:void(0)" id="print" class="easyui-linkbutton" iconCls="icon-print" onclick="printSO()" style="width:90px; height: 20px;">Print</a>
    <a href="javascript:void(0)" id="customer" class="easyui-linkbutton" iconCls="icon-customer" onclick="showCustomer()" style="width:90px; height: 20px;">Customer</a>
    <a href="javascript:void(0)" id="info" class="easyui-linkbutton" iconCls="icon-info" onclick="infoData()" style="width:30px; height: 20px;"></a>
  </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
  <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
       style="width:100%;height:100%;background:#fafafa;"
       data-options="iconCls:'icon-sales',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
    <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div class="border-kotak" style="width: 30%; padding: 10px;">
          <div style="margin-bottom:1px">
            <div style="float:left; width: 55%; padding-right: 5px;">
              <input name="docno" id="docno" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Trx. No:" style="width:100%">
            </div>
            <div style="float:left; width: 35%; padding-right: 5px;">
              <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     required="true" readonly="true" label="Status:" style="width:100%">
            </div>
            <div style="float:right; width:10%;">
              <input name="jumlah_print" id="jumlah_print" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     required="true" readonly="true" label=" " style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
            <input name="doc_date" id="doc_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                   required="true" label="Trx. Date:" style="width:100%">
            </div>
            <div style="float:right; width: 50%; padding-right: 5px;">
              <input name="customer_type_name" id="customer_type_name" readonly class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Price Type:" style="width:100%">
            </div>
          </div>

          <div style="margin-bottom:1px">
            <div style="float:left; width: 25%; padding-right: 5px;">
              <input name="customer_code" id="customer_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Customer:" style="width:100%">
            </div>
            <div style="float:right; width: 75%; padding-right: 5px;">
              <input name="customer_name" id="customer_name" readonly class="easyui-textbox" labelPosition="top" tipPosition="bottom" label=" " style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="margin-bottom:1px; display: none">
              <input name="regency_id" id="regency_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     required="false" readonly="true" label="" style="width:100%">
            </div>
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="regency_name" id="regency_name" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Kabupaten:" style="width:100%">
            </div>
            <div style="margin-bottom:1px; display: none">
              <input name="provinsi_id" id="provinsi_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     required="false" readonly="true" label="" style="width:100%">
            </div>
            <div style="float:right; width: 50%; padding-left: 5px;">
              <input name="provinsi_name" id="provinsi_name" class="easyui-combogrid" labelPosition="top" tipPosition="bottom"
                     required="true" readonly="true" label="Provinsi:"style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px; display: none">
            <input name="pkp" id="pkp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   required="false" readonly="true" label="PKP:" style="width:100%">
          </div>
        </div>
        <div class="border-kotak" style="width: 30%; padding: 10px;">
          <div style="margin-bottom:1px; display: none">
            <input name="jenis_so" id="jenis_so" class="easyui-combobox" labelPosition="top" tipPosition="bottom" label="Jenis Faktur:" style="width:100%;">
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 70%; padding-right: 5px;">
              <input readonly="true" name="store_code" id="store_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Sales Toko:" style="width:100%">
            </div>
            <div style="float:right; width: 30%; padding-left: 5px;">
              <input readonly="true" name="location_code" id="location_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Gudang:" style="width:100%">
            </div>
          </div>

          <div style="margin-bottom:1px">
            <input name="salesman_id" id="salesman_id" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Salesman:" style="width:100%">
          </div>
          <div style="margin-bottom:1px">
            <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   required="true" label="Keterangan:" style="width:100%;">
          </div>
          <div style="margin-bottom:1px; display: none;">
            <input name="reason" id="reason" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   label="Keterangan:" style="width:100%;">
          </div>
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 5px; width: 35%">
              <input name="disc1_persen" id="disc1_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 1:" style="width:100%">
            </div>
            <div style="width: 35%">
              <input name="disc2_persen" id="disc2_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" label="Disc 2:" style="width:100%">
            </div>
            <div style=" padding-right: 10px; width: 35%">
              <input name="disc3_persen" id="disc3_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 3:" style="width:100%">
            </div>
          </div>
<!--          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">-->
<!--            <div style="width: 35%">-->
<!--              <input name="tipe_komisi" id="tipe_komisi" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" label="Type Harga:" style="width:100%">-->
<!--            </div>-->
<!--          </div>-->
        </div>
        <div class="border-kotak" style="width: 40%; padding: 10px;">
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 10px; width: 33%">
              <input name="credit_limit" id="credit_limit" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     data-options="formatter:numberFormat" readonly="true" label="Credit Limit:" style="width:100%">
            </div>
            <div style="padding-right: 10px; width: 33%">
              <input name="outstanding" id="outstanding" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Outstanding:" style="width:100%">
            </div>
            <div style="width: 33%">
              <input name="credit_remain" id="credit_remain" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Credit Remain:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 10px; width: 25%">
              <input name="service_level" id="service_level" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="SL:" style="width:100%">
            </div>
            <div style=" padding-right: 10px; width: 25%">
              <input name="qty_deliver" id="qty_deliver" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="DO:" style="width:100%">
            </div>
            <div style=" padding-right: 10px; width: 25%">
              <input name="qty_item" id="qty_item" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="#IT:" style="width:100%">
            </div>
            <div style=" padding-right: 10px; width: 25%">
              <input name="qty_order" id="qty_order" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Qty:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 10px; width: 50%">
              <input name="gross_sales" id="gross_sales" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Bruto:" style="width:100%">
            </div>
            <div style="padding-right: 10px; width: 50%">
              <input name="total_discount" id="total_discount" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Discount:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 10px; width: 33%">
              <input name="sales_before_tax" id="sales_before_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Sales Before Tax:" style="width:100%">
            </div>
            <div style="padding-right: 10px; width: 33%">
              <input name="total_ppn" id="total_ppn" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Total PPN:" style="width:100%">
            </div>
            <div style=" padding-right: 10px; width: 33%">
              <input name="sales_after_tax" id="sales_after_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Sales After Tax:" style="width:100%">
            </div>
          </div>
<!--          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">-->
<!--            <div style=" padding-right: 10px; width: 50%">-->
<!--              <input name="sales_after_tax" id="sales_after_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"-->
<!--                     readonly="true" label="Sales Aft Tax:" style="width:100%">-->
<!--            </div>-->
<!--          </div>-->
        </div>
        <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
      </div>
      <div data-options="region:'west'" style="width:100%;">
        <table id="dg" class="easyui-edatagrid" style="width:100%;height: 300px">
        </table>
      </div>
    </form>
  </div>
  <div id="toolbar23" style="padding:2px;">
    <form id="fromcopy" style="margin-bottom:-0px;">
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <input class="easyui-combogrid" id="combo" name="combo"
               label="Customer:" label-position="top" style="width:50%">
        <a href="#" onclick="submitCopy()" class="easyui-linkbutton" iconCls="icon-save" plain="true">Submit</a>
        <a href="#" onclick="cancelUpload()" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Cancel</a>
      </div>
    </form>
  </div>
</div>
