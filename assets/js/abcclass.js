var options={
    fitColumns:true,
    width:"100%",
    url: base_url+"abcclass/load_grid",
    method:"POST",
    pagePosition:"top",
    resizeHandle:"right",
    resizeEdge:10,
    pageSize:20,
    clientPaging: false,
    remoteFilter: true,
    rownumbers: false,
    pagination:true, striped:true, nowrap:false,
    sortName:"code",
    sortOrder:"asc",
    singleSelect:true,
    toolbar:'#toolbar',
    loadFilter: function(data){
        if (data.data){
            data.rows = data.data;
            return data;
        } else {
            return data;
        }
    },
    columns:[[
        {field:"code",   title:"Kode",      width: '30%', sortable: true},
        {field:"description",   title:"Deskripsi",      width: '60%', sortable: true},
        {field:"action", title:"Action",    width:"10%", formatter: function(value, row){
               return `<a href="#" onclick="deleteData('`+row.code+`');" title="Cancel" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-cancel">&nbsp;</span></span>
                        </a>
                        <a href="#" onclick="editData('`+row.code+`');" title="Edit/Update" class="easyui-tooltip l-btn l-btn-small l-btn-plain" group="" id="">
                        <span class="l-btn-left l-btn-icon-left">
                        <span class="l-btn-text l-btn-empty">&nbsp;</span>
                        <span class="l-btn-icon icon-edit">&nbsp;</span></span>
                        </a>`;
            }
        }
    ]],
    onSelect: function(index, row) {
        $(this).datagrid('unselectRow', index);
    }
};

setTimeout(function () {
    initGrid();
},2000);

var flag = undefined;
function initGrid() {
    var dg = $('#dg').datagrid(options);
}

function addnew(){
    clearFormInput();
    flag = "abcclass/save_data";
    $('#modal_edit').modal('show');
}
function upload(){
    $('#modal_upload').modal('show');
}
function editData(id){
    $.ajax({

        type:"POST",
        url:base_url+"abcclass/read_data/"+id,
        dataType:"html",
        success:function(result){
            flag = "abcclass/edit_data";
            clearFormInput();
            console.log(result);
            var data = $.parseJSON(result);
            $("input[name='code']").val(data.data.code);
            $("input[name='description']").val(data.data.description);
            $('#modal_edit').modal('show');

        }
    });
}

function uploadData() {
    var iform = $('#form_upload')[0];
    var data = new FormData(iform);

    $.ajax({
        url: base_url+"abcclass/upload_data",
        type: 'post',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        data: data,
        success: function(result){
            var res = $.parseJSON(result);
            if (res.status===1){
                alert(res.msg)
            }
            $('#dg').datagrid('reload');
            $('#modal_upload').modal('toggle');
        }
    });
}

function deleteData(id){
    bootbox.confirm("Anda yakin akan menghapus data ini ?",
        function(result){
            if(result==true){

                $.post(
                    base_url+"abcclass/delete_data/"+id,function(result){
                        var res = $.parseJSON(result);
                        if (res.status===1){
                            alert(res.msg)
                        } else {
                            $('#dg').datagrid('reload');    // reload the user data
                            clearFormInput();
                        }
                    }
                );
            }
        }
    );
}
function submit(){
    console.log(flag)
    $("#form_editing").ajaxSubmit({
        url: base_url+flag,
        type: 'post',
        success: function(result){
            var res = $.parseJSON(result);
            console.log(result);
            console.log(res.status);
            if (res.status===1){
                alert(res.msg)
            } else {
                $('#dg').datagrid('reload');    // reload the user data
                $('#modal_edit').modal('toggle');
                clearFormInput();
            }
        }
    });
}

function clearFormInput() {
    document.getElementById("form_editing").reset();
}
