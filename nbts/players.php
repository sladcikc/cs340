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
		$view = "CREATE VIEW playing_on AS SELECT name, avg, bats, position, status FROM player, game WHERE ((`game`.`date` = '2018-5-12') and ((`player`.`team_id` = `game`.`away_id`) or (`player`.`team_id` = `game`.`home_id`)))";
		$query = "SELECT name, avg, bats FROM playing_on where position !='P' and status = 'A' ";


	// Get results from query
		$val = mysql_query('select * from `playing_on`');

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
		$drop = "DROP VIEW playing_on";
		mysqli_query($conn, $drop);
		mysqli_free_result($result);
		mysqli_close($conn);
	?>
	</body>
</html>
