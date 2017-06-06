<?php
session_start();
if($_SESSION['notices']['loginStat'] == ''){
	die(0);
}

include('classes/notices_controllers.php');
$notices = new Notices;
$ip = $_POST['ip'];

$total = $notices->getIPCapturesTotal($ip);
echo $total;
?>