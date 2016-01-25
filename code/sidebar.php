<?
//
// sidebar.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// Side bar management page.
//

require_once("header.php");
?>
<div id="sidebar_edit" class="modal hide fade" tabindex="-1" role="dialog" style="width: 260px; margin-left: -130px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 id="myModalLabel">Edit Item</h3>
	</div>
	<div class="modal-body">
		<div style="display: none;" id="sidebar_edit_id"></div>
		<input type="text" id="sidebar_edit_title" placeholder="Title" /><br />
		<input type="text" id="sidebar_edit_url" placeholder="URL" /><br />
		<input type="text" id="sidebar_edit_order" placeholder="Order" /><br />
		<div style="display: none;" id="sidebar_edit_load"></div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-danger pull-left" data-dismiss="modal" id="sidebar_edit_delete">Delete</button>
		<button class="btn" data-dismiss="modal">Cancel</button>
		<button class="btn btn-primary" data-dismiss="modal" id="sidebar_edit_save">Save</button>
	</div>
</div>
<div id="sidebar_add" class="modal hide fade" tabindex="-1" role="dialog" style="width: 260px; margin-left: -130px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 id="myModalLabel">Create Item</h3>
	</div>
	<div class="modal-body">
		<input type="text" id="sidebar_add_title" placeholder="Title" /><br />
		<input type="text" id="sidebar_add_url" placeholder="URL" /><br />
		<input type="text" id="sidebar_add_order" placeholder="Order" /><br />
		<div style="display: none;" id="sidebar_add_load"></div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Cancel</button>
		<button class="btn btn-primary" data-dismiss="modal" id="sidebar_add_button">Add</button>
	</div>
</div>

<button class="btn btn-primary" id="add_sidebar_item">Add Item</button><br /><br />
<table class="table table-striped table-bordered table-hover" id="sidebar_list">
	<thead>
		<tr><th>#</th><th>Title</th><th>URL</th><th>Order</th></tr>
	</thead>
	<tbody>
		
	</tbody>
</table>
<script type="text/javascript">
function loadSidebar() {
	$("#sidebar_list tbody").load("<?=generateURL("api/sidebar/list")?>/");
}
$(document).ready(function() {
	$("#sidebar_list").on("click", "tbody tr", function() {
		$("#sidebar_edit_id").text($(this).find(".id").text());
		$("#sidebar_edit_title").val($(this).find(".title").text());
		$("#sidebar_edit_url").val($(this).find(".url").text());
		$("#sidebar_edit_order").val($(this).find(".order").text());
		$("#sidebar_edit").modal();
	});
	$("#sidebar_edit_save").click(function() {
		$("#sidebar_edit_load").load("<?=generateURL("api/sidebar/update")?>/", {id: $("#sidebar_edit_id").text(), title: $("#sidebar_edit_title").val(), url: $("#sidebar_edit_url").val(), order: $("#sidebar_edit_order").val()}, function(response, status, xhr) {
			loadSidebar();
		});
	});
	$("#sidebar_edit_delete").click(function() {
		$("#sidebar_edit_load").load("<?=generateURL("api/sidebar/delete")?>/", {id: $("#sidebar_edit_id").text()}, function(response, status, xhr) {
			loadSidebar();
		});
	});
	$("#add_sidebar_item").click(function() {
		$("#sidebar_add").modal();
	});
	$("#sidebar_add_button").click(function() {
		$("#sidebar_add_load").load("<?=generateURL("api/sidebar/add")?>/", {title: $("#sidebar_edit_title").val(), url: $("#sidebar_edit_url").val(), order: $("#sidebar_edit_order").val()}, function(response, status, xhr) {
			loadSidebar();
		});
	});
	loadSidebar();
});
</script>
<?
require_once("footer.php");
exit();
?>