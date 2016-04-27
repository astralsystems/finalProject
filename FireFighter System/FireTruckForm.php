<?php
require 'header.html';
require 'protect.php';
require 'inc/forms.func.php';
require 'config.php';

echo '<div class = "container">';

function createForm() {
	return '
	<form method="post" action="FireTruckForm.php?action=submit">
	<h1>Add Fire Truck</h1>
	<div class="row">'.
	createSelectList("type", "Type", 3, array("Engine", "Ladder", "Rescue", "Utility")).
	createSelectList("status", "Status", 3, array("Available", "In Service")).
	createTextField("gow", "Gallons of Water", 3).
	createTextField("gpm", "Gallons per Minute", 3).'
	 </div> 
	<button type="submit" class="btn btn-success" id="add">Add</button>
	<button type="button" class="btn btn-danger" id="cancel">Cancel</button>
	</form>
	<script type="text/javascript">
      document.getElementById("cancel").onclick = function () {
        location.href = "FireTruck.php";
      };
	</script>';
	
}

function createFormModify() {
	if (is_null($_GET['id'])) {
		echo "ERROR: No ID specified";
		exit();
	}

	$query = "SELECT * FROM Truck WHERE id={$_GET['id']}";
	$result = mysql_query($query);

	if ( !$result ) {
	    die('Invalid query: ' . mysql_error());
	} else {
		$row = mysql_fetch_assoc($result);
		return '
		<form method="post" action="FireTruckForm.php?action=submit_mods&id='.$_GET['id'].'">
		<h1>Modify Fire Truck</h1>
		<div class="row">'.
		createSelectList("type", "Type", 3, array("Engine", "Ladder", "Rescue", "Utility"), "", $row['type']).
		createSelectList("status", "Status", 3, array("Available", "In Service"), "", $row['status']). 
		//createTextField("id", "ID Number", 4, $row['id']).
		createTextField("gow", "Gallons of Water", 3, $row['gow']).
		createTextField("gpm", "Gallons per Minute", 3, $row['gpm']).'
		</div> 
		<button type="submit" class="btn btn-success" id="add">Update</button>
		<button type="button" class="btn btn-danger" id="cancel">Cancel</button>
		</form>
		<script type="text/javascript">
     	document.getElementById("cancel").onclick = function () {
        	location.href = "FireTruck.php";
      	};
		</script>';
	}
}

function createFooter($title) {
    $year = date('Y');
    return '
    <footer>Copyright '.$year.' '.$title.'</footer>
    </div><!-- /.container -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
     </body>
    </html>';
}

function processForm($isAdding) {
	//checks to see which fields are blank and assigns those fields a value of !missing! 
	//createTextField uses the !missing! value to display error messages
	//does not connect to database if a mandatory field is missing 
	
	if ($_POST['type'] == null || $_POST['gow'] == null || $_POST['gpm'] == null || 
		$_POST['status'] == null) {
			
		/*if ($_POST['id'] == null) {
			$_POST['id'] = "!missing!";
		}*/
		if ($_POST['type'] == null) {
			$_POST['type'] = "!missing!";
		}
		if ($_POST['gow'] == null) {
			$_POST['gow'] = "!missing!";
		}
		if ($_POST['gpm'] == null) {
			$_POST['gpm'] = "!missing!";
		}
		if ($_POST['status'] == null) {
			$_POST['status'] = "!missing!";
		}
		
		if ($isAdding)
			return createForm();
		else 
		    return createFormModify();
	}
	else{	
		if ($isAdding) {
			$result = mysql_query("INSERT INTO `perm_astral`.`Truck` 
									(`type`, `gow`, `gpm`, `status`)  
										VALUES ( 
												'".$_POST['type']."',
												'".$_POST['gow']."',
												'".$_POST['gpm']."',
												'".$_POST['status']."')");
		} else {
			// if the POST id is different from the true id passed via HTTP 
			// queries, we must update the truck with the new id. But since
			// id is a primary key, we have to delete the record using the 
			// HTTP id and make a new record with its id set as the POST id
			/*if ($_POST['id'] != $_GET['id']) {
				$result = mysql_query("DELETE FROM Truck WHERE id = {$_GET['id']}");

				if (!$result) {
					echo mysql_error();
					exit();
				}
				
				$result = mysql_query("INSERT INTO `perm_astral`.`Truck` 
										(`id`, `type`, `gow`, `gpm`, `status`)  
											VALUES ('".$_POST['id']."', 
												'".$_POST['type']."',
												'".$_POST['gow']."',
												'".$_POST['gpm']."',
												'".$_POST['status']."')");
			} else {*/
				$result = mysql_query("UPDATE Truck SET type = '{$_POST['type']}',
														gow = '{$_POST['gow']}',
														gpm = '{$_POST['gpm']}',
														status = '{$_POST['status']}'
									   WHERE id = '{$_GET['id']}'");
			//}
		}

		if (!$result) {
			echo "Could not enter data";
		}
		else {
			echo "Data modified successfully";
		}
			
		exit();
	}  
}	

$title = 'Astral Systems';

if ($_GET['action'] == "submit") {
	echo processForm(true);
}
else if ($_GET['action'] == "submit_mods") {
	echo processForm(false);
}
else if ($_GET['action'] == "modify") {
  	echo createFormModify();
}
else {
  	echo createForm();
}

echo createFooter($title);

?>