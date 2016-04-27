<?php
/**
* Allows user to add or modify fire fighters in the database.
*/
require 'header.html';
require 'protect.php';
require 'inc/forms.func.php';
require 'config.php';

echo '<div class = "container">';

/**
* Creates the form of text fields
*/
function createForm(){
	return '
	<form method="post" action="FireFighterForm.php?action=submit">
	<h1>Add Fire Fighter</h1>
	<div class="row">'.
	createTextField("fname", "First Name", 3).
	createTextField("lname", "Last Name", 3).
	createTextField("suffix", "Suffix", 2).
	createTextField("dob", "Date of Birth", 4).
	createSelectList("gender", "Gender", 4, array("M","F","Other"), array("Male","Female","Other")).
	createSelectList("rank", "Rank", 4, array("Fire Captain","Firefighter","Captain"), 
										array("Fire Captain","Firefighter","Captain")).
	createSelectList("firefighter_type", "Type", 4, array("I","E"), array("Interior", "Exterior")).
	'<h4> Credentials </h4>'.
		createCheckBox("ff1", "", "FireFighter 1").
		createCheckBox("po", "", "Pump Ops").
		createCheckBox("ld", "", "Ladders").
		createCheckBox("cs", "", "Confined Space").
		createCheckBox("hm", "", "Hazardous Materials").
		createCheckBox("ot", "", "Officer Training").
		createCheckBox("fp", "", "Fire Police").
		createCheckBox("jol", "", "Jaws of Life").
		createCheckBox("emt", "", "EMT").'
	
	</div> 
	<button type="submit" class="btn btn-success" id="add">Add</button>
	<button type="button" class="btn btn-danger" id="cancel">Cancel</button>
	</form>
	<script type="text/javascript">
      document.getElementById("cancel").onclick = function () {
        location.href = "FireFighter.php";
      };
      $(\'#dob\').datepicker({
      	format: "yyyy-mm-dd",
      	startDate: "1900-1-1",
      });  
	</script>';
}

//if the modify button is clicked
/**
* Creates form with pre-filled fields to modify fire fighters
*/
function createFormModify(){
	if (is_null($_GET['id'])) {
		echo "ERROR: No ID specified";
		exit();
	}

	//get the row from the database with the specified ID
	$query = "SELECT * FROM Firefighter INNER JOIN Credentials ON Firefighter.id = Credentials.id WHERE Firefighter.id={$_GET['id']}";
	$result = mysql_query($query);

	if ( !$result ) {
	    die('Invalid query: ' . mysql_error());
	} else {
		$row = mysql_fetch_assoc($result);
		return '
		<form method="post" action="FireFighterForm.php?action=submit_mods&id='.$_GET['id'].'">
		<h1>Modify Fire Fighter</h1>
		<div class="row">'.
		createTextField("fname", "First Name", 3, $row['fname']).
		createTextField("lname", "Last Name", 3, $row['lname']).
		createTextField("suffix", "Suffix", 2, $row['suffix']).
		createTextField("dob", "Date of Birth", 4, $row['dob']).
		createSelectList("gender", "Gender", 4, array("M","F","Other"), array("Male","Female","Other"), $row['gender']).
		createSelectList("rank", "Rank", 4, array("Fire Captain","Firefighter","Captain"), 
											array("Fire Captain","Firefighter","Captain"), $row['rank']).
		createSelectList("firefighter_type", "Type", 4, array("I","E"), array("Interior", "Exterior"), $row['type']).
			'<h4> Credentials </h4>'.
		createCheckBox("ff1", "", "FireFighter 1", $row['firefighter1']).
		createCheckBox("po", "", "Pump Ops", $row['pump_ops']).
		createCheckBox("ld", "", "Ladders", $row['ladders']).
		createCheckBox("cs", "", "Confined Space", $row['confined_space']).
		createCheckBox("hm", "", "Hazardous Materials", $row['hazmats']).
		createCheckBox("ot", "", "Officer Training", $row['officer_training']).
		createCheckBox("fp", "", "Fire Police", $row['fire_police']).
		createCheckBox("jol", "", "Jaws of Life", $row['jaws_of_life']).
		createCheckBox("emt", "", "EMT", $row['emt']).'

		</div> 
		<button type="submit" class="btn btn-success" id="add">Update</button>
		<button type="button" class="btn btn-danger" id="cancel" onclick="window.location:\'FireFighters.php\'">Cancel</button>
		</form>
		<script type="text/javascript">
	      	document.getElementById("cancel").onclick = function () {
	        	location.href = "FireFighter.php";
	      	};
	      	$(\'#dob\').datepicker({
	      		format: "yyyy-mm-dd",
	      		startDate: "1900-1-1",
	      	});
		</script>';
	}
}

/**
* Creates Astral Systems footer
*/
function createFooter($title) {
    $year = date('Y');
    return '
      <footer>Copyright '.$year.' '.$title.'</footer>
      </div><!-- /.container -->
    </body>
    </html>';
}

/**
* Checks to see which fields are blank and assigns those fields a value of !missing!.
* createTextField uses the !missing! value to display error messages.
* does not connect to database if a mandatory field is missing.
*/
function processForm($isAdding) {

	if ($_POST['fname'] == null || $_POST['lname'] == null || $_POST['rank'] == null || 
		$_POST['gender'] == null || $_POST['dob'] == null){

			if ($_POST['fname'] == null) {
				$_POST['fname'] = "!missing!";
			}
			if ($_POST['lname'] == null) {
				$_POST['lname'] = "!missing!";
			}
			if ($_POST['rank'] == null) {
				$_POST['rank'] = "!missing!";
			}
			if ($_POST['firefighter_type'] == null) {
				$_POST['firefighter_type'] = "!missing!";
			}
			if ($_POST['gender'] == null) {
				$_POST['gender'] = "!missing!";
			}
			if ($_POST['dob'] == null) {
				$_POST['dob'] = "!missing!";
			}
			if ($_POST['credentials'] == null) {
				$_POST['credentials'] = "!missing!";
			}

			if ($isAdding)
				return createForm();
		    else
		    	return createFormModify();
	} else {
		if ($isAdding) {
			$result = mysql_query("INSERT INTO `perm_astral`.`Firefighter` 
										( `fname`, `lname`, `suffix`, `rank`, `type`, `gender`, `dob`, `credentials`, `available`) 
										VALUES ( 
												'".$_POST['fname']."',
												'".$_POST['lname']."',
												'".$_POST['suffix']."',
												'".$_POST['rank']."',
												'".$_POST['firefighter_type']."',
												'".$_POST['gender']."',
												'".$_POST['dob']."',
												'test', 1)");
			$id = 0;
			$id_query = mysql_query("SELECT max(id) FROM Firefighter");
			$row = mysql_fetch_array($id_query);
			
			$query = "INSERT INTO `perm_astral`.`Credentials`
						 (`id`, `firefighter1`, `pump_ops`, `ladders`, `confined_space`, `hazmats`, `officer_training`, `fire_police`, `jaws_of_life`, `emt`)
						 VALUES ( {$row[0]},";

			$query .= (!isset($_POST["ff1"]) ? "0," : "1,");
			$query .= (!isset($_POST["po"]) ? "0," : "1,");
			$query .= (!isset($_POST["ld"]) ? "0," : "1,");
			$query .= (!isset($_POST["cs"]) ? "0," : "1,");
			$query .= (!isset($_POST["hm"]) ? "0," : "1,");
			$query .= (!isset($_POST["ot"]) ? "0," : "1,");
			$query .= (!isset($_POST["fp"]) ? "0," : "1,");
			$query .= (!isset($_POST["jol"]) ? "0," : "1,");
			$query .= (!isset($_POST["emt"]) ? "0" : "1");
			
			$query .= ")";

		    if ( !mysql_query($query) ) echo('Invalid query: ' . mysql_error() + "<br>" + $query);
								  
						
		// modifying data otherwise
		} else {
			$result = mysql_query("UPDATE Firefighter SET fname = '{$_POST['fname']}',
														  lname = '{$_POST['lname']}',
														  suffix = '{$_POST['suffix']}',
														  rank = '{$_POST['rank']}',
														  type = '{$_POST['firefighter_type']}',
														  gender = '{$_POST['gender']}',
														  dob = '{$_POST['dob']}',
														  credentials =''
								   WHERE id = {$_GET['id']}"); 
			$query = "UPDATE Credentials SET
						firefighter1 = ";
			$query .= (!isset($_POST["ff1"]) ? "0," : "1,");

			$query .= "pump_ops = ";
			$query .= (!isset($_POST["po"]) ? "0," : "1,");

			$query .= "ladders = ";
			$query .= (!isset($_POST["ld"]) ? "0," : "1,");

			$query .= "confined_space = ";
			$query .= (!isset($_POST["cs"]) ? "0," : "1,");

			$query .= "hazmats = ";
			$query .= (!isset($_POST["hm"]) ? "0," : "1,");

			$query .= "officer_training = ";
			$query .= (!isset($_POST["ot"]) ? "0," : "1,");

			$query .= "fire_police = ";
			$query .= (!isset($_POST["fp"]) ? "0," : "1,");

			$query .= "jaws_of_life = ";
			$query .= (!isset($_POST["jol"]) ? "0," : "1,");

		    $query .= "emt = ";
			$query .= (!isset($_POST["emt"]) ? "0 " : "1 ");

			$query .= "WHERE id = {$_GET['id']}"; 

			if ( !mysql_query($query) ) echo('Invalid query: ' . mysql_error() + "<br>" + $query);
		}

		if (!$result) {
			echo "Could not enter data";
		}
		else {
			echo "Data entered successfully";
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