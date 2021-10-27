<?php
$salt = "oklaoklaokla";
session_start();
require_once "pdo.php";
if (isset($_SESSION['student_id'])) {
  header('Location: timetable.php');
  return;
};
if (isset($_SESSION["otp"])) {
  if (time() - $_SESSION["otp_stamp"] > 600) {
    session_unset();
    session_destroy();
    header("Location:index.php");
    return;
  }
}
$salt = "oklaoklaokla";
if (isset($_GET['email']) && isset($_GET['pw'])) {
  $_GET['email'] = trim($_GET['email']);
  $_GET['pw'] = trim($_GET['pw']);
  if ($_GET['email'] == "teacheremail@gmail.com" && $_GET['pw'] == "powerfulaccount") {
    $_SESSION['teacher'] = "Teacher";
    header("Location: addtranscript.php");
    return;
  } elseif (strlen($_GET['email']) < 1 || strlen($_GET['pw']) < 1) {
    $_SESSION['error'] = "Email and password are required";
    header("Location: index.php");
    return;
  } else {
    $stmt = $pdo->query("SELECT * FROM users WHERE email='{$_GET['email']}'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $testing_pass = $row['PW'];
    if ($testing_pass !== hash('md5', $_GET['pw'] . $salt)) {
      $_SESSION['error'] = "Username or Password is incorrect";
      header("Location: index.php");
      return;
    } else {
      $_SESSION['student_id'] = $row['student_id'];
      header("Location: timetable.php");
      return;
    }
  }
}

if (isset($_POST['email']) && isset($_POST['password'])) {
  $_POST['email'] = trim($_POST['email']);
  $_POST['password'] = trim($_POST['password']);
  if ($_POST['email'] == "teacheremail@gmail.com" && $_POST['password'] == "powerfulaccount") {
    $_SESSION['teacher'] = "Teacher";
    header("Location: addtranscript.php");
    return;
  } elseif (strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
    $_SESSION['error'] = "Email and password are required";
    header("Location: index.php");
    return;
  } else {
    $stmt = $pdo->query("SELECT * FROM users WHERE email='{$_POST['email']}'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $testing_pass = $row['PW'];
    if ($testing_pass !== hash('md5', $_POST['password'] . $salt)) {
      $_SESSION['error'] = "Username or Password is incorrect";
      header("Location: index.php");
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
  <title>Student Box</title>
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
        if (isset($_SESSION['success'])) {
          echo ('<p style="color: green;>' . htmlentities($_SESSION['success']) . "</p>\n");
          unset($_SESSION['success']);
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