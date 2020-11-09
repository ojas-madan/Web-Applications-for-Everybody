<?php
    session_start();
    if ( ! isset($_SESSION['name']) ) {
    die("Not logged in");
}

require_once "pdo.php";

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
         header( 'Location: add.php' ) ;
         return;
    } else if ( strpos($_POST['email'],'@') == FALSE){
        $_SESSION["error"] = "Email must have an at-sign (@)";
            header( 'Location: add.php' ) ;
            return;
    }
     // else if (is_numeric($_POST['mileage']) < 1){
    //     $_SESSION["error"] = "Mileage must be an integer";
    //     header( 'Location: add.php' ) ;
    //     return;
    //      } 
    else { 
    $stmt = $pdo->prepare('INSERT INTO Profile
    (user_id, first_name, last_name, email, headline, summary)
    VALUES ( :uid, :fn, :ln, :em, :he, :su)');

    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary'])
    );
        $_SESSION['success'] = "Profile added";
        header("Location: index.php");
        return;
    }
 }


?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ojas Madan's Profile Add</title>
</head><body>
<div class="container">
<h1>Adding Profile for <?= $_SESSION['name'] ?></h1>

<?php

    if ( isset($_SESSION['error']) ) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']);
        }
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Cancel">
</p>
</form>
</div>
</body>
</html>