<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/finance_inv.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>

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
        <table id="dg" title="<?php echo $title; ?>" class="easyui-datagrid" style="width:100%;height: 90%">
        </table>
    </div>
</div>
<div id="toolbar">
<!--    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="viewData()" iconCls="icon-eye" plain="true">View</a>-->
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="proformaInvoice();" iconCls="icon-finance-inv" plain="true">Create Proforma Invoice</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="cetakInvoice();" iconCls="icon-print" plain="true">Cetak Invoice</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="cetakProforma();" iconCls="icon-print" plain="true">Cetak Proforma Invoice</a>
</div>
