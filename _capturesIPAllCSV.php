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
		
$notices = new Notices;
$results = $notices->getIPCapturesAll($ip,$offset,$rows);

if($page==1){
	$_SESSION['data_cip_name'] = time() .'-IP ' . $ip . ' Captures.csv';
	$_SESSION['data_cip_handler'] = fopen('downloads/'.$_SESSION['data_cip_name'],'w');
	
	fwrite($_SESSION['data_cip_handler'],"IP,PORT,Peer ID,Torrent Hash,Torrent Name,Rights Holder,Bit Field,Session Start,Transfer End,Session End\n");
}
else{
	$_SESSION['data_cip_handler'] = fopen('downloads/'.$_SESSION['data_cip_name'],'a');
}

if($results){
	foreach($results AS $i){
		$i['IP'] = $i['IP']? str_replace(',',' ',$i['IP']) : ' ';
		$i['Port'] = $i['Port']? str_replace(',',' ',$i['Port']) : ' ';
		$i['PeerID'] = $i['PeerID']? str_replace(',',' ',$i['PeerID']) : '';
		$i['TorrentHash'] = $i['TorrentHash']? str_replace(',',' ',$i['TorrentHash']) : ' ';
		$i['Torrentname'] = $i['Torrentname']? str_replace(',',' ',$i['Torrentname']) : '';
		$i['Owner'] = $i['Owner']? str_replace(',',' ',$i['Owner']) : ' ';
		$i['Bitfield'] = $i['Bitfield']? str_replace(',',' ',$i['Bitfield']) : ' ';
		$i['SessionStart'] = $i['SessionStart']? str_replace(',',' ',$i['SessionStart']) : ' ';
		$i['TransferEnd'] = $i['TransferEnd']? str_replace(',',' ',$i['TransferEnd']) : ' ';
		$i['SessionEnd'] = $i['SessionEnd']? str_replace(',',' ',$i['SessionEnd']) : ' ';
		
		$csv = $i['IP'].','.$i['Port'].','.$i['PeerID'].','.$i['TorrentHash'].','.$i['Torrentname'].','.$i['Owner'].','.$i['Bitfield'].','.$i['SessionStart'].','.$i['TransferEnd'].','.$i['SessionEnd']."\n";
		fwrite ($_SESSION['data_cip_handler'],$csv);
	}
}

fclose ($_SESSION['data_cip_handler']);
echo json_encode($results);
?>