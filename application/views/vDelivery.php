<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var route ="<?php echo $route==="to"?"out":"in";?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var store = "<?php echo $this->session->userdata('store_code'); ?>";
    var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
    var awalan = "<?php echo $prefix; ?>";
	var lokasi_produksi = "<?php echo $auto['lokasi produksi']; ?>";
	var lokasi_barang_jadi = "<?php echo $auto['lokasi barang jadi']; ?>";
	var lokasi_barang_retur = "<?php echo $auto['lokasi barang retur']; ?>";
	var kode_store_pusat = "<?php echo $auto['kode store pusat']; ?>";
	var lokasi_produksi_name = "<?php echo $auto['nama lokasi produksi']; ?>";
	var lokasi_barang_jadi_name = "<?php echo $auto['nama lokasi barang jadi']; ?>";
	var lokasi_barang_retur_name = "<?php echo $auto['nama lokasi barang retur']; ?>";
	var kode_store_pusat_name = "<?php echo $auto['nama kode store pusat']; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/delivery.js"></script>
<?php //var_dump($auto); die();?>

<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="tt_disc" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:900px" data-options="closed:true,modal:true,border:'thin'">
        <div class="easyui-panel" style="width:100%;height:100%">
            <table id="dd" class="easyui-edatagrid" style="width:100%;height:400px"></table>
        </div>
    </div>
</div>