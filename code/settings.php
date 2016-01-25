<?
//
// settings.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// Settings management page.
//

require_once("header.php");
?>
<lable for="settings_email">Email Address:&nbsp;</label><input type="text" id="settings_email" value="<?=getSetting("email")?>" /> <span>The email address which emails (electronic mail (telegrams (long distance tranmission of messages)) messages) are sent from.</span><br />
<lable for="settings_replyToEmail">Reply to Address:&nbsp;</label><input type="text" id="settings_replyToEmail" value="<?=getSetting("replyToEmail")?>" /> <span>The email address which replies are sent to.</span><br />
<button class="btn btn-primary" id="settings_save">Save</button><br /><br />
<span id="settings_save_load">
<script type="text/javascript">
function loadUsers() {
	$("#users_list tbody").load("<?=generateURL("api/users/list")?>/");
}
$(document).ready(function() {

	$("#settings_save").click(function() {
		$("#settings_save_load").load("<?=generateURL("api/settings/save")?>/", {email: $("#settings_email").val(), replyToEmail: $("#settings_replyToEmail").val()});
	});
});
</script>
<?
require_once("footer.php");
exit();
?>