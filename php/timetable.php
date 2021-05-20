<?php
session_start();
require_once "pdo.php";
if(!isset($_SESSION['student_id'])){
  $_SESSION['error']="Please Log In";
  header('Location: login.php');
};
$stmt1=$pdo->query("SELECT * FROM Students WHERE student_id='{$_SESSION['student_id']}'");
$student=$stmt1->fetch(PDO::FETCH_ASSOC);
$student_name=$student['FN']." ".$student['LN'];

?>














 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hungng Utilities</title>
    <?php require_once "css.php" ?>
</head>
<body>




<div class="screencomponent">
  <div class="navbar">
    <div class="fixed">
    <div class="brandname"><?=htmlentities($student_name) ?>'s<br> Utilities</div>
    <div class="menu">
    <div class="navbox">Profile</div>
    <div class="navbox">Academic Transcript</div>
    <div class="navbox">Time Table</div>
    <div class="navbox">Teachers' Contacts</div>
    <div class="navbox"><a href="logout.php" class="none">Log out</a></div>
    </div>
  </div>
  </div>
  <div class="main">
  <div class="article">Time Table</div>
  <div><a href="./addtb.php"><img src="https://icons-for-free.com/iconfiles/png/512/create+new+plus+icon-1320183284524393487.png" height="30px" width="30px"/></a></div>
  <?php
  if(isset($_SESSION['error'])){
   echo ('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
   unset($_SESSION['fail']);
  };
  if(isset($_SESSION['success'])){
   echo ('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
   unset($_SESSION['success']);
  }
  ?>
<div id="timetable">
<?php
$stmt2=$pdo->query("SELECT Event, StartDate, EndDate, Notes, tb_id FROM timetable WHERE student_id= '{$_SESSION['student_id']}' ORDER BY EndDate");
echo "<table>";
echo "<tr>";
echo "<th>"."Event"."</th>";
echo"<th>"."Start Date"."</th>";
echo"<th>"."End Date"."</th>";
echo"<th>"."Notes"."</th>";
echo"<th>"."Action"."</th>";
echo"</tr>";
while($row=$stmt2->fetch(PDO::FETCH_ASSOC)){
  echo "<tr>";
  echo "<td>".htmlentities($row['Event'])."</td>";
  echo "<td>".htmlentities($row['StartDate'])."</td>";
  echo "<td>".htmlentities($row['EndDate'])."</td>";
  echo "<td>".htmlentities($row['Notes'])."</td>";
  echo "<td>".'<a class="none" href="edittb.php?tb_id='.$row['tb_id']. '">Edit</a> /';
  echo'<a class="none" href="deltb.php?tb_id='.$row['tb_id'].'">Delete</a>'."</td>";
  echo "</tr>";
};
echo "</table>"
 ?>
</div>
</div>
</div>
</body>
</html>
