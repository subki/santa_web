<script type="text/javascript">
    var base_url="<?php echo base_url();?>"; 
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/soonline_grid.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/redirect.js"></script> 
    <div id="p2" data-options="region:'north', height:80" style="width: 100%;">
        <div style="margin-bottom:1px;display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;"> 
            <div style="width: 50%; padding: 10px;" class="border-kotak">
                <div style="margin:5px;">
                    <div style="float:left; width: 20%; padding-right: 5px;">
                         <select name="jenis_status" id="jenis_status" class="easyui-combobox" labelPosition="top" tipPosition="bottom" required="true" label="Jenis Status:" style="width:100%;">
                            <option value="ALL">ALL</option> 
                            <option value="OPEN">OPEN</option> 
                            <option value="ON ORDER">ON ORDER</option>
                            <option value="CLOSED">CLOSED</option>
                            <option value="BATAL">BATAL</option> 
                        </select>
                    </div>
                    <div style="float:left; width:35%;">
                        <input name="periode" id="periode" value="<?php echo $datenow;?>" class="easyui-datebox" labelPosition="top" tipPosition="right" required="true" label="Tanggal:" style="width:100%;"> 
                    </div>
                     <div style="float:left; width: 35%; padding-right: 5px;">
                         <input name="customer_code" id="customer_code" class="easyui-combogrid" labelPosition="top" tipPosition="bottom" label="Customer:" label="" style="width:100%">  
                         <input type="hidden" name="customer_codeid" id="customer_codeid">
                         <input name="customer_name" id="customer_name" class="easyui-textbox" labelPosition="top" tipPosition="bottom" readonly="true" label="" style="width:100%">
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
    function addonline(){
        var fromdate = $('#periode').datebox('getValue');
        var cust = $('#customer_codeid').val();
             $.redirect(base_url+"Online/form/add", {'tglnow': fromdate,'cust': cust}); 
    }  
   

    function populateCustomer() {
    $('#customer_code').combogrid({
                idField: 'customer_code',
                textField:'customer_code',
                url:base_url+"Online/load_gridcust",
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
                    data.data.unshift({customer_code:'~',customer_name:'All Customer'});
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

                $('#customer_code').combogrid('setValue',rw.customer_code)
                $('#customer_name').textbox('setValue',rw.customer_name) 
                $('#customer_codeid').val(rw.customer_code) 
                if(status!==""){
                    $('#dg').datagrid({url:base_url+"Online/load_grid/"+status+"/"+rw.customer_code+"/"+prd});
                   
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
                 {field:'customer_code', title:'Kode', width:200},
                 {field:'customer_name', title:'Customer', width:300},
            ]]
        }); 
        var gr =  $('#customer_code').combogrid('grid') 
        gr.datagrid('destroyFilter');
                // / $('#customer_name').textbox('setValue','')  
        gr.datagrid('enableFilter'); 
        //gr.datagrid('doFilter');
        }
</script>