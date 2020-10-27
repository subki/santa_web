<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/finance_ok.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <div style="margin: 20px">
            <label>Periode : </label>
            <input name="tahun" id="tahun" type="number" placeholder="Tahun" min="2019" value="<?php echo date('Y')?>" style="width:20%">
            <input name="bulan" id="bulan" type="number" placeholder="Bulan" min="1" max="12" value="<?php echo date('m')?>" style="width:10%">
            <select id="jenis" class="form-control" name="jenis">
                <option value="">Select Faktur Type</option>
                <option value="SHOWROOM">SHOWROOM</option>
                <option value="WHOLESALES">WHOLESALES</option>
                <option value="CONSIGNMENT">CONSIGNMENT</option>
                <option value="SALES ONLINE">SALES ONLINE</option>
            </select>
            <button onclick="showData()">Show Data</button>
        </div>
        <div id="tt" class="easyui-tabs" style="width:100%;height:100%;">
            <div title="Faktur Closed" style="padding:5px;display:none">
                <table id="dg" title="<?php echo $title; ?>" class="easyui-datagrid" style="width:100%;height: 90%">
                </table>
            </div>
            <div title="Sales Invoice" style="padding:5px;display:none;">
                <table id="dg2" title="<?php echo $title; ?>" class="easyui-datagrid" style="width:100%;height: 90%">
                </table>
            </div>
        </div>
    </div>
</div>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="viewData()" iconCls="icon-eye" plain="true">View</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="exportData()" iconCls="icon-download" plain="true">E-Faktur</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="postingWSSelected();" iconCls="icon-ok" plain="true">Posting Selected</a>
</div>
<div id="toolbar2">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="viewData()" iconCls="icon-eye" plain="true">View</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="exportData()" iconCls="icon-download" plain="true">E-Faktur</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="unpostingWSSelected();" iconCls="icon-ok" plain="true">Unposting Selected</a>
</div>