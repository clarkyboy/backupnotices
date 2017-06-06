<?php
// ini_set('max_execution_time', 5000);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
if($_SESSION['notices']['loginStat'] == ''){
	die(0);
}

include('classes/notices_controllers.php');
$notices = new Notices;

$ip = $_GET['ip'];
$page = isset($_GET['page'])? intval($_GET['page']) : 1;
$rows = intval($_GET['rows']);
$total = isset($_GET['total'])? intval($_GET['total']) : 0;
$offset = $page==1? 0 : (($page-1) * $rows) + 1;
		

if(!$total) $total = $notices->getIPCapturesTotal($ip);

$results = $notices->getIPCapturesAll($ip,$offset,$rows);

if(!isset($_SESSION['data'])) $_SESSION['data'] = array();
$_SESSION['data'][] =$results;
print '<pre>' . print_r($results,true) . '</pre>';
// sleep(5);
// if($offset < $total + $rows){
	// $page++;
	// header("Location: https://securedservice.net/notices/_capturesIPAllExtract.php?ip=$ip&rows=$rows&page=$page");
// }
// else{
	// generateCSV($_SESSION['data'],$total);
	// unset($_SESSION['data']);
// }

// function generateCSV($data,$total){
	// echo count($data).'/'.$total;
	// print '<pre>' . print_r($data,true) . '</pre>';
// }
?> 