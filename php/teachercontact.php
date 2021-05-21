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

</div>
</div>
</body>
</html>
