<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/users.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:75%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div data-options="region:'east'" style="width:25%;">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <h3>Users</h3>
            <div style="margin-bottom:10px">
                <input name="user_id" id="user_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="ID:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="nik" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="NIK:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="fullname" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Fullname:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="user_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Username:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="user_password" type="password" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Password:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="store_code" id="store_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Store:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="location_code" id="location_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Loaction:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="kode_otoritas" id="kode_otoritas" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Kode Autorisasi:" style="width:100%">
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
</div>