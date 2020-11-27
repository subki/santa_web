<html>
<body>

<?php
$sesi = $this->session->userdata();
require_once "fungsi.php"
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
                    <td width="65%">
                        <div>
                            <table>
                                <tbody>
                                <tr>
                                    <td width="38%">No. Inv. Proforma</td>
                                    <td width="2%">:</td>
                                    <td width="60%"><?php echo $header->sales_proforma_id."  /  ".date('d/m/Y',strtotime($header->doc_date)); ?></td>
                                </tr>
                                <tr>
                                    <td width="38%">KETERANGAN</td>
                                    <td width="2%">:</td>
                                    <td width="60%"> - </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td width="35%">
                        TANDA TERIMA FAKTUR <br />
                        --------------------- <br /><br />
                        Kepada Yth, <br />
                        <?php echo $header->customer_name?> <br />
                        <?php echo $header->address1." ".$header->address2?> <br />
                        <br />
                        <?php echo $header->regency_name?> <br />
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
            <tr><th colspan="5"><div class="border-bottom"/></th></tr>
            <tr>
                <th class="title-header text-center content2" width="10%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">No.</th>
                <th class="title-header text-center content2" width="15%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">TGL FAKTUR</th>
                <th class="title-header text-center content2" width="20%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">NO. FAKTUR</th>
                <th class="title-header text-center content2" width="30%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">KETERANGAN</th>
                <th class="title-header text-center content2" width="25%" style="padding-left: 5px; padding-top: 2px; padding-bottom: 2px">NILAI FAKTUR</th>
            </tr>
            <tr><th colspan="5"><div class="border-bottom"/></th></tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            $subtot = 0;
            foreach ($detail as $row) { ?>
                <tr>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $i ?></td>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo date('d/m/Y', strtotime($row->faktur_date)) ?></td>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->no_faktur2 ?></td>
                    <td class="text-left content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo $row->remark ?></td>
                    <td class="text-right content2" style="padding-left: 5px; padding-top: <?php echo $i==1? '10px': '1px' ?>; padding-bottom: 1px"><?php echo number_format($row->sales_after_tax, 0, '.', ','); ?></td>
                </tr>
                <?php $i++;
                $subtot += $row->sales_after_tax;
            } ?>
            <tr><td colspan="5"><div class="border-bottom"/></td></tr>
            <tr>
                <td colspan="4" class="text-right content"> TOTAL :</td>
                <td class="text-right content"><?php echo number_format($subtot,0,'.',',')?></td>
            </tr>
            </tbody>
        </table>
        <div>
            <table style="width: 100%;" class="top">
                <tbody>
                <tr>
                    <td width="80%">
                        <table style="width: 100%;" class="content text-left">
                            <tbody>
                            <tr><td colspan="2">Tebilang</td></tr>
                            <tr>
                                <td width="1%"></td>
                                <td width="99%">#<?php echo terbilang($subtot,"Rupiah")?>#</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="20%"></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 10px">
            <table style="width: 100%;" class="top">
                <tbody>
                <tr>
                    <td width="40%">
                        <table style="width: 100%;" class="content text-left">
                            <tbody>
                            <tr><td>NB :</td></tr>
                            <tr><td>A/N :</td></tr>
                            <tr>
                                <td width="100%" height="10px"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="20%"></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="kiri">
            <p class="content text-center">
                Customer <br/><br/><br/><br/><br/>
                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
            </p>
        </div>
        <div class="kanan">
            <p class="content text-center">
                Finance <br/><br/><br/><br/><br/>
                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
            </p>
        </div>
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
    .border-kotak {
        border: solid;
        border-width: 1px !important;
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
    .border-top{
        border-top: 1px solid black;
        width:100%;
    }
    .border-bottom{
        border-bottom: 1px solid black;
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
    .kanan{
        float:right;
        width:40%;
        height: 20px;
    }
    .kiri{
        float:left;
        width:60%;
        height: 20px;
    }
    .tengah {
        display: inline-block;
        margin:0 auto;
        width:100px;
        height: 20px;
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
        margin-top: 4cm;
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