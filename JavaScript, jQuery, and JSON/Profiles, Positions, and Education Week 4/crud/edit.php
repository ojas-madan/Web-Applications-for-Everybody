<?php
    require_once "pdo.php";
    require_once 'util.php';
    session_start();
 
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

    $msg = validateProfile();
    if ( is_string($msg) ) {
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=" . $REQUEST["profile_id"]);
        return;
    }

    // Validate position entries if present
    $msg = validatePos();
    if ( is_string($msg) ) {
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=" . $REQUEST["profile_id"]);
        return;
    } 

    // TODO: Should validate education;
    $msg = validateEdu();
    if ( is_string($msg) ) {
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=" . $REQUEST["profile_id"]);
        return;
    } 

    // Begin to update the data
    $sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em,
            headline = :he, summary = :su
            WHERE profile_id = :pid AND  user_id=:uid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
                  ':pid' => $_REQUEST['profile_id'],
                  ':uid' => $_SESSION['user_id'],  
                  ':fn' => $_POST['first_name'],
                  ':ln' => $_POST['last_name'],
                  ':em' => $_POST['email'],
                  ':he' => $_POST['headline'],
                  ':su' => $_POST['summary']
    ));

    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

    // Insert the position entries
    insertPosition($pdo, $_REQUEST['profile_id']);

    // Clear out the old education entries
    $stmt = $pdo->prepare('DELETE FROM education WHERE profile_id=:pid');
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

    // Insert the education entries
    insertEducations($pdo, $_REQUEST['profile_id']);

    $_SESSION['success'] = 'Profile updated';
    header( 'Location: index.php' ) ;
    return;

}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

// Load up the profile in question
$stmt = $pdo->prepare('SELECT * FROM profile where profile_id = :prof AND user_id = :uid');
$stmt->execute(array(':prof' => $_REQUEST['profile_id'], ':uid' => $_SESSION['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $profile === false ) {
    $_SESSION['error'] = "Could not load profile";
    header( 'Location: index.php' ) ;
    return;
}


$fname = htmlentities($profile['first_name']);
$lname = htmlentities($profile['last_name']);
$m = htmlentities($profile['email']);
$hl = htmlentities($profile['headline']);
$sum = htmlentities($profile['summary']);
$profile_id = $profile['profile_id'];
$user_id = $profile['user_id'];

// Load up the position rows 
$positions = loadPos($pdo, $_REQUEST['profile_id']);
$schools = loadEdu($pdo, $_REQUEST['profile_id']);
$countPos= count($positions);
$countEdu= count($schools);


?>

<!DOCTYPE html>
<html>
<head>
<title>Ojas Madan</title>
<?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
<h1>Editing Profile for <?= $_SESSION['name'] ?></h1>

<?php flashMessages(); ?>

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
<p>Education: <input type="submit" id="addEdu" value="+">
    <div id="edu_fields">
         <?php
            $rank = 1;
            foreach ($schools as $row) {
                echo "<div id=\"edu" . $rank . "\">
  <p>Year: <input type=\"text\" name=\"edu_year1\" value=\"".$row['year']."\">
  <input type=\"button\" value=\"-\" onclick=\"$('#edu". $rank ."').remove();return false;\"></p>
  <p>School: <input type=\"text\" name=\"edu_school1\" value=\"".$row['name']."\"></div>";
                $rank++;
            } ?>
    </div>
</p>
<p>Position: <input type="submit" id="addPos" value="+">
    <div id="position_fields">
         <?php
            $rank = 1;
            foreach ($positions as $row) {
                echo "<div id=\"position" . $rank . "\">
  <p>Year: <input type=\"text\" name=\"year1\" value=\"".$row['year']."\">
  <input type=\"button\" value=\"-\" onclick=\"$('#position". $rank ."').remove();return false;\"></p>
  <textarea name=\"desc". $rank ."\"').\" rows=\"8\" cols=\"80\">".$row['description']."</textarea>
</div>";
                $rank++;
            } ?>
    </div>
</p>


<p><input type="submit" value="Save"/>
<input type="submit" name="logout" value="Cancel">
</p>
</form>

<script>
countPos= <?= $countPos ?>;
countEdu= <?= $countEdu ?>; 


$(document).ready(function () {
            window.console && console.log('Document ready called');
            $('#addPos').click(function (event) {
            
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position " + countPos);
                $('#position_fields').append(
                    '<div id="position' + countPos + '"> \
            <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
            <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
            </div>');
            });

 $('#addEdu').click(function (event) {
                event.preventDefault();
                if (countEdu >= 9) {
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding education " + countEdu);

                $('#edu_fields').append(
                    '<div id="edu' + countEdu + '"> \
            <p>Year: <input type="text" name="edu_year' + countEdu + '" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu' + countEdu + '\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school' + countEdu + '" class="school" value="" />\
            </p></div>'
                );

                $('.school').autocomplete({
                    source: "school.php"
                });

            });

        });

    </script>
</div>
</body>
</html>


