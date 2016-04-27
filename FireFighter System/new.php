<!DOCTYPE html>

<?php
require 'header.html';
require 'protect.php';

echo '<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assign Firefighter</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  </head>';

echo'<div class="container">
			<h1>Assign Firefighter</h1>
		
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table table-border">
				<thead>
						<tr>
							<th class="col-sm-3">Call Type</th>
							<th class="col-sm-3">Call Location</th>
							<th class="col-sm-6">Responders at Scene</th>
						</tr>
					</thead>
					<tbody>
						<tr class="danger">
							<td class="col-sm-3">Structure Fire</td>
							<td class="col-sm-3">515 Loudon Rd.</td>
							<td class="col-sm-6">Police, EMT, Ambulance</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">'
		;

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
	mysql_selectdb(DB_NAME,$dbh);
	
	$IDs = array();
	$IDs2 = array();
	
	if ( $_GET["action"] == "delete" ) {
		$query = "DELETE FROM Firefighter WHERE id='{$_GET["id"]}'";
		//echo $query;
		if ( !mysql_query($query) ) {
			die('Error: ' . mysql_error());
		}

	}
	
	// $result is a pointer into the server's internal memory
	$result = mysql_query("SELECT fname,lname, credentials FROM Firefighter WHERE type='I'");
	$result2 = mysql_query("SELECT fname,lname, credentials FROM Firefighter WHERE type='E'");
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
	
	
	echo '		<div class="col-sm-6">
			<div class="table-responsive">
				<h3>Interior Firefighters</h3>
				<table class="table table-hover"><tbody>';
	echo '<thead>
						<tr>
							<th class="col-sm-2">Name</th>
							<th class="col-sm-3">Credentials</th>
							<th class="col-sm-1">Select</th>
						</tr>
					</thead>';
					
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	  echo '<tr>';
	  echo '<td class="col-sm-3">'.$row['fname'].' '.$row['lname'].'</td>';
	  echo '<td class="col-sm-3">'.$row['credentials'].'</td>';
	  echo '<td class="col-sm-1"><input type="checkbox" name="selected"></td>';
	  echo '</tr>';
	}
	echo '</tbody></table>
			</div>
		</div>';
	
	echo '		<div class="col-sm-6">
			<div class="table-responsive">
				<h3>Exterior Firefighters</h3>
				<table class="table table-hover"><tbody>';
				
	echo '<thead>
						<tr>
							<th class="col-sm-2">Name</th>
							<th class="col-sm-3">Credentials</th>
							<th class="col-sm-1">Select</th>
						</tr>
					</thead>';
		
	while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
	  echo '<tr>';
	  echo '<td class="col-sm-3">'.$row['fname'].' '.$row['lname'].'</td>';
	  echo '<td class="col-sm-3">'.$row['credentials'].'</td>';
	  echo '<td class="col-sm-1"><input type="checkbox" name="selected"></td>';
	  echo '</tr>';
	}
	
	
	echo '</tbody></table>
			</div>
		</div>
		</div>';
	}
	
	
	echo '
	<!--
	<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assign Firefighter</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  </head>
  
  <body>
    <div class="container">
	<form>
		<h1>Assign Firefighter</h1>
		
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table table-border">
				<thead>
						<tr>
							<th class="col-sm-3">Call Type</th>
							<th class="col-sm-3">Call Location</th>
							<th class="col-sm-6">Responders at Scene</th>
						</tr>
					</thead>
					<tbody>
						<tr class="danger">
							<td class="col-sm-3">Structure Fire</td>
							<td class="col-sm-3">515 Loudon Rd.</td>
							<td class="col-sm-6">Police, EMT, Ambulance</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="col-sm-6">
			<div class="table-responsive">
				<h3>Interior Firefighters</h3>
				<table class="table table-hover">
				
					<thead>
						<tr>
							<th class="col-sm-2">Name</th>
							<th class="col-sm-3">Credentials</th>
							<th class="col-sm-1">Select</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-sm-2">Kristen Bossio</td>
							<td class="col-sm-3">EMT, Hazardous Materials</td>
							<td class="col-sm-1"><input type="checkbox" name="selected"></td>
						</tr>
						<tr>
							<td class="col-sm-2">Blake Edwards</td>
							<td class="col-sm-3">Hazardous Materials, Confined Space</td>
							<td class="col-sm-1"><input type="checkbox" name="selected"></td>
						</tr>
						<tr>
							<td class="col-sm-2">Eric Neuls</td>
							<td class="col-sm-3">Confined Space, Jaws of Life</td>
							<td class="col-sm-1"><input type="checkbox" name="selected"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	   
		<div class="col-sm-6">
			<div class="table-responsive">
				<h3>Exterior Firefighters</h3>
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="col-sm-2">Name</th>
							<th class="col-sm-3">Credentials</th>
							<th class="col-sm-1">Select</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-sm-2">Drew Pintus</td>
							<td class="col-sm-3">Jaws of Life, EMT</td>
							<td class="col-sm-1"><input type="checkbox" name="selected"></td>
						</tr>
						<tr>
							<td class="col-sm-2">Brian Sopok</td>
							<td class="col-sm-3">EMT, Confined Space</td>
							<td class="col-sm-1"><input type="checkbox" name="selected"></td>
						</tr>
						<tr>
							<td class="col-sm-2">Dr. Meg Fryling</td>
							<td class="col-sm-3">EMT, Hazardous Materials, Jaws of Life, Confined Space</td>
							<td class="col-sm-1"><input type="checkbox" name="selected"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div style="width:100%;">
		<label>Select a Firetruck:</label>
		 <select>
			<option>Select an Available Firetruck</option>
			<option>101 - Engine</option>
			<option>102 - Engine</option>
			<option>103 - Engine</option>
			<option>201 - Ladder</option>
			<option>301 - Utility</option>
			<option>401 - Rescue</option>
		</select> 
		</div>
		<br>
		<button type="submit" class="btn btn-success">Assign Firefighter(s) to Truck</button>
	</form>
	</div>

  </body>
  </html>
  -->';
  
?>