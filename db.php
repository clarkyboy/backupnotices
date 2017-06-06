<?php
ini_set('max_execution_time', 0);
// require 'phpmailer/PHPMailerAutoload.php';
function site_db(){
		
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
function add_user( $username, $password, $createdBy, $lastLogin, $emailAdd, $sendout, $sendoutf)
	{
		$db = site_db();
		$sql = "INSERT into reporting_users (Username, PasswordHash, createdByUserID, lastLogin, emailAddress, SendoutFrequency, LastSendout) VALUES (?,?,?,?,?,?,?)";
		$st = $db->prepare($sql);
		$st->execute(array($username, $password, $createdBy, $lastLogin, $emailAdd, $sendoutf, $sendout));	
		$db = null;
	}
function display(){

	 	$sql = "SELECT * FROM reporting_users";
		$db = site_db();
		$st = $db->prepare($sql);
		$st->execute();
		$grp = $st->fetchAll(); //returns and array of arrays
		$db = null;
		return $grp;
}
function display_relatedtitles(){

	 	$sql = "SELECT api.relatedtitleinfo.relatedtitlename, relatedtitleid from active_title left join api.relatedtitleinfo using(relatedtitleid) where country='US' or country ='CA' or country='*' group by relatedtitleid order by relatedtitlename asc;";
		$db = site_db();
		$st = $db->prepare($sql);
		$st->execute();
		$grp = $st->fetchAll(); //returns and array of arrays
		$db = null;
		return $grp;
}
function members()
    {
        $sql = "SELECT * FROM lawyer_informations";
		$db = site_db();
		$st = $db->prepare($sql);
		$st->execute();
		$grp = $st->fetchAll(); //returns and array of arrays
		$db = null;
		return $grp;
    }
function findLawyer($userid){

		$db = site_db();
		$sql = "SELECT cLayerName FROM lawyer_informations WHERE LawyerID = :userid";
		$sthandler1 = $db->prepare($sql);
		$sthandler1->bindParam(':userid', $userid);
		$sthandler1->execute();
		$username = $sthandler1->fetch();
		$result = $username['cLayerName'];
		return $result;
		$db = null;
}
function allocate_titles($userid, $relatedtitle){

		$db = site_db();
		$sql = "INSERT into reporting_titleallocation (Userid, RelatedtitleID) VALUES ('$userid', '$relatedtitle')";
		$st = $db->prepare($sql);
		$st->execute(array($userid, $relatedtitle));	
		$db = null;
}
function findUser($userid){

		$db = site_db();
		$sql = "SELECT username FROM reporting_users WHERE id = :userid";
		$sthandler1 = $db->prepare($sql);
		$sthandler1->bindParam(':userid', $userid);
		$sthandler1->execute();
		$username = $sthandler1->fetch();
		$result = $username['username'];
		return $result;
		$db = null;
}
function findRelated($relatedtitle){

		$db = site_db();
		$sql = "SELECT relatedtitlename from api.relatedtitleinfo where relatedtitleid = :relatedtitle";
		$sthandler1 = $db->prepare($sql);
		$sthandler1->bindParam(':relatedtitle', $relatedtitle);
		$sthandler1->execute();
		$username = $sthandler1->fetch();
		$result = $username['relatedtitlename'];
		return $result;
		$db = null;
}
function findAvailable($relatedtitle){

		$db = site_db();
		$sql = "SELECT relatedtitleid FROM reporting_titleallocation WHERE relatedtitleid = :relatedtitle";
		$sthandler1 = $db->prepare($sql);
		$sthandler1->bindParam(':relatedtitle', $relatedtitle);
		$sthandler1->execute();
		$username = $sthandler1->fetch();
		$result = $username['relatedtitle'];
		return $result;
		$db = null;
}
function display_allocatedusers(){

		$sql = "SELECT * FROM reporting_titleallocation";
		$db = site_db();
		$st = $db->prepare($sql);
		$st->execute();
		$grp = $st->fetchAll(); //returns and array of arrays
		$db = null;
		return $grp;
}
function getEmail($userid){

		$db = site_db();
		$sql = "SELECT emailAddress FROM reporting_users WHERE id = :userid";
		$sthandler1 = $db->prepare($sql);
		$sthandler1->bindParam(':userid', $userid);
		$sthandler1->execute();
		$username = $sthandler1->fetch();
		$result = $username['emailAddress'];
		return $result;
		$db = null;
}
function getTitles($userid){

		$db = site_db();
		$sql = "SELECT * FROM reporting_titleallocation WHERE Userid = :userid";
		$sthandler1 = $db->prepare($sql);
		$sthandler1->bindParam(':userid', $userid);
		$sthandler1->execute();
		$username = $sthandler1->fetchAll();
		return $username;
		$db = null;
}
// function mailer($email, $userid){

// 		$variable = getTitles($userid);
// 		$message = "";
// 		$message .= '<html><head>
// 		  <title>testrun</title>
// 		</head><body><p>Here are the reporting users assigned by Atty Crowell to you '.$email.'</p>';
// 		$message .= '<table rules="all" style="border-color: #62c462" cellpadding="10">';
// 		$message .= '<tr style="background: #8DBFCF;"><th>ID</th><th>Userid</th><th>Username</th><th>RelatedTitleID</th><th>Related Title Name</th></tr>';
// 		foreach($variable as $key) {
			
// 		        $message .= '<tr><td>'.$key['id'].'</td>';
// 		        $message .= '<td>'.$key['Userid'].'</td>';
// 		        $message .= '<td>'.$user = findUser($key['Userid']); echo $user.'</td>';
// 		        $message .= '<td>'.$key['RelatedtitleID'].'</td>';
// 		        $message .= '<td>'.$user = findRelated($key['RelatedtitleID']); echo $user.'</td></tr>';
// 		}
// 		$message .= '</table>';
// 		$message .= '<body></html>';
// 		$to = $email;
// 		$subject = "Allocated titles to ".$email;
// 		// To send HTML mail, the Content-type header must be set
// 		$headers  = 'MIME-Version: 1.0' . "\r\n";
// 		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// 		// Additional headers
// 		$headers .= 'To: '.$email.''. "\r\n";
// 		$headers .= 'Cc: yubabs94@gmail.com' . "\r\n";
// 		$headers .= 'Bcc: '.$email.'' . "\r\n";

// 		 $mail = new PHPMailer;

//                     $mail->isSMTP();

//                     $mail->Host = 'smtp.gmail.com';

//                     $mail->Port = 587;

//                     $mail->SMTPSecure = 'tls';

//                     $mail->SMTPAuth = true;

//                     $mail->Username = 'christian.cebueastern@gmail.com';

//                     $mail->Password = '84268524143775';

//                     $mail->setFrom('cbarral@newlachemysolutions.com', 'Christian C. Barral');

//                     $mail->addReplyTo('no-reply@example.com', 'No Reply');

//                     $mail->addAddress($to);

//                     $mail->Subject = $subject;

//                     $mail->msgHTML($message);

//                     if (!$mail->send()) {
//                        $error = "Mailer Error: " . $mail->ErrorInfo;
//                         
//                     } 
//                     else {
//                        echo '<script>alert("Message sent!");</script>';
//                     }

// }
function delete($userid, $relatedtitle)
	{
		$sql = "DELETE FROM reporting_titleallocation WHERE Userid=? and RelatedtitleID=?";
		$db = site_db();
		$st = $db->prepare($sql);
		$st->execute(array($userid, $relatedtitle));
		$db = null;
	}
function getCANoticeSentInfoSingle($ispName,$relatedtitleID){
			try{
				
				$db = site_db();
				$sql = "select noticesid from notices_ca where GeoIpIsp = ? and relatedtitleid = ? and dfirstmessage is not null group by iip;";
				$q = $db->prepare($sql);
				$q->execute(array($ispName,$relatedtitleID));
				return $q->fetchAll();
			} catch(PDOException $e){
				echo 'ERROR : '.$e->getMessage();
			}
		}
function getCAISPInfoSingle($relatedtitleID){
			try{
				$db = site_db();
				$sql = "SELECT geoipregion, geoipisp, dfirstmessage, dsecondmessage, count(noticesid) as 'Notices Sent' from notices_ca where relatedtitleid = ? and dfirstmessage is not null and dsecondmessage is not null group by geoipregion, geoipisp order by notices_ca.dSendMessage DESC, geoipregion asc, count(*) desc";
				$q = $db->prepare($sql);
				$q->execute(array($relatedtitleID));
				return $q->fetchAll();
			} catch(PDOException $e){
				echo 'ERROR : '.$e->getMessage();
			}
		}
?>