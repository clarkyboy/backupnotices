<?php
include('db.php');
// include_once('mailers.php');
ini_set('max_execution_time', 0);
$display = display();
$related = display_relatedtitles();
// function My_Alert($msg) {
//     echo '<script type="text/javascript">alert("' . $msg . '")</script>';
// }
            if(isset($_POST['add']))
                {
                    $userid = trim($_POST['userid']);
                    $hobb= $_POST['relatedtitle'];
                    $email = getEmail($userid);
                    if(empty($hobb)) 
                        {
                            echo("<p>You didn't select any any hobby.</p>\n");
                        } 
                        else 
                        {
                            $N = count($hobb);
                            for($i=0; $i < $N; $i++)
                            {
                                $var1=$hobb[$i];
                                allocate_titles($userid, $var1);
                            }
                        // mailer($email, $userid);
                        header("Location: allocatetitles.php");
                        }
               }
  
   

foreach ($related as $key) {
  
  }
  
?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <title>Reports Notices</title>

        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/multiselect.css">
         <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
        <link href="style.css" rel="stylesheet" type="text/css"/>
        <meta http-equiv="refresh" content="30000; url=<?php echo $_SERVER['PHP_SELF']; ?>">
    </head>
        <script src="js/jquery.js" type="text/javascript"></script>
       
        <script src="js/bootstrap.js" type="text/javascript"></script>

        <script type="text/javascript" charset="utf-8" language="javascript" src="js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>
        <script>
               {
                   var today=new Date();
                   var t=today.toLocaleTimeString("en-GB");
                    document.getElementById("itemdateofreg").value=t;
               }
              </script>

    <body>

       <<!-- div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
             <div class="container">
                 <div class="navbar-header navbar-right">
                    <div class="dropdown">
                        <a style=" margin-top:7px; margin-bottom: 2px;" class="dropdown-toggle btn btn-info" data-toggle="dropdown"><span class="glyphicon glyphicon-align-justify"> <b>Menu</b></span> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                         
                                  <li><a href="employee.php"><span class="glyphicon glyphicon-user"></span>Payroll Table</a></li>
                                   <li><a href="adminpanel.php"><span class="glyphicon glyphicon-user"></span>Employee Table</a></li>
                                   <li><a href="inventorypanel.php"><span class="glyphicon glyphicon-cloud"></span>Inventory Table</a></li>
                                   <li><a href="addsales.php"><span class="glyphicon glyphicon-user"></span>Sale form</a></li>
                                   <li><a href="adminlogout.php"><span class="glyphicon glyphicon-user"></span>Logout</a></li>
                  </div>
                 </div>
                 <a class="btn btn-primary" style="margin-top: 7px;" href="index.php"><span class="glyphicon glyphicon-edit"></span>Home</a>
             </div>
            </div>        -->
        <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12" >
                <div class="container">
                 <h2>Allocate Titles</h2>
         
                            <form method="post">
                            <table  class="table table-striped table-bordered">
                            <thead><th>Allocate Titles Form</th></thead>
                            <tbody>
                            <tr><td>Title Recipient</td> <td>
                            <select name= "userid">
                                <?php foreach($display as $m){?>
                                <option value="<?php echo htmlentities($m['id']);?>"><?php echo htmlentities($m['Username']);?></option>
                                 <?php } ?>
                            </select>
                               
                                 </td></tr>


                            <tr><td>Related Title Name </td><td>
                              <div class="multiselect">
                                <?php foreach($related as $m){?>
                                        <label><input type="checkbox" name="relatedtitle[]" value="<?php 
                                              try {
                                                    $handler = new PDO('mysql:host=172.27.12.51;dbname=settlement_solutions_2','christian', 'R4mo5idoMER8');
                                                            $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    } catch (PDOException $e){
                                                         exit($e->getMessage());
                                                    }
                                                $sthandler = $handler->prepare("SELECT relatedtitleid, userid FROM reporting_titleallocation WHERE relatedtitleid = :relatedtitleid");
                                                $sthandler->bindParam(':relatedtitleid', $m['relatedtitleid']);
                                                $sthandler->execute();
                                    if($sthandler->rowCount() <= 0){echo htmlentities($m['relatedtitleid']);}else{}?>" <?php try {
   $handler = new PDO('mysql:host=172.27.12.51;dbname=settlement_solutions_2','christian', 'R4mo5idoMER8');

   $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch (PDOException $e)
  { exit($e->getMessage());}
  $sthandler = $handler->prepare("SELECT relatedtitleid, userid FROM reporting_titleallocation WHERE relatedtitleid = :relatedtitleid");

  $sthandler->bindParam(':relatedtitleid', $m['relatedtitleid']);

  $sthandler->execute();

  if($sthandler->rowCount() == 0){echo $disable = $display ? ' ': '';}else{ echo $disable = $display ? 'disabled="disabled"': '';}


   ?> /><?php 
                                                if($sthandler->rowCount() <= 0){echo htmlentities($m['relatedtitlename']);}
                                                else{echo htmlentities($m['relatedtitlename']);}?></label>
                                <?php } ?>
                               </div> 
                               </td></tr>
                            </tbody>
                            <tfoot>
                               <tr>
                                <td><input type="submit" class="btn btn-warning" name="add" value="Allocate Titles">
                                <a class="btn btn-primary" href="reportingusers.php">Back</a>
                                </td>
                              </tr>

                            </tfoot>
                            </table>
                            </form>

            </div>
            </div>
            </div>
        </div>
        </div>



    </body>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
     <script>
     $(function() {
     $(".multiselect").multiselect();
        });
    jQuery.fn.multiselect = function() {
    $(this).each(function() {
        var checkboxes = $(this).find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = $(this);
            // Highlight pre-selected checkboxes
            if (checkbox.prop("checked"))
                checkbox.parent().addClass("multiselect-on");
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.prop("checked"))
                    checkbox.parent().addClass("multiselect-on");
                else
                    checkbox.parent().removeClass("multiselect-on");
            });
        });
    });
};
    </script> 
</html>
   