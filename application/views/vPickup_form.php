<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
    var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
    var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
    var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
    var otoritas = "<?php echo $this->session->userdata('kode otoritas'); ?>";
    var aksi = "<?php echo $aksi; ?>";
    var docno = "<?php echo $docno; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/pickup_form.js"></script>

<style>
    .panel-titleq .panel-tool{
        height:50px;
        line-height: 50px;
    }
    }
    .textbox-readonly,
    .textbox-label-readonly {
        opacity: 0.6;
        filter: alpha(opacity=60);
    }
    .border-kotak {
        border: solid;
        border-width: 2px !important;
    }
</style>
<div id="tt">
   
</div>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
         style="width:100%;height:100%;background:#fafafa;"
         data-options="iconCls:'icon-sales',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">

                <div style="width: 50%; padding: 10px;" class="border-kotak">  
                     <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                        <a href="<?php echo base_url('Pickup')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
                        <a href="javascript:void(0)" id="new" class="easyui-linkbutton" iconCls="icon-save" onclick="addform('')" style="width:90px; height: 20px;">NEW</a>
                        <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Save</a>
                        <a href="javascript:void(0)" id="update" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Update</a>    
                    </div>
                    <div style="margin-bottom:1px">

                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 90%;">
                                <div style="margin-bottom:1px;"> 
                                    <input type="hidden" name="id" id="id" class="easyui-textbox" > 
                                    <input name="pickupby" id="pickupby" class="easyui-combobox" labelPosition="top" tipPosition="bottom" label="PickUp By:" label="" style="width:100%"> 
                                </div> 
                            </div>
                        </div>
                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 90%;">
                                <div style="margin-bottom:1px;">
                                <input name="customer_code" id="customer_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Expedisi by:" label="" style="width:100%">
                                </div>
                                <input name="customer_name" id="customer_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" label="" style="width:100%">
                            </div>
                        </div>    
                         <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="doc_date" id="doc_date" class="easyui-datebox" required="true" labelPosition="top" tipPosition="bottom" label="Tanggal:" style="width:100%">
                        </div>
                        <div id="divparentstatus" style="float:left; width: 25%; padding-right: 5px;">
                            <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label="Status:" style="width:100%">
                        </div>
                        <!-- <div style="float:right; width:10%;">
                            <input name="line" id="line" class="easyui-textbox" label="#Line"  labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label=" " style="width:100%">
                        </div> -->
                        <div id="divparentfase" style="float:right; width:15%;">
                            <input name="fase" id="fase" class="easyui-textbox" label="Fase#"  labelPosition="top" tipPosition="bottom"
                                   readonly="true" label=" " style="width:100%">
                        </div>
                         <div id="divparentpickup_by" style="float:left; width: 50%; padding-right: 5px;">
                            <input name="pickup_by" id="pickup_by" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="PickUp By:" style="width:100%">
                        </div>
                         <div id="divparenttgl_pickup" style="float:left; width: 50%; padding-right: 5px;">
                            <input name="tgl_pickup" id="tgl_pickup" class="easyui-datebox"   labelPosition="top" tipPosition="bottom" label="Tgl PickUp:" style="width:100%">
                        </div>
                    </div>
                </div>
                <div style="width: 100%; padding: 10px;" id="getbarcode" class="border-kotak">
                     <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;"> 
                       <!--  <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Simpan</a> -->
                        <a href="javascript:void(0)" id="pickup" class="easyui-linkbutton" iconCls="icon-box1" onclick="pickupget('')" style="width:90px; height: 20px;">Pick Up</a>     
                    </div>
                    <div style="margin:5px;"> 
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="so_no" id="so_no" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Barcode:" style="width:100%">
                        </div>  
                    </div>     
                    <div style="margin:5px;"> 
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="totalpick" id="totalpick" class="easyui-textbox" labelPosition="top" label="Total Paket:" tipPosition="bottom" style="width:100%">
                        </div>  
                    </div> 
                </div>  
                <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
            </div> 
            <div class="easyui-layout" style="width:100%;height:100%">
                <div id="p" data-options="region:'west'" style="width:100%;">
                    <table id="dg"  class="easyui-edatagrid" style="width:100%;height: 100%">
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript"> 

     $('#pickupby').combobox({
        data:[
            {value:'Ekspedisi',text:'Pick Up by Ekspedisi'},
            {value:'Ojol',text:'Pick Up by Ojol'}
        ],
        prompt:'-Please Select-',
        validType:'inList["#pickupby"]',
    }); 
$('#pickupby').combobox({
    inputEvents: $.extend({}, $.fn.combobox.defaults.inputEvents, {
        blur: function(){
            var event = new $.Event('keydown');
            event.keyCode = 13;
            var dp = $('#pickupby').combobox('getValue'); 
            pickupby(dp); 
        }
    })
}) 
$('#so_no').textbox({
    inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
        keyup:function(e){

          var input = $(this).val();  
         if (e.keyCode == 13){
                
            var dp = $('#so_no').textbox('getValue');
            var doc_date = $('#doc_date').textbox('getValue'); 
            submitdetail(dp,docno,doc_date); 
          }
        }
    })
});
function pickupby(dp) {
   $('#customer_code').combogrid({
        idField: 'customer_code',
        textField:'customer_code',
        url:base_url+"Pickup/load_gridexpedisi",
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
            //console.log(data)
            data.rows = [];
            if (data.data) data.rows = data.data;
            return data;
        },
        onSelect:function (index, rw) {
         // console.log(rw.id); 
            $('#customer_code').combogrid('setValue',rw.id)
            $('#customer_name').textbox('setValue',rw.ekspedisi)
            $('#so_no').textbox('textbox').focus();   
            $("#customer").show();   
        },
        columns: [[
            {field:'id', title:'Kode', width:200},
            {field:'ekspedisi', title:'Ekspedisi', width:300},
        ]]
    }); 
    var gr =  $('#customer_code').combogrid('grid') 
       $('#customer_name').textbox('setValue','')
       $('#so_no').textbox('textbox').focus();   
    gr.datagrid('destroyFilter'); 
    gr.datagrid('enableFilter');
    gr.datagrid('addFilterRule', {
        field: 'tipe',
        op: 'equal',
        value:dp
    });
    gr.datagrid('doFilter');
}

 
</script>