<?php


/**
* This page allows you to click edit on the fire truck page and a page with 
* all the sections will load with information able to be changed.
*
*@author Astral Systems
*/



/**
*
* These files are required for ModifyFireTruck.php
*/

require 'header.html';
require 'protect.php';
echo '<div class = "container">';

/**
* Sets up the formatting of the ModifyFireTruck
* Adds a section for Type, Status, ID, Gall of Water, and Gallons per minute
* Also Adds the Buttons for adding the call or cancelling the call
*
* @return each text field for a fire truck.
*/
function createForm(){
	return '
   <form method="post" action="FireTruckForm.php?action=submit">
   <h1>Modify Fire Truck</h1>
   <div class="row">'.
   createTextField("type", "Type (Engine, Ladder, etc.)", 12).
   createTextField("status", "Status (Available, In Service, etc.)", 12).  
   createTextField("id", "ID Number", 4).
   createTextField("gow", "Gallons of Water", 4).
   createTextField("gpm", "Gallons per Minute", 4).'
   </div> 
    <button type="submit" class="btn btn-success" id="update">Update</button>
   <button type="submit" class="btn btn-danger" id="cancel">Cancel</button>
   </form>';
	
}

/**
* Puts a copyright at the bottom of the page with the current year after it
*
* @param string $title
* @return the Astral Systems footer
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
* @return text field based on the parameters.
*/
function createTextField($id, $label, $size) {
	//error handling - styles the text fields using Bootstrap if the $id field is equal to !missing! 
	$errorClass = null;
	$errorSpan = null;
	if($_POST[$id] == "!missing!"){
		$errorClass = " has-error";
		$errorSpan = '<span class="help-block">Field must not be blank.</span>';
	}
	
  return '
   <div class="col-sm-'.$size.'">	
    <div class="form-group'.$errorClass.'">
     <label class="control-label" for="'.$id.'">'.$label.'</label>
     <input type="text" class="form-control" id="'.$id.'" name="'.$id.'" value="'.$value.'">'.$errorSpan.'
    </div>
   </div>';	
}

/**
* Decided what to process depending on the get and adds the footer to the 
* bottom of the page no matter what
*
*@param string $title
*/

$title = 'Astral Systems';
//echo createHeader();
if ($_GET['action']=="submit") {
  echo processForm();
}
else {
  echo createForm();
}
echo createFooter($title);

?>