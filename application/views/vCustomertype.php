<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/customer_type.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:70%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%"></table>
    </div>
    <div data-options="region:'east'" style="width:30%;">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <h3><?php echo $title ?></h3>
            <div style="margin-bottom:10px">
                <input name="code" id="code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="description" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Name:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <select name="pkp" id="pkp" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Jenis PKP:" style="width:100%;">
                    <option value="">Pilih Jenis PKP</option>
                    <option value="Include">Include</option>
                    <option value="Exclude">Exclude</option>
                </select>
            </div>
            <div style="margin-bottom:10px">
                <input name="diskon" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="min:0, precision:2, formatter:formatnumberbox" required="true" label="Discount:" style="width:100%">
            </div>
<!--            <div style="margin-bottom:10px">-->
<!--                <select name="auto_create" id="auto_create" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Auto Create Location:" style="width:100%;">-->
<!--                    <option value="">Please Select</option>-->
<!--                    <option value="Yes">Yes</option>-->
<!--                    <option value="No">No</option>-->
<!--                </select>-->
<!--            </div>-->
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:800px" data-options="closed:true,modal:true,border:'thin'">
        <table id="tt" class="easyui-datagrid" style="width:100%;height:400px"></table>
    </div>
</div>