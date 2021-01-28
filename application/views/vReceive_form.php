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
    var supplier_code= "<?php echo $supplier_code; ?>";
    var supplier_name= "<?php echo $supplier_name; ?>"; 
    
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/receive_form.js"></script>

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
        border-width: 2px !important;
    }
</style>
<div id="tt">
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <a href="<?php echo base_url('Poreceiving')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
        <a href="javascript:void(0)" id="new" class="easyui-linkbutton" iconCls="icon-save" onclick="addform('')" style="width:90px; height: 20px;">NEW</a>
        <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Save</a>
        <a href="javascript:void(0)" id="update" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('Update')" style="width:90px; height: 20px;">Update</a>
      <!--   <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-posting" onclick="submit('On Order')" style="width:90px; height: 20px;">Approve</a> -->
        <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-close" onclick="submit_cancel()" style="width:90px; height: 20px;">Cancel</a>
        <a href="javascript:void(0)" id="print" class="easyui-linkbutton" iconCls="icon-print" onclick="printSO()" style="width:90px; height: 20px;">Print</a>
       <!--  <a href="javascript:void(0)" id="customer" class="easyui-linkbutton" iconCls="icon-customer" onclick="showSupplier()" style="width:90px; height: 20px;">Supplier</a> 
        <a href="javascript:void(0)" id="info" class="easyui-linkbutton" iconCls="icon-info" onclick="infoData()" style="width:90px; height: 20px;">Info</a>-->
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

                <div style="width: 38%; padding: 10px;" class="border-kotak"> 
                    <div style="margin-bottom:1px">

                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 90%;">
                                <div style="margin-bottom:1px;">
                                    <input name="supplier_code" id="supplier_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Supplier:" style="width:100%"> 
                                </div>
                                <input name="supplier_name" id="supplier_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" label="" style="width:100%">
                            </div>
                        </div>
                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 90%;">
                                <div style="margin-bottom:1px;">
                                    <input name="po_no" id="po_no" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="PO No:" label="" style="width:100%">
                                </div> 
                            </div>
                          <!--   <div style="margin-bottom:1px; display: none">
                                <input name="regency_id" id="regency_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       readonly="true" label="" style="width:100%">
                            </div> -->
                            <!-- <div style="float:left; width: 50%; padding-right: 5px;">
                                <input name="regency_name" id="regency_name" class="easyui-combogrid" labelPosition="top" tipPosition="bottom"  label="Kabupaten:" style="width:100%">
                            </div> -->
                        </div>
<!-- 
                        <div style="margin-bottom:1px;">
                            <input name="pkp" id="pkp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="false" readonly="true" label="PKP:" style="width:100%">
                        </div> -->
 
                      <div style="margin-bottom:1px"> 
                            <!-- <div style="float:left; width: 50%; padding-right: 5px;">
                               <input name="lokasi_stock" id="lokasi_stock" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Gudang:" style="width:100%">
                            </div>  --> 
                        </div>
                       <!--  <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
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
                        </div> -->
                        <div style="float:left; width: 50%; padding-right: 5px; display: none;">
                            <input readonly="true" name="store_code" id="store_code" value="<?php echo $this->session->userdata('store_code'); ?>" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" style="width:100%">
                        </div>
                        <div style="float:right; width: 50%; padding-left: 5px; display: none;">
                            <input readonly="true" name="location_code" id="location_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Gudang:" style="width:100%">
                        </div>
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="po_typename" id="po_typename" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Po Type:" style="width:100%">
                        </div>
                        <div style="float:left; width: 50%; padding-right: 5px; display: none;">
                           <input name="po_type" id="po_type" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" style="width:100%">

                        </div>
                        <div style="float:left; width: 50%; padding-right: 5px;">
                             <input name="do_no" id="do_no" class="easyui-textbox" required="true" labelPosition="top" tipPosition="bottom" label="DO No:" style="width:100%">
                        </div>  
                        <div style="float:left; width: 50%; padding-right: 5px;">
                           <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               required="true" label="Keterangan:" style="width:100%;">
                        </div> 
                    </div>
                </div>
                <div style="width: 38%; padding: 10px;" class="border-kotak">
                    <div style="margin:5px;">  
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="docno" id="docno" value="<?php echo $docno; ?>" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="No. Po:" style="width:100%">
                        </div>  
                         <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="trx_date" id="trx_date" class="easyui-datebox"  value="<?php echo $tgl;?>"   required="true" labelPosition="top" tipPosition="bottom" label="Trx Date:" style="width:100%">
                        </div>
                    </div>
                    <div style="margin:5px;">  
                        <div style="float:left; width: 25%; padding-right: 5px;">
                           <input name="currency" id="currency" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="#Currency:" style="width:100%">
                        </div>  
                         <div style="float:left; width: 25%; padding-right: 5px;">
                              <input name="rate" id="rate" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Rate:" style="width:100%">
                        </div>
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="status_po" id="status_po" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label="Status:" style="width:100%">
                        </div>
                        <div style="float:right; width:10%;">
                            <input name="jumlah_print" id="jumlah_print" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label="#Print" style="width:100%">
                        </div>
                    </div>
                    <div style="margin:5px;">
                         <div style="float:left; width: 40%; padding-right: 5px;">
                               <input name="po_date" id="po_date" class="easyui-datebox" required="true" labelPosition="top" tipPosition="bottom" label="Po Date:" style="width:100%">
                        </div>
                         <div style="float:left; width: 40%; padding-right: 5px;"> 
                            <input name="expired_date" id="expired_date" class="easyui-datebox" required="true" labelPosition="top" tipPosition="bottom" label="Expired Date:" style="width:100%">
                         </div>
                    </div> 
                    <div style="margin:5px;">
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="tot_qty_order" id="tot_qty_order" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Total Qty Order:" style="width:100%; text-align: right;">
                        </div>
                        <div style="float:left; width: 25%; padding-right: 5px;">
                            <input name="tot_item" id="tot_item" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Total Order:" style="width:100%; text-align: right;">
                        </div>
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="tot_item_recv" id="tot_item_recv" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Total Item Receive:" style="width:100%; text-align: right;">
                        </div>
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="tot_qty_recv" id="tot_qty_recv" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Total Qty Receive:" style="width:100%; text-align: right;">
                        </div>
                    </div>   
                    </div> 
                    <div style="margin:5px;">
                    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                             
                    </div> 
                        <div style="margin-bottom:1px; display: none">
                            <input name="reason" id="reason" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Reason:" style="width:100%">
                        </div>
                    </div>
                <div style="width: 20%; padding: 10px;" class="border-kotak"> 
                    <div style="margin-bottom:1px">
                        <input name="tot_disc" id="tot_disc" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Disc:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="tot_ppn" id="tot_ppn" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="PPN:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="total_recv" id="total_recv" class="easyui-textbox textbox-text-number" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Subtotal:" style="width:100%; text-align: right;">
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

<div id="modal_edit" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:50%;height:500px;padding:20px;"> 
    <form action="" id="form_editing" name="frm2" class="frm2" method="POST">
     <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">  
        <div style="width: 100%; padding: 10px;">
            <div style="margin-bottom:1px">
                <div id="docnoheader" style="float:left; width: 100%; padding-right: 5px;"> 
                    <input name="trx_no" id="trx_no" class="easyui-textbox trx_no" labelPosition="top" tipPosition="bottom"
                           label="No. Trx:" readonly style="width:100%">
                </div>
                <div style="float:left; width: 100%; padding-right: 5px;display: none"> 
                    <input name="datetrx" id="datetrx" class="easyui-textbox datetrx" labelPosition="top" tipPosition="bottom" readonly style="width:100%">
                    <input name="store_code" id="store_code" value="<?php echo $this->session->userdata('store_code'); ?>" class="easyui-textbox store_code" labelPosition="top" tipPosition="bottom" readonly style="width:100%">
                    <input name="seqno" id="seqno" class="easyui-textbox seqno" labelPosition="top" tipPosition="bottom" readonly style="width:100%">
                    <input name="po_nodetail" id="po_nodetail" class="easyui-textbox po_nodetail" labelPosition="top" tipPosition="bottom" readonly style="width:100%">
                    <input name="supplier_id" id="supplier_id" class="easyui-textbox supplier_id" labelPosition="top" tipPosition="bottom" readonly style="width:100%"> 
                    <div style="float:left; width: 50%; padding-right: 5px;">
                        <input name="discdetail" id="discdetail" class="easyui-textbox discdetail" labelPosition="top" tipPosition="bottom"
                               label="Disc%:" style="width:100%"> 
                    </div>
                    <div style="float:left; width: 50%; padding-right: 5px;">
                        <input name="ppndetail" id="ppndetail" class="easyui-textbox ppndetail" labelPosition="top" tipPosition="bottom"
                               label="PPN%:" style="width:100%"> 
                    </div>    
                </div>
                <div id="Product" style="float:left; width: 100%; padding-right: 5px;"> 
                    <input name="sku" id="sku" class="easyui-textbox sku" labelPosition="top" tipPosition="bottom"
                           label="Product:" readonly style="width:100%">
                    <input name="uom" id="uom" class="easyui-textbox uom" labelPosition="top" tipPosition="bottom"
                           label="UOM:" readonly style="width:100%">
                    <input name="skucode" id="skucode" class="easyui-textbox skucode" labelPosition="top" tipPosition="bottom"
                           label="Product Code:" readonly style="width:100%">
                           <a href="javascript:void(0)" id="btn_simpan"  class="easyui-linkbutton btn_simpan" onclick="addnewsku()" iconCls="icon-search"  style="width:90px; height: 20px;">Find</a> </a> 
                </div>
                <div style="float:left; width: 50%; padding-right: 5px;">
                    <input name="qty_order" id="qty_order" class="easyui-textbox qty_order" labelPosition="top" tipPosition="bottom"
                           label="Qty Order:" style="width:100%"> 
                </div>
                <div style="float:left; width: 50%; padding-right: 5px;">
                    <input name="qty_receive" id="qty_receive" class="easyui-textbox qty_order" labelPosition="top" tipPosition="bottom"
                           label="Qty Receive:" style="width:100%"> 
                </div>
                <div style="float:left; width: 50%; padding-right: 5px;">
                    <input name="unit_price" id="unit_price" class="easyui-textbox unit_price" labelPosition="top" tipPosition="bottom"
                           label="Unit Price:" style="width:100%"> 
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
<div id="modal_edit_detail_sku" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:60%;height:500px;padding:20px;"> 
    <form class="form-horizontal" id="form_editing_detail_sku"> 
        <table id="tt_sku" title="List SKU" class="easyui-datagrid" style="width:100%;height:400px">
        </table>
    </form> 
</div>  

<script type="text/javascript">
    
    $("#docno").textbox({
        inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
            focus: function(e){ 
                var t = $(e.data.target);
                var v = t.textbox('getValue');
                if (v){t.textbox('setText', v);}
            },
            blur: function(e){
                var t = $(e.data.target);
               console.log(t)
                var v = t.textbox('getValue');
                if (v){
                    if(v.length>6){
                        var seri = v.substring(0, 2)+"."+v.substring(2,4)+"."+v.substring(4,10);
                        t.textbox('setText', seri)
                    }else t.textbox('setText', v);
                }
            }
        })
    })  
 
  
</script>