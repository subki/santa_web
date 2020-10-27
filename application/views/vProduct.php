<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var uom_stock = "<?php echo $this->session->userdata('uom stock'); ?>";
    var kelompok = "<?php echo $kelompok; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/product.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%">
        </table>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:1020px; height: 500px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:10px 50px">
            <div data-options="region:'west'" style="width:100%;padding:10px">
                <table style="width:100%;height:100%">
                    <tr>
                        <td style="width: 25%; vertical-align: top; padding-left: 5px; padding-right: 5px">
                            <table>
                                <tr style="margin-bottom:10px; width: 30%; padding: 5px;">
                                    <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="ID:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding: 5px">
                                    <input name="sku" id="sku" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="SKU:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding: 5px">
                                    <input name="product_code" id="product_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode Produk:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="article_code" id="article_code" labelPosition="top" tipPosition="bottom" required="true" label="Article:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="product_name" id="product_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Nama Produk:" style="width:100%">
                                </tr>
                            </table>
                        </td>
                        <td style="width: 25%; vertical-align: top; padding-left: 5px; padding-right: 5px">
                            <table>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="brand_code" id="brand_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Brand:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="size_code" id="size_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Size:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="class_code" id="class_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Class:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="subclass_code" id="subclass_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Subclass:" style="width:100%">
                                </tr>
<!--                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">-->
<!--                                    <input name="type_barang" id="type_barang" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Type Barang:" style="width:100%">-->
<!--                                </tr>-->
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="supplier_code" id="supplier_code" labelPosition="top" tipPosition="bottom" required="true" label="Supplier:" style="width:100%">
                                </tr>
                            </table>
                        </td>
                        <td style="width: 25%; vertical-align: top; padding-left: 5px; padding-right: 5px">
                            <table>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <select name="jenis_barang" id="jenis_barang" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Jenis Barang:" style="width:100%;">
                                    </select>
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="satuan_beli" id="satuan_beli" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="UOM Beli:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="satuan_stock" id="satuan_stock" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="UOM Stock:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="satuan_jual" id="satuan_jual" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="UOM Jual:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="colour_code" id="colour_code" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Colour:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <select name="status_product" id="status_product" class="easyui-combobox" data-options="prompt:'- Please Select -'" labelPosition="top" tipPosition="bottom" required="true" label="Status:" style="width:100%;">
                                        <option value="Active">Active</option>
                                        <option value="Block Production">Block Production</option>
                                        <option value="Non Active">Non Active</option>
                                    </select>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 25%; vertical-align: top; padding-left: 5px; padding-right: 5px">
                            <table>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="first_production" id="first_production" class="easyui-datebox" labelPosition="top" tipPosition="bottom" required="true" label="First Production:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="last_production" id="last_production" class="easyui-datebox" labelPosition="top" tipPosition="bottom" required="true" label="Last Production:" style="width:100%">
                                </tr>
                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">
                                    <input name="purchase_market" id="purchase_market" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Purchases Market:" style="width:100%">
<!--                                    <select name="purchase_market" id="purchase_market" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Purchases Market:" style="width:100%;">-->
<!--                                        <option value="">-Please Select-</option>-->
<!--                                        <option value="Import">Import</option>-->
<!--                                        <option value="Lokal">Local</option>-->
<!--                                    </select>-->
                                </tr>
<!--                                <tr style="margin-bottom:10px; width: 30%; padding : 5px">-->
<!--                                    <select name="sales_market" id="sales_market" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Sales Market:" style="width:100%;">-->
<!--                                        <option value="">-Please Select-</option>-->
<!--                                        <option value="Wholesales">Wholesales</option>-->
<!--                                        <option value="Outlet">Outlet</option>-->
<!--                                    </select>-->
<!--                                </tr>-->
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="dlg-buttons" style="float: right;">
                <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>
    </div>
    <div id="dlg2" class="easyui-dialog" style="width:840px; height: 400px" data-options="closed:true,modal:true,border:'thin'">
        <table id="prc" class="easyui-edatagrid" style="width:100%;">
        </table>
    </div>
    <div id="dlg3" class="easyui-dialog" style="width:840px; height: 400px" data-options="closed:true,modal:true,border:'thin'">
        <table id="mts" class="easyui-edatagrid" style="width:100%;">
        </table>
    </div>
    <div id="dlg4" class="easyui-dialog" style="width:1020px; height: 500px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttonss'">
        <form id="formupload" style="margin-bottom:-0px;">
			<div data-options="region:'west'" style="width:100%;padding:10px">
				<div style="margin-bottom:10px">
					<label>Format file berbentuk <b>.csv</b>, dengan format : <br/> 
					<b>1. Produk</b> (product.csv) => Article Code, Product Name, Brand Code, Class Code, SubClass Code, Supplier Code, Size Code, Colour Code, UOM Beli, UOM Jual, Status <br/>
					<b>2. Article</b> (article.csv) => Article Code, Article Name, Style, BOM/Pcs, FOH/Pcs, Ongkos Jahit/Pcs, Operational Cost, Interest Cost <br/>
					<b>3. Article Colour</b> (article_colour.csv) => Article Code, Colour Code<br/>
					<b>4. Article Size</b> (article_size.csv) => Article Code, Size Code<br/>
					</label>
				</div>
				<div style="margin-bottom:10px">
					<input class="easyui-filebox" id="userfile" name="userfile[]" label="Select File:" data-options="prompt:'Choose a file...',accept:'.csv', multiple:true" style="width:30%">
				</div>
			</div>
			<div id="dlg-buttonss" style="float: right">
				<a href="#" onclick="submitUpload()" class="easyui-linkbutton" iconCls="icon-save" plain="true">Submit</a>
				<a href="#" onclick="cancelUpload()" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Cancel</a>
			</div>
		</form>
    </div>
</div>
		
		