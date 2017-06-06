 <?php
session_start();
include('classes/notices_controllers.php');
$notices = new Notices;

switch($_POST['action']){
	case 'sentmail':
		$notices->sentmailNoticesNotSent($_POST['sendtitleID'],$_POST['relatedtitleID'],$_POST['ispName']);
		break;
	case 'updateips':
		break;
}
?>