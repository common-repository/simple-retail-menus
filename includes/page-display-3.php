<?php
/*
	This will render output formatted as in SRM plugin versions up to 3.4.
*/
//Open the jsrm container DIV
	
	$mcontent = '<div id="jsrm-menu-container-'. $id . '" class="'. $class . '">';

//Add the Header if it exists

	if($header != "none"){
		$mcontent.= '<' .$header. '>' . $menuname . '</' .$header. '>';
		}
	
//Add the Description if it exists

	if($menudescription != "" && $desc != "none"){
		$mcontent.= '<' .$desc. '>' . $menudescription . '</' .$desc. '>';
		}
	
// CHECK FOR IMAGE ENTRIES

$hasimages = false;
foreach ($result as $r) {
	if($r->image){ $hasimages = true; }	
}

// ADD THE COLUMN HEADERS

	switch ($display) {
	
		case "table":
			$mcontent.= "<table id='jsrm-menu-". $id . " ' class='jsrm-" .$display. "'>";
			if($hasheaders){
				if($hasimages){
					$mcontent.= "<thead class='col-headers'><tr><th colspan='2'>" . $itemheader . "</th>";
					}
				else{
					$mcontent.= "<thead class='col-headers'><tr><th>" . $itemheader . "</th>";
					}

				for($i=1;$i<$valuecols;$i++){
					$mcontent.= "<th class='value-".$i."'>" . ${'valueheader'.$i} ."</th>";
					}
					
				$mcontent.= "</tr></thead>";
			}
			$mcontent.= "<tbody>";
		break;
		
		case ("ol" || "ul"):
			if($hasheaders){
				$mcontent.= "<div class='col-headers'><div class='item-header'>". $itemheader . "</div>";
				$mcontent.= "<div class='value-header'>";
				
				for($i=1;$i<$valuecols;$i++){
					$mcontent.= "<div class='value-col value-".$i."'>" . ${'valueheader'.$i} ."</div>";
					}

				$mcontent.= "</div></div>";
			}
			$mcontent.= "<".$display." id='jsrm-menu-". $id . "' class='jsrm-" .$display. "'>";
		break;	
		}
				
// ITERATE THROUGH THE MENU DATA

$num = 0;
	foreach ($result as $r) {
		$alt = ( $num % 2 == 0 ) ? "odd" : "even";
		$itemhidden = ($r->itemhidden);
		$image = ($r->image) ? html_entity_decode(esc_html(stripslashes($r->image))) : false;
		$linked = ($r->linked);
		$linkurl = ($r->linkurl) ? html_entity_decode(esc_html(stripslashes($r->linkurl))) : false;
		$item = ($r->item) ? html_entity_decode(esc_html(stripslashes($r->item))) : false;	
		$description = ($r->description) ? html_entity_decode(esc_html(stripslashes($r->description))) : false;
		$value1 = ($r->value) ? html_entity_decode(esc_html(stripslashes($r->value))) : "&nbsp;";
				
		for ($v=2;$v<=$valuecols;$v++){
			$valvar = "value".$v;
			${$valvar} = ($r->$valvar) ? html_entity_decode(esc_html(stripslashes($r->$valvar))) : "&nbsp;";
		}
	
	 switch ($display) {

		case "table":
			
			$row = "<tr class='". $alt. "'>";
				if($hasimages){
				$row.= "<td class='image'>";
				if($image){
					if($linked){
						$row.= "<a href='". $linkurl ."'><img src='". $image . "' /></a>";
					}
					else{
						$row.= "<img src='". $image . "' />";
					}
				}
				$row.= "</td>";
				}
			$row.= "<td class='item'><p class='item-text'>" . $item . "</p>";
			if($description){
				$row.= "<p class='desc'>" .  $description ."</p>";
				} 
			$row.= "</td>";

			for($i=1;$i<$valuecols;$i++){
					$row.= "<td class='value'><p class='value-text'>" . ${'value'.$i} ."</p></td>";
					}
			$row.="</tr>";
			
		break;
	
		case ("ol" || "ul"):
	
			$row = "<li class='". $alt. "'>";
			if($image){
					if($linked == 1){
						$row.= "<div class='image'><a href='". $linkurl ."'><img src='". $image . "' /></a></div>";
					}
					else{
						$row.= "<div class='image'><img src='". $image . "' /></div>";
					}
				}
			$row.= "<div><p class='item-text'>" . $item . "</p>";
			if($description){
				$row.= "<p class='desc'>" . $description ."</p>";
				}
			$row.= "</div><div class='value'>";

			for($i=1;$i<$valuecols;$i++){
				$row.= "<div class='value-col value-".$i."'>" . ${'value'.$i} . "</div>";
			}
			$row.= "</div></li>";
			
		break;
					
	}	
	if($itemhidden != 1){
		$mcontent.= $row;
	}
	$num++;
	}
	
//Close the jsrm Menu element and container DIV
	if($display == "table"){
		$mcontent.= "</tbody>";
		}
	$mcontent.= "</". $display . "/>";
	$mcontent.= "</div>";

//Flush it all out:
	echo $mcontent;
?>