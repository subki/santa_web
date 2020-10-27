<div region="west" title="List" split="true" style="width:55%; height: 100%;">
    <table id="dg2" class="easyui-datagrid" style="width:100%;height:100%">
    </table>
</div>
<div region="center" title="Input" style="width:45%; height: 100%;">
    <form id="fm2" method="post" novalidate style="margin:0;padding:20px 50px">
        <div style="margin-bottom:10px">
            <input name="store_code2" id="store_code2" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Store Code:" style="width:100%" readonly>
        </div>
        <tr style="margin-bottom:10px">
            <input name="location_code" class="easyui-combogrid" id="location_code" labelPosition="top" tipPosition="bottom" required="true" label="Stock Location:" style="width:100%">
        </tr>
        <div style="margin-bottom:10px">
            <input name="kode_cabang" id="kode_cabang" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Kode Cabang:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="nama_cabang" id="nama_cabang" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Nama Cabang:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="prefix_trx" id="prefix_trx" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Pref. Trx:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <select name="type" id="type" class="easyui-combobox" labelPosition="top" tipPosition="bottom" label="Type:" style="width:100%;">
                <option value="HO">HO</option>
                <option value="Cabang">Cabang</option>
            </select>
        </div>
        <div style="margin-bottom:10px">
            <input name="flag" id="flag" class="easyui-textbox" labelPosition="top" tipPosition="bottom" required="true" label="Flag:" style="width:100%">
        </div>
    </form>
    <div style="float:right; padding: 10px">
        <a id="submit2" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="submit2();" style="width:90px">Save</a>
        <a id="cancel2" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clearInput();" style="width:90px">Cancel</a>
    </div>
</div>