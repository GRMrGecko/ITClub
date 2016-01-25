<?
//
// announcements.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// This is the announcements management page.
//

require_once("header.php");
?>
<div id="announcement_add" class="modal hide fade" tabindex="-1" role="dialog" style="width: 260px; margin-left: -130px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 id="myModalLabel">Send Announcement</h3>
	</div>
	<div class="modal-body">
		<input type="text" id="announcement_add_subject" placeholder="Subject" /><br />
		<label for="announcement_add_message">Message</label>
		<textarea id="announcement_add_message" placeholder="Message"></textarea>
		<label for="announcement_add_smsmessage">SMS Message</label>
		<textarea id="announcement_add_smsmessage" placeholder="SMS Message" disabled></textarea>
		<br /><span id="announcement_add_sms_limit" class="pull-right">160</span>
		<span id="announcement_add_load"></span>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Cancel</button>
		<button class="btn btn-primary" id="announcement_add_create">Send</button>
	</div>
</div>

<button class="btn btn-primary" id="add">Send Announcement</button>
<table class="table table-striped table-bordered table-hover" id="announcement_list">
	<thead>
		<tr><th>#</th><th>Email</th><th>Subject</th><th>Message</th><th>SMS Message</th><th>Date</th></tr>
	</thead>
	<tbody>
		
	</tbody>
</table>
<script type="text/javascript">
function loadAnnouncements() {
	$("#announcement_list tbody").load("<?=generateURL("api/announcements/list")?>/", function(response, status, xhr) {
		
	});
}
$(document).ready(function() {
	$("#add").click(function() {
		$("#announcement_add").modal();
	});
	$("#announcement_add_create").click(function() {
		if ($("#announcement_add_smsmessage").val().length>160) {
			alert("SMS Message is too big, cannot send.");
		} else {
			$("#announcement_add_load").load("<?=generateURL("api/announcements/send")?>/", {subject: $("#announcement_add_subject").val(), message: $("#announcement_add_message").val(), smsmessage: $("#announcement_add_smsmessage").val()}, function(response, status, xhr) {
				if ($("#announcement_add_load").text()=="Successfully Sent.") {
					$("#announcement_add_subject").val("");
					$("#announcement_add_message").val("");
					$("#announcement_add_smsmessage").val("");
					$("#announcement_add").modal("hide");
				}
				loadAnnouncements();
			});
		}
	});
	
	loadAnnouncements();
});

$("#announcement_add_smsmessage").bind("input propertychange", function() {
	$("#announcement_add_sms_limit").text(160-$(this).val().length);
});
</script>
<?
require_once("footer.php");
exit();
?>