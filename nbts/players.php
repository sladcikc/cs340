<?php
	session_start();
	$user = $_SESSION['username'];
?>

<!DOCTYPE html>
<!-- List User Info from Users Table -->
<?php
		$currentpage="Players";
		include "pages.php";
?>
<html>
	<head>
		<title>Available Players</title>
		<link rel="stylesheet" href="index.css">
	</head>
	<body>

	<?php
		// change the value of $dbuser and $dbpass to your username and password
		// Including files for DB connection
		include 'connectvars.php';
		include 'header.php';
		// Make connection with DB
		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}

		$day = null;
		$team_ID = null;
		// Get the date if set
		if(isset($_GET['date'])) {
			$day = $_GET['date'];
		}
		// Get the team_ID if set
		if(isset($_GET['team_ID'])) {
			$team_ID = $_GET['team_ID'];
		}

		// If the team_ID is not set, then create a view for players that play that day
 		if (!$team_ID) {
			$view = "CREATE VIEW playing_on AS SELECT name, avg, bats, position, status
							FROM player, game
							WHERE ((`game`.`date` = '$day' ) and ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`)))";
			$query = "SELECT distinct name, avg, bats
								FROM playing_on where position !='P' and status = 'A'
								ORDER BY avg DESC";
			$val = mysql_query('select * from `playing_on`');
		}
		// if both are set then create a view that shows the teams that play that day
		elseif($team_ID && $day) {
			$view = "CREATE VIEW plays_on_team AS SELECT game_id, team_id, name, player_id, avg, bats, position, status
							FROM player, game
							WHERE ((`game`.`date` = '$day' ) and ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`)))";
			$query = "SELECT distinct name, avg, bats, player_id as mug, game_id as pick FROM plays_on_team
								WHERE position !='P' and status = 'A' and team_id = $team_ID
								ORDER BY avg DESC";
			$val = mysql_query('select * from `plays_on_team`');
		}
		// Else just create a view of players that play on a specific team
		else {
			$view = "CREATE VIEW plays_on_team AS SELECT team_id, name, avg, bats, position, status
							FROM player, game
							WHERE ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`))";
			$query = "SELECT distinct name, avg, bats
								FROM plays_on_team where position !='P' and status = 'A'
								and team_id = $team_ID
								ORDER BY avg DESC";
			$val = mysql_query('select * from `plays_on_team`');
		}
		// Get results from query
		if(!$val)
		{
			$make_view = mysqli_query($conn, $view);
			if (!$make_view){
				die($view);
			}
		}

		$result = mysqli_query($conn, $query);

		if (!$result) {
			die("Query to show fields from table failed");
		}

		echo "<h1>Players:</h1>";

		// Get team_name & team_id to populate the dropdown form
		$name_query = "SELECT team_id, team_name FROM team";
		$res = mysqli_query($conn, $name_query);
		// Dropdown menu populated with team names from team table in DB

		echo "
			<form method='GET' action='players.php'>
				<label for='date'>Choose By Team</label>
				<select name='team_ID'>
				<option disabled selected value> -- Select a Team -- </option>";
					while($row = $res->fetch_assoc()) {
						echo '<option value=" '.$row['team_id'].' "> '.$row['team_name'].' </option>';
					}
		echo "
				</select>
					<input type='date' id='date' name='date'>
					<button type='submit'>Submit</button>
				</form>";

		echo "<table id='t01' border='1'><tr>";


		// get number of columns in table
		$fields_num = mysqli_num_fields($result);
		// printing table headers
		// Don't show table header unless one var is set
		for($i=0; $i<$fields_num; $i++) {
			$field = mysqli_fetch_field($result);
			echo "<td><b>$field->name</b></td>";
		}
		echo "</tr>\n";


		// Fill the table with rows of players
/* 		action='picks.php' */
		echo "<form id='player_table' method='POST'  >";
		while($row = mysqli_fetch_row($result)) {
			echo "<tr>";
			$name = $row[0];
			$avg = $row[1];
			$bats = $row[2];
			$id = $row[3];
			$_SESSION['gameid'] = $row[4];

			echo "<tr>";
			echo "<td>$name</td>";
			echo "<input type='hidden' value='$name' name='picked' disabled='disabled'/>";
			echo "<td>$avg</td>";
			echo "<td>$bats</td>";
			$url = "http://gdx.mlb.com/images/gameday/mugshots/mlb/".$id.".jpg";
			echo "<td><img id='mug' src='$url'/></td>";
			#echo "<input type='hidden' name='picked' value='$id' />";
			echo "<td><input type='radio' name='picked' value='$id'</input></td>";
			echo "</tr>\n";
			echo "</tr>\n";

		}


		echo " <button type='submit'>Submit Pick</button>";
		var_dump($_POST);
		var_dump($_GET);
		$gameid = $_SESSION['gameid'];
		$pickID = $_POST['picked'];
		$day = $_GET['date'];

		echo $gameid." ";
		echo $pickID." ";
		echo $user." ";
		echo $day;

		echo "</form>";
		// Free data and close the connection to DB
		if (!$team_ID){
			$drop = "DROP VIEW playing_on";
		} else {
			$drop = "DROP VIEW plays_on_team";
		}
		$pq = "INSERT INTO pick VALUES($gameid, $pickID, '$user', '', '$day')";
		$re = mysqli_query($conn, $pq);

		mysqli_query($conn, $drop);
		mysqli_free_result($result);
		mysqli_close($conn);
	?>
	</body>
</html>
