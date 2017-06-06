<?php 
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $titleid = isset($_POST,$_POST['titleid'])? $_POST['titleid'] : $_GET['titleid'];
    
	$usregions = $notices->getUSRegion($titleid);
	if($usregions){
		echo '<strong>Region</srong>';
		echo '<div id="usregions" style="max-height: 275px;overflow: auto;border: 1px solid #ccc;padding: 10px;">';
		foreach($usregions AS $k=>$i){
			echo '<p style="margin-bottom:0px;"><label style="margin-bottom:0px;" for="usregion-'.$k.'"><input value="'.$i['ID'].'" type="checkbox" name="usregion['.$k.']" class="usregion-fld" id="usregion-'.$k.'" '.($i['Active']==1?'checked="checked"':'').'/> '.$i['GeoIpRegion'].'</label></p>';
		}
		echo '</div>';
	}
?> 