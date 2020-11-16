<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
    var store_code = "<?php echo $this->session->userdata('store_code'); ?>";
    var store_name = "<?php echo $this->session->userdata('store_name'); ?>";
    var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>";
    var location_name = "<?php echo $this->session->userdata('location_name'); ?>";
    var aksi = "<?php echo $aksi; ?>";
    var docno = "<?php echo $docno; ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/rekapshowroom_form.js"></script>
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
</style>

<div id="tt">
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <a href="javascript:void(0)" id="submit" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Save</a>
        <a href="javascript:void(0)" id="getdate" class="easyui-linkbutton" iconCls="icon-search" onclick="Opendialog()" style="width:90px; height: 20px;">Get Date</a>
        <a href="javascript:void(0)" id="update" class="easyui-linkbutton" iconCls="icon-save" onclick="submit('')" style="width:90px; height: 20px;">Update</a>
        <a href="javascript:void(0)" id="posting" class="easyui-linkbutton" iconCls="icon-posting" onclick="submit('CLOSED')" style="width:90px; height: 20px;">Posting</a>
        <a href="javascript:void(0)" id="close" class="easyui-linkbutton" iconCls="icon-close" onclick="submit('CLOSE')" style="width:90px; height: 20px;">Close</a>
        <a href="javascript:void(0)" id="print" class="easyui-linkbutton" iconCls="icon-print" onclick="printSO()" style="width:90px; height: 20px;">Print</a>
        <a href="javascript:void(0)" id="customer" class="easyui-linkbutton" iconCls="icon-customer" onclick="showCustomer()" style="width:90px; height: 20px;">Customer</a>
        <a href="javascript:void(0)" id="crt_faktur" class="easyui-linkbutton" iconCls="icon-tax" onclick="createFaktur()" style="width:140px; height: 20px;">Create Faktur</a>
        <a href="javascript:void(0)" id="btn_seri_pajak" class="easyui-linkbutton" iconCls="icon-tax" onclick="createSeriPajak()" style="width:90px; height: 20px;">Get FP</a>
       <!--  <a href="javascript:void(0)" id="verify_fa" class="easyui-linkbutton" iconCls="icon-ok" onclick="verifyFA()" style="width:110px; height: 20px;">Finance</a> -->
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
                <div style="width: 40%; padding: 10px;">
                    <div style="margin-bottom:1px">
                        <div id="dis_faktur1" style="float:left; width: 55%; padding-right: 5px;">
                            <input name="no_faktur" id="no_faktur" class="easyui-textbox khusus" labelPosition="top" tipPosition="bottom"
                                   label="Trx. No:" style="width:100%">
                        </div>
                        <div id="dis_faktur2" style="float:left; width: 55%; padding-right: 5px;">
                            <input name="no_faktur2" id="no_faktur2" class="easyui-textbox khusus" labelPosition="top" tipPosition="bottom"
                                   label="Trx. No1:" style="width:100%">
                        </div>
                        <div style="float:left; width: 35%; padding-right: 5px;">
                            <input name="status" id="status" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label="Status:" style="width:100%">
                        </div>
                        <div style="float:right; width:10%;">
                            <input name="qty_print" id="qty_print" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   required="true" readonly="true" label=" " style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px; display: none">
                        <input name="id" id="id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="ID:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px; display: none">
                        <input name="reason" id="reason" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Reason:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px;">
                        <input name="seri_pajak" id="seri_pajak" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Seri Pajak:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px; display:block" id="vseri">
                        <input name="seri_pajak_formatted" id="seri_pajak_formatted" readonly="true" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Seri Pajak:" style="width:100%">
                    </div>
                    <div style="margin-bottom:1px">
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="doc_date" id="doc_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                                   required="true" label="Trx. Date:" style="width:100%">
                        </div>
                        <div style="float:right; width: 50%; padding-right: 5px;">
                            <input name="faktur_date" id="faktur_date" class="easyui-datebox" labelPosition="top" tipPosition="bottom"
                                   required="true" label="Faktur Date:" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px; display:none">
                        <div style="margin-bottom:1px; display: none">
                            <input name="regency_id" id="regency_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="" style="width:100%">
                        </div>
                        <div style="float:left; width: 50%; padding-right: 5px;">
                            <input name="regency_name" id="regency_name" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Kabupaten:" style="width:100%">
                        </div>
                        <div style="margin-bottom:1px; display: none">
                            <input name="provinsi_id" id="provinsi_id" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="" style="width:100%">
                        </div>
                        <div style="float:right; width: 50%; padding-left: 5px;">
                            <input name="provinsi_name" id="provinsi_name" class="easyui-combogrid" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Provinsi:"style="width:100%">
                        </div>
                    </div> 
                    <div style="margin-bottom:1px">
                        <div style="float:left; width: 85%; padding-right: 5px;">
                            <input name="customer_code" id="customer_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Customer:" style="width:100%">
                        </div>
                        <div style="float:right; width: 15%; padding-right: 5px;">
                            <input name="beda_fp" id="beda_fp" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="Beda FP" readonly="true" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px">
                        <div style="float:left; width: 85%; padding-right: 5px;">
                            <input name="customer_name" id="customer_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" disabled="true" label="" style="width:100%">
                        </div>
                        <div style="float:right; width: 15%; padding-right: 5px;">
                            <input name="pkp" id="pkp" readonly="true" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   label="" style="width:100%">
                        </div>
                    </div>
                    <div style="margin-bottom:1px;">
                        <input readonly="true" name="sales" id="sales" class="easyui-textbox" labelPosition="top" tipPosition="bottom" label="Salesman:" style="width:100%">
                    </div>
                </div>
                <div style="width: 40%; padding: 10px;">
                    <!-- <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                        <div style=" padding-right: 10px; width: 30%">
                            <input readonly="true" name="disc1_persen" id="disc1_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 1:" style="width:100%">
                        </div>
                        <div style="width: 30%">
                            <input readonly="true" name="disc2_persen" id="disc2_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" label="Disc 2:" style="width:100%">
                        </div>
                        <div style=" padding-right: 10px; width: 30%">
                            <input readonly="true" name="disc3_persen" id="disc3_persen" class="easyui-numberbox" data-options="min:0, precision:2, formatter:formatnumberbox" labelPosition="top" tipPosition="bottom" required="false" label="Disc 3:" style="width:100%">
                        </div>
                    </div> -->
                    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                        <div style=" padding-right: 10px; width: 50%">
                            <input name="qty_item" id="qty_item" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="#ITEM:" style="width:100%">
                        </div>
                        <div style=" padding-right: 10px; width: 50%">
                            <input name="qty_order" id="qty_order" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="QTY SALES:" style="width:100%">
                        </div>
<!--                        <div style=" padding-right: 10px; width: 25%">-->
<!--                            <input name="qty_deliver" id="qty_deliver" class="easyui-textbox" labelPosition="top" tipPosition="bottom"-->
<!--                                   readonly="true" label="QTY DO:" style="width:100%">-->
<!--                        </div>-->
<!--                        <div style=" padding-right: 10px; width: 25%">-->
<!--                            <input name="service_level" id="service_level" class="easyui-textbox" labelPosition="top" tipPosition="bottom"-->
<!--                                   readonly="true" label="SL:" style="width:100%">-->
<!--                        </div>-->
                    </div>
					<div style="margin-bottom:1px">
                       <!--  <div style="float:left; width: 50%; padding-right: 5px;">
							<input readonly="true" name="store_code" id="store_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Sales Toko:" style="width:100%">
                        </div> -->
                        <!-- <div style="float:right; width: 50%; padding-left: 5px;">
                            <input readonly="true" name="location_code" id="location_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" required="true" label="Gudang:" style="width:100%">
                        </div> -->
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="remark" id="remark" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               required="true" label="Keterangan:" style="width:100%; height: 100px;">
                    </div>
                    <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                        <div style=" padding-right: 10px; width: 50%">
                            <input name="verifikasi_finance" id="verifikasi_finance" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                                   readonly="true" label="Finance:" style="width:100%;">
                        </div>
                        <div style="width: 50%">
                            <input name="jenis_faktur" id="jenis_faktur" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Jenis Faktur:" style="width:100%;">
                        </div>

                    </div>
                </div>
                <div style="width: 20%; padding: 10px;">

                    <div style="margin-bottom:1px">
                        <input name="gross_sales" id="gross_sales" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Subtotal:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="total_discount" id="total_discount" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Discount:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="sales_before_tax" id="sales_before_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="DPP:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="total_ppn" id="total_ppn" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="PPN:" style="width:100%; text-align: right;">
                    </div>
                    <div style="margin-bottom:1px">
                        <input name="sales_after_tax" id="sales_after_tax" class="easyui-textbox" labelPosition="top" tipPosition="bottom"
                               readonly="true" label="Total:" style="width:100%; text-align: right;">
                    </div>
                </div>
                <span class="icon-transparent" style="display:inline-block;width:16px;height:16px;vertical-align:top"></span>
            </div>
            <div data-options="region:'west'" style="width:100%;">
                <table id="dg" class="easyui-edatagrid" style="width:100%;height: 300px">
                </table>
            </div>
        </form>
    </div>
</div>    
            <div id="w" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:80%;height:300px;padding:10px;"> 
                <form action="" name="frm2" class="frm2" method="POST">
                   <div style="margin-bottom:20px"> 
                        <input id="dd"  class="easyui-datebox" label="Start Date:" data-options="labelPosition:'top',onSelect:onSelect"  labelPosition="top" style="width:50%;">
                    </div>
                    <div style="margin-bottom:20px">
                        <input id="tdd" class="easyui-datebox" label="End Date:" labelPosition="top" style="width:50%;">
                    </div> 
                    <div style="margin-bottom:20px"> 
                        <a href="javascript:void(0)" id="searchdate" class="easyui-linkbutton save" iconCls="icon-search"  style="width:90px; height: 20px;">Search</a>
                    </div>  
                    <div style="margin-bottom:20px"> 
                        <span id="l"></span> <br>
                        <a href="javascript:void(0)" id="postdetail" class="easyui-linkbutton postdetail" iconCls="icon-posting" style="width:90px; height: 20px;">Post</a>
                    </div>  
                 </form> 
            </div> 
<script> 
    function Opendialog(){
            $('#w').dialog('open').dialog('center').dialog('setTitle','Cari Tanggal Transaksi Daily'); 
        }
    function onSelect(date){ 
 
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            var fulldate=y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
            getdatefrom(fulldate); 
         }
         function getdatefrom(fulldate){

            //console.log(fulldate);
             $('#tdd').datebox().datebox('calendar').calendar({
                validator: function(date){

                    $("#postdetail").hide();
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate(); 
                    var fulldateto=y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);  
                    return fulldateto>=fulldate;
                }
            });
         }
  
  
          $("#postdetail").hide();
          $(".save").click(function(){
            var fromdate = $('#dd').datebox('getValue');
            var todate = $('#tdd').datebox('getValue');  
            var customer_code = $('#customer_code').combogrid('getValue'); 
                $.ajax({
                  type: 'POST',
                  dataType:"json",
                  url:base_url+"Rekapshowroom/getDate", 
                   data: {
                       customer_code:customer_code,
                       from:fromdate,
                       to:todate
                   },
                  success: function(result) {
                    console.log(result); 
                    $("#l").text(result.total+" Data Dok. Sales Showroom");
                    if(result.total >= 1){ 
                        $("#postdetail").show();
                    } else{ 
                        $("#postdetail").hide();
                    }  
                  }
                });
          }); 
          $(".postdetail").click(function(){
            var fromdate = $('#dd').datebox('getValue');
            var todate = $('#tdd').datebox('getValue'); 
            var customer_code = $('#customer_code').combogrid('getValue');  
                $.ajax({
                  type: 'POST',
                  dataType:"json",
                  url:base_url+"Rekapshowroom/postdaily", 
                   data: {
                       docno:docno,
                       customer_code:customer_code,
                       from:fromdate,
                       to:todate
                   },
                  success: function(result) { 
                    if(result.status==1){
                      alert(result.msg);
                        location.reload(); 
                    }else{
                      alert(result.msg);
                        location.reload(); 
                    }
                  }
                });
          }); 
    </script>