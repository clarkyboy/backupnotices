<?php

// function upload_image()
// {
// 	if(isset($_FILES["user_image"]))
// 	{
// 		$extension = explode('.', $_FILES['user_image']['name']);
// 		$new_name = rand() . '.' . $extension[1];
// 		$destination = './upload/' . $new_name;
// 		move_uploaded_file($_FILES['user_image']['tmp_name'], $destination);
// 		return $new_name;
// 	}
// }

// function get_image_name($user_id)
// {
// 	include('db.php');
// 	$statement = $connection->prepare("SELECT image FROM users WHERE id = '$user_id'");
// 	$statement->execute();
// 	$result = $statement->fetchAll();
// 	foreach($result as $row)
// 	{
// 		return $row["image"];
// 	}
// }

function get_total_all_records()
{
	include('connect.php');
	$statement = $connection->prepare("SELECT * FROM reporting_users");
	$statement->execute();
	$result = $statement->fetchAll();
	return $statement->rowCount();
}
function findLawyer($userid){

		include('connect.php');
		$sql = "SELECT cLayerName FROM lawyer_informations WHERE LawyerID = :userid";
		$sthandler1 = $connection->prepare($sql);
		$sthandler1->bindParam(':userid', $userid);
		$sthandler1->execute();
		$username = $sthandler1->fetch();
		$result = $username['cLayerName'];
		return $result;
		$db = null;
}
?>