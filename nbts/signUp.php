<!DOCTYPE html>
<!-- Add User Info to Table User -->
<!-- Borrowed code from activity-1-cs340 -->
<?php
		$currentpage="Sign Up";
		include "pages.php";

?>
<html>
	<head>
		<title>Sign Up</title>
		<link rel="stylesheet" href="index.css">
		<script type="text/javascript"  src="formValidate.js" > </script>
	</head>
	<body>


	<?php
		// change the value of $dbuser and $dbpass to your username and password
		include 'connectvars.php';
		include 'header.php';
		$msg = "Add a new user to the user table";

		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			// Escape user inputs for security
			$username = mysqli_real_escape_string($conn, $_POST['username']);
			$email = mysqli_real_escape_string($conn, $_POST['email']);
			$password = mysqli_real_escape_string($conn, $_POST['password']);

			// Hashing the password here
			// This functions autogenerates the SALT 
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
//			var_dump($hashed_password);


			// See if username is already in the table
			$queryIn = "SELECT * FROM user where username='$username' ";

			$resultIn = mysqli_query($conn, $queryIn);
			if (mysqli_num_rows($resultIn)> 0) {
				$msg ="<h2>Can't Add to Table</h2> There is already a user with username $username<p>";
			} else {
				// attempt insert query
				// Inserting the hashed_password instead of password???
				$query = "INSERT INTO user (username, email, password )
				VALUES ('$username', '$email', '$hashed_password' )";
				if(mysqli_query($conn, $query)){
					$msg =  "The user added successfully.<p>";
				} else{
					echo "ERROR: Could not execute $query. ". mysqli_error($conn);
				}
			}
	}
	// close connection
	mysqli_close($conn);

	?>
		<section>
	    <h2> <?php echo $msg; ?> </h2>

	<form method="post" id="addForm" >
	<fieldset>
		<legend>User Info:</legend>
	    <p>
	        <label for="username">User Name:</label>
	        <input type="text" class="required" name="username" id="username" required>
	    </p>
			<p>
					<label for="Email Address">Email Address:</label>
					<input type="email" class="required" name="email" id="email" required>
			</p>
			<p>
					<label for="Password">Password:</label>
					<input type="password" class="required" name="password" id="password" required>
			</p>
	</fieldset>
	      <p>
	        <input type="submit"  value="Submit" />
	        <input type="reset"  value="Clear Form" />
	      </p>
	</form>
	</body>
</html>
