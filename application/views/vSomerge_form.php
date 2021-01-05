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
<script src="<?php echo base_url(); ?>assets/js/spm_form.js"></script>

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
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
         style="width:100%;height:100%;background:#fafafa;"
         data-options="iconCls:'icon-sales',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                <div data-options="region:'west'" style="width:100%;">
                    <table id="dg" class="easyui-edatagrid" style="width:100%;height: 120px">
                    </table>
                </div>
            </div>
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;"> 
                <div style="width: 100%; padding: 10px;" class="border-kotak">
                    <div style="margin-bottom:1px"> 
                        <div style="margin-bottom:1px">
                            <div style="float:right; width: 30%;">
                                <div style="margin-bottom:1px;">  
                                    Total Varience :  <?php echo $totalv; ?>
                                </div> 
                            </div> 
                        </div>
                    </div> 
                </div> 
            </div>
            <div data-options="region:'west'" style="width:100%;">
                <table id="dgdetail" class="easyui-edatagrid" style="width:100%;height: auto;">
                </table>
            </div>
        </form>
    </div>
 
</div>
