
	
<?php
require 'functions.php';
require 'inc/footer.func.php';
define('DB_SERVER','oraserv.cs.siena.edu');
define('DB_PORT','3306');
define('DB_USERNAME','perm_astral');
define('DB_PASSWORD','alien=abort-smash');
define('DB_NAME','perm_astral');
 
$dbh = mysql_connect(DB_SERVER.':'.DB_PORT,DB_USERNAME,DB_PASSWORD);
if (!$dbh) {
    echo "Oops! Something went horribly wrong.";
    exit();
}
else
{
		mysql_selectdb(DB_NAME,$dbh);
			
		$IDs = array();
		
		if ( $_GET["action"] == "delete" ) {
			$query = "DELETE FROM Truck WHERE id={$_GET["id"]}";
			if ( !mysql_query($query,$dbh) ) {
				die('Error: ' . mysql_error());
			}

		}
}
echo displayFiretrucks();
function displayFiretrucks() {
	$mysqli = databaseConnect();
	$query = "SELECT id, status, type FROM Truck";
	$result = $mysqli->query($query);
	$finfo = $result->fetch_fields();
	$output = '
	<div class = "container">
		<div class="col-sm-12">
			<div class="table-responsive">
				<h2 id="truckCenter">Firetrucks</h2>
				<table class="table table-hover">
				
					<thead>
						<tr>
							<th class="col-sm-3">ID</th>
							<th class="col-sm-3">Status</th>
							<th class="col-sm-3">Type</th>
							<th class="col-sm-3">Action</th>
						</tr>
					</thead>
					<tbody>';
	
	while ($row = $result->fetch_row()) {
		$output .= '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td>
				<td>'.$row[2].'</td>';
		
		$output .= '<td><a href="FireTruckForm.php?action=modify&id='.$row[0].'"><button type="submit" class="btn btn-primary buttonFire col-xs-4">Edit</button></a>
					<button type="submit" class="btn btn-danger buttonFire col-xs-4" onClick="confirmDelete('.$row[0].')">Delete</button>
					</td></tr>';
	}
	$output .= '</tbody></table>
				<a href="FireTruckForm.php"><div class="alignButton"><button type="submit" class="btn btn-success buttonEdit col-xs-6" style="float:inherit;padding:15px;">Add</button></a></div></div>';

	$result->free();
	$mysqli->close();
	return $output;
}	


		echo '<div class="container" style="padding-top: 10px;">';
		echo createFooter('Astral Systems');
		echo '</div>';
		
?>
</div>
<script>
function confirmDelete(id) {
	show_confirm("<h5><p>Are you sure you want to delete this truck?</p><p>(Truck ID: "+id+")</p></h5>",
				 function(){window.location="FireTruck.php?action=delete&id=" + id;});
  	/*var box = bootbox.dialog({
	    message: "<h5><p>Are you sure you want to delete this truck?</p><p>(Truck ID: "+id+")</p></h5>",
	    title: "Alert",
	    buttons: {
	    	ok: {
		        label: "Cancel",
	      	},
	      	cancel: {
	      		label: "Delete",
	      		className: "btn-danger",
	      		callback: function() {
        			window.location="FireTruck.php?action=delete&id=" + id;
      			}
	      	}
	    }
  	});
	box.css({
		'top': '50%',
		'margin-top': function () {
		  return -(box.height() / 2);
		}
	}); */
}
</script>
		
</body>
</html>
