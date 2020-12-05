<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var uom_stock = "<?php echo $this->session->userdata('uom stock'); ?>";
</script>

<script src="<?php echo base_url(); ?>assets/js/importdata.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div class="easyui-layout" style="width:100%;height:100%">
        <div id="p" data-options="region:'west'" style="width:60%;">
            <form id="formupload" style="margin-bottom:-0px;">
                <div data-options="region:'west'" style="width:100%;padding:10px">
                    <div style="margin-bottom:10px;">
                        <input class="easyui-filebox" id="userfile" name="userfile[]" label="Select File:"
                               data-options="prompt:'Choose a file...',accept:'.csv', multiple:true" style="width:90%">
                    </div>
                    <div style="margin-bottom:10px">
                        <label>Format file berbentuk <b>.csv</b>, dengan format : <br/>
                            <b>1. Produk</b> (product.csv) => SKU, Product Code, Article Code, Product Name, Brand Code, Class Code, SubClass Code, Supplier Code, Size Code, Colour Code, UOM Beli, UOM Stock, UOM Jual, Status, Jenis Barang <br/>
                            <b>2. Article</b> (article.csv) => Article Code, Article Name, Style, BOM/Pcs, FOH/Pcs, Ongkos Jahit/Pcs, Operational Cost, Interest Cost <br/>
                            <b>3. Article Colour</b> (article_colour.csv) => Colour Code, Article Code<br/>
                            <b>4. Article Size</b> (article_size.csv) => Size Code, Article Code<br/>
                            <b>5. DO Produksi</b> (produksi.csv) => <b><i>(Pastikan tanggal DO sesuai dengan periode berjalan pada lokasi PRD dan PST)</i></b> Tanggal DO, SKU, Qty Send, Keterangan<br/>
                            <b>5. DO Produksi (Product Import)</b> (produksi_import.csv) => <b><i>(Pastikan tanggal DO sesuai dengan periode berjalan pada lokasi PRD dan PST)</i></b> Tanggal DO, SKU, Qty Send, Keterangan<br/>
                            <b>6. Brand</b> (brand.csv) => Kode Brand, Description<br/>
                            <b>7. Size</b> (size.csv) => Kode Size, Description<br/>
                            <b>8. Colour</b> (colour.csv) => Kode Colour, Description<br/>
                            <b>9. UOM</b> (uom.csv) => Kode UOM, Description<br/>
                            <b>10. UOM Convertion</b> (uom_convertion.csv) => UOM From, UOM To, Convertion<br/>
                            <b>11. Group</b> (group.csv) => Kode Group,Description<br/>
                            <b>12. Subgroup</b> (subgroup.csv) => Kode Group, Kode Sub Group, Description<br/>
                            <b>13. Faktur Pajak</b> (faktur.csv) => Nomor sequence <br/>
                            <b>14. Resi Market Place</b> (marketplace.csv) => No. Resi, Nama Customer, No Telepon, Alamat Kirim, Kota, Provinsi, Order Date (YYYY-MM-DD HH:MM:SS) <br/>
                        </label>
                    </div>
                </div>
            </form>
        </div>
        <div data-options="region:'east'" style="width:41%;">
            <table id="dg" title="Informasi Gagal Upload" class="easyui-datagrid" style="width:100%;height:100%">
            </table>
        </div>
    </div>
</div>
		
		