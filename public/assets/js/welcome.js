$(function() {


	$("#status_accept_check").click(function(){
		if($(this).attr("checked")){
			$(".status_waiting_for_acceptance").show();
		}
		else{
			$(".status_waiting_for_acceptance").hide();
		}
	});
	$("#status_deploy_check").click(function(){
		if($(this).attr("checked")){
			$(".status_fixed_-_waiting_to_be_deployed").show();
		}
		else{
			$(".status_fixed_-_waiting_to_be_deployed").hide();
		}
	});
	$("#status_closed_check").click(function(){
		if($(this).attr("checked")){
			$(".status_closed").show();
		}
		else{
			$(".status_closed").hide();
		}
	});
	
	
	
	
   //$('.description').hide();
   
   $('#hide').click(function() {
       $('.description').slideToggle();
   });
	try{
		$("#mytable").tablesorter(
			{ 
  				sortList: [[4,1]],
				widgets: ["zebra"]
			}
		); 
	}catch(e){}
	$(".sortable th").addClass("sort_header");
	
    $(".row").mouseover(function(){
      $(this).addClass("over");
	  $("#tooltip").html($(this).find(".progress .closed").attr("width"));
    });

    $(".row").mouseout(function(){
      $(this).removeClass("over");
    });
 
	$(".row").click(function(){
		$("#hidden_"+this.id).toggle();
	});
	
	$("#versions").change(function(){
		window.location = "index.php?version="+this.value;
	});
	
	$(".closed").tooltip({
			tooltip: "#tooltip"
		});
	$(".closed").mouseout(function(){
		$(this).html("");
    });
   
});
