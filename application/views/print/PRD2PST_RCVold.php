<html>
<head>
    <title>Receiving DO</title>
</head>
<body>
<!--<header>header on each page</header>-->
<header>
    <div class="report-header">
        <div class="content text-left">
            <p class="title-header text-center border-bottom-double">RECEIVING DO</p>
            <table width="100%">
                <tbody>
                <tr>
                    <td width="19%">Receiving DO#</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $header->docno; ?></td>
                    <td width="19%">Base On DO#</td>
                    <td width="1%">:</td>
                    <td width="20%"><?php echo $header->docno; ?></td>
                </tr>
                <tr>
                    <td width="19%">Received Date</td>
                    <td width="1%">:</td>
                    <td width="40%"><?php echo $header->ak_receive_date; ?></td>
                    <td width="19%">Date</td>
                    <td width="1%">:</td>
                    <td width="20%"><?php echo $header->ak_doc_date; ?></td>
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
            <div class="border-bottom-double"/>
        </div>
    </div>
</header>
<table>
    <!--    <div class="header-space">&nbsp;</div>-->
    <tbody>
    <div class="content">
        <table style="width: 100%;">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td class="title-header text-center content border-top border-bottom" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">No.</td>
                <td class="title-header text-center content border-top border-bottom" width="15%" colspan="2" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">QTY</td>
                <td class="title-header text-center content border-top border-bottom" width="30%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Article</td>
                <td class="title-header text-center content border-top border-bottom" width="45%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Keterangan</td>
            </tr>
            <?php
            $i = 1;
            $qty = 0;
            for($r=0; $r<30;$r++){
                foreach ($detail as $row) { ?>
                    <tr>
                        <td class="text-left content"><?php echo $i ?></td>
                        <td class="text-right content"><?php echo number_format($row->qty, 2, '.', ','); ?></td>
                        <td class="text-left content"><?php echo $row->uom_id ?></td>
                        <td class="text-left content"><?php echo $row->article_name ?></td>
                        <td class="text-left content"><?php echo $row->nmbar ?></td>
                    </tr>
                    <?php $i++; $qty+=$row->qty;}} ?>
            <tr>
                <td class="title-header text-center content border-top" width="10%" colspan="3" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Total Item#</td>
                <td class="text-left content border-top" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"><?php echo ": ". $i ."  <==> ". number_format($qty, 2, '.', ',') ?></td>
                <td class="title-header text-center content border-top" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>
            </tr>
            </tbody>
        </table>
    </div>
    </tbody>
</table>
<!--<br />-->
<!--<table>-->
<!--    <tbody>-->
<!--    <div class="content">-->
<!--        <table style="width: 100%;">-->
<!--            <thead>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr>-->
<!--                <td class="text-center content" height="120px" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--                <td class="text-center content" height="120px" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Penerima</td>-->
<!--                <td class="text-center content" height="120px" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--                <td class="text-center content" height="120px" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Gudang</td>-->
<!--                <td class="text-center content" height="120px" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--                <td class="text-center content" height="120px" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">Hormat Kami,</td>-->
<!--                <td class="text-center content" height="120px" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="text-center content" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--                <td class="text-center content" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(_____________________)</td>-->
<!--                <td class="text-center content" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--                <td class="text-center content" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(_____________________)</td>-->
<!--                <td class="text-center content" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--                <td class="text-center content" width="20%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px">(_____________________)</td>-->
<!--                <td class="text-center content" width="10%" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px"></td>-->
<!--            </tr>-->
<!--            </tbody>-->
<!--        </table>-->
<!--    </div>-->
<!--    </tbody>-->
<!--</table>-->
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
    table thead th {
        border-bottom:1px solid black;
    }

    /*@media only screen {*/
    /*.header {*/
    /*position: absolute;*/
    /*top: 20px;*/
    /*}*/
    /*body {*/
    /*font-family: Verdana, Geneva, sans-serif;*/
    /*line-height: 1.3;*/
    /*background: #cccccc !important;*/
    /*color: #000;*/
    /*font-size: 12px;*/
    /*padding-top: 10px;*/
    /*}*/

    /*.main-report {*/
    /*padding: 20px;*/
    /*margin: 0 auto;*/
    /*display: block;*/
    /*width: 800px;*/
    /*background: #ffffff;*/
    /*min-height: 700px;*/
    /*}*/
    /*}*/

    @media only print {
        .header {
            position: fixed;
            top: 0;
        }
        html, #app{
            height: 99%;
        }
        .entry-wrap {
            padding: 25px; /* adjust it accordingly */
        }
        body {
            font-family: Verdana, Geneva, sans-serif;
            line-height: 1.3;
            background: #fff !important;
            color: #000;
            font-size: 12px;
        }
        .main-report {
            margin: 8mm 5mm 5mm 8mm;
            display: block;
            width: 800px;
            background: #fff;
        }
    }

    .report-header{
        margin-bottom: 20px;
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
        font-size: 12px;
    }

    /** Define the margins of your page **/
    @page {
        margin: 100px 25px;
        size: 21.6cm 27.9cm; size : portrait; /** Ukuran kertas A4 **/
    }

    body {
        margin: 1cm;
    }

    header {
        position: fixed;
        top: -70px;
        left: 1cm;
        right: 1cm;
        height: 100px;
    }

    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
    }

</style>