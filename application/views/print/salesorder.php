<html>
<head>
</head>
<body>
<header>
    <div class="report-header">
        <div class="content text-left">
            <table width="100%">
                <tbody>
                <tr>
                    <td width="19%"></td>
                    <td width="1%"></td>
                    <td width="40%"></td>
                    <td width="40%">Kepada Yth,</td>
                </tr>
                <tr>
                    <td width="19%"></td>
                    <td width="1%"></td>
                    <td width="40%"></td>
                    <td width="40%"><?php echo $so->customer_code." - ".$so->customer_name?></td>
                </tr>
                <tr>
                    <td width="19%">SALES ORDER No.</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->ak_docno." / ".$so->status; ?></td>
                    <td width="40%"></td>
                </tr>
                <tr>
                    <td width="19%">Tgl SO</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->ak_doc_date;?></td>
                    <td width="40%"></td>
                </tr>
                <tr>
                    <td width="19%">SALESMAN</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->salesman_name;?></td>
                    <td width="40%">Phone  :  <?php echo $so->phone1;?></td>
                </tr>
                <tr>
                    <td width="19%">KETERANGAN</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->remark;?></td>
                    <td width="40%" style="text-align: right"><?php echo $so->jumlah_print+1;?></td>
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
            <tr><th colspan="7"><div class="border-bottom-double"/></th></tr>
            <tr>
                <th class="title-header text-left content" width="3%" colspan="3" style="padding-top: 5px; padding-bottom: 5px"></th>
                <th class="title-header text-left content" width="3%" style="padding-top: 5px; padding-bottom: 5px">Quantity</th>
                <th class="title-header text-left content" width="6%" style="padding-top: 5px; padding-bottom: 5px">UOM</th>
                <th class="title-header text-left content" width="6%" style="padding-top: 5px; padding-bottom: 5px">KODE BARANG</th>
                <th class="title-header text-left content" width="16%" style="padding-top: 5px; padding-bottom: 5px">NAMA BARANG</th>
            </tr>
            <tr><th colspan="7"><div class="border-bottom-double"/></th></tr>
            </thead>
            <tbody>
            <?php
            foreach ($det as $row) { ?>
                <tr>
                    <td class="text-left content">(</td>
                    <td class="text-left content"></td>
                    <td class="text-right content">)</td>
                    <td class="text-left content"><?php echo $row->qty_order ?></td>
                    <td class="text-left content"><?php echo $row->uom_id ?></td>
                    <td class="text-left content"><?php echo $row->product_code ?></td>
                    <td class="text-left content"><?php echo $row->product_name ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    </tbody>
</table>
</body>
<footer>
    <?php echo $so->crtby."      ".$so->crtdt?>
</footer>
</html>


<style rel="stylesheet/scss" lang="scss">
    .header, .header-space,
    .footer-space {
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
        margin-top: 1.8cm;
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
        height: 160px;
    }

    footer {
        position: fixed;
        bottom: -10px;
        left: 1cm;
        right: 1cm;
        height: 30px;
    }

</style>