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

		// Get the date to display players that play on that date
		if(isset($_GET['date'])) {
			$day = $_GET['date'];
		} else {
			$day = null;
		}

		// Set the team ID to create the view
		$team_ID = null;
		if(isset($_GET['team_ID'])) {
			$team_ID = $_GET['team_ID'];
		} else {
			$team_ID = null;
		}
		// If the team_ID is not set then create a view and make it happen cap'n
 		if (!$team_ID) {
			$team_ID = null;
			$view = "CREATE VIEW playing_on AS SELECT name, avg, bats, position, status FROM player,
			game WHERE ((`game`.`date` = '$day' ) and ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`)))";
			$query = "SELECT distinct name, avg, bats FROM playing_on where position !='P' and status = 'A'  ORDER BY avg DESC";
			$val = mysql_query('select * from `playing_on`');
		}
		// Else team_ID is not set then create a view and make it happen cap'n
		else {
			$day = null;
			$view = "CREATE VIEW plays_on_team AS SELECT team_id, name, avg, bats, position, status FROM player,
			game WHERE ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`))";
			$query = "SELECT distinct name, avg, bats FROM plays_on_team where position !='P' and status = 'A'
								and team_id = $team_ID ORDER BY avg DESC";
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
					<button type='submit'>Submit</button>
				</form>";

		echo "<table id='t01' border='1'><tr>";
		echo "
		<form method='GET' action='players.php'>
  		<div>
    		<label for='date'>Choose By Date</label>
    		<input type='date' id='date' name='date'>
				<button type='submit'>Submit</button>
  		</div>
	  </form>";


		// get number of columns in table
		$fields_num = mysqli_num_fields($result);
		// printing table headers
		if($team_ID) {
			echo "<h2>$team_name</h2>";
		}
		for($i=0; $i<$fields_num; $i++) {
			$field = mysqli_fetch_field($result);
			echo "<td><b>$field->name</b></td>";
		}
		echo "</tr>\n";

		// Fill the table with rows of players
		while($row = mysqli_fetch_row($result)) {
			echo "<tr>";
			foreach($row as $cell)
				echo "<td>$cell</td>";
			echo "</tr>\n";
		}
		// Free data and close the connection to DB
		if (!$team_ID){
			$drop = "DROP VIEW playing_on";
		} else {
			$drop = "DROP VIEW plays_on_team";
		}
		mysqli_query($conn, $drop);
		mysqli_free_result($result);
		mysqli_close($conn);
	?>
	</body>
</html>
