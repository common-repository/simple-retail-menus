<ul id="admin-link-list">
	<li><a class="admin-link" href="<?php echo JSRM_SELF."&mode=new"; ?>">Create a New Menu</a></li>
</ul>
	<?php
	$result = $wpdb->get_results("SELECT * FROM $jsrm_menu_table ORDER by menuorder ASC");
	if ($result){ ?>
		<h3>Current Menus</h3>
		<form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
		<table id='jsrm-all-menus' class='jsrm-admin-table'>
			<thead><tr><th>Label</th><th>Menu Title</th><th>Description</th><th>Items</th><th class="menu-id-cell">ID</th></tr></thead>
			<tbody>
			<?php
			foreach ($result as $r) {
				$menuid= $r->id;
				$menuorder = ($r->menuorder) ? $r->menuorder : $menuid;
				$name = esc_html(stripslashes($r->name));
				$label = ($r->label) ? esc_html(stripslashes($r->label)) : $name;
				$description = ($r->description) ? esc_html(stripslashes($r->description)) : "&nbsp;";
				$editlink = JSRM_SELF."&mode=edit&targetmenu=".$menuid;
				$num_rows = $wpdb->query("SELECT * FROM $jsrm_item_table WHERE menu = $menuid");
				?>
				<tr id="<?php echo $menuid; ?>">	
					</td>
					<td><a href="<?php echo $editlink; ?> "><?php echo $label; ?></a></td>
					<td><?php echo $name; ?></td>
					<td><?php echo $description; ?></td>
					<td><?php echo $num_rows; ?></td>
					<td><?php echo $menuid; ?>
						<input type="hidden" name="id[<?php echo $menuid ?>]" value="<?php echo $menuid ?>" id="id<?php echo $menuid ?>"/>
						<input type="hidden" name="morder[<?php echo $menuid ?>]" value="<?php echo $menuorder ?>" id="order<?php echo $menuid ?>"/>
					</td>
				</tr>
			
			<?php
			} 
			?>
			</tbody>
		</table>
		<p><input type="hidden"  name="dbtouch" value="menuorder" /> <input type="submit"  id="order-menus" value="Save Order"/></p>
		</form>
	<?php
	}
	
	if( JSRM_SHOW_DONATION ) {
		include( 'donation.php' );
	}
	?>