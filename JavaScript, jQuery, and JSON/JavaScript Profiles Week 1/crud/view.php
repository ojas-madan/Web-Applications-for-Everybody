<?php
require_once "pdo.php";
session_start();
// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$fname = htmlentities($row['first_name']);
$lname = htmlentities($row['last_name']);
$m = htmlentities($row['email']);
$hl = htmlentities($row['headline']);
$sum = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
$user_id = $row['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ojas Madan's Profile View</title>
</head><body>
<div class="container">
<h1>Profile information</h1>
<p>First Name: <?= $fname ?> </p>
<p>Last Name: <?= $lname ?></p>
<p>Email: <?= $m ?></p>
<p>Headline:<br/> <?= $hl ?></p>
<p>Summary:<br/> <?= $sum ?><p>
</p>
<a href="index.php">Done</a>
</div>
</body>
</html>
