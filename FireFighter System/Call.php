<?php

/**
* This page allows the user to add a call after it is recieved and enter the information into the
* database so firefighters can be assigned to a given call
*
*@author Astral Systems
*/

/**
* These files are required files
*/
require 'header.html';
require 'protect.php';
require 'config.php';
require 'inc/forms.func.php';
echo '<div class = "container">';


/**
* Sets up the formatting of the Enter Call Page
* Adds a section for Call Type, Call Location, and Information
* Also Adds the Buttons for adding the call or cancelling the call
*
*@return html
*/
function createForm(){
	return '
	<form method="post" action="Call.php?action=submit">
	<h1>Enter Call Information</h1>
	<div class="row">'.
	//createTextField("call_type", "Call Type", 12, false).
	createSelectList("call_type", "Call Type", 12, array("Structure Fire", "Motor Vehicle Accident", "Hazardous Conditions", "Brush Fire", "Mutual Aid", "EMS"), "", "").
	//createTextField("call_loc", "Call Location", 8, false).
	createTextField("call_street", "Street Address", 5, false).
	createTextField("call_city", "City", 3, false).
	createTextField("call_state", "State", 2, false).
	createTextField("call_zip", "Zip Code", 2, false, "", false).
	createTextField("information", "Information", 12, false).'
	</div> 
	<button type="submit" class="btn btn-success" id="add">Add</button>
	<button type="button" class="btn btn-danger" id="cancel">Cancel</button>
	</form>
    <script type="text/javascript">
      document.getElementById("cancel").onclick = function () {
        location.href = "activecallslist.php";
      };
    </script>';
}

/**
* Puts a copyright at the bottom of the page with the current year after it
*
* @param string $title
* @return html
*/
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


/**
* A function where params can be passed and the function will create a text
* box with those specifications
*
* @param string $id
* @param string $label
* @param int $size
* @param boolean $isTextArea
*/
/*
function createTextField($id, $label, $size, $isTextArea, $value = "", $required) {
	//error handling - styles the text fields using Bootstrap if the $id field is equal to !missing! 
	if ( $required ) {
		$errorClass = null;
		$errorSpan = null;

		if ($_POST[$id] == "!missing!") {
			$errorClass = " has-error";
			$errorSpan = '<span class="help-block">Field must not be blank.</span>';
		}

		if ($value == "") {
			$value = $_POST[$id];
			if ($value == "!missing!") {
				$value = null;
			}
		}
	}
	
    $ret = '
     <div class="col-sm-'.$size.'">	
      <div class="form-group'.$errorClass.'">
       <label class="control-label" for="'.$id.'">'.$label.'</label>';

    if ( $isTextArea ) {
    	$ret .= '
       <textarea class="form-control" rows="5" id="'.$id.'" name="'.$id.'" value="'.$value.'"></textarea>'.$errorSpan.'';
    } else {
    	$ret .= '
       <input type="text" class="form-control" id="'.$id.'" name="'.$id.'" value="'.$value.'">'.$errorSpan.'';
    }

    $ret .= '
      </div>
     </div>';

    	return $ret;	
}

*/

/**
* checks to see which fields are blank and assigns those fields a value of !missing! 
* createTextField uses the !missing! value to display error messages
* does not connect to database if a mandatory field is missing 
*
* @param boolean $isAdding
*/
function processForm($isAdding) {
	$IDChange = mysql_query("SELECT MAX(ID) FROM Calls");
	date_default_timezone_set('America/New_York');
	$timestamp = date('Y-m-d g:i:s');
	$one = 1;
	
	if ($_POST['call_type'] == null || $_POST['call_street'] == null || $_POST['call_city'] == null ||
	    $_POST['call_state'] == null || $_POST['information'] == null){
			
			if ($_POST['call_type'] == null) {
				$_POST['call_type'] = "!missing!";
			}
			if ($_POST['call_street'] == null) {
				$_POST['call_street'] = "!missing!";
			}
			if ($_POST['call_city'] == null) {
				$_POST['call_city'] = "!missing!";
			}
			if ($_POST['call_state'] == null) {
				$_POST['call_state'] = "!missing!";
			}
			if ($_POST['information'] == null) {
				$_POST['information'] = "!missing!";
			}

			if ($isAdding)
				return createForm();
		    else 
		    	return createFormModify();
	} else {
		$query = "";
		if ($isAdding) {
			$address = "{$_POST['call_street']} {$_POST['call_city']} {$_POST['call_state']} {$_POST['call_zip']}";
			$query = "INSERT INTO `perm_astral`.`Calls` 
										(`ID`, `Type`, `Location`, `Information`, `Active`, `Time`, `on_scene`, `clear_scene`, `truck_left`, `injuries`) 
										VALUES ('".$IDinc."',
												'".$_POST['call_type']."',
												'".$address."',
												'".$_POST['information']."',
												'".$one."',
												'".$timestamp."',
												'".$timestamp."',
												'".$timestamp."',
												'".$timestamp."',
												'test')";
								$result = mysql_query($query);
		} 
		
		if (!$result) {
			echo "Could not enter data ";
			echo mysql_error();
			echo $query;
		}
		else {
			echo "Data entered successfully ";
			echo mysql_error();
			echo $query;
		}
				
		exit();
	}
}

/**
* Decided what to process depending on the get and adds the footer to the 
* bottom of the page no matter what
*
*@param string $title
*/
 	
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