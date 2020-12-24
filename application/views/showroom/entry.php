<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
	var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
	var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
	var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
	var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
	var otoritas = "<?php echo $this->session->userdata('kode otoritas'); ?>";
	var aksi = "<?php echo $aksi; ?>";
	var id = "<?php echo $docno; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
<script src="<?php echo base_url(); ?>assets/mousetrap.min.js"></script>
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
    <a href="<?php echo base_url('showroom/index')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="batalForm()" style="width:90px; height: 20px;">Batal</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()" style="width:90px; height: 20px;">Submit</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-print" onclick="printForm()" style="width:90px; height: 20px;">Print</a>
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
      <table style="width:100%;">
        <tr>
          <td style="width: 60%">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
              <div style="width: 50%; padding: 10px;">
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
                  <div style="float:left; width: 15%; padding-right: 5px;">
                    <input name="location_code" id="location_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label="Loc.:" style="width:100%">
                  </div>
                  <div style="float:left; width: 15%; padding-right: 5px;">
                    <input name="customer_code" id="customer_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label="Cust:" style="width:100%">
                  </div>
                  <div style="float:left; width: 15%; padding-right: 5px;">
                    <input name="store_code" id="store_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label="Store:" style="width:100%">
                  </div>
                  <div style="float:right; width:55%;">
                    <input name="store_name" id="store_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly label=" " style="width:100%">
                  </div>
                </div>
                <div style="margin-bottom:1px">
                  <input name="promoid" id="promoid" labelPosition="top" tipPosition="bottom" label="Nomor Promo:" style="width:100%">
                </div>
              </div>
              <div style="width: 50%; padding: 10px;">

                <div style="margin-bottom:1px">
                  <div style="float:left; width: 50%; padding-right: 5px;">
                    <input name="gross_sales" id="gross_sales" readonly class="easyui-numberbox" data-options="groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" required="false" label="Bruto:" style="width:100%">
                  </div>
                  <div style="float:right; width:50%;">
                    <input name="total_discount" id="total_discount" readonly class="easyui-numberbox" data-options="groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" required="false" label="Discount:" style="width:100%">
                  </div>
                </div>

                <div style="margin-bottom:1px">
                  <div style="float:left; width: 50%; padding-right: 5px;">
                    <input name="sales_after_tax" id="sales_after_tax" readonly class="easyui-numberbox" data-options="groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" required="false" label="Net Sales:" style="width:100%">
                  </div>
                  <div style="float:right; width:50%;">
                    <input name="payment_sum" id="payment_sum" readonly class="easyui-numberbox" data-options="groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" required="false" label="Payment:" style="width:100%">
                  </div>
                </div>
                <div style="margin-bottom:1px">
                  <div style="float:left; width: 50%; padding-right: 5px;">
<!--                    <input name="sales_after_tax" id="sales_after_tax" readonly class="easyui-numberbox" data-options="groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" required="false" label="Net Sales:" style="width:100%">-->
                  </div>
                  <div style="float:right; width:50%;">
                    <input name="payment_ret" id="payment_ret" readonly class="easyui-numberbox" data-options="groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" required="false" label="Kembalian:" style="width:100%">
                  </div>
                </div>
              </div>
            </div>
          </td>
          <td style="width:40%; vertical-align: top">
            <div id="detail" style="margin-bottom:1px;display: flex; flex-direction: column; flex-wrap: nowrap; justify-content: space-between;">
              <div>
                <div style="margin: 10px">
                  <div style="float:left; width: 35%;">Type</div>
                  <div style="float:left; width: 25%;">Document</div>
                  <div style="float:left; width: 22%;">Nilai Bayar</div>
                  <div style="float:left; width: 5%;">
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="addDetail(null)" >Add</a>
                  </div>
                </div>
              </div>
              <div style="display:inline-block; margin-top: 5px; margin-bottom: 5px; width:100%; height:2px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"></div>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div style="display:inline-block; width:100%; height:2px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"></div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
              <div style="width: 40%; ">
                <div style="margin-bottom:1px">
                  <div style="float:left; width: 20%; padding-right: 5px;">
                    <input name="qty" id="qty" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" label="Qty:" style="width:100%">
                  </div>
                  <div style="float:left; width: 80%; padding-right: 5px;">
                    <input name="scan" id="scan" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Item:" style="width:80%">
                  </div>
                </div>
              </div>
            </div>
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
              <table id="dg" class="easyui-datagrid" style="width:98%;height: 300px">
              </table>
            </div>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <script type="text/javascript">
		var detail_item = [];
		var options={
			title:"List Detail",
			pagination:false, striped:true, nowrap:false,
			singleSelect:true,
			data: detail_item,
			columns:[[
				{field:"action", title:"Act", formatter: function(value,row,index){
					var a = `<a href="#" onclick="remove(${row.idlocal});" title="Remove" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-clear">&nbsp;</span></span>
                        </a>
                        `;
					return a;
				}
				},
				{field:"id", title:"id"},
				{field:"docno", title:"No. Trx"},
				{field:"product_tipe", title:"  "},
				{field:"seqno", title:"Seq"},
				{field:"nobar", title:"SKU"},
				{field:"product_code", title:"Product Code"},
				{field:"tipe", title:"tipe"},
				{field:"komisi", title:"komisi"},
				{field:"qty_order", title:"Qty", width:100, styler:function(value,row){
					return 'background-color:#ffee00;color:red;';
				}},
				{field:"qty_sales", title:"qty_sales"},
				{field:"qty_refund", title:"qty_refund"},
				{field:"uom_code", title:"uom_code"},
				{field:"uom_id", title:"UOM"},
				{field:"unit_price", title:"Retail", formatter:function (index, row) {
					return numberFormat(row.unit_price);
				}},
				{field:"disc1_persen", title:"disc1_persen"},
				{field:"disc1_amount", title:"disc1_amount"},
				{field:"disc2_persen", title:"disc2_persen"},
				{field:"disc2_amount", title:"disc2_amount"},
				{field:"disc_total", title:"Discount", formatter:function (index, row) {
					return numberFormat(row.disc_total);
				}},
				{field:"net_unit_price", title:"net_unit_price"},
				{field:"sales_before_ppn", title:"sales_before_ppn"},
				{field:"sales_after_ppn", title:"Sub Total", formatter:function (index, row) {
					return numberFormat(row.sales_after_ppn);
				}},
				{field:"net_total_price", title:"net_total_price"},
				{field:"jumlah_hpp", title:"jumlah_hpp"},
				{field:"status_detail", title:"status_detail"},
				{field:"add_cost1", title:"add_cost1"},
				{field:"add_cost2", title:"add_cost2"},
				{field:"add_cost3", title:"add_cost3"}
			]],
			onClickCell:function(index, field, value){
				if(field === "qty_order"){
					var row = $('#dg').datagrid('getRows')[index];
					var boleh = false;
					if(index_edit!==undefined){
						if(index_edit!==index) {
							var rowe = $(this).datagrid('getRows')[index_edit];
							rowe.qty_order = $('#qty'+index_edit).textbox('getValue');
							for(var i=0; i<detail_item.length; i++){
								if(detail_item[i].idlocal===rowe.idlocal) {
									detail_item[i].qty_order = rowe.qty_order;
									detail_item[i].sales_before_ppn = Math.round((detail_item[i].net_unit_price*detail_item[i].qty_order)/1.1);
									detail_item[i].sales_after_ppn = detail_item[i].net_unit_price*detail_item[i].qty_order
									detail_item[i].net_total_price = detail_item[i].sales_after_ppn
									detail_item[i].jumlah_hpp = detail_item[i].hpp*detail_item[i].qty_order*detail_item[i].convertion
									rowe = detail_item[i];
									hitungHeader()
									break;
								}
							}
							$('#dg').datagrid('updateRow', {index:index_edit,row:rowe})
							index_edit = index;
							boleh = true;
						}
					}else{
						index_edit = index;
						boleh = true;
					}
					if(boleh){
						row.qty_order = ` <div style="float:left; width: 100%; padding-right: 5px;">
              <input value="${row.qty_order}" name="qty${index_edit}" id="qty${index_edit}" class="easyui-textbox" style="width:100%">
            </div>`;
						$('#dg').datagrid('updateRow', {index:index_edit,row:row})
						$('#qty'+index_edit).textbox({
							icons: [{
								iconCls:'icon-ok',
								handler: function(e){
									var rowee = $('#dg').datagrid('getRows')[index_edit];
									rowee.qty_order = e.data.target.value;
									for(var i=0; i<detail_item.length; i++){
										if(detail_item[i].idlocal===rowee.idlocal) {
											detail_item[i].qty_order = rowee.qty_order;

											detail_item[i].sales_before_ppn = Math.round((detail_item[i].net_unit_price*detail_item[i].qty_order)/1.1);
											detail_item[i].sales_after_ppn = detail_item[i].net_unit_price*detail_item[i].qty_order
											detail_item[i].net_total_price = detail_item[i].unit_price-detail_item[i].disc_total
											detail_item[i].jumlah_hpp = detail_item[i].hpp*detail_item[i].qty_order*detail_item[i].convertion
											rowee = detail_item[i];
											hitungHeader()
											break;
										}
									}
									$('#dg').datagrid('updateRow', {index:index_edit,row:rowee})
									index_edit = undefined;
								}
							}]
						});
						$('#qty'+index_edit).textbox('textbox').bind('keydown',function (e) {
							if(e.key==='Enter' || e.keyCode===13){
								var rowee = $('#dg').datagrid('getRows')[index_edit];
								rowee.qty_order = $('#qty'+index_edit).textbox('getValue');
								for(var i=0; i<detail_item.length; i++){
									if(detail_item[i].idlocal===rowee.idlocal) {
										detail_item[i].qty_order = rowee.qty_order;
										detail_item[i].sales_before_ppn = Math.round((detail_item[i].net_unit_price*detail_item[i].qty_order)/1.1);
										detail_item[i].sales_after_ppn = detail_item[i].net_unit_price*detail_item[i].qty_order
										detail_item[i].net_total_price = detail_item[i].unit_price-detail_item[i].disc_total
										detail_item[i].jumlah_hpp = detail_item[i].hpp*detail_item[i].qty_order*detail_item[i].convertion
										rowee = detail_item[i];
										hitungHeader()
										break;
									}
								}
								$('#dg').datagrid('updateRow', {index:index_edit,row:rowee})
								index_edit = undefined;
							}
						})
					}
				}
			}
		};
		var index_edit = undefined;
		var tb_scan;
		var header = <?php echo json_encode($header);?>;
		var product = <?php echo json_encode($products);?>;
		var promo_header = <?php echo json_encode($promo_header);?>;
		var det = <?php echo json_encode($detail);?>;
		var bayar = <?php echo json_encode($bayar);?>;
		var promo_detail = <?php echo json_encode($promo_detail);?>;
		var paymenttipe = <?php echo json_encode($paymenttype);?>;
		var ctr = 0;
		$(document).ready(function () {
			$('#fm').form('load', header);
			$('#dg').datagrid(options);
			$('#dg').datagrid('hideColumn','id');
			$('#dg').datagrid('hideColumn','docno');
			$('#dg').datagrid('hideColumn','komisi');
			$('#dg').datagrid('hideColumn','qty_sales');
			$('#dg').datagrid('hideColumn','qty_refund');
			$('#dg').datagrid('hideColumn','uom_code');
			$('#dg').datagrid('hideColumn','disc1_persen');
			$('#dg').datagrid('hideColumn','disc1_amount');
			$('#dg').datagrid('hideColumn','disc2_persen');
			$('#dg').datagrid('hideColumn','disc2_amount');
			$('#dg').datagrid('hideColumn','net_unit_price');
			$('#dg').datagrid('hideColumn','sales_before_ppn');
			$('#dg').datagrid('hideColumn','net_total_price');
			$('#dg').datagrid('hideColumn','jumlah_hpp');
			$('#dg').datagrid('hideColumn','status_detail');
			$('#dg').datagrid('hideColumn','add_cost1');
			$('#dg').datagrid('hideColumn','add_cost2');
			$('#dg').datagrid('hideColumn','add_cost3');
			$('#qty').numberbox('setValue',1)
			tb_scan = $('#scan').textbox({
				icons: [{
					iconCls:'icon-search',
					handler: function(e){

					}
				}]
			});
			tb_scan.textbox('clear').textbox('textbox').focus();
			tb_scan.textbox('textbox').bind('keydown', function(e){
				if(e.key==='Enter' || e.keyCode===13){ 	// when press ENTER key, accept the inputed value.
          console.log("masuk sini")
					var item = $(this).val().toLowerCase();
					var qty = $('#qty').numberbox('getValue');
					console.log("masuk sini",item,qty,product)
					var gakada = true;
					for(var i=0; i<product.length; i++){
						let a = product[i];
						if(a.sku.toLowerCase()===item || a.product_code.toLowerCase().startsWith(item)){
							util_get_unit_price(a.product_id,header.doc_date,header.location_code,header.customer_code,function (result) {
								if(parseFloat(result.unit_price)>0) {
									var template_item = {
										idlocal:0, id : 0, docno : '<?php echo $docno ?>', product_tipe : '', seqno : '', nobar : '', product_code : '', tipe : '', komisi : 0,
										qty_order : 0, qty_sales : 0, qty_refund : 0, uom_code : 0, uom_id : 0,
										unit_price : 0, disc1_persen : 0, disc1_amount : 0, disc2_persen : 0, disc2_amount : 0, disc_total : 0,
										net_unit_price : 0, sales_before_ppn : 0, sales_after_ppn : 0, net_total_price : 0, jumlah_hpp : 0,
										status_detail : 'OPEN', add_cost1 : 0, add_cost2 : 0, add_cost3 : 0,action:''
									}
									template_item.unit_price = result.unit_price;
									if(parseFloat(result.diskon)>0){
										template_item.disc1_persen = result.diskon;
										template_item.disc1_amount = parseFloat(template_item.unit_price)*(parseFloat(template_item.disc1_persen)/100);
										template_item.disc_total = template_item.disc1_amount
									}
									var promo = $("#promoid").combobox('getValue');
									if(promo!=="" || promo!=="0"){
										for(var p=0;p<promo_header.length;p++){
											if(parseInt(promo_header[p].id)===parseInt(promo)){
												template_item.disc2_persen = promo_header[p].discount;
												template_item.disc2_amount =  (parseFloat(template_item.unit_price)-parseFloat(template_item.disc1_amount))* template_item.disc2_persen/100;
												template_item.disc_total = template_item.disc1_amount+template_item.disc2_amount
												break;
											}
										}
									}
									var tipe = 0;
									for(var j=0; j<detail_item.length; j++) if(detail_item[j].nobar===a.sku) tipe++;
									if(tipe>0) template_item.product_tipe = tipe;
									template_item.idlocal = ctr;
									template_item.qty_order = qty;
									template_item.uom_id = a.uom_id;
									template_item.uom_code = a.satuan_jual;
									template_item.nobar = a.sku;
									template_item.product_code = a.product_code;
									template_item.seqno = detail_item.length<10?"00"+detail_item.length:detail_item.length<100?"0"+detail_item.length:detail_item.length;
									template_item.net_unit_price = template_item.unit_price-template_item.disc_total;
									template_item.sales_before_ppn = Math.round((template_item.net_unit_price*template_item.qty_order)/1.1);
									template_item.sales_after_ppn = template_item.net_unit_price*template_item.qty_order
									template_item.net_total_price = template_item.unit_price-template_item.disc_total
									template_item.jumlah_hpp = a.hpp*template_item.qty_order*a.convertion
									detail_item.push(template_item);
									$('#dg').datagrid('loadData',detail_item);
									hitungHeader();
									ctr++;
								}else{
									$.messager.alert("Error","Unit Price Belum tersedia")
								}
							})
							gakada = false;
							break;
						}
					}
					if(gakada) $.messager.alert("Error","Product not available")
					tb_scan.textbox('setValue', "");
					$('#qty').numberbox('setValue',1)
				}
			});
			populatePromo();

			detail_item = det;
			$('#dg').datagrid('loadData',detail_item);
//			hitungHeader();

			for(var i=0;i<bayar.length;i++)addDetail(bayar[i])
      hitungHeader()

			Mousetrap.bind('s s enter', function(e) {
				tb_scan.textbox('clear').textbox('textbox').focus();
				return false;
			});
			Mousetrap.bind('a s enter', function(e) {
				addDetail(null)
				return false;
			});
		});
		function populatePromo() {
			$('#promoid').combobox({
				valueField:'id',
				textField:'no_promo',
				data:promo_header,
				prompt:'-Please Select-',
				validType:'inList["#promoid"]',
				onChange:function(newValue, oldValue){
					if(newValue==="") return
					for(var p=0;p<promo_header.length;p++){
						console.log("looping ",p)
						if(parseInt(promo_header[p].id)===parseInt(newValue)){
							var l = $('.paymenttipe').length; var jgn_lanjut = false;
							for (var i = 0; i < l; i++){
								var id_bayar_tipe = $('.paymenttipe').eq(i).val();
								if(id_bayar_tipe==="1"){
									$.messager.alert("Error","Promo tidak bisa di pakai dengan pembayaran cash");
									$(this).combobox('setValue','')
									jgn_lanjut = true;
									break;
                }
							}
							if(jgn_lanjut) break;

							header.promoid = promo_header[p].id;
							var ph = promo_header[p];
							console.log("from",new Date(ph.active_from))
							console.log("to",new Date(ph.active_to))
							console.log("heade",new Date(header.doc_date))
							if(new Date(ph.active_from) <= new Date(header.doc_date) && new Date(ph.active_to) >= new Date(header.doc_date)){
								for(var di=0; di<detail_item.length; di++){
									detail_item[di].disc2_persen = ph.discount;
									detail_item[di].disc2_amount =  (parseFloat(detail_item[di].unit_price)-parseFloat(detail_item[di].disc1_amount))* detail_item[di].disc2_persen/100;
									detail_item[di].disc_total = detail_item[di].disc1_amount+detail_item[di].disc2_amount
									detail_item[di].sales_before_ppn = Math.round((detail_item[di].net_unit_price*detail_item[di].qty_order)/1.1);
									detail_item[di].sales_after_ppn = detail_item[di].net_unit_price*detail_item[di].qty_order
									detail_item[di].net_total_price = detail_item[di].unit_price-detail_item[di].disc_total
									detail_item[di].jumlah_hpp = detail_item[di].hpp*detail_item[di].qty_order*detail_item[di].convertion
								}
								setTimeout(function () {
									$('#dg').datagrid('loadData',detail_item);
									hitungHeader()
								},100)
							}else{
								$.messager.show({title: 'Error', msg: "Promo sudah kadaluarsa"});
								setTimeout(function () {
									$("#promoid").combobox('clear');
								},100)
							}

							break;
						}
					}
				}
			});
		}

		function populatePayment(id,id2, ctr) {
			var promo = $('#promoid').combobox('getValue');
			console.log(promo)
      var paymentsaring = [];
			for(var i=0; i<paymenttipe.length; i++){
				if(promo!==""){
					console.log("masuk sini")
					if(paymenttipe[i].description==="CASH") continue;
				}
				paymentsaring.push(paymenttipe[i])
			}
			$('#'+id).combobox({
				valueField:'id',
				textField:'description',
				data:paymentsaring,
				prompt:'-Please Select-',
				validType:'inList["#'+id+'"]',
				onChange:function (newValue, oldValue) {
					var promo1 = $('#promoid').combobox('getValue');
					if(newValue==="") return
					console.log("promo1",promo1,newValue);
					if(promo1!=="" && newValue==="1"){
						$.messager.alert("Error", "Type Pembayaran tidak bisa di pakai untuk promo ini")
						$(this).combobox('setValue','')
					}
					$('#'+id2).textbox({required:newValue!=="1"});
				}
			});
		}
		function remove(index) {
			detail_item = detail_item.filter(item => item.idlocal !== index)
			setTimeout(function () {
				$('#dg').datagrid('loadData',detail_item);
				hitungHeader()
			},100)
		}
		function hitungHeader() {
			var bruto = 0;
			var disc = 0;
			var nett = 0;
			for(var i=0; i<detail_item.length;i++){
				bruto += parseFloat(detail_item[i].qty_order)*parseFloat(detail_item[i].unit_price)
				disc += parseFloat(detail_item[i].qty_order)*parseFloat(detail_item[i].disc_total)
				nett += parseFloat(detail_item[i].sales_after_ppn)
			}
			var l = $('.nilai_bayar').length; var result = [];
			for (var i = 0; i < l; i++) result.push(parseFloat($('.nilai_bayar').eq(i).val()));
			var total = 0;
			for (var i = 0; i < result.length; i++) total += isNaN(result[i])?0:result[i];
			header.payment_sum = total;
			header.payment_ret = total-nett;

			header.gross_sales = bruto;
			header.total_discount = disc;
			header.sales_after_tax = nett;
			console.log(header)
			$('#fm').form('load', header);
		}

		function printForm(){
			$.ajax({
				type:"POST",
				url:base_url+'showroom/print_so/'+header.docno,
				dataType:"json",
				success:function(result){
					header.jumlah_print = parseInt(header.jumlah_print)+1;
					$('#fm').form('load', header);
					console.log(result.data)
				}
			});
		}
		function submitForm(){
			if(detail_item.length===0){
				$.messager.alert("Error","Detail harus di input telebih dahulu")
				return
			}
			if(counter===0){
				$.messager.alert("Error","Payment harus di isi")
				return
      }
			var values = {};
			values['header'] = header;
			values['detailitem'] = {};
			for(var i=0;i<detail_item.length; i++){
				values['detailitem'][i] = detail_item[i]
			}
			$.redirectFormValues("<?php echo base_url('showroom/entryp')?>","#fm",values,"post","")
		}
		function batalForm() {
      myConfirm("Batal Transaction","Are you sure?","Yes","No", function (res) {
        if(res==="Yes"){
					inputReason("Note","Input Keterangan batal : ", function (keterangan) {
						var values = {};
            header.remark = keterangan;
            header.status = "BATAL";
						values['header'] = header;
						values['detailitem'] = {};
						for(var i=0;i<detail_item.length; i++){
							detail_item[i].status_detail = "BATAL";
							values['detailitem'][i] = detail_item[i]
						}
						$.redirectFormValues("<?php echo base_url('showroom/entryp')?>","#fm",values,"post","")
					})
        }
			})
		}

		function removeItem(baris, idd) {
			if(idd>0){
				myConfirm("Alert","Anda yakin ingin hapus?","Ya","Tidak",function (r) {
					if(r==="Ya"){
						var values = {};
						values['id_det'] = idd;
						values['id_head'] = id;
						$.redirect(base_url+"showroom/delete_detail/"+idd,values,"GET","")
					}
				})
			}else{
				$('#detailBaris'+baris).remove();
			}
		}

		var counter = 0;
		function addDetail(e) {
			if(detail_item.length===0){
				$.messager.alert("Error","Detail harus di input telebih dahulu")
        return
      }
			counter++;
			var d = {
				id: e === null ? 0 : e.id === null ? 0 : e.id,
				paymenttypeid: e === null ? '' : e.paymenttypeid === null ? '' : e.paymenttypeid,
				trx_no: e === null ? header.docno : e.trx_no === null ? header.docno : e.trx_no,
				keterangan: e === null ? '' : e.keterangan === null ? '' : e.keterangan,
				nilai_bayar: e === null ? '' : e.nilai_bayar === null ? '' : e.nilai_bayar,
			}
			var html =' ' +
				'<div id="detailBaris'+counter+'" >' +
				'	<div> ' +
				'		<div style="float:left; width: 35%; padding-right: 2px;">' +
				'		 	<input name="detail['+counter+'][id]" id="detail['+counter+'][id]" type="hidden" value="'+d.id+'" > ' +
				'		 	<input name="detail['+counter+'][trx_no]" id="detail['+counter+'][trx_no]" type="hidden" value="'+d.trx_no+'" > ' +
				'     <input name="detail['+counter+'][paymenttypeid]" id="paymenttypeid'+counter+'" value="'+d.paymenttypeid+'" class="paymenttipe easyui-combobox easyui-combobox'+counter+'" labelPosition="top" tipPosition="bottom" placeholder="Type:" style="width:100%">' +
				'		</div> ' +
				'		<div style="float:left; width: 25%; padding-right: 2px;">' +
				'		 	<input name="detail['+counter+'][keterangan]" id="keterangan'+counter+'" value="'+d.keterangan+'" class="easyui-textbox'+counter+'" labelPosition="top" tipPosition="bottom" placeholder="Document" style="width:100%;"> ' +
				'		</div>' +
				'		<div style="float:left; width: 35%; padding-right: 2px;">' +
				'				<input value="'+d.nilai_bayar+'" name="detail['+counter+'][nilai_bayar]" id="nilai_bayar'+counter+'" class="nilai_bayar easyui-numberbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" style="width:100%"> ' +
				'		</div>' +
				'		<div style="float:left; width: 5%;">' +
				' 		<a href="javascript:void(0)" class="easyui-linkbutton'+counter+'" iconCls="icon-clear" onclick="removeItem('+counter+', '+d.id+')" ></a> ' +
				'		</div> ' +
				'	</div>' +
				'</div>';
			$("#detail").append(html);
			$(".easyui-textbox"+counter).textbox();
			$(".easyui-numberbox"+counter).numberbox({groupSeparator:',', decimalSeparator:'.'});
			$(".easyui-combobox"+counter).combobox();
			$(".easyui-checkbox"+counter).checkbox();
			$(".easyui-linkbutton"+counter).linkbutton();
			populatePayment('paymenttypeid'+counter,'keterangan'+counter, counter)
      if(d.nilai_bayar==='') {
				var sls = $("#sales_after_tax").numberbox('getValue');
				var pay = $("#payment_sum").numberbox('getValue');
				var rem = parseFloat(sls) - parseFloat(pay)
				$("#nilai_bayar" + counter).numberbox('setValue', rem)
				hitungHeader();
			}
			$('#nilai_bayar'+counter).numberbox({
				onChange:function (newValue, oldValue) {
					console.log("onChange HPP1",newValue, oldValue)
					var tipe = $("#paymenttypeid"+counter).combobox('getValue');
					if(tipe!=="1"){
						if(parseFloat(bayar)<=50000){
							$.messager.show({title: 'Warning', msg: "Minimal belanja harus lebih dari 50 ribu"});
							$("#nilai_bayar"+counter).textbox('setValue',"")
              return;
						}
          }
          hitungHeader();
				}
			})

//			$("#nilai_bayar"+counter).textbox('textbox').bind('keydown', function(e){
//				if(e.key==='Enter' || e.keyCode===13){ 	// when press ENTER key, accept the inputed value.
//					var bayar = $(this).val();
//					var tipe = $("#paymenttypeid"+counter).combobox('getValue');
//					if(tipe!=="1"){
//						if(parseFloat(bayar)<=50000){
//							$.messager.show({title: 'Warning', msg: "Minimal belanja harus lebih dari 50 ribu"});
//							$("#nilai_bayar"+counter).textbox('setValue',"")
//						}
//					}
//					hitungHeader();
//				}
//			});
		}

  </script>
</div>
