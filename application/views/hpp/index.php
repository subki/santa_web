<script type="text/javascript">
	var base_url="<?php echo base_url();?>";
	var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
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
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
<div id="tt">
  <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton headbutton" iconCls="icon-undo" onclick="cancelForm()" style="width:90px; height: 20px;">Cancel</a>
    <a href="javascript:void(0)" id="submit" class="easyui-linkbutton headbutton" iconCls="icon-save" onclick="submitForm()" style="width:90px; height: 20px;">Submit</a>
    <a href="javascript:void(0)" id="hitung" class="easyui-linkbutton headbutton" iconCls="icon-reload" onclick="hitungHPP()" style="width:120px; height: 20px;">Hitung HPP</a>
  </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
  <div id="p" data-options="region:'west'" style="width:55%;">
    <table id="dg" title="<?php echo $item->article_code." | ".$item->article_name; ?>" class="easyui-datagrid" style="width:100%;height: 90%">
    </table>
  </div>
  <div id="p" data-options="region:'east'" style="width:45%;">
    <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
         style="width:100%;height:100%;background:#fafafa;"
         data-options="iconCls:'rgb',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
      <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
        <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
          <div style="width: 100%; padding: 10px;">
            <div style="margin-bottom:1px">
              <div style="float:left; width: 60%; padding-right: 5px;">
                <input name="effdate" id="effdate" class="easyui-datebox" labelPosition="top" tipPosition="bottom" label="Effective Date:" style="width:100%">
              </div>
              <div style="float:right; width:40%;">
              </div>
            </div>
            <div style="margin-bottom:1px">
              <div style="float:left; width: 50%; padding-right: 5px;">
                <input name="id" id="id" type="hidden" value="<?php echo isset($id)?$id:'0'; ?>">
                <input name="opsi" id="opsi" labelPosition="top" tipPosition="bottom" label="Opsi:" style="width:100%">
              </div>
              <div style="float:right; width:50%;">
                <input name="tipe" id="tipe" labelPosition="top" tipPosition="bottom" label="Tipe:" style="width:100%">
              </div>
            </div>
            <div style="margin-bottom:1px">
              <input name="keterangan" id="keterangan" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Keterangan:" style="width:100%; height: 100px;">
            </div>
            <div style="display:inline-block; width:100%; height:2px; margin-bottom: 15px; margin-top: 5px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"><b>HPP 1</b></div>
            <div style="margin-bottom:1px" class="opsi2">
              <div style="float:left; width: 30%; padding-right: 5px;">
                <input name="product_qty" id="product_qty" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Qty Barang Jadi:" style="width:100%">
              </div>
              <div style="float:left; width: 30%; padding-right: 5px;">
                <input name="product_price" id="product_price" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Harga Barang Jadi:" style="width:100%">
              </div>
              <div style="float:left; width: 20%; padding-right: 5px;">
                <input name="disc1_persen" id="disc1_persen" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Disc 1 %:" style="width:100%">
                <input name="disc1_amt" id="disc1_amt" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" tipPosition="bottom" style="width:100%">
              </div>
              <div style="float:right; width:20%;">
                <input name="disc2_persen" id="disc2_persen" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Disc 2 %:" style="width:100%">
                <input name="disc2_amt" id="disc2_amt" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" tipPosition="bottom" style="width:100%">
              </div>
            </div>
            <div style="margin-bottom:1px" class="opsi2">
              <div style="float:left; width: 50%; padding-right: 5px;">
                <input name="product_amount" id="product_amount" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Total Harga Barang Jadi:" style="width:100%">
              </div>
              <div style="float:right; width:50%;">
                <input name="product_pcs" id="product_pcs" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Barang Jadi / Pcs:" style="width:100%">
              </div>
            </div>
            <div style="margin-bottom:1px">
              <div style="float:left; width: 33%; padding-right: 5px;">
                <input name="bom_pcs" id="bom_pcs" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="BOM:" style="width:100%">
              </div>
              <div style="float:left; width: 33%; padding-right: 5px;">
                <input name="foh_pcs" id="foh_pcs" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="FOH:" style="width:100%">
              </div>
              <div style="float:right; width:33%;">
                <input name="ongkos_jahit_pcs" id="ongkos_jahit_pcs" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Sewing:" style="width:100%">
              </div>
            </div>
            <div style="display:inline-block; width:100%; height:2px; margin-bottom: 15px; margin-top: 5px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"><b>HPP 2</b></div>
            <div style="margin-bottom:1px">
              <div style="float:left; width: 33%; padding-right: 5px;">
                <input name="buffer_cost" id="buffer_cost" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Buffer Cost %:" style="width:100%">
                <input name="buffer_cost_amt" id="buffer_cost_amt" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" tipPosition="bottom" style="width:100%">
              </div>
              <div style="float:left; width: 33%; padding-right: 5px;">
                <input name="interest_cost" id="interest_cost" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Interest Cost %:" style="width:100%">
                <input name="interest_cost_amt" id="interest_cost_amt" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" tipPosition="bottom" style="width:100%">
              </div>
              <div style="float:right; width:33%;">
              </div>
            </div>
            <div style="display:inline-block; width:100%; height:2px; margin-bottom: 15px; margin-top: 5px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"><b>HPP 2 + Ekspedisi</b></div>
            <div style="margin-bottom:1px">
              <div style="float:left; width: 50%; padding-right: 5px;">
                <input name="ekspedisi" id="ekspedisi" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="Ekspedisi:" style="width:100%">
              </div>
              <div style="float:left; width: 50%; padding-right: 5px;">
              </div>
            </div>
            <div style="display:inline-block; width:100%; height:2px; border-top:1px solid #ccc; border-bottom:1px solid #fff; vertical-align:middle;"></div>
            <div style="margin-bottom:1px; margin-top: 40px">
              <div style="float:left; width: 33%; padding-right: 5px;">
                <input name="hpp1" id="hpp1" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="HPP 1:" style="width:100%">
              </div>
              <div style="float:left; width: 33%; padding-right: 5px;">
                <input name="hpp2" id="hpp2" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="HPP 2:" style="width:100%">
              </div>
              <div style="float:right; width:33%;">
                <input name="hpp_ekspedisi" id="hpp_ekspedisi" class="easyui-numberbox" data-options="precision:2, groupSeparator:',', decimalSeparator:'.'" labelPosition="top" tipPosition="bottom" label="HPP + Ekspedisi:" style="width:100%">
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
<div id="toolbar" style="display: none">
  <a href="javascript:void(0)" class="easyui-linkbutton" id="add" onclick="addData()" iconCls="icon-add" plain="true">Add</a>
  <a href="javascript:void(0)" class="easyui-linkbutton" id="edit" onclick="editData()" iconCls="icon-edit" plain="true">Edit</a>
</div>

<script type="text/javascript">
	var options={
		method:"POST",
		url : base_url+"hpp/grid",
		pagePosition:"top",
		resizeHandle:"right",
		resizeEdge:10,
		pageSize:20,
		clientPaging: false,
		remoteFilter: true,
		rownumbers: false,
		pagination:true, striped:true, nowrap:false,
		sortName:"effdate",
		sortOrder:"desc",
		singleSelect:true,
		toolbar:"#toolbar",
		loadFilter: function(data){
			data.rows = [];
			if (data.data) data.rows = data.data;
			return data;
		},
		columns:[[
			{field:"opsi",   title:"Opsi",      sortable: true},
			{field:"tipe",   title:"Tipe",      sortable: true},
			{field:"effdate",   title:"Eff Date",      sortable: true},
			{field:"keterangan",   title:"Remark",      sortable: true},
			{field:"hpp1",   title:"HPP",    align:'right',  sortable: true, formatter:numberFormat},
			{field:"hpp2",   title:"HPP 2",  align:'right',  sortable: true, formatter:numberFormat},
			{field:"hpp_ekspedisi",   title:"Ekspedisi",    align:'right',  sortable: true, formatter:numberFormat}
		]],
		onLoadSuccess:function(){
			authbutton();
		},
	};

	var opsion = <?php echo json_encode($opsi);?>;
	var tipe = <?php echo json_encode($tipe);?>;
	var header = <?php echo json_encode($item);?>;
	var detail = {
		opsi:'',
		tipe:'',
		keterangan:'',
		article_code:'<?php echo json_encode($article_code)?>',
		effdate:'',
		product_qty:0,
		product_price:0,
		disc1_persen:0,
		disc1_amt:0,
		disc2_persen:0,
		disc2_amt:0,
		product_amount:0,
		product_pcs:0,
		bom_pcs:0,
		foh_pcs:0,
		ongkos_jahit_pcs:0,
		interest_cost:0,
		interest_cost_amt:0,
		buffer_cost:0,
		buffer_cost_amt:0,
		ekspedisi:0,
		hpp1:0,
		hpp2:0,
		hpp_ekspedisi:0
  };
	$(document).ready(function() {
		$('#dg').datagrid(options);
		$('#dg').datagrid('destroyFilter');
		$('#dg').datagrid('enableFilter');
		$('#dg').datagrid('addFilterRule', {
			field: 'article_code',
			op: 'equal',
			value: '<?php echo $article_code; ?>'
		});
		$('#dg').datagrid('doFilter');

		$('#opsi').combobox({
			valueField:'id',
			textField:'description',
			data:opsion,
			prompt:'-Please Select-',
			validType:'inList["#opsi"]',
			onSelect:function(rec){
				console.log("disini",rec)
				if(rec.id===1){
					$("#ekspedisi").numberbox({readonly:true})
          $(".opsi2").hide()
				}else if(rec.id===2){
					$("#ekspedisi").numberbox({readonly:false})
					$(".opsi2").show()
        }else if(rec.id===3){
					$("#ekspedisi").numberbox({readonly:false})
					$(".opsi2").hide()
        }
			}
		});
		$('#tipe').combobox({
			valueField:'id',
			textField:'description',
			data:tipe,
			prompt:'-Please Select-',
			validType:'inList["#tipe"]',
			onSelect:function(rec){
				console.log("disini",rec)
			}
		});
		$(".headbutton").hide();
	});

	function hitungHPP() {
		detail.opsi = $('#opsi').combobox('getValue');
		detail.tipe = $("#tipe").combobox('getValue');
		detail.keterangan = $("#keterangan").textbox('getValue');
		detail.effdate = $("#effdate").datebox('getValue');

		detail.product_qty = parseFloatt($("#product_qty").numberbox('getValue'));
		detail.product_price = parseFloatt($("#product_price").numberbox('getValue'));
		detail.disc1_persen = parseFloatt($("#disc1_persen").numberbox('getValue'));
		detail.disc1_amt = (detail.product_price*detail.disc1_persen/100)*-1;
		detail.disc2_persen = parseFloatt($("#disc2_persen").numberbox('getValue'));
		detail.disc2_amt = ((detail.product_price-detail.disc1_amt)*detail.disc2_persen/100)*-1;
		detail.product_amount = detail.product_price+detail.disc1_amt+detail.disc2_amt;
		detail.product_pcs = isNaN(detail.product_amount/detail.product_qty)?0:detail.product_amount/detail.product_qty;
		detail.bom_pcs = parseFloatt($("#bom_pcs").numberbox('getValue'));
		detail.foh_pcs = parseFloatt($("#foh_pcs").numberbox('getValue'));
		detail.ongkos_jahit_pcs = parseFloatt($("#ongkos_jahit_pcs").numberbox('getValue'));
		detail.hpp1 = detail.product_pcs+detail.bom_pcs+detail.foh_pcs+detail.ongkos_jahit_pcs;

		detail.interest_cost = parseFloatt($("#interest_cost").numberbox('getValue'))
		detail.interest_cost_amt = detail.hpp1*detail.interest_cost/100
		detail.buffer_cost = parseFloatt($("#buffer_cost").numberbox('getValue'))
		detail.buffer_cost_amt = detail.hpp1*detail.buffer_cost/100
		detail.hpp2 = detail.hpp1+detail.interest_cost_amt+detail.buffer_cost_amt;

		detail.ekspedisi = parseFloatt($("#ekspedisi").numberbox('getValue'));
		detail.hpp_ekspedisi = detail.hpp2+detail.ekspedisi;
		console.log(detail);
		$("#fm").form('load',detail);
	}

	function parseFloatt(val){
		if(isNaN(val) || val===null || val==="") return 0;
		return parseFloat(val);
  }

	function getRow() {
		var row = $('#dg').datagrid('getSelected');
		if (!row){
			$.messager.show({    // show error message
				title: 'Error',
				msg: 'Please select data to edit.'
			});
			return null;
		}else{
			row.record = $('#dg').datagrid("getRowIndex", row);
		}
		return row;
	}
	function submitForm() {
		var val = {};
		val['header'] = header;
		$.redirectFormValues(base_url+"hpp/entryp","#fm",val,"post","");
	}
	function cancelForm() {
		$(".headbutton").hide();
		$("#fm").form('clear')
	}
	function addData() {
		$(".headbutton").show();
		detail = {
			opsi:'',
			tipe:'',
			keterangan:'',
			effdate:'',
			product_qty:0,
			product_price:0,
			disc1_persen:0,
			disc1_amt:0,
			disc2_persen:0,
			disc2_amt:0,
			product_amount:0,
			product_pcs:0,
			bom_pcs:0,
			foh_pcs:0,
			ongkos_jahit_pcs:0,
			interest_cost:0,
			interest_cost_amt:0,
			buffer_cost:0,
			buffer_cost_amt:0,
			ekspedisi:0,
			hpp1:0,
			hpp2:0,
			hpp_ekspedisi:0
		}
		$("#fm").form('load',detail)
	}
	function editData() {
		var r = getRow();
		if(r===null) return;
		$(".headbutton").show();
		detail = r;
		console.log(detail);
		$("#fm").form('load',detail)
	}
</script>