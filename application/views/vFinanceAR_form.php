<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
	var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
	var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
	var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
	var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
	var otoritas = "<?php echo $this->session->userdata('kode otoritas'); ?>";
	var aksi = "<?php echo $aksi; ?>";
	var id = "<?php echo $id; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/adminlte/number-divider.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/finance_ar_form.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
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
<div id="tt">
  <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <a href="<?php echo base_url('Finance/ar')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitAR()" style="width:90px; height: 20px;">Save</a>
    <a href="javascript:void(0)" id="update" class="easyui-linkbutton" iconCls="icon-save" onclick="updateAR()" style="width:90px; height: 20px;">Update</a>
    <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-posting" onclick="postingAR()" style="width:90px; height: 20px;">Posting</a>
    <a href="javascript:void(0)" id="unposting" class="easyui-linkbutton" iconCls="icon-posting" onclick="unpostingAR()" style="width:90px; height: 20px;">Unposting</a>
    <a href="javascript:void(0)" id="print" class="easyui-linkbutton" iconCls="icon-print" onclick="printAR()" style="width:90px; height: 20px;">Print</a>
  </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
  <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
       style="width:100%;height:100%;background:#fafafa;"
       data-options="iconCls:'icon-finance-ar',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
    <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div style="width: 30%; padding: 10px;">
          <div style="margin-bottom:1px">
            <div style="float:left; width: 100%; padding-right: 5px;">
              <label id="last_number"></label>
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 55%; padding-right: 5px;">
              <input name="trx_type" id="trx_type" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     label="Tipe:" style="width:100%">
            </div>
            <div style="float:left; width: 35%; padding-right: 5px;">
            </div>
            <div style="float:right; width:10%;">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 55%; padding-right: 5px;">
              <input name="id" id="id" type="hidden" value="0">
              <input name="docno" id="docno" readonly class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     label="Trx. No:" style="width:100%">
            </div>
            <div style="float:left; width: 35%; padding-right: 5px;">
              <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     required="true" readonly="true" label="Status:" style="width:100%">
            </div>
            <div style="float:right; width:10%;">
              <input name="printno" id="printno" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     required="true" readonly="true" label=" " style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <input name="payment_date" id="payment_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                   required="true" label="Trx. Date:" style="width:100%">
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="due_date" id="due_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                     required="true" label="Due Date:" style="width:100%">
            </div>
            <div style="float:right; width: 50%; padding-left: 5px;">
              <input name="cleared_date" id="cleared_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                     required="true" label="Cleared Date:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
<!--              <input name="store_code" id="store_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Store:" style="width:100%">-->
<!--            </div>-->
<!--            <div style="float:right; width: 50%; padding-left: 5px;">-->
              <input name="customer_code" id="customer_code" labelPosition="top" tipPosition="bottom" label="Customer:" style="width:100%">
            </div>
          </div>
        </div>
        <div style="width: 30%; padding: 10px;">
          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="cbtype" id="cbtype" class="easyui-combobox" labelPosition="top" tipPosition="right" required="true" label="C/B Type:" style="width:100%">
            </div>
            <div style="float:right; width: 50%; padding-left: 5px;">
              <input name="dbcr" id="dbcr" class="easyui-combobox" labelPosition="top" tipPosition="right" required="true" label="Debit / Credit:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <input name="no_cb" id="no_cb" class="easyui-combogrid" labelPosition="top" tipPosition="bottom"
                   label="C/B Number:" style="width:100%">
          </div>
          <div style="margin-bottom:1px">
            <input name="reff" id="reff" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   label="Referrence:" style="width:100%">
          </div>
          <div style="margin-bottom:1px">
            <input name="bg_no" id="bg_no" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   label="BG Number:" style="width:100%">
          </div>
          <div style="margin-bottom:1px; display: none">
            <input name="journal_no" id="journal_no" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   label="Journal Number:" style="width:100%">
          </div>
        </div>
        <div style="width: 30%; padding: 10px;">
          <div style="margin-bottom:1px">
            <input name="remark" id="remark" class="easyui-textbox" multiline="true" labelPosition="top" tipPosition="bottom"
                   required="true" label="Keterangan:" style="width:100%; height: 100px;">
          </div>
          <div style="margin-bottom:1px">
            <input name="payment_amount" id="payment_amount" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Amount:" style="width:100%">
          </div>

          <div style="margin-bottom:1px">
            <input name="payment_by" id="payment_by" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Payment Type:" style="width:100%">
          </div>
        </div>
      </div>
      <div style="display:inline-block; width:100%; height:2px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;">Detail</div>
      <div id="detail">
        <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: flex-end">
          <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="addDetail(null)" >Add</a>
        </div>
      </div>
    </form>
  </div>
</div>
<div id="dlg" class="easyui-dialog" style="width:800px" data-options="closed:true,title:'Get Faktur',modal:true,border:'thin'">
  <table id="dg" class="easyui-datagrid" style="width:100%;height: 300px" >
  </table>
</div>
