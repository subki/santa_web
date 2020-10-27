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
                    <td width="100%" style="vertical-align: top; margin-right: 10px;">
                        <?php echo $sesi['nama pkp'] ?><br />
                        <?php echo $sesi['alamat pkp'] ?><br />
                        <?php echo $sesi['telepon pkp'] ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <table width="100%">
                <tbody>
                <tr>
                    <td width="50%">
                        <div>
                            <table>
                                <tbody>
                                <tr>
                                    <td width="38%">FAKTUR No</td>
                                    <td width="2%">:</td>
                                    <td width="60%"><?php echo $header->no_faktur2."  /  ".$header->status; ?></td>
                                </tr>
                                <tr>
                                    <td width="38%">TANGGAL</td>
                                    <td width="2%">:</td>
                                    <td width="60%"><?php echo $header->ak_faktur_date; ?></td>
                                </tr>
                                <tr>
                                    <td width="38%">SALESMAN</td>
                                    <td width="2%">:</td>
                                    <td width="60%"><?php echo $header->salesman_name; ?></td>
                                </tr>
                                <tr>
                                    <td width="38%">KETERANGAN</td>
                                    <td width="2%">:</td>
                                    <td width="60%"><?php echo $header->remark; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td width="50%">
                        Kepada Yth, <br />
                        <?php echo $header->customer_name?> <br />
                        <?php echo $header->address1?> <br />
                        <?php echo $header->address2?> <br />
                        <?php echo $header->regency_name."      Phone : ".$header->phone1?>
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
            <tr><th colspan="12"><div class="border-bottom-double"/></th></tr>
            <tr>
                <th class="title-header text-center content2" width="2%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">No.</th>
                <th class="title-header text-center content2" width="13%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Article</th>
                <th class="title-header text-center content2" width="2%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Size</th>
                <th class="title-header text-center content2" width="18%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Nama Article</th>
                <th class="title-header text-center content2" width="5%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">QTY Uom</th>
                <th class="title-header text-center content2" width="7%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Unit Price</th>
                <th class="title-header text-center content2" width="6%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Disc1<br/>%</th>
                <th class="title-header text-center content2" width="6%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Disc2<br/>%</th>
                <th class="title-header text-center content2" width="6%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Disc3<br/>%</th>
                <th class="title-header text-center content2" width="6%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Disc <br/>Amount</th>
                <th class="title-header text-center content2" width="6%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Net Price</th>
                <th class="title-header text-center content2" width="9%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">Total</th>
            </tr>
            <tr><th colspan="12"><div class="border-bottom-double"/></th></tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            $qty = 0;
            foreach ($detail as $row) { ?>
                <tr>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i ?></td>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->product_code ?></td>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->size_code ?></td>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->nmbar ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->qty_on_sales, 0, '.', ',').$row->uom_id; ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->unit_price, 0, '.', ','); ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->disc1_persen, 2, '.', ','); ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->disc2_persen, 2, '.', ','); ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->disc3_persen, 2, '.', ','); ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->disc_total, 0, '.', ','); ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->netto_after_tax, 0, '.', ','); ?></td>
                    <td class="text-center content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->netto_after_tax*$row->qty_on_sales, 0, '.', ','); ?></td>
                </tr>
                <?php $i++; $qty+=$row->qty; } ?>
            <tr><th colspan="12"><div class="border-bottom-double"/></th></tr>
            </tbody>
        </table>

    </div>
    </tbody>
</table>
<br />
<table>
    <tbody>
    <!--div class="content">
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td class="text-center content" height="120px" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Satpam</td>
                <td class="text-center content" height="120px" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Penerima</td>
                <td class="text-center content" height="120px" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Gudang</td>
                <td class="text-center content" height="120px" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Hormat Kami,</td>
            </tr>
            <tr>
                <td class="text-center content" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(__________________)</td>
                <td class="text-center content" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(__________________)</td>
                <td class="text-center content" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(__________________)</td>
                <td class="text-center content" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(__________________)</td>
            </tr>
            </tbody>
        </table>
    </div-->
    </tbody>
</table>
</body>
</html>

<style rel="stylesheet/scss" lang="scss">
    table {
        border-spacing: 2px;
    }
    table tfoot {
        border-top:3px double black;
    }

    .report-header{
        margin-bottom: 10px;
    }
    .title-header{
        text-align: center;
        font-size: 10pt;
        font-weight: bold;
    }

    .report-title p{
        text-align: center;
        margin: 0 0 2px 0;
        font-size: 10pt;
        font-weight: bold;
    }
    .border-bottom-double{
        border-bottom: 3px double black;
        width:100%;
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
    .content{
        font-size: 9pt;
    }
    .content2{
        font-size: 8pt;
    }

    /** Define the margins of your page **/
    @page {
        margin-top: 2.54cm;
        margin-bottom: 0.4cm;
        margin-left: 0.1cm;
        margin-right: 0.1cm;
        size: 21.6cm 27.9cm; size : portrait; /** Ukuran kertas A4 **/
        font-family: Courier, sans-serif;
    }

    body {
        margin-top: 3cm;
        margin-bottom: 0.4cm;
        margin-left: 0.1cm;
        margin-right: 0.1cm;
        font-family: Courier, sans-serif;
    }

    header {
        position: fixed;
        top: -20px;
        left: 0.5cm;
        right: 0.5cm;
        height: 4cm;
    }

    footer {
        position: fixed;
        bottom: -10px;
        left: 1cm;
        right: 1cm;
        height: 200px;
    }

</style>