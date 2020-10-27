<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<style type="text/css">
    .textbox-editable true{
        background: blue;
    }
    .textbox-editable false{
        background: yellow;
    }
</style>
<?php
    $sesi = $this->session->userdata();
?>
<script src="<?php echo base_url(); ?>assets/js/merchant.js"></script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div class="easyui-layout" fit="true" style="width:100%;height:100%;">
        <div data-options="region:'west'" style="width:50%;padding:0px">
            <div class="easyui-panel" title="Cetak Faktur Penjualan">

            </div>
        </div>
        <div data-options="region:'center'" style="width:50%;padding:0px">
            <div class="easyui-panel" title="Pengusaha Kena Pajak">
                <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                    <div style="width: 100%; padding: 10px;">
                        <div style="margin-bottom:1px">
                            <input name="nama_pkp" id="nama_pkp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="Nama PKP :" style="width:100%" value="<?php echo $sesi['nama pkp'];?>">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="alamat_pkp" id="alamat_pkp" class="easyui-textbox" data-options="multiline:true" labelPosition="top" tipPosition="bottom"
                                   label="Alamat PKP :" style="width:100%; height: 100px;" value="<?php echo $sesi['alamat pkp']?>">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="npwp" id="npwp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="NPWP :" style="width:100%;" value="<?php echo $sesi['npwp']?>">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="tanggal_pengukuhan" id="tanggal_pengukuhan" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="Tanggal Pengukuhan :" style="width:100%;" value="<?php echo $sesi['tanggal pengukuhan']?>">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="prefix_seri_pajak" id="prefix_seri_pajak" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="Prefix Seri Pajak :" style="width:100%;" value="<?php echo $sesi['prefix seri pajak']?>">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="nama_bagian" id="nama_bagian" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="Nama Bagian :" style="width:100%;" value="<?php echo $sesi['nama bagian']?>">
                        </div>
                        <div style="margin-bottom:1px">
                            <input name="pemegang_bagian" id="pemegang_bagian" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="Pemegang Bagian :" style="width:100%;" value="<?php echo $sesi['pemegang bagian']?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
