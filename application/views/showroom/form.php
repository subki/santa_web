<style>
  .panel-titleq .panel-tool{
    height:50px;
    line-height: 50px;
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
    <a href="<?php echo base_url('Showroomrecap/index/').$location_code?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
    <a href="javascript:void(0)" id="get_rekap" class="easyui-linkbutton" iconCls="icon-search" onclick="generateRekap()" style="width:90px; height: 20px;">Get Sales</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('<?php echo isset($item)?$item->status:'OPEN' ?>')" style="width:90px; height: 20px;">Submit</a>
    <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-posting" onclick="submit('CLOSED')" style="width:90px; height: 20px;">Posting</a>
    <a href="javascript:void(0)" id="close" class="easyui-linkbutton" iconCls="icon-close" onclick="submit('CLOSE')" style="width:90px; height: 20px;">Close</a>
    <a href="javascript:void(0)" id="print" class="easyui-linkbutton" iconCls="icon-print" onclick="printSO()" style="width:90px; height: 20px;">Print</a>
    <a href="javascript:void(0)" id="customer" class="easyui-linkbutton" iconCls="icon-customer" onclick="showCustomer()" style="width:90px; height: 20px;">Customer</a>
    <a href="javascript:void(0)" id="clear_faktur" class="easyui-linkbutton" iconCls="icon-tax" onclick="clearFaktur()" style="width:200px; height: 20px;">Clear Detail Faktur</a>
    <a href="javascript:void(0)" id="crt_faktur" class="easyui-linkbutton" iconCls="icon-tax" onclick="createFaktur()" style="width:140px; height: 20px;">Create Faktur</a>
    <a href="javascript:void(0)" id="btn_seri_pajak" class="easyui-linkbutton" iconCls="icon-tax" onclick="createSeriPajak()" style="width:90px; height: 20px;">Get FP</a>
    <a href="javascript:void(0)" id="verify_fa" class="easyui-linkbutton" iconCls="icon-ok" onclick="verifyFA()" style="width:150px; height: 20px;">Verifikasi Finance</a>
  </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
	<?php echo $this->message->display();?>
  <div id="p" class="easyui-panel" title="  "
       style="width:100%;height:100%;background:#fafafa;"
       data-options="closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
    <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
      <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div class="border-kotak" style="width: 40%; padding: 10px; margin: 1px;">
          <div style="margin-bottom:1px">
            <div style="float:left; width: 55%; padding-right: 5px;">
              <input name="no_faktur" id="no_faktur" class="easyui-textbox khusus checkFaktur" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->no_faktur:'' ?>" label="Trx. No:" style="width:100%">
            </div>
            <div style="float:left; width: 35%; padding-right: 5px;">
              <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->status:'OPEN' ?>" required="true" readonly="true" label="Status:" style="width:100%">
            </div>
            <div style="float:right; width:10%;">
              <input name="qty_print" id="qty_print" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->qty_print:'0' ?>" required="true" readonly="true" label=" " style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px; display: none">
            <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   value="<?php echo $id ?>" readonly="true" label="ID:" style="width:100%">
          </div>
          <div style="margin-bottom:1px; display:block">
            <input name="seri_pajak" id="seri_pajak" readonly="true"
                   value="<?php echo isset($item)?$item->seri_pajak:'' ?>" class="easyui-maskedbox" mask="999.999.99.99999999" labelPosition="top" tipPosition="bottom" label="Seri Pajak:" style="width:100%">
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="doc_date" id="doc_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->doc_date:date('d/m/Y') ?>" required="true" label="Trx. Date:" style="width:100%">
            </div>
            <div style="float:right; width: 50%; padding-right: 5px;">
              <input name="faktur_date" id="faktur_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->faktur_date:date('d/m/Y') ?>" required="true" label="Faktur Date:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px; display:none">
            <div style="margin-bottom:1px; display: none">
              <input name="regency_id" id="regency_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->regency_id:$location_data->regency_id ?>" readonly="true" label="" style="width:100%">
            </div>
            <div style="margin-bottom:1px; display: none">
              <input name="provinsi_id" id="provinsi_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->provinsi_id:$location_data->provinsi_id ?>" readonly="true" label="" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 85%; padding-right: 5px;">
              <input name="customer_code" id="customer_code" class="easyui-textbox" readonly
                     value="<?php echo isset($item)?$item->customer_code:$location_data->customer_code ?>" labelPosition="top" tipPosition="bottom" required="true" label="Customer:" style="width:100%">
            </div>
            <div style="float:right; width: 15%; padding-right: 5px;">
              <input name="beda_fp" id="beda_fp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->beda_fp:$location_data->beda_fp ?>" label="Beda FP" readonly="true" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 85%; padding-right: 5px;">
              <input name="customer_name" id="customer_name" class="easyui-textbox"
                     value="<?php echo isset($item)?$item->customer_name:$location_data->customer_name ?>" labelPosition="top" tipPosition="bottom" readonly="true" disabled="true" label="" style="width:100%">
            </div>
            <div style="float:right; width: 15%; padding-right: 5px;">
              <input name="pkp" id="pkp" readonly="true" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->pkp:$location_data->pkp ?>" label="" style="width:100%">
            </div>
          </div>
        </div>
        <div class="border-kotak" style="width: 40%; padding: 10px; margin: 1px;">
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 10px; width: 30%">
              <input readonly="true" name="disc1_persen" id="disc1_persen" class="easyui-numberbox"
                     value="<?php echo isset($item)?$item->disc1_persen:'' ?>" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 1:" style="width:100%">
            </div>
            <div style="width: 30%">
              <input readonly="true" name="disc2_persen" id="disc2_persen" class="easyui-numberbox"
                     value="<?php echo isset($item)?$item->disc2_persen:'' ?>" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" label="Disc 2:" style="width:100%">
            </div>
            <div style=" padding-right: 10px; width: 30%">
              <input readonly="true" name="disc3_persen" id="disc3_persen" class="easyui-numberbox"
                     value="<?php echo isset($item)?$item->disc3_persen:'' ?>" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 3:" style="width:100%">
            </div>
          </div>

          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input readonly="true" name="store_name" id="store_name" class="easyui-textbox"
                     value="<?php echo isset($item)?$item->store_name:$location_data->store_name ?>" labelPosition="top" tipPosition="bottom" required="true" label="Sales Toko:" style="width:100%">
            </div>
            <div style="float:right; width: 50%; padding-left: 5px;">
              <input readonly="true" name="location_code" id="location_code" class="easyui-textbox"
                     value="<?php echo isset($item)?$item->location_code:$location_code ?>" labelPosition="top" tipPosition="bottom" required="true" label="Gudang:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   value="<?php echo isset($item)?$item->remark:'' ?>" required="true" label="Keterangan:" validType="length[0,50]" style="width:100%;">
          </div>
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 10px; width: 50%">
              <input name="verifikasi_finance" id="verifikasi_finance" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     readonly="true" label="Verifikasi Finance:" style="width:100%;">
            </div>
            <div style="width: 50%">
              <input name="jenis_faktur" id="jenis_faktur" class="easyui-textbox" readonly
                     value="<?php echo isset($item)?$item->jenis_faktur:'SHOWROOM' ?>" labelPosition="top" tipPosition="bottom" required="true" label="Jenis Faktur:" style="width:100%;">
            </div>

          </div>
        </div>
        <div class="border-kotak" style="width: 20%; padding: 10px; margin: 1px;">
          <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style=" padding-right: 10px; width: 50%">
              <input name="qty_item" id="qty_item" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->qty_item:'' ?>" readonly="true" label="#ITEM:" style="width:100%">
            </div>
            <div style=" padding-right: 10px; width: 50%">
              <input name="qty_order" id="qty_order" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                     value="<?php echo isset($item)?$item->qty_order:'' ?>" readonly="true" label="QTY SALES:" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
            <input name="gross_sales" id="gross_sales" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   value="<?php echo isset($item)?$item->gross_sales:'' ?>" readonly="true" label="Subtotal:" style="width:100%; text-align: right;">
          </div>
          <div style="margin-bottom:1px">
            <input name="total_discount" id="total_discount" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   value="<?php echo isset($item)?$item->total_discount:'' ?>" readonly="true" label="Discount:" style="width:100%; text-align: right;">
          </div>
          <div style="margin-bottom:1px">
            <input name="sales_before_tax" id="sales_before_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   value="<?php echo isset($item)?$item->sales_before_tax:'' ?>" readonly="true" label="DPP:" style="width:100%; text-align: right;">
          </div>
          <div style="margin-bottom:1px">
            <input name="total_ppn" id="total_ppn" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   value="<?php echo isset($item)?$item->total_ppn:'' ?>" readonly="true" label="PPN:" style="width:100%; text-align: right;">
          </div>
          <div style="margin-bottom:1px">
            <input name="sales_after_tax" id="sales_after_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                   value="<?php echo isset($item)?$item->sales_after_tax:'' ?>" readonly="true" label="Total:" style="width:100%; text-align: right;">
          </div>
        </div>
        <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
      </div>
      <div data-options="region:'west'" style="width:100%;">
        <div class="border-kotak" style="width: 100%; padding: 10px; ">

        </div>
      </div>
    </form>
  </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var location_code = "<?php echo $location_code ?>";
	var location_data = <?php echo json_encode($location_data); ?>;
	var aksi = "<?php echo $aksi; ?>";
	var docno = "<?php echo isset($docno)?$docno:''; ?>";
	var so_item = null;
	$(document).ready(function () {
		if(aksi==="add"){
			$("#update").hide();
			$("#posting").hide();
			$("#close").hide();
			$("#print").hide();
			$("#customer").hide();
			$("#crt_faktur").hide();
			$("#btn_seri_pajak").hide();
			$("#clear_faktur").hide();
			$("#verify_fa").hide();
			$("#get_rekap").show();
		}else{
			so_item = <?php echo json_encode($item)?>;
			if(so_item.status==="CLOSED"){
				$("#posting").linkbutton({text:"Unposting"});
				$("#posting").show();
				$("#close").hide();
				$("#clear_faktur").hide();
				$("#update").show();
				$("#get_rekap").hide();
			}else{
				$("#clear_faktur").show();
			}
			if(so_item.pkp==="YES"){
				if(so_item.beda_fp==="YES") {
					$("#crt_faktur").show();
				}else {
					$("#crt_faktur").hide();
				}
				if(so_item.no_faktur.length>9){
					$("#crt_faktur").hide();
				}
				if(so_item.seri_pajak!==""){
					var str = so_item.seri_pajak;
					$("#btn_seri_pajak").maskedbox('setValue',str)
				}else{
					if(so_item.status=="POSTING" || so_item.status=="CLOSED"){
						$("#btn_seri_pajak").show();
					}else{
						$("#btn_seri_pajak").hide();
					}
				}
			}else{
				$("#btn_seri_pajak").hide();
				if(so_item.no_faktur.length>9){
					$("#crt_faktur").hide();
				}else {
					$("#crt_faktur").hide();
				}
			}
			if(so_item.verifikasi_finance==="OPEN" || so_item.verifikasi_finance==="" || so_item.verifikasi_finance===null || so_item.verifikasi_finance===undefined){
				$("#verify_fa").hide();
			}else{
				$("#verify_fa").hide();
			}
			if(so_item.no_faktur.length>9){
				$("#crt_faktur").hide();
			}else{
				$("#crt_faktur").show();
			}
			$("#close").hide();
		}
	});
	function generateRekap() {
    var tgl = $("#doc_date").datebox('getValue');
    console.log("tangal", tgl);
	}
</script>