<?php
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

$failure = false;
$success = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage'])) {
	if ( strlen($_POST['make']) < 1) {
        $failure = "Make is required";
    } else if (( is_numeric($_POST['year'])  && is_numeric($_POST['mileage'])) == FALSE){
    	$failure = "Mileage and year must be numeric";
    } else {
    	$sql = "INSERT INTO autos (make, year, mileage) 
              VALUES (:make, :year, :mil)";
    // echo("<pre>\n".$sql."\n</pre>\n");
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mil' => $_POST['mileage']));
    	$success ="Record inserted";
    }
}

$stmt = $pdo->query("SELECT * FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ojas Madan's Autos Database</title>
</head><body>
<div class="container">
<h1>Tracking Autos for csev@umich.edu</h1>
<!-- <p class="font-weight-bold">Tracking Autos for csev@umich.edu</p>  -->
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
else{
	echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
}
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="40"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p><input type="submit" value="Add"/>
<input type="submit" name="logout" value="Logout">
</p>
</form>

<h1>Automobiles</h1>
<?php
echo "<ul>";
foreach ( $rows as $row ) {
    // echo "";
    echo"<li>";
    echo(htmlentities($row['year'])); 
    echo " ";
    echo(htmlentities($row['make'])); 
    echo " ";
    echo(htmlentities($row['mileage']));
    echo"</li>";
    // echo("</td></tr>\n");
}
echo"</ul>";
?>
</div>
</body>