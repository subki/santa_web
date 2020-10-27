<?php
	$user = $this->session->userdata('role');
	function check_priv($iduser, $class){
		if($iduser=='Administrator' || $iduser=='Auditor') echo '';
		else{
		    switch ($class){
                case "Report" : echo ''; break;
                default : echo 'hide';
            }
        }
	}
?>
<div class="easyui-accordion" style="width:100%;height:95%;" id="submenu"></div>
