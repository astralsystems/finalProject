 
 <?php
 /**
 * @author Astral Systems
 * 
 * This page displays all of the active calls that are currently in the system.
 * Each call is displayed in panel form, allowing for all the trucks and
 * firefighters on each truck to drop down to be displayed. This page also has
 * functionality to end an active call which makes all of that call's trucks and
 * firefighters available.
 */
 
require 'header.html';
require 'protect.php';
require 'config.php'; 
require 'inc/footer.func.php';

	if ( $_GET["action"] == "reset" ) {
		$query = "UPDATE Firefighter SET available=1, truck=NULL WHERE id='{$_GET["id"]}'";
		$query2 = "SELECT capacity FROM Truck WHERE id='{$_GET["tid"]}'";
		$res2 = mysql_query($query2);
		$row = mysql_fetch_row($res2);
		$cap = $row[0];
		$newCap = $cap -1;
		$query3 = "UPDATE Truck SET capacity='".$newCap."' WHERE id='{$_GET["tid"]}'";
		mysql_query($query3);
		if ( !mysql_query($query) ) {
			die('Error: ' . mysql_error());
		}

	}
	
if ( $_GET["action"] == "end" ) {
		mysql_query("INSERT INTO `perm_astral`.`Archive` (`ff_id`, `call_id`, `truck_id`) SELECT F.id, T.callID, T.id FROM Firefighter F, Truck T WHERE F.truck = T.id and T.callID = {$_GET["id"]}");

		$query = "UPDATE Calls SET active=0, injuries='{$_GET["injuries"]}' WHERE ID='{$_GET["id"]}'";
		mysql_query($query);
		$query2 = "UPDATE Truck SET callID=NULL, capacity=0 WHERE callID='{$_GET["id"]}'";
		$query3 = "SELECT id FROM Truck WHERE callID='{$_GET["id"]}'";
		$truckID = mysql_query($query3);
		mysql_query($query2);
		while ($row = mysql_fetch_assoc($truckID)){
			$query4 = "UPDATE Firefighter SET available=1, truck=NULL WHERE truck='{$row['id']}'";
			mysql_query($query4);
		}
		if ( !mysql_query($query) ) {
			die('Error: ' . mysql_error());
		}
	}
	
if ( $_GET["action"] == "scene" ) {
		//$stateOn = " disabled";
		//$stateClear = " active";
		date_default_timezone_set('America/New_York');
		$timestamp = date('Y-m-d g:i:s');
		$query = "UPDATE Calls SET on_scene='$timestamp' WHERE ID='{$_GET["id"]}'";
		mysql_query($query);
		if ( !mysql_query($query) ) {
			die('Error: ' . mysql_error());
		}
	}
	
if ( $_GET["action"] == "clear" ) {
		date_default_timezone_set('America/New_York');
		$timestamp = date('Y-m-d g:i:s');
		$query = "UPDATE Calls SET clear_scene='$timestamp' WHERE ID='{$_GET["id"]}'";
		mysql_query($query);
		if ( !mysql_query($query) ) {
			die('Error: ' . mysql_error());
		}
	}

if ( $_GET["action"] == "left" ) {
		date_default_timezone_set('America/New_York');
		$timestamp = date('Y-m-d g:i:s');
		$query = "UPDATE Calls SET truck_left='$timestamp' WHERE ID='{$_GET["id"]}'";
		mysql_query($query);
		if ( !mysql_query($query) ) {
			die('Error: ' . mysql_error());
		}
	}
	
displayCalls();

/**
* This function displays all active calls on the page in panel form.
* This includes drop down panels for all trucks and firefighters on each call.
*/
function displayCalls() {
	//$stateOn = "";
	//$stateClear = " disabled";
	//$stateEnd = " disabled";
	$result = mysql_query("SELECT * FROM Calls WHERE Active=1");
	$id = 0;
	while ( $row = mysql_fetch_assoc($result) ) {
		echo'
			<div class="panel-group" id="accordion1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion1" href="#collapse'.$id.'"><div class="table-responsive">
					<div class="col-sm-2"><strong>Time - </strong>'.$row['Time'].'</div>
					<div class="col-sm-2"><strong>Type - </strong>'.$row['Type'].'</div>
					<div class="col-sm-3"><strong>Location - </strong>'.$row['Location'].'</div>
					<div class="col-sm-1 leftButton"><button type="button" class="btn btn-danger'.$stateEnd.'" name="end" onClick="confirmLeft('.$row['ID'].')">Truck Left Firehouse</button></div>
					<div class="col-sm-1"><button type="button" class="btn btn-danger'.$stateOn.'" name="scene" onClick="confirmScene('.$row['ID'].')">On Scene</button></div>
					<div class="col-sm-1 space"><button type="button" class="btn btn-danger'.$stateClear.'" name="clear" onClick="confirmClear('.$row['ID'].')">Clear Scene</button></div>
					<div class="col-sm-1"><button type="button" class="btn btn-danger'.$stateEnd.'" name="end" onClick="confirmEnd('.$row['ID'].')">End Call</button></div>
				</div>
							</a>
						</h4>
					</div>
					<div id="collapse'.$id.'" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="panel-body">
								<h4>Information</h4>
								'.$row['Information'].'
								<br><br>
								<h4>Fire Trucks</h4>';
												
		$trucks = mysql_query("SELECT * FROM Truck WHERE callID = {$row['ID']}");
		$truck_id = 0;
		while ( $trow = mysql_fetch_assoc($trucks) ) {
			echo '
									<div class="panel-group" id="accordion21">
										<div class="panel">
											<a data-toggle="collapse" data-parent="#accordion21" href="#collapse'.$id.''.$truck_id.'One">'.$trow['type'].'
											</a>
											<div id="collapse'.$id.''.$truck_id.'One" class="panel-collapse collapse">';
				$fresult = mysql_query("SELECT * FROM Firefighter WHERE truck = {$trow['id']}");
				while ( $frow = mysql_fetch_assoc($fresult) ) {
										$href = '#collapse'.$id.''.$truck_id.'One';
										
										echo'		<div class="panel-body">'.$frow['fname'].' '.$frow['lname'].
													'<button type="button" class="btn btn-danger resetFire" name="reset" value="'.$frow['id'].'" onClick="confirmReset('.$frow['id'].', '.$trow['id'].', \''.$href.'\')">Remove</button></div>';
				}
			echo '							</div>
										</div>
									</div>';
			$truck_id++;
		}
								
		echo '
							</div>
						</div>
					</div>
				</div>
			</div>
			';
		$id++;
	}

}

echo createFooter('Astral Systems');
?>	

<script>
$(document).ready(function() {
    var anchor = window.location.hash.replace("#", "");
    $(".collapse").collapse('hide');
    $("#" + anchor).collapse('show');
});
/*$(document).ready(function() {
    var last=$.cookie('activeAccordionGroup');
    if (last!=null) {
        //remove default collapse settings
        $("#accordion1 .collapse").removeClass('in');
        //show the last visible group
        $("#"+last).collapse("show");
    }
});*/

/**
*when a group is shown, save it as the active accordion group
*/
$("#accordion1").bind('shown', function() {
    var active=$("#accordion1 .in").attr('id');
    $.cookie('activeAccordionGroup', active)
});
/**
* Removes Firefighter on truck
*/
function confirmReset(id,tid,href = "") {
 	show_confirm("Remove this firefighter?\n(Firefighter ID: " + id + ")", function(){window.location="activecallslist.php?action=reset&id=" + id + "&tid=" + tid + href;});
}
/**
* Ends the selected call
*/
function confirmEnd(id) {
	show_end_call("End this call?\n(Call ID: " + id + ")", id);
}

function confirmScene(id) {
	show_confirm("Truck arrived on scene?\n(Call ID: " + id + ")", function(){window.location="activecallslist.php?action=scene&id=" + id});
}

function confirmClear(id) {
	show_confirm("Scene cleared?\n(Call ID: " + id + ")", function(){window.location="activecallslist.php?action=clear&id=" + id});
}

function confirmLeft(id) {
	show_confirm("Truck left the firehouse?\n(Call ID: " + id + ")", function(){window.location="activecallslist.php?action=left&id=" + id});
}
</script>	