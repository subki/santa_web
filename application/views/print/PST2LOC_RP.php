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
                    <td width="50%" style="vertical-align: top; margin-right: 10px;">
                        <?php echo isset($header->to_pkp)? $header->to_pkp=="YES"? $sesi['nama pkp']: $header->from_store_name : $header->from_store_name ?><br />
                        <?php echo isset($header->to_pkp)? $header->to_pkp=="YES"? $sesi['alamat pkp']: $header->from_store_address : $header->from_store_address ?>
                    </td>
                    <td width="50%" style="vertical-align: top; margin-left: 10px;">
                        <?php echo $header->to_customer_name ?><br />
                        <?php echo $header->to_customer_address ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="title-header text-center border-bottom-double">SURAT JALAN LOCATION TRANSFER</p>
            <table width="100%">
                <tbody>
                <tr>
                    <td width="19%">Surat Jalan</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $header->docno."  /  ".date("d/M/Y",strtotime($header->doc_date)); ?></td>
                    <td width="19%">Lokasi</td>
                    <td width="1%">:</td>
                    <td width="20%"><?php echo $header->from_location_code." - ".$header->to_location_code; ?></td>
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
    <!--    <div class="header-space">&nbsp;</div>-->
    <tbody>
    <div class="content">
        <table style="width: 100%;">
            <thead>
            <tr><th colspan="9"><div class="border-bottom-double"/></th></tr>
            <tr>
                <th class="title-header text-center content" width="3%" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">No.</th>
                <th class="title-header text-center content" width="13%" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">Article</th>
                <th class="title-header text-center content" width="20%" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">Keterangan</th>
                <th class="title-header text-center content" width="6%" colspan="2" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">QTY</th>
                <th class="title-header text-center content" width="8%" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">Unit Price</th>
                <th class="title-header text-center content" width="5%" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">Discount</th>
                <th class="title-header text-center content" width="9%" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">Netto</th>
                <th class="title-header text-center content" width="9%" style="padding-left: 2px; padding-top: 5px; padding-bottom: 5px">Subtotal</th>
            </tr>
            <tr><th colspan="9"><div class="border-bottom-double"/></th></tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            $qty = 0;
            $prc = 0;
            foreach ($detail as $row) { ?>
                    <tr>
                        <td class="text-left content" width="3%" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i ?></td>
                        <td class="text-left content" width="13%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->product_code ?></td>
                        <td class="text-left content" width="20%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->nmbar ?></td>
                        <td class="text-right content" width="3%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->qty, 2, '.', ','); ?></td>
                        <td class="text-left content" width="3%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->uom_jual ?></td>
                        <td class="text-right content" width="8%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->retail_price,0,'.',',') ?></td>
                        <td class="text-right content" width="5%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->discount,2,'.',',') ?></td>
                        <td class="text-right content" width="9%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->net_price,0,'.',',') ?></td>
                        <td class="text-right content" width="9%" style="padding-left: 2px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->net_price*$row->qty,0,'.',',') ?></td>
                    </tr>
                <?php $i++; $qty+=$row->qty; $prc+= ($row->net_price*$row->qty); } ?>
            <tfoot>
                <tr>
                    <td class="title-header text-center content" width="10%" colspan="2" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Total Item#</td>
                    <td class="text-left content" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"><?php echo ": ". ($i-1) ."  <==> ". number_format($qty, 0, '.', ',') ?></td>
                    <td class="title-header text-right content" width="10%" colspan="4" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">TOTAL</td>
                    <td class="title-header text-right content" width="10%" colspan="2" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"><?php echo number_format($prc,0,'.',',')?></td>
                </tr>
            </tfoot>
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
        font-size: 10pt;
    }

    /** Define the margins of your page **/
    @page {
        margin-top: 0.8cm;
        margin-bottom: 0.3cm;
        margin-left: 0.2cm;
        margin-right: 0.2cm;
        size: 21.6cm 27.9cm; size : portrait; /** Ukuran kertas A4 **/
        font-family: Courier, sans-serif;
    }

    body {
        margin-top: 3cm;
        margin-bottom: 0.3cm;
        margin-left: 0.2cm;
        margin-right: 0.2cm;
        font-family: Courier, sans-serif;
    }

    header {
        position: fixed;
        top: -20px;
        left: 0.2cm;
        right: 0.2cm;
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