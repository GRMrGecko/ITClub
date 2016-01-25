<?
//
// rsvp.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// RSVP Center.
//

require_once("header.php");
if (!empty($_MGM['path'][1]) && intVal($_MGM['path'][1])!=0) {
	$meetings = databaseQuery("SELECT * FROM meetings WHERE id=%s", $_MGM['path'][1]);
	$meeting = databaseFetchAssoc($meetings);
	?>
	<style type="text/css">
	#rsvp_form {
		margin: 0 auto;
		width: 280px;
		padding: 20px;
		border-radius: 20px;
		background: #ffffff;
	}
	.rsvp_option {
		display: inline;
	}
	</style>
	<br />
	<div id="rsvp_form">
		<h4 style="text-align: center;">Meeting RSVP for<br /><?=date("l M j Y, h:i A", $meeting['date'])?><br /><?=$meeting['location']?></h4>
		<input type="text" placeholder="Your name" id="rsvp_name" /><br />
		<input type="text" placeholder="Your email or phone number" id="rsvp_contact" /><br />
		<input type="radio" name="rsvp_option" id="rsvp_option_0" value="0" checked />&nbsp;<label for="rsvp_option_0" class="rsvp_option">Attending</label><br />
		<input type="radio" name="rsvp_option" id="rsvp_option_1" value="1" />&nbsp;<label for="rsvp_option_1" class="rsvp_option">Maybe Attending</label><br />
		<input type="radio" name="rsvp_option" id="rsvp_option_2" value="2" />&nbsp;<label for="rsvp_option_2" class="rsvp_option">Not Attending</label><br />
		<button class="btn btn-primary pull-right" id="rsvp_submit">Submit</button><br />
		<span id="rsvp_load"></span>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#rsvp_submit").click(function() {
			$("#rsvp_load").text("");
			$("#rsvp_load").load("<?=generateURL("api/rsvp/".$_MGM['path'][1]."/submit")?>/", {name: $("#rsvp_name").val(), contact: $("#rsvp_contact").val(), choice: $("input:radio[name='rsvp_option']:checked").val()}, function(response, status, xhr) {
			
			});
		});
	});
	</script>
	<?
} else {
?>
<header class="content-header">
	<h1>
		Meetings RSVP
	</h1>
</header>
<p>
	Click or touch the meetings you will be attending and fill out the form.
</p>
<table class="table table-striped table-bordered table-hover" id="meting_list">
	<thead>
		<tr><th>#</th><th>Date</th><th>Location</th></tr>
	</thead>
	<tbody>
		
	</tbody>
</table>
<script type="text/javascript">
function loadMeetings() {
	$("#meting_list tbody").load("<?=generateURL("api/rsvp/list")?>/", function(response, status, xhr) {
		
	});
}
$(document).ready(function() {
	$("#meting_list").on("click", "tbody tr", function() {
		window.location = "<?=generateURL("rsvp/")?>"+$(this).find(".id").text();
	});
	
	loadMeetings();
});
</script>
<?
}
require_once("footer.php");
exit();
?>