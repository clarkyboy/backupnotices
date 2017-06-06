<?php 
  include('db.php');
  ini_set('max_execution_time', 0);
  $display = display_allocatedusers();
  date_default_timezone_set("Asia/Manila");
?>

<!DOCTYPE html>
<html lang="en">
    <head>

        <title>Reports Notices</title>

        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
        <link href="style.css" rel="stylesheet" type="text/css"/>
        <meta http-equiv="refresh" content="3000; url=<?php echo $_SERVER['PHP_SELF']; ?>">
    </head>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/bootstrap.js" type="text/javascript"></script>

        <script type="text/javascript" charset="utf-8" language="javascript" src="js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>

    <body>

      <!--  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
                      </ul>
                  </div>
                 </div>
                 <a class="btn btn-primary" style="margin-top: 7px;" href="index.php?username=<?php echo htmlentities($_SESSION['username']) ?>"><span class="glyphicon glyphicon-edit"></span>Home</a>
             </div>
            </div>        -->
        <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12" >
                <div class="container">
				 <h2>Recipient with Titles</h2>
                            <table  class="table table-striped table-bordered" id="example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th data-th="Driver details"><span>User ID</span></th>
                                        <th data-th="Driver details"><span>User Name</span></th>
                                        <th>Related Title ID Assigned</th>
                                        <th>Title Owner</th>
                                        <th width="10%">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($display as $disp) { ?>
                                       <tr>
                                         <td><?php echo $disp['id'];?></td>
                                         <td><?php echo $disp['Userid'];?></td>
                                         <td><?php 
                                              $user = findUser($disp['Userid']);
                                              echo $user;
                                         ;?></td>
                                         <td><?php echo $disp['RelatedtitleID'];?></td>
                                          <td><?php 
                                              $user = findRelated($disp['RelatedtitleID']);
                                              echo $user;
                                         ;?></td>
                                         <td><a href="delete-titles.php?username=<?php echo htmlentities($disp['Userid']);?>&relatedtitleid=<?php echo htmlentities($disp['RelatedtitleID'])?>" onclick="return confirm('Are you sure you want to remove titles?')" ><img src="img/remove.png" width="30%" height="30%" /></a></td>
                                       </tr>
                               <?php } ?>
                                </tbody>
                            </table>
                        <a href="allocate.php"><img src="img/check.png" />Allocate More</a>
                        <a href="home.php"><img src="img/home2.png" alt="Home">Back to Home</a>


            </div>
            </div>
            </div>
        </div>
        </div>


    </body>
</html>


