<?php

/**
* This page allows you to click edit on the fire fighter page and a page with 
* all the sections will load with information able to be changed.
*
*@author Astral Systems
*/


/**
* Displays the header navigation bar for the application.
* @return the navigation bar.
*/
function createHeader() {
  return '
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>'.$title.'</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="container">';
    	
}	


/**
* Sets up the formatting of the ModifyFireFighter
* Adds a section for first name, last name, suffix, id, date of birth, gender, rank, firefighter type, credentials
* Also Adds the Buttons for adding the call or cancelling the call
*
* @return the text fields for the fire fighter.
*/

function createForm(){
	return '
   <form method="post" action="FireFighterForm.php?action=submit">
   <h1>Modify Fire Fighter</h1>
   <div class="row">'.
   createTextField("fname", "First Name", 12).
   createTextField("lname", "Last Name", 10).
   createTextField("suffix", "Suffix", 2).
   createTextField("firefighter_id", "ID", 4).
   createTextField("dob", "Date of Birth (yyyy-mm-dd)", 4).
   createTextField("gender", "Gender (M, F, Other)", 4).
   createTextField("rank", "Rank (Fire Captain, Firefighter, etc.)", 12).
   createTextField("firefighter_type", "Type (Exterior, Interior)", 12).
   createTextField("credentials", "Credentials", 12).'
   
   <button type="submit" class="btn btn-success" id="update">Update</button>
   <button type="submit" class="btn btn-danger" id="cancel">Cancel</button>
   
   </div> 
   </form>';
	
}

/**
* Puts a copyright at the bottom of the page with the current year after it
*
* @param string $title
* @return the Astral Systems footer.
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
* @param string $id the database field name
* @param string $label the text field label
* @param int $size the width of the text field for bootstrap.
* @return the text field based on the parameters.
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
echo createHeader();
if ($_GET['action']=="submit") {
  echo processForm();
}
else {
  echo createForm();
}
echo createFooter($title);

?>