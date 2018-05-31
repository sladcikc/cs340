<!DOCTYPE html>
<!-- adapted from Activity 1 -->

<!-- Add user Info to Table Users -->

<?php
		$currentpage="Sign Up";
        include "pages.php";
		
?>
<html>
	<head>
		<title>Sign Up</title>
		<link rel="stylesheet" href="index.css">
	</head>
<body>


<?php
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }

	include "header.php";
    include 'connectvars.php'; 
    
	$dt = new DateTime();
	$dt->sub(new DateInterval('P1D'));
	$newd = $dt->format('Y-n-d');

    $filename = "./hhits_".$newd.".json";
    //$filename = "./ahits_".$newd.".json";
    echo $filename;
    $thing = file_get_contents($filename);
    $json = json_decode($thing, true);
    
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysql_error());
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

        
// See if username is already in the table
        foreach ($json as $row){
            $pid = $row['player_id'];
            $h = $row['hits'];
            $ab = $row['at_bats'];
            $avg = $row['average'];
            $th = $row['t_hits'];

        
		$queryIn = "SELECT * FROM player where player_id='$player_id'";
		$resultIn = mysqli_query($conn, $queryIn);
		if (mysqli_num_rows($resultIn)> 0) {
            $mssg ="Unable to update. Player not in table!";
            echo "<script type='text/javascript'>alert('$mssg');</script>";
        } 
        else{
                // attempt insert query 
                $query = "UPDATE player SET avg = '$avg', total_hits = '$th' WHERE player_id = '$pid';";
                    if(mysqli_query($conn, $query)){
                        $mssg =  "Updated successfully!";
                        echo "<script type='text/javascript'>alert('$avg');</script>";
                    } else{
                        echo "ERROR: Not able to execute $query. " . mysqli_error($conn);
                    }
                $pickQ = "UPDATE pick SET hit = 'Y' WHERE pick.date = '$newd' AND $pid = pick.player_id AND $h > 0;";
                    if(!mysqli_query($conn, $pickQ)){
                        $mssg = "Picks not updated!";
                    }
                $pickQQ = "UPDATE pick SET hit = 'N' WHERE pick.date = '$newd' AND $pid = pick.player_id AND $h = 0;";
                    if(!mysqli_query($conn, $pickQQ)){
                        $mssg = "Picks not updated!";
                    }
                }

        }
    }
// close connection
mysqli_close($conn);

?>
	<section>
    	<h2>Sign Up Page!</h2>
<div>
    <form method="post" id="addForm">
    <fieldset>
        <legend>Info:</legend>
        <p>
            <label for="Username">Username:</label>
            <input type="text" class="required" name="username" id="username">
        </p>
        <p>
            <label for="First Name">First Name:</label>
            <input type="text" class="required" name="firstName" id="firstName">
        </p>
        <p>
            <label for="Last Name">Last Name:</label>
            <input type="text" class="required" name="lastName" id="lastName">
        </p>
        <p>
            <label for="Age">Age:</label>
            <input type="number" min=1 max =200 class="optional" name="age" id="age">
        </p>

        <p>
            <label for="Email">Email:</label>
            <input type="email" class="required" name="email" id="email">
        </p>

        <p>
            <label for="Password"> Password:</label>
            <input type="password" class="required" name="password" id="password" placeholder="6-20chars">
    </fieldset>

        <p>
            <input type = "submit"  value = "Submit" />
            <input type = "reset"  value = "Clear Form" />
        </p>
    </form>
</div>
</body>
</html>
