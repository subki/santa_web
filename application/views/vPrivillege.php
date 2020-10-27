<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/privillege.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/jquery.etree.js"></script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:30%;">
        <a href="#" onclick="$('#tree_group').etree('create')" style="margin: 5px" iconCls="icon-add" class="easyui-linkbutton"> Add </a>
        <a href="#" onclick="$('#tree_group').etree('destroy')" style="margin: 5px" iconCls="icon-remove" class="easyui-linkbutton"> Remove </a>
        <div id="tts" fit="true" style="width:100%;height:450px;" class="easyui-accordion"></div>
    </div>
    <div id="p2" data-options="region:'center',border:false" style="width:30%; padding: 5px;">
        <div style="width: 100%">
            <h2>Group Permission</h2>
            <table style="width: 100%">
                <tbody id="isi">
                    <tr>
                        <td>Please Select group privillege</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table style="width: 100%">
            <tbody>
            <tr>
                <td width="40%"><div id="app" style="width:100%;height:250px;" class="easyui-accordion"></div></td>
                <td width="20%">
                    <div style="position:relative; top: 30%; text-align:center">
                        <a href="#" onclick="add_to_priv()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> >> </a>
                        <a href="#" onclick="remove_from_priv()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> << </a>
                    </div>
                </td>
                <td width="40%"><div id="priv" style="width:100%;height:250px;" class="easyui-accordion"></div></td>
            </tr>
            <tr>
                <td width="40%">
                    <div id="app2" style="width:100%;height:250px;" class="easyui-accordion"></div>
                </td>
                <td width="20%">
                    <div style="position:relative; top: 30%; text-align:center">
                        <a href="#" onclick="add_to_priv2()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> >> </a>
                        <a href="#" onclick="remove_from_priv2()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> << </a>
                    </div>
                </td>
                <td width="40%">
                    <div id="priv2" style="width:100%;height:250px;" class="easyui-accordion"></div>
                </td>
            </tr>
            </tbody>
        </table>
<!--        <div class="easyui-layout" style="width:100%;height:100%;">-->
<!--            <div data-options="region:'west',border:false" style="width:40%;height: 50%;">-->
<!--                <div id="app" style="width:100%;height:250px;" class="easyui-accordion"></div>-->
<!--            </div>-->
<!--            <div data-options="region:'center',border:false" style="width:20%;height: 50%;">-->
<!--                <div style="position:relative; top: 30%; text-align:center">-->
<!--                <a href="#" onclick="add_to_priv()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> >> </a>-->
<!--                <a href="#" onclick="remove_from_priv()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> << </a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div data-options="region:'east',border:false" style="width:40%;height: 50%;">-->
<!--                <div id="priv" style="width:100%;height:250px;" class="easyui-accordion">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div style="margin-bottom:20px;">-->
<!--                <table style="width: 100%">-->
<!--                    <tbody id="isi">-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->
<!--            <div data-options="region:'south'" style="width:100%;height: 45%;">-->
<!--                <table id="dg" title=" " class="easyui-datagrid" style="width:100%;height:100%;" fit="true"></table>-->
<!--                <div id="toolbar">-->
<!--                    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow',0)">Add</a>-->
<!--                    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Del</a>-->
<!--                    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Submit</a>-->
<!--                    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>-->
<!--                </div>-->
<!--                <div class="easyui-layout" style="width:100%;height:100%;">-->
<!--                    <div data-options="region:'west',border:false" style="width:40%;height: 50%;">-->
<!--                        <div id="app2" style="width:100%;height:250px;" class="easyui-accordion"></div>-->
<!--                    </div>-->
<!--                    <div data-options="region:'center',border:false" style="width:20%;height: 50%;">-->
<!--                        <div style="position:relative; top: 30%; text-align:center">-->
<!--                            <a href="#" onclick="add_to_priv2()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> >> </a>-->
<!--                            <a href="#" onclick="remove_from_priv2()" style="width: 80%; margin: 5px;" class="easyui-linkbutton"> << </a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div data-options="region:'east',border:false" style="width:40%;height: 50%;">-->
<!--                        <div id="priv2" style="width:100%;height:250px;" class="easyui-accordion"></div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
