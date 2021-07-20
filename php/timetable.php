<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['student_id'])) {
  $_SESSION['error'] = "Please Log In";
  header('Location: login.php');
};
$stmt1 = $pdo->query("SELECT * FROM Students WHERE student_id='{$_SESSION['student_id']}'");
$student = $stmt1->fetch(PDO::FETCH_ASSOC);
$student_name = $student['FN'] . " " . $student['LN'];
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
        <div class="brandname"><?= htmlspecialchars($student_name) ?>'s<br> Box</div>
        <div class="menu">
          <div class="navbox1"><a href="profile.php" class="none">Profile</a></div>
          <div class="navbox1"><a href="academictranscript.php" class="none">Academic Transcript</a></div>
          <div class="navbox2"><a href="timetable.php" class="none">Time Table</a></div>
          <div class="navbox1"><a href="teachercontact.php" class="none">Teachers' Contacts</a></div>
          <div class="navbox1"><a href="logout.php" class="none">Log out</a></div>
        </div>
      </div>
    </div>
    <div class="main">
      <div class="article">Time Table</div>
      <div id="adddiv">
        <div><a href="./addtb.php"><img src="../img/Plus-Icon.png" height="30px" width="30px" /></a></div>
      </div>
      <div id="timetable">
        <?php
        if (isset($_SESSION['error'])) {
          echo ('<p style="color: red; margin-left: 140px">' . htmlentities($_SESSION['error']) . "</p>\n");
          unset($_SESSION['error']);
        };
        if (isset($_SESSION['success'])) {
          echo ('<p style="color: green; margin-left: 140px">' . htmlentities($_SESSION['success']) . "</p>\n");
          unset($_SESSION['success']);
        }
        ?>
        <?php
        $stmt2 = $pdo->query("SELECT Event, StartDate, EndDate, Notes, tb_id FROM timetable WHERE student_id= '{$_SESSION['student_id']}' ORDER BY EndDate");
        echo "<table>";
        echo "<tr>";
        echo "<th>" . "Event" . "</th>";
        echo "<th>" . "Start Date" . "</th>";
        echo "<th>" . "End Date" . "</th>";
        echo "<th>" . "Notes" . "</th>";
        echo "<th>" . "Action" . "</th>";
        echo "</tr>";
        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['Event']) . "</td>";
          echo "<td>" . htmlspecialchars($row['StartDate']) . "</td>";
          echo "<td>" . htmlspecialchars($row['EndDate']) . "</td>";
          echo "<td>" . htmlspecialchars($row['Notes']) . "</td>";
          echo "<td>" . '<a class="none" href="edittb.php?tb_id=' . $row['tb_id'] . '">Edit</a> /';
          echo '<a class="none" href="deltb.php?tb_id=' . $row['tb_id'] . '">Delete</a>' . "</td>";
          echo "</tr>";
        };
        echo "</table>"
        ?>
      </div>
    </div>
  </div>
</body>

</html>