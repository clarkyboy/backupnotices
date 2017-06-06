<?php
session_start();
if($_SESSION['notices']['loginStat'] == ''){
	die(0);
}

include('classes/notices_controllers.php');

$ip = $_GET['ip'];
$page = isset($_POST['page'])? intval($_POST['page']) : 1;
$rows = intval($_POST['rows']);
$offset = $page==1? 0 : (($page-1) * $rows) + 1;
		
$result = array();
$notices = new Notices;
$total = $notices->getIPCapturesTotal($ip);
$result['total'] = $total;
$result['rows'] = $notices->getIPCapturesAll($ip,$offset,$rows);
echo json_encode($result);
?>