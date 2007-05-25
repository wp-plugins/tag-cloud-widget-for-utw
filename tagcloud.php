<?php
/*
Plugin Name: Tag Cloud widget for UTW
Plugin URI: http://blog.broom9.com/?page_id=349
Description: Adds a tag cloud sidebar widget, used with <a href="http://www.neato.co.nz/archives/2007/02/04/ultimate-tag-warrior-314159265/">Ultimate Tag Warrior 3.14159265</a>
Author: Wei Wei
Version: 1.0
Author URI: http://www.broom9.com
*/

//Thanks for the google search widget( http://automattic.com ), I copied a lot of codes from it

function widget_cloud_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little form.
	function widget_cloud($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_cloud');
		$title = $options['title'];
		$tagStyle = $options['tagStyle'];
		$limit = $options['limit'];
		$order = $options['order'];
		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget . $before_title . $title . $after_title;
		echo '<div>';
	   if ($order == 'alpha') {
	           UTW_ShowWeightedTagSetAlphabetical($tagStyle,"",$limit);
	   } else {
	           UTW_ShowWeightedTagSet($tagStyle,"",$limit);
	   }
		echo '</div>';
		echo $after_widget;
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_cloud_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_cloud');
		if ( !is_array($options) )
			$options = array('title'=>'', 'tagStyle'=>__('sizedtagcloud', 'widgets'),'limit'=>150,'order'=>'alpha'); 
		if ( $_POST['cloud-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['cloud-title']));
			$options['tagStyle'] = strip_tags(stripslashes($_POST['cloud-tagStyle']));
			$options['limit'] = strip_tags(stripslashes($_POST['cloud-limit']));
			$options['order'] = strip_tags(stripslashes($_POST['cloud-order']));
			update_option('widget_cloud', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$tagStyle = htmlspecialchars($options['tagStyle'], ENT_QUOTES);
		$limit = htmlspecialchars($options['limit'], ENT_QUOTES);
		$order = htmlspecialchars($options['order'], ENT_QUOTES);
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="cloud-title">' . __('Title:') . ' <input style="width: 200px;" id="cloud-title" name="cloud-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="cloud-tagStyle">' . __('Tag Style:') . ' <input style="width: 200px;" id="cloud-tagStyle" name="cloud-tagStyle" type="text" value="'.$tagStyle.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="cloud-order">' . __('Tag Order:') . ' <select id="cloud-order" name="cloud-order" style="width: 200px;"><option value="alpha"';
    if ($order == 'alpha') { echo ' selected'; }
    echo '>Alphabetical</option><option value="rank"';
    if ($order == 'rank') { echo ' selected'; }
    echo '>Popular</option></select></label></p>'; 
		echo '<p style="text-align:right;"><label for="cloud-limit">' . __('Number Limit:') . ' <input style="width: 200px;" id="cloud-limit" name="cloud-limit" type="text" value="'.$limit.'" /></label></p>';
		echo '<p><a target="_blank" href="http://www.neato.co.nz/wp-content/plugins/UltimateTagWarrior/ultimate-tag-warrior-help-themes.html#predefinedformats">Tag Style Reference</a></p>';
		echo '<input type="hidden" id="cloud-submit" name="cloud-submit" value="1" />';
	}
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Tag Cloud', 'widgets'), 'widget_cloud');
	
	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Tag Cloud', 'widgets'), 'widget_cloud_control', 300, 175);
	
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_cloud_init');

?>