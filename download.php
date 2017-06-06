<?php
session_start();
if( !isset($_SESSION['notices']) || $_SESSION['notices']['loginStat'] == '') die();

$country = $_SESSION['notices']['country'];
$country = $country && strtolower($country)!='us'?'CA':'US';

$refno = $_GET['refno'];
$refOrig = isset($_GET['reforig'])? $_GET['reforig'] : '';
$fname = ($refOrig? $refOrig . '_' : '') . $refno . '.eml';

if(isset($_GET['reply'])){
	$fname = ($refOrig? $refOrig . '_' : '') . $refno . '_reply.eml';
}

if(!$refno || !is_numeric($refno) || strlen($refno) < 7) die();

$file = isset($_GET['reply'])?
		'/mnt/evirdgoogle/_Notices/' . $country . '_REPLIES/' . substr( $refno, 0, strlen($refno)-6) . '000000/' . $refno . '.eml' 	
		: '/mnt/evirdgoogle/_Notices/' . $country . '/' . substr( $refno, 0, strlen($refno)-6) . '000000/' . $refno . '.eml' ;

if(!file_exists($file))
{
	die("<b>Error:</b> File \"$refno.eml\" not found.");
}

$ctype = mime_content_type($file);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: message/rfc822");
header("Content-Disposition: attachment; filename=\"". $fname . "\";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($file));
readfile($file);
exit();
?> 