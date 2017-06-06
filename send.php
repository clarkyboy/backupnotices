<?php
if(isset($_POST)) {
    $name = $_POST['name'];
    $message = $_POST['message'];
    $from = $_POST['from'];

    if(!empty($name) && !empty($message)) {
        $subject = 'message from '.$name;

        $headers = "From: " . strip_tags($from) . "\r\n";
        $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
        //$headers .= "CC: susan@example.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // @SEE: http://php.net/manual/en/function.mail.php
        if(mail('christian.alientechsolutions@gmail.com', $subject, $message, $headers)) {
            echo 'Thx 4 msg!';
        }
        else {
            echo 'Oh nooos, The msg was not send.';
        }
    }
    else {
        echo 'You should provide the fields with some data..';
    }
}
?>