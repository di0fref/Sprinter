<!DOCTYPE html>
<html>
<head>
    <!--<meta charset="utf-8">-->
    <title>Sprinter</title>
	<link rel="stylesheet" href="assets/css/blue/style.css" type="text/css" media="print, projection, screen" />
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="print, projection, screen" />

       
	
    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
    <script src="assets/js/jquery.tablesorter.js"></script>

    <script type="text/javascript">
    $(function() {
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
		//  Adds sort_header class to ths
		$(".sortable th").addClass("sort_header");
		
		//  Adds "over" class to rows on mouseover
	    $(".row").mouseover(function(){
	      $(this).addClass("over");
	    });

	    //  Removes "over" class from rows on mouseout
	    $(".row").mouseout(function(){
	      $(this).removeClass("over");
	    });
	 
		$(".row").click(function(){
			$("#hidden_"+this.id).toggle();
		});
		
		$("#versions").change(function(){
			window.location = "index.php?version="+this.value;
		});
        
    });
    </script>
</head>
<body>
    <div id="wrapper">
        <h1>Sprinter</h1>
        <h2 class="first">Overall</h2>
        
        <table class='infobox'>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>Completed</strong></td>
                <td><strong>Remaining</strong></td>
                <td></td>
                <td><strong>Done</strong></td>
            </tr>
            <tr>
                <td><?php echo $estimated_hours ?></td>
                <td><?php echo $completed ?></td>
                <td><?php echo $remaining ?></td>
                <td></td>
                <td><?php echo $done_ratio ?>%</td>
            </tr>
        </table>
        
		<table cellspacing=5 border=0>
			<tr><td align="right" width="100%">Target Version:</td>
			<td align="right"><select name="versions" id="versions"><option> - </option>
				<?php foreach($versions as $key => $v){
					if($key == $version) $selected = "selected"; else $selected = "";
					echo "<option value='$key' $selected>$v</option>";
				}?>
			</select></td></tr>
		</table>

        <h2>Issues (<?php echo $current_target_version;?>)</h2>
        
        <table id="mytable" class="sortable tablesorter" cellspacing=0 cellpadding=0 border=0>
        <thead><tr>
            <th><strong>Id</strong></th>
            <th><strong>Subject</strong></th>
            <th><strong>Assignee</strong></th>
			<th nowrap><strong>Due date</strong></th>
            <th><strong>Estimated</strong></th>
            <th><strong>Done</strong></th>
			<th>Updated</th>
            <th><strong>Status</strong></th>

        </tr></thead>
        <?php foreach($issues as $issue): ?>
		<?php

		?>
        <tr class='<?php echo $issue["due_today"];?> row' id="<?php echo $issue['id'] ?>">
            <td class='id' valign="top"><a target='_blank' href="https://redmine.redpill-linpro.com/issues/<?php echo $issue['id'] ?>"><?php echo $issue['id'] ?></a></td>
            <td valign='top'><span class="subject"><?php echo $issue['subject'] ?></span><p class="hidden" id='<?php echo "hidden_".$issue["id"];?>'><?php echo "Author: ".$issue["author"]["name"]?><br><br><?php echo html_entity_decode($issue["description"])?></p></td>
            <td valign='top' nowrap><?php echo $issue['assigned_to'] ?></td>
            <td valign='top'><?php if(isset($issue["due_date"]))echo $issue["due_date"];?></td>
			<td valign='top'><?php echo $issue['estimated_hours'] ?></td>
            <td valign='top'>
				<table class="progress" cellpadding=0 cellspacing=0>
					<tr>
						<td class="closed" width="<?php echo $issue['done_ratio'] ?>%"></td></td>
						<td class="remaining" width="<?php echo 100-$issue['done_ratio'] ?>"</td></td>
					</tr>
				</table>
				
			<!--<?php echo $issue['done_ratio'] ?></td>-->
			<td nowrap><?php echo $issue['updated_on']?></td>
            <td valign='top' nowrap><?php echo $issue['status']['name'] ?></td>
        </tr>
        <?php endforeach ?>
        </table>
        <p class="footer">
            <a href="http://">Sprinter</a> is not released yet. 
            <a href="http://fuelphp.com">Fuel PHP</a> is released under the MIT license.<br />Page rendered in {exec_time}s using {mem_usage}mb of memory.
        </p>
    </div>
</body>
</html>