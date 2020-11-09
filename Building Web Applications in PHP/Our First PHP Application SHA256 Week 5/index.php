<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ojas Madan PHP</title>
</head>
<body>
	<h1>Ojas Madan PHP</h1>
	<p>The SHA256 hash of "Ojas Madan" is
		<?php
		print hash('sha256', 'Ojas Madan');
		?>
	</p>
	<pre>
ASCII ART:

    *******
    *     *
    *     *
    *     *
    *     *    
    *     * 
    *******		    
	</pre>
	<a href="check.php">Click here to check the error setting</a>
	<br/>
	<a href="fail.php">Click here to cause a traceback</a>
</body>
</html>