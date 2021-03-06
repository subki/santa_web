<html>
<head>
    <title>Receiving DO Import</title>
</head>
<body>
<header>
    <div class="report-header">
        <div class="content text-left">
            <p class="title-header text-center border-bottom-double">RECEIVING DO PRODUKSI IMPORT</p>
            <table width="100%">
                <tbody>
                <tr>
                    <td width="19%">Receiving DO#</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $header->docno; ?></td>
                    <td width="19%">Base On SPK#</td>
                    <td width="1%">:</td>
                    <td width="20%"><?php echo $header->docno; ?></td>
                </tr>
                <tr>
                    <td width="19%">Received Date</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $header->receive_date==""?"":date("d/M/Y",strtotime($header->receive_date)); ?></td>
                    <td width="19%">Date</td>
                    <td width="1%">:</td>
                    <td width="20%"><?php echo date("d/M/Y",strtotime($header->doc_date)); ?></td>
                </tr>
                <tr>
                    <td width="19%">Remark</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $header->keterangan; ?></td>
                    <td width="19%">Status</td>
                    <td width="1%">:</td>
                    <td width="20%"><?php echo $header->status; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</header>
<table>
    <tbody>
    <div class="content">
        <table style="width: 100%;">
            <thead>
            <tr><th colspan="5"><div class="border-bottom-double"/></th></tr>
            <tr>
                <th class="title-header text-center" width="3%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">No.</th>
                <th class="title-header text-center" width="15%" colspan="2" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">QTY</th>
                <th class="title-header text-center" width="27%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Article</th>
                <th class="title-header text-center" width="55%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Keterangan</th>
            </tr>
            <tr><th colspan="5"><div class="border-bottom-double"/></th></tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            $qty = 0;
            foreach ($detail as $row) { ?>
                <tr>
                    <td class="text-left content"><?php echo $i ?></td>
                    <td class="text-right content"><?php echo number_format($row->qty, 2, '.', ','); ?></td>
                    <td class="text-left content"><?php echo $row->uom_id ?></td>
                    <td class="text-left content"><?php echo $row->product_code ?></td>
                    <td class="text-left content"><?php echo $row->nmbar ?></td>
                </tr>
                <?php $i++; $qty+=$row->qty;} ?>
            </tbody>
            <tfoot>
            <tr>
                <td class="title-header text-center content" width="10%" colspan="3" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Total Item#</td>
                <td class="text-left content" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"><?php echo ": ". $i ."  <==> ". number_format($qty, 2, '.', ',') ?></td>
                <td class="title-header text-center content" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>
            </tr>
            </tfoot>
        </table>
    </div>
    </tbody>
</table>
<br />
<table>
    <tbody>
    <div class="content">
        <table style="width: 100%;">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td class="text-center content" height="120px" width="33%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Penerima</td>
                <td class="text-center content" height="120px" width="33%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Gudang</td>
                <td class="text-center content" height="120px" width="33%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Hormat Kami,</td>
            </tr>
            <tr>
                <td class="text-center content" width="33%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(_____________________)</td>
                <td class="text-center content" width="33%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(_____________________)</td>
                <td class="text-center content" width="33%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(_____________________)</td>
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
        font-size: 10pt;
        font-weight: bold;
    }

    .report-title p{
        text-align: center;
        margin: 0 0 2px 0;
        font-size: 10pt;
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
        font-size: 10pt;
    }

    /** Define the margins of your page **/
    @page {
        margin-top: 1.4cm;
        margin-bottom: 0.3cm;
        margin-left: 0.5cm;
        margin-right: 0.5cm;
        size: 21.6cm 27.9cm; size : portrait; /** Ukuran kertas A4 **/
        font-family: Courier, sans-serif;
    }

    @font-face {
        font-family: 'Courier';
        font-weight: normal;
        font-style: normal;
        font-variant: normal;
        src: url("<?php echo base_url(); ?>assets/font/courier.ttf") format("truetype");
    }

    body {
        margin-top: 1.4cm;
        margin-bottom: 0.3cm;
        margin-left: 0.5cm;
        margin-right: 0.5cm;
        font-family: Courier, sans-serif;
    }

    header {
        position: fixed;
        top: -1.4cm;
        left: 0.5cm;
        right: 0.5cm;
        height: 140px;
    }

    footer {
        position: fixed;
        bottom: -10px;
        left: 1cm;
        right: 1cm;
        height: 200px;
    }

</style>