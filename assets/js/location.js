var options={
    url: base_url+"location/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"location_code",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:[{
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
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("dg", function (x, x1, x2) {
                let urlss = base_url+"location/export_data?field="+x+"&op="+x1+"&value="+x2;
                window.open(urlss, '_blank')
            })
        }
    }],
    loadFilter: function(data){
        data.rows = [];
        if (data.data) data.rows = data.data;
        return data;
    },
    columns:[[
        {field:"location_code",   title:"Kode",      width: '10%', sortable: true},
        {field:"description",   title:"Location Name",      width: '40%', sortable: true},
        {field:"pkp",   title:"PKP",      width: '9%', sortable: true},
        {field:"price_type_name",   title:"Price Type",      width: 130, sortable: true},
        {field:"check_stock",   title:"Check Stock",      width: 80, sortable: true},
        {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        {field:"updby",   title:"Update By",      width: 100, sortable: true},
        {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        //$('#edit').linkbutton({disabled:true});
        //$('#delete').linkbutton({disabled:true});
        // $('#tb').show();
        // $('#fm').hide();
        disable_enable(false)
        authbutton();
    },
    onSelect: function(index, row) {
        //$('#edit').linkbutton({disabled:false});
        //$('#delete').linkbutton({disabled:false});
        // $('#tb').show();
        // $('#fm').hide();
        initGrid2();
    }
};
var options2={
    fitColumns:true,
    width:"100%",
    height:'100%',
    // url: base_url+"location/load_grid_location_close/"+row.location_code,
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:false,
    sortName:"status_cl",
    sortOrder:"desc",
    singleSelect:true,
    idField:'id',
    loadFilter: function(data){
        data.rows = [];
        if (data.data){
            data.rows = data.data;
            return data;
        } else {
            return data;
        }
    },
    toolbar:[
        // {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
        //     $('#dg2').edatagrid('addRow',0);
        // }},
        // {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
        //     let row =ddv.edatagrid('getSelected');
        //     if(row.status==="Approved"){
        //         $.messager.show({
        //             title: 'Warning',
        //             msg: "Data sudah di approve, tidak bisa di edit/delete"
        //         });
        //         setTimeout(function () {
        //             ddv.edatagrid('cancelRow');
        //         },500)
        //     }else{
        //         ddv.edatagrid('destroyRow');
        //         ddv.edatagrid('fixDetailRowHeight',index);
        //     }
        // }},
        {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
            $('#dg2').edatagrid('saveRow');
        }},
        {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function() {
            $('#dg2').edatagrid('cancelRow');
        }
        // }},{
        //     id:'approve2',
        //     iconCls: 'icon-ok',
        //     text:'Approve/Draft',
        //     handler: function(){
        //         ApproveDataSub(ddv.edatagrid('getSelected'))
        //     }
        // },{
        //     iconCls: 'icon-download', id:'download',
        //     text:'Download',
        //     handler: function(){
        //         let urlss = `${base_url}productcolour/export_data_sub/${row.colour_code}?field=colour_code&op=equal&value=${row.colour_code}`;
        //         // let urlss = base_url+"productcolour/export_data_sub/"+row.colour_code;
        //         window.open(urlss, '_blank')
        //     }
        }],
    onBeginEdit: function(index,row){
        console.log(row);
        var editor = $(this).edatagrid('getEditor', {index:index,field:'periode'});
        if(row.isNewRecord) {
            $(editor.target).datebox({readonly:false, disabled:false});
            $(editor.target).datebox('setValue', new Date().getFullYear() + "-" + ("0" + (new Date().getMonth() + 1)).slice(-2) + "-" + ("0" + new Date().getDate()).slice(-2));
        } else {
            $(editor.target).datebox({readonly:true, disabled:true});
            $(editor.target).datebox('setValue', formattanggal(row.periode,{}));
        }
    },
    onSuccess: function(index, row){
        if(row.status===1) {
            $.messager.show({    // show error message
                title: 'Error',
                msg: row.msg
            });
        }
    },
    onError:function (index, row) {
        $.messager.show({    // show error message
            title: 'Error2',
            msg: row.msg
        });
        $('#dg2').edatagrid('reload')
    },
    columns:[[
        {field:"periode",   title:"Periode",      width: '55%', sortable: true, formatter:function (index, row) {
            return row.prd;
        }, editor:{
            type:"datebox",
            options:{
                required:true
            }
        }},
        {field:"status_cl",   title:"Status",      width: '55%', sortable: true, editor:{
            type:"combobox",
            options:{
                valueField:'status_cl',
                textField:'name',
                data:[
                    {status_cl:"Close", name:"Close"},
                    {status_cl:"Open", name:"Open"},
                ],
                required:true
            }
        }},
    ]]
};

setTimeout(function () {
    initGrid();
    initGridMonit();
    populateCheckStock();
    populatePKP();
    populatePriceType()
},500);

function populatePriceType() {
    let row = getRow(false);
    $('#price_type').combobox({
        url: base_url+"location/get_customer_type",
        valueField: 'code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#price_type"]',
    });
    if(row!=null) $('#price_type').combobox('select',row.price_type)
}
function populatePKP() {
    $('#pkp').combobox({
        data:[
            {value:'Yes',text:'Yes'},
            {value:'No',text:'No'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#pkp"]',
    });
}
function populateCheckStock() {
    $('#check_stock').combobox({
        data:[
            {value:'Yes',text:'Yes'},
            {value:'No',text:'No'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#check_stock"]',
    });
}
var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter');
}
function initGrid2() {
    let row = getRow();
    options2.url = base_url+"location/load_grid_location_close/"+row.location_code;
    options2.saveUrl=base_url+"location/save_data_sub/"+row.location_code;
    options2.updateUrl=base_url+"location/edit_data_sub";
    // options2.destroyUrl=base_url+"location/delete_data_sub";
    $('#dg2').edatagrid(options2);
}
function initGridMonit() {
    let dg = $('#mm').edatagrid({
        fitColumns:true,
        width:"100%",
        url: base_url+"location_monitoring/load_grid",
        method:"POST",
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:false,
        sortName:"location",
        sortOrder:"asc",
        singleSelect:true,
        saveUrl: base_url+"location_monitoring/save_data",
        updateUrl: base_url+"location_monitoring/edit_data",
        destroyUrl: base_url+"location_monitoring/delete_data",
        onAfterEdit:function(data){
            $('#mm').edatagrid('reload');
        },
        onSave: function(index, row){
            $('#mm').edatagrid('reload');
        },
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        toolbar:[
            {iconCls: 'icon-add', id:'add', text:'New', handler: function(){
                    $('#mm').edatagrid('addRow',0);
                }},
            {id:'delete', iconCls: 'icon-remove', text:'Delete', handler: function(){
                    $('#mm').edatagrid('destroyRow');
                }},
            {id:'submit', iconCls: 'icon-save', text:'Submit', handler: function(){
                    $('#mm').edatagrid('saveRow');
                }},
            {id:'cancel', iconCls: 'icon-undo', text:'Cancel', handler: function(){
                    $('#mm').edatagrid('cancelRow');
                }
            }],
        columns:[[
            {field:"location",   title:"Kode",      width: '10%', sortable: true, editor:{
                    type:'combobox',
                    options:{
                        valueField:'location_code',
                        textField:'description',
                        url:base_url+"location/load_grid?page=1&rows=1000",
                        required:true,
                        prompt:'-Please Select-',
                        validType:'cekKeberadaan["#mm","location"]',
                        loadFilter: function (data) {
                            return data.data;
                        },
                        onSelect:function (rr) {
                            if(rr.location_code==="") return
                            var selectedrow = $("#mm").edatagrid("getSelected");
                            var rowIndex = $("#mm").edatagrid("getRowIndex", selectedrow);
                            var ed = $('#mm').edatagrid('getEditor',{
                                index:rowIndex,
                                field:'description'
                            });
                            $(ed.target).textbox('setText',rr.description);
                            $(ed.target).textbox('setValue',rr.description);
                        }
                    }
                }},
            {field:"description",   title:"Location Name",      width: '40%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"periode",   title:"Periode",      width: '9%', sortable: true, editor:{type:'datebox',options:{required:true}}},
            {field:"status_cl",   title:"Status",      width: '9%', sortable: true, editor:{
                    type:'combobox',
                    options:{
                        valueField:'status_cl',
                        textField:'description',
                        prompt:'-Please Select-',
                        validType:'cekKeberadaan["#mm","status_cl"]',
                        data:[
                            {status_cl:"Open", description:"Open"},
                            {status_cl:"Close", description:"Close"},
                            ],
                        required:true
                    }
                },styler:function (value, row, index) {
                    if (value === "Open"){
                        return 'background-color:#ffee00;color:red;';
                    }
                }},
        ]],
        onLoadSuccess:function(){
        },
        onSelect: function(index, row) {
        },
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
        }
    });
    dg.edatagrid('enableFilter',[
        {
            field:'status_cl',
            type:'combobox',
            options:{
                panelHeight:'auto',
                data:[{value:'Close',text:'Close'},{value:'Open',text:'Open'}],
                onChange:function(value){
                    if (value == ''){
                        dg.datagrid('removeFilterRule', 'status_cl');
                    } else {
                        dg.datagrid('addFilterRule', {
                            field: 'status_cl',
                            op: 'equal',
                            value: value
                        });
                    }
                    dg.datagrid('doFilter');
                }
            }
        }]
    );
}

function clearInput() {
    $('#dlg').dialog('close');
    $('#fm').form('clear');
    disable_enable(true)
}

function addnew(){
    disable_enable(false)
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Location');
    $('#location_code').textbox({disabled:false, readonly:false, width:'100%'});
    $('#description').textbox({disabled:false, readonly:false, width:'100%'});
    $('#pkp').combobox({disabled:false, readonly:false, width:'100%'});
    $('#price_type').textbox({disabled:false, readonly:false, width:'100%'});
    $('#check_stock').textbox({disabled:false, readonly:false, width:'100%'});
    $('#fm').form('clear');
    flag = "location/save_data";
}
function editData(){
    let row = getRow();
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"location/read_data/"+row.location_code,
        dataType:"html",
        success:function(result){
            var data = $.parseJSON(result);
            disable_enable(false)
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit Location');
            $('#location_code').textbox({disabled:false, readonly:true, width:'100%'});
            $('#description').textbox({disabled:false, readonly:false, width:'100%'});
            $('#pkp').combobox({disabled:false, readonly:false, width:'100%'});
            $('#price_type').textbox({disabled:false, readonly:false, width:'100%'});
            $('#check_stock').textbox({disabled:false, readonly:false, width:'100%'});
            $('#fm').form('load',data.data);
            flag = "location/edit_data";
        }
    });
}

function deleteData(){
    let row = getRow();
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"location/delete_data/"+row.location_code,function(result){
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