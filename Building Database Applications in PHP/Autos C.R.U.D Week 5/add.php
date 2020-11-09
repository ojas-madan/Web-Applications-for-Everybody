<?php
    session_start();
    if ( ! isset($_SESSION['name']) ) {
    die("ACCESS DENIED");
}

require_once "pdo.php";

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}


// Check to see if we have some POST data, if we do process it
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
    } else {
        $sql = "INSERT INTO autos (make, model, year, mileage) 
              VALUES (:make, :model, :year, :mil)";
    // echo("<pre>\n".$sql."\n</pre>\n");
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mil' => $_POST['mileage']));
        $_SESSION['success'] = "Record added";
        header("Location: index.php");
        return;
    }
}


?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ojas Madan's Autos Database</title>
</head><body>
<div class="container">
<h1>Tracking Autos for <?= $_SESSION['name'] ?></h1>

<?php

    if ( isset($_SESSION['error']) ) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']);
        }
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="40"></p>
<p>Model:
<input type="text" name="model" size="40"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p><input type="submit" value="Add"/>
<input type="submit" name="logout" value="Cancel">
</p>
</form>

</div>
</body>