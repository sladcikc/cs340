<!DOCTYPE html>
<!-- Check username and password to attempt successfull login -->
<?php
		$currentpage="Log In";
		include "pages.php";
?>
<!-- Need to change input verification file.js -->
<html lang ="en">
	<head>
		<title>Log In</title>
		<link rel="stylesheet" href="index.css">
		<script type="text/javascript"  src="verifyinput.js" > </script>
	</head>
	<body>

	<?php
		// Including files to connect to DB
		include 'connectvars.php';
		include 'header.php';
//		session_start();
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if (!$db) {
			die('Could not connect: ' . mysql_error());
		}

		if($_SERVER["REQUEST_METHOD"] == "POST") {
	      // username and password sent from form
	      $username = mysqli_real_escape_string($db,$_POST['username']);
	      $password = mysqli_real_escape_string($db,$_POST['password']);

	      $sql = "SELECT * FROM Users WHERE username='$username'; ";
	      $result = mysqli_query($db,$sql);
				// If query worked we got the right row
				if($row = mysqli_fetch_assoc($result)) {
					// This function checks the password entered vs salted pass in DB
					$verify = password_verify($_POST['password'], $row['password']);
					// If it is a match display message, else error message.
					if ($verify) {
						echo "SUCCESS!";
					}
					else {
						echo "WRONG USERNAME OR PASSWORD.";
					}
				}
				else {
					echo "SORRY DOESN'T EXIST";
				}
	   }

	?>
		<section>
	    <h2> <?php echo $msg; ?> </h2>
			<!-- Form for user login -->
			<form role="form" method="post" action="login.php" id="addForm">
				<fieldset>
					<legend>Log In Info:</legend>
	    			<p>
	        		<label for="username">User Name:</label>
	        		<input type="text" class="required" name="username" id="username" required>
	    			</p>
						<p>
								<label for="Password">Password:</label>
								<input type="password" class="required" name="password" id="password" required>
						</p>
				</fieldset>
				<!-- Submit & Clear buttons -->
				<p>
	        <input type = "submit"  value = "Submit" />
	        <input type = "reset"  value = "Clear Form" />
	      </p>
			</form>
	</body>
</html>
