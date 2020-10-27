<html>
<head> 
</head>
<body style="width: 100%;height: auto">
<div >  
    <img src="<?php echo base_url()?>assets/barcode/<?php echo $so->docno?>.jpg" style="width: 80%;"> 
 
</div>
 <header>
    <div class="report-header">
        <div class="content text-left">
            <table align="center" width="100%" >
                <tbody>
                <tr>
                    <td class="title-header text-left content" width="30%">No. /Cust.</td> 
                    <td class="title-header text-left content" width="50%" class="text-left">:<?php echo $so->ak_docno ; ?></td> 
                </tr>
                <tr>
                    <td class="title-header text-left content" width="30%"><?php echo  $so->customer_name; ?></td> 
                    <td class="title-header text-left content" width="50%"></td>
                </tr>
                <tr>
                    <td class="title-header text-left content" width="30%">SO Date</td> 
                    <td class="title-header text-left content" width="50%">:<?php echo $so->ak_doc_date;?></td>
                </tr>
                <tr>
                    <td class="title-header text-left content" width="30%">SO#</td> 
                    <td class="title-header text-left content" width="50%">:<?php echo $so->so_no;?></td>
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
                <th class="title-header text-left content" width="5%" style="padding-top: 5px; padding-bottom: 5px">No.</th>
                <th class="title-header text-left content" width="50%" style="padding-top: 5px; padding-bottom: 5px">Item#</th>
                <th class="title-header text-left content" width="30%" style="padding-top: 5px; padding-bottom: 5px">Qty</th>  
            </tr>
            <tr><th colspan="5"><div class="border-bottom-double"/></th></tr>
            </thead>
            <tbody>
            <?php
            $i=1;
            foreach ($det as $row) { ?>
                <tr>
                    <td class="title-header text-left content"><?php echo $i; ?></td>
                    <td class="title-header text-left content"><?php echo $row->product_code ?></td>
                    <td class="title-header text-left content"><?php echo $row->qty_order ?> <?php echo $row->uom_id ?></td>  
                </tr>
            <?php 
                $i++;
            } ?>

            </tbody>
        </table>
        <table style="width: 100%;">
            <tbody>
            <tr style="height: 50px"><th colspan="5"><div class="border-bottom-double"/></th></tr>
            <tr>
                <th class="title-header text-left content" width="15%" style="padding-top: 5px; padding-bottom: 5px">Item</th>
                <th class="title-header text-left content" width="80%" style="padding-top: 5px; padding-bottom: 5px">:<?php echo $totalitem ;?></th> 
            </tr>
            <tr>
                <th class="title-header text-left content" width="15%" style="padding-top: 5px; padding-bottom: 5px">Qty</th>
                <th class="title-header text-left content" width="80%" style="padding-top: 5px; padding-bottom: 5px">:<?php echo (int)$qty; ?></th> 
            </tr>
            <tr style="height: 50px"><th colspan="5"><div class="border-bottom-double"/></th></tr>
            <tr>
                <th class="title-header text-left content" width="15%" style="padding-top: 5px; padding-bottom: 5px">Print </th>
                <th class="title-header text-left content" width="80%" style="padding-top: 5px; padding-bottom: 5px">:<?php echo $so->crtby ;?></th> 
            </tr>
            <tr>
                <?php if($so->jumlah_print <=1){?>  
                    <th colspan="2" class="title-header text-left content" width="80%" style="padding-top: 5px; padding-bottom: 5px"><?php echo $so->tanggal_crt;?>  </th> 
                <?php }else{  ?>
                <th class="title-header text-left content" width="15%" style="padding-top: 5px; padding-bottom: 5px">Copied  </th>
                <th class="title-header text-left content" width="80%" style="padding-top: 5px; padding-bottom: 5px">:<?php echo $so->tanggal_crt; ?></th>
                <?php }?> 
            </tr>
            </tbody>
        </table>
         <table align="center" width="100%" >
                <tbody>
                <tr>
                    <td class="title-header text-left content">Disiapkan</td> 
                    <td class="title-header text-left content" >Packing</td> 
                    <td class="title-header text-left content">Checked</td> 
                </tr>
                <tr>
                    <td class="title-header text-left content" style="height: 120px">(.......)</td>  
                    <td class="title-header text-left content" style="height: 120px">(.......)</td>  
                    <td class="title-header text-left content" style="height: 120px">(.......)</td>  
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
        margin-bottom: 0px;
    }
    .title-header{
        text-align: center;
        font-size: 10px;
        font-weight: bold;
    }
    .report-title p{
        text-align: center;
        margin: 0 0 0 0;
        font-size: 0pt;
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
    .content{
        font-family: 'Courier';
    }

    /** Define the margins of your page **/
    @page {  
      
        margin-top: 1mm;
        margin-bottom: 1mm;
        margin-left: 8mm;
        margin-right: 2mm;
        size:80mm  21cm; size : portrait; /** Ukuran kertas A4 **/
       /* font-family: Courier, sans-serif;*/
    }

    @font-face {
        font-family: 'Courier';
        font-weight: normal;
        font-style: normal;
        font-variant: normal;
        src: url("<?php echo base_url(); ?>assets/font/courier.ttf") format("truetype");
    } 
    footer {
        position: fixed;
        bottom: 1px;
        left: 1mm;
        right: 1mm;
        height: 1px;
    }

</style>

<script>
    window.print();
</script>