var options={
    url: base_url+"masterproduct/load_grid/"+kelompok,
    fitColumns:true,
    width:"100%",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"sku",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
        iconCls: 'icon-eye',
        text:'View',
        handler: function(){
            viewdata()
        }
    },{
        iconCls: 'icon-add', id:'add',
        text:'New',
        handler: function(){
            addnew()
        }
    },{
        id:'edit',
        iconCls: 'icon-edit',
        text:'Edit',
        handler: function(){
            editData()
        }
    },{
        id:'delete',
        iconCls: 'icon-remove',
        text:'Delete',
        handler: function(){
            deleteData()
        }
    },{
        id:'lokasi',
        iconCls: 'icon-location',
        text:'Lokasi Barang',
        handler: function(){
            openLocation();
        }
    },{
        id:'multi',
        iconCls: 'icon-coins',
        text:'Multi Price',
        handler: function(){
            openMultiPrice();
        }
    },
        /**{
        id:'add_cost',
        iconCls: 'icon-coins',
        text:'Additional Cost',
        handler: function(){
            openAdditionalCost();
        }
    },**/
    //     {
    //     id:'harga_beli',
    //     iconCls: 'icon-info',
    //     text:'Info Pembelian',
    //     handler: function(){
    //         // deleteData()
    //     }
    // },
        {
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            var rules  = $("#dg").datagrid('options').filterRules;
            console.log("disini",rules)
            let field=[];
            let op=[];
            let value=[];
            for(let i=0; i<rules.length; i++){
                field.push(rules[i].field);
                op.push(rules[i].op);
                value.push(rules[i].value);
            }
            let x = field.join(",");
            let x1 = op.join(",");
            let x2 = value.join(",");
            let urlss = base_url+"masterproduct/export_data?field="+x+"&op="+x1+"&value="+x2;
            console.log("disini",urlss)
            window.open(urlss, '_blank')
        }
    },{
            iconCls:'icon-eye',
            text:'Image',
            handler:function () {
                let row = getRow(true);
                if(row===null) return;
                if(row.gambar==="") return $.messager.alert("Error","Gambar tidak terlampir")
                window.open(row.gambar, '_blank')
            }
        }
        // {
        //     id:'upload',
        //     iconCls: 'icon-upload',
        //     text:'Upload',
        //     disabled:true,
        //     handler: function(){
        //         openUpload();
        //     }
        // }
    ],
    loadFilter: function(data){
        data.rows = [];
        if (data.data){
            data.rows = data.data;
            return data;
        } else {
            return data;
        }
    },
    // view: detailview,
    // detailFormatter:function(index,row){
    //     return `<div style="padding:2px;position:relative; width: 860px;">
    //                 <table id="ddv${index}" class="easyui-edatagrid">
    //                 </table>
    //             </div>`;
    // },
    onExpandRow:function (index, row) {
        let ddv = $('#ddv'+index).edatagrid({
            onError:function(index, e){
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            },
            onAfterEdit:function(data){
                ddv.edatagrid('reload');
            },
            onSave: function(index, row){
                ddv.edatagrid('reload');
            },
            onDestroy:function(index, row){
                setTimeout(function(){
                    $('#dg').datagrid('fixDetailRowHeight',index);
                },500);
            },
            idField:'nobar',
            url: base_url+"product/load_grid/"+row.id,
            saveUrl: base_url+"product/save_data/"+row.id,
            method:"POST",
            updateUrl: base_url+"product/edit_data",
            destroyUrl: base_url+"product/delete_data",
            loadFilter: function(data){
                data.rows = [];
                if (data.data){
                    data.rows = data.data;
                    return data;
                } else {
                    return data;
                }
            },
            toolbar:[{
                iconCls: 'icon-add', id:'add',
                text:'New',
                handler: function(){
                    ddv.edatagrid('addRow',0)
                    var count = $('#dg').datagrid('getRows').length;
                    for(var i=0; i<count; i++){
                        $('#dg').datagrid('fixDetailRowHeight',i);
                    }
                }
            },{
                id:'delete',
                iconCls: 'icon-remove',
                text:'Delete',
                handler: function(){
                    var ss = ddv.edatagrid('getSelected');
                    console.log(ss);
                    ddv.edatagrid('destroyRow')
                }
            },{
                id:'submit',
                iconCls: 'icon-save',
                text:'Submit',
                handler: function(){
                    ddv.edatagrid('saveRow')
                }
            },{
                id:'cancel',
                iconCls: 'icon-undo',
                text:'Cancel',
                handler: function(){
                    ddv.edatagrid('cancelRow')
                    var count = $('#dg').datagrid('getRows').length;
                    for(var i=0; i<count; i++){
                        $('#dg').datagrid('fixDetailRowHeight',i);
                    }
                }
            }],
            onResize:function(){
                $('#dg').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
                setTimeout(function(){
                    $('#dg').datagrid('fixDetailRowHeight',index);
                },500);
            },
            columns:[[
                {field:"nobar",   title:"No. Item",      width: '15%', sortable: true, editor:{
                        type:'textbox',
                        options:{
                            disabled:false,
                            readonly:true
                        }
                    }},
                {field:"nmbar",   title:"Nama Item",      width: '42%', sortable: true, editor:{
                        type:'textbox',
                        options:{
                            disabled:false,
                            readonly:true
                        }
                    }},
                {field:"warna",   title:"Colour",      width: '25%', sortable: true, editor:{
                        type:'combobox',
                        options:{
                            valueField:'art_colour_code',
                            textField:'description',
                            url:base_url+"product/get_colour/"+row.article_code+"/"+row.product_code,
                            multiple:true,
                            panelHeight:'auto',
                            required:true,
                            prompt:'-Please Select-',
                            validType:'cekKeberadaan["#ddv'+index+'","warna"]',
                            loadFilter: function (data) {
                                return data.data;
                            },
                            onSelect:function (rr) {
                                console.log(rr);
                                if(row.art_colour_code==="") return
                                var selectedrow = ddv.edatagrid("getSelected");
                                var rowIndex = ddv.edatagrid("getRowIndex", selectedrow);

                                let xx = $(this).combobox('getValues');
                                let dt = $(this).combobox('getData');
                                xx.push(rr.art_colour_code);

                                if(row.convertion===null){
                                    $.messager.show({
                                        title: 'Error',
                                        msg: `UOM Convertion ${row.unit_jual} ke PCS belum tersedia`
                                    });
                                    return
                                }
                                if(xx.length > row.convertion){
                                    $.messager.show({
                                        title: 'Error',
                                        msg: "Jumlah warna yang di pick, harus "+row.convertion
                                    });
                                    return;
                                }

                                var ed = ddv.edatagrid('getEditor',{
                                    index:rowIndex,
                                    field:'nmbar'
                                });
                                let ss = row.product_name;
                                // for(let i=0; i<dt.length; i++){
                                //     for(let j=0; j<xx.length; j++){
                                //         if(dt[i].art_colour_code === xx[j]){
                                //             ss += ", "+dt[i].art_colour_code
                                //         }
                                //     }
                                // }
                                $(ed.target).textbox('setText',ss);
                                $(ed.target).textbox('setValue',ss);
                            },
                            onUnselect:function (rr) {
                                var selectedrow = ddv.edatagrid("getSelected");
                                var rowIndex = ddv.edatagrid("getRowIndex", selectedrow);

                                let xx = $(this).combobox('getValues');
                                let dt = $(this).combobox('getData');
                                xx.splice( xx.indexOf(rr.art_colour_code), 1 );

                                console.log(xx);
                                console.log(dt);

                                var ed = ddv.edatagrid('getEditor',{
                                    index:rowIndex,
                                    field:'nmbar'
                                });
                                let ss = row.product_name;
                                // for(let i=0; i<dt.length; i++){
                                //     for(let j=0; j<xx.length; j++){
                                //         if(dt[i].art_colour_code === xx[j]){
                                //             ss += ", "+dt[i].art_colour_code
                                //         }
                                //     }
                                // }
                                $(ed.target).textbox('setText',ss);
                                $(ed.target).textbox('setValue',ss);
                            }
                        }
                }},
                {field:"soh",   title:"SOH",      width: '20%', sortable: true, formatter:numberFormat, editor:{type:'numberbox',options:{required:false}}},
                // {field:"min_stock",   title:"Min",      width: '10%', sortable: true, formatter:numberFormat, editor:{type:'numberbox',options:{required:true}}},
                // {field:"max_stock",   title:"Max",      width: '10%', sortable: true, formatter:numberFormat, editor:{type:'numberbox',options:{required:true}}}
            ]],
        })
    },
    columns:[fields()],
    onLoadSuccess:function(){
        $('#lokasi').linkbutton({disabled:true});
        $('#multi').linkbutton({disabled:true});
        $('#harga_beli').linkbutton({disabled:true});
        $('#add_cost').linkbutton({disabled:true});
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
		if(kelompok==="Bahan Baku"){
			// $('#upload').linkbutton({disabled:false});
            $('#upload').linkbutton({disabled:true});
            $('#upload').hide();
		}else{
			$('#upload').linkbutton({disabled:true});
			$('#upload').hide();
		}
        disable_enable(true);
		authbutton();
    },
    onSelect: function(index, row) {
        $('#lokasi').linkbutton({disabled:false});
        $('#multi').linkbutton({disabled:false});
        $('#harga_beli').linkbutton({disabled:false});
        $('#add_cost').linkbutton({disabled:false});

        $('#fm').form('load',row);
    }
};

function openUpload() {
   $('#dlg4').dialog('open').dialog('center').dialog('setTitle',`Upload`);
}
function cancelUpload() {
	$('#dlg4').dialog('close');
}
function submitUpload() {
    console.log("masuk sini");
    var iform = $('#formupload')[0];
    var data = new FormData(iform);
    data.append("jenis_barang", kelompok);
    data.append("satuan_stock", uom_stock);

	//console.log($("#userfile").filebox('getText'))
    $.ajax({
        url: base_url+"masterproduct/upload_data",
        type: 'post',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        data: data,
        success: function(result){
            var res = $.parseJSON(result);
            if (res.status===1){
                $.alert("Error", res.msg)
            }else{
				$.alert("Success", `Berhasil upload data. 
				<br/> Product : ${res.pc} of ${res.pct},
				<br/> Article : ${res.ac} of ${res.act},
				<br/> Article Colour : ${res.cc} of ${res.cct},
				<br/> Article Size : ${res.sc} of ${res.sct}`)
			}
            $('#dg').datagrid('reload');
            cancelUpload();
        }
    }); 
}

function fields() {
    let x=[];
    // x.push({field:"jenis_barang",   title:"Klasifikasi",      width: '15%', sortable: true});
    // x.push({field:"id",   title:"ID",      width: '9%', sortable: true});
    x.push({field:"sku",   title:"SKU",      width: '9%', sortable: true});
    x.push({field:"purchase_market",   title:"Purchases Market",      width: '10%', sortable: true});
    x.push({field:"product_code",   title:"Kode Produk",      width: '12%', sortable: true});
	x.push({field:"product_name",   title:"Nama Produk",      width: '15%', sortable: true});
    x.push({field:"unit_jual",   title:"UOM Jual",      width: '9%', sortable: true});
    //x.push({field:"unit_stock",   title:"UOM Stock",      width: '9%', sortable: true});
    x.push({field:"total_soh",   title:"SOH/Pcs",    formatter:numberFormat,  width: '7%', sortable: true});
    x.push({field:"status_product",   title:"Status",      width: '7%', sortable: true});
    
    //
    // x.push({field:"article_code",   title:"Article",      width: '12%', sortable: true});
    // x.push({field:"brand_name",   title:"Merk",      width: '15%', sortable: true});
    // x.push({field:"size_name",   title:"Ukuran",      width: '11%', sortable: true});
    // x.push({field:"class_name",   title:"Grup",      width: '14%', sortable: true});
    // x.push({field:"subclass_name",   title:"Subgrup",      width: '14%', sortable: true});
    if(kelompok!=="Barang Jadi"){
        x.push({field:"supplier_name",   title:"Supplier",      width: '15%', sortable: true});
    }

    // x.push({field:"unit_beli",   title:"UOM Beli",      width: '9%', sortable: true});
    x.push({field:"crtby",   title:"Create By",      width: "8%", sortable: true});
    x.push({field:"crtdt",   title:"Create Date",      width: "8%", sortable: true});
    x.push({field:"updby",   title:"Update By",      width: "8%", sortable: true});
    x.push({field:"upddt",   title:"Update Date",      width: "8%", sortable: true});

    return x;
}

var callFisrt = true;
$(document).ready(function () {
    initGrid();
});


function openMultiPrice(){
    let row = getRow(true);
    if(row==null) return
    console.log(row)
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Multi Price : ${row.product_code} - ${row.product_name}`);
    $('#prc').edatagrid('loadData', []);
    $('#prc').edatagrid({
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#prc').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
            $('#prc').edatagrid('reload');
        },
        toolbar:[{
            iconCls: 'icon-add', id:'add',
            text:'New',
            handler: function(){
                $('#prc').edatagrid('addRow',0)
            }
        },{
            id:'delete',
            iconCls: 'icon-remove',
            text:'Delete',
            handler: function(){
                $('#prc').edatagrid('destroyRow')
            }
        },{
            id:'submit',
            iconCls: 'icon-save',
            text:'Submit',
            handler: function(){
                $('#prc').edatagrid('saveRow')
            }
        },{
            id:'duplicate',
            iconCls: 'icon-copy',
            text:'Copy Harga',
            handler: function(){
                duplikat();
            }
        },{
            id:'cancel',
            iconCls: 'icon-undo',
            text:'Cancel',
            handler: function(){
                $('#prc').edatagrid('cancelRow')
            }
        }],
        onBeforeEdit: function(index, row){
            if(row.isNewRecord) return
            $.messager.show({
                title: 'Warning',
                msg: "Tidak boleh di edit, hanya bisa di hapus"
            });
            setTimeout(function () {
                $("#prc").edatagrid('cancelRow');
            },500)
        },
        url: base_url+"multiprice/load_grid/"+row.id,
        saveUrl: base_url+"multiprice/save_data/"+row.id,
        updateUrl: base_url+"multiprice/edit_data",
        destroyUrl: base_url+"multiprice/delete_data",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        sortName:"tanggalan",
        sortOrder:"desc",
        height:'100%',
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
		onLoadSuccess:function(){
			authbutton();
		},
        columns:[[
            {field:'ck',checkbox:true},
            // {field:"sku", title:'SKU', width: '7%', sortable: true, editor:{
            //     type:"textbox",
            //         options:{
            //         disabled:true,
            //             readonly:true
            //         }
            //     } },
            // {field:"product_name", title:'Product Name', width: '15%', sortable: true, editor:{
            //     type:"textbox",
            //         options:{
            //         disabled:true,
            //             readonly:true
            //         }
            //     }},
            {field:"description", title:'Price Type', width: '12%', sortable: true, editor:{
                type:"combobox",
                    options:{
                    url:base_url+"multiprice/get_customer_type",
                        valueField:'code',
                        textField:'description',
                        multiple:false,
                        panelHeight:'auto',
                        required:false,
                        prompt:'-Please Select-',
                        loadFilter: function (data) {
                            return data.data;
                        },
                    }
                }},
            {field:"eff_date", title:'Effective Date', width: '10%', sortable: true, formatter:function (index, row) {
                return row.eff_date2;
            }, editor:{
                type:"datebox",
                    options:{required:true}
                }},
            {field:"price_non_pkp", title:'Price Non PKP', width: '10%', sortable: true, formatter:numberFormat, editor:{
                type:"numberbox",
                    options:{
                        required:true,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function(e){
                                // var selectedrow = $("#prc").edatagrid("getSelected");
                                // var rowIndex = $("#prc").edatagrid("getRowIndex", selectedrow);
                                var rowIndex = 0;
                                var ed = $('#prc').edatagrid('getEditor',{
                                    index:rowIndex,
                                    field:'price_pkp'
                                });
                                $(ed.target).numberbox('setValue',e.target.value*1.10);
                                var ed2 = $('#prc').edatagrid('getEditor',{
                                    index:rowIndex,
                                    field:'price_tax'
                                });
                                $(ed2.target).numberbox('setValue',e.target.value*0.10);
                            },
                        })
                    }
                }},
            {field:"price_tax", title:'Tax (10%)', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{readonly:true}
                }},
            {field:"price_pkp", title:'Price PKP', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{
                        required:true,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function(e){
                                // var selectedrow = $("#prc").edatagrid("getSelected");
                                // var rowIndex = $("#prc").edatagrid("getRowIndex", selectedrow);
                                var rowIndex = 0;
                                var ed = $('#prc').edatagrid('getEditor',{
                                    index:rowIndex,
                                    field:'price_non_pkp'
                                });
                                let npkp = (e.target.value*100)/1.1/100;
                                $(ed.target).numberbox('setValue',npkp);
                                var ed2 = $('#prc').edatagrid('getEditor',{
                                    index:rowIndex,
                                    field:'price_tax'
                                });
                                $(ed2.target).numberbox('setValue',npkp*0.10);
                            },
                        })
                    }
                }},
            {field:"crtby",   title:"Create By",      width: '6%', sortable: true},
            {field:"crtdt",   title:"Create Date",      width: '7%', sortable: true},
            {field:"updby",   title:"Update By",      width: '6%', sortable: true},
            {field:"upddt",   title:"Update Date",      width: '7%', sortable: true},
        ]],
    });
    $('#prc').edatagrid({singleSelect:false});
    $('#prc').edatagrid({selectOnCheck:true});
    $('#prc').edatagrid({checkOnSelect:true});
    $('#prc').edatagrid('enableFilter');
}
function duplikat() {
    var rows = $('#prc').edatagrid('getSelections');
    if (rows.length===0) {
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to duplicate.'
        });
        return
    };
    var ids = [];
    for(var i=0; i<rows.length; i++){
        ids.push(rows[i].id);
    }
    // alert(ids.join('\n'));
    $.messager.confirm('Confirm','Anda yakin menduplikat multiparice untuk semua produk yang berbeda size dalam 1 article?',function(r){
        if (r){
            $.post(
                base_url+"multiprice/duplikat?ids="+ids,function(result){
                    var res = $.parseJSON(result);
                    if (res.status===1){
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: res.msg
                        });
                    } else {
                        $.messager.alert('Success', res.msg);
                        $('#prc').edatagrid('reload');    // reload the user data
                    }
                }
            );
        }
    });
}
function openAdditionalCost(){
    let row = getRow(true);
    console.log('disini',row);
    if(row==null) return;
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Additional Cost : ${row.product_code}`);
    $('#prc').edatagrid('loadData', []);
    $('#prc').edatagrid({
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        },
        onAfterEdit:function(data){
            $('#prc').edatagrid('reload');
        },
        onSave: function(index, row){
            $('#prc').edatagrid('reload');
        },
        onDestroy: function(index, row){
            $('#prc').edatagrid('reload');
        },
        toolbar:[
            {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
                $('#prc').edatagrid('addRow',0)
            }},
            {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
                $('#prc').edatagrid('destroyRow')
            }},
            {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
                $('#prc').edatagrid('saveRow')
            }},
            {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function(){
                $('#prc').edatagrid('cancelRow')
            }}
        ],
        url: base_url+"productcost/load_grid/"+row.id,
        saveUrl: base_url+"productcost/save_data/"+row.id,
        updateUrl: base_url+"productcost/edit_data",
        destroyUrl: base_url+"productcost/delete_data",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"periode",
        sortOrder:"desc",
        height:'100%',
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
        columns:[[
            {field:"sku", title:'SKU', width: '10%', sortable: true, editor:{
                type:"textbox",
                    options:{
                    disabled:true,
                        readonly:true
                    }
                } },
            {field:"purchase_market", title:'Purchase Marker', width: '10%', sortable: true, editor:{
                type:"combobox",
                    options:{
                        valueField:'purchase_market',
                        textField:'purchase_market2',
                        data:[
                            {purchase_market:"Local", purchase_market2:"Local"},
                            {purchase_market:"Import", purchase_market2:"Import"},
                        ],
                        required:true
                    }
                }},
            {field:"periode", title:'Effective Date', width: '10%', sortable: true,formatter:function (value, row) {
              return row.periode_ak;
            }, editor:{
                type:"datebox",
                    options:{required:true}
                }},
            {field:"hpp", title:'HPP Amount', width: '10%', sortable: true, formatter:numberFormat, editor:{
                type:"numberbox",
                    options:{
                        required:true,precision:2, min:0, formatter:formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                keyupnumber(e,'hpp');
                            },
                        })
                    }
                }},
            {field:"cost1", title:'Cost 1 (Amount)', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{required:true, min:0, precision:2, formatter:formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                keyupnumber(e, 'cost1')
                            },
                        })
                    }
                }},
            {field:"cost2", title:'Cost 2 (%)', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{min:0, precision:2, formatter:formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                keyupnumber(e, 'cost2')
                            },
                        })
                    }
                }},
            {field:"cost2_amt", title:'Amount', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{readonly:true, min:0, precision:2, formatter:formatnumberbox,}
                }},
            {field:"cost3", title:'Cost 3 (%)', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{min:0, precision:2, formatter:formatnumberbox,
                        inputEvents: $.extend({}, $.fn.numberbox.defaults.inputEvents, {
                            keyup:function (e) {
                                keyupnumber(e, 'cost3')
                            },
                        })}
                }},
            {field:"cost3_amt", title:'Amount', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{readonly:true, min:0, precision:2, formatter:formatnumberbox,}
                }},
            {field:"hpp_end", title:'HPP End', width: '10%', sortable: true, formatter:numberFormat, editor:{
                    type:"numberbox",
                    options:{readonly:true, min:0, precision:2, formatter:formatnumberbox,}
                }},
        ]],
    });
    $('#prc').edatagrid('enableFilter');
}
function keyupnumber(e, field){
    var selectedrow = $("#prc").edatagrid("getSelected");
    var rowIndex = $("#prc").edatagrid("getRowIndex", selectedrow);

    let arr = ['hpp','cost1','cost2','cost3'];
    let val = [0,0,0,0];

    for(let i=0; i<arr.length; i++){
        if(arr[i] === field) val[i] = e.target.value;
        else {
            var edt = $('#prc').edatagrid('getEditor',{
                index:rowIndex,
                field:arr[i]
            });
            let s = $(edt.target).numberbox('getValue');
            if(s==='') val[i] = 0;
            else val[i]=s;
        }
    }
    let n = val.map(Number);
    var amt1 = (n[0]+n[1])*(n[2]/100);
    var amt2 = amt1*(n[3]/100);
    var amt3 = n[0]+n[1]+((n[0]+n[1])*(n[2]/100))+(amt1*(n[3]/100));
    console.log(n)
    console.log(amt1)
    console.log(amt2)
    console.log(amt3)
    var ed = $('#prc').edatagrid('getEditor',{
        index:rowIndex,
        field:'cost2_amt'
    });
    $(ed.target).numberbox('setValue',isNaN(amt1)?0:amt1);
    ed = $('#prc').edatagrid('getEditor',{
        index:rowIndex,
        field:'cost3_amt'
    });
    $(ed.target).numberbox('setValue',isNaN(amt2)?0:amt2);
    ed = $('#prc').edatagrid('getEditor',{
        index:rowIndex,
        field:'hpp_end'
    });
    $(ed.target).numberbox('setValue',isNaN(amt3)?0:amt3);
}

function openLocation(){
    let rowhead = getRow(true);
    if(rowhead==null) return
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Product Location : ${rowhead.product_code}`);
    $('#prc').datagrid({
        url: base_url+"masterproduct/read_data/"+rowhead.id,
        fitColumns:true,
        width:"100%",
        height:'100%',
        method:"POST",
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:false,
        sortName:"sku",
        sortOrder:"asc",
        singleSelect:true,
        toolbar:[],
        loadFilter: function(data){
            console.log(data)
            data.rows = [];
            if (data.data){
                data.rows.push(data.data)
            }
            data.total = data.rows.length;
            return data;
        },
        columns:[[
            {field:'e', expander:true},
            {field:"sku", title:'SKU', width: '15%', sortable: true},
            {field:"product_code", title:'Product Code', width: '15%', sortable: true},
            {field:"product_name", title:'Product Name', width: '30%', sortable: true},
            {field:"satuan_jual_code", title:'UOM Jual', width: '10%', sortable: true},
            {field:"satuan_beli_code", title:'UOM Beli', width: '10%', sortable: true},
            {field:"total_soh", title:'SOH', width: '15%', formatter:function (value, row) {
                let soh = isNaN(row.total_soh)?"0":parseFloat(row.total_soh).toLocaleString('en');
                return soh+" "+row.uom_id;
            }, sortable: true},
        ]],
        onLoadSuccess:function(){
            $('#prc').datagrid('expandRow',0);
            setTimeout(function () {
                $('#prc').datagrid('fixDetailRowHeight',0);
            },1000);
        },
        view: detailview,
        detailFormatter:function(index,row){
            /**return `<div style="padding:2px;position:relative;">
                        <table style="width: 100%; height: 300px; vertical-align: top">
                            <td style="width: 50%; vertical-align: top">
                                <table class="ddvx1" style="width: 100%;"></table>
                            </td>
                            <td style="width: 50%; vertical-align: top">
                                <table class="ddvx2" style="width: 100%;"></table>
                            </td>
                        </table>
                    </div>`;**/
			return '<div style="width: 100%; height: 300px; vertical-align: top"><table class="ddvx2"></table></div>';
        },
        onExpandRow:function (index, row_l) {
            /**var ddv = $(this).datagrid('getRowDetail', index).find('table.ddvx1');
            ddv.datagrid({
                url:base_url+"masterproduct/stock_sku/"+rowhead.id,
                method:'POST',
                height:'100%',
                fitColumns:true,
                singleSelect:true,
                loadFilter: function(data){
                    data.rows = [];
                    if (data.data){
                        data.rows = data.data;
                    }
                    return data;
                },
                columns:[[
                    {field:"nobar", title:'No Item', width: '30%', sortable: true},
                    {field:"nmbar", title:'Nama Item', width: '50%', sortable: true},
                    {field:"soh", title:'SOH', width: '20%', align:'right', formatter:function (value, rr) {
                        let soh = isNaN(rr.soh)?"0":parseFloat(rr.soh).toLocaleString('en');
                        return soh+" "+row.uom_id;
                    }, sortable: true},
                ]],
                onResize:function(){
                    $('#prc').datagrid('fixDetailRowHeight',index);
                },
                onLoadSuccess:function(){
                    setTimeout(function(){
                        $('#prc').datagrid('fixDetailRowHeight',index);
                    },500);
                }
            });
            **/
			var ddv2 = $(this).datagrid('getRowDetail',index).find('table.ddvx2');
            ddv2.datagrid({
                url:base_url+"masterproduct/stock_location/"+rowhead.id,
                method:'POST',
                fitColumns:true,
                singleSelect:true,
                height:'100%',
                sortName:"soh",
                sortOrder:"desc",
                loadFilter: function(data){
                    data.rows = [];
                    if (data.data){
                        data.rows = data.data;
                    }
                    return data;
                },
                columns:[[
                    {field:"location_code", title:'Kode Lokasi', width:'20%', sortable: true},
                    {field:"location_name", title:'Nama Lokasi', width:'30%', sortable: true},
                    {field:"soh", title:'Stock Lokasi', align:'right', width:'20%', formatter:function (value, rr) {
                        let soh = isNaN(rr.soh)?"0":parseFloat(rr.soh).toLocaleString('en');
                        return soh+" "+row_l.uom_id;
                    }, sortable: true},
                    {field: "action", title: "Action", width: "10%", formatter: function (value, row) {
                        return `<a href="#" onclick="showMutasiHistory('${row.location_code}', '${row.location_name}', '${row_l.sku}');" title="History" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                                            <span class="l-btn-left l-btn-icon-left">
                                            <span class="l-btn-text l-btn-empty">&nbsp;</span>
                                            <span class="l-btn-icon icon-eye">&nbsp;</span></span>
                                            </a>`;
                    }}
                ]],
                onResize:function(){
                    $('#prc').datagrid('fixDetailRowHeight',index);
                },
                onLoadSuccess:function(){
                    setTimeout(function(){
                        $('#prc').datagrid('fixDetailRowHeight',index);
                    },500);
                },
                // view: detailview,
                // detailFormatter:function(index,row){
                //     return '<div style="width: 100%; vertical-align: top"><table class="ddvx"></table></div>';
                // },
                onExpandRow:function (index, row_l) {
                    let ddvx = $(this).datagrid('getRowDetail', index).find('table.ddvx');
                    ddvx.datagrid({
                        url:base_url+"masterproduct/stock_location_d/"+rowhead.id+"/"+row_l.location_code,
                        method:'POST',
                        fitColumns:true,
                        singleSelect:true,
                        loadFilter: function(data){
                            data.rows = [];
                            if (data.data){
                                data.rows = data.data;
                            }
                            return data;
                        },
                        height:'auto',
                        sortName:"soh",
                        sortOrder:"desc",
                        columns:[[
                            {field:"nobar", title:'No Item', width: '32%', sortable: true},
                            {field:"nmbar", title:'Nama Item', width: '40%', sortable: true},
                            {field:"soh", title:'SOH', width: '18%', align:'right', formatter:function (value, rr) {
                                let soh = isNaN(rr.soh)?"0":parseFloat(rr.soh).toLocaleString('en');
                                return soh+" "+row.uom_id;
                            }, sortable: true},
                            {field: "action", title: "Action", width: "10%", formatter: function (value, row) {
                                return `<a href="#" onclick="showMutasiHistory('${row_l.location_code}', '${row_l.location_name}', '${row.nobar}');" title="History" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                                            <span class="l-btn-left l-btn-icon-left">
                                            <span class="l-btn-text l-btn-empty">&nbsp;</span>
                                            <span class="l-btn-icon icon-eye">&nbsp;</span></span>
                                            </a>`;
                            }}
                        ]],
                        onResize:function(){
                            ddv2.datagrid('fixDetailRowHeight',index);
                        },
                        onLoadSuccess:function(){
                            setTimeout(function(){
                                ddv2.datagrid('fixDetailRowHeight',index);
                            },500);
                        }
                    });
                }
            });
            $('#prc').datagrid('fixDetailRowHeight',index);
        }
    });
}
function showMutasiHistory(location_code, location_name, nobar) {
    $('#dlg3').dialog('open').dialog('center').dialog('setTitle',`History Mutasi Pada : ${location_name}`);
    $('#mts').datagrid({
        url: base_url+"masterproduct/read_mutasi/"+nobar+"/"+location_code,
        fitColumns:true,
        width:"100%",
        height:"100%",
        method:"POST",
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:false,
        sortName:"periode",
        sortOrder:"desc",
        singleSelect:true,
        toolbar:[],
        loadFilter: function(data){
            console.log(data)
            data.rows = [];
            if (data.data){
                data.rows = data.data;
            }
            return data;
        },
        columns:[[
            {field:"periode", title:'Periode', width: '13%', formatter:function(index,row){
                if(row.periode==null) return "";
				return row.periode.substr(-2)+"/"+row.periode.substr(0,4)
			}, sortable: true},
            {field:"saldo_awal", title:'Begin', width: '11%', formatter:numberFormat, sortable: true},
            {field:"do_masuk", title:'DO In', width: '11%', formatter:numberFormat, sortable: true},
            {field:"do_keluar", title:'DO Out', width: '11%', formatter:numberFormat, sortable: true},
            {field:"penyesuaian", title:'Adjustment', width: '11%', formatter:numberFormat, sortable: true},
            {field:"penjualan", title:'Sales', width: '11%', formatter:numberFormat, sortable: true},
            {field:"pengembalian", title:'Sales Return', width: '11%', formatter:numberFormat, sortable: true},
            {field:"saldo_akhir", title:'Ending', width: '11%', formatter:numberFormat, sortable: true},
        ]],
        view: detailview,
        detailFormatter:function(index,row){
            return '<div style="width: 100%; height: 100%; vertical-align: top"><table class="ddvx"></table></div>';
        },
        onExpandRow:function (index, row_l) {
            let ddvx = $(this).datagrid('getRowDetail', index).find('table.ddvx');
            ddvx.datagrid({
                url:base_url+"masterproduct/read_mutasi_trx/"+row_l.periode+"/"+location_code+"/"+nobar,
                method:'POST',
                pagePosition:"top",
                resizeHandle:"right",
                fitColumns:true,
                resizeEdge:10,
                pageSize:20,
                clientPaging: false,
                remoteFilter: true,
                rownumbers: false,
                pagination:true, striped:true, nowrap:false,
                sortName:"tanggal",
                sortOrder:"desc",
                singleSelect:true,
                onResizeColumn:function(field, width){
                    var state = $.data(this, 'datagrid');
                    var opts = state.options;

                    var col = $(this).datagrid('getColumnOption', field);
                    col.width = width/(state.dc.view.width()-opts.scrollbarSize)*100+'%';
                    $(this).datagrid('resize');
                },
                loadFilter: function(data){
                    data.rows = [];
                    if (data.data){
                        data.rows = data.data;
                    }
                    return data;
                },
                height:'auto',
                columns:[[
                    {field:"tanggal", title:'Date', width: '15%', formatter:function (index, row) {
                        return row.tanggal2;
                    }, sortable: true},
                    {field:"jam", title:'Time', width: '15%', sortable: true},
                    {field:"tipe", title:'Type', width: '10%', sortable: true},
                    {field:"trx", title:'Trx No', width: '20%', sortable: true},
                    {field:"qty", title:'Qty Balance', width: '15%', sortable: true},
                    {field:"remark", title:'Keterangan', width: '25%', sortable: true},
                ]],
                onResize:function(){
                    $('#mts').datagrid('fixDetailRowHeight',index);
                },
                onLoadSuccess:function(){
                    setTimeout(function(){
                        $('#mts').datagrid('fixDetailRowHeight',index);
                    },500);
                }
            });
            $('#mts').datagrid('fixDetailRowHeight',index);
        }
    });
}

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
    // $('#dg').datagrid('enableFilter',[{
    //     field:'jenis_barang',
    //     type:'combobox',
    //     options:{
    //         disabled:true,
    //         panelHeight:'auto',
    //         data:[
    //             {value:'Barang Jadi',text:'Barang Jadi'},
    //             {value:'Bahan Baku',text:'Bahan Baku'},
    //             {value:'Accessories',text:'Accessories'},
    //             {value:'Packing',text:'Packing'},
    //             {value:'Spare Part',text:'Spare Part'},
    //             {value:'ATK',text:'ATK'},
    //             ],
    //         onChange:function(value){
    //             if (value === ''){
    //                 $('#dg').datagrid('removeFilterRule', 'jenis_barang');
    //             } else {
    //                 $('#dg').datagrid('addFilterRule', {
    //                     field: 'jenis_barang',
    //                     op: 'equal',
    //                     value: value
    //                 });
    //             }
    //             $('#dg').datagrid('doFilter');
    //         },
    //         onLoadSuccess:function () {
    //             $(this).combobox('setValue',kelompok)
    //         }
    //     }
    // }]);
}

function populateJenisBarang() {
    $('#jenis_barang').combobox({
        data:[
            {value:'Barang Jadi',text:'Barang Jadi'},
            {value:'Bahan Baku',text:'Bahan Baku'},
            {value:'Accessories',text:'Accessories'},
            {value:'Packing',text:'Packing'},
            {value:'Spare Part',text:'Spare Part'},
            {value:'ATK',text:'ATK'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#jenis_barang"]',
    });
    $('#jenis_barang').combobox({disabled:false, readonly:true, width:'100%'});
    // var fltr = $('#dg').datagrid('getFilterRule','jenis_barang');
    $('#jenis_barang').combobox('select',kelompok);
}
function populateUOM() {
    let row = getRow(false);
    console.log("uom",row);
    $('#satuan_beli').combobox({
        url: base_url+"masterproduct/get_uom",
        valueField: 'uom_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#satuan_beli"]',
        loadFilter: function (data) {
            return data.data;
        }
    });
    $('#satuan_stock').combobox({
        url: base_url+"masterproduct/get_uom",
        valueField: 'uom_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#satuan_stock"]',
        loadFilter: function (data) {
            for(let i=0; i<data.data.length; i++){
                data.data[i].selected = data.data[i].default_unit > 0;
            }
            return data.data;
        }
    });
    $('#satuan_jual').combobox({
        url: base_url+"masterproduct/get_uom",
        valueField: 'uom_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#satuan_jual"]',
        loadFilter: function (data) {
            return data.data;
        },
        onSelect:function (index, row) {
            populateWarna();
        }
    });
    if(row!==null) $('#satuan_jual').combobox('setValue',row.satuan_jual)
    if(row!==null) $('#satuan_stock').combobox('setValue',row.satuan_stock)
    if(row!==null) $('#satuan_beli').combobox('setValue',row.satuan_beli)
}
function populateWarna() {
    let row = getRow(false);
    let artikel = $('#article_code').combogrid('getValue');
    console.log("populate warna1",row)
    console.log("populate warna2",artikel)
    if(artikel === ""){
        return $.alert('Error',"Pilih article terlebih dahulu");
    }
    $('#colour_code').combobox({
        valueField:'art_colour_code',
        textField:'description',
        url:base_url+"product/get_colour/"+artikel,
        multiple:true,
        panelHeight:'auto',
        required:true,
        prompt:'-Please Select-',
        validType:'inList["#colour_code"]',
        loadFilter: function (data) {
            return data.data;
        },
        onSelect:function (rr) {
            if(rr.art_colour_code==="") return
            var vall = $("#satuan_jual").combobox('getValue');
            var satuan_jual = $.grep($("#satuan_jual").combobox('getData'),function (row) {
                return row.uom_code === vall
            });
            if(satuan_jual===null) return

            let xx = $(this).combobox('getValues');
            var bool = 0;
            xx.forEach(item=>{
                if(item===rr.art_colour_code) bool++
            })
            if(bool===0) xx.push(rr.art_colour_code);

            if(satuan_jual[0].convertion===null){
                $.messager.show({
                    title: 'Error',
                    msg: `UOM Convertion ${row.unit_jual} ke PCS belum tersedia`
                });
                return
            }
            if(xx.length > satuan_jual[0].convertion){
                $.messager.show({
                    title: 'Error',
                    msg: "Jumlah warna yang di pick, harus "+satuan_jual[0].convertion
                });
                return;
            }
        },
        onUnselect:function (rr) {
            let xx = $(this).combobox('getValues');
            let dt = $(this).combobox('getData');
            xx.splice( xx.indexOf(rr.art_colour_code), 1 );
        }
    })
    if(row!==null) {
        $('#colour_code').combobox('setValues',row.colour_code.split(","))
        // $('#product_name').textbox('setText',row.product_name)
        // $('#product_name').textbox('setValue',row.product_name)
    }

}
function populateSize(id, name) {
    if(id==="") return;
    let row = getRow(false);
    $('#size_code').combobox({
        url: base_url+"masterproduct/get_size/"+id,
        valueField: 'size_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#size_code"]',
        loadFilter: function (data) {
            for(let i=0; i<data.data.length; i++){
                data.data[i].disabled = data.data[i].size_code===data.data[i].sel
            }
            return data.data;
        },
        onSelect:function (row) {
            // console.log("soebkie",row);
            // if(row.size_code==="") return
            // let x = $('#product_name').textbox('getText');
            // $('#product_name').textbox('setText',name+" "+row.description.split(" - ")[1])
            // $('#product_name').textbox('setValue',name+" "+row.description.split(" - ")[1])
        }
    });
    if(row!==null) {
        $('#size_code').combobox('select',row.size_code)
        $('#product_name').textbox('setText',row.product_name)
        $('#product_name').textbox('setValue',row.product_name)
    }
}
function populateColour() {
    $('#colour_code').combobox({
        url: base_url+"masterproduct/get_colour",
        valueField: 'colour_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#colour_code"]',
        loadFilter: function (data) {
            return data.data;
        }
    });
}
function populateCustomerType() {
    let row = getRow(false);
    $('#type_barang').combobox({
        url: base_url+"masterproduct/get_customer_type",
        valueField: 'type_barang',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#type_barang"]',
        loadFilter: function (data) {
            return data.data;
        }
    });
    if(row!=null) $('#type_barang').combobox('select',row.type_barang)
}
function populateSupplier() {
    let row = getRow(false);
    // $('#supplier_code').combobox({
    //     url: base_url+"masterproduct/get_supplier",
    //     valueField: 'supplier_code',
    //     textField: 'supplier_name',
    //     prompt:'-Please Select-',
    //     validType:'inList["#supplier_code"]',
    //     loadFilter: function (data) {
    //         return data.data;
    //     }
    // });
    // if(row!=null) $('#supplier_code').combobox('select',row.supplier_code)
    $('#supplier_code').combogrid({
        idField: 'supplier_code',
        textField:'supplier_name',
        url:base_url+"mastersupplier/load_grid",
        required:true,
        labelPosition:'top',
        tipPosition:'bottom',
        hasDownArrow: false,
        remoteFilter:true,
        panelWidth: 500,
        multiple:false,
        panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
            mousedown: function(){}
        }),
        editable: false,
        pagination: true,
        fitColumns: true,
        mode:'remote',
        loadFilter: function (data) {
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        columns: [[
            {field:'supplier_code',title:'Supplier Code',width:150},
            {field:'supplier_name',title:'Supplier Name',width:250},
            {field:'pkp',title:'PKP',width:150},
        ]]
    });
    var gr =  $('#supplier_code').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
    if(row!==null) {
        $('#supplier_code').combogrid('setValue',row.supplier_code)
    }
}
function populateSubclass(id) {
    if(id==="" || id===undefined) return;
    console.log(id);
    let row = getRow(false);
    $('#subclass_code').combobox({
        url: base_url+"masterproduct/get_subclass/"+id,
        valueField: 'subclass_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#subclass_code"]',
        loadFilter: function (data) {
            if(data.data) {
                return data.data;
            }else return [];
        }
    });
    if(row!=null) $('#subclass_code').combobox('select',row.subclass_code)
}
function populateClass() {
    let row = getRow(false);
    $('#class_code').combobox({
        url: base_url+"masterproduct/get_class",
        valueField: 'class_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#class_code"]',
        loadFilter: function (data) {
            return data.data;
        },
        onChange:function(newValue,oldValue){
            // console.log(newValue);
            // console.log(oldValue);
            $('#subclass_code').combobox('loadData',[]);
            if(newValue === "") return;
            populateSubclass(newValue);
        },
        // onSelect:function (row) {
        //     console.log(row);
        //     $('#subclass_code').combobox('clear');
        //     populateSubclass(row.class_code);
        // }
    });
    if(row!==null) $('#class_code').combobox('select',row.class_code)
}
function populateBrand() {
    let row = getRow(false);
    $('#brand_code').combobox({
        url: base_url+"masterproduct/get_brand",
        valueField: 'brand_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#brand_code"]',
        loadFilter: function (data) {
            return data.data;
        }
    });
    if(row!==null) $('#brand_code').combobox('select',row.brand_code)
}
function populateArticle() {
    let row = getRow(false);
    $('#article_code').combogrid({
        idField: 'article_code',
        textField:'article_name',
        url:base_url+"masterproduct/get_article",
        required:true,
        labelPosition:'top',
        tipPosition:'bottom',
        hasDownArrow: false,
        remoteFilter:true,
        panelWidth: 500,
        multiple:false,
        panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
            mousedown: function(){}
        }),
        editable: false,
        pagination: true,
        fitColumns: true,
        mode:'remote',
        loadFilter: function (data) {
            console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
            console.log("select",rw);
            if(rw.article_code==="") return
            let nama = rw.article_name;
            if(row!==null){
                nama += " "+row.size_name;
            }
            $('#product_name').textbox('setText',nama)
            $('#product_name').textbox('setValue',nama)
            populateSize(rw.article_code, rw.article_name);
        },
        columns: [[
            {field:'article_code',title:'Article Code',width:150},
            {field:'article_name',title:'Article Name',width:250},
            {field:'size_code',title:'Size Code',width:150},
            {field:'size_name',title:'Size Name',width:250},
        ]]
    });
    var gr =  $('#article_code').combogrid('grid')
    gr.datagrid('destroyFilter');
    gr.datagrid('enableFilter');
    if(row!==null) {
        $('#article_code').combogrid('setValue',row.article_code)
        $('#product_name').textbox('setText',row.product_name)
        $('#product_name').textbox('setValue',row.product_name)
    }
}

function clearInput() {
    disable_enable(true);
    $('#fm').form('clear');
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});
    $('#dlg').dialog('close');        // close the dialog
}

function addnew(){
    var row = getRow(false);
    if(row!==null) {
        $('#dg').datagrid('unselectRow', row.record);
    }

    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`New Product`);
    disable_enable(false);

    $('#id').textbox({disabled:true, readonly:true, width:'100%'});
    $('#sku').textbox({disabled:true, readonly:true, width:'100%'});
    $('#product_code').textbox({disabled:true, readonly:true, width:'100%'});
    $('#article_code').combogrid({disabled:false, readonly:false, width:'100%'});
    $('#supplier_code').combogrid({disabled:false, readonly:false, width:'100%'});

    $('#first_production').datebox({disabled:true, readonly:true, width:'100%', label:''});
    $('#last_production').datebox({disabled:true, readonly:true, width:'100%', label:''});

    $('#satuan_stock').combobox({disabled:false, readonly:true, width:'100%'});

    $('#first_production').datebox({disabled:true, readonly:true, width:'100%', label:''});
    $('#last_production').datebox({disabled:true, readonly:true, width:'100%', label:''});
    $('#purchase_market').textbox({disabled:true, readonly:true, width:'100%', label:''});
    $('#first_production').datebox('hide');
    $('#last_production').datebox('hide');
    $('#purchase_market').textbox('hide');

    $('#jenis_barang').combobox({disabled:false, readonly:true, width:'100%'});
    // var fltr = $('#dg').datagrid('getFilterRule','jenis_barang');
    // console.log(fltr);

    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    $('#jenis_barang').combobox('select',kelompok);
    flag = "masterproduct/save_data";
    populateArticle();
    populateBrand();
    populateClass();
    populateCustomerType();
    populateSupplier();
    populateUOM();
    populateJenisBarang();
}
function editData(){
    let row = getRow(true);
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"masterproduct/read_data/"+row.id,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Edit Product : ${data.data.product_name}`);
            disable_enable(false);
            $('#id').textbox({disabled:false, readonly:true, width:'100%'});
            $('#sku').textbox({disabled:false, readonly:true, width:'100%'});
            $('#product_code').textbox({disabled:false, readonly:true, width:'100%'});

            $('#size_code').combobox({disabled:true, readonly:true, width:'100%'});
            $('#article_code').combobox({disabled:true, readonly:true, width:'100%'});
            $('#satuan_jual').combobox({disabled:true, readonly:true, width:'100%'});
            $('#satuan_stock').combobox({disabled:true, readonly:true, width:'100%'});
            $('#satuan_beli').combobox({disabled:true, readonly:true, width:'100%'});

            $('#first_production').datebox({disabled:true, readonly:true, width:'100%', label:''});
            $('#last_production').datebox({disabled:true, readonly:true, width:'100%', label:''});
            $('#purchase_market').textbox({disabled:true, readonly:true, width:'100%', label:''});
            $('#first_production').datebox('hide');
            $('#last_production').datebox('hide');
            $('#purchase_market').textbox('hide');

            $('#article_code').combogrid({disabled:true, readonly:true, width:'100%'});
            $('#article_code').combogrid('setValue',data.data.article_code)

            $('#supplier_code').combogrid({disabled:false, readonly:false, width:'100%'});
            $('#supplier_code').combogrid('setValue',data.data.supplier_code)

            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "masterproduct/edit_data";

            populateArticle();
            populateBrand();
            populateClass();
            populateCustomerType();
            populateSupplier();
            populateUOM();
            populateJenisBarang();
        }
    });
}


function viewdata(){
    clearInput();
    let row = getRow();
    if(row===null) return;
    console.log("disini", row);
    $.ajax({
        type:"POST",
        url:base_url+"masterproduct/read_data/"+row.id,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','View Master Product');
            disable_enable(true)
            $('#fm').form('load',data.data);
            $('#first_production').datebox({disabled:true, readonly:true, width:'100%', label:''});
            $('#last_production').datebox({disabled:true, readonly:true, width:'100%', label:''});
            $('#purchase_market').textbox({disabled:true, readonly:true, width:'100%', label:'Purchases Market'});
            $('#first_production').datebox('hide');
            $('#last_production').datebox('hide');
            $('#purchase_market').textbox('show');

            $('#article_code').combogrid({disabled:true, readonly:true, width:'100%'});
            $('#article_code').combogrid('setValue',data.data.article_code);
            $('#article_code').combogrid('setText',row.article_name);


            $('#supplier_code').combogrid({disabled:true, readonly:true, width:'100%'});
            $('#supplier_code').combogrid('setValue',data.data.supplier_code);
            $('#supplier_code').combogrid('setText',row.supplier_name);

            $('#brand_code').combobox('setValue',data.data.brand_code);
            $('#brand_code').combobox('setText',row.brand_name);
            $('#class_code').combobox('setValue',data.data.class_code);
            $('#class_code').combobox('setText',row.class_name);
            $('#subclass_code').combobox('setValue',data.data.subclass_code);
            $('#subclass_code').combobox('setText',row.subclass_name);
            $('#satuan_beli').combobox('setValue',data.data.satuan_beli);
            $('#satuan_beli').combobox('setText',row.unit_beli);
            $('#satuan_stock').combobox('setValue',data.data.satuan_stock);
            $('#satuan_stock').combobox('setText',row.unit_stock);
            $('#satuan_jual').combobox('setValue',data.data.satuan_jual);
            $('#satuan_jual').combobox('setText',row.unit_jual);
            $('#colour_code').combobox('setValue',data.data.colour_code);
            $('#colour_code').combobox('setText',data.data.colour_name);


            $('#submit').linkbutton({disabled:true});
            $('#cancel').linkbutton({disabled:false});
        }
    });

    // populateArticle();
    // populateBrand();
    // populateClass();
    // populateCustomerType();
    // populateSupplier();
    // populateUOM();



    // $('#first_production').datebox('setValue', row.first_production);
    // $('#last_production').datebox('setValue', row.last_production);

}

function deleteData(){
    let row = getRow(true);
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"masterproduct/delete_data/"+row.id,function(result){
                    var res = $.parseJSON(result);
                    if (res.status===1){
                        $.messager.show({    // show error message
                            title: 'Error',
                            msg: res.msg
                        });
                    } else {
                        $('#dg').datagrid('reload');    // reload the user data
                    }
                }
            );
        }
    });
}

function getRow(bool) {
    var row = $('#dg').datagrid('getSelected');
    if (!row){
        if(bool) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: 'Please select data to edit.'
            });
        }
        return null;
    }else{
        row.record = $('#dg').datagrid("getRowIndex", row);
    }
    return row;
}
function submit(){
    console.log(flag)
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        onSubmit:function (param) {
            param.colour_code = $('#colour_code').combobox('getValues');
        },
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                console.log(result);
                console.log(res.status);
                if (res.status === 0) {
                    $('#dlg').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
                    clearInput();
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: res.msg
                    });
                }
            }catch (e) {
                console.log(e)
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            }
        }
    });
}