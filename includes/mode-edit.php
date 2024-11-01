<ul id="admin-link-list">
	<li><a class="admin-link" href="<?php echo JSRM_SELF; ?>">List Menus</a></li>
	<li><a class="admin-link" href="<?php echo JSRM_SELF."&mode=new"; ?>">Create a New Menu</a></li>
</ul>
		
<?php
$message = (isset($_GET['error']) && $_GET['error'] == "namefield") ?  "<p style='color:red;'>Please give your menu a name.</p>" : "";

$menu = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $jsrm_menu_table WHERE id = %d", $_GET['targetmenu'] ), OBJECT );
$menuid = $menu->id;
$label = esc_html(stripslashes($menu->label));
$name = esc_html(stripslashes($menu->name));
$menudescription = esc_textarea(stripslashes($menu->description));
$itemhead = esc_html(stripslashes($menu->itemheader));
$itemheaddisplay = ($itemhead) ? $itemhead : "Current Items" ;
$valhead = esc_html(stripslashes($menu->valueheader));
$valheaddisplay = ($valhead) ? $valhead : "Value 1";

for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
	$vhv = "valhead".$v;
	$vheader = "valueheader".$v;
	${$vhv} = esc_html(stripslashes($menu->$vheader));
	
	$vhdv = "valheaddisplay".$v;
	${$vhdv} = (${$vhv}) ? ${$vhv} : "Value ".$v;
	}
?>


<! -- MENU INFO -->

<h3>Menu #<?php echo $menuid; ?>: <?php echo $label; ?></h3>

<form class="menu-form" action="<?php echo JSRM_SELF; ?>" method="post">
	<?php echo $message; ?>
	<div class="menu-info-col">
				<div class="form-row">
			<label for="name">Menu Title</label>
			<input id="menu-title" type="text" name="name" value="<?php echo $name; ?>"/>
		</div>
		<div class="form-row">
			<label for="label">Label</label>
			<input id="menu-label" type="text" name="label" value="<?php echo $label; ?>"/>
		</div>
		<div class="form-row">
			<label for="desc">Description</label>
			<textarea id="menu-description" rows="3" name="desc"/><?php echo $menudescription; ?></textarea>
		</div>
	</div>
	<div class="menu-info-col">
		<div class="form-row">
			<label class="header-field-label" for="item-header">Item Column Header</label>
			<input class="header-field" id="item-header" type="text" name="itemheader" value="<?php echo $itemhead; ?>" />
		</div>

		<?php for ($v=1;$v<=JSRM_VALUE_COLS;$v++){
				$n = ($v==1) ? "" : $v;
				$m = (JSRM_VALUE_COLS<2) ? "" : $v;
				echo "<div class='form-row'>";
				echo "<label class='header-field-label' for='value-header".$n."'>Value ".$m." Column Header</label> ";
				echo "<input class='header-field' id='value-header".$n."' type='text' name='valueheader".$n."' value='".${"valhead".$n}."'/>";
				echo "</div>";

			} ?>
			
		<input type="hidden" name="targetmenu" value="<?php echo $menuid; ?>">
	</div>
	<p class="clear">
		<input type="submit" class="button-primary" name="dbtouch" id="menu-update" value="Update Menu"/>
		<input type="submit" class="button-secondary" name="dbtouch" id="menu-delete" value="Delete This Menu" onclick="return confirm('Are you sure you want to delete this menu?')"/>
 	</p>
</form>			



<! -- ADD MENU ITEM -->

<h3>Add Menu Item</h3>
<form id="add-item-form" action="<?php echo JSRM_SELF; ?>" method="post">
	<table class="jsrm-admin-table">
		<tbody>
		<tr>
			<td class="image-cell">
				<a class="item-image" id="item-image-new" title="Click to add image"></a>
				<div class="image-tools" style="display: none;">
					<a class="remove-image" id="image-new" title="Remove image"> </a>	
					<input id="link-toggle" class="link-toggle" type="checkbox" name="linked" value="checked" />
					<label for="link-toggle" class="link-icon"><span></span></label>
				</div>
			</td>
			<td class="text-cell"><textarea name="item" title="Item Name *required*" id="add-item-field"></textarea></td>
			<td class="text-cell"><textarea name="desc" title="Item Description" id="add-desc-field"></textarea></td>
			<td class="value-cell"><input type="text" id="add-value-field" class="value-field" title="<?php echo $valheaddisplay; ?>" name="value" /></td>
			
			<?php for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
				$vhd = "valheaddisplay".$v;
				echo "<td class='value-cell'><input type='text' id='add-value-field".$v."' class='value-field' title='".${$vhd}."' name='value".$v."' /></td>";
			} ?>
			
			<td class="add-cell">
				<input type="hidden" name="image" id="src-item-image-new" value="" />
				<input type="hidden" name="linkurl" id="link-item-image-new" value="" />
				<input type="hidden" name="mode" value="editmenu"/>
				<input type="hidden" name="targetmenu" value="<?php echo $menuid; ?>">
				<input type="submit" name="dbtouch" id="add-item-button" class="button-primary" value="Add"/></div> 
			</td>
		</tr>
		</tbody>
	</table>
</form> 


<! -- EDIT MENU ITEMS -->


<?php

$result = $wpdb->get_results("SELECT * FROM $jsrm_item_table WHERE menu = $menuid ORDER by itemorder ASC");
if( $result ){
?>
	
<h3>Current Items <small> (Drag rows to re-order items)</small></h3>
<form id="edit-menu-form" action="<?php echo JSRM_SELF; ?>" method="post">	
	<table id="jsrm-current-item-list" class="jsrm-admin-table">
		<thead>
			<tr>
				<th> </th>
				<th>Image</th>
				<th><?php echo $itemheaddisplay; ?></th>
				<th>Description</th>
				<th class="value-cell"><?php echo $valheaddisplay; ?></th>
				<?php
				for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
					$vhd = "valheaddisplay".$v;
					echo "<th class='value-cell'>".${$vhd}."</th>";
				};
				?>
				<th>Hide</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		
		<?php
		foreach ($result as $r) {
			$id = $r->id;
			$order = $r->itemorder;
			$image = $r->image;
			$linked = ($r->linked == 1) ? "checked='yes'" : "";
			$linkedtitle = ($r->linked == 1) ? "Unlink Image" : "Link Image";
			$itemhidden = ($r->itemhidden == 1) ? "checked='yes'" : "";
			$linkurl = $r->linkurl;
			$item = esc_html(stripslashes( $r->item ));
			$desc = esc_html(stripslashes($r->description));
			$value = esc_html(stripslashes($r->value));
			for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
				$valvar = "value".$v;
				${$valvar} = esc_html(stripslashes($r->$valvar));
			}
		?>
		
		<tr id="<?php echo $id ?>">
			<td class="order-cell"><?php echo $order; ?></td>
			<td class="image-cell">
				
	<a id="item-image-<?php echo $id;?>" class="item-image<?php if($image) echo " has-image"; ?>" <?php if($image) echo "style='background-image:url(".$image.");'"; ?> title="Add/Edit image"></a>
				
				<div class="image-tools" <?php if(!$image) echo "style='display: none;'";?> >
					<a class="remove-image" id="image-<?php echo $id ?>" title="Remove image"> </a>	
					<input id="link-toggle[<?php echo $id; ?>]" class="link-toggle" type="checkbox" <?php echo $linked ?> name="linked[<?php echo $id; ?>]" value="checked" />
					<label for="link-toggle[<?php echo $id; ?>]" title="<?php echo $linkedtitle; ?>" class="link-icon"><span ></span></label>
				</div>
			</td>
			<td class="text-cell"><textarea class="edit-item-field" name="item[<?php echo $id ?>]"><?php echo $item ?></textarea></td>
			<td class="text-cell"><textarea class="edit-desc-field" name="desc[<?php echo $id ?>]"><?php echo $desc ?></textarea></td>
			<td class="value-cell"><input type="text" class="value-field" name="value[<?php echo $id ?>]" value="<?php echo $value ?>" /></td>
			<?php
			for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
				echo "<td class='value-cell'><input type='text' class='value-field' name='value".$v."[".$id."]' value='".${"value".$v}."'/></td>";
			};
			?>
			
			<td class="check-cell"><input type="checkbox" class="hideit" <?php echo $itemhidden; ?> name="itemhidden[<?php echo $id ?>]" value="checked"/></td>
			<td class="check-cell"><input type="checkbox" class="strike" name="strike[<?php echo $id ?>]" value="checked"/></td>
		</tr>
		<input type="hidden" name="image[<?php echo $id ?>]" id="src-item-image-<?php echo $id; ?>" value="<?php echo $image; ?>" />
		<input type="hidden" name="linkurl[<?php echo $id ?>]" id="link-item-image-<?php echo $id; ?>" value="<?php echo $linkurl; ?>" />
		<input type="hidden" name="order[<?php echo $id ?>]" value="<?php echo $order ?>" id="order<?php echo $id ?>"/>
		<input type="hidden" name="id[<?php echo $id ?>]" value="<?php echo $id ?>" id="id<?php echo $id ?>"/>
		<?php
		};
		?>
		</tbody>
	</table>
	<div id="image-preview"></div>
	<p>
		<input type="hidden" name="targetmenu" value="<?php echo $menuid; ?>">
		<input type="submit"  name="dbtouch" value="Update Items" id="update-items-button" class="button-primary"/>
	</p>
</form>	
<?php
include('shortcode.php');
};
?>