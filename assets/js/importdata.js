$(document).ready(function () {
    $('#userfile').filebox({
        buttonText: 'Choose File',
        buttonAlign: 'left',
        icons: [{
            iconCls:'icon-no',
            handler: function(e){
                $(e.data.target).filebox('clear');
            }},{
            iconCls:'icon-save',
            handler: function(e){
                submitUpload()
            }
        }]
    })
});


function submitUpload() {
    console.log("masuk sini");
    var iform = $('#formupload')[0];
    var data = new FormData(iform);
    data.append("satuan_stock", uom_stock);

    //console.log($("#userfile").filebox('getText'))
    $.ajax({
        url: base_url+"importdata/upload_data",
        type: 'post',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        data: data,
        success: function(result){
            console.log(result)
            var res = $.parseJSON(result);
            console.log(res)
            if (res.status===1){
                $.alert("Error", res.msg)
            }else{
                var text = "Berhasil upload data.";
                res.response.forEach(item=>{
                    text += `<br/> ${item.nama} : ${item.insert} of ${item.total}`
                })
                $.messager.alert({
                    title:"Success",
                    msg: text,
                    width:450
                })
                if(res.response_gagal.length>0){
                    initGrid(res.response_gagal)
                }
            }
            // $('#dg').datagrid('reload');
            // cancelUpload();
        }
    });
}
var options={
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    striped:true, nowrap:false,
    columns:[[
        {field:"baris",   title:"Baris",      width:'10%', sortable: true},
        {field:"data",   title:"Data",      width: '60%', sortable: true},
        {field:"why",   title:"Response",      width: '30%', sortable: true},
    ]],
};
function initGrid(data) {
    $('#dg').datagrid(options);
    $('#dg').datagrid({
        data: data
    });
}