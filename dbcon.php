<?php
include('db.php');
function db(){
		
		$servername = "172.27.12.51";
		$username = "christian";
		$password = "R4mo5idoMER8";
		$dbnames = "settlement_solutions_2";

		try {
		     return new PDO('mysql:host=172.27.12.51;dbname=settlement_solutions_2','christian','R4mo5idoMER8');
		    echo "Connected Successfully";
		  }
		catch(PDOException $e)
		    {
		    echo "Connection failed: " . $e->getMessage();
		    }
}
function getTitles($userid){

		$db = db();
		$sql = "SELECT * FROM reporting_titleallocation WHERE Userid = :userid";
		$sthandler1 = $db->prepare($sql);
		$sthandler1->bindParam(':userid', $relatedtitle);
		$sthandler1->execute();
		$username = $sthandler1->fetchAll();
		return $username;
		$db = null;
}


?>