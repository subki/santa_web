<html>
<body>

<?php
$sesi = $this->session->userdata();
?>
<header>
    <div class="report-header">
        <div class="content text-left">
            <table width="100%">
                <tbody>
                <tr>
                    <td width="100%" colspan="2" style="vertical-align: top; margin-left: 10px;">
                        <div CLASS="title-header text-center">PACKING LIST</div>
                    </td>
                </tr>
                <tr>
                    <td width="60%" style="vertical-align: top; margin-right: 10px;">
                        <div class="content text-left">
                            <table width="100%">
                                <tbody>
                                <tr>
                                    <td width="34%">Packing List</td>
                                    <td width="1%">:</td>
                                    <td width="65%"><?php echo $header->docno; ?></td>
                                </tr>
                                <tr>
                                    <td width="34%">Sales Order</td>
                                    <td width="1%">:</td>
                                    <td width="65%"><?php echo $header->so_number; ?></td>
                                </tr>
                                <tr>
                                    <td width="34%">Tanggal</td>
                                    <td width="1%">:</td>
                                    <td width="65%"><?php echo strtoupper($header->ak_doc_date); ?></td>
                                </tr>
                                <tr>
                                    <td width="34%">Salesman</td>
                                    <td width="1%">:</td>
                                    <td width="65%"><?php echo $header->salesman_id; ?></td>
                                </tr>
                                <tr>
                                    <td width="34%">Keterangan</td>
                                    <td width="1%">:</td>
                                    <td width="65%"><?php echo $header->remark; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td width="40%" style="vertical-align: top; margin-left: 10px;">
                        <div class="content title-header text-left">
                            Kepada Yth, <br />
                        <?php echo $header->customer_code." - ".$header->customer_name ?><br />
                        <?php echo $header->address1 ?><br />
                        <?php echo "Phone : ".$header->phone1 ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</header>
<table>
    <!--    <div class="header-space">&nbsp;</div>-->
    <tbody>
    <div class="content">
        <table style="width: 100%;">
            <thead>
            <tr><th colspan="14"><div class="border-bottom-double"/></th></tr>
            <tr>
                <th class="title-header text-center content" width="4%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">QTY</th>
                <th class="title-header text-center content" width="6%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">UOM</th>
                <th class="title-header text-center content" width="18%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Product Code</th>
                <th class="title-header text-center content" width="22%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Nama Barang</th>
                <th class="title-header text-center content" width="4%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Urut</th>
                <th class="title-header text-center content" width="12%" colspan="3" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Qty Fisik</th>
                <th class="title-header text-center content" width="36%" colspan="6" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">No. Colie</th>
            </tr>
            <tr><th colspan="14"><div class="border-bottom-double"/></th></tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            $qty = 0;
            foreach ($detail as $row) { ?>
                <tr>
                    <td class="text-right content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '5px' ?>; padding-bottom: 5px"><?php echo number_format($row->qty_order, 0, '.', ','); ?></td>
                    <td class="text-left content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->uom_id ?></td>
                    <td class="text-left content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->product_code ?></td>
                    <td class="text-left content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->nmbar ?></td>
                    <td class="text-left content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i ?></td>
                    <td class="text-left content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px">(</td>
                    <td class="text-center content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"></td>
                    <td class="text-right content" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px">)</td>

                    <td class="text-left content" width="6%" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i%2==0?"(":""; ?></td>
                    <td class="text-center content" width="6%" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i%2==0?"|":""; ?></td>
                    <td class="text-right content" width="6%" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i%2==0?")":""; ?></td>

                    <td class="text-left content" width="6%" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i%2==1?"(":""; ?></td>
                    <td class="text-center content" width="6%" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i%2==1?"|":""; ?></td>
                    <td class="text-right content" width="6%" style="padding-left: 10px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i%2==1?")":""; ?></td>

                </tr>
                <?php $i++; $qty+=$row->qty_order; } ?>
            <tr><td colspan="14" ><div class="border-bottom-double"/> </td></tr>
            <tr>
                <td class="title-header text-center content" width="10%" colspan="2" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">TOTAL QTY</td>
                <td class="text-left content" width="10%" colspan="12" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"><?php echo number_format($qty, 0, '.', ',') ?></td>
            </tr>
            <tr><td colspan="14"><div class="border-bottom-double"/> </td></tr>
            </tbody>
        </table>
    </div>
    </tbody>
</table>
<br />
<table>
    <tbody>
    <div class="content">
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td width="33%" style="vertical-align: top; margin-left: 10px;">
                    <div class="text-left content">
                        <table style="width: 100%;">
                            <tr><td style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px" width="19%">Dikerjakan</td>
                                <td style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px" width="1%">:</td><td width="80%"><div class="border-bottom-dashed"/></td></tr>
                            <tr><td style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px" width="19%">Dikontrol</td>
                                <td style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px" width="1%">:</td><td width="80%"><div class="border-bottom-dashed"/></td></tr>
                            <tr><td style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px" width="19%">Diperiksa</td>
                                <td style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px" width="1%">:</td><td width="80%"><div class="border-bottom-dashed"/></td></tr>
                        </table>
                    </div>
                </td>
                <td width="34%" style="vertical-align: top; margin-left: 10px;">
                    <div class="text-left content">
                        <table style="width: 100%;">
                            <tr><td width="19%">Dikirim via</td><td width="1%">:</td><td width="80%"><div class="border-bottom-dashed"/></td></tr>
                            <tr><td width="19%">Dipacking</td><td width="1%">:</td><td width="08%"><div class="border-bottom-dashed"/></td></tr>
                        </table>
                    </div>
                </td>
                <td width="33%" style="vertical-align: top; margin-left: 10px;">
                    <div class="text-left content">
                        <table style="width: 100%;">
                            <tr><td width="19%">Diangkut</td><td width="1%">:</td><td width="80%"><div class="border-bottom-dashed"/></td></tr>
                            <tr><td width="19%">Total Berat</td><td width="1%">:</td><td width=8085%"><div class="border-bottom-dashed"/></td></tr>
                            <tr><td width="19%">Jumlah</td><td width="1%">:</td><td width="80%"><div class="border-bottom-dashed text-right">Colie</div></td></tr>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    </tbody>
</table>
</body>
</html>


<style rel="stylesheet/scss" lang="scss">
    .header, .header-space,
    .footer, .footer-space {
        height: 130px;
    }

    .footer {
        position: fixed;
        bottom: 0;
    }

    table {
        border-spacing: 2px;
    }
    /*table thead {*/
    /*border-bottom:3px double black;*/
    /*border-top:3px double black;*/
    /*}*/
    table tfoot {
        border-top:3px double black;
    }

    .report-header{
        margin-bottom: 10px;
    }
    .title-header{
        text-align: center;
        font-size: 14px;
        font-weight: bold;
    }

    .report-title p{
        text-align: center;
        margin: 0 0 2px 0;
        font-size: 14px;
        font-weight: bold;
    }

    .text-underline{
        text-decoration: underline;
    }
    .border-top-double{
        border-top: 3px double black;
        width:100%;
    }
    .border-bottom-double{
        border-bottom: 3px double black;
        width:100%;
    }
    .border-bottom-dashed{
        border-bottom: 1px dashed black;
        border-width: thin;
    }
    .border-top{
        border-top: 1px solid black;
    }
    .border-bottom{
        border-bottom: 1px solid black;
    }
    .text-center{
        text-align: center;
    }
    .text-left{
        text-align: left;
    }
    .text-right{
        text-align: right;
    }
    .font-bold{
        font-weight: bold;
    }

    .content{
        font-size: 12px;
    }

    /** Define the margins of your page **/
    @page {
        margin: 100px 5px;
        size: 21.6cm 27.9cm; size : portrait; /** Ukuran kertas A4 **/
    }

    body {
        margin-top: 1cm;
        margin-bottom: 0.1cm;
        margin-left: 1cm;
        margin-right: 1cm;
    }

    header {
        position: fixed;
        top: -90px;
        left: 1cm;
        right: 1cm;
        height: 100px;
    }

    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 0.5cm;
    }

</style>