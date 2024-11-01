<div id="jsrm-section-shortcode" class="menu-form">
	<h3>Get Menu Shortcode</h3>
	<p class="instruction">In order to display this menu anywhere in your WordPress blog, you must include a "shortcode" in a page or post. Select your options, then copy and paste the resulting shortcode into any page or post.</p>
		
	<input type="hidden" name="dbtouch" value="editshortcode" />
	<input type="hidden" name="targetmenu" id="targetmenu" value="<?php echo $menuid; ?>" />
	<ul>
	<li class="form-row">
		<label for="selectclass">Menu Style:</label> 
		<select class="shortcodeselecter" id="selectclass">
			<option selected="selected">basic</option>
			<option>zebra</option>
			<option>custom</option>
		</select>
		<input type="text" class="customclass" id="selectcustomclass" value=""/> <span class="customclass">* Custom style class must be defined in your style sheet.</span>
	</li>
	<li class="form-row">
		<label for="selectheader">Display Menu Title as:</label> 
		<select class="shortcodeselecter" id="selectheader">
			<option>none</option>
			<option>p</option>
			<option>h1</option>
			<option selected="selected">h2</option>
			<option>h3</option>
			<option>h4</option>
			<option>h5</option>
			<option>h6</option>
			<option>span</option>
			<option>div</option>
			
		</select> 
	</li>
	<li class="form-row">
		<label for="selectdesc">Display Menu Description as:</label> 
		<select class="shortcodeselecter" id="selectdesc" name="selectdesc">
			<option>none</option>
			<option selected="selected">p</option>
			<option>h1</option>
			<option>h2</option>
			<option>h3</option>
			<option>h4</option>
			<option>h5</option>
			<option>h6</option>
			<option>span</option>
			<option>div</option>
			
		</select>
	</li>
	<li class="form-row">
		<label for="selectdisplay" class="label-wide">Display Menu as:</label> 
		<select class="shortcodeselecter" id="selectdisplay">
			<option selected="selected">Table</option>
			<option>Ordered List</option>
			<option>Unordered List</option>
		</select>
	</li>
	<li class="form-row">
		<label for="selectvaluecols" class="label-wide">Number of Value Columns to Display:</label> 
		<select class="shortcodeselecter" id="selectvaluecols">
			<?php
			for ($v=1;$v<JSRM_VALUE_COLS;$v++){
				echo "<option>". $v."</option>";
			};
			?>
			<option selected="selected">All</option>
		</select>
	</li>
	</ul>
	<p class="form-row shortcoderow">
		<label for="shortcodeoutput"><strong>Shortcode:</strong></label> 
		<textarea id="shortcodeoutput" rows="1" value="nan" readonly="readonly"/></textarea>
	</p>
</div>