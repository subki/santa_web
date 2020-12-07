<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/article.js"></script>
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script>
<div id="cc" class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%; height: 60%">
        <table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" style="width:100%;height:100%"></table>
    </div>
    <div data-options="region:'south'" style="height:40%; width: 60%;">
        <div class="easyui-layout" fit="true" style="width:100%;height:100%;">
            <div data-options="region:'west'" style="width:50%;padding:0px">
                <table id="size" class="easyui-edatagrid" title="Article Size" style="width:100%;"></table>
            </div>
            <div data-options="region:'center'" style="width:50%;padding:0px">
                <table id="colour" class="easyui-edatagrid" title="Article Colour" style="width:100%;">
                </table>
            </div>
        </div>
    </div>
    <div id="dlg" class="easyui-dialog" style="width:99%; height: 95%" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                <div style="width: 33%; padding: 10px;">
                    <div style="margin-bottom:1px">
                        <input name="article_code" id="article_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="article_name" id="article_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Name:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="style" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Style:" style="width:100%">
                    </div>

                </div>
                <div style="width: 33%; padding: 10px;">
                    <div style="margin-bottom:1px">
                        <input name="bom_pcs" id="bom_pcs" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="BOM/Pcs:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="foh_pcs" id="foh_pcs" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="FOH/Pcs:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="ongkos_jahit_pcs" id="ongkos_jahit_pcs" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="Ongkos Jahit / PCS:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="ekspedisi" id="ekspedisi" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="Biaya Ekspedisi:" style="width:100%">
                    </div>
                </div>
                <div style="width: 33%; padding: 10px;">
                    <div style="margin-bottom:1px">
                        <input name="operation_cost" id="operation_cost" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="Operational Cost (%):" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="interest_cost" id="interest_cost" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="Interest Cost (%):" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="buffer_cost" id="buffer_cost" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="Buffer Cost (%):" style="width:100%">
                    </div>
                </div>
            </div>
            <div style="margin-bottom:1px; margin-left: 10px; margin-right: 10px;">
                <input name="keterangan" id="keterangan" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Keterangan:" style="width:100%; height: 100px;">
            </div>
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                <div style="width: 100%; padding: 10px;">
                    <div style="margin-bottom:1px">
                      <div style="float:left; width: 20%; padding-right: 5px;">
                        <input name="effdate" id="effdate" class="easyui-datebox" labelPosition="top" tipPosition="bottom" label="Effective Date:" style="width:100%">
                      </div>
                        <div style="float:left; width: 20%; padding-right: 5px;">
                            <input name="hpp1" id="hpp1" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="HPP 1:" readonly style="width:100%">
                        </div>
                        <div style="float:left; width: 20%; padding-right: 5px;">
                            <input name="hpp2" id="hpp2" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="HPP 2:" readonly style="width:100%">
                        </div>
                        <div style="float:left; width: 20%; padding-right: 5px;">
                            <input name="hpp_ekspedisi" id="hpp_ekspedisi" class="easyui-numberbox" labelPosition="top" tipPosition="bottom" data-options="precision:2,formatter:formatnumberbox"  label="HPP 2 + Ekspedisi:" readonly style="width:100%">
                        </div>
                    </div>
                </div>
            </div>
            <div id="dlg-buttons" style="float: right">
                <a href="javascript:void(0)" id="submit2" class="easyui-linkbutton" iconCls="icon-ok" onclick="submit()" style="width:90px">Save</a>
                <a href="javascript:void(0)" id="cancel2" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
            </div>
        </form>

    </div>
    <div id="dlg2" class="easyui-dialog" style="width:650px; height: 300px" data-options="closed:true,modal:true,maximizable:true,border:'thin'">
        <table id="prod" class="easyui-edatagrid" style="width:100%;">
        </table>
    </div>
    <div id="dlg4" class="easyui-dialog" style="width:1020px; height: 500px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttonss'">
        <form id="formupload" style="margin-bottom:-0px;">
            <div data-options="region:'west'" style="width:100%;padding:10px">
                <div style="margin-bottom:10px">
                    <input class="easyui-filebox" id="userfile" name="userfile" label="Select File:" data-options="prompt:'Choose a file...'" style="width:30%">
                </div>
            </div>
            <div id="dlg-buttonss" style="float: right">
                <a href="#" onclick="submitUpload()" class="easyui-linkbutton" iconCls="icon-save" plain="true">Submit</a>
                <a href="#" onclick="cancelUpload()" class="easyui-linkbutton" iconCls="icon-undo" plain="true">Cancel</a>
            </div>
        </form>
    </div>
</div>