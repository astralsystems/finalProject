<!DOCTYPE html>
<html>
<body>

<?php
/**
* Checks to see that the truck the user is trying to add fire fighters to
* still has seats.
*/
require("config.php");
$q = intval($_GET['q']);
$result = mysql_query("SELECT T.id, TT.maxcapacity, T.capacity, T.callID FROM TruckType TT, Truck T WHERE T.id = {$q} AND T.type = TT.name");


while ( $row = mysql_fetch_assoc($result) ) {
	$seats_left = $row['maxcapacity'] - $row['capacity'];
    echo "There are " . $seats_left . " seats left in this truck.<br>";
	
	$result2 = mysql_query("SELECT Time, Location FROM Calls WHERE ID = {$row['callID']}");
	while ( $row2 = mysql_fetch_assoc($result2) ) {
		echo "This truck is already assigned to the call '{$row2["Time"]} - {$row2["Location"]}'";
	}
}

?>
</body>
</html>