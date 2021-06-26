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
    <div class="brandname"><?=htmlspecialchars($student_name) ?>'s<br> Utilities</div>
    <div class="menu">
    <div class="navbox1"><a href="profile.php" class="none">Profile</a></div>
    <div class="navbox2"><a href="academictranscript.php" class="none">Academic Transcript</a></div>
    <div class="navbox1"><a href="timetable.php" class="none">Time Table</a></div>
    <div class="navbox1"><a href="teachercontact.php" class="none">Teachers' Contacts</a></div>
    <div class="navbox1"><a href="logout.php" class="none">Log out</a></div>
    </div>
  </div>
  </div>
  <div class="main">
  <div class="article">Academic Transcript</div>
<?php
echo "<table>";
echo "<tr>";
echo "<th>"."Subject"."</th>";
echo "<th>"."Score"."</th>";
echo "</tr>";
echo "<tr>";
echo "<td>"."Mathematics"."</td>";
echo "<td>".htmlspecialchars($student['math_score'])."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>"."Mathematics"."</td>";
echo "<td>".htmlspecialchars($student['science_score'])."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>"."Mathematics"."</td>";
echo "<td>".htmlspecialchars($student['lit_score'])."</td>";
echo "</tr>";
echo "</table>"
 ?>
</div>
</div>
</div>
</body>
</html>
