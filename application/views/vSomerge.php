<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var store_code = "<?php echo $this->session->userdata('store_code'); ?>"; 
    var location_code = "<?php echo $this->session->userdata('lokasi_sales'); ?>"; 
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/spm_grid.js"></script>

<div class="easyui-layout" style="width:100%;height:100%">
    <div id="p" data-options="region:'west'" style="width:100%;">
        <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
        </table>
    </div>
</div>
<!--<div id="toolbar">-->
<!--    <a href="--><?php //echo base_url(); ?><!--Salesorder/form/add" class="easyui-linkbutton" iconCls="icon-add" plain="true">New</a>-->
<!--    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="editData()" iconCls="icon-edit" plain="true">Edit</a>-->
<!--</div>-->
<div id="wprint" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:80%;height:500px;padding:10px;"> 
                <form action="" name="frm2" class="frm2" method="POST">
                   <div style="margin-bottom:20px"> 
                        <input id="opn_no"  class="easyui-textbox" label="No. Merge Opname/Ref.No:" data-options="labelPosition:'top',onSelect:onSelect"  labelPosition="top" style="width:50%;">
                    </div> 
                    <div style="margin-bottom:20px"> 
                        <a href="javascript:void(0)" id="searchdate"  class="easyui-linkbutton printOP" iconCls="icon-print"  style="width:90px; height: 20px;">Print</a>
                        <a href="javascript:void(0)" id="searchdate"  class="easyui-linkbutton printOPexcel" iconCls="icon-print"  style="width:150px; height: 20px;">Print Excel</a>
                    </div>   
                 </form> 
            </div> 
<div id="w" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:60%;height:300px;padding:10px;"> 
  <form action="" name="frm2" class="frm2" method="POST">
     <div style="margin-bottom:20px"> 
          <input id="dd"  class="easyui-datebox" label="Search Date:" data-options="labelPosition:'top'"  labelPosition="top" style="width:20%;">
      </div> 
      <div style="width: 100%; " class="border-kotak">
          <div style="margin-bottom:1px"> 
              <div style="margin-bottom:1px">
                  <div style="float:left; width: 25%;">
                      <div style="margin-bottom:1px;">  
                         <input name="on_loc" id="on_loc" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Location:" label="" style="width:100%"> 
                      </div> 
                  </div>
                  <div style="float:left; width: 50%;">
                      <div style="margin-bottom:1px;">  
                          <input name="on_locname" id="on_locname" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" label="Location Name:" style="width:100%"> 
                      </div> 
                  </div>
                  <div style="float:left; width: 25%;">
                      <div style="margin-bottom:1px;">  
                            <input name="store_code" id="store_code" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" label="Store Code:" style="width:100%">
                      </div> 
                  </div>  
              </div>
          </div>  
      </div>   
      <div style="margin-bottom:20px"> 
          <a href="javascript:void(0)" id="searchdate" class="easyui-linkbutton save" iconCls="icon-search"  style="width:90px; height: 20px;">Search</a>
      </div>  
      <div style="margin-bottom:20px"> 
          <span id="l"></span> <br>
          <a href="javascript:void(0)" id="postdetail" class="easyui-linkbutton postdetail" iconCls="icon-posting" style="width:290px; height: 20px;">Merge Opname</a>
      </div>  
   </form> 
</div> 
<div id="wadj" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:80%;height:300px;padding:10px;"> 
                <form action="" name="frm2" class="frm2" method="POST">
                   <div style="margin-bottom:20px"> 
                        <input id="opn_noadj"  class="easyui-textbox" label="No. Merge Opname/Ref.No:" data-options="labelPosition:'top',onSelect:onSelect"  labelPosition="top" style="width:50%;">
                    </div> 
                    <div style="margin-bottom:20px"> 
                        <a href="javascript:void(0)" id="btn_simpan"  class="easyui-linkbutton btn_simpan" onclick="addOpn()" iconCls="icon-search"  style="width:250px; height: 20px; ">Find Opname</a> </a> 
                    </div>   
                 </form> 
            </div> 

<div id="modal_detailOpname" class="easyui-dialog" data-options="iconCls:'icon-save',closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" style="width:80%;height:500px;padding:20px;"> 
   <div id="toolbar">  
   <a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitadjopn()" iconCls="icon-save" plain="true">Simpan Adjustment</a>
   </div>  
    <form class="form-horizontal" id="form_opname"> 
        <table id="tt_opn" title="List Opname" class="easyui-datagrid" style="width:100%;height:400px">
        </table>
    </form> 
</div>  

<script type="text/javascript">
	    function printOPN(){
            $('#wprint').dialog('open').dialog('center').dialog('setTitle','Merge Opname'); 
        } function Opendialog(){
         $("#postdetail").hide();
            $('#w').dialog('open').dialog('center').dialog('setTitle','Merge Opname'); 
        } function adjOPN(){
            $('#wadj').dialog('open').dialog('center').dialog('setTitle','Merge Opname'); 
        } function onSelect(date){ 
 
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

                    $("#l").text("");
                    $("#postdetail").hide();
                    var y = date.getFullYear();
                    var m = date.getMonth()+1;
                    var d = date.getDate(); 
                    var fulldateto=y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);  
                    return fulldateto>=fulldate;
                }
            });
         }          
          $(".save").click(function(){
            var fromdate = $('#dd').datebox('getValue');
            var location_code2 = $('#on_loc').datebox('getValue'); 
            var store_code2 = $('#store_code').textbox('getValue');    
                $.ajax({
                  type: 'POST',
                  dataType:"json",
                  url:base_url+"Somerge/getDate", 
                   data: {
                       store_code:store_code2,
                       location_code:location_code2,
                       from:fromdate,
                       to:fromdate
                   },
                  success: function(result) {
                    console.log(result); 
                    if(result.total >= 1){ 
                        $("#l").text(result.total+" Data Dok. Opname ");
                        $("#postdetail").show();
                    } else{  
                        $("#l").text(result.msg);
                        $("#postdetail").hide();
                    }  
                  }
                });
          }); 
          $(".postdetail").click(function(){
            var fromdate = $('#dd').datebox('getValue');   
            var location_code2 = $('#on_loc').datebox('getValue');  
            var store_code2 = $('#store_code').textbox('getValue');  
                $.ajax({
                  type: 'POST',
                  dataType:"json",
                  url:base_url+"Somerge/postdaily", 
                   data: {
                       store_code:store_code2,
                       location_code:location_code2,
                       from:fromdate,
                       to:fromdate
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
       $(".printOP").click(function(){
            var docno = $('#opn_no').textbox('getValue'); 
                  window.open(base_url+'Somerge/print_opfull/'+docno, '_blank');
          }); 
       $(".printOPexcel").click(function(){
            var docno = $('#opn_no').textbox('getValue'); 
                  window.open(base_url+'Somerge/print_opfullexcel/'+docno, '_blank');
          }); 

</script>