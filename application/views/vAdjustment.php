<style>
    .datepicker {
        z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
    var role = "<?php echo $this->session->userdata('role'); ?>";
</script>

<script src="<?php echo base_url(); ?>assets/js/adjustment.js"></script>
<section class="content-header">
</section>
<section class="content">
    <div class="box box-danger">
        <div class="box-body">
            <table id="dg" title="List Data" class="easyui-datagrid" style="width:100%;height:400px">
            </table>
            <div id="toolbar">
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addnew()">New</a>
<!--                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-upload" plain="true" onclick="upload()">Upload</a>-->
            </div>
        </div>
    </div>
</section>


<!-- modal editing form -->
<div id="modal_edit" class="modal fade bs-modal-md" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Data</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_editing">
                    <div class="form-group" id="divCode">
                        <label class="col-md-3 control-label">Nomor Trx</label>
                        <div class="row">
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="docno" placeholder="Nomor Trx" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group" id="divCode">
                            <div class="row">
                                <label class="col-md-3 control-label">Outlet Code</label>
                                <div class="col-md-7">
                                    <select class="form-control select2" style="width: 100%;" id="outlet_code" name="outlet_code"></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="divDocDate">
                            <label class="col-md-3 control-label">Doc Date</label>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right datepicker" name="doc_date"
                                               id="doc_date" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="divCode">
                            <label class="col-md-3 control-label">Keterangan</label>
                            <div class="row">
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="remark" placeholder="Keterangan" />
                                </div>
                            </div>
                        </div>
<!--                        <div class="form-group tight-bottom" id="divStatus">-->
<!--                            <label class="col-md-3 control-label">Status</label>-->
<!--                            <div class="row">-->
<!--                                <div class="col-sm-6">-->
<!--                                    <select class="form-control select2" style="width: 100%;" id="status" name="status"></select>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn_simpan" class="btn btn-primary btn-flat" onclick="submit()">
                    <i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Simpan
                </button>
                <img id="img-load" style="display:none" src="<?php echo base_url(); ?>assets/images/fb-loader.gif" />
            </div>
        </div>
    </div>
</div>

<!-- modal editing form -->
<div id="modal_edit_detail" class="modal fade bs-modal-md" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Data</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_editing_detail">
                    <div class="box-body">
                        <div class="form-group" id="divCode">
                            <label class="col-md-3 control-label">Nomor Trx</label>
                            <div class="row">
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="docno" placeholder="Nomor Trx" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="divCode">
                            <label class="col-md-3 control-label">Product</label>
                            <div class="col-sm-7">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="sku" id="sku" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" onclick="addnewsku();" class="btn btn-info btn-flat">Find</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="divCode">
                            <label class="col-md-3 control-label">Qty On Hand</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="soh" id="soh" placeholder="Qty On Hand" readonly />
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="divCode">
                            <label class="col-md-3 control-label">Qty Adjustment</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="adjust" id="adjust" placeholder="Qty Adjustment" />
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="divCode">
                            <label class="col-md-3 control-label">Keterangan</label>
                            <div class="row">
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="keterangan" placeholder="Keterangan" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-flat" onclick="clearFormInput2()" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn_simpan" class="btn btn-primary btn-flat" onclick="submit_detail()">
                    <i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Simpan
                </button>
                <img id="img-load" style="display:none" src="<?php echo base_url(); ?>assets/images/fb-loader.gif" />
            </div>
        </div>
    </div>
</div>


<!-- modal editing form -->
<div id="modal_edit_detail_sku" class="modal fade bs-modal-md" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Form Data</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_editing_detail_sku">
                    <input type="text" class="hidden" name="discount_id_det_sku" id="discount_id_det_sku" value="" />
                    <table id="tt_sku" title="List SKU" class="easyui-datagrid" style="width:100%;height:400px">
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
                <img id="img-load" style="display:none" src="<?php echo base_url(); ?>assets/images/fb-loader.gif" />
            </div>
        </div>
    </div>
</div>

