<?php 
$datenow=date("d-m-Y-His");
header("Content-type: application/octet-stream");

header("Content-Disposition: attachment; filename=$so->trx_no-$datenow.xls");

header("Pragma: no-cache");

header("Expires: 0");

?>
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
                    <td width="30%">No. Transaksi Opname</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->trx_no; ?></td> 
                </tr>
                <tr>
                    <td width="30%">Tgl Opname</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $date=date_create($so->trx_date);
                                        echo date_format($date,"d/m/Y ");?></td> 
                </tr>
                <tr>
                    <td width="30%">User </td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->useropname;?></td> 
                </tr>
                <tr>
                    <td width="30%">KETERANGAN</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->remark;?></td> 
                </tr>
                <tr>
                    <td width="30%">Print Ke-</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $so->print;?></td> 
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</header>
<table style="margin-top:-111px">
    <tbody>
    <div class="content">
        <table border="1" style="width: 100%;margin-top:-120px">
            <thead> 
            <tr>
                <th class="title-header text-left content" width="5%" style="padding-top: 5px; padding-bottom: 5px">No</th>
                <th class="title-header text-left content" width="10%" style="padding-top: 5px; padding-bottom: 5px">Item</th>
                <th class="title-header text-left content" width="30%" style="padding-top: 5px; padding-bottom: 5px">Product Code</th>
                <th class="title-header text-left content" width="6%" style="padding-top: 5px; padding-bottom: 5px">UOM</th> 
                <th class="title-header text-left content" width="16%" style="padding-top: 5px; padding-bottom: 5px">Qty Stock</th>
                <th class="title-header text-left content" width="16%" style="padding-top: 5px; padding-bottom: 5px">Qty Scan</th>
                <th class="title-header text-left content" width="16%" style="padding-top: 5px; padding-bottom: 5px">Selisih</th>
            </tr> 
            </thead>
            <tbody>
            <?php  
            $no=1;
            for ($i=0; $i<count($det); $i++) { ?>
                <tr>
                    <td class="text-left content"><?php echo  $no++; ?></td>
                    <td class="text-left content"><?php echo $det[$i]->item ?></td>
                    <td class="text-left content"><?php echo $det[$i]->product_code ?></td>
                    <td class="text-left content"><?php echo $det[$i]->uom ?></td>
                    <td class="text-center content"><?php echo $det[$i]->QTYStock ?></td>
                    <td class="text-center content"><?php echo $det[$i]->QTYScan ?></td>
                    <td class="text-center content"><?php echo $det[$i]->Selisih ?></td> 
                </tr><?php } ?> 
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
    .footer, .footer-space {
        height: 130px;
    }

    .footer {
        position: fixed;
        bottom: 0;
    }
 
    /*table thead {*/
    /*border-bottom:3px double black;*/
    /*border-top:3px double black;*/
    /*}*/
  

    table {
        border-spacing:0;
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
        position: relative;
        top: -2.5cm;
        left: 0.5cm;
        right: 0.5cm;
        height: 120px;
    } 
    footer {
        position: fixed;
        font-size: 7;
        bottom: -10px;
        left: 1cm;
        right: 1cm;
        height: 20px;
    }
</style>