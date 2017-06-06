 <?php
    session_start();
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $country = $_GET['country'];
    $titleid = $_GET['titleid'];
    echo json_encode($notices->get_top100_pertitle($titleid, $country));
?>