<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/salesonline_grid.js"></script>
<div id="p2" data-options="region:'north', height:80">
        <table style="width: 90%; margin:10px;">
            <tr style="width: 100%">
                <td style="margin:10px; width: 10%;">
                    <select name="jenis_status" id="jenis_status" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Jenis Status:" style="width:100%;">
                    	<option value="ALL">ALL</option> 
                    	<option value="OPEN">OPEN</option> 
                    	<option value="POSTING">POSTING</option>
                    	<option value="CLOSED">CLOSED</option>
                    	<option value="PAID">PAID</option> 
                    	<option value="BATAL">BATAL</option> 
                    </select>
                </td> 
                <td style="margin:10px; width: 20%;">
                    <input name="periode" id="periode" class="easyui-datebox" labelPosition="top" tipPosition="right" required="true" label="Tanggal:" style="width:50%;">
                    </input>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="Refresh()" iconCls="icon-reload" plain="true">Refresh</a>
                </td>
                <td style="margin:10px; width: 30%;"> 
                </td>
            </tr>
        </table>
    </div>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
</div>
<div id="toolbar">
    <!-- <a href="<?php echo base_url(); ?>Salesonline/form/add" class="easyui-linkbutton" iconCls="icon-add" plain="true">New</a> -->
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="editData()" iconCls="icon-edit" plain="true">Edit</a>
</div>