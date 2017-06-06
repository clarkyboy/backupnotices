<?php
include ('none.php');
if(isset($_POST['submit']))
{
$name=mysql_real_escape_string($_POST['Name']);
$worked=mysql_real_escape_string($_POST['Worked']);
$hobb= $_POST['Hobbies'];
if(empty($hobb)) 
    {
        echo("<p>You didn't select any any hobby.</p>\n");
    } 
    else 
    {
       $N = count($hobb);
        echo("<p>You selected $N Hobbies(s): ");
        for($i=0; $i < $N; $i++)
        {
            $var1=$hobb[$i];
            add($name, $var1, $worked);  
        }

        echo "successfully uploaded!..";
      }
}
else
 {
echo "error please check ur code";
}