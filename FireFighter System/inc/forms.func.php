<?php
/**
* Creates a text field for the form based on parameters.
* @param string $id the value of the database field
* @param string $label the label for the text field
* @param integer $size the width of the text field for bootstrap
* @param string $value the value to give the text field
* @return the text field itself
*/
function createTextField($id, $label, $size, $value = "", $required = false) {
  //error handling - styles the text fields using Bootstrap if the $id field is equal to !missing! 
  $errorClass = null;
  $errorSpan = null;

  if ($_POST[$id] == "!missing!") {
    $errorClass = " has-error";
    $errorSpan = '<span class="help-block">Field must not be blank.</span>';
  }

  if ( $required ) {
	  if ($value == "") {
		$value = $_POST[$id];
		if ($value == "!missing!") {
		  $value = null;
		}
	  }
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
* Creates a general select list.
*
* Note that $value and $text must be the same length as $value contains the value properties
* for the option tags, and $text contains the text that will be displayed instead of the value.
*
* For example:
* $value:   $text:
* 1         Brown => <option value=1>Brown</option>
*
* Note that if an empty string is given for $text, it will use the values already given in
* $value by default.
* @param string $id the value of the database field
* @param string $label the label for the select list
* @param integer $size the width of the select list for bootstrap
* @param string[] $value an array of values for the select list option tags
* @param string[] $text an array of text strings that will be displayed with the option tag value 
*/
function createSelectList($id, $label, $size, $value = array(""), $text = "", $default = "") {
    if ( !$text ) $text = $value;
    $ret = '
     <div class="col-sm-'.$size.'"> 
      <div class="form-group'.$errorClass.'">
       <label class="control-label" for="'.$id.'">'.$label.'</label>
       '.$errorSpan.'
       <select class="form-control" id="'.$id.'" name="'.$id.'">';

    for ( $i = 0; $i < count($value); $i++ ) {
        if ( $default == $value[$i] ) {
            $ret .= '<option value="'.$value[$i].'" selected>'.$text[$i].'</option>';
        } else {
            $ret .= '<option value="'.$value[$i].'">'.$text[$i].'</option>';
        }
    }

    $ret .= '
        </select>
      </div>
     </div>';
    return $ret;
}

function createCheckBox($id, $value, $label, $checked = false) {
  $ret = '<div class="form-group">    <div class="checkbox-inline">
  <label class= "checkbox-inline">
  <input class="checkbox-inline" type="checkbox" name="'.$id.'" value="'.$value.'" name="'.$label.'"';

  if ( $checked ) {
    $ret .= ' checked';
  }

  $ret .= '>'.$label.'</label>  </div>  </div> ';

  return $ret;
}
  
?>