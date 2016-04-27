<?php
/**
* Puts a copyright at the bottom of the page with the current year after it
*
* @param string $title
* @return html
*/
function createFooter($title) {
    $year = date('Y');
    return '
      <footer>Copyright '.$year.' '.$title.'</footer>';
}
?>