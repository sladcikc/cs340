<?php
    session_start();
    $_SESSION['id'] = "some_session";
?>
<!DOCTYPE html>

<?php
        $currentpage="Home";
        include "pages.php";
?>

<html>
    <head>
    <title>Home</title>
		<link rel="stylesheet" href="index.css">
    </head>
    <body>
        <section>
                <form>
                    <input type="button" value="Sign Up" onclick="window.location.href='./signUp.php'" />
                    <input type="button" value="Log In" onclick="window.location.href='./login.php'" />
                </form>
        </section>
    
    </body>
</html>