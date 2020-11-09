<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) 
     && isset($_POST['mileage'])) {
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION["error"] = "All fields are required";
         header( 'Location: add.php' ) ;
         return;
    } else if ( is_numeric($_POST['year']) < 1 ){
        $_SESSION["error"] = "Year must be an integer";
        header( 'Location: add.php' ) ;
        return;
    } else if (is_numeric($_POST['mileage']) < 1){
        $_SESSION["error"] = "Mileage must be an integer";
        header( 'Location: add.php' ) ;
        return;
    }

    $sql = "UPDATE autos SET make = :make, model = :model,
            year = :year, mileage = :mileage
            WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
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

$m = htmlentities($row['make']);
$mod = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mil = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ojas Madan's Autos Database</title>
</head><body>
<div class="container">
<p>Edit User</p>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $m ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $mod ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $y ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mil ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>

