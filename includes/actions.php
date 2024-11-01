<?php 

//Database Query Actions 4.2.1

switch ($_POST['dbtouch']) {
		case "Cancel":
			$loc = "";
		break;	
		
		//Delete a Menu
		case "Delete This Menu":
			$targetmenu = esc_sql($_POST['targetmenu']);
			$wpdb->delete( $jsrm_item_table, array( 'menu' => $targetmenu ), array( '%d' ) );
			$wpdb->delete( $jsrm_menu_table, array( 'id' => $targetmenu ), array( '%d' ) );	
			$loc = "";
		break;
		
		// Add a new menu
		case "Add Menu": 
			$loc = "&mode=newmenu&error=namefield";	
			$name = preg_replace('/\s\s+/', ' ', $_POST['name']);
			if (isset($name) && $name != "" && $name !=" "){
			 	$num_menus = $wpdb->query( $wpdb->prepare("SELECT * FROM %s", $jsrm_menu_table) );
			 	$label = ($_POST['label']) ? $_POST['label'] : $name;
				$description = $_POST['desc'];
				$wpdb->insert( $jsrm_menu_table, array( 'name' => $name, 'label' => $label, 'description' => $description, ), array( '%s','%s','%s') );
				$targetmenu = $wpdb->insert_id;
				$loc = "&mode=edit&targetmenu=".$targetmenu;
				$md = "edit";
			}
		break;
		
		// Sort Menu List
		case "menuorder":
			$loc = "";
			
			foreach($_POST['id'] as $j){
				$morder = $_POST['morder'][$j];
				$wpdb->update( $jsrm_menu_table , array( 'menuorder' => $morder ),array( 'id' => $j ), array( '%s' ) );
			}
		break;

		
		// Update an existing menu
		case "Update Menu": 
			$loc = "&mode=edit&targetmenu=".$_POST['targetmenu']."&error=namefield";	
			$name = preg_replace('/\s\s+/', ' ', $_POST['name']);
			if (isset($name) && $name != "" && $name !=" "){
				$label = ($_POST['label']) ? $_POST['label'] : $name;
				$description = $_POST['desc'];
				$id = $_POST['targetmenu'];
				$itemheader = $_POST['itemheader'];
				$valueheader = $_POST['valueheader'];
				
				for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
						$p = "valueheader".$v;
						${$p} = $_POST[$p];
					}
				
				$allplaceholders = array( '%s','%s','%s','%s','%s' );
				
				$updates = array(
					'name' => $name,
					'label' => $label,
					'description' => $description,
					'itemheader' => $itemheader,
					'valueheader' => $valueheader,
				);
				
				for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
						$q = "valueheader".$v;
						$updates[$q] = $$q;
						$allplaceholders[q] = "%s";
					}
					
				$wpdb->update( $jsrm_menu_table, $updates, array( 'id' => $id ), $allplaceholders );
				$loc = "&mode=edit&targetmenu=".$_POST['targetmenu'];
				$md = "edit";
			}				
		break;
		
		// Add a new item to a menu
		case "Add": 
			$targetmenu = esc_sql($_POST['targetmenu']);
			$num_rows = $wpdb->query("SELECT * FROM $jsrm_item_table WHERE menu = $targetmenu");
			$neworder = $num_rows+1;	
			if ($_POST['item']){
				$image = $_POST['image'];
				$linked = (isset($_POST['linked']) && $_POST['linked'] == 'checked' ) ? 1 : 0;
				$linkurl = $_POST['linkurl'];
				$item = $_POST['item'];
				$description = $_POST['desc'];
				$value = $_POST['value'];
				
				for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
					$p = "value".$v;
					${$p} = $_POST[$p];
				}	
				$insertions = array(
					'itemorder' => $neworder,
					'menu' => $targetmenu,
					'image' => $image,
					'linked' => $linked,
					'linkurl' => $linkurl,
					'item' => $item,
					'description' => $description,
					'value' => $value
				);
				
				$allplaceholders =  array('%d','%d','%s','%s','%s','%s','%s','%s');
				
				for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
					$q = "value".$v;
					$insertions[$q] = $$q;
					$allplaceholders[$q] = "%s";
				}
				$wpdb->insert( $jsrm_item_table , $insertions, $allplaceholders );	
			}
			
			$loc = "&mode=edit&targetmenu=".$targetmenu;
			$md = "edit";
		break;
		
		// Update existing menu items
		case "Update Items":
		
			foreach($_POST['id'] as $j){
				if (isset($_POST['strike'][$j]) && $_POST['strike'][$j] == 'checked' ){
					$wpdb->query( $wpdb->prepare("DELETE FROM $jsrm_item_table WHERE id = $j") );
				}
				else{
					$order = (isset($_POST['order'][$j])) ? $_POST['order'][$j] : "";
					$image = (isset($_POST['image'][$j])) ? $_POST['image'][$j] : "";
					$linked = (isset($_POST['linked'][$j]) && $_POST['linked'][$j] == 'checked' ) ? 1 : 0;
					$linkurl = (isset($_POST['linkurl'][$j])) ? $_POST['linkurl'][$j] : "";
					$itemhidden = (isset($_POST['itemhidden'][$j]) && $_POST['itemhidden'][$j] == 'checked' ) ? 1 : 0;
					$item = (isset($_POST['item'][$j])) ? $_POST['item'][$j] : "";
					$desc = (isset($_POST['desc'][$j])) ? $_POST['desc'][$j] : "";
					$value = (isset($_POST['value'][$j])) ? $_POST['value'][$j] : "";
					
					for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
						$p = "value".$v;
						${$p} = (isset($_POST[$p][$j])) ? $_POST[$p][$j] : "";
					}	
					
					$updates = array(
						'itemorder' => $order,
						'image' => $image,
						'linked' => $linked,
						'linkurl' => $linkurl,
						'itemhidden' => $itemhidden,
						'item' => $item,
						'description' => $desc,
						'value' => $value,
					);
					
					$allplaceholders =  array('%d','%s','%s','%s','%s','%s','%s','%s');
					
					for ($v=2;$v<=JSRM_VALUE_COLS;$v++){
						$q = "value".$v;
						$updates[$q] = $$q;
						$allplaceholders[$q] = "%s";
					}
					
					$wpdb->update( $jsrm_item_table , $updates, array( 'id' => $j ), $allplaceholders );
					
				}
			}
			//Sort items by order, then rewrite the orderw ith no gaps left from deleted items
			$targetmenu = esc_sql($_POST['targetmenu']);
			$rows = "SELECT * FROM $jsrm_item_table WHERE menu = $targetmenu ORDER by itemorder ASC";
			$result = $wpdb->get_results($rows);
			$n = 1;
			foreach ($result as $r){
				$id = $r->id;
				$wpdb->update( $jsrm_item_table , array( 'itemorder' => $n ), array( 'id' => $id ), array( '%d' ) );
				++$n;
			}
			$loc = "&mode=edit&targetmenu=".$targetmenu;
		break;
		}
	
	// return to Admin page
	header('Location:'.JSRM_SELF.$loc);
	exit;
?>