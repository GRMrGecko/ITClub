<?
//
// members.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// Memeber management page.
//

require_once("header.php");
?>
<div id="entries_upload" class="modal fade" tabindex="-1" role="dialog" style="width: 270px; margin-left: -140px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Upload Entries</h3>
			</div>
			<div class="modal-body">
				<form>
					<input type="file" id="upload_files" />
				</form>
				<span id="entries_upload_load"></span>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-primary" id="entries_upload_create">Upload</button>
			</div>
		</div>
	</div>
</div>
<button class="btn btn-primary" id="upload">Upload updated list</button>
<button class="btn" id="download">Download as Spreadsheet</button>
<table class="table table-striped table-bordered table-hover" id="member_list">
	<thead>
		<tr><th>#</th><th>Name</th><th>Position</th><th>Phone</th><th>Email</th><th>Preferred Method</th></tr>
	</thead>
	<tbody>
		
	</tbody>
</table>
<script type="text/javascript">
function loadMembers() {
	$("#member_list tbody").load("<?=generateURL("api/members/list")?>/", function(response, status, xhr) {
		
	});
}
$(document).ready(function() {
	$("#download").click(function() {
		window.location = "<?=generateURL("api/members/download")?>";
	});

	$("#upload").click(function() {
		$("#entries_upload").modal();
	});
	$("#entries_upload_create").click(function() {
		$("#upload_files").attr("disabled", "true");
		$("#entries_upload_create").attr("disabled", "true");
		var file = $("#upload_files")[0].files[0];
		if (file.name==undefined) {
			alert("Error: Browser unsupported.");
			return;
		}
		var request = new XMLHttpRequest;
		request.onreadystatechange = function() {
			if (request.readyState==4) {
				$("#entries_upload_load").text(request.responseText);
				$("#upload_files")[0].form.reset();
				$("#upload_files").removeAttr("disabled");
				$("#entries_upload_create").removeAttr("disabled");
				loadMembers();
			}
		}
		request.open("post", "<?=generateURL("api/members/upload")?>", true);
		request.setRequestHeader("Cache-Control", "no-cache");
		request.setRequestHeader("X-FILENAME", file.name);
		request.setRequestHeader("Content-Type", "multipart/form-data");
		request.send(file);
	});
	loadMembers();
});
</script>
<?
require_once("footer.php");
exit();
?>