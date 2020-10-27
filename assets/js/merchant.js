$(document).ready(function () {
    init("nama_pkp");
    init("alamat_pkp");
    init("npwp");
    init("tanggal_pengukuhan");
    init("prefix_seri_pajak");
    init("nama_bagian");
    init("pemegang_bagian");
});
function init(id) {
    $(`#${id}`).textbox({icons:[{iconCls:'icon-edit', handler: function () {editData(id)}}]});
    $(`#${id}`).textbox({editable:false});
}
function editData(id) {
    $(`#${id}`).textbox({icons:[{iconCls:'icon-ok', handler: function () {
        saveData(id)
    }}]});
    $(`#${id}`).textbox({editable:true});
}
function saveData(id) {

    $.ajax({
        type:"POST",
        url:base_url+"merchant/edit_data",
        data:{
            key:id.replace("_"," "),
            value:$(`#${id}`).textbox('getValue')
        },
        dataType:"json",
        success:function(result){
            console.log(result.data)
            if(result.status===0) {
                $(`#${id}`).textbox({icons:[{iconCls:'icon-edit', handler: function () {
                    editData(id)
                }}]});
                $(`#${id}`).textbox({editable:false});
            }
            else {
                $.messager.show({
                    title: 'Error',
                    msg: e.message,
                    handler:function () {
                        window.location.href = base_url+"merchant";
                    }
                });
            }

        }
    });
}