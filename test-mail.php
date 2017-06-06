<?php
include_once("phpmailer/class.phpmailer.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth= true;
$mail->Port = 465; // Or 587
$mail->Username= "abocadoweb@gmail.com";
$mail->Password= "!pa55w0rd";
$mail->SMTPSecure = 'ssl';
$mail->From = "abocadoweb@gmail.com";
$mail->FromName= "Notices";
$mail->isHTML(true);
$mail->Subject = 'Test email from notices';
$mail->Body = 'This is testing email only';
$mail->addAddress($_POST['email']);
echo $mail->send() ? 1 : "Mailer Error: " . $mail->ErrorInfo;
	
?>