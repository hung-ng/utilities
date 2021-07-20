<?php
session_start();
require_once "pdo.php";
if (isset($_SESSION['student_id'])) {
  header('Location: timetable.php');
};
$salt = "oklaoklaokla";
if (isset($_POST['email']) && isset($_POST['password'])) {
  if ($_POST['email'] == "teacheremail@gmail.com" && $_POST['password'] == "powerfulaccount") {
    $_SESSION['teacher'] = "Teacher";
    header("Location: addtranscript.php");
  } elseif (strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
    $_SESSION['error'] = "Email and password are required";
    header("Location: login.php");
    return;
  } else {
    $stmt = $pdo->query("SELECT * FROM Students WHERE email='{$_POST['email']}'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $testing_pass = $row['PW'];
    if ($testing_pass !== $_POST['password']) {
      $_SESSION['error'] = "Username or Password is incorrect";
      header("Location: login.php");
      return;
    } else {
      $_SESSION['student_id'] = $row['student_id'];
      header("Location: timetable.php");
      return;
    }
  }
}
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
  <div class="login-container">
    <div class="aside-right">
      <div class="header">
        <h3>Student Box</h3>
      </div>
      <div class="error">
        <?php
        if (isset($_SESSION['error'])) {
          echo ('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
          unset($_SESSION['error']);
        }
        ?>
      </div>
      <form method="POST" id="login-form">

        <div class="input-wrapper">
          <input type="email" name="email" placeholder="Email">
        </div>

        <div class="input-wrapper">
          <input type="password" name="password" placeholder="Password">
        </div>
        <div class="form-action">
          <span class="cursor-pointer">Don't have an account? <a href="register.php" class="none">Register</a></span>
        </div>
        <button class="btn" type="submit" value="Log in">
          Login
        </button>
      </form>
    </div>
  </div>
</body>

</html>