window.onload = function(){recalcMap();}
	window.onresize = function(){recalcMap();}
	
	$(".percentages").hover(function(){
			$(".hideme").attr("style","display:none");
			$("#"+this.id+"ID").attr("style","display:block");
		}, 
		function(){
				$(".hideme").attr("style","display:none");
				$("#team").attr("style","display:block");
		});
	
	function recalcMap() {
		var width = $("#holder").width();
		var height = $("#holder").height();

		
		$("#sopok").attr("coords",""+(width*0.121)+","+(height*0.032)+","+(width*0.256)+","+(height*0.22));
		$("#pintus").attr("coords",""+(width*0.409)+","+(height*0.09)+","+(width*0.555)+","+(height*0.273));
		$("#neuls").attr("coords",""+(width*0.694)+","+(height*0.054)+","+(width*0.843)+","+(height*0.253));
		$("#edwards").attr("coords",""+(width*0.211)+","+(height*0.39)+","+(width*0.36)+","+(height*0.60));
		$("#bossio").attr("coords",""+(width*0.741)+","+(height*0.392)+","+(width*0.90)+","+(height*0.60));
	}
