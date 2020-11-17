<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url(); ?>assets/js/util.js"></script>
<script src="<?php echo base_url(); ?>assets/js/soshowroom_grid.js"></script>

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