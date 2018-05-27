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

		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}

		// query to select all information from supplier table
		//$today = date("Y-m-d");
		$day = "2018-5-12";
		if(isset($_POST['action'])) {

		}
		$team_ID = null;
 		if (!$team_ID) {
			$view = "CREATE VIEW playing_on AS SELECT name, avg, bats, position, status FROM player,
			game WHERE ((`game`.`date` = '2018-5-12') and ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`)))";
			$query = "SELECT distinct name, avg, bats FROM playing_on where position !='P' and status = 'A'  ";
			$val = mysql_query('select * from `playing_on`');

		}
		else {
			$view = "CREATE VIEW plays_on_team AS SELECT team_id, name, avg, bats, position, status FROM player,
			game WHERE ((`game`.`date` = '2018-5-12') and ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`)))";
			$query = "SELECT distinct name, avg, bats FROM plays_on_team where position !='P' and status = 'A' and team_id = $team_ID ";
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
		// get number of columns in table
		$fields_num = mysqli_num_fields($result);
		echo "<h1>Players:</h1>";
		// Select Element form input
		// When someone selects value, reload the page
		// then set the team id
		echo "
		<form method='GET' action='players.php'>
			<select name='team_ID'>
	  			<option value='110'>Baltimore Orioles</option>
	  			<option value='144'>Atlanta Braves</option>
			</select>
			<button type='submit'>Submit</button>
		</form>";

		echo "<table id='t01' border='1'><tr>";

		// printing table headers
		for($i=0; $i<$fields_num; $i++) {
			$field = mysqli_fetch_field($result);
			echo "<td><b>$field->name</b></td>";
		}
		echo "</tr>\n";
		while($row = mysqli_fetch_row($result)) {
			echo "<tr>";
			// $row is array... foreach( .. ) puts every element
			// of $row to $cell variable
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
