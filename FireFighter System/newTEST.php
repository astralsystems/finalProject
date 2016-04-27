<?php
/**
* Displays available fire fighters and drop downs for available trucks
* and active calls. Active call information is displayed based on the
* call that is selected in the drop down. Once all is selected, trucks
* and fire fighters can be added to the call or reset. Error checking
* is implemented.
*/
require 'config.php';
require 'functions.php';
require 'inc/footer.func.php';

$title = 'Assign Firefighters';
$message = "";
$names = array();

if ( isset( $_POST['submit'] ) ) {
	/*if ( empty($_POST['selected']) && !empty($_POST['truck']) ) {
		$message = "Please select a firefighter";
		$message .= (empty($_POST['call']) ? " and an active call." : ".");
	} else if ( !empty($_POST['selected']) && empty($_POST['truck']) ) {
		$message = "Please select a firetruck";
		$message .= (empty($_POST['call']) ? " and an active call." : ".");
	} else if ( empty($_POST['selected']) && empty($_POST['truck']) ) {
		$message = "Please select a firefighter";
		$message .= (empty($_POST['call']) ? ", firetruck and an active call." : " and a firetruck.");
	} else if ( !empty($_POST['selected']) && !empty($_POST['truck']) && empty($_POST['call']) ) {
		$message = "Please select an active call.";
	} else {*/
	$call_result = mysql_query("SELECT C.ID, C.Time, C.Location FROM Calls C, Truck T WHERE T.id={$_POST['truck']} and T.callID = C.ID");
	$can_add = true;
	$call_info = array();
	while ( $call_row = mysql_fetch_assoc($call_result) ) {
		$call_info['Time'] = $call_row['Time'];
		$call_info['Location'] = $call_row['Location'];
		if ( $_POST['call'] != $call_row['ID'] ) {
			$can_add = false;
		}
	}

	if ( $can_add ) {
		$message = "The following firefighters have been added to truck #{$_POST['truck']}:<br>";

		$max_capacity = 0;
		$current_capacity = 0;
		$result = mysql_query("SELECT TT.maxcapacity, T.capacity FROM TruckType TT, Truck T WHERE T.id = {$_POST['truck']} AND T.type = TT.name");
		while ( $row = mysql_fetch_assoc($result) ) {
			$max_capacity = $row['maxcapacity'];
			$current_capacity = $row['capacity'];
		}

		if ( count($_POST['selected']) <= $max_capacity &&
			(count($_POST['selected']) + $current_capacity) <= $max_capacity ) {
			$current_capacity += count($_POST['selected']);
			foreach($_POST['selected'] as $id) {
				$query = "UPDATE Firefighter SET available=0, truck={$_POST['truck']} WHERE id={$id}";
				$result = mysql_query($query);
				if ( !result ) {
					echo mysql_error();
				}

				$query = "SELECT fname, lname FROM Firefighter WHERE id={$id}";
				$result = mysql_query($query);
				while ( $row = mysql_fetch_assoc($result) ) {
					$message .= "{$row['fname']} {$row['lname']}<br>";
				}
			}

			if ( $current_capacity >= $max_capacity )
				mysql_query("UPDATE Truck SET capacity={$current_capacity}, status='Unavailable', callID={$_POST['call']} WHERE id={$_POST['truck']}");
			else
				mysql_query("UPDATE Truck SET capacity={$current_capacity}, callID={$_POST['call']} WHERE id={$_POST['truck']}");
		} else {
			$message = "Cannot add more than {$max_capacity} firefighters on to this truck";
		}

	} else {
		$message = "This truck is already assigned to the call '{$call_info['Time']} - {$call_info['Location']}'";
	}
	//}
}

if ( !empty($_POST['reset']) ) {
	$query = "UPDATE Firefighter SET available=1, truck=NULL";
	mysql_query($query);
	mysql_query("UPDATE Truck SET capacity=0, status='Available', callID=NULL");
}

echo createHeader($title);

echo createCallBox();

echo displayTableInterior('Firefighter');
echo displayTableExterior('Firefighter');
echo '</div>';
echo '<div class="container">';
echo displayDropDown('Truck');

echo ' <div id="buttons">
		<button type="submit" class="btn btn-danger" name="reset" value="1">Reset</button>
		<button type="button" class="btn btn-success" name="submit" id="submit" onclick="validate()">Assign Firefighter(s) to Truck</button>
		</div>';

if ( !empty($message) ) {
	echo '<br><br><div id="message"><strong>'.$message.'</strong></div>';
}

echo '</div>';

echo '<div class="container" style="padding-top: 20px;"><div id="transit-wpr">
</div>
<div id="panel-wpr">
  <div id="info">
    <div>
      <h2>Driving directions</h2>
    </div>
    <div>
      <label>from:</label>
      <input class="input" id="from" value="515 Loudon Road, Latham, NY, USA">
    </div>
    <div>
      <label>to:</label>

      <input class="input" id="to" value="Albany">

    </div>
    <div style="display:none;">Depart at <select id="depart"></select></div>
    <div class="btn">
      <button id="go" type="button">Get Directions</button>
    </div>
  </div>
  <div id="map"></div>
  <div id="panel"></div>
</div></div>';

	echo '<div class="container" style="padding-top: 10px;">';
	echo createFooter('Astral Systems');
	echo '</div>';
?>
