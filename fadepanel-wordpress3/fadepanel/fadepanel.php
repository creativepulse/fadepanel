<?php

/*
Plugin Name: Fade Panel
Plugin URI: http://www.creativepulse.gr/en/appstore/fadepanel
Version: 1.2
Description: Fade Panel features a fade in/out animation effect when the user rolls the mouse over a predefined area
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Author: Creative Pulse
Author URI: http://www.creativepulse.gr
*/


class CpExt_FadePanel extends WP_Widget {

	function __construct() {
		$options = array('classname' => 'CpExt_FadePanel', 'description' => 'Panel with fade in/out animation effect when the user rolls the mouse over it');
		$this->WP_Widget('CpExt_FadePanel', 'Fade Panel', $options);
	}

	function register_widget() {
		register_widget(get_class($this));
	}

	function widget($args, $instance) {
		$dir = dirname(__FILE__);
		require($dir . '/inc/widget.php');
	}

	function update($new_instance, $old_instance) {
		$fields = array('title', 'interval', 'step', 'default_width', 'default_height', 'image_prefix', 'source', 'hpp');
		foreach ($fields as $field) {
			$old_instance[$field] = $new_instance[$field];
		}
		return $old_instance;
	}

	function form($instance) {
		$dir = dirname(__FILE__);
		require($dir . '/inc/form.php');
	}

}

add_action('widgets_init', array('CpExt_FadePanel', 'register_widget'));

?>