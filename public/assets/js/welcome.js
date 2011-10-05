$(function() {

	$("#status_accept_check").click(function(){
		if($(this).attr("checked")){
			$(".status_accept").show();
		}
		else{
			$(".status_accept").hide();
		}
	});

       $('.description').toggle();
       
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
