var Stockopname=undefined;
var flag = "";
$(document).ready(function () {
 

 $('#so_no').textbox('textbox').focus();   
    if(aksi==="add"){
        flag = "Stockopname/save_data_header";
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y; 
        var tglset =  y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d); 
        $("#trx_date").datebox('setValue', tgl);
        $("#trx_date").datebox('setText', tgl);  
        $("#store_code").combogrid('setValue',store_code);
        $("#jenis_adjust").combogrid('setValue','Stock Taking'); 
        $("#on_loc").combogrid('setValue',location_code);
        $("#trx_no").textbox('setValue',docno);
        $("#status").textbox('setValue','OPEN');
        $("#print").textbox('setValue','0');
        $("#update").hide();
        $("#printOp").hide();
        $("#Unsubmit").hide();
        $("#posting").hide();
        $("#posting").hide();
        $("#cancel").hide(); 
        $("#new").hide(); 
 
       $("#barcode") .css ("display", "none");
        

    }else{
        flag = "Stockopname/edit_data_header";
        $.ajax({
            type:"POST",
            url:base_url+"Stockopname/read_data/"+docno,
            dataType:"json",
            success:function(result){
             console.log(result);
                var total=(result.total)?(result.total.jumlah)?result.total.jumlah:0:0;
                $("#totalpick").textbox('setValue',total);
                if(result.status===0) {
                    $('#fm').form('load',result.data);
                    console.log(result);
                    Stockopname = result.data;
                    Stockopnamedata = result.totalopname;
                    initHeader()  
                    $("#tot_qty").textbox('setValue',Stockopnamedata.tot_qty); 
                    $("#tot_item").textbox('setValue',Stockopnamedata.tot_item);
                }
                else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.message,
                        handler:function () {
                            window.location.href = base_url+"Stockopname";
                        }
                    });
                }

            }
        });
    } 
});

function initHeader() {  

    var date = new Date(Stockopname.tgl);
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    var tgl =  (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
    if(Stockopname.tgl_Stockopname==null){ 
        $("#tgl_Stockopname").datebox('setValue', '');
        $("#tgl_Stockopname").datebox('setText', '');
    }else{

        var date2 = new Date(Stockopname.tgl_Stockopname);
        var y2 = date2.getFullYear();
        var m2 = date2.getMonth()+1;
        var d2 = date2.getDate();
        var tglpick =  (d2<10?('0'+d2):d2)+'/'+(m2<10?('0'+m2):m2)+'/'+y2;

        $("#tgl_Stockopname").datebox('setValue', tglpick);
        $("#tgl_Stockopname").datebox('setText', tglpick);
    }
    $("#doc_date").datebox('setValue', tgl);
    $("#doc_date").datebox('setText', tgl);
    $("#id").textbox('setValue',Stockopname.id);    
    $('#gondola').combogrid({"readonly":true});   
    initGrid();
    $("#update").hide();
    $("#submit").hide();
        $("#printOp").show();
    if(Stockopname.status==="Open"){ 
        $("#update").hide();
        $("#Unsubmit").hide();
    }
    if(Stockopname.status==="Posted"){
        $("#posting").hide();
        $("#cancel").hide();
        $("#update").hide();
       $("#barcode") .css ("display", "none"); 
    }

}

function printOP() {
        // window.open(base_url+'Online/print_so/'+docno, '_blank', 'location=yes,height=400,width=500,scrollbars=yes,status=yes');
     window.open(base_url+'Stockopname/print_op/'+docno, '_blank');
    // $.ajax({
    //     type:"get",
    //     url:base_url+"Stockopname/print_so/"+docno,
    //     dataType:"json",
    //     success:function(result){
    //         //console.log(result.data)
    //         if(result.status===0) {
    //             $.messager.alert("Success","Print Berhasil")
    //         }
    //         else {
    //             $.messager.alert("Info","Print Gagal")
    //         }

    //     }
    // });
}
function addform() {
         window.location.href = base_url+"Stockopname/form/add";
   // window.open(base_url+'Stockopname/print_so/'+docno, '_blank');
}  
function posting() {
    //if(pickup==null) return 
 
     $.ajax({
                type:"POST",
                url:base_url+"Stockopname/Updatestatus/"+docno,
                dataType:"json",
                data: {
                    docno:docno, 
                    status:'Posted'   
                },
                success:function(result){  
               // console.log(docno)
                       if(result.status===1) { 
                            $.messager.show({
                                title: 'Error',
                                msg: result.message, 
                                handler:function () {
                                   window.location.href = base_url + "Stockopname/form/edit?id=" + docno
                                }
                            }); 
                        }
                        else {
                            window.location.href = base_url+"Stockopname/form/edit?id=" + docno;
                        }
                }
            });   
} 
function unposting() {
    //if(pickup==null) return 
 
     $.ajax({
                type:"POST",
                url:base_url+"Stockopname/Updatestatus/"+docno,
                dataType:"json",
                data: {
                    docno:docno,
                    status:'Open'   
                },
                success:function(result){  
               // console.log(docno)
                       if(result.status===1) { 
                            $.messager.show({
                                title: 'Error',
                                msg: result.message, 
                                handler:function () {
                                   window.location.href = base_url + "Stockopname/form/edit?id=" + docno
                                }
                            }); 
                        }
                        else {
                            window.location.href = base_url+"Stockopname/form/edit?id=" + docno;
                        }
                }
            });   
} 
function submitdetail(dp,docno,doc_date,qty,gondola) {
    //if(pickup==null) return 

var dateAr = doc_date.split('/');
var newDate = dateAr[2] + '-' + dateAr[1] + '-' +  dateAr[0].slice(-2);
  
    $.ajax({
        url: base_url+"Stockopname/submitdetail",
        type: 'post',
        dataType:"json",
        data: {
            barcode:dp,
            trx_no:docno,
            gondola:gondola,
            store_code:store_code,
            location_code:location_code,
            tanggal:newDate,
            qty:qty
        },
        success: function(result){ 
                if (result.status===1){
                      alert(result.msg); 
                      return;
                } 
            console.log(result.status) 
            $("#tot_item").textbox('setValue',result.totalopname.tot_item); 
            $("#tot_qty").textbox('setValue',parseInt(result.totalopname.tot_qty)+1); 
            // if (res.status===1){
            //     // alert(res.msg)
            //     $.ajax({
            //         type:"POST",
            //         url:base_url+"Stockopname/read_dataopname/"+docno,
            //         dataType:"json",
            //         success:function(result){
            //          console.log(result);
            //             // var total=result.total.jumlah;
            //             // $("#totalpick").textbox('setValue',total);  
            //         }
            //     });
            // }  
            // else{
            //     alert(res.message);
            // }
            $('#dg').edatagrid('reload'); 
            // cancelUpload();
        }
    }); 
    
       $('#so_no').textbox('setValue','')
       $('#so_no').textbox('textbox').focus();   
} 
function Stockopnameget() {
    //if(Stockopname==null) return 

    var Stockopname=$("#Stockopname_by").textbox('getValue');   
    var Stockopnamedate=$("#tgl_Stockopname").textbox('getValue');  
    
    if(Stockopname==="" || Stockopnamedate===""){
        if(Stockopname===""){
            var datafocus='Data Stockopname Belum Lengkap';
        }
        else{
            var datafocus='Tanggal Stockopname Belum Terisi';
        }
         $.messager.show({
                title: 'Error',
                msg: datafocus 
            });
    }
    else{
      $.ajax({
                type:"POST",
                url:base_url+"Stockopname/Updatestatus/"+docno,
                dataType:"json",
                data: {
                    Stockopname:Stockopname,
                    Stockopnamedate:Stockopnamedate 
                },
                success:function(result){  
               // console.log(docno)
                       if(result.status===1) { 
                            $.messager.show({
                                title: 'Error',
                                msg: result.message, 
                                handler:function () {
                                   window.location.href = base_url + "Stockopname/form/edit?id=" + docno
                                }
                            }); 
                        }
                        else {
                            window.location.href = base_url+"Stockopname/form/edit?id=" + docno;
                        }
                }
            });  
    }  
}  
function reload_header() {
    $.ajax({
        type:"POST",
        url:base_url+"Stockopname/read_data/"+id,
        dataType:"json",
        success:function(result){
          //  console.log(result.data)
            if(result.status===0) {
                $('#fm').form('load',result.data);
                Stockopname = result.data;
                initHeader()
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"Stockopname";
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
    if(!Stockopname) return
    $("#dg").edatagrid({
        fitColumns: false,
        width: "100%",
        url: base_url + "Stockopname/load_grid_detail/"+Stockopname.trx_no,
        saveUrl: base_url + "Stockopname/save_data_detail/"+Stockopname.trx_no,
        updateUrl: base_url + "Stockopname/edit_data_detail", 
        idField: 'barcode',
        method: "POST",
        pagePosition:"top",
        resizeHandle:"right",
        resizeEdge:10,
        pageSize:20,
        clientPaging: true,
        remoteFilter: true,
        rownumbers: false,
        pagination:true, striped:true, nowrap:true, 
        singleSelect:true,
        sortName: "tgl",
        sortOrder: "desc",  

        toolbar: [
        // {
        //     iconCls: 'icon-add', id:'add', text:'New',
        //     handler: function(){$('#dg').edatagrid('addRow',0)}
        // },
        {
            id:'delete', iconCls: 'icon-remove', text:'Delete',
            handler: function(){
                if (Stockopname.status!=="Open"){
                    $.messager.show({
                        title: 'Warning',
                        msg: `Detail tidak bisa di hapus (status : ${Stockopname.status})`
                    });
                    return
                }
                else{       
                    deleteData(); 
                }
               
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

                    //console.log(data)
            data.rows = [];
            if (data.data) {
                data.rows = data.data;
            }
            return data;
        },
        onLoadSuccess: function () {
            authbutton();
            var dt = $("#dg").edatagrid('getData'); 
            if(dt.rows.length>0){
                // $('#customer_name').combogrid({"readonly":true});
                // $('#customer_name').combogrid('setValue', Stockopname.customer_name);
                $("#kopi").linkbutton({"disabled":true})
                // $('#open_cust').show();
            }else {
                $("#kopi").linkbutton({"disabled":false})
                // $('#open_cust').hide();
            }
        },
        onBeginEdit: function (index, row) {

            if (row.isNewRecord ){
                if(Stockopname.status!=="OPEN") {
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
            if(Stockopname.status!=="Open") {
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
                title: "Item#",
                width: '200',
                sortable: true,
                formatter: function (value, row) {
                    //console.log(row)
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
                        }
                    }
                }
            }, 
            {field: "product_code", title: "Product code", sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "uom", title: "Uom", sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "taking_qty", title: "Qty", sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "store", title: "Store",sortable: true, editor: {type: 'textbox',options:{disabled:true}}},
            {field: "crtby1", title: "Create by", sortable: true,editor: {type: 'textbox',options:{disabled:true}}},
            {field: "crtdt1", title: "Create time", sortable: true,editor: {type: 'textbox',options:{disabled:true}}},
            {field: "updby1", title: "Update by", sortable: true,editor: {type: 'textbox',options:{disabled:true}}},
            {field: "upddt1", title: "Update time", sortable: true,editor: {type: 'textbox',options:{disabled:true}}},
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
    //('#dg').edatagrid('enableFilter');  
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
                        docno:Stockopname.docno,
                        status:status
                    },
                    success:function(result){
                        //console.log(result.data)
                        if(result.status===0) {
                            window.location.href = base_url + "Stockopname/form/edit?docno=" + Stockopname.id
                        }
                        else {
                            $.messager.show({
                                title: 'Error',
                                msg: e.message,
                                handler:function () {
                                    window.location.href = base_url + "Stockopname/form/edit?docno=" + Stockopname.id
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
    let status = (stt==="")?(Stockopname)?Stockopname.status:'OPEN':stt;
    if(Stockopname!==undefined && Stockopname.status==="ON Waiting" && status==="Stockopname") status = 'OPEN';
    $('#status').textbox('setValue',status);

    if(aksi==="add") {
        submit_reason("Open")
    }else{
        if(Stockopname!==undefined){
            if(Stockopname.status==="ON Waiting" && status==="Open"){
                read_packinglist(function (res) {
                    if(res==="OK"){
                        if(parseInt(global_auth[global_auth.appId].allow_unposting)===0){
                            $.messager.show({title:'Error', msg:'Anda tidak memiliki otoritas Unposting'});
                            $('#status').textbox('setValue',Stockopname.status);
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
            }else if(Stockopname.status === status){
                submit_reason("")
            }else{
                myConfirm("Confirm", "Anda yakin ingin mengubah status sales ini?", "Yes", "No", function (r) {
                    if (r === "Yes") {
                        if (Stockopname.credit_limit==="" || isNaN(Stockopname.credit_limit) ||
                            Stockopname.sales_after_tax==="" || isNaN(Stockopname.sales_after_tax) ||
                            Stockopname.credit_limit-Stockopname.outstanding<=0 ||
                            (parseFloat(Stockopname.credit_limit) - parseFloat(Stockopname.outstanding))< parseFloat(Stockopname.sales_after_tax)) {
                            //cek otoritas
                            if(parseInt(global_auth[global_auth.appId].allow_approve)>0){
                                submit_reason("")
                            }else {
                                var psn = 'Nilai transaksi melebihi limit customer, dibutuhkan otorisasi untuk memposting.';
                                $.messager.show({title: 'Error', msg: psn});
                                $('#status').textbox('setValue', Stockopname.status);
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
            $('#status').textbox('setValue', Stockopname.status);
        }
    }

}
function submit_reason(reason,id) {
   // console.log(reason);
   //  console.log(base_url+flag);
    $("#reason").textbox('setValue', reason);
   $.ajax({
        type:"POST", 
        url:base_url+"Stockopname/delete_data_detail",
        dataType:"json",
        data:{
            reason:reason,
            id:id
        },
        success:function(result){
            console.log(result.data)
            // if(result.status===0) {
            //     window.location.href = base_url + "Stockopname/form/edit?docno=" + Stockopname.id
            // }
            // else {
            //     $.messager.show({
            //         title: 'Error',
            //         msg: e.message,
            //         handler:function () {
            //             window.location.href = base_url + "Stockopname/form/edit?docno=" + Stockopname.id
            //         }
            //     });
            // }

        }
    });
}
function getRow() {
    var row = $('#dg').datagrid('getSelected');
    if (!row){
        $.messager.show({    // show error message
            title: 'Error',
            msg: 'Please select data to edit.'
        });
        return null;
    }else{
        row.record = $('#dg').datagrid("getRowIndex", row);
    }
    return row;
}
function deleteData(){ 
    let row = getRow(true);
    if(row==null) return 
      $.ajax({
            type:"POST", 
            url:base_url+"Stockopname/delete_data_detail",
            dataType:"json",
            data:{ 
                id:docno,
                row:row.item
            },
            success:function(result){
                //console.log(result.message)
                if(result.status===0) { 
                    $('#dg').edatagrid('reload'); 
                }
                else { 
                    $('#dg').edatagrid('reload'); 
                }

            }
        });
} 
function submit_reason2(reason,id,row) {
   // console.log(reason);
   //  console.log(base_url+flag);

    $("#reason").textbox('setValue', reason);
   $.ajax({
        type:"POST", 
        url:base_url+"Stockopname/delete_data_detail",
        dataType:"json",
        data:{
            reason:reason,
            id:id,
            row:row 
        },
        success:function(result){
            //console.log(result.message)
            if(result.status===0) {
                window.location.href = base_url + "Stockopname/form/edit?id=" + Stockopname.id
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: result.message,
                    handler:function () {
                        window.location.href = base_url + "Stockopname/form/edit?id=" + Stockopname.id
                    }
                });
            }

        }
    });
} 
function submitCopy() {
    if(Stockopname==null) return
    var xx = $('#combo').combogrid('getValue');
    //console.log(xx)
    $.ajax({
        url: base_url+"Stockopname/copy_detail",
        type: 'post',
        data: {
            from:xx,
            to:Stockopname.docno
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
                            window.location.href = base_url + "Stockopname/form/edit?id=" + res.id
                        })
                    }else{
                        window.location.href = base_url + "Stockopname/form/edit?id=" + res.id
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
   