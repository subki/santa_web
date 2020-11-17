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
                    <td width="20%">Customer</td>
                    <td width="1%">:</td>
                    <td width="10%"><?php echo $so->customer_code?></td>
                    <td width="40%"><?php echo $so->customer_name?></td>
                </tr>
                <tr>
                    <td width="20%">Faktur No.</td>
                    <td width="1%">:</td>
                    <td width="10%"><?php echo $so->ak_docno ; ?></td>
                    <td width="40%"></td>
                </tr> 
                </tbody>
            </table>
        </div>
    </div>
</header> 
<div class="report-detail" >
    <table>
        <tbody>
        <div class="content">
            <table style="width: 100%;">
                <thead>
                <tr><th colspan="10"><div class="border-bottom-double"/></th></tr>
                <tr> 
                    <th class="title-header text-left content" width="3%" style="padding-top: 5px; padding-bottom: 5px">Qty</th>
                    <th class="title-header text-left content" width="6%" style="padding-top: 5px; padding-bottom: 5px">UOM</th>
                    <th class="title-header text-left content" width="25%" style="padding-top: 5px; padding-bottom: 5px">Product Code</th>
                    <th class="title-header text-left content" width="40%" style="padding-top: 5px; padding-bottom: 5px">Product Description</th>
                    <th class="title-header text-left content" width="18%" style="padding-top: 5px; padding-bottom: 5px">Bft Tax</th>
                    <th class="title-header text-left content" width="18%" style="padding-top: 5px; padding-bottom: 5px">After Tax</th>
                    <th class="title-header text-left content" width="12%" style="padding-top: 5px; padding-bottom: 5px">Disc1 %</th>
                    <th class="title-header text-left content" width="12%" style="padding-top: 5px; padding-bottom: 5px">Disc2 %</th> 
                    <th class="title-header text-left content" width="30%" style="padding-top: 5px; padding-bottom: 5px">Sales Before PPN</th>
                </tr>
                <tr><th colspan="10"><div class="border-bottom-double"/></th></tr>
                </thead>
                <tbody >
                <?php
                foreach ($det as $row) { ?>
                    <tr > 
                        <td class="text-left content"><?php echo $row->qty_order ?></td>
                        <td class="text-left content"><?php echo $row->uom_id ?></td>
                        <td class="text-left content"><?php echo $row->product_code ?></td>
                        <td class="text-left content"><?php echo $row->product_name ?></td>
                        <td class="text-right content"><?php echo number_format($row->bruto_before_tax,2,".",","); ?></td>
                        <td class="text-right content"><?php echo number_format($row->net_total_price,2,".",","); ?></td>
                        <td class="text-left content"><?php echo $row->disc1_persen ?></td>
                        <td class="text-left content"><?php echo $row->disc2_persen ?></td> 
                        <td class="text-right content"><?php echo number_format($row->bfr_ppn,2,".",","); ?></td>
                    </tr>
                <?php } ?>  
                </tbody> 
            </table>  
        </div>
        </tbody>
    </table>
</div>
</body>
 <?php 
 
    // FUNGSI TERBILANG OLEH : MALASNGODING.COM
    // WEBSITE : WWW.MALASNGODING.COM
    // AUTHOR : https://www.malasngoding.com/author/admin
 
 
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = penyebut($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
 
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(penyebut($nilai));
        } else {
            $hasil = trim(penyebut($nilai));
        }           
        return $hasil;
    }
  
   
    ?>
<div class="footerbilangan">
    <table>
        <tbody>
            <table style="width: 100%;">
                <thead> 
                <tr><th colspan="13"><div class="border-bottom-double"/></th></tr>
                    <tr> 
                        <th style="vertical-align:top" colspan="3" rowspan="6" class="text-left content" width="8%" style="padding-top: 5px; padding-bottom: 5px">Terbilang</th>
                        <th style="vertical-align:top" rowspan="6" class="text-left content" width="1%" >:</th>
                        <th style="vertical-align:top" rowspan="6" colspan="6" class="text-left content" width="40%"  > # <?php echo terbilang($so->sales_after_tax); ?> Rupiah # </th> 
                        <th class="text-left content" width="10%"  >SUB TOTAL</th> 
                        <th class="text-left content" width="1%"  >:</th> 
                        <th class="text-right content" width="12%"  ><?php echo number_format($so->sales_before_tax,2,".",",");  ?></th>  
                    </tr> 
                </thead> 
                 <tbody > 
                    <tr >    
                        <th class="text-left content">TOTAL DISC</th> 
                        <th class="text-left content">:</th>
                        <th class="text-right content"><?php echo number_format($so->total_discount,2,".",",");  ?></th>
                    </tr>  
                    <tr >  
                        <th class="text-left content">TOTAL PPN</th> 
                        <th class="text-left content">:</th>
                        <th class="text-right content"><?php echo number_format($so->total_ppn,2,".",",");  ?></th>
                    </tr> 
                    <tr >  
                        <th class="text-left content">TOTAL FAKTUR</th> 
                        <th class="text-left content">:</th>
                        <th class="text-right content"><?php echo number_format($so->sales_after_tax,2,".",",");  ?></th>
                    </tr> 
                    <tr >  
                        <th class="text-left content">DOWN PAYMENT</th> 
                        <th class="text-left content">:</th>
                        <th class="text-right content"></th>
                    </tr> 
                    <tr >  
                        <th class="text-left content">LUNAS</th> 
                        <th class="text-left content">:</th>
                        <th class="text-right content"></th>
                    </tr>  
                </tbody> 
            </table>
        </tbody>
        </table>
</div> 
<footer>
     
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
        font-size: 8pt;
    } 
    .report-detail {
        position: fixed;
        top: 0.1cm;
        left: 0.5cm;
        right: 0.5cm;  
    }
    /** Define the margins of your page **/
    @page {
        margin-top: 1.4cm;
        margin-bottom: 0.3cm;
        margin-left: 0.5cm;
        margin-right: 0.5cm;
        size: 21.0cm 14.8cm; size : landscape; /** Ukuran kertas A5 **/
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
        height: 80px;
    }

    footer {
        position: fixed;
        bottom: -10px;
        left: 1cm;
        right: 1cm;
        height: 80px;
    }
    .footerbilangan {
        position: fixed;
        left: 0.5cm;
        right: 0.5cm;  
        bottom: -10px;  
        height: 100px;
    }
</style>