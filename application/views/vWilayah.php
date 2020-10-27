<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var customer_price = [];
    <?php foreach ($select as $row) { ?>
    customer_price.push(
        {
            customer_type_code:"<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>",
            display:"<?php echo (isset($row['display'])) ? $row['display'] : ''; ?>"
        }
    );
    <?php } ?>
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/wilayah.js"></script>
<div id="cc" class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;height: 60%">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:530px; height: 350px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px">
            <div data-options="region:'east'" style="width:100%;height: 100%">
                <div style="margin-bottom:10px">
                    <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="ID:" style="width:100%">
                </div>
                <div style="margin-bottom:10px">
                    <input name="name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Provinsi:" style="width:100%">
                </div>
            </div>

        </form>
        <div id="dlg-buttons" style="float: right">
            <a href="javascript:void(0)" id="submit" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
            <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
        </div>
    </div>
    <div id="dlg2" class="easyui-dialog" style="width:530px; height: 350px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons2'">
        <form id="fm2" method="post" novalidate style="margin:0;padding:20px">
            <div data-options="region:'east'" style="width:100%;height: 100%">
                <div style="margin-bottom:10px">
                    <input name="province_id" id="province_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Provinsi ID:" style="width:100%">
                </div>
                <div style="margin-bottom:10px">
                    <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="ID:" style="width:100%">
                </div>
                <div style="margin-bottom:10px">
                    <input name="name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kota/Kab.:" style="width:100%">
                </div>
            </div>

        </form>
        <div id="dlg-buttons2" style="float: right">
            <a href="javascript:void(0)" id="submit2" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit2()" style="width:90px">Save</a>
            <a href="javascript:void(0)" id="cancel2" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput2();" style="width:90px">Cancel</a>
        </div>
    </div>
    <div id="dlg3" class="easyui-dialog" style="width:840px; height: 400px" data-options="closed:true,modal:true,border:'thin'">
        <table id="prc" class="easyui-edatagrid" style="width:100%;">
        </table>
    </div>

</div>