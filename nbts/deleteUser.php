<!DOCTYPE html>
<!-- Add User Info to Table User -->
<!-- Borrowed code from activity-1-cs340 -->
<?php
		$currentpage="Delete User";
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
		$msg = "Delete a user from the user table";

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

			// See if username is already in the table
			$queryIn = "DELETE FROM user where username='$username' ";

			$resultIn = mysqli_query($conn, $queryIn);
			if (mysqli_num_rows($resultIn)> 0) {
				$msg ="<h2>Can't Delete from Table</h2> There is not a user with username $username<p>";
			} else {
				// attempt delete query
				// Deleting everything from the user input
				$query = "DELETE FROM user
				WHERE username='$username' and email='$email' and password='$hashed_password' ";
				if(mysqli_query($conn, $query)){
					$msg =  "The user was DELETED successfully.<p>";
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
