<!DOCTYPE html>
<html>
<body>

<?php
/**
* Displays the call information for the active call that is selected
* from the active calls drop down on the Truck Assignment page.
*/
require("config.php");

$q = $_GET['q'];
$a = $_GET['a'];

if ( $q == "default" ) {
echo '<table class="table table-border"><thead>
						<tr>
						    <th class="col-sm-2">Time</th>
							<th class="col-sm-2">Call Type</th>
							<th class="col-sm-2">Call Location</th>
							<th class="col-sm-6">Information</th>
						</tr>
					</thead>
					<tbody>
							<tr class="danger">
								<td class="col-sm-2">&nbsp;</td>
								<td class="col-sm-2">&nbsp;</td>
								<td class="col-sm-2" name="address">&nbsp;</td>
								<td class="col-sm-6">&nbsp;</td>
							</tr>
						</tbody>
				</table>';
die();
}
else if ( $q == "archive" ) {
echo '<table class="table table-border"><thead>
						<tr>
						    <th class="col-sm-2">Time of Call</th>
							<th class="col-sm-2">Time Left Station</th>
							<th class="col-sm-2">Time Arrived on Scene</th>
							<th class="col-sm-2">Time Cleared Scene</th>
						</tr>
					</thead>
					<tbody>
							<tr class="danger">
								<td class="col-sm-2">&nbsp;</td>
								<td class="col-sm-2">&nbsp;</td>
								<td class="col-sm-2">&nbsp;</td>
								<td class="col-sm-2">&nbsp;</td>
							</tr>
					</tbody>
	  </table>
			<p><h4><strong>Information</strong></h4></p>
			No call selected. 
			
	  		<p><h4><strong>Assigned Trucks and Firefighters</strong></h4></p>
	  		No call selected.

	  		<p><h4><strong>Injuries</strong></h4></p>
	  		No call selected.
	  ';
die();
}

// display call info from archive
if ( $a==true ) {
	$names = array();
	$result2 = mysql_query("select F.fname, F.lname From Firefighter F where F.id in (SELECT ff_id from Archive where call_id = {$q}");
	while ( $row = mysql_fetch_assoc($result2) ) {
		$names = $row;
		var_dump($names);
	}

	$result = mysql_query("SELECT C.ID, C.Time, C.Information, C.on_scene, C.truck_left, C.clear_scene, C.injuries FROM Calls C WHERE C.ID={$q}");

	while ( $row = mysql_fetch_assoc($result) ) {
		echo '<table class="table table-border">
					<thead>
							<tr>
							    <th class="col-sm-2">Time of Call</th>
								<th class="col-sm-2">Time Left Station</th>
								<th class="col-sm-2">Time Arrived on Scene</th>
								<th class="col-sm-2">Time Cleared Scene</th>

							</tr>
						</thead>
							<tbody>
								<tr class="danger">
									<td class="col-sm-2">'.$row["Time"].'</td>
									<td class="col-sm-2">'.$row["truck_left"].'</td>
									<td class="col-sm-2">'.$row["on_scene"].'</td>
									<td class="col-sm-2">'.$row["clear_scene"].'</td>
								</tr>
							</tbody>
					</table>
		  <p><h4><strong>Information</strong></h4></p>';
		  echo '<p>'.$row['Information'].'</p>';
		  
		  echo '<p><h4><strong>Assigned Trucks and Firefighters</strong></h4></p>';

		$trucks = mysql_query("SELECT DISTINCT truck_id FROM Archive WHERE call_id = {$q}");
		while ( $trow = mysql_fetch_assoc($trucks) ) {
			echo '<p style="margin-bottom: 2px;"><strong>Truck ID: '.$trow['truck_id'].'</strong></p>
				  <p style="border-top: 1.5px solid #ccc;">';

			$fresult = mysql_query("SELECT DISTINCT Firefighter.id, Firefighter.fname, Firefighter.lname FROM Archive INNER JOIN Firefighter ON Archive.ff_id = Firefighter.id WHERE Archive.truck_id = {$trow['truck_id']} and Archive.call_id = {$row['ID']}");
			while ( $frow = mysql_fetch_assoc($fresult) ) {
				echo "<a href='FireFighterForm.php?action=modify&id={$frow['id']}'>{$frow['fname']} {$frow['lname']}<br></a>";
			}
			echo '</p>';
		}
		echo '</p>';

		echo '<p><h4><strong>Injuries</strong></h4></p>';
		echo '<p>'.$row['injuries'].'</p>';
	}
}
// display call info from active calls list
else{
	$result = mysql_query("SELECT C.Time, C.Type, C.Location, C.Information FROM Calls C WHERE C.ID={$q}");


	while ( $row = mysql_fetch_assoc($result) ) {
		echo '<table class="table table-border">
					<thead>
							<tr>
							    <th class="col-sm-2">Time</th>
								<th class="col-sm-2">Call Type</th>
								<th class="col-sm-2">Call Location</th>
								<th class="col-sm-6">Information</th>
							</tr>
						</thead>
							<tbody>
								<tr class="danger">
									<td class="col-sm-2">'.$row["Time"].'</td>
									<td class="col-sm-2">'.$row["Type"].'</td>
									<td class="col-sm-2" id="address">'.$row["Location"].'</td>
									<td class="col-sm-6">'.$row["Information"].'</td>
								</tr>
							</tbody>
					</table>
					';
	}
}
?>
</body>
</html>