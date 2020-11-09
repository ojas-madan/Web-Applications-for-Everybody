<?php
    session_start();
    require_once "pdo.php";
    if ( ! isset($_SESSION['name']) ) {
    die("Not logged in");
    }
// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) 
     && isset($_POST['summary'])) {
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        $_SESSION["error"] = "All fields are required";
         // header( 'Location: edit.php' ) ;
         // return;
    } else if ( strpos($_POST['email'],'@') == FALSE){
        $_SESSION["error"] = "Email must have an at-sign (@)";
            // header( 'Location: edit.php' ) ;
            // return;
    } else{

    $sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em,
            headline = :he, summary = :su
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
                  ':fn' => $_POST['first_name'],
                  ':ln' => $_POST['last_name'],
                  ':em' => $_POST['email'],
                  ':he' => $_POST['headline'],
                  ':su' => $_POST['summary'],
                  // ':user_id' => $_POST['user_id'],
                  ':profile_id' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}
}

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


$fname = htmlentities($row['first_name']);
$lname = htmlentities($row['last_name']);
$m = htmlentities($row['email']);
$hl = htmlentities($row['headline']);
$sum = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
// $user_id = $row['user_id'];

?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ojas Madan</title>
</head><body>
<div class="container">
<h1>Editing Profile for <?= $_SESSION['name'] ?></h1>

<?php
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" value="<?= $fname ?>"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= $lname ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $m ?>"></p>
<p>Headline:<br/>
<input type="text" name="headline" value="<?= $hl ?>"></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" ><?= $sum ?></textarea></p>
<p><input type="hidden" name="profile_id" value="<?= $profile_id ?>"></p>
<!-- <input type="hidden" name="user_id" value="<?= $user_id ?>"></p> -->

<p><input type="submit" value="Save"/>
<input type="submit" name="logout" value="Cancel">
</p>
</form>


