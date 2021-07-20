<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['student_id'])) {
  die("Not logged in");
};
$stmt1 = $pdo->query("SELECT * FROM Students WHERE student_id='{$_SESSION['student_id']}'");
$student = $stmt1->fetch(PDO::FETCH_ASSOC);
$student_name = $student['FN'] . " " . $student['LN'];
if (isset($_POST['event']) && isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['notes'])) {
  if (strlen($_POST['event']) < 1) {
    $_SESSION['error'] = "Event is required";
    header('Location: addtb.php');
    return;
  } elseif ($_POST['enddate'] < $_POST['startdate']) {
    $_SESSION['error'] = "EndDate must be after StartDate";
    header('Location: addtb.php');
    return;
  } else {
    $_SESSION['success'] = "Record inserted";
    $stmt3 = $pdo->prepare("INSERT INTO timetable(Event, StartDate, EndDate, Notes, student_id) VALUES ( :ev, :sd, :ed, :no, :std)");
    $stmt3->execute(
      array(
        ':ev' => $_POST['event'],
        ':sd' => $_POST['startdate'],
        ':ed' => $_POST['enddate'],
        ':no' => $_POST['notes'],
        ':std' => $_SESSION['student_id']
      )
    );
    header('Location: timetable.php');
    return;
  }
};
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
        <div class="brandname"><?= htmlspecialchars($student_name) ?>'s<br> Utilities</div>
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
      <div id="timetableform">
        <?php
        if (isset($_SESSION['error'])) {
          echo ('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
          unset($_SESSION['error']);
        };
        if (isset($_SESSION['success'])) {
          echo ('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
          unset($_SESSION['success']);
        }
        ?>
        <form method="POST">
          <label for="event">Event </label>
          <input type="text" name="event" id="event" maxlength="128" class="textinput"><br />
          <label for="startdate">StartDate </label>
          <input type="date" name="startdate" id="startdate" value="2021-10-10" class="textinput"><br />
          <label for="enddate">EndDate </label>
          <input type="date" name="enddate" id="enddate" value="2021-10-10" class="textinput"><br />
          <label for="notes">Notes </label>
          <input type="text" name="notes" id="notes" maxlength="256" class="textinput"><br />
          <input type="submit" value="Add">
          <a href="timetable.php">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</body>

</html>