<?php
    session_start();
if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}
require_once "pdo.php";

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

<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>


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

<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>
</body>