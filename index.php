<?php
    session_start();
    include('classes/notices_controllers.php');
    
    if($_SESSION['notices']['loginStat'] != '')
    {
         header('Location: home.php');
    }   

    $msg = "You need to Sign in to use this feature. Please Sign in.";
    
    if(isset($_POST['submit']))
    {
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        
        $notices = new Notices;
        $msg = $notices->user_login($user,$pass);
    }
    
    session_write_close();
?>
<!Doctype html>
<html>
<head>
<meta charset="utf-8">
<title>NOTICES Activity - Login</title>
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
        <h2>NOTICES</h2>
    </div><!-- end head div-->
    <form method="post">    
    <div style="background:#eaeaec; padding: 5px; text-align: center;"><!-- form div-->
        <input type="text" style="margin:10px; padding: 5px;" name="user" value="" placeholder="Enter your User ID" required=""><br>
        <input type="password" style="margin:10px; padding: 5px;" name="pass" value="" placeholder="Password" required=""><br>
    </div><!-- end form div-->
    <div style="text-align:center; padding: 5px; border: thin solid #999; background:#999; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; height:50px;"><!-- foot div-->
        <input type="submit" style="border: solid thin #bce8f1; border-radius: 3px; width: 90%; background: #d9edf7; color: #31708f; height: 30px;" name="submit" value="Sign me in">
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