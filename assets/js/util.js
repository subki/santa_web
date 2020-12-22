
function disable_enable(bool) {
    // $("#fm :input").prop("disabled", bool);
    // $("#fm1 :input").prop("disabled", bool);
    // $("#fm2 :input").prop("disabled", bool);
    // $("#fm3 :input").prop("disabled", bool);
    // $("#fm :input").prop("readonly", bool);
    // $("#fm1 :input").prop("readonly", bool);
    // $("#fm2 :input").prop("readonly", bool);
    // $("#fm3 :input").prop("readonly", bool);
    $('.easyui-textbox').textbox({
        disabled:bool,
        readonly:bool,
        width:'100%',
        labelPosition:'top',
        tipPosition:'bottom'
    });
    $('.easyui-combobox').combobox({
        disabled:bool,
        readonly:bool,
        width:'100%',
        labelPosition:'top',
        tipPosition:'bottom'
    });
    $('.easyui-datebox').datebox({
        disabled:bool,
        readonly:bool,
        width:'100%',
        labelPosition:'top',
        tipPosition:'bottom'
    });
    $('.easyui-numberbox').numberbox({
        disabled:bool,
        readonly:bool,
        width:'100%',
        labelPosition:'top',
        tipPosition:'bottom'
    });
    $('.easyui-combogrid').combogrid({
        disabled:bool,
        readonly:bool,
        width:'100%',
        labelPosition:'top',
        tipPosition:'bottom'
    });
}

function disabledonly(bool) {
    $('.easyui-textbox').textbox({
        disabled:bool,
    });
    $('.easyui-combobox').combobox({
        disabled:bool,
    });
    $('.easyui-datebox').datebox({
        disabled:bool,
    });
    $('.easyui-numberbox').numberbox({
        disabled:bool,
    });
    $('.easyui-combogrid').combogrid({
        disabled:bool,
    });
}
function formattanggal(value, row){
    if(value===null) return "";
    let d = new Date(value);
    return ("0" + d.getDate()).slice(-2) + "/" + ("0" + (d.getMonth() + 1)).slice(-2) + "/" +
        d.getFullYear()
}

function formatnumberbox(value) {
    var value = $.fn.numberbox.defaults.formatter.call(this,value);
    value = parseFloat(value);
    return (value)?value:'0';
}
function numberFormat(x){
    if(x===null) return 0;
    if (!isNaN(x)) return parseFloat(x).toLocaleString('en')
    return "0"
}

function getParamOption(el, callback) {
    var rules  = $("#"+el).datagrid('options').filterRules;
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
    callback(x,x1,x2)
}

$.extend($.fn.textbox.methods, {
    show: function(jq){
        return jq.each(function(){
            $(this).next().show();
        })
    },
    hide: function(jq){
        return jq.each(function(){
            $(this).next().hide();
        })
    }
});
$.extend($.fn.datebox.methods, {
    show: function(jq){
        return jq.each(function(){
            $(this).next().show();
        })
    },
    hide: function(jq){
        return jq.each(function(){
            $(this).next().hide();
        })
    }
});
$.extend($.fn.numberbox.methods, {
    show: function(jq){
        return jq.each(function(){
            $(this).next().show();
        })
    },
    hide: function(jq){
        return jq.each(function(){
            $(this).next().hide();
        })
    }
});
$.extend($.fn.combobox.methods, {
    show: function(jq){
        return jq.each(function(){
            $(this).next().show();
        })
    },
    hide: function(jq){
        return jq.each(function(){
            $(this).next().hide();
        })
    }
});
$.fn.datebox.defaults.formatter = function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return (d<10?('0'+d):d)+'/'+(m<10?('0'+m):m)+'/'+y;
    // return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
};
$.fn.datebox.defaults.parser = function(s){
    // console.log(s)
    if (!s) return new Date();
    var ss = s.split('/');
    var d = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var y = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
};

$.extend($.fn.validatebox.defaults.rules,{
    isNumberOnly:{
        validator:function(value,param){
            console.log("validate",value)
            console.log("validate",param)
            return !isNaN(value);
        },
        message:'Invalid value'
    },
    inList:{
        validator:function(value,param){
            var c = $(param[0]);
            var opts = c.combobox('options');
            var data = c.combobox('getData');
            var exists = false;
            // console.log("opt",opts.multiple)
            // console.log("val",value)
            if(opts.multiple){
                var vv = value.split(",");
                var b = 0;
                for(var ii=0; ii<vv.length; ii++){
                    for(var i=0; i<data.length; i++){
                        if (vv[ii].toLowerCase() === data[i][opts.textField].toLowerCase()){
                            b++;
                            break;
                        }
                    }
                }
                exists = b==vv.length;
            }else {
                for (var i = 0; i < data.length; i++) {
                    if (value.toLowerCase() === data[i][opts.textField].toLowerCase()) {
                        exists = true;
                        break;
                    }
                }
            }
            return exists;
        },
        message:'Invalid value'
    },
    cekKeberadaan:{
        validator:function (value, param) {
            console.log(value);
            var selectedrow = $(param[0]).edatagrid("getSelected");
            var rowIndex = $(param[0]).edatagrid("getRowIndex", selectedrow);
            var ed = $(param[0]).edatagrid('getEditor',{
                index:rowIndex,
                field:param[1]
            });
			
			if(ed===null) return true;
            var c = $(ed.target);
            var opts = c.combobox('options');
            console.log(opts.multiple);
            var data = c.combobox('getData');
            var exists = false;
            for(var i=0; i<data.length; i++){
                if (value.toLowerCase() === data[i][opts.textField].toLowerCase()){
                    exists = true;
                    break;
                }
                if(opts.multiple){
                    if (value.toLowerCase().includes(data[i][opts.textField].toLowerCase())){
                        exists = true;
                        break;
                    }
                }
            }
            return exists;
        },
        message:'Invalid value'
    }
})

var pgl = 0;
function authbutton() {
    // console.log("panggil", pgl);
    pgl++;
    if(global_auth[global_auth.appId]===undefined){
        setTimeout(function () {
            authbutton()
        },1000)
    }
    else {
        console.log("panggil", 'end');
        authbutton1();
    }
}
function authbutton1() {
    $('#add').linkbutton({disabled:global_auth[global_auth.appId].allow_add==="0"});
    $('#edit').linkbutton({disabled:global_auth[global_auth.appId].allow_update==="0"});
    $('#delete').linkbutton({disabled:global_auth[global_auth.appId].allow_delete==="0"});
    $('#download').linkbutton({disabled:global_auth[global_auth.appId].allow_download==="0"});
    $('#unposting').linkbutton({disabled:global_auth[global_auth.appId].allow_unposting==="0"});

    $('#add2').linkbutton({disabled:global_auth[global_auth.appId].allow_add==="0"});
    $('#edit2').linkbutton({disabled:global_auth[global_auth.appId].allow_update==="0"});
    $('#delete2').linkbutton({disabled:global_auth[global_auth.appId].allow_delete==="0"});
    $('#download2').linkbutton({disabled:global_auth[global_auth.appId].allow_download==="0"});


	let xx = false;
	if((global_auth[global_auth.appId].allow_add==="0") && (global_auth[global_auth.appId].allow_update==="0")){
		xx = true;
	}
    $('#submit').linkbutton({disabled:xx});
}

function canEdit(){
	return global_auth[global_auth.appId].allow_update==="0";
}

function myConfirm(title, msg, negatif, positif, callback) {
    var dlg = $.messager.confirm({
        title: title,
        msg: msg,
        buttons:[{
            text: negatif,
            onClick: function(){
                callback(negatif)
                dlg.dialog('destroy')
            }
        },{
            text: positif,
            onClick: function(){
                callback(positif)
                dlg.dialog('destroy')
            }
        }]
    });
}
function myConfirm3(title, msg, negatif, netral, positif, callback) {
    var dlg = $.messager.confirm({
        title: title,
        msg: msg,
        buttons:[{
            text: negatif,
            onClick: function(){
                callback(negatif)
                dlg.dialog('destroy')
            }
        },{
            text: netral,
            onClick: function(){
                callback(netral)
                dlg.dialog('destroy')
            }
        },{
            text: positif,
            onClick: function(){
                callback(positif)
                dlg.dialog('destroy')
            }
        }]
    });
}
function inputReason(title, msg, callback) {
	$.messager.prompt({
		title: title,
		msg: msg,
		fn: function (r) {
			if (r) {
				callback(r)
			}
		}
	});
}

function authorization(pesan, docno, tabel, callback){
    var m = $.messager.prompt('Warning', pesan, function(r){
        if (r){
            $.ajax({
                url: base_url+"salesorder/cek_authority",
                type: 'post',
                data: {
                    kode_otoritas:r,
                    docno:docno,
                    tabel:tabel
                },
                success: function(result){
                    var res = $.parseJSON(result);
                    if(res.status===0){
                        $.messager.show({title:'Success',msg:res.msg})
                        callback(true);
                    }else{
                        $.messager.show({title:'Invalid',msg:res.msg})
                        callback(false);
                    }
                }
            });
        }
    });
    m.find('.messager-input').passwordbox({
        prompt: 'Kode Otoritas',
        showEye: true,
        width:'80%'
    });
}

/**
 * @param product_id
 * @param doc_date
 * @param location_code
 * @param customer_code
 * @param callback
 */
function util_get_unit_price(product_id,doc_date,location_code,customer_code, callback) {
	$.ajax({
		type:"POST",
		url: `${base_url}salesorder/get_unit_price?product_id=${product_id}&tanggal=${doc_date}&lokasi=${location_code}&customer_code=${customer_code}`,
		dataType:"json",
		success:function(result){
			console.log(result)
			callback(result)
		}
	});
}