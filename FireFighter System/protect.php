<?php
	/**
	* Checks to see if a user is logged in.
	*/
    require("config.php");
    
    // No user is logged in, so redirect to login page
    if ( empty($_SESSION['user']) ) { 
        $_SESSION['page_from'] = $_SERVER['PHP_SELF'];
        header("Location: login.php");
        die("Redirecting to login.php");
    }