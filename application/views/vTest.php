<script type="text/javascript">
    var base_url="<?php echo base_url();?>";
</script>
<div class="easyui-layout" style="width:100%;height:100%">
    <div class="easyui-layout" style="width:100%;height:100%">
        <div id="p" data-options="region:'west'" style="width:60%;">
            <form id="formupload" style="margin-bottom:-0px;">
                <button onclick="onClick()">Print</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
<script type="text/javascript">
    var printer = new Recta('APPKEY', '1811')

    function onClick () {
        printer.open().then(function () {
            printer.align('center')
                .text('Hello World !!')
                .bold(true)
                .text('This is bold text')
                .bold(false)
                .underline(true)
                .text('This is underline text')
                .underline(false)
                .barcode('UPC-A', '123456789012')
                .cut()
                .print()
        })
    }
</script>