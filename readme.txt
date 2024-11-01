=== Simple Retail Menus ===
Contributors: whatwouldjessedo
Donate link: http://whatwouldjessedo.com/simple-retail-menus/
Tags: restaurant, menu, salon, menus, services, offerings, list, retail, store, tables, menus, lists
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 4.2.1

Create and manage restaurant, salon, and retail store menu lists of services, food items, retail items, or other data.

== Description ==

Perfect for salon, restaurant, and retail store websites, as well as many other applications. Simple Retail Menus lets you create and manage menu-type lists for display in a post or page. This is a free, full-featured plugin. Create as many menus as you want, add as many items as you want to any menu, add menus to any post or page on your WordPress site.

It's simple and easy to use! Just build your menus, then copy/paste the resulting 'shortcode' into you post or page.

Example of a shortcode: [simple-retail-menu id="1"]


= Plugin's Official Site =

http://whatwouldjessedo.com/simple-retail-menus/

== Installation ==

1. Upload the entire 'simple-retail-menus' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.


= Basic Use =

1. Click 'Add new Menu' link on the main Retail Menu plugin page. Give your menu a name (required) and a description (optional). Click 'Add Menu'.
2. Add as many items as you want to your menu.
 - Item Name is required. Image thumbnail, description and value fields are optional.
3. Menu information and menu item information can be updated or deleted at any time. Basic html can be included in all menu fields.
4. Change the order of menu items by dragging the rows of menu items into the desired order, then clicking 'Update Items'.
5. Get the menu Shortcode. Choose your display options, then copy the resulting 'shortcode' into the content of any page, post, or the Sidebar Text Widget.

=Display Options=

Note: Default options do not need to be included in the shortcode. The only required attribute in the shortcode for a menu to display is 'name'.

'class' refers to the containing DIV that is generated for every menu.

	Options for 'class':
	- Three class styles are built into Simple Retail Menus: 'default', 'light', and 'dark'. 
	- If you create your own custom class for your menus you can enter it under the 'custom' option.
	  Note that the custom class text field will only allow you to enter valid CSS 	class names.
	  See 'display.css' for css style structure.
	
'header' refers to the name of your menu. It can be displayed in any html containing element.

	Options for 'header':
	- Any html container: div, span, p, h1-h6, section, article, etc.
	- 'none' will cause the menu title to not display.
	-  Default: 'h2'.

'desc' refers to the description of your menu. It can be displayed in any html containing element.

	Options  for 'desc':
	- Any html container: div, span, p, h2-h6, section, article, etc.
	- 'none' will cause the menu description to not display.
	-  Default: 'p'
	
'display' refers to the html structure the menu data is displayed in. YOu can choose to display any menu as rows in a table, Ordered list, unordered list, definition list or as a series of nested divs.

	Options  for 'display':
	- 'table'
	- 'ul'
	- 'ol'

'valcols' refers to the number of value columns to display. By default two columns will display. If you want only one to display you can set valcols="1".

= Advanced/Hacks =

Menu HTML Rendering: Menu data output is handled by 'includes/display.php'. Edit this file to achieve your own custom menu display.

Menu CSS Styling: Menu data CSS styles are defined in 'css/display.css'. You can edit this file to achieve your own custom menu display, however the preferred way to customize menu styling is to define a custom style in your theme style sheet and include that custom style in the short code.

== Screenshots ==

1. screenshot-1.png

== Changelog ==

= 4.2.1 =
(3/14/2014)
* Include placeholder values to prevent wpdb prepare errors.

= 4.2 =
(3/13/2014)
* Added a loop to include placeholder values for multiple data value columns

= 4.1 =
(2/19/2014)
* Improved security against sql injection

= 4.0.1 =
(6/20/2013)
* Update to display css to make list style type: none a default.

= 4.0 =
(3/15/2013)
* Image Thumnails use WP sized images
* Image thumbnails can have links
* Updated Admin UI
* Added Rollover zoom for image thumbnails in admin view
* Improved page display code
* Added Admin Bar node for plugin
* Added option for legacy page desiplay for v3 and below.
* Added Variable number of value columns
* Added Ability to hide items from display

= 3.3.1 =
(1/3/2013)
* Fixed bug that prevented widget management in some cases (via conditional script enqueue).

= 3.3 =
(1/2/2013)
* Optimized some code, changed script and style hook actions.
* Rewrote CSS and output html for better display.
* Included updated tabledragdrop JS
* Included display JS for measuring value box widths

= 3.2 =
(1/3/2013)
* Optimized some code, changed script and style hook actions.
* Rewrote CSS and output html for better display.
* Included updated tabledragdrop JS
* Included display JS for measuring value box widths

= 3.1 = 
(10/5/2012)
* Fixed bug that prevented database update upon version update.

= 3.0 = 
(9/1/2012)
* Multisite Capable
* Menus gain a 'label field', a display name in the admin area. Does not display in page/post html.
* Definable menu column headers
* Image uploading for menu items
* Optional second 'value' field
* Output HTML and CSS updates

= 2.0  =
(10/5/2011)
* Added the capability to add a menu to the Sidebar by including the shortcode in the Text Widget.
* Changed the shortcode to use the ID, rather than the NAME of a menu. NOTE: Name can still be used, but ID is preferred.

= 1.1.1 =
(7/5/2011)
* Fixed a bug in the Javascript that caused menu list in Admin display to be disabled.

= 1.1 =
(6/29/2011)
* Added the ability to include basic html in all menu fields. You can now have hyperlinks, image links, and inline styles in your menus.

= 1.0.1 =
(6/23/2011)
* Fixed Form Action for Add New Menu
