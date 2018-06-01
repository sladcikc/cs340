<?php
	session_start();
	$user = $_SESSION['username'];
?>

<!DOCTYPE html>
<!-- List User Info from Users Table -->
<?php
		$currentpage="Teams";
		include "pages.php";
?>
<html>
	<head>
		<title>Teams</title>
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
		$query = "SELECT * FROM team";

	// Get results from query
		$result = mysqli_query($conn, $query);
		if (!$result) {
			die("Query to show fields from table failed");
		}
	// get number of columns in table
		$fields_num = mysqli_num_fields($result);
		echo "<h1>TEAMS:</h1>";
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
		mysqli_free_result($result);
		mysqli_close($conn);
	?>
	</body>
</html>
