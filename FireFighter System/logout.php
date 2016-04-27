 <?php
	/**
	* Logs the user out of the application. Redirects the user
	* to the login page after logging out.
	*/
    require("config.php");
    
    // remove the user's data from the session
    unset($_SESSION['user']);
    unset($_SESSION['page_from']);
    
    // redirect user back to the login page
    header("Location: login.php");
    die("Redirecting to: login.php"); 