<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
	var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
	var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
	var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
	var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
	var otoritas = "<?php echo $this->session->userdata('kode otoritas'); ?>";
	var aksi = "<?php echo $aksi; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/jquery-easyui-1.9.4/plugins/jquery.textbox.js"></script>-->
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
    <a href="<?php echo base_url('fa/Adminfee')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()" style="width:90px; height: 20px;">Submit</a>
  </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
	<?php echo $this->message->display();?>
  <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
       style="width:100%;height:100%;background:#fafafa;"
       data-options="iconCls:'icon-finance-ar',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
    <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div style="width: 10%; padding: 10px;">
        </div>
        <div style="width: 40%; padding: 10px;">
          <div style="margin-bottom:1px">
            <div style="float:left; width: 55%; padding-right: 5px;">
              <input name="docno" id="docno" readonly class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Trx. No:" style="width:100%">
            </div>
            <div style="float:left; width: 35%; padding-right: 5px;">
              <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label="Status:" style="width:100%">
            </div>
            <div style="float:right; width:10%;">
              <input name="jumlah_print" id="jumlah_print" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label=" " style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="gross_sales" id="gross_sales" readonly class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Bruto:" style="width:100%">
            </div>
            <div style="float:right; width:50%;">
              <input name="total_discount" id="total_discount" readonly class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Discount:" style="width:100%">
            </div>
          </div>
        </div>
        <div style="width: 40%; padding: 10px;">
          <div style="margin-bottom:1px">
            <div style="float:left; width: 20%; padding-right: 5px;">
              <input name="location_code" id="location_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label="Lokasi:" style="width:100%">
            </div>
            <div style="float:left; width: 20%; padding-right: 5px;">
              <input name="store_code" id="store_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label="Store:" style="width:100%">
            </div>
            <div style="float:right; width:60%;">
              <input name="store_name" id="store_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label=" " style="width:100%">
            </div>
          </div>

          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="sales_before_tax" id="sales_before_tax" readonly class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Net Sales:" style="width:100%">
            </div>
            <div style="float:right; width:50%;">
              <input name="sales_after_tax" id="sales_after_tax" readonly class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Payment:" style="width:100%">
            </div>
          </div>
        </div>
        <div style="width: 10%; padding: 10px;">
        </div>
      </div>
      <div style="display:inline-block; width:100%; height:2px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"></div>
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div style="width: 40%; padding: 10px;">
          <div style="margin-bottom:1px">
            <div style="float:left; width: 20%; padding-right: 5px;">
              <input name="qty" id="qty" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" label="Qty:" style="width:100%">
            </div>
            <div style="float:left; width: 80%; padding-right: 5px;">
              <input name="scan" id="scan" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="SKU:" style="width:80%">
            </div>
          </div>
        </div>
      </div>
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <table id="dg" class="easyui-datagrid" style="width:100%;height: 300px">
        </table>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
	var options={
		title:"List Detail",
		pagination:true, striped:true, nowrap:false,
		singleSelect:true,
		columns:[[
			{field:"id", title:"id"},
			{field:"docno", title:"docno"},
			{field:"product_tipe", title:"product_tipe"},
			{field:"seqno", title:"seqno"},
			{field:"nobar", title:"nobar"},
			{field:"tipe", title:"tipe"},
			{field:"komisi", title:"komisi"},
			{field:"qty_order", title:"qty_order"},
			{field:"qty_sales", title:"qty_sales"},
			{field:"qty_refund", title:"qty_refund"},
			{field:"uom_code", title:"uom_code"},
			{field:"unit_price", title:"unit_price"},
			{field:"disc1_persen", title:"disc1_persen"},
			{field:"disc1_amount", title:"disc1_amount"},
			{field:"disc2_persen", title:"disc2_persen"},
			{field:"disc2_amount", title:"disc2_amount"},
			{field:"disc_total", title:"disc_total"},
			{field:"net_unit_price", title:"net_unit_price"},
			{field:"sales_before_ppn", title:"sales_before_ppn"},
			{field:"sales_after_ppn", title:"sales_after_ppn"},
			{field:"net_total_price", title:"net_total_price"},
			{field:"jumlah_hpp", title:"jumlah_hpp"},
			{field:"status_detail", title:"status_detail"},
			{field:"add_cost1", title:"add_cost1"},
			{field:"add_cost2", title:"add_cost2"},
			{field:"add_cost3", title:"add_cost3"}
		]]
	};
	var tb_scan;
  $(document).ready(function () {
		$('#fm').form('load',<?php echo json_encode($header);?>);
		$('#dg').datagrid(options);
		$('#qty').numberbox('setValue',1)
    tb_scan = $('#scan').textbox({
			icons: [{
				iconCls:'icon-search',
				handler: function(e){
					$(e.data.target).textbox('setValue', 'Something added!');
				}
			}]
    });
		tb_scan.textbox('clear').textbox('textbox').focus();
		tb_scan.textbox('textbox').bind('keydown', function(e){
			if(e.key==='Enter' || e.keyCode===13){ 	// when press ENTER key, accept the inputed value.
        var sku = $(this).val();
        console.log(sku)
				tb_scan.textbox('setValue', "");
				$('#qty').numberbox('setValue',1)
			}
		});
	})
	function submitForm(){
  	$.redirectForm("<?php echo base_url('fa/Adminfee/entryp/'.$aksi)?>","#fm","post","")
  }
</script>
