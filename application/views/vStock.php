<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/stock.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p2" data-options="region:'north', height:80">
        <table style="width: 90%; margin:10px;">
            <tr style="width: 100%">
                <td style="margin:10px; width: 30%;">
                    <select name="location_code" id="location_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Lokasi:" style="width:100%;">
                    </select>
                </td>
                <td style="margin:10px; width: 30%;">
                    <input name="periode" id="periode" class="easyui-datebox" labelPosition="top" tipPosition="right" required="true" label="Periode:" style="width:100%;">
                    </input>
                </td>
                <td style="margin:10px; width: 30%;">
                </td>
            </tr>
        </table>
    </div>
    <div id="p" data-options="region:'center'" style="width:100%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" fit="true" style="width:100%;height:100%">
        </table>
        <div id="toolbar1" style="padding:2px;">
            <a href="#" onclick="openUpload()" class="easyui-linkbutton" iconCls="icon-upload" plain="true">Upload</a>
        </div>
        <div id="toolbar2" style="padding:2px;">
            <form id="formupload" style="margin-bottom:-0px;">
                <input class="easyui-filebox" id="userfile" name="userfile" label="Select File:" data-options="prompt:'Choose a file...',accept:'.csv'" style="width:30%">
                <a href="#" onclick="submitUpload()" class="easyui-linkbutton" iconCls="icon-save" plain="true">Submit</a>
                <a href="#" onclick="cancelUpload()" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Cancel</a>
            </form>
            <label>Format file berbentuk <b>.csv</b>, dengan isi Nomor Barang, Saldo Awal</label>
        </div>
    </div>
</div>