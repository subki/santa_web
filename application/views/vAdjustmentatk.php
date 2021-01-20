<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";  
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/adjustmentatk.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
        <div id="toolbar">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addnew()">New</a>
             
        </div>
    </div>
</div>
<div id="modal_edit" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:50%;height:500px;padding:20px;"> 
    <form action="" id="form_editing" name="frm2" class="frm2" method="POST">
     <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div style="width: 100%; padding: 10px;">
            <div style="margin-bottom:1px">
                <div id="docnoheader" style="float:left; width: 100%; padding-right: 5px;">
                    <input name="docno" id="docno" class="easyui-textbox docno" labelPosition="top" tipPosition="bottom"
                           label="Nomor Trx:" readonly style="width:100%">
                </div>
                <div id="outlet_code" style="float:left; width: 100%; padding-right: 5px;"> 
                    <select name="location_code" id="location_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Lokasi:" style="width:100%;">
                    </select>
                </div>
                <div style="float:left; width: 100%; padding-right: 5px;">
                   <input name="doc_date" id="doc_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                           required="true" label="Trx. Date:" style="width:100%">
                </div>
                <div style="float:left; width: 100%; padding-right: 5px;">
                    <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                       required="true" label="Keterangan:" style="width:100%; height: 100px;">
                </div>
            </div>      
        </div> 
        <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
    </div> 
    <div style="margin-bottom:20px"> 
        <a href="javascript:void(0)" id="btn_simpan"  class="easyui-linkbutton btn_simpan" onclick="submit()" iconCls="icon-save"  style="width:90px; height: 20px;">Simpan</a> </a>
    </div>  
    </form> 
</div>  

<div id="modal_edit_detail" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:50%;height:500px;padding:20px;"> 
    <form action="" id="form_editing_detail" name="frm2" class="frm2" method="POST">
     <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <div style="width: 100%; padding: 10px;">
            <div style="margin-bottom:1px">
                <div id="docnoheader" style="float:left; width: 100%; padding-right: 5px;"> 
                    <input name="docno_id" id="docno_id" class="easyui-textbox docno_id" labelPosition="top" tipPosition="bottom"
                           label="Nomor Trx:" readonly style="width:100%">
                </div>
                <div id="Product" style="float:left; width: 100%; padding-right: 5px;"> 
                    <input name="sku" id="sku" class="easyui-textbox sku" labelPosition="top" tipPosition="bottom"
                           label="Product:" readonly style="width:100%">
                    <input name="skucode" id="skucode" class="easyui-textbox skucode" labelPosition="top" tipPosition="bottom"
                           label="Product Code:" readonly style="width:100%">
                           <a href="javascript:void(0)" id="btn_simpan"  class="easyui-linkbutton btn_simpan" onclick="addnewsku()" iconCls="icon-search"  style="width:90px; height: 20px;">Find</a> </a> 
                </div>
                <div style="float:left; width: 100%; padding-right: 5px;">
                    <input name="soh" id="soh" class="easyui-textbox soh" labelPosition="top" tipPosition="bottom"
                           label="Qty On Hand:" readonly style="width:100%"> 
                </div>
                <div style="float:left; width: 100%; padding-right: 5px;">
                    <input name="adjust" id="adjust" class="easyui-textbox adjust" labelPosition="top" tipPosition="bottom"
                           label="Qty Adjustment:" style="width:100%"> 
                </div>
                <div style="float:left; width: 100%; padding-right: 5px;">
                    <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                       required="true" label="Keterangan:" style="width:100%; height: 100px;">
                </div>
            </div>      
        </div> 
        <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
    </div> 
    <div style="margin-bottom:20px"> 
        <a href="javascript:void(0)" id="btn_simpan"  class="easyui-linkbutton btn_simpan" onclick="submit_detail()" iconCls="icon-save"  style="width:90px; height: 20px;">Simpan</a> </a>
    </div>  
    </form> 
</div>  

<div id="modal_edit_detail_sku" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:50%;height:500px;padding:20px;"> 
    <form class="form-horizontal" id="form_editing_detail_sku"> 
        <table id="tt_sku" title="List SKU" class="easyui-datagrid" style="width:100%;height:400px">
        </table>
    </form> 
</div>  


<div id="modal_adj_stockopname" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:80%;height:500px;padding:20px;"> 
       <form action="" id="form_stockadjopname" name="frm2" class="frm2" method="POST">
         <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
            <div style="width: 100%; padding: 10px;">
                <div style="margin-bottom:1px">
                    <div id="docnoheader" style="float:left; width: 100%; padding-right: 5px;">
                        <input name="docno_idopn" id="docno_idopn" class="easyui-textbox docno_idopn" labelPosition="top" tipPosition="bottom"
                               label="Nomor Trx:" readonly style="width:100%">
                    </div> 
                    <div id="Product" style="float:left; width: 100%; padding-right: 5px;"> 
                        <input name="skuopn" id="skuopn" class="easyui-textbox skuopn" labelPosition="top" tipPosition="bottom"
                               label="Product:" readonly style="width:100%">
                        <input name="skucodeopn" id="skucodeopn" class="easyui-textbox skucodeopn" labelPosition="top" tipPosition="bottom"
                               label="Product Code:" readonly style="width:100%">
                               <a href="javascript:void(0)" id="btn_simpan"  class="easyui-linkbutton btn_simpan" onclick="addOpn()" iconCls="icon-search"  style="width:250px; height: 20px;float:right;">Find Opname</a> </a> 
                    </div> 
                    <div style="float:left; width: 100%; padding-right: 5px;">
                        <input name="sohopn" id="sohopn" class="easyui-textbox sohopn" labelPosition="top" tipPosition="bottom"
                               label="Qty On Hand:" readonly style="width:100%"> 
                    </div>
                    <div style="float:left; width: 100%; padding-right: 5px;">
                        <input name="adjustopn" id="adjustopn" class="easyui-textbox adjustopn" labelPosition="top" tipPosition="bottom"
                               label="Qty Adjustment:" style="width:100%"> 
                    </div>
                    <div style="float:left; width: 100%; padding-right: 5px;">
                        <input name="remarkopn" id="remarkopn" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                           required="true" readonly label="Keterangan:" style="width:100%; height: 100px;">
                    </div>
                </div>      
            </div> 
            <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
        </div> 
        <div style="margin-bottom:20px"> 
            <a href="javascript:void(0)" id="btn_simpan"  class="easyui-linkbutton btn_simpan" onclick="submitadjopn()" iconCls="icon-save"  style="width:90px; height: 20px;">Simpan</a> </a>
        </div>  
        </form> 
</div>  

<div id="modal_detailOpname" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:80%;height:500px;padding:20px;"> 
    <form class="form-horizontal" id="form_opname"> 
        <table id="tt_opn" title="List Opname" class="easyui-datagrid" style="width:100%;height:400px">
        </table>
    </form> 
</div>  

