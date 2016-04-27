<?php

/**
* This connects our application to the phpmyadmin database.
*/

define('DB_SERVER','oraserv.cs.siena.edu');
define('DB_PORT','3306');
define('DB_USERNAME','perm_astral');
define('DB_PASSWORD','alien=abort-smash');
define('DB_NAME','perm_astral');
 
$dbh = mysql_connect(DB_SERVER.':'.DB_PORT,DB_USERNAME,DB_PASSWORD);
if (!$dbh) {
    echo "Oops! Something went horribly wrong.";
    exit();
}
else
{
    //This is just temporary    
    echo "<p>Successfully connected to MySQL</p>"; 

		mysql_selectdb(DB_NAME,$dbh);
		
		// $result is a pointer into the server's interal memory
		$result2 = mysql_query("SELECT * FROM Firefighter");
		$result = mysql_query("SELECT * FROM Firefighter");
		
		echo '<table style="border: 1px solid red">';
		
		$col = mysql_fetch_array($result2, MYSQL_ASSOC);
		echo "<tr>";
		foreach ($col as $key => $value) {
			echo "<th>".$key."</th>";
		}
		echo "</tr>";
		
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		  echo "<tr>";
		  foreach ($row as $key => $x)
		    echo "<td>$x</td>";
		  echo "</tr>";
		}
		echo "</table>";
		
		mysql_free_result($result);
		exit();
}

?>


?>