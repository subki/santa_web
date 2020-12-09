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
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
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
    <a href="<?php echo base_url('promo')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
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
        <div style="width: 50%; padding: 10px;">
          <div style="margin-bottom:1px">
            <input name="id" id="id" type="hidden" value="<?php echo isset($id)?$id:'0'; ?>">
            <div style="margin-bottom:1px">
              <div style="float:left; width: 50%; padding-right: 5px;">
                <input name="active_from" id="active_from" class="easyui-datebox" labelPosition="top" tipPosition="bottom" label="Active From:" style="width:100%">
              </div>
              <div style="float:left; width: 50%; padding-right: 5px;">
                <input name="active_to" id="active_to" class="easyui-datebox" labelPosition="top" tipPosition="bottom" label="Active To:" style="width:100%">
              </div>
            </div>
          </div>
          <div style="margin-bottom:1px">
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="no_promo" id="no_promo" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="No. Promo:" style="width:100%">
            </div>
            <div style="float:left; width: 50%; padding-right: 5px;">
              <input name="discount" id="discount" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" label="Discount :" style="width:100%">
            </div>
          </div>
          <div style="margin-bottom:1px">
              <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Remark:" style="width:100%; height: 100px;">
          </div>
        </div>
        <div style="width: 50%; padding: 10px;">
          <div id="detail">
            <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: flex-end">
              <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="addDetail(null)" >Add</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  var detail = <?php echo json_encode($detail);?>;
  var item = <?php echo json_encode($item);?>;
  $(document).ready(function () {
  	if(item.active_from!==undefined){
  		item.active_from = formattanggal(item.active_from,null)
    }
  	if(item.active_to!==undefined){
  		item.active_to = formattanggal(item.active_to,null)
    }
		$('#fm').form('load',item);
		for(var i=0; i<detail.length; i++){
			addDetail(detail[i])
		}
	})
	function submitForm(){
  	$.redirectForm("<?php echo base_url('promo/entryp/'.$aksi)?>","#fm","post","")
  }

	function removeItem(baris, idd) {
		if(idd>0){
			myConfirm("Alert","Anda yakin ingin hapus?","Ya","Tidak",function (r) {
				if(r==="Ya"){
					var values = {};
					values['id_det'] = idd;
					values['id_head'] = id;
					$.redirect(base_url+"promo/delete_detail/"+idd,values,"GET","")
				}
			})
		}else{
			$('#detailBaris'+baris).remove();
		}
	}

	var counter = 0;
	function addDetail(e) {
		counter++;
		var d = {
			id: e === null ? 0 : e.id === null ? 0 : e.id,
			promoid: e === null ? '' : e.promoid === null ? '' : e.promoid,
			prefix: e === null ? '' : e.prefix === null ? '' : e.prefix,
			disc: e === null ? '' : e.disc === null ? '' : e.disc,
			status: e === null ? '' : e.status === null ? '' : e.status,
		}
		var html =' ' +
			'<div id="detailBaris'+counter+'" >' +
			'	<div> ' +
			'		<div style="float:left; width: 50%; padding-right: 5px;">' +
			'		 	<input name="detail['+counter+'][id]" id="detail['+counter+'][id]" type="hidden" value="'+d.id+'" > ' +
			'		 	<input name="detail['+counter+'][promoid]" id="detail['+counter+'][promoid]" type="hidden" value="'+d.promoid+'" > ' +
			'		 	<input name="detail['+counter+'][prefix]" id="detail['+counter+'][prefix]" value="'+d.prefix+'" class="easyui-textbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" style="width:100%;"> ' +
			'		</div> ' +
			'		<div style="float:left; width: 30%;; padding-right: 5px;">' +
			'				<input value="'+d.disc+'" name="detail['+counter+'][disc]" id="disc'+counter+'" class="easyui-numberbox'+counter+'" labelPosition="top" tipPosition="bottom" required="true" style="width:100%"> ' +
			'		</div>' +
			'		<div style="float:left; width: 20%;">' +
			' 		<a href="javascript:void(0)" class="easyui-linkbutton'+counter+'" iconCls="icon-remove" onclick="removeItem('+counter+', '+d.id+')" ></a> ' +
			'		</div> ' +
			'	</div>' +
			'</div>';
		$("#detail").append(html);
		$(".easyui-textbox"+counter).textbox();
		$(".easyui-numberbox"+counter).numberbox();
		$(".easyui-combobox"+counter).combobox();
		$(".easyui-checkbox"+counter).checkbox();
		$(".easyui-linkbutton"+counter).linkbutton();
	}
</script>
