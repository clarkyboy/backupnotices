<?php
	//check if idno is passed from the URL
	if(isset($_GET['username']) && isset($_GET['relatedtitleid']))
	{
		include('db.php');
		$userid = trim($_GET['username']);
		$relatedtitle = trim($_GET['relatedtitleid']);
		delete($userid, $relatedtitle);
		$msg = "Successfully deleted";
		// echo '<html>
		// 			<head>
		// 			<style>
		// 			.alert {
		// 			    padding: 20px;
		// 			    background-color: #f44336;
		// 			    color: white;
		// 			    opacity: 1;
		// 			    transition: opacity 0.6s;
		// 			    margin-bottom: 15px;
		// 			}

		// 			.alert.success {background-color: #4CAF50;}
		// 			.alert.info {background-color: #2196F3;}
		// 			.alert.warning {background-color: #ff9800;}

		// 			.closebtn {
		// 			    margin-left: 15px;
		// 			    color: white;
		// 			    font-weight: bold;
		// 			    float: right;
		// 			    font-size: 22px;
		// 			    line-height: 20px;
		// 			    cursor: pointer;
		// 			    transition: 0.3s;
		// 			}

		// 			.closebtn:hover {
		// 			    color: black;
		// 			}
		// 			</style>
		// 			</head>
		// 			<body>
		// 			<div class="alert success">
		// 			  <span class="closebtn">&times;</span>  
		// 			  <strong>Success!</strong> <a href = "allocatetitles.php">Click here to go back</a>
		// 			</div>
		// 			<script>
		// 				var close = document.getElementsByClassName("closebtn");
		// 				var i;

		// 				for (i = 0; i < close.length; i++) {
		// 				    close[i].onclick = function(){
		// 				        var div = this.parentElement;
		// 				        div.style.opacity = "0";
		// 				        setTimeout(function(){ div.style.display = "none"; }, 600);
		// 				    }
		// 				}
		// 				</script>

		// 				</body>
		// 				</html>
		// 			';
		header('location: allocatetitles.php?');
		//echo "Student record has been deleted.";
	}

?>
