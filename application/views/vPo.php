<script type="text/javascript">
    var base_url="<?php echo base_url();?>"; 
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/po_grid.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script> 
    <div id="p2" data-options="region:'north', height:80" style="width: 100%;">
        <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;"> 
            <div style="width: 50%; padding: 10px;" class="border-kotak">
                <div style="margin:5px;">
                    <div style="float:left; width: 20%; padding-right: 5px;">
                         <select name="jenis_status" id="jenis_status" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Jenis Status:" style="width:100%;">
                            <option value="ALL">ALL</option> 
                            <option value="Open">OPEN</option> 
                            <option value="On Order">ON ORDER</option>
                            <option value="Closed">CLOSED</option>
                            <option value="Cancel">BATAL</option> 
                        </select>
                    </div>
                    <div style="float:left; width:35%;">
                        <input name="periode" id="periode" value="<?php echo $datenow;?>" class="easyui-datebox" labelPosition="top" tipPosition="right" required="true" label="Tanggal:" style="width:100%;"> 
                    </div>
                     <div style="float:left; width: 35%; padding-right: 5px;">
                         <input name="supplier_code" id="supplier_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Supplier:" label="" style="width:100%">  
                         <input type="hidden" name="supplier_codeid" id="supplier_codeid">
                         <input name="supplier_name" id="supplier_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" label="" style="width:100%">
                    </div>
                    <div style="float:left; width:10%;margin-top: 20px">
                        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="Refresh()" iconCls="icon-reload" plain="true">Refresh</a> 
                    </div>
                </div>  
            </div> 
        </div> 
    </div>
    <div class="easyui-layout" style="width:100%;height:100%">
        <div id="p" data-options="region:'west'" style="width:100%;">
            <table id="dg" title="<?php echo $title; ?>" class="easyui-edatagrid" style="width:100%;height: 100%">
            </table>
        </div>
    </div> 

<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-easyui-1.9.4/datagrid-filter.js"></script>
<script type="text/javascript">
    function addPurchaseorder(){
        var fromdate = $('#periode').datebox('getValue');
        var supp = $('#supplier_codeid').val();
             $.redirect(base_url+"Purchaseorder/form/add", {'tglnow': fromdate,'supp': supp}); 
    }  
   

    function populateSupplier() {
    $('#supplier_code').combogrid({
                idField: 'supplier_code',
                textField:'supplier_code',
                url:base_url+"Purchaseorder/load_gridsupp",
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
                 // console.log(data)
                    data.rows = [];
                    if (data.data) data.rows = data.data; 
                    data.data.unshift({customer_code:'~',customer_name:'All Supplier'});
                    return data;
                },
             onSelect:function (index, rw) {
                var periode = $('#periode').datebox('getValue');
                var status = $('#jenis_status').combogrid('getValue'); 
 
                var date = new Date();

                var d = date.getDate(periode);

                var m = date.getMonth(periode)+1;

                var y = date.getFullYear(periode);

                var prd =   y+""+(m<10?('0'+m):m)+""+(d<10?('0'+d):d); 
  
                $('#supplier_code').combogrid('setValue',rw.supplier_code)
                $('#supplier_name').textbox('setValue',rw.supplier_name) 
                $('#supplier_codeid').val(rw.supplier_code) 
                if(status!==""){
                    $('#dg').datagrid({url:base_url+"Purchaseorder/load_grid/"+status+"/"+rw.supplier_code+"/"+prd});
                   
                    // $('#dg').datagrid({url:base_url+"Online/load_grid/", 
                    //    data: {
                    //        prd:prd,
                    //        status:status 
                    //    }});
                   // $('#dg').datagrid('destroyFilter');
                    $('#dg').datagrid('enableFilter');
                }
                 
            }, 
            columns: [[
                 {field:'supplier_code', title:'Kode', width:200},
                 {field:'supplier_name', title:'Supplier', width:300},
            ]]
        }); 
        var gr =  $('#supplier_code').combogrid('grid') 
        gr.datagrid('destroyFilter');
                // / $('#customer_name').textbox('setValue','')  
        gr.datagrid('enableFilter'); 
        //gr.datagrid('doFilter');
        }
</script>