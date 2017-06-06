<?php
include('connect.php');
include('function.php');
if(isset($_POST["user_id"]))
{
	$output = array();
	$statement = $connection->prepare(
		"SELECT * FROM reporting_users 
		WHERE id = '".$_POST["user_id"]."' 
		LIMIT 1"
	);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output["username"] = $row["Username"];
		$output["password"] = $row["PasswordHash"];
		$output["email_add"] = $row["emailAddress"];
	}
	echo json_encode($output);
}
?>