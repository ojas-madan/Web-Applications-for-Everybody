<?php // Do not put any HTML above this line 
	// index.php
	require_once "pdo.php";
	require_once 'util.php';

	session_start();

	// Retrieve the Profile from the database
	$stmt = $pdo->query("SELECT * FROM profile");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// Now it is time to produce output for this page
?>

<!DOCTYPE html>
<html>
<head>
<title>Ojas Madan's Resume Registry</title>
<?php require_once "head.php"; ?>


</head>
<body>

<div class="container">
<h1>Ojas Madan's Resume Registry</h1>
<?php 
	flashMessages();
    if(!isset($_SESSION['name'])){
	?> 
 
<p>
<a href="login.php">Please log in</a>
</p>
<!-- <p>Attempt to <a href="add.php">add data</a> without logging in</p> -->
<?php
		if(count($rows)>0){
		echo('<table border="1">'."\n");
		echo"<tr><th>Name</th>";
		echo"<th>Headline</th>";
		// echo"<th>Year</th>";
		// echo"<th>Mileage</th>";
		// echo"<th>Action</th>";
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">');
    echo(htmlentities($row['first_name']));
    echo ' ';
    echo(htmlentities($row['last_name']));
    echo '</a>';
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td></tr>\n");
    // echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>  ');
    // echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    // echo("</td></tr>\n");
}
    echo("</table>");

}
		else{
			echo"<p>No rows found</p>";
		}
 }
?>

<?php 
    if(isset($_SESSION['name'])){
		echo'<p><a href="logout.php">Logout</a></p>';
		if(count($rows)>0){
		echo('<table border="1">'."\n");
		echo"<tr><th>Name</th>";
		echo"<th>Headline</th>";
		// echo"<th>Year</th>";
		// echo"<th>Mileage</th>";
		echo"<th>Action</th>";
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">');
    echo(htmlentities($row['first_name']));
    echo ' ';
    echo(htmlentities($row['last_name']));
    echo '</a>';
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>  ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</td></tr>\n");
}
    echo("</table>");

}
		else{
			echo"<p>No rows found</p>";
		}
	
}

?>

<?php 
    if(isset($_SESSION['name'])){
    	echo('<p><a href="add.php">Add New Entry</a></p>');
		
}

?>
</div>
</body>
</html>
