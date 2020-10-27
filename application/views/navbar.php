<?php
$full_name = $this->session->userdata('fullname');
?>
<ul>
    <li><a href="/index.php">Inventory</a></li>
    <li><a href="/demo/main/index.php">Inventory Report</a></li>
    <li><a href="/tutorial/index.php">Finance</a></li>
    <li><a href="/documentation/index.php">Jurnal Setting</a></li>
<!--    <li><a href="/download/index.php">SQL Inventory</a></li>-->
    <li><a href="/extension/index.php">General Legder</a></li>
    <li><a href="<?php echo base_url(); ?>auth/logout_act">Logout</a></li>
</ul>
