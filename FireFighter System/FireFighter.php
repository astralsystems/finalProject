<?php
/**
* @author Astral Systems
*
* This page displays all firefighters in the database categorized
* by interior and exterior. This page also has the functionality
* to edit each firefighter's information, delete each firefighter,
* and add a firefighter.
*/
require 'header.html';
require 'config.php';
require 'protect.php';
require 'inc/footer.func.php';

echo'
	<h1 style="text-align:center;">FireFighters Page</h1>
		<div class="row">'
		;

$IDs = array();
$IDs2 = array();

if ( $_GET["action"] == "delete" ) {
	// if firefighter is on a truck, update the truck's capacity 
	// accordingly and then remove the firefighter from that truck
	mysql_query("UPDATE Truck T SET T.capacity = T.capacity - 1
		 		 WHERE T.id IN ( SELECT truck FROM Firefighter WHERE id = '{$_GET["id"]}')");

	$query = "DELETE FROM Firefighter WHERE id='{$_GET["id"]}'";
	if ( !mysql_query($query) ) {
		die('Error: ' . mysql_error());
	}
}

if ( $_GET["action"] == "check" ) {
	$query = "UPDATE Firefighter SET checked=not checked WHERE id='{$_GET["id"]}'";
	if ( !mysql_query($query) ) {
		die('Error: ' . mysql_error());
	}

	// if checking out and firefighter is on a truck, update the truck's 
	// capacity accordingly and then remove the firefighter from that truck
	mysql_query("UPDATE Truck T SET T.capacity = T.capacity - 1
				 WHERE T.id IN ( SELECT truck FROM Firefighter WHERE id = '{$_GET["id"]}' and checked=0)");

	mysql_query("UPDATE Firefighter SET truck=NULL, available=1 WHERE id='{$_GET["id"]}' and checked=0");
}

$result = mysql_query("SELECT fname,lname,checked FROM Firefighter WHERE type='I'");
$result2 = mysql_query("SELECT fname,lname,checked FROM Firefighter WHERE type='E'");
$result3 = mysql_query("SELECT id FROM Firefighter WHERE type='I'");
$result4 = mysql_query("SELECT id FROM Firefighter WHERE type='E'");

while ($row = mysql_fetch_array($result3, MYSQL_ASSOC)) {
	  foreach ($row as $key => $a){
			array_push($IDs, $a);
	  }
	}

while ($row = mysql_fetch_array($result4, MYSQL_ASSOC)) {
	  foreach ($row as $key => $a){
			array_push($IDs2, $a);
	  }
	}







echo '<div class="container ">
<div class="table-responsive col-sm-6">
	<h2>Interior Firefighters</h2>
	<table class="table table-hover">

		<tbody>';
$i = 0;

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  //echo '<div class="checkbox aligncontent">';
    echo '<tr><td>'.$row["fname"].'</td>';

  

		echo '<td>'.$row["lname"].'</td>';
		echo '<td><a href="FireFighterForm.php?action=modify&id='.$IDs[$i].'"><button type="submit" class="btn btn-primary buttonFire col-xs-3">Edit</button></a>';
		echo '<button type="submit" class="btn btn-danger buttonFire col-xs-3" onClick="confirmDelete('.$IDs[$i].',\''.$row["fname"].'\',\''.$row["lname"].'\')">Delete</button>';
		if($row["checked"]==0){
			echo '<a href="FireFighter.php?action=check&id='.$IDs[$i].'"><button type="submit" class="btn btn-warning buttonFire col-xs-3">Absent</button></a></td>';
		}else{
			echo '<a href="FireFighter.php?action=check&id='.$IDs[$i].'"><button type="submit" class="btn btn-success buttonFire col-xs-3">Present</button></a></td>';
		}
		echo '</tr>';
		$i++;
  //echo "</div>";
  }

echo '</tbody></table></div>';

echo '<div class="table-responsive col-sm-6">
	<h2>Exterior Firefighters</h2>
	<table class="table table-hover">

		<tbody>';

$m = 0;
while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
  //echo '<div class="checkbox aligncontent">';
    echo '<tr><td>'.$row["fname"].'</td>';
		echo '<td>'.$row["lname"].'</td>';
		echo '<td><a href="FireFighterForm.php?action=modify&id='.$IDs2[$m].'"><button type="submit" class="btn btn-primary buttonFire col-xs-3">Edit</button></a>';
		echo '<button type="submit" class="btn btn-danger buttonFire col-xs-3" onClick="confirmDelete('.$IDs2[$m].',\''.$row["fname"].'\',\''.$row["lname"].'\')">Delete</button>';
		if($row["checked"]==0){
			echo '<a href="FireFighter.php?action=check&id='.$IDs2[$m].'"><button type="submit" class="btn btn-warning buttonFire col-xs-3">Absent</button></a></td>';
		}else{
			echo '<a href="FireFighter.php?action=check&id='.$IDs2[$m].'"><button type="submit" class="btn btn-success buttonFire col-xs-3">Present</button></a></td>';
		}
		echo '</tr>';
		$m++;
  //echo "</div>";
}
echo '</tbody></table></div></div>';

echo '<a href="FireFighterForm.php"><div class="alignButton"><button type="submit" class="btn btn-success buttonEdit col-xs-6" style="float:inherit;padding:15px;">Add</button></a></div>';

mysql_free_result($result);
//}
		echo '<div class="container" style="padding-top: 10px;">';
		echo createFooter('Astral Systems');
		echo '</div>';
?>
<script>
function confirmDelete(id, fname, lname) {
	show_confirm("<h5><p>Are you sure you want to delete firefighter <strong>"+fname+" "+lname+"</strong>?</p><p>(Firefighter ID: "+id+")</p></h5>",
				 function(){window.location="FireFighter.php?action=delete&id=" + id;});
}
</script>
</html>
