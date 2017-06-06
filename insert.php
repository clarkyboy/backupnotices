<?php
include('connect.php');
include('db.php');

if(isset($_POST["operation"]))
{
	if($_POST["operation"] == "Add")
	{
			$createdBy = trim($_POST['createdBy']);
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $lastLogin = trim($_POST['lastLogin']);
            $emailAdd = trim($_POST['email_add']);
            $sendout = trim($_POST['sendout']);
            $sendoutf = trim($_POST['sendoutf']);
            if(($createdBy != '') || ($lastLogin != '') || ($password != '') || ($username != ''))
            {
                try {
                    $handler = new PDO('mysql:host=172.27.12.51;dbname=settlement_solutions_2','christian', 'R4mo5idoMER8');
                    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e){
                    exit($e->getMessage());
                }

                $sthandler = $handler->prepare("SELECT username FROM reporting_users WHERE username = :username");
                $sthandler->bindParam(':username', $username);
                $sthandler->execute();

                if($sthandler->rowCount() <= 0)
                {
                        try {
                        $handler = new PDO('mysql:host=172.27.12.51;dbname=settlement_solutions_2','christian', 'R4mo5idoMER8');
                        $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (PDOException $e){
                        exit($e->getMessage());
                    }

                    $sthandler1 = $handler->prepare("SELECT username FROM reporting_users WHERE username = :username");
                    $sthandler1->bindParam(':username', $username);
                    $sthandler1->execute();
                    if($sthandler1->rowCount() <= 0){
                    $hash = md5($password);
                    add_user($username, $hash, $createdBy, $lastLogin, $emailAdd, $sendoutf, $sendout);
                    
            		echo 'Data Inserted!';
                    }
                    else{
                         echo "Your username is already existing! Please try another :D";
           
                    }
                }
                else{
                   echo "This person is already in the database!";
 
                }
            }
	}
	if($_POST["operation"] == "Edit")
	{
		$statement = $connection->prepare(
			"UPDATE reporting_users
			SET Username = :Username, PasswordHash = :PasswordHash, emailAddress = :emailAddress 
			WHERE id = :id
			"
		);
		$password = $_POST["password"];
		$hash = md5($password);
		$statement->bindParam(':Username', $_POST["username"]);
		$statement->bindParam(':PasswordHash', $hash);
		$statement->bindParam(':emailAddress', $_POST["email_add"]);
		$statement->bindParam(':id', $_POST["user_id"]);
		$result = $statement->execute();
		if(!empty($result))
		{
			echo 'Data Updated';
		}
	}
}

?>