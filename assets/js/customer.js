var options={
    url: base_url+"customer/load_grid?golongan="+golongan,
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"customer_code",
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
        id:'info',
        iconCls: 'icon-info',
        text:'Contact Customer',
        handler: function(){
            openContact()
        }
    },{
        id:'sales',
        iconCls: 'icon-salesman',
        text:'Salesman',
        handler: function(){
            openSalesman()
        }
    },{
        id:'alamat',
        iconCls: 'icon-wilayah',
        text:'Alamat Kirim',
        handler: function(){
            openAlamatKirim()
        }
    },{
        id:'orders',
        iconCls: 'icon-sales',
        text:'History Sales',
        handler: function(){
            openSales()
        }
    },{
        id:'article',
        iconCls: 'rgb',
        text:'Category Article',
        handler: function(){
            openCategoryArticle()
        }
    },{
        iconCls: 'icon-download', id:'download',
        text:'Download',
        handler: function(){
            getParamOption("dg", function (x, x1, x2) {
                let urlss = base_url+"customer/export_data?field="+x+"&op="+x1+"&value="+x2;
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
        // {field:"gol_customer",   title:"Golongan Customer",      width: '15%', sortable: true},
        {field:"status",   title:"Status",      width: '8%', sortable: true},
        {field:"customer_code",   title:"Code Customer",      width: '8%', sortable: true},
        {field:"customer_name",   title:"Nama Customer",      width: '15%', sortable: true},
        {field:"customer_type_name",   title:"Price Type",      width: '12%', sortable: true},
        {field:"provinsi",   title:"Provinsi",      width: '12%', sortable: true},
        {field:"kota",   title:"Kota / Kab.",      width: '12%', sortable: true},
        {field:"contact_person",   title:"Contact Person",      width: '30%', sortable: true},
        {field:"phone1",   title:"Phone 1",      width: '9%', sortable: true},
        {field:"top_day",   title:"Term Of Payment",      width: '5%', sortable: true},
        {field:"parent_name",   title:"Parent Customer",      width: '15%', sortable: true},
        {field:"customer_class",   title:"Customer Class",      width: '12%', sortable: true},
        {field:"margin_persen",   title:"Margin%",      width: '12%', sortable: true, formatter:numberFormat},

        // {field:"pkp",   title:"PKP",      width: '5%', sortable: true},
        // {field:"address1",   title:"Alamat 1",      width: '20%', sortable: true},
        // {field:"salesman_name",   title:"Salesman",      width: '14%', sortable: true},
        // {field:"lokasi",   title:"Lokasi Stok",      width: '12%', sortable: true},
        // {field:"credit_limit",   title:"Kredit Limit",      width: '12%', sortable: true, formatter:numberFormat},
        // {field:"outstanding",   title:"Outstanding",      width: '12%', sortable: true, formatter:numberFormat},
        // {field:"credit_remain",   title:"Sisa Limit",      width: '12%', sortable: true, formatter:numberFormat},
        // {field:"keterangan",   title:"Keterangan",      width: '18%', sortable: true},
        // {field:"head_customer_name",   title:"Head Customer",      width: '15%', sortable: true},
        // {field:"zip",   title:"ZIP",      width: '8%', sortable: true},
        // {field:"fax",   title:"Fax",      width: '11%', sortable: true},
        // {field:"phone2",   title:"Phone 2",      width: '9%', sortable: true},
        // {field:"phone3",   title:"Phone 3",      width: '9%', sortable: true},
        // {field:"npwp",   title:"NPWP",      width: '13%', sortable: true},
        // {field:"nama_pkp",   title:"Nama PKP",      width: '15%', sortable: true},
        // {field:"alamat_pkp",   title:"Alamat PKP",      width: '20%', sortable: true},
        // {field:"gl_account",   title:"GL Account",      width: '12%', sortable: true},
        // {field:"crtby",   title:"Create By",      width: 100, sortable: true},
        // {field:"crtdt",   title:"Create Date",      width: 140, sortable: true},
        // {field:"updby",   title:"Update By",      width: 100, sortable: true},
        // {field:"upddt",   title:"Update Date",      width: 140, sortable: true},
    ]],
    onLoadSuccess:function(){
        $('#detail').linkbutton({disabled:true});
        $('#info').linkbutton({disabled:true});
        $('#sales').linkbutton({disabled:true});
        $('#alamat').linkbutton({disabled:true});
        $('#orders').linkbutton({disabled:true});
        $('#article').linkbutton({disabled:true});
        $('#submit').linkbutton({disabled:true});
        $('#cancel').linkbutton({disabled:true});
		authbutton();
    },
    onSelect: function(index, row) {
        $('#detail').linkbutton({disabled:false});
        $('#info').linkbutton({disabled:false});
        $('#sales').linkbutton({disabled:false});
        $('#alamat').linkbutton({disabled:false});
        $('#orders').linkbutton({disabled:false});
        $('#article').linkbutton({disabled:false});

        $('#fm').form('load',row);
    }
};

setTimeout(function () {
    initGrid();
},500);

function changeLimit(e) {
    var nilai = e.target.value!==""?e.target.value:0;
    var outstd = $('#outstanding').numberbox('getValue');
    var nilai2 = outstd!==""?outstd:0;
    $('#credit_remain').numberbox('setValue',nilai-nilai2);
    $('#credit_remain').numberbox('setText',nilai-nilai2);
}

function changeOutstanding(e) {
    var credit = $('#credit_limit').numberbox('getValue');
    var nilai = credit!==""?credit:0;
    var nilai2 = e.target.value!==""?e.target.value:0;
    $('#credit_remain').numberbox('setValue',nilai-nilai2);
    $('#credit_remain').numberbox('setText',nilai-nilai2);
}

function populatePKP() {
    $('#pkp').combobox({
        data:[
            {value:'Yes',text:'Yes'},
            {value:'No',text:'No'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#pkp"]',
        onSelect:function(row){
            // console.log(row)
            if(row.value !== '') {
                $('#npwp').textbox({required:(row.value === 'Yes'),readonly:(row.value === 'No')});
                $('#nama_pkp').textbox({required:(row.value === 'Yes'),readonly:(row.value === 'No')});
                $('#alamat_pkp').textbox({required:(row.value === 'Yes'),readonly:(row.value === 'No')});
                if(row.value==="Yes"){
                    $("#display_beda_fp").show();
                }else{
                    $("#display_beda_fp").hide();
                }
            }
        }
    });

    let row = getRow(false);
    if(row!=null){
        $('#pkp').combobox('select',row.pkp)
        $('#npwp').textbox({required:(row.pkp === 'Yes'),readonly:(row.pkp === 'No')});
        $('#nama_pkp').textbox({required:(row.pkp === 'Yes'),readonly:(row.pkp === 'No')});
        $('#alamat_pkp').textbox({required:(row.pkp === 'Yes'),readonly:(row.pkp === 'No')});
    }
}

function populatePayment() {
    $('#payment_first').combobox({
        data:[
            {value:'Yes',text:'Yes'},
            {value:'No',text:'No'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#payment_first"]',
        onSelect:function(row){

        }
    });

    let row = getRow(false);
    if(row!=null){
        $('#payment_first').combobox('select',row.payment_first)
    }
}
function populateBedaFP() {
    $('#beda_fp').combobox({
        data:[
            {value:'Yes',text:'Yes'},
            {value:'No',text:'No'},
        ],
        prompt:'-Please Select-',
        validType:'inList["#beda_fp"]'
    });

    let row = getRow(false);
    if(row!=null){
        $('#beda_fp').combobox('select',row.beda_fp)
    }
}
function populateHeadCustomer() {
    $('#head_customer_id').combobox({
        url: base_url+"customer/get_head_customer",
        valueField: 'head_customer_id',
        textField: 'nama_company',
        prompt:'-Please Select-',
        validType:'inList["#head_customer_id"]',
        loadFilter: function (data) {
            return data.data;
        }
    });

    let row = getRow(false);
    if(row!=null) $('#head_customer_id').combobox('select',row.head_customer_id);
}
function populateCustomerParent() {
    $('#parent_cust').combobox({
        url: base_url+"customer/get_parent_cust",
        valueField: 'customer_code',
        textField: 'customer_name',
        prompt:'-Please Select-',
        validType:'inList["#parent_cust"]',
        loadFilter: function (data) {
            return data.data;
        }
    });

    let row = getRow(false);
    if(row!=null) $('#parent_cust').combobox('select',row.parent_cust);
}
function populateLocationStock() {
    $('#lokasi_stock').combobox({
        url: base_url+"customer/get_location_stock",
        valueField: 'location_code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#lokasi_stock"]',
        loadFilter: function (data) {
            return data.data;
        }
    });

    let row = getRow(false);
    if(row!=null) $('#lokasi_stock').combobox('select',row.lokasi_stock)
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

    let row = getRow(false);
    if(row!=null) $('#check_stock').combobox('select',row.check_stock)
}
function populateSalesman(regency, provinsi) {
    $('#salesman_id').combobox({
        url: base_url+"customer/get_salesman/"+provinsi+"/"+regency,
        valueField: 'salesman_id',
        textField: 'salesman_name',
        prompt:'-Please Select-',
        validType:'inList["#salesman_id"]',
        loadFilter: function (data) {
            return data.data;
        }
    });

    let row = getRow(false);
    if(row!=null) $('#salesman_id').combobox('select',row.salesman_id)
}
function populateCustomerType() {
    $('#customer_type').combobox({
        url: base_url+"customer/get_customer_type",
        valueField: 'code',
        textField: 'description',
        prompt:'-Please Select-',
        validType:'inList["#customer_type"]',
        loadFilter: function (data) {
            return data.data;
        },
        onSelect:function (row) {
            console.log(row)
        }
    });
    let row = getRow(false);
    if(row!=null) $('#customer_type').combobox('select',row.customer_type)
}
function populateRegency(id) {
    if(id==="") return;
    let row = getRow(false);
    $('#regency_id').combobox({
        url: base_url+"customer/get_regency/"+id,
        valueField: 'id',
        textField: 'name',
        prompt:'-Please Select-',
        validType:'inList["#regency_id"]',
        loadFilter: function (data) {
            return data.data;
        },
        onSelect:function (row) {
            if(row.id!=="") populateSalesman(row.id, id)
        }
    });
    if(row!=null) $('#regency_id').combobox('select',row.regency_id)
}
function populateProvinsi() {
    $('#provinsi_id').combobox({
        url: base_url+"customer/get_provinsi",
        valueField: 'id',
        textField: 'name',
        prompt:'-Please Select-',
        validType:'inList["#provinsi_id"]',
        loadFilter: function (data) {
            return data.data;
        },
        onSelect:function (row) {
            if(row.id!=="") populateRegency(row.id);
        }
    });

    let row = getRow(false);
    if(row!=null) $('#procinsi_id').combobox('select',row.provinsi_id)
}
function populateStatus() {
    $('#status').combobox({
        prompt:'-Please Select-',
        validType:'inList["#status"]',
        data:[
            {value:'Aktif', text:'Aktif'},
            {value:'Non-Aktif', text:'Non-Aktif'},
            {value:'Block', text:'Block'},
        ],
        onSelect:function (row) {
            let rows = getRow();
            if(row.value!==""){
                if(rows!==null){
                    if(row.value!==rows.status){
                        $('#info_status').textbox({disabled:false, readonly:false, width:'100%', label:'Reason Status:'});
                        $('#info_status').textbox('show');
                    }else{
                        $('#info_status').textbox({disabled:true, readonly:true, width:'100%', label:''});
                        $('#info_status').textbox('hide');
                    }
                }
            }
        }
    });

    let row = getRow(false);
    if(row!=null) $('#status').combobox('select',row.status)
}
function populateCustomerClass() {
    $('#customer_class').combobox({
        prompt:'-Please Select-',
        validType:'inList["#customer_class"]',
        data:[
            {value:'Eceran 2',      text:'Eceran 2'},
            {value:'Counter',       text:'Counter'},
            {value:'Distributor',   text:'Distributor'},
            {value:'Grosir 1',      text:'Grosir 1'},
            {value:'Modern Market', text:'Modern Market'},
            {value:'Showroom',      text:'Showroom'},
            {value:'Retail Cart',   text:'Retail Cart'},
            {value:'Customer Online', text:'Customer Online'},
            {value:'Eceran',        text:'Eceran'},
            {value:'Grosir 2',      text:'Grosir 2'},
        ]
    });

    let row = getRow(false);
    if(row!=null) $('#customer_class').combobox('select',row.customer_class)
}

var flag = undefined;
function initGrid() {
    $('#dg').datagrid(options);
    $('#dg').datagrid('enableFilter',[{
        field:'gol_customer',
        type:'combobox',
        options:{
            disabled:true,
            panelHeight:'auto',
            data:[
                {value:'Wholesales',text:'Wholesales'},
                {value:'Counter',text:'Counter'},
                {value:'Showroom',text:'Showroom'},
                {value:'Customer Online',text:'Customer Online'},
            ],
            onChange:function(value){
                if (value === ''){
                    $('#dg').datagrid('removeFilterRule', 'gol_customer');
                } else {
                    $('#dg').datagrid('addFilterRule', {
                        field: 'gol_customer',
                        op: 'equal',
                        value: value
                    });
                }
                $('#dg').datagrid('doFilter');
            },
            // onLoadSuccess:function () {
            //     $(this).combobox('setValue',golongan)
            // }
        }
    }]);

    disable_enable(true);
    populateProvinsi();
    populateStatus();
    populateCustomerType();
    populateCustomerClass();
    populateLocationStock();
    populateCheckStock();
    populateHeadCustomer();
    populatePKP();
    populateBedaFP();
    populateCustomerParent();
    populatePayment();
}

function clearInput() {
    $('#fm').form('clear');
    $('#dlg2').dialog('close');
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:true});
    $('#check_stock').combobox('hide');
    $('#kode_lokasi').textbox('hide');
    // $('#kode_store').textbox('hide');
    disable_enable(true);
}

function addnew(){
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Add New Customer`);
    disable_enable(false)
    // var fltr = $('#dg').datagrid('getFilterRule','gol_customer');
    $('#gol_customer').combobox({disabled:false, readonly:true, width:'100%'});
    $('#customer_code').textbox({disabled:true, readonly:true, width:'100%'});
    $('#info_status').textbox({disabled:true, readonly:true, width:'100%', label:''});
    $('#info_status').textbox('hide');
    $('#submit').linkbutton({disabled:false});
    $('#cancel').linkbutton({disabled:false});
    $('#fm').form('clear');
    $('#gol_customer').combobox('select',golongan);
    console.log("disnii", golongan)
    if(golongan==="Counter"||golongan==="Showroom"){
        $('#lokasi_stock').combobox({disabled:true, readonly:true, width:'100%', label:''});
        $('#check_stock').combobox({disabled:false, readonly:false, width:'100%', label:'Check Stok:'});
        $('#kode_lokasi').textbox({disabled:false, readonly:false, width:'100%', label:'New Location Code:'});
        // $('#kode_store').textbox({disabled:false, readonly:false, width:'100%', label:'New Store Code:'});
        $('#lokasi_stock').combobox('hide');
        $('#check_stock').combobox('show');
        $('#kode_lokasi').textbox('show');
        // $('#kode_store').textbox('show');
    }else{
        $('#lokasi_stock').combobox({disabled:false, readonly:false, width:'100%', label:'Lokasi Stok'});
        $('#check_stock').combobox({disabled:true, readonly:true, width:'100%', label:''});
        $('#kode_lokasi').textbox({disabled:true, readonly:true, width:'100%', label:''});
        // $('#kode_store').textbox({disabled:true, readonly:true, width:'100%', label:''});
        $('#lokasi_stock').combobox('show');
        $('#check_stock').combobox('hide');
        $('#kode_lokasi').textbox('hide');
        // $('#kode_store').textbox('hide');
        populateLocationStock();
    }
    if(golongan==="Wholesales" || golongan==="Customer Online"){
        $('#credit_limit').numberbox({disabled:false, readonly:false, required:true, width:'100%'});
        $('#outstanding').numberbox({disabled:false, readonly:false, required:true, width:'100%'});
        $('#credit_remain').numberbox({disabled:false, readonly:true, required:true, width:'100%'});
    }else{
        $('#credit_limit').numberbox({disabled:false, readonly:false, required:false, width:'100%'});
        $('#outstanding').numberbox({disabled:false, readonly:false, required:false, width:'100%'});
        $('#credit_remain').numberbox({disabled:false, readonly:false, required:false, width:'100%'});
    }
    flag = "customer/save_data";
}


function viewdata(){
    let row = getRow();
    if(row==null) return
    $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`View Customer : ${row.customer_name}`);
    disable_enable(true)
    $('#fm').form('load',row);
    $("#credit_limit").numberbox({formatter:numberFormat});
    $("#outstanding").numberbox({formatter:numberFormat});
    $("#credit_remain").numberbox({formatter:numberFormat});
    $('#submit').linkbutton({disabled:true});
    $('#cancel').linkbutton({disabled:false});
    
	$('#check_stock').combobox({disabled:true, readonly:true, width:'100%', label:''});
    $('#kode_lokasi').textbox({disabled:true, readonly:true, width:'100%', label:''});
    $('#check_stock').combobox('hide');
    $('#kode_lokasi').textbox('hide');
}
function editData(){
    let row = getRow(true);
    if(row==null) return
    $.ajax({
        type:"POST",
        url:base_url+"customer/read_data/"+row.customer_code,
        dataType:"html",
        success:function(result){
            $('#dlg2').dialog('open').dialog('center').dialog('setTitle',`Edit Customer : ${row.customer_name}`);
            disable_enable(false)
            var data = $.parseJSON(result);
            $('#customer_code').textbox({disabled:false, readonly:true, width:'100%'});
            $('#info_status').textbox({disabled:true, readonly:true, width:'100%', label:''});
            $('#info_status').textbox('hide');
            $('#submit').linkbutton({disabled:false});
            $('#cancel').linkbutton({disabled:false});
            $('#fm').form('load',data.data);
            flag = "customer/edit_data";

            // var fltr = $('#dg').datagrid('getFilterRule','gol_customer');
            $('#gol_customer').combobox('select',golongan);
            console.log("disnii", golongan)
            if(golongan==="Counter"||golongan==="Showroom"){
                $('#lokasi_stock').combobox({disabled:true, readonly:true, width:'100%', label:''});
                //$('#check_stock').combobox({disabled:false, readonly:false, width:'100%', label:'Check Stok:'});
                //$('#kode_lokasi').textbox({disabled:false, readonly:false, width:'100%', label:'New Location Code:'});
                // $('#kode_store').textbox({disabled:false, readonly:false, width:'100%', label:'New Store Code:'});
                $('#lokasi_stock').combobox('hide');
                //$('#check_stock').combobox('show');
                //$('#kode_lokasi').textbox('show');
                // $('#kode_store').textbox('show');
            }else{
                $('#lokasi_stock').combobox({disabled:false, readonly:false, width:'100%', label:'Lokasi Stok'});
                //$('#check_stock').combobox({disabled:true, readonly:true, width:'100%', label:''});
                //$('#kode_lokasi').textbox({disabled:true, readonly:true, width:'100%', label:''});
                // $('#kode_store').textbox({disabled:true, readonly:true, width:'100%', label:''});
                $('#lokasi_stock').combobox('show');
                //$('#check_stock').combobox('hide');
                //$('#kode_lokasi').textbox('hide');
                // $('#kode_store').textbox('hide');
                populateLocationStock();
            }
			
			$('#check_stock').combobox({disabled:true, readonly:true, width:'100%', label:''});
			$('#kode_lokasi').textbox({disabled:true, readonly:true, width:'100%', label:''});
			$('#check_stock').combobox('hide');
			$('#kode_lokasi').textbox('hide');
			
            if(golongan==="Wholesales" || golongan==="Customer Online"){
                $('#credit_limit').numberbox({disabled:false, readonly:false, required:true, width:'100%'});
                $('#outstanding').numberbox({disabled:false, readonly:false, required:true, width:'100%'});
                $('#credit_remain').numberbox({disabled:false, readonly:true, required:true, width:'100%'});
            }else{
                $('#credit_limit').numberbox({disabled:false, readonly:false, required:false, width:'100%'});
                $('#outstanding').numberbox({disabled:false, readonly:false, required:false, width:'100%'});
                $('#credit_remain').numberbox({disabled:false, readonly:false, required:false, width:'100%'});
            }

            populateCheckStock();
            populatePKP();
        }
    });
}

function deleteData(){
    let row = getRow(true);
    if(row==null) return
    $.messager.confirm('Confirm','Are you sure you want to destroy this data?',function(r){
        if (r){
            $.post(
                base_url+"customer/delete_data/"+row.customer_code,function(result){
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

function openSalesman(){
    let row = getRow(true);
    if(row==null) return
    console.log(row)
    $("#history").hide();
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Salesman Customer : ${row.customer_name}`);
    $('#tt').edatagrid({
        url: base_url+"customer/get_customer_sales/"+row.customer_code,
        saveUrl: base_url+"customer/save_data_customer_sales/"+row.customer_code,
        updateUrl: base_url+"customer/edit_data_customer_sales",
        destroyUrl: base_url+"customer/delete_data_customer_sales",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"tanggalan",
        sortOrder:"desc", nowrap:false,
        height:'100%',
        clientPaging: false,
        remoteFilter: true,
        pageSize:20,
        pagination:true, striped:true,
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        onBeginEdit: function(index,row){
            // var editor = $(this).edatagrid('getEditor', {index:index,field:'province_id'});
            // var grid = $(editor.target).combogrid('grid');
            // grid.datagrid('enableFilter');
        },
        columns:[[
            {field:"id",   title:"ID",      width: '7%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"customer_code",   title:"Customer",      width: '10%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"salesman_id",   title:"Kode Sales",      width: '30%', sortable: true, formatter:function (value, row) {
                return row.salesman_name;
            }, editor:{
                    type:'combobox',
                    options:{
                        valueField:'salesman_id',
                        textField:'salesman_name',
                        url:base_url+"customer/get_salesman/"+row.provinsi_id+"/"+row.regency_id,
                        required:true,
                        prompt:'-Please Select-',
                        validType:'cekKeberadaan["#tt","salesman_id"]',
                        loadFilter: function (data) {
                            return data.data;
                        }
                    }
                }},
            // {field:"salesman_name",   title:"Nama Sales",      width: '15%', sortable: true},
            {field:"periode",   title:"Periode",      width: '15%', sortable: true, editor:{
                    type:'datebox'
                }},
            {field:"crtby",   title:"Create By",      width: '6%', sortable: true},
            {field:"crtdt",   title:"Create Date",      width: '7%', sortable: true},
            {field:"updby",   title:"Update By",      width: '6%', sortable: true},
            {field:"upddt",   title:"Update Date",      width: '7%', sortable: true},
        ]],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#tt').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    });
    $('#tt').edatagrid('destroyFilter');
    $('#tt').edatagrid('enableFilter');
}

function openAlamatKirim(){
    let row = getRow(true);
    if(row==null) return
    console.log(row)
    $("#history").hide();
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Alamat Kirim : ${row.customer_name}`);
    $("#tt").edatagrid({
        url: base_url+"customeraddress/load_grid/"+row.customer_code,
        saveUrl: base_url+"customeraddress/save_data/"+row.customer_code,
        updateUrl: base_url+"customeraddress/edit_data",
        destroyUrl: base_url+"customeraddress/delete_data",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"tanggalan",
        sortOrder:"desc", nowrap:false,
        height:'100%',
        clientPaging: false,
        remoteFilter: true,
        pageSize:20,
        pagination:true, striped:true,
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        onBeginEdit: function(index,row){
            var editor = $(this).edatagrid('getEditor', {index:index,field:'province_id'});
            var grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');

            editor = $(this).edatagrid('getEditor', {index:index,field:'regency_id'});
            grid = $(editor.target).combogrid('grid');
            grid.datagrid('enableFilter');

            $(this).edatagrid('selectRow', index);
        },
        columns:[[
            {field:"id",   title:"ID",      width: '3%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"alias_name",   title:"Nama",      width: '20%', sortable: true, editor:{
                    type:'textbox'
                }},
            {field:"alamat1",   title:"Address1",      width: '15%', sortable: true, editor:{
                type:'textbox',
                options:{
                    height:'70px',
                    multiline:true
                }
            }},
            {field:"alamat2",   title:"Address2",      width: '15%', sortable: true, editor:{
                type:'textbox',
                options:{
                    height:'70px',
                    multiline:true
                }
            }},
            {field:"province_id",   title:"Provinsi",      width: '8%', sortable: true, formatter:function(value, rr){
                return rr.province_name;
            },editor:{
                type:'combogrid',
                options:{
                    readonly:false,
                    idField: 'province_id',
                    textField:'province_name',
                    url:base_url+"wilayah2/load_grid2",
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
                        console.log(data.rows)
                        return data;
                    },
                    onSelect:function (index,rw) {
                        if(rw.province_id==="") return
                        var selectedrow = $("#tt").edatagrid("getSelected");
                        var rowIndex = $("#tt").edatagrid("getRowIndex", selectedrow);

                        var ed = $('#tt').edatagrid('getEditor',{
                            index:rowIndex,
                            field:'regency_id'
                        });
                        if(ed!=null) {
                            $(ed.target).combogrid('setValue', rw.id)
                            $(ed.target).combogrid('setText', rw.name)
                        }
                    },
                    columns: [[
                        {field:'province_id', title:'', width:75},
                        {field:'province_name', title:'Provinsi', width:175},
                        {field:'id', title:'', width:75},
                        {field:'name', title:'Kabupatan', width:175}
                    ]],
                    fitColumns: true,
                    labelPosition: 'center'
                }
            }},
            {field:"regency_id",   title:"Kota/Kabupaten",      width: '8%', sortable: true, formatter:function(value, rr){
                return rr.regency_name;
            },editor:{
                type:'combogrid',
                options:{
                    readonly:false,
                    idField: 'id',
                    textField:'name',
                    url:base_url+"wilayah2/load_grid2",
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
                        console.log(data.rows)
                        return data;
                    },
                    onSelect:function (index,rw) {
                        if(rw.id==="") return
                        var selectedrow = $("#tt").edatagrid("getSelected");
                        var rowIndex = $("#tt").edatagrid("getRowIndex", selectedrow);

                        var ed = $('#tt').edatagrid('getEditor',{
                            index:rowIndex,
                            field:'province_id'
                        });
                        if(ed!=null) {
                            $(ed.target).combogrid('setValue', rw.province_id)
                            $(ed.target).combogrid('setText', rw.province_name)
                        }
                    },
                    columns: [[
                        {field:'province_id', title:'', width:75},
                        {field:'province_name', title:'Provinsi', width:175},
                        {field:'id', title:'', width:75},
                        {field:'name', title:'Kabupatan', width:175}
                    ]],
                    fitColumns: true,
                    labelPosition: 'center'
                }
            }},
            {field:"zip",   title:"ZIP",      width: '4%', sortable: true, editor:{type:'textbox', options:{required:true}}},
            {field:"phone",   title:"Phone ",      width: '6%', sortable: true, editor:{type:'textbox', options:{required:true}}},
            {field:"fax",   title:"Fax",      width: '6%', sortable: true, editor:{type:'textbox', options:{required:true}}},
            {field:"crtby",   title:"Create By",      width: '6%', sortable: true},
            {field:"crtdt",   title:"Create Date",      width: '7%', sortable: true},
            {field:"updby",   title:"Update By",      width: '6%', sortable: true},
            {field:"upddt",   title:"Update Date",      width: '7%', sortable: true},
        ]],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#tt').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    });
    $('#tt').edatagrid('destroyFilter');
    $('#tt').edatagrid('enableFilter');
    $('#tt').edatagrid('hideColumn','id');
}

function openSales(){
    let row =  getRow(true);
    if(row==null) return
    $('#dlg3').dialog('open').dialog('center').dialog('setTitle',`Sales HD & Sales DT : ${row.customer_name}`);
    $('#tt2').datagrid({
        url: base_url+"salesorder/load_grid",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"docno",
        sortOrder:"desc", nowrap:false,
        height:'100%',
        clientPaging: false,
        remoteFilter: true,
        pageSize:20,
        pagination:true, striped:true,
        loadFilter: function(data){
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        columns:[[
            {field:"customer_code",   title:"Customer code",      width: 100, sortable: true},
            {field:"doc_date",   title:"Trans. Date",      width: 100, sortable: true},
            {field:"docno",   title:"Faktur No",      width:200, sortable: true},
            {field:"gross_sales",   title:"Gross sales",  formatter:numberFormat,    width: 100, sortable: true},
            {field:"total_ppn",   title:"Total PPN",  formatter:numberFormat,      width: 100, sortable: true},
            {field:"total_discount",   title:"Total Discount",  formatter:numberFormat,      width: 160, sortable: true},
            {field:"sales_before_tax",   title:"Sales Before Tax",  formatter:numberFormat,      width: 100, sortable: true},
            {field:"sales_after_tax",   title:"Net Sales After Tax",  formatter:numberFormat,      width: 160, sortable: true},
        ]],
        view: detailview,
        detailFormatter:function(index,row){
            return '<div style="width: 100%; height: 100%; vertical-align: top"><table class="ddvx"></table></div>';
        },
        onExpandRow:function (index, row_l) {
            let ddvx = $(this).datagrid('getRowDetail', index).find('table.ddvx');
            ddvx.datagrid({
                url:base_url+"salesorder/load_grid_detail/"+row_l.docno,
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
                sortName:"seqno",
                sortOrder:"desc",
                singleSelect:true,
                loadFilter: function(data){
                    data.rows = [];
                    if (data.data) data.rows = data.data;
                    return data;
                },
                height:'auto',
                columns:[[
                    {field:"nobar", title:'Product Code', width: '15%', sortable: true},
                    {field:"qty_order", title:'Qty Sales', width: '15%', sortable: true},
                    {field:"uom_jual", title:'UOM Jual', width: '10%', sortable: true},
                    // {field:"uom_id", title:'UOM Jual', width: '10%', sortable: true},
                    {field:"unit_price", title:'Selling Price',  formatter:numberFormat, width: '20%', sortable: true},
                    {field:"disc_total", title:'Disc Amt/UOM', width: '15%', sortable: true},
                    {field:"fullname", title:'User', width: '10%', sortable: true},
                    {field:"last_time", title:'Time', width: '10%', formatter:function (value, row) {
                        return row.last_jam;
                    }, sortable: true},
                ]],
                onResize:function(){
                    $('#tt2').datagrid('fixDetailRowHeight',index);
                },
                onLoadSuccess:function(){
                    setTimeout(function(){
                        $('#tt2').datagrid('fixDetailRowHeight',index);
                    },500);
                }
            });
            $('#tt2').datagrid('fixDetailRowHeight',index);
        }
    });

    $('#tt2').datagrid('destroyFilter');
    $('#tt2').datagrid('enableFilter');
    $('#tt2').datagrid('removeFilterRule', 'customer_code');
    $('#tt2').datagrid('addFilterRule', {
        field: 'customer_code',
        op: 'equal',
        value: row.customer_code
    });
    $('#tt2').datagrid('doFilter');
    $('#tt2').edatagrid('hideColumn', 'customer_code');
}

function openCopy() {
    let row =  getRow(true);
    if(row==null) return
    $('#tt').edatagrid({toolbar:'#toolbar23'});
    $("#combo").combogrid({
        idField: 'customer_code',
        textField:'customer_name',
        disabled:false,
        required:true,
        readonly:false,
        url:base_url+"customer/load_grid?golongan="+golongan,
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
            console.log(data.rows)
            return data;
        },
        columns: [[
            {field:'customer_code',title:'Kode Customer',width:150},
            {field:'customer_name',title:'Customer Name',width:250},
        ]],
        fitColumns: true,
        labelPosition: 'center'
    });
    var grid = $("#combo").combogrid('grid');
    grid.datagrid('enableFilter');
}

function submitCopy() {
    let row =  getRow(true);
    if(row==null) return
    var xx = $('#combo').combogrid('getValue');
    console.log(xx)
    $.ajax({
        url: base_url+"customer/copy_article",
        type: 'post',
        data: {
            from:xx,
            to:row.customer_code
        },
        success: function(result){
            console.log(result);
            var res = $.parseJSON(result);
            if (res.status===1){
                alert(res.msg)
            }
            $('#tt').edatagrid('reload');
            cancelUpload();
        }
    });
    cancelUpload()
}

function cancelUpload() {
    $('#toolbar23').hide();
}
function openHistory(){
    let row =  $('#tt').edatagrid('getSelected');
    let row2 =  getRow(true);
    if(row==null || row2==null) return
    $('#dlg3').dialog('open').dialog('center').dialog('setTitle',`History Promo : ${row.article_code}`);
    $('#tt2').datagrid({
        url: base_url+"discount/load_grid_nobar_by_article_customer?article="+row.article_code+"&customer="+row2.customer_code,
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"article_code",
        sortOrder:"desc", nowrap:false,
        height:'100%',
        clientPaging: false,
        remoteFilter: true,
        pageSize:20,
        pagination:true, striped:true,
        loadFilter: function(data){
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        columns:[[
            {field:"discount_id",   title:"Kode Diskon",      width: '14%', sortable: true},
            {field:"start_date",   title:"Periode Awal",      width: '13%', sortable: true},
            {field:"end_date",   title:"Periode Akhir",      width: '13%', sortable: true},
            {field:"customer_type",   title:"Price Type",      width: '20%', sortable: true},
            {field:"print_barcode",   title:"Print Barcode",    align:"center",  width: '12%', editor:{
                type:'checkbox',
                options:{on:'YES',off:'NO'}
            }},
            {field:"discount",   title:"Disc Article",      width: '18%', sortable: true},
        ]],
    });
    $('#tt2').datagrid('destroyFilter');
    $('#tt2').datagrid('enableFilter');
}
function openCategoryArticle(){
    let row = getRow(true);
    if(row==null) return
    $("#history").show();
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Category Article : ${row.customer_name}`);
    $('#tt').edatagrid({
        url: base_url+"customer/get_article/"+row.customer_code,
        saveUrl: base_url+"customer/save_data_article/"+row.customer_code,
        updateUrl: base_url+"customer/edit_data_article",
        destroyUrl: base_url+"customer/delete_data_article",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"article_code",
        sortOrder:"desc", nowrap:false,
        height:'100%',
        clientPaging: false,
        remoteFilter: true,
        pageSize:20,
        pagination:true, striped:true,
        toolbar:"#toobar23",
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        onBeginEdit: function(index,row){
            // var editor = $(this).edatagrid('getEditor', {index:index,field:'province_id'});
            // var grid = $(editor.target).combogrid('grid');
            // grid.datagrid('enableFilter');
        },
        columns:[[
            {field:"id",   title:"ID",      width: '7%', sortable: true, editor:{
                type:'textbox',
                options:{
                    disabled:false,
                    readonly:true
                }
            }},
            {field:"customer_code",   title:"Customer",      width: '10%', sortable: true, formatter:function (value, rr) {
                return rr.customer_name;
            }, editor:{
                type:'textbox',
                options:{
                    disabled:false,
                    readonly:true
                }
            }},
            {field:"article_code",   title:"Article Code",      width: '15%', sortable: true, editor:{
                type:'combogrid',
                options:{
                    idField: 'article_code',
                    textField:'article_name',
                    url:base_url+"masterarticle/load_grid",
                    required:true,
                    remoteFilter:true,
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
                    onSelect:function (index,row) {
                        console.log(row)
                        var selectedrow = $("#tt").edatagrid("getSelected");
                        var rowIndex = $("#tt").edatagrid("getRowIndex", selectedrow);

                        var ed = $('#tt').edatagrid('getEditor',{
                            index:rowIndex,
                            field:'article_name'
                        });
                        $(ed.target).textbox('setValue', row.article_name);
                        $(ed.target).textbox('setText', row.article_name);

                    },
                    columns: [[
                        {field:'article_code',title:'Article Code',width:150},
                        {field:'article_name',title:'Article Name',width:250},
                    ]],
                    fitColumns: true,
                    labelPosition: 'center'
                }
            }},
            {field:"article_name",   title:"Article Name",      width: '20%', sortable: true, editor:{
                type:'textbox',
                options:{
                    disabled:false,
                    readonly:true
                }
            }},
            {field:"customer_type",   title:"Price Type",      width: '20%', sortable: true, formatter: function(value, row){
                return row.description;
            },editor:{
                type:'combobox',
                options:{
                    valueField:'code',
                    textField:'description',
                    url:base_url+"headcustomer/get_customertype",
                    required:true,
                    prompt:'-Please Select-',
                    validType:'cekKeberadaan["#tt","customer_type"]',
                    loadFilter: function (data) {
                        return data.data;
                    },
                }
            }},
            {field:"discount",   title:"Discount",      width: '20%', sortable: true, editor:{type:'numberbox', options:{min:0, precision:2, formatter:formatnumberbox}}},
            {field:"print_barcode",   title:"Print Barcode",    align:"center",  width: '8%', editor:{
                type:'checkbox',
                options:{on:'YES',off:'NO'}
            }},
        ]],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#tt').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    });
    $('#tt').edatagrid('destroyFilter');
    $('#tt').edatagrid('enableFilter');
}

function openContact(){
    let row = getRow(true);
    if(row==null) return
    $("#history").hide();
    $('#dlg').dialog('open').dialog('center').dialog('setTitle',`Customer Contact : ${row.customer_name}`);
    $('#tt').edatagrid({
        url: base_url+"customer/get_contact/"+row.customer_code,
        saveUrl: base_url+"customer/save_data_contact/"+row.customer_code,
        updateUrl: base_url+"customer/edit_data_contact",
        destroyUrl: base_url+"customer/delete_data_contact",
        idField:"id",
        rownumbers:"true",
        fitColumns:"true",
        singleSelect:"true",
        sortName:"contact",
        sortOrder:"desc", nowrap:false,
        height:'100%',
        clientPaging: false,
        remoteFilter: true,
        pageSize:20,
        pagination:true, striped:true,
        loadFilter: function(data){
            data.rows = [];
            if (data.data){
                data.rows = data.data;
                return data;
            } else {
                return data;
            }
        },
        onBeginEdit: function(index,row){
            // var editor = $(this).edatagrid('getEditor', {index:index,field:'province_id'});
            // var grid = $(editor.target).combogrid('grid');
            // grid.datagrid('enableFilter');
        },
        columns:[[
            {field:"id",   title:"ID",      width: '7%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"customer_code",   title:"Customer",      width: '10%', sortable: true, editor:{
                    type:'textbox',
                    options:{
                        disabled:false,
                        readonly:true
                    }
                }},
            {field:"contact",   title:"Kontak",      width: '20%', sortable: true, editor:{type:'textbox'}},
            {field:"no_telp",   title:"No Telepon",      width: '20%', sortable: true, editor:{type:'textbox'}},
            {field:"dept",   title:"Bagian",      width: '15%', sortable: true, editor:{type:'textbox'}},
            {field:"keterangan",   title:"Keterangan",      width: '30%', sortable: true, editor:{type:'textbox'}}
        ]],
        onSuccess: function(index, row){
            if(row.status===1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#tt').edatagrid('reload');
        },
        onError:function(index, e){
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    });
    $('#tt').edatagrid('destroyFilter');
    $('#tt').edatagrid('enableFilter');
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
    // console.log(base_url+flag)
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            console.log(result)
            try {
                var res = $.parseJSON(result);
                // console.log(result);
                // console.log(res.status);
                if (res.status === 0) {
                    $('#dlg2').dialog('close');        // close the dialog
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