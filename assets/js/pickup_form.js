var pickup=undefined;
var flag = "";
$(document).ready(function () {
 

 $('#so_no').textbox('textbox').focus();   
    if(aksi==="add"){
        flag = "Pickup/save_data_header";
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y; 
        var tglset =  y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d); 
        $("#doc_date").datebox('setValue', tgl);
        $("#doc_date").datebox('setText', tgl); 
        $.ajax({
            type:"POST",
            url:base_url+"Pickup/cekdatapickup/",
            dataType:"json",
            data: {
                tgl: tglset 
            },
            success:function(result){ 
              //  console.log(result);
                if(result.data==0){ 
                    var faseadd=1; 
                }
                else{ 
                    var faseadd=result.data; 
                }
                $("#fase").textbox('setText',''); 
                $("#fase").textbox('setValue',''); 
            }
        });
 
        $("#line").textbox('setValue','1');  
        $("#status").textbox('setValue','OPEN');
        $("#jumlah_print").textbox('setValue','0');
        $("#update").hide();
        $("#posting").hide();
        $("#cancel").hide();
        $("#print").hide();
        $("#new").hide();
        $("#getbarcode").hide();
        $("#pickup").hide();

    }else{
        flag = "Pickup/edit_data_header";
        $.ajax({
            type:"POST",
            url:base_url+"Pickup/read_data/"+docno,
            dataType:"json",
            success:function(result){
             //   console.log(result);
                var total=result.total.jumlah;
                $("#totalpick").textbox('setValue',total);
                if(result.status===0) {
                    $('#fm').form('load',result.data);
                    pickup = result.data;
                    initHeader()
                    console.log(pickup);
                     if(pickup.status==="Pickup" || pickup.status==="On Waiting"|| pickup.status==="Cancel"){
                        $('#so_no').textbox({"readonly":true}); 
                        $("#pickup").hide();
                    }
                }
                else {
                    $.messager.show({
                        title: 'Error',
                        msg: e.message,
                        handler:function () {
                            window.location.href = base_url+"Pickup";
                        }
                    });
                }

            }
        });
    } 
});
function initHeader() {  

    var date = new Date(pickup.tgl);
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
    if(pickup.tgl_pickup==null){ 
        $("#tgl_pickup").datebox('setValue', '');
        $("#tgl_pickup").datebox('setText', '');
    }else{

        var date2 = new Date(pickup.tgl_pickup);
        var y2 = date2.getFullYear();
        var m2 = date2.getMonth()+1;
        var d2 = date2.getDate();
        var tglpick =  (d2<10?('0'+d2):d2)+'/'+(m2<10?('0'+m2):m2)+'/'+y2;

        $("#tgl_pickup").datebox('setValue', tglpick);
        $("#tgl_pickup").datebox('setText', tglpick);
    }
    $("#doc_date").datebox('setValue', tgl);
    $("#doc_date").datebox('setText', tgl);
    $("#id").textbox('setValue',pickup.id);  
    $("#fase").textbox('setValue',pickup.fase_pickup); 
    $("#fase").textbox('setText',pickup.fase_pickup); 

    $("#pickup_by").textbox('setValue',pickup.user);  

    $('#pickupby').combobox('setValue',pickup.ekspedisi); 
    $('#customer_code').combogrid('setValue',pickup.ekspedisiby); 
    $('#customer_name').textbox('setValue',pickup.ekspedisiname);
    initGrid();
    $("#update").show();
    $("#submit").hide();
    if(pickup.status==="Open"){ 
        $("#update").show();
    }
    if(pickup.status==="Pickup" || pickup.status==="On Waiting"|| pickup.status==="Cancel"){
        $("#posting").hide();
        $("#cancel").hide();
        $("#update").hide();
    }

}


function addform() {
         window.location.href = base_url+"Pickup/form/add";
   // window.open(base_url+'Pickup/print_so/'+docno, '_blank');
} 
function pickupget() {
    //if(pickup==null) return 

    var pickup=$("#pickup_by").textbox('getValue');   
    var pickupdate=$("#tgl_pickup").textbox('getValue');  
    
    if(pickup==="" || pickupdate===""){
        if(pickup===""){
            var datafocus='Data Pickup Belum Lengkap';
        }
        else{
            var datafocus='Tanggal Pickup Belum Terisi';
        }
         $.messager.show({
                title: 'Error',
                msg: datafocus 
            });
    }
    else{
      $.ajax({
                type:"POST",
                url:base_url+"Pickup/Updatestatus/"+docno,
                dataType:"json",
                data: {
                    pickup:pickup,
                    pickupdate:pickupdate 
                },
                success:function(result){  
               //console.log(result.status)
                       if(result.status===1) { 
                            $.messager.show({
                                title: 'Error',
                                msg: result.message, 
                                handler:function () {
                                   window.location.href = base_url + "Pickup/form/edit?id=" + docno
                                }
                            }); 
                        }
                        else {
                            $.messager.show({
                                title: 'Error',
                                msg: result.message,
                                handler:function () {
                                    window.location.href = base_url+"Pickup";
                                }
                            });
                        }
                }
            });  
    }  
}  
function reload_header() {
    $.ajax({
        type:"POST",
        url:base_url+"Pickup/read_data/"+id,
        dataType:"json",
        success:function(result){
          //  console.log(result.data)
            if(result.status===0) {
                $('#fm').form('load',result.data);
                pickup = result.data;
                initHeader()
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"Pickup";
                    }
                });
            }

        }
    });
}
var timer=null;
var product_selected=null;
function initGrid() { 

 $('#so_no').textbox('textbox').focus();   
    if(!pickup) return
    $("#dg").edatagrid({
        fitColumns: false,
        width: "100%",
        url: base_url + "Pickup/load_grid_detail/"+pickup.id,
        saveUrl: base_url + "Pickup/save_data_detail/"+pickup.id,
        updateUrl: base_url + "Pickup/edit_data_detail",
        destroyUrl: base_url + "Pickup/delete_data_detail",
        idField: 'barcode',
        method: "POST",
        pagePosition: "top",
        resizeHandle: "right",
        resizeEdge: 10,
        pageSize: 20,
        clientPaging: false,
        remoteFilter: true,
        rownumbers: false,
        pagination: true,
        sortName: "tgl",
        sortOrder: "desc",
        singleSelect: true, nowrap:false,
        toolbar: [
        // {
        //     iconCls: 'icon-add', id:'add', text:'New',
        //     handler: function(){$('#dg').edatagrid('addRow',0)}
        // },
        {
            id:'delete', iconCls: 'icon-remove', text:'Delete',
            handler: function(){
                if (pickup.status!=="Open"){
                    $.messager.show({
                        title: 'Warning',
                        msg: `Detail tidak bisa di hapus (status : ${pickup.status})`
                    });
                    return
                }
                $('#dg').edatagrid('destroyRow')
            }
        },
        // {
        //     id:'submit', iconCls: 'icon-save', text:'Submit',
        //     handler: function(){
        //         var selectedrow = $("#dg").edatagrid("getSelected");
        //         var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);
 
        //        $('#dg').edatagrid('saveRow')

        //     }
        // }
        // {
        //     id:'cancel', iconCls: 'icon-undo', text:'Cancel',
        //     handler: function(){$('#dg').edatagrid('cancelRow')}
        // }
        ],
        loadFilter: function (data) {

                    console.log(data)
            data.rows = [];
            if (data.data) {
                data.rows = data.data;
            }
            return data;
        },
        onLoadSuccess: function () {
            authbutton();
            var dt = $("#dg").edatagrid('getData');
            if(dt.data.length>0){
                // $('#customer_name').combogrid({"readonly":true});
                // $('#customer_name').combogrid('setValue', pickup.customer_name);
                $("#kopi").linkbutton({"disabled":true})
                // $('#open_cust').show();
            }else {
                $("#kopi").linkbutton({"disabled":false})
                // $('#open_cust').hide();
            }
        },
        onBeginEdit: function (index, row) {

            if (row.isNewRecord ){
                if(pickup.status!=="OPEN") {
                    $.messager.show({
                        title: 'Warning',
                        msg: "Silahkan Scan Barcode"
                    });
                    setTimeout(function () {
                        $("#dg").edatagrid('cancelRow');
                    }, 500)
                    return
                } 
            }   
            var editor = $(this).edatagrid('getEditor', {index: index, field: 'barcode'});
            var grid = $(editor.target).combogrid('textbox').focus();
            grid.focus();  
        },
        onBeforeEdit: function (index, row) {
            if (row.isNewRecord) return
            if(pickup.status!=="Open") {
                $.messager.show({
                    title: 'Warning',
                    msg: "Data tidak bisa di edit"
                });
                setTimeout(function () {
                    $("#dg").edatagrid('cancelRow');
                }, 500)
            }
        },
        columns: [[
            {
                field: "barcode",
                title: "Barcode#",
                width: '20%',
                sortable: true,
                formatter: function (value, row) {
                    console.log(row)
                    return row.barcode;
                },
                editor: {
                    type: 'textbox',
                    options: {
                        readonly: false,
                        idField: 'barcode',
                        textField: 'barcode', 
                        required: true,
                        hasDownArrow: false,  
                        multiple: false,
                        editable: true, 
                        loadFilter: function (data) {
                            data.rows = [];
                            if (data.data) {
                                data.rows = data.data;
                            }
                            return data;
                        },  
                         inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
                                keyup:function(e){ 
                            $("#dg").edatagrid('editCell',$("#dg").edatagrid('cell')); 
                            var row = $(this).val();  
                            $.ajax({
                                type: 'POST',
                                url: `${base_url}Pickup/get_bypickup`, 
                                data: {
                                    id: pickup.id, 
                                },
                                dataType: 'json',
                                success: function(data){
                                    //console.log(data.data[0]); 
                                    var datas = data.data[0]; 
                                    var jml_flg = 0;
                                    for(var i=0; i<datas; i++){
                                        if(datas.data[i].barcode && datas.data[i].barcode===row.barcode){
                                            jml_flg++;
                                        }
                                    }

                                    // /dg.datagrid('gotoCell','right');
                                    var selectedrow = $("#dg").edatagrid("getSelected");
                                    var rowIndex = $("#dg").edatagrid("getRowIndex", selectedrow);

                                    var ed = $('#dg').edatagrid('getEditor', {
                                        index: rowIndex,
                                        field: 'barcode'
                                    }); 
                                    $(ed.target).textbox('setValue', datas.barcode); 
                                },
                                error: function(){
                                    error.apply(this, arguments);
                                }
                            }); 

                                }
                            }),
                        onChange: function (index, row) { 


                        }
                    }
                }
            }, 
            {field: "status", title: "Status", width: '20%', sortable: true, editor: {type: 'textbox',options:{disabled:true}}}, 
            {field: "tgl", title: "Tgl PickUp", width: '20%', sortable: true,editor: {type: 'textbox',options:{disabled:true}}}, 
            {field: "time", title: "Time", width: '20%', sortable: true,editor: {type: 'textbox',options:{disabled:true}}}, 
        ]],
        onSuccess: function (index, row) {
            if (row.status === 1) {
                $.messager.show({    // show error message
                    title: 'Error',
                    msg: row.msg
                });
            }
            $('#dg').edatagrid('reload');
            reload_header()
        },
        onError: function (index, e) {
            $.messager.show({
                title: 'Error',
                msg: e.message
            });
        }
    })  
} 
function submit_cancel() {
    var dt = $("#dg").edatagrid('getData');
    var status = "";
    if(dt.data.length>0){
        var qty_pl = 0;
        var qty_ord= 0;
        for(var i=0; i<dt.data.length; i++){
            var pl = dt.data[i].qty_pl;
            if(pl==="") pl = "0"
            qty_pl += parseInt(pl)
            qty_ord += parseInt(dt.data[i].qty_order)
        }
        //console.log(qty_pl)
        //console.log(qty_ord)
        if(qty_pl>0){
            if(qty_pl<qty_ord){
                status = "EXPIRED"
            }
            if(qty_pl === qty_ord){
                status = "CLOSE"
            }
        }else{
            status = "CANCEL"
        }
    }else {
        status = "CANCEL"
    }

    if(status!==""){
        myConfirm("Confirmation", "Anda yakin ingin mengubah status transaksi ini?","Ya","Tidak", function (r) {
            if(r==="Ya"){
                $.ajax({
                    type:"POST",
                    url:base_url+"salesapp/edit_data_header",
                    dataType:"json",
                    data:{
                        docno:pickup.docno,
                        status:status
                    },
                    success:function(result){
                        //console.log(result.data)
                        if(result.status===0) {
                            window.location.href = base_url + "Pickup/form/edit?docno=" + pickup.id
                        }
                        else {
                            $.messager.show({
                                title: 'Error',
                                msg: e.message,
                                handler:function () {
                                    window.location.href = base_url + "Pickup/form/edit?docno=" + pickup.id
                                }
                            });
                        }

                    }
                });
            }
        })
    }
}
function submit(stt){
     // console.log(base_url+flag)
    let status = (stt==="")?(pickup)?pickup.status:'OPEN':stt;
    if(pickup!==undefined && pickup.status==="ON Waiting" && status==="Pickup") status = 'OPEN';
    $('#status').textbox('setValue',status);

    if(aksi==="add") {
        submit_reason("")
    }else{
        if(pickup!==undefined){
            if(pickup.status==="ON Waiting" && status==="Open"){
                read_packinglist(function (res) {
                    if(res==="OK"){
                        if(parseInt(global_auth[global_auth.appId].allow_unposting)===0){
                            $.messager.show({title:'Error', msg:'Anda tidak memiliki otoritas Unposting'});
                            $('#status').textbox('setValue',pickup.status);
                        }else {
                            $.messager.prompt({
                                title: 'Reason Unposting',
                                msg: 'Input reason unposting sales:',
                                fn: function (r) {
                                    if (r) {
                                        submit_reason(r);
                                    }
                                }
                            });
                        }
                    }else{
                        $.messager.show({title:'Error', msg:res});
                    }
                })
            }else if(pickup.status === status){
                submit_reason("")
            }else{
                myConfirm("Confirm", "Anda yakin ingin mengubah status sales ini?", "Yes", "No", function (r) {
                    if (r === "Yes") {
                        if (pickup.credit_limit==="" || isNaN(pickup.credit_limit) ||
                            pickup.sales_after_tax==="" || isNaN(pickup.sales_after_tax) ||
                            pickup.credit_limit-pickup.outstanding<=0 ||
                            (parseFloat(pickup.credit_limit) - parseFloat(pickup.outstanding))< parseFloat(pickup.sales_after_tax)) {
                            //cek otoritas
                            if(parseInt(global_auth[global_auth.appId].allow_approve)>0){
                                submit_reason("")
                            }else {
                                var psn = 'Nilai transaksi melebihi limit customer, dibutuhkan otorisasi untuk memposting.';
                                $.messager.show({title: 'Error', msg: psn});
                                $('#status').textbox('setValue', pickup.status);
                            }
                        } else {
                            submit_reason("")
                        }
                    }
                })
            }
        }else{
            var psn = 'Invalid add/edit data, please refresh your browser.';
            $.messager.show({title: 'Error', msg: psn});
            $('#status').textbox('setValue', pickup.status);
        }
    }

}

function submitdetail(dp,docno,doc_date) {
    //if(pickup==null) return 

var dateAr = doc_date.split('/');
var newDate = dateAr[2] + '-' + dateAr[1] + '-' +  dateAr[0].slice(-2);
  
    $.ajax({
        url: base_url+"Pickup/submitdetail",
        type: 'post',
        data: {
            barcode:dp,
            pickupheader:docno,
            tanggal:newDate
        },
        success: function(result){
           // /console.log(result);
            var res = $.parseJSON(result);
            if (res.status===1){
                // alert(res.msg)
                $.ajax({
                    type:"POST",
                    url:base_url+"Pickup/read_data/"+docno,
                    dataType:"json",
                    success:function(result){
                     //   console.log(result);
                        var total=result.total.jumlah;
                        $("#totalpick").textbox('setValue',total);  
                    }
                });
            }
            else{
                alert(res.message);
            }
            $('#dg').edatagrid('reload'); 
            // cancelUpload();
        }
    }); 
    
       $('#so_no').textbox('setValue','')
       $('#so_no').textbox('textbox').focus();   
}  
function submitCopy() {
    if(pickup==null) return
    var xx = $('#combo').combogrid('getValue');
    //console.log(xx)
    $.ajax({
        url: base_url+"Pickup/copy_detail",
        type: 'post',
        data: {
            from:xx,
            to:pickup.docno
        },
        success: function(result){
            //console.log(result);
            var res = $.parseJSON(result);
            if (res.status===1){
                alert(res.msg)
            }
            $('#dg').edatagrid('reload');
            cancelUpload();
        }
    });
    cancelUpload()
}
function cancelUpload() {
    $('#toolbar23').hide();
}
function submit_reason(reason) { 
    $("#reason").textbox('setValue', reason);
    $('#fm').form('submit',{
        url: base_url+flag,
        type: 'post',
        success: function(result){
            //console.log(result)
            try {
                var res = $.parseJSON(result);
                 
                if (res.status === 0) {
                    var stt = $('#status').textbox('getValue');
                    if(stt=="ON Waiting") {
                        myConfirm("Success", "Posting berhasil, apakah anda ingin mencetak doc.?", "Cetak", "Tidak", function (r) {
                            if (r === "Cetak") {
                                printSO()
                            }
                            window.location.href = base_url + "Pickup/form/edit?id=" + res.id
                        })
                    }else{
                        window.location.href = base_url + "Pickup/form/edit?id=" + res.id
                    }
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: res.msg
                    });
                }
            }catch (e) {
                $.messager.show({
                    title: 'Error',
                    msg: e.message
                });
            }
        }
    });
}
   