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
<script src="<?php echo base_url(); ?>assets/js/sp_form.js"></script>

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
   <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <a href="<?php echo base_url('Stockopname')?>" id="back" class="easyui-linkbutton" iconCls="icon-undo" style="width:90px; height: 20px;">Back</a>
        <a href="javascript:void(0)" id="new" class="easyui-linkbutton" iconCls="icon-save" onclick="addform('')" style="width:90px; height: 20px;">NEW</a>
        <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Save</a>
        <a href="javascript:void(0)" id="update" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Update</a><a href="javascript:void(0)" id="printOp" class="easyui-linkbutton" iconCls="icon-print" onclick="printOP()"  style="width:90px; height: 20px;">Print</a>
        <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-box1" onclick="posting('')"  style="width:90px; height: 20px;">Posting</a>
        <a href="javascript:void(0)" id="Unsubmit" class="easyui-linkbutton" iconCls="icon-box1" onclick="unposting('')"  style="width:90px; height: 20px;">UnPosting</a>
    </div>
</div>
<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" class="easyui-panel" title="<?php echo $title; ?>"
         style="width:100%;height:100%;background:#fafafa;"
         data-options="iconCls:'icon-sales',closable:false,
                collapsible:false,minimizable:false,maximizable:false,
                tools:'#tt', headerCls:'panel-titleq'">
        <form id="fm" method="post" novalidate style="margin:0;padding:5px 5px">
            <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">

                <div style="width: 80%; padding: 10px;" class="border-kotak">

                    <div style="margin-bottom:1px">
                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 50%;">
                                <div style="margin-bottom:1px;">
                                    <input name="trx_no" id="trx_no" readonly="readonly" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Trx No:" label="" style="width:100%">
                                </div>
                            </div>
                            <div style="float:left; width: 50%;">
                                <div style="margin-bottom:1px;">
                                    <input name="store_code" readonly="readonly" id="store_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Store Code:" label="" style="width:100%">
                                </div>
                            </div>
                            <div style="float:left; width: 50%;">
                                <div style="margin-bottom:1px;">
                                    <input name="jenis_adjust" id="jenis_adjust" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Jenis Adjust:" label="" style="width:100%">
                                </div>
                            </div>
                             <div style="float:left; width: 50%; padding-right: 5px;">
                                <input name="trx_date" id="trx_date" class="easyui-datebox" required="true" labelPosition="top" tipPosition="bottom" label="Tanggal:" style="width:100%">
                            </div>
                        </div>
                    </div>
                     <div style="margin-bottom:1px">
                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 25%;">
                                <div style="margin-bottom:1px;">

                                </div>
                            </div>
                            <div id="barcode" style="float:left; width: 50%;">
                                <div style="margin-bottom:1px;">
                                     <input name="qtyscan" id="qtyscan" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Qty:" style="width:50%">
                                </div>
                                <div style="margin-bottom:1px;">
                                     <input name="so_no" id="so_no" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Barcode:" style="width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; padding: 10px;" class="border-kotak">
                    <div style="margin-bottom:1px">
                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 30%;">
                                <div style="margin-bottom:1px;">
                                    <input readonly="true" name="on_loc" id="on_loc" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Location Code:" style="width:100%">
                                </div>
                            </div>
                            <div style="float:left; width: 50%;">
                                <div style="margin-bottom:1px;">
                                    <input name="gondola" id="gondola" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="true"  label="Gondola:" style="width:100%">
                                </div>
                            </div>
                            <div style="float:left; width: 20%;">
                                <div style="margin-bottom:1px;">
                                    <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="true" readonly="true" label="Status:" style="width:100%">
                                </div>
                            </div>
                            <div style="float:left; width: 25%;">
                                <div style="margin-bottom:1px;">
                                     <input name="tot_item" id="tot_item" class="easyui-textbox" label="Total Item#"  labelPosition="top" tipPosition="bottom"
                                       readonly="true" label=" " style="width:100%">
                                </div>
                            </div>
                            <div style="float:left; width: 25%;">
                                <div style="margin-bottom:1px;">
                                     <input name="tot_qty" id="tot_qty" class="easyui-textbox" label="Total Qty#"  labelPosition="top" tipPosition="bottom"
                                       readonly="true" label=" " style="width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom:1px">
                        <div style="margin-bottom:1px">
                            <div style="float:left; width: 50%;">
                                <div style="margin-bottom:1px;">
                                     <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                       required="true" label="Keterangan:" style="width:100%; height: 100px;">
                                </div>
                            </div>
                            <div style="float:left; width: 10%;">
                                <div style="margin-bottom:1px;">
                                     <input name="print" id="print" class="easyui-textbox" label="Print#"  labelPosition="top" tipPosition="bottom"
                                       readonly="true" label=" " style="width:100%">
                                </div>
                            </div>
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
//      $('#jenis_adjust').combobox({
//         data:[
//             {value:'Stock Adjustment',text:'Stock Adjustment'},
//             {value:'Stock Taking',text:'Stock Taking'},
//             {value:'Write Off Adjustment',text:'Write Off Adjustment'}
//         ],
//         prompt:'-Please Select-',
//         validType:'inList["#jenis_adjust"]',
//     });
//  $('#so_no').textbox({
//     inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
//         keyup:function(e){
//          $("#qtyscan").textbox('setValue','');
//          $('#qtyscan').textbox('textbox').focus();
//         }
//     })
// });
//  $('#qtyscan').textbox({
//     inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
//         keyup:function(e){

//           var input = $(this).val();
//          if (e.keyCode == 13){
//             var dp = $('#so_no').textbox('getValue');
//             var qty = $('#qtyscan').textbox('getValue');
//             var doc_date = $('#trx_date').textbox('getValue');
//             submitdetail(dp,docno,doc_date,qty);
//             $("#qtyscan").textbox('setValue','');
//           }
//         }
//     })
// });
  $('#so_no').textbox({
    inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
        keyup:function(e){
           var input = $(this).val();
             if (e.keyCode == 13){
                var dp = $('#so_no').textbox('getValue');
                var gondola = $('#gondola').textbox('getValue');
                var qty = 1;
                var doc_date = $('#trx_date').textbox('getValue');
                submitdetail(dp,docno,doc_date,qty,gondola);
                $("#qtyscan").textbox('setValue',1);
              }
        }
    })
});

    // function populateCustomer() {
    // $('#on_loc').combogrid({
    //             idField: 'on_loc',
    //             textField:'on_loc',
    //             url:base_url+"Stockopname/load_gridlocation",
    //             required:true,
    //             labelPosition:'top',
    //             tipPosition:'bottom',
    //             hasDownArrow: false,
    //             remoteFilter:true,
    //             panelWidth: 500,
    //             multiple:false,
    //             panelEvents: $.extend({}, $.fn.combogrid.defaults.panelEvents, {
    //                 mousedown: function(){}
    //             }),
    //             editable: false,
    //             pagination: true,
    //             fitColumns: true,
    //             mode:'remote',
    //             loadFilter: function (data) {
    //              // console.log(data)
    //                 data.rows = [];
    //                 if (data.data) data.rows = data.data;
    //                 return data;
    //             },
    //          onSelect:function (index, rw) {

    //             $('#on_loc').combogrid('setValue',rw.customer_code)
    //             $('#on_locname').textbox('setValue',rw.customer_name)

    //         },
    //         columns: [[
    //              {field:'location_code', title:'location_code', width:200},
    //              {field:'decsription', title:'Decsription', width:300},
    //         ]]
    //     });
    //     var gr =  $('#on_loc').combogrid('grid')
    //     gr.datagrid('destroyFilter');
    //             // / $('#customer_name').textbox('setValue','')
    //     gr.datagrid('enableFilter');
    //     //gr.datagrid('doFilter');
    //     }
</script>