<?
//
// meetings.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// Meeting management page.
//

require_once("header.php");
if (!empty($_MGM['path'][1]) && intVal($_MGM['path'][1])!=0) {
	?>
	<span id="update_rsvp"></span>
	<table class="table table-striped table-bordered table-hover" id="meting_list">
		<thead>
			<tr><th>#</th><th>Date</th><th>Name</th><th>Contact</th><th>RSVP</th><th>Options</th></tr>
		</thead>
		<tbody>
		
		</tbody>
	</table>
	<script type="text/javascript">
	function loadMeeting() {
		$("#meting_list tbody").load("<?=generateURL("api/meetings/".$_MGM['path'][1]."/list")?>/", function(response, status, xhr) {
		
		});
	}
	$(document).ready(function() {
		$("#meting_list").on("click", ".going", function() {
			$("#update_rsvp").load("<?=generateURL("api/meetings/".$_MGM['path'][1]."/going")?>", {id: $(this).parent().parent().find(".id").text()}, function(response, status, xhr) {
				loadMeeting();
			});
		});
		$("#meting_list").on("click", ".maybe", function() {
			$("#update_rsvp").load("<?=generateURL("api/meetings/".$_MGM['path'][1]."/maybe")?>", {id: $(this).parent().parent().find(".id").text()}, function(response, status, xhr) {
				loadMeeting();
			});
		});
		$("#meting_list").on("click", ".not_attending", function() {
			$("#update_rsvp").load("<?=generateURL("api/meetings/".$_MGM['path'][1]."/not_attending")?>", {id: $(this).parent().parent().find(".id").text()}, function(response, status, xhr) {
				loadMeeting();
			});
		});
	
		loadMeeting();
	});
	</script>
	<?
} else {
?>
<div id="meeting_edit" class="modal hide fade" tabindex="-1" role="dialog" style="width: 260px; margin-left: -130px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 id="myModalLabel">Edit Meeting</h3>
	</div>
	<div class="modal-body">
		<div style="display: none;" id="meeting_edit_id"></div>
		<input type="text" id="meeting_edit_date" placeholder="Date" /><br />
		<input type="text" id="meeting_edit_location" placeholder="Location" />
		<span id="meeting_edit_load"></span>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Cancel</button>
		<button class="btn btn-primary" data-dismiss="modal" id="meeting_edit_save">Save</button>
	</div>
</div>
<div id="meeting_add" class="modal hide fade" tabindex="-1" role="dialog" style="width: 260px; margin-left: -130px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 id="myModalLabel">Add Meeting</h3>
	</div>
	<div class="modal-body">
		<input type="text" id="meeting_add_date" placeholder="Date" /><br />
		<input type="text" id="meeting_add_location" placeholder="Location" />
		<span id="meeting_add_load"></span>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Cancel</button>
		<button class="btn btn-primary" id="meeting_add_create">Add</button>
	</div>
</div>

<span class="pull-right">G = Going, M = Maybe, R = Responses</span>
<button class="btn btn-primary" id="add">Add Meeting</button>

<style type="text/css">
#meting_list .options {
	width: 240px;
}
</style>
<table class="table table-striped table-bordered table-hover" id="meting_list">
	<thead>
		<tr><th>#</th><th>Date</th><th>Location</th><th>RSVP</th><th>Options</th></tr>
	</thead>
	<tbody>
		
	</tbody>
</table>
<script type="text/javascript">
function loadMeetings() {
	$("#meting_list tbody").load("<?=generateURL("api/meetings/list")?>/", function(response, status, xhr) {
		
	});
}
$(document).ready(function() {
	$("#add").click(function() {
		$("#meeting_add").modal();
	});
	$("#meeting_add_create").click(function() {
		$("#meeting_add_load").load("<?=generateURL("api/meetings/add")?>/", {date: $("#meeting_add_date").val(), location: $("#meeting_add_location").val()}, function(response, status, xhr) {
			if ($("#meeting_add_load").text()=="Successfully Added.") {
				$("#meeting_add_date").val("");
				$("#meeting_add_location").val("");
				$("#meeting_add").modal("hide");
			}
			loadMeetings();
		});
	});
	
	$("#meting_list").on("click", ".edit", function() {
		$("#meeting_edit_id").text($(this).parent().parent().find(".id").text());
		$("#meeting_edit_date").val($(this).parent().parent().find(".date").text());
		$("#meeting_edit_location").val($(this).parent().parent().find(".location").text());
		$("#meeting_edit").modal();
	});
	$("#meeting_edit_save").click(function() {
		$("#meeting_edit_load").load("<?=generateURL("api/meetings/save")?>/", {id: $("#meeting_edit_id").text(), date: $("#meeting_edit_date").val(), location: $("#meeting_edit_location").val()}, function(response, status, xhr) {
			if ($("#meeting_edit_load").text()=="Successfully Saved.") {
				$("#meeting_edit").modal("hide");
			}
			loadMeetings();
		});
	});
	$("#meting_list").on("click", ".view", function() {
		window.location = "<?=generateURL("meetings/")?>"+$(this).parent().parent().find(".id").text();
	});
	$("#meting_list").on("click", ".rsvp", function() {
		window.location = "<?=generateURL("rsvp/")?>"+$(this).parent().parent().find(".id").text();
	});
	
	loadMeetings();
});
</script>
<?
}
require_once("footer.php");
exit();
?>