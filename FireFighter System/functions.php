<?php


/**
* These are all the functions in our system that are called from various other files
* 
* @author Astral Systems
*/

/**
*
* These files are required for functions.php
*/
require 'header.html';
require 'protect.php';

/**
* This connects our application to the phpmyadmin database.
*/

function databaseConnect() {
	$mysqli = new mysqli("oraserv.cs.siena.edu", "perm_astral", "alien=abort-smash", "perm_astral");
	if ($mysqli->connect_errno) {
		die("Database connection failed");
	}
	else {
		return $mysqli;
	}
}

/**
* Creates the Header
*
* @param string $title
*
* @return html
*/
function createHeader($title){
	$out = '
	<!--<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loudonville Fire Dept.</title>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">


<script src="https://raw.github.com/carhartl/jquery-cookie/master/jquery.cookie.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script src="js/prefixfree.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places,geometry"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="https://github.com/johan/railroad-diagrams/raw/gh-pages/kdtree.js"></script>
<script src="js/index.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<link rel="stylesheet" type="text/css" href="style.css">

<script type="text/javascript">
	window.onload=showCallInfoHeader();

	/**
	* Uses Ajax with get Truck information every time a new truck is selected
	*
	*@param string str
	*/
		function showTruckInfo(str) {
	    if (str == "") {
	        document.getElementById("truckinfo").innerHTML = "";
	        return;
	    } else { 
	        if (window.XMLHttpRequest) {
	            // code for IE7+, Firefox, Chrome, Opera, Safari
	            xmlhttp = new XMLHttpRequest();
	        } else {
	            // code for IE6, IE5
	            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	        }
	        xmlhttp.onreadystatechange = function() {
	            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	                document.getElementById("truckinfo").innerHTML = xmlhttp.responseText;
	            }
	        };
	        xmlhttp.open("GET","gettruckinfo.php?q="+str,true);
	        xmlhttp.send();
	    }
	}

	/**
	  * Uses Ajax with get Call information every time a new call is selected
	  *
	  *@param string str
	  */
	function showCallInfo(str) {
	    if (str == "") {
	        str = "default";
	    }

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("callinfo").innerHTML = xmlhttp.responseText;
				var el = document.createElement( \'html\' );
				el.innerHTML = xmlhttp.responseText;
				
				if ( str != "default" ) {
					document.getElementById("to").value = el.getElementsByTagName("td")[2].innerHTML;
					document.getElementById("go").click();
				}
			}
        };
        xmlhttp.open("GET","getcallinfo.php?q="+str,true);
        xmlhttp.send();
	}

	/**
	  * Uses Ajax with get Call Header information every time a new truck is selected
	  *
	  */
	function showCallInfoHeader()
	{
		if (window.XMLHttpRequest) {
	            // code for IE7+, Firefox, Chrome, Opera, Safari
	            xmlhttp = new XMLHttpRequest();
		} else {
	            // code for IE6, IE5
	            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	        xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	                document.getElementById("callinfo").innerHTML = xmlhttp.responseText;
			}
		};
		xmlhttp.open("GET","getcallinfo.php?q=default",true);
		xmlhttp.send();
	}

	function validate() {
		var msg = "Please select a ";
		var truck = document.getElementsByName("truck")[0].value;
		var call = document.getElementsByName("call")[0].value;

		// get number of selected firefighters
		var ffs = document.getElementsByName("selected[]");
		var numChecked = 0;
		for ( i = 0; i < ffs.length; i++ ) {
			if ( ffs[i].checked ) {
				numChecked++;
			}
		}

		if (numChecked < 1 && truck) {
			msg += "firefighter";
			msg += (!call ? " and an active call." : ".");
			show_alert(msg);
		} else if ( numChecked > 0 && !truck ) {
			msg += "firetruck";
			msg += (!call ? " and an active call." : ".");
			show_alert(msg);
		} else if ( numChecked < 1 && !truck ) {
			msg += "firefighter";
			msg += (!call ? ", firetruck and an active call." : " and a firetruck.");
			show_alert(msg);
		} else if ( numChecked > 0 && truck && !call ) {
			msg = "Please select an active call.";
			show_alert(msg);
		} else {
			// change button type to "submit" to complete the form
			var button = document.getElementById("submit");
			button.setAttribute(\'type\', \'submit\');
			button.click();
		}
	}
</script>
	</head>
  
    <div class="container">
		<h1>Assign Firefighter</h1>
		<ol>
			<li>Select an active call from the active calls drop down</li>
			<li>Select a firetruck to add to the active call from the firetruck drop down</li>
			<li>Select one or more fire fighters to add to that truck</li>
			<li id="last">Repeat steps 2-3 as necessary for more firetrucks and fire fighters</li>
		</ol>
		<form method="post" action="newTEST.php">';
		$out .= activeDrop();
		return $out;
	
}
/**
* Creates the drop down for active calls
*/
function activeDrop(){
$query20 = mysql_query("SELECT ID, Time, Location FROM Calls WHERE Active=1");
	$output2 .= '<div class="col-md-6">
		<label>Select an Active Call</label>
		 <select name="call" onchange="showCallInfo(this.value)" id="callsDrop">
			<option value="">Select an Available Call</option>';
			
	while ($row = mysql_fetch_array($query20, MYSQL_ASSOC)) {
		$output2 .= '<option value='.$row['ID'].'>'.$row["Time"].' - '.$row["Location"].'</option>';
	
	}
	$output2 .= '</select></div>';
	return $output2;
}

function archiveDrop(){
$query20 = mysql_query("SELECT ID, Time, Location FROM Calls WHERE Active=0");
	$output2 .= '<div class="col-md-6">
		<label>Select a Call from the Archive</label>
		 <select name="call" onchange="showCallInfo(this.value)" id="callsDrop">
			<option value="">Select an Available Call</option>';
			
	while ($row = mysql_fetch_array($query20, MYSQL_ASSOC)) {
		$output2 .= '<option value='.$row['ID'].'>'.$row["Time"].' - '.$row["Location"].'</option>';
	
	}
	$output2 .= '</select></div>';
	return $output2;
}
/**
* Sets up the formatting of the call box
* Adds a section for time, type, location, and information credentials
*
*@return html
*/
function createCallBox(){
	$ID = mysql_query("SELECT * FROM Calls WHERE ID = (SELECT MAX(ID) FROM Calls)");
	$row = mysql_fetch_array($ID, MYSQL_ASSOC);
	return '
	<div class="col-sm-12">
			<div id="callinfo" class="table-responsive">
				<!--<table class="table table-border">
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
								<td class="col-sm-2">'.$row["Location"].'</td>
								<td class="col-sm-6">'.$row["Information"].'</td>
							</tr>
						</tbody>
				</table>-->
			</div>
		</div>';
}


/**
* Puts a copyright at the bottom of the page with the current year after it
*
* @return html
*//*
function createFooter(){
	return '
	</div>
	</form>
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
		</body>
		</html>';
}

*/
/**
* Diplays all interior firefighters and information about them including credentials and select
*
*@ param string $table
*/
function displayTableInterior($table) {
	$mysqli = databaseConnect();
	$query = "SELECT * FROM Firefighter INNER JOIN Credentials ON Firefighter.id=Credentials.id WHERE Firefighter.type='I'";
	$result = $mysqli->query($query);
	$finfo = $result->fetch_fields();
	
	$output = '<div class="col-sm-6">
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
					<tbody>';
	while ($row = mysqli_fetch_assoc($result)) {
		
		$cred = "";
			if ($row['firefighter1']==1){
				$temp = "Fire Fighter 1, ";
				$cred .= $temp;
			}if ($row['pump_ops']==1){
				$temp = "Pump Ops, ";
				$cred .= $temp;
			}if ($row['ladders']==1){
				$temp = "Ladders, ";
				$cred .= $temp;
			}if ($row['confined_space']==1){
				$temp = "Confined Space, ";
				$cred .= $temp;
			}if ($row['hazmats']==1){
				$temp = "Hazmats, ";
				$cred .= $temp;
			}if ($row['officer_training']==1){
				$temp = "Officer Training, ";
				$cred .= $temp;
			}if ($row['fire_police']==1){
				$temp = "Fire Police, ";
				$cred .= $temp;
			}if ($row['jaws_of_life']==1){
				$temp = "Jaws of Life, ";
				$cred .= $temp;
			}if ($row['emt']==1){
				$temp = "EMT,";
				$cred .= $temp;
			}
			
			$cred = trim($cred, ' ');
			$cred = substr($cred, 0, -1);
		$output .= '<tr><td>'.$row['fname'].' '.$row['lname'].'</td>
				<td>'.$cred.'</td>';
				
		$output .= '<td class="col-sm-1"><input type="checkbox" name="selected[]" id="firefighter" value="'.$row['id'].'"></td></tr>';
	}
	$output .= '</tbody></table></div></div>';

	$result->free();
	$mysqli->close();
	return $output;
}

/**
* Diplays all exterior firefighters and information about them including credentials and select
*
*@param string $table
*/
function displayTableExterior($table) {
	$mysqli = databaseConnect();
	$query = "SELECT * FROM Firefighter INNER JOIN Credentials ON Firefighter.id=Credentials.id WHERE Firefighter.type='E'";
	$result = $mysqli->query($query);
	$finfo = $result->fetch_fields();
	
	$output = '<div class="col-sm-6">
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
					<tbody>';
	while ($row = mysqli_fetch_assoc($result)) {
		
		$cred = "";
			if ($row['firefighter1']==1){
				$temp = "Fire Fighter 1, ";
				$cred .= $temp;
			}if ($row['pump_ops']==1){
				$temp = "Pump Ops, ";
				$cred .= $temp;
			}if ($row['ladders']==1){
				$temp = "Ladders, ";
				$cred .= $temp;
			}if ($row['confined_space']==1){
				$temp = "Confined Space, ";
				$cred .= $temp;
			}if ($row['hazmats']==1){
				$temp = "Hazmats, ";
				$cred .= $temp;
			}if ($row['officer_training']==1){
				$temp = "Officer Training, ";
				$cred .= $temp;
			}if ($row['fire_police']==1){
				$temp = "Fire Police, ";
				$cred .= $temp;
			}if ($row['jaws_of_life']==1){
				$temp = "Jaws of Life, ";
				$cred .= $temp;
			}if ($row['emt']==1){
				$temp = "EMT,";
				$cred .= $temp;
			}
			
			$cred = trim($cred, ' ');
			$cred = substr($cred, 0, -1);
		$output .= '<tr><td>'.$row['fname'].' '.$row['lname'].'</td>
				<td>'.$cred.'</td>';
				
		$output .= '<td class="col-sm-1"><input type="checkbox" name="selected[]" id="firefighter" value="'.$row['id'].'"></td></tr>';
	}
	$output .= '</tbody></table></div></div>';

	$result->free();
	$mysqli->close();
	return $output;
}

/**
* Diplays a dropdown with the inputted params
*
*@param string $table
*
* @return html $output
*/
function displayDropDown($table){
	$mysqli = databaseConnect();
	$query = "SELECT id, type FROM {$table} WHERE status='Available'";
	$result = $mysqli->query($query);
	$finfo = $result->fetch_fields();
		
	$output = '<div class="row" id="truckoption">
					<div class="col-md-6">
					<label>Select a Firetruck:</label>
					<select name="truck" onchange="showTruckInfo(this.value)">
					<option value="">Select an Available Firetruck</option>';
	
	while ($row = $result->fetch_row()) {
		$output .= '<option value='.$row[0].'>'.$row[0].' - '.$row[1].'</option>';
	
	}
	
	$output .= '</select></div>';
	
	/*$query2 = mysql_query("SELECT ID, Time, Location FROM Calls WHERE Active=1");
	$output .= '<div class="col-md-6">
		<label>Select an Active Call</label>
		 <select name="call" onchange="showCallInfo(this.value)">
			<option value="">Select an Available Call</option>';
			
	while ($row = mysql_fetch_array($query2, MYSQL_ASSOC)) {
		$output .= '<option value='.$row['ID'].'>'.$row["Time"].' - '.$row["Location"].'</option>';
	
	}
	$output .= '</select></div>';*/
			
			
	$output .= '</div><br>
		<div id="truckinfo"></div>';
		

	$result->free();
	$mysqli->close();
	return $output;
}


?>