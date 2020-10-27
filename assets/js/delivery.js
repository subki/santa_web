var prefix;
var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"delivery/load_grid/"+route+"/"+awalan,
    saveUrl:base_url+"delivery/save_data",
    updateUrl:base_url+"delivery/edit_data",
    destroyUrl:base_url+"delivery/delete_data",
    idField:'docno',
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"status",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:toolbarDO(),
    loadFilter: function(data){
        data.rows = [];
        if (data.data){
            data.rows = data.data;
        }
        return data;
    },
	onLoadSuccess:function(){
		authbutton();
	},
    onBeginEdit: function(index,row){
        // var editor = $(this).edatagrid('getEditor', {index:index,field:'to_store_code'});
        // var grid = $(editor.target).combogrid('grid');
        // grid.datagrid('enableFilter');
        var editor = $(this).edatagrid('getEditor', {index:index,field:'to_location_code'});
        var grid = $(editor.target).combogrid('grid');
        grid.datagrid({url: base_url + "delivery/get_location/xx"});
        grid.datagrid('enableFilter');

        // editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
        // grid = $(editor.target).combogrid('grid');
        // grid.datagrid('enableFilter');
        editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
        grid = $(editor.target).combogrid('grid');
        grid.datagrid({url: base_url + "delivery/get_location/xx"});
        grid.datagrid('enableFilter');

        editor = $(this).edatagrid('getEditor', {index:index,field:'status'});
        $(editor.target).combobox('loadData', statusDO(true));

        if(row.isNewRecord) {
            editor = $(this).edatagrid('getEditor', {index:index,field:'docno'});
            $(editor.target).textbox('setValue', prefix);
			
			var date = new Date();
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate();
			var tgl = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
			editor = $(this).edatagrid('getEditor', {index:index,field:'doc_date'});
            $(editor.target).textbox('setValue', tgl);
			editor = $(this).edatagrid('getEditor', {index:index,field:'status'});
			$(editor.target).combobox('select', 'OPEN');
			
			if(prefix==="PON" || prefix==="MPI"){
				editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'to_store_code'});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
				$(editor.target).combogrid('setValue', lokasi_produksi);
				editor = $(this).edatagrid('getEditor', {index:index,field:'to_location_code'});
				grid = $(editor.target).combogrid('grid');
				grid.datagrid({url: base_url + "delivery/get_location/" + kode_store_pusat});
				grid.datagrid('enableFilter');
				$(editor.target).combogrid('setValue', lokasi_barang_jadi);
			}else if(prefix==="DO2"){
				editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
				grid = $(editor.target).combogrid('grid');
				grid.datagrid({url: base_url + "delivery/get_location/" + kode_store_pusat});
				grid.datagrid('enableFilter');
				$(editor.target).combogrid('setValue', lokasi_barang_jadi);
			}else if(prefix==="DOLTR"){
				editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
				grid = $(editor.target).combogrid('grid');
				grid.datagrid({url: base_url + "delivery/get_location/" + kode_store_pusat});
				grid.datagrid('enableFilter');
				$(editor.target).combogrid('setValue', lokasi_barang_jadi);
			}else if(prefix==="DO1"){
				editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'to_store_code'});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
				$(editor.target).combogrid('setValue', lokasi_barang_jadi);
				
				editor = $(this).edatagrid('getEditor', {index:index,field:'to_location_code'});
				grid = $(editor.target).combogrid('grid');
				grid.datagrid({url: base_url + "delivery/get_location/" + kode_store_pusat});
				grid.datagrid('enableFilter');
				$(editor.target).combogrid('setValue', lokasi_produksi);
			}else if(prefix==="DO2_1"){
				editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
				$(editor.target).textbox('setValue', store);
				$(editor.target).textbox('setText', store_name);

				// editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
				// grid = $(editor.target).combogrid('grid');
				// grid.datagrid({url: base_url + "delivery/get_location/" + store});
				// grid.datagrid('enableFilter');

				editor = $(this).edatagrid('getEditor', {index:index,field:'to_store_code'});
				$(editor.target).textbox({readonly:true});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'to_location_code'});
				grid = $(editor.target).combogrid('grid');
				grid.datagrid({url: base_url + "delivery/get_location/" + kode_store_pusat});
				grid.datagrid('enableFilter');
                $(editor.target).combogrid({readonly:true});
                $(editor.target).combogrid('setValue', lokasi_barang_jadi);

                // editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
                // grid = $(editor.target).combogrid('grid');
                // grid.datagrid({url: base_url + "delivery/get_location/" + store});
                // grid.datagrid('enableFilter');
                // $(editor.target).combogrid('setValue', lokasi_barang_jadi);
            }else if(prefix==="DOLTR_1"){
				editor = $(this).edatagrid('getEditor', {index:index,field:'from_store_code'});
				$(editor.target).textbox('setValue', store);
				$(editor.target).textbox('setText', store_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'from_location_code'});
				grid = $(editor.target).combogrid('grid');
				grid.datagrid({url: base_url + "delivery/get_location/" + store});
				grid.datagrid('enableFilter');
				
				editor = $(this).edatagrid('getEditor', {index:index,field:'to_store_code'});
				$(editor.target).textbox('setValue', kode_store_pusat);
				$(editor.target).textbox('setText', kode_store_pusat_name);

				editor = $(this).edatagrid('getEditor', {index:index,field:'to_location_code'});
				grid = $(editor.target).combogrid('grid');
				grid.datagrid({url: base_url + "delivery/get_location/" + kode_store_pusat});
				grid.datagrid('enableFilter');
				$(editor.target).combogrid('setValue', lokasi_barang_jadi);
			}
        }else{
            var date = new Date(row.doc_date);
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            var tgl = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
            editor = $(this).edatagrid('getEditor', {index:index,field:'doc_date'});
            $(editor.target).textbox('setValue', tgl);

            date = new Date(row.tgl_promo);
            y = date.getFullYear();
            m = date.getMonth()+1;
            d = date.getDate();
            tgl = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
            editor = $(this).edatagrid('getEditor', {index:index,field:'tgl_promo'});
            $(editor.target).textbox('setValue', tgl);
        }
    },
    onBeforeEdit: function(index, row){
        var cant = false;
        if(route==="out"){
            if(row.status!=="OPEN") cant = true;
            else if(row.from_store_code!==store) cant = true;
        }else{
            if(row.status!=="ON DELIVERY") cant = true;
            else if(row.to_store_code!==store) cant = true;
        }

        if(cant){
            if(row.isNewRecord) return
            $.messager.show({
                title: 'Warning',
                msg: "Data tidak bisa di edit"
            });
            setTimeout(function () {
                $("#tt_disc").edatagrid('cancelRow');
            },500)
        }
    },
    columns:[[
        {field:"docno",   title:"Nomor DO",      width: '12%', sortable: true, editor:{
                type:'textbox',
                options:{
                    readonly:true
                }
            }},
        {field:"doc_date",   title:"Tanggal",      width: '9%', sortable: true, formatter:function (value, row) {
            return row.ak_doc_date;
        },
            editor:{type:'datebox', options:{required:true, readonly:route==="in"}}},
        {field:"tgl_promo",   title:"Tanggal Promo",      width: '9%', sortable: true, formatter:function (value, row) {
            return row.ak_tgl_promo;
        },
            editor:{type:'datebox', options:{readonly:route==="in"}}},
        {field:"from_store_code",   title:"From Store",      width: '15%', sortable: true, formatter: function(value, row){
            return row.from_store_name;
        },editor:{
            type:'textbox',
            options:{
                readonly:true
            }
        }},
        {field:"from_location_code",   title:"From Location",      width: '15%', sortable: true, formatter: function(value, row){
            return row.from_location_name;
        },editor:{
            type:'combogrid',
            options:{
                readonly:route==="in",
                idField: 'location_code',
                textField:'location_name',
                // url:base_url+"delivery/get_location/from",
                required:true,
                hasDownArrow: false,
                remoteFilter:true,
                panelWidth: 500,
                multiple:false,
                panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
                    mousedown: function(){}
                }),
                editable: false,
                pagination: true,
                loadFilter: function (data) {
                    data.rows = [];
                    if (data.data){
                        data.rows = data.data;
                    }
                    // console.log(data.rows)
                    return data;
                },
                onSelect:function (index,row) {
                    console.log(row)
                    var selectedrow = $("#tt_disc").edatagrid("getSelected");
                    var rowIndex = $("#tt_disc").edatagrid("getRowIndex", selectedrow);

                    var ed = $('#tt_disc').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'from_store_code'
                    });
                    $(ed.target).textbox('setValue', row.store_code);
                    $(ed.target).textbox('setText', row.store_name);
                },
                columns: [[
                    {field:'location_code',title:'Kode Lokasi',width:100},
                    {field:'location_name',title:'Nama Lokasi',width:150},
                    {field:'store_name',title:'Nama Store',width:250},
                ]],
                fitColumns: true,
                labelPosition: 'center'
            }
        }},
        {field:"to_store_code",   title:"To Store",      width: '15%', sortable: true, formatter: function(value, row){
            return row.to_store_name;
        },editor:{
            type:'textbox',
            options:{
                readonly:true
            }
        }},
        {field:"to_location_code",   title:"To Location",      width: '15%', sortable: true, formatter: function(value, row){
            return row.to_location_name;
        },editor:{
            type:'combogrid',
            options:{
                readonly:false,
                idField: 'location_code',
                textField:'location_name',
                required:true,
                hasDownArrow: false,
                remoteFilter:true,
                panelWidth: 500,
                multiple:false,
                panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
                    mousedown: function(){}
                }),
                editable: false,
                pagination: true,
                loadFilter: function (data) {
                    data.rows = [];
                    if (data.data) data.rows = data.data;
                    return data;
                },
                onSelect:function (index,row) {
                    console.log(row)
                    var selectedrow = $("#tt_disc").edatagrid("getSelected");
                    var rowIndex = $("#tt_disc").edatagrid("getRowIndex", selectedrow);

                    var ed = $('#tt_disc').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'to_store_code'
                    });
                    $(ed.target).textbox('setValue', row.store_code);
                    $(ed.target).textbox('setText', row.store_name);
                },
                columns: [[
                    {field:'location_code',title:'Kode Lokasi',width:100},
                    {field:'location_name',title:'Nama Lokasi',width:150},
                    {field:'store_name',title:'Nama Store',width:250},
                ]],
                fitColumns: true,
                labelPosition: 'center'
            }
        }},
        {field:"keterangan",   title:"Keterangan",      width: '15%', sortable: true,
            editor:{type:'textbox', options:{readonly:false}}},
        {field:"receive_date",   title:"Tanggal Terima",      width: '9%', sortable: true, formatter:function (value, row) {
            return row.ak_recv_date;
        },
            editor:{type:'datebox', options:{required:route==="in", readonly:route==="out"}}},
        {field:"rcvby",   title:"Terima Oleh",      width: '9%', sortable: true,
            editor:{type:'textbox', options:{readonly:route==="out"}}},
        {field:"status",   title:"Status",      width: '10%', sortable: true, editor:{
                type:'combobox',
                options:{
                    valueField:'status',
                    textField:'name',
                    prompt:'-Please Select-',
                    validType:'cekKeberadaan["#tt_disc","status"]',
                    data:statusDO(false),
                    required:true,
                    disabled:awalan==="DO2_1",
                    onChange:function (va) {
                        var selectedrow = $("#tt_disc").edatagrid("getSelected");
                        var rowIndex = $("#tt_disc").edatagrid("getRowIndex", selectedrow);

                        var ed = $('#tt_disc').edatagrid('getEditor',{
                            index:rowIndex,
                            field:'receive_date'
                        });
						if(ed){
							if(va==="APPROVED" || va==="RECEIVED"){
								$(ed.target).datebox({required:true, readonly:false, disabled:false});
                                let d = new Date();
                                var tgl =  ("0" + d.getDate()).slice(-2) + "/" + ("0" + (d.getMonth() + 1)).slice(-2) + "/" + d.getFullYear();
                                $(ed.target).datebox('setText',tgl);
                                $(ed.target).datebox('setValue',tgl);
							}else{
								$(ed.target).datebox({required:false, readonly:true, disabled:true});
							}
						}
                    }
                }
            }},
    ]],
    onSuccess: function(index, row){
        console.log(index);
        console.log(row);
        if(row.status===1) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: row.msg
            });
        }
        if(row.status===101 && awalan==="DO1") { /** alert setelah ubah status ke on delivery / received **/
            $.messager.confirm('Confirm','Apakah anda ingin export data DO?',function(r){
                if (r){
                    let urlss = base_url+"delivery/export_data/"+row.msg;
                    window.open(urlss, '_blank')
                }
            });
        }
        $('#tt_disc').edatagrid('reload');
        if(row.status===102){ /** auto select**/
            $('#tt_disc').edatagrid('selectRow', row.docno);
        }
    },
    onError:function(index, e){
        $.messager.show({
            title: 'Error',
            msg: e.message
        });
        $('#tt_disc').edatagrid('reload');
    },
};
function statusDO(e) {
    console.log("dosa")
    var xx = [];
    if(route==="out") {
        xx.push({status: 'OPEN', name: 'OPEN'});
        xx.push({status: 'ON DELIVERY', name: 'ON DELIVERY'});
    }else{
        if(e) {
            let row = getRow(false);
            if (row !== null) {
                if (row.to_store_code === store) {
                    xx.push({status: 'RECEIVED', name: 'RECEIVED'})
                    // xx.push({status: 'Received with Pending', name: 'Received with Pending'})
                }
            }
        }
    }
    return xx;
}
$(document).ready(function () {
    $('#tt_disc').edatagrid(options);
    $('#tt_disc').edatagrid('enableFilter');

    if(awalan === "PON" || awalan === "MPI"|| awalan === "DO1"|| awalan === "DO2_1"){
        $('#tt_disc').edatagrid('hideColumn', 'tgl_promo');
    }
});

function toolbarDO() {
    let tool = [];
    if(route==="out"){
        //return "#toolbar1";
        tool.push({
            iconCls: 'icon-add', id:'add', text:'New',
            handler: function(){addNew(awalan);}
        },
        //     {
        //     id:'delete', iconCls: 'icon-remove', text:'Delete',
        //     handler: function(){$('#tt_disc').edatagrid('destroyRow')}
        // }
        );
    }
    tool.push({
        id:'submit', iconCls: 'icon-save', text:'Submit',
        handler: function(){$('#tt_disc').edatagrid('saveRow')}
    },{
        id:'cancel', iconCls: 'icon-undo', text:'Cancel',
        handler: function(){$('#tt_disc').edatagrid('cancelRow')}
    },{
        iconCls: 'icon-shelf', text:'DO Items',
        handler: function(){
            let row = getRow(true);
            if(row == null) return;
            openBarangDO()
        }
    },{
        iconCls: 'icon-download', text:'Export CSV',
        handler: function(){
            let row = getRow(true);
            if(row == null) return;
            let urlss = base_url+"delivery/export_data/"+row.docno;
            window.open(urlss, '_blank')
        }
    });
    if(route==="in" /* receiving */){
        if(awalan==="PON"){
            tool.push({
                iconCls: 'icon-print', text:'Print',
                handler: function(){
                    let row = getRow(true);
                    if(row == null) return;
                    let urlss = `${base_url}delivery/print_data?route=${route}&golongan=${awalan}&tipe=-&nomor=${row.docno}`;
                    window.open(urlss, '_blank')
                }
            });
        }else if(awalan === "MPI"){
            tool.push({
                iconCls: 'icon-print', text:'Print',
                handler: function(){
                    let row = getRow(true);
                    if(row == null) return;
                    let urlss = `${base_url}delivery/print_data?route=${route}&golongan=${awalan}&tipe=-&nomor=${row.docno}`;
                    window.open(urlss, '_blank')
                }
            });
        }
    }else /*sending*/{
        if(awalan==="DO2"){
            tool.push({
                iconCls: 'icon-print', text:'Print',
                handler: function(){
                    let row = getRow(true);
                    if(row == null) return;
                    // let urlss = `${base_url}delivery/print_data?route=${route}&golongan=${awalan}&tipe=-&nomor=${row.docno}`;
                    // window.open(urlss, '_blank')
                    showOption(row);
                }
            },{
                iconCls: 'icon-close', text:'Batal',
                handler: function(){
                    let row = getRow(true);
                    if(row === null) return;
                    if(row.status==="OPEN"){
                        $.messager.confirm('Confirm','Apakah anda ingin membatalkan DO?',function(r){
                            if (r){
                                row.status="CANCELED";
                                row.pesan = "DO berhasil di batalkan";
                                changeStatus(row);
                            }
                        });
                    }
                }
            });
        }
        if(awalan==="DO1"){
            tool.push({
                iconCls: 'icon-print', text:'Print',
                handler: function(){
                    let row = getRow(true);
                    if(row == null) return;
                    let urlss = `${base_url}delivery/print_data?route=${route}&golongan=${awalan}&tipe=-&nomor=${row.docno}`;
                    window.open(urlss, '_blank')
                }
            });
        }
    }
	return tool;
    //return "#toolbar2";
}
var print_selected = undefined;
function showOption(row) {
    print_selected = undefined;
    $.messager.confirm({
        title:'Option print',
        msg:`<p>Select Print Option</p>
            <input class="easyui-combobox" data-options="
                valueField: 'label',
                textField: 'value',
                data: [{
                    label: '1',
                    value: 'Packing List',
                    selected:true
                },{
                    label: '2',
                    value: 'Surat Jalan'
                },{
                    label: '3',
                    value: 'Retail Price'
                },{
                    label: '4',
                    value: 'Cost (HPP)'
                }],
                onSelect:function(rec){
                    print_selected = rec;
                }"
                 />`,
        fn: function(r){
            if (r){
                let urlss = `${base_url}delivery/print_data?route=${route}&golongan=${awalan}&tipe=${print_selected.label}&nomor=${row.docno}`;
                window.open(urlss, '_blank')
            }
        }
    });
}
function addNew(code){
	console.log(code);
    prefix = code;
	console.log(prefix);
    $('#tt_disc').edatagrid('addRow',0)
}

function openBarangDO() {
    let row = getRow(true);
    if(row===null) return
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','DO Items ('+row.docno+')');
    $("#dd").edatagrid({
        url: base_url+"delivery/load_grid_nobar/"+row.docno,
        saveUrl: base_url+"delivery/save_data_nobar/"+row.docno,
        updateUrl: base_url+"delivery/edit_data_nobar",
        destroyUrl: base_url+"delivery/delete_data_nobar",
        fitColumns:true,
        idField:'id',
        height:'100%',
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:false,
        onAfterEdit:function(index, row){
            if(row.isNewRecord) $('#dd').edatagrid('reload');
        },
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
            }
            return data;
        },
        onBeginEdit: function(index,row){
            var editor = $(this).edatagrid('getEditor', {index:index,field:'nobar'});
            var grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');
        },
        onBeforeEdit: function(index, rr){
            if(row.status==="RECEIVED"){
                $.messager.show({
                    title: 'Warning',
                    msg: "Data tidak bisa di edit"
                });
                setTimeout(function () {
                    $("#dd").edatagrid('cancelRow');
                },500)
            }
        },
        toolbar:button_detail(row),
        columns:[
            fields(row),
        ],
		onLoadSuccess:function(){
			authbutton();
            var jml = $('#dd').edatagrid('getRows');
            console.log(jml);
            if(jml.length>0){
                $('#change').linkbutton({disabled:false});
            }else $('#change').linkbutton({disabled:true});
		},
        onSuccess: function(index, row){
            $('#dd').edatagrid('reload');
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    });
    $('#dd').edatagrid('hideColumn', 'status');
    $('#dd').edatagrid('hideColumn', 'docno');
    $('#dd').edatagrid('hideColumn', 'nmbar');
    $('#dd').edatagrid('hideColumn', 'id');
}
function changeStatus(data) {
    $.ajax({
        url: base_url+"delivery/edit_data",
        type: 'post',
        data: data,
        success: function(result){
            var res = $.parseJSON(result);
            if (res.status===1){
                $.messager.alert("Error", res.msg)
            }else{
                if(data.status==="ON DELIVERY" && awalan==="DO1") { /** alert setelah ubah status ke on delivery / received **/
                    $.messager.confirm('Confirm',data.pesan+'<br /> Apakah anda ingin export data DO?',function(r){
                        if (r){
                            let urlss = base_url+"delivery/export_data/"+res.msg;
                            window.open(urlss, '_blank')
                        }
                    });
                }else{
                    $.messager.alert("Success", data.pesan)
                }
            }
        }
    });
}
function button_detail(row) {
    var data = {
        docno:row.docno,
        doc_date:row.doc_date,
        receive_date:row.receive_date,
        tgl_promo:row.tgl_promo,
        from_store_code:row.from_store_code,
        from_location_code:row.from_location_code,
        to_store_code:row.to_store_code,
        to_location_code:row.to_location_code,
        keterangan:row.keterangan,
    };
    if(awalan==="PON"){
        if(row.status==="OPEN"){
            return [
                {id:'change', iconCls: 'icon-save', text:'Submit : On Delivery', handler: function(){
                    data.status = "ON DELIVERY";
                    data.pesan = "Berhasil Ubah status ke ON DELIVERY";
                    changeStatus(data);
                }}
            ]
        }else if(row.status==="ON DELIVERY"){
            var date = new Date();
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            var tgl = (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
            return [
                {id:'change', iconCls: 'icon-save', text:'Submit : Received', handler: function(){
                    data.receive_date=tgl;
                    data.status="RECEIVED";
                    data.pesan="Berhasil terima DO";
                    changeStatus(data);
                }}
            ]
        }
        return [];
    }else{
        var onDeliv = {id:'change', iconCls: 'icon-save', text:'Submit : On Delivery', handler: function(){
            data.status = "ON DELIVERY";
            data.pesan = "Berhasil Ubah status ke ON DEELIVERY";
            changeStatus(data);
        }};
        var onReceive = {id:'change', iconCls: 'icon-save', text:'Submit : Received', handler: function(){
            data.receive_date=tgl;
            data.status="RECEIVED";
            data.pesan="Berhasil terima DO";
            changeStatus(data);
        }};

        var btns = [
            {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
                if(row.status==="OPEN") {
                    if(row.from_store_code===store || kode_store_pusat===store) {
                        $("#dd").edatagrid('addRow',0);
                    }else{
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: `Hanya ${row.from_store_name} yang dapat Add`
                        });
                    }
                }else if(row.status==="ON DELIVERY") {
                    if(row.to_store_code===store || kode_store_pusat===store) {
                        $("#dd").edatagrid('addRow',0);
                    }else{
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: `Hanya ${row.to_store_name} yang dapat Add`
                        });
                    }
                }else{
                    $.messager.show({    // show error message
                        title: 'Error',
                        msg: `Status DO sudah ${row.status}, tidak boleh Add`
                    });
                }
            }},
            {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
                if(row.status==="OPEN"){
                    if(row.from_store_code===store) {
                        $("#dd").edatagrid('destroyRow');
                    }else{
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: `Hanya ${from_store_name} yang dapat menghapus`
                        });
                    }
                }else{
                    $.messager.show({    // show error message
                        title: 'Error',
                        msg: 'Data tidak bisa dihapus'
                    });
                }
            }},
            {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
                $("#dd").edatagrid('saveRow');
            }},
            {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function(){
                $("#dd").edatagrid('cancelRow');
            },
            }];
        if(row.status==="OPEN"){
            btns.push(onDeliv)
        }else if(row.status==="ON DELIVERY"){
            btns.push(onReceive)
        }

        if(route==="out"){
            if(awalan==='DO2'){
                btns.push({
                    iconCls: 'icon-print', text:'Print',
                    handler: function(){
                        showOption(row);
                    }
                })
            }else if(awalan==="DO1"){
                btns.push({
                    iconCls: 'icon-print', text:'Print',
                    handler: function(){
                        let urlss = `${base_url}delivery/print_data?route=${route}&golongan=${awalan}&tipe=-&nomor=${row.docno}`;
                        window.open(urlss, '_blank')
                    }
                })
            }
        }
        return btns;
    }
}

function fields(row) {
    var ff = [];
    ff.push({field:"id",   title:"ID",      width: '5%', sortable: true, editor:{type:'textbox', options:{disabled:false, readonly:true}}});
    ff.push({field:"docno",   title:"Nomor DO",      width: '8%', sortable: true, editor:{type:'textbox', options:{disabled:false, readonly:true}}})
    let nobar = false;
    let qty_send = false;
    let qty_recv = false;
    let qty_rev = false;

    let r_send = false;
    let r_recv = false;
    let r_revs = false;
    if(route==="out"){
        if(row.status==="OPEN"){
            nobar = false;
            qty_send = false;
            qty_recv = true;
            qty_rev = true;

            if(row.from_store_code===store){
                r_send = true;
                r_recv = r_revs = false;
            }
        }else if(row.status==="ON DELIVERY"){
            qty_rev = false;
            if(row.to_store_code===store){
                r_recv = true;
                r_send = r_revs = false;
            }
        }else{
            nobar = true;
            qty_send = true;
            qty_recv = true;
            qty_rev = true;
        }
    }else{
        nobar = true;
        qty_send = true;
        qty_rev = true;
        if(row.status==="ON DELIVERY"){
            qty_recv = false;
            if(row.to_store_code===store){
                r_recv = true;
                r_send = r_revs = false;
            }
        }else{
            qty_recv = true;
        }
    }
    ff.push({field:"nobar",   title:"SKU",      width: '10%', sortable: true,editor:{
        type:'combogrid',
        options:{
            readonly:nobar,
            idField: 'nobar',
            textField:'nobar',
            url:base_url+"delivery/get_product/"+row.docno,
            required:true,pageSize:20,
            clientPaging: false,
            remoteFilter: true,
            rownumbers: false,
            hasDownArrow: false,
            panelWidth: 500,
            multiple:false,
            panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
                mousedown: function(){}
            }),
            editable: false,
            pagination: true,
            loadFilter: function (data) {
                data.rows = [];
                if (data.data) data.rows = data.data;
                return data;
            },
            columns: [[
                {field:'nobar',title:'No Item',width:100},
                {field:'product_code',title:'Product Code',width:100},
                {field:'nmbar',title:'Product Name',width:200},
                {field:'satuan_jual_desc',title:'UOM',width:100, formatter:function(value, row){
                    if(awalan === "DO2") return row.uom_id+" | "+row.satuan_jual_desc;
                    else return row.uom_stock+" | "+row.satuan_stock_desc;
                }},
            ]],
            fitColumns: true,
            labelPosition: 'center',
            onSelect: function(index, rr) {
                var selectedrow = $("#dd").edatagrid("getSelected");
                var rowIndex = $("#dd").edatagrid("getRowIndex", selectedrow);

                var ed = $('#dd').edatagrid('getEditor',{
                    index:rowIndex,
                    field:'nmbar'
                });
                $(ed.target).textbox('setValue', rr.nmbar);
                ed = $('#dd').edatagrid('getEditor',{
                    index:rowIndex,
                    field:'product_code'
                });
                $(ed.target).textbox('setValue', rr.product_code);

                ed = $('#dd').edatagrid('getEditor',{
                    index:rowIndex,
                    field:'uom_id'
                });
                console.log("UKI",rr);
                if(awalan === "DO2") {
                    $(ed.target).textbox('setValue', rr.uom_id);
                    $(ed.target).textbox('setText', rr.satuan_jual_desc);
                }else{
                    $(ed.target).textbox('setValue', rr.uom_stock);
                    $(ed.target).textbox('setText', rr.satuan_stock_desc);
                }



                if(awalan !== "PON" && awalan !== "MPI" && awalan !== "DO2_1" && awalan !=="DO1") {
                    ed = $('#dd').edatagrid('getEditor', {
                        index: rowIndex,
                        field: 'qty'
                    });
                    var qty = $(ed.target).numberbox('getValue');

                    $.ajax({
                        type: "POST",
                        url: `${base_url}salesorder/get_unit_price?product_id=${rr.product_id}&tanggal=${row.tgl_promo}&lokasi=${row.to_location_code}&customer_code=`,
                        dataType: "json",
                        success: function (result) {
                            console.log(result)
                            ed = $('#dd').edatagrid('getEditor', {
                                index: rowIndex,
                                field: 'retail_price'
                            });
                            if (result.unit_price > 0) {
                                $(ed.target).textbox('setValue', result.unit_price);
                            } else {
                                $.messager.alert("Error", "Unit Price Belum tersedia")
                                $(ed.target).textbox('setValue', 0);
                            }

                            ed = $('#dd').edatagrid('getEditor', {
                                index: rowIndex,
                                field: 'discount'
                            });
                            if (result.unit_price > 0) $(ed.target).textbox('setValue', result.diskon);
                            else $(ed.target).textbox('setValue', 0);

                            var disc = result.unit_price * (result.diskon / 100);
                            var perunit = result.unit_price - disc;
                            ed = $('#dd').edatagrid('getEditor', {
                                index: rowIndex,
                                field: 'net_price'
                            });
                            if (perunit > 0) $(ed.target).textbox('setValue', perunit);
                            else $(ed.target).textbox('setValue', 0);
                        }
                    });
                }
            }
        }
    }});
    ff.push({field:"product_code",   title:"Product Code",      width: '20%', sortable: true, editor:{type:'textbox', options:{disabled:false, readonly:true}}});
    ff.push({field:"nmbar",   title:"Nama Barang",      width: '20%', sortable: true, editor:{type:'textbox', options:{disabled:false, readonly:true}}});
    if(awalan === "DO2") {
        ff.push({field: "uom_id", title: "UOM Jual", width: '20%', sortable: true, editor: {type: 'textbox', options: {disabled: true, readonly: true}}});
    }else {
        ff.push({field: "uom_id", title: "UOM Stok", width: '20%', sortable: true, editor: {type: 'textbox', options: {disabled: true, readonly: true}}});
    }
    if(awalan !== "PON" && awalan !== "MPI" && awalan !=="DO2_1" && awalan !=="DO1") {
        ff.push({field:"retail_price", title:'Retail Price', width: '10%', sortable: true, formatter:numberFormat, editor:{
            type:"numberbox",
            options:{readonly:true}
        }});
        ff.push({field:"discount", title:'Discount', width: '10%', sortable: true, formatter:numberFormat, editor:{
            type:"numberbox",
            options:{readonly:true}
        }});
    }
    ff.push({field:"qty",   title:"Qty Send",      width: '10%', sortable: true, formatter:numberFormat, editor:{
        type:'numberbox',
        options:{disabled:false, readonly:qty_send, required:r_send,
            inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                keyup:function(e){
                    var selectedrow = $("#dd").edatagrid("getSelected");
                    var rowIndex = $("#dd").edatagrid("getRowIndex", selectedrow);
                    var ed = $('#dd').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'retail_price'
                    });
                    var retail = $(ed.target).numberbox('getValue');
                    ed = $('#dd').edatagrid('getEditor',{
                        index:rowIndex,
                        field:'discount'
                    });
                    var disc = $(ed.target).numberbox('getValue');
                    ed = $('#dd').edatagrid('getEditor', {
                        index: rowIndex,
                        field: 'net_price'
                    });

                    var diskon = retail*(disc/100);
                    var perunit = retail-diskon;
                    if(perunit>0) $(ed.target).numberbox('setValue', perunit);
                    else $(ed.target).numberbox('setValue', 0);
                },
            })
        }}});
    ff.push({field:"qty_rcv",   title:"Qty Receive",      width: '10%', sortable: true, formatter:numberFormat, editor:{
        type:'numberbox',
        options:{disabled:false, readonly:qty_recv, required:r_recv}}});
    if(awalan !== "PON" && awalan !== "MPI" && awalan !=="DO2_1" && awalan !=="DO1") {
        ff.push({
            field: "net_price", title: 'Net Price', width: '10%', sortable: true, formatter: numberFormat, editor: {
                type: "numberbox",
                options: {readonly: true}
            }
        });
    }
    // ff.push({field:"qty_rev",   title:"Qty Revisi",      width: '10%', sortable: true, formatter:numberFormat, editor:{
    //     type:'numberbox',
    //     options:{disabled:false,readonly:qty_rev, required:r_revs}}});
    ff.push({field:"status",   title:"Status",      width: '8%', sortable: true, editor:{
        type:'textbox',
        options:{disabled:false, readonly:true}}});
    ff.push({field:"keterangan",   title:"Keterangan",      width: '19%', sortable: true, editor:{
        type:'textbox',
        options:{disabled:false, readonly:false}}});
    return ff;
}

function getRow(bool) {
    var row = $('#tt_disc').edatagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data.'
            });
        }
        return null;
    }else{
        row.record = $('#tt_disc').edatagrid("getRowIndex", row);
    }
    return row;
}