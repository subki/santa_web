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
<!-- <div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 50%">
        </table>
    </div> 
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dgdetail" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table> 
    </div>
</div>  --> 

<div id="tt">
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <a href="<?php echo base_url('Somerge')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a> 
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

                <div style="width: 80%; padding: 10px;" class="border-kotak"> 
                    <div style="margin-bottom:1px"> 
                        <div style="margin-bottom:1px"> 
                            <div style="float:left; width: 50%; padding-right: 5px;">
                                 Base On Taking # 
                            </div> 
                            <div style="float:right; width: 50%; padding-left: 5px;">
                                : <?php echo $docno; ?>
                            </div>
                        </div>
                        <div style="margin-bottom:1px"> 
                            <div style="float:left; width: 50%; padding-right: 5px;">
                                 Location
                            </div> 
                            <div style="float:right; width: 50%; padding-left: 5px;">
                                : <?php echo $this->session->userdata('location_name'); ?>
                            </div>
                        </div>
                        <div style="margin-bottom:1px"> 
                            <div style="float:left; width: 50%; padding-right: 5px;">
                                Remark 
                            </div> 
                            <div style="float:right; width: 50%; padding-left: 5px;">
                                : <?php echo $remark; ?>
                            </div>
                        </div>    
                    </div>
                </div>
                <div style="width: 20%; padding: 10px;" class="border-kotak">
                     <div style="margin-bottom:1px"> 
                        <div style="margin-bottom:1px"> 
                            <div style="float:left; width: 50%; padding-right: 5px;">
                                 Status
                            </div> 
                            <div style="float:right; width: 50%;color: red; padding-left: 5px;">
                                : <?php echo $status; ?>
                            </div>
                        </div>   
                    </div>
                </div>  
            </div> 

            <div data-options="region:'west'" style="width:100%;">
                <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">

                
                <div style="width: 40%; padding: 10px;color: blue;"> 
                    <div style="margin-bottom:1px">
                        <div style="margin-bottom:1px"> 
                            <div style="float:left; width: 50%; padding-right: 5px;">
                                 Varience Plus
                            </div>  
                        </div> 
                        <div style="margin-bottom:1px">
                            <div style="margin-bottom:1px;">
                                <input  label="Total Qty Plus (PCS):" value="<?php echo number_format($total_itemplus,2); ?>"  class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                            <div style="margin-bottom:1px;">
                                <input  label="Total Net Retail Plus:" value="<?php echo number_format($total_netplus,2); ?>"   class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                            <div style="margin-bottom:1px;">
                                <input  label="Total HPP Plus:" value="<?php echo number_format($total_netplus,2); ?>"    class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                        </div>   
                    </div>
                </div>

                <div style="width: 40%; padding: 10px;color: red; "> 
                    <div style="margin-bottom:1px"> 
                        <div style="margin-bottom:1px"> 
                            <div style="float:left; width: 50%;padding-right: 5px;">
                                  Varience Minus
                            </div>  
                        </div> 
                        <div style="margin-bottom:1px">
                            <div style="margin-bottom:1px;">
                                <input  label="Total Qty Minus (PCS):" value="<?php echo number_format($total_itemminus,2); ?>"   class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                            <div style="margin-bottom:1px;">
                                <input  label="Total Net Retail Minus:" value="<?php echo number_format($total_netminus,2); ?>"   class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                            <div style="margin-bottom:1px;">
                                <input  label="Total HPP Minus:"  value="<?php echo number_format($total_netminus,2); ?>"  class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                        </div>   
                    </div>
                </div>
                <div style="width: 40%; padding: 10px;"> 
                    <div style="margin-bottom:1px"> 
                        <div style="margin-bottom:1px"> 
                            <div style="float:left; width: 50%; padding-right: 5px;">
                                  Varience Stock
                            </div>  
                        </div> 
                        <div style="margin-bottom:1px">
                            <div style="margin-bottom:1px;">
                                <input  label="Total Qty Varience:"  value="<?php echo number_format($total_itemvarience,2); ?>"  class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                            <div style="margin-bottom:1px;">
                                <input  label="Total Net Retail Varience:"  value="<?php echo $total_netvarience; ?>"  class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                            <div style="margin-bottom:1px;">
                                <input  label="Total Varience HPP:" value="<?php echo $total_netvarience; ?>"   class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="false" readonly="true" label="" style="width:100%">
                            </div>
                        </div>   
                    </div>
                </div>
                <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
            </div> 
            </div>
        </form>
    </div> 
</div>  