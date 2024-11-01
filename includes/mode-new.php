<ul id="admin-link-list">
	<li><a class="admin-link" href="<?php echo JSRM_SELF; ?>">List Menus</a></li>
</ul>
<h3>Create a New Menu</h3>

<?php
	$message = (isset($_GET['error']) && $_GET['error'] == "namefield") ?  "<span style='color:red;'>Please give your menu a Display Title.</span>" : "";
	echo $message; 
?>
<form class="menu-form" id="new-menu-form" action="<?php echo JSRM_SELF; ?>" method="post">
	<div class="form-row">
		<label for="menu-title-input">Menu Title</label>
		<input id="menu-title-input" type="text" name="name" />
	</div>
	<div class="form-row">
		<label for="menu-label-input">Label</label>
		<input id="menu-label-input" type="text" name="label" />
	</div>
	<div class="form-row">
		<label for="menu-description-input">Description</label>
		<textarea rows="3" name="desc" id="menu-description-input"/></textarea>
	</div>
	<p class="clear">
		<input type="submit" id="new-menu-submit" class="button-primary" name="dbtouch" value="Add Menu"/>
		<input type="submit" class="button-secondary" name="dbtouch" id="menu-cancel" value="Cancel" />
	</p>
</form>