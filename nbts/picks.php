<?php
	session_start();
	$user = $_SESSION['username'];
?>

<!DOCTYPE html>

<?php
		$currentpage= $user."'s Picks";
		include "pages.php";
?>
<html>
	<head>
		<title><?php 			
			if(!empty($_SESSION['username'])){
				echo $user."'s Picks";}
				else{
					echo "How'd you get here?";}?></title>
		<link rel="stylesheet" href="index.css">
	</head>
	<body>

	<?php

		// change the value of $dbuser and $dbpass to your username and password
		// Including files for DB connection
		include 'connectvars.php';
		include 'header.php';

		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}

	// query to select all picks of a user from pick table
		$query = "SELECT  game.date, player.name, pick.hit, pick.player_id as image FROM pick 
				  JOIN game ON pick.game_id = game.game_id JOIN player ON pick.player_id = player.player_id 
				  JOIN leaderboard ON pick.username = leaderboard.username 
				  where pick.username = '$user'
				  ORDER BY game.date ASC";

	// get all game days from DB
		//$gamedays = "SELECT DISTINCT game.date FROM game, pick WHERE game.date !=pick.date AND '$user' = pick.username ORDER BY game.date DESC";

	// Get results from query
		$result = mysqli_query($conn, $query);
		//$gameR = mysqli_query($conn, $gamedays);
		if (!$result ) {
			die("Query to show fields from table failed");
		}
	// get number of columns in table
		$fields_num = mysqli_num_fields($result);
		
		if(!empty($_SESSION['username'])){
			echo "<h1>$user's Picks</h1>";
			echo "<table id='t01' border='1'><tr>";
			// printing table headers
			for($i=0; $i<$fields_num; $i++) {
				$field = mysqli_fetch_field($result);
				echo "<td><b>$field->name</b></td>";
			}
			echo "</tr>\n";
			
			while($row = mysqli_fetch_row($result)) {
				$date = $row[0];
				$name = $row[1];
				$hit = $row[2];
				$id = $row[3];
				
				echo "<tr>";

				echo "<td>$date</td>";
				echo "<td>$name</td>";
				echo "<td>$hit</td>";
				$url = "http://gdx.mlb.com/images/gameday/mugshots/mlb/".$id.".jpg";
				echo "<td><img src='$url'/></td>";
				echo "</tr>\n";
			}
			/* while($row = mysqli_fetch_row($gameR)) {
				$date = $row[0];
				$name = $row[1];
				$hit = $row[2];
				$id = $row[3];
				
				echo "<tr>";

				echo "<td>$date</td>";
				echo "<td>$name</td>";
				echo "<td>$hit</td>";

				echo "<td></td>";
				echo "</tr>\n";
			} */
		}
		else
		{
			echo "<h1>Nobody here but us chickens!</h1>";
		}
	


		// Free data and close the connection to DB
		mysqli_free_result($result);
		mysqli_close($conn);
	?>
	</body>
</html>
