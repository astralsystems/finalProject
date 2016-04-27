<?php
require 'functions.php';
require 'inc/footer.func.php';

echo '<div class="container">';

echo archiveDrop();

echo '<script>
	window.onload=showCallInfoHeader();
/**
	  * Uses Ajax with get Call information every time a new call is selected
	  *
	  *@param string str
	  */
	function showCallInfo(str) {
	    if (str == "") {
	        str = "archive";
	    }

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("callinfo").innerHTML = xmlhttp.responseText;
				var el = document.createElement( \'html\' );
				el.innerHTML = xmlhttp.responseText;
				
				if ( str != "archive" ) {
					document.getElementById("to").value = el.getElementsByTagName("td")[2].innerHTML;
					document.getElementById("go").click();
				}
			}
        };
        xmlhttp.open("GET","getcallinfo.php?a=true&q="+str,true);
        xmlhttp.send();
	}
	
	/**
	  * Uses Ajax with get Call Header information every time a new truck is selected
	  *
	  */
	function showCallInfoHeader()
	{
		if (window.XMLHttpRequest) {
	            // code for IE7+, Firefox, Chrome, Opera, Safari
	            xmlhttp = new XMLHttpRequest();
		} else {
	            // code for IE6, IE5
	            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	        xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	                document.getElementById("callinfo").innerHTML = xmlhttp.responseText;
			}
		};
		xmlhttp.open("GET","getcallinfo.php?q=archive",true);
		xmlhttp.send();
	}
	</script>';
	
	echo '<div class="col-sm-12">
			<div id="callinfo" class="table-responsive">
				<!--<table class="table table-border">
				<thead>
						<tr>
						    <th class="col-sm-2">Time of Call</th>
							<th class="col-sm-2">Time Left Station</th>
							<th class="col-sm-2">Time Arrived on Scene</th>
							<th class="col-sm-6">Time Cleared Scene</th>
							<th class="col-sm-6">Fire Fighters</th>
							<th class="col-sm-6">Injuries</th>
						</tr>
					</thead>
						<tbody>
							<tr class="danger">
								<td class="col-sm-2">'.$row["Time"].'</td>
								<td class="col-sm-2">'.$row["Type"].'</td>
								<td class="col-sm-2">'.$row["Location"].'</td>
								<td class="col-sm-6">'.$row["Information"].'</td>
							</tr>
						</tbody>
				</table>-->
			</div>
		</div>
		</div>';
		
		echo '<div class="container" style="padding-top: 10px;">';
		echo createFooter('Astral Systems');
		echo '</div>';
?>
