<?php 

	$alert = '';

	if($this->session->flashdata('error')==null){

		$alert = 'hidden';
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Santa</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/ui-cupertino/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/themes/color.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/demo/demo.css">
    <script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.easyui.min.js"></script>
</head>
<body>

<div data-options="region:'center'">
    <div id="p" class="easyui-panel" title="Login Application" data-options="style:{margin:'0 auto'}" style="width:400px;height:200px; ">
        <form action="auth/login_act" method="post" accept-charset="UTF-8" name="frmLogin" id="frmLogin" style="margin-top: 4ex;">
            <table class="tborder" width="350" cellspacing="0" cellpadding="4" border="0" align="center">
                <tbody>
                <tr class="windowbg">
                    <td width="50%" align="right"><b>Username:</b></td>
                    <td><input type="text" size="20" value="" name="txt_uid"></td>
                </tr>
                <tr class="windowbg">
                    <td align="right"><b>Password:</b></td>
                    <td><input type="password" value="" size="20" name="txt_pwd"></td>
                </tr>
                <tr class="windowbg">
                    <td colspan="2" align="center"><input type="submit" value="Login" style="margin-top: 2ex;"></td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

</body>
</html>
