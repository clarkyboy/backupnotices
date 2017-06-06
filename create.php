<?php
$msg = "This is where you create a title recipient";
ini_set('max_execution_time', 0);
include('db.php');
date_default_timezone_set("Asia/Manila");
$mem = members();
function My_Alert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}
if(isset($_POST['register']))
{
            $createdBy = trim($_POST['createdBy']);
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $lastLogin = trim($_POST['lastLogin']);
            $emailAdd = trim($_POST['emailAdd']);
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
                    $full = "Recipient created successfully!";
                    $msg = 
                            My_Alert($full);  
                            // header("Location: reportingusers.php");
                    }
                    else{
                        $full = "Your username is already existing! Please try another :D";
                    $msg = 
                            My_Alert($full);
                    }
                }
                else{
                    $full = "This person is already in the database!";
                    $msg = 
                            My_Alert($full);  
                }
                header("location: reportingusers.php");
            }

}

?>
<!Doctype html>
<html>
<head>
<meta charset="utf-8">
<title>NOTICES Activity - Create</title>
<style>
    body {
        font-family: Arial;
    }
</style>
</head>
<body>
<br><br>
<div style="margin:auto; width: 300px;"><!--wrapper div-->
    <div style="text-align:center; padding:10px;
    border: solid thin #bce8f1;
    text-align: center;
    background: #d9edf7;
    padding: 10px;
    color: #31708f;
    border-top-right-radius: 5px;border-top-left-radius: 5px;"><!-- head div-->
        <h2>Title Recipient</h2>
    </div><!-- end head div-->
    <form method="post">    
    <div style="background:#eaeaec; padding: 5px; text-align: center;"><!-- form div-->
        <input type="text" style="margin:10px; padding: 5px;" name="username" value="" placeholder="Username" required=""><br>
        <input type="password" style="margin:10px; padding: 5px;" name="password" value="" placeholder="Password" required=""><br>
         <input type="email" style="margin:10px; padding: 5px;" name="emailAdd" value="" placeholder="Email Address" required=""><br>
         <select name="createdBy" style="margin:10px; padding: 10px;">
                    <option>Atty In Charge</option>
                     <?php foreach($mem as $m){?>
                       <option value="<?php echo htmlentities($m['LawyerID']);?>"><?php echo htmlentities($m['cLayerName']);?></option>
                    <?php } ?>
        </select>
        <input type="hidden" name="lastLogin" id="lastLogin" value="0">
        <input type="hidden" name="sendoutf" id="sendoutf" value = "0">
        <input type="hidden" name="sendout" id="sendout" value = "0">
    </div><!-- end form div-->
    <div style="text-align:center; padding: 5px; border: thin solid #999; background:#999; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; height:50px;"><!-- foot div-->
        <input type="submit" style="border: solid thin #bce8f1; border-radius: 3px; width: 90%; background: #d9edf7; color: #31708f; height: 30px;" name="register" value="Create Recipient">
    </div><!-- end foot div-->
    </form>
    </div>
    
    <br><br>
    <div style="
    margin: auto;
    width: 244px;
    border: solid thin #faebcc;
    text-align: center;
    background: #fcf8e3;
    padding: 10px;
    color: #8a6d3b;
    border-radius: 5px;
">
    <?php echo $msg; ?>
</div><!--end wrapper div-->

</body>
</html>