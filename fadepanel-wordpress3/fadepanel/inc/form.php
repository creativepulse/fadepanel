<?php

/**
 * Fade Panel
 *
 * @version 1.2
 * @author Creative Pulse
 * @copyright Creative Pulse 2013-2014
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


$instance = wp_parse_args(
	(array) $instance,
	array(
		'title' => '',
		'interval' => '20',
		'step' => '7',
		'default_width' => '',
		'default_height' => '',
		'image_prefix' => 'wp-content/uploads/',
		'source' => '',
		'hpp' => '0'
	)
);

echo
'<p><label for="' . $this->get_field_id('title') . '">' . __('Title') . ':</label>
	<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($instance['title']) . '" />
</p>

<p><label for="' . $this->get_field_id('interval') . '">' . __('Interval') . ':</label>
	<input id="' . $this->get_field_id('interval') . '" name="' . $this->get_field_name('interval') . '" type="text" value="' . esc_attr($instance['interval']) . '" size="5" />
	&nbsp; &nbsp; &nbsp;
	<label for="' . $this->get_field_id('step') . '">' . __('Step') . ':</label>
	<input id="' . $this->get_field_id('step') . '" name="' . $this->get_field_name('step') . '" type="text" value="' . esc_attr($instance['step']) . '" size="5" />
</p>

<p><label for="' . $this->get_field_id('default_width') . '">' . __('Default width') . ':</label>
	<input id="' . $this->get_field_id('default_width') . '" name="' . $this->get_field_name('default_width') . '" type="text" value="' . esc_attr($instance['default_width']) . '" size="5" />
	&nbsp;
	<label for="' . $this->get_field_id('default_height') . '">' . __('height') . ':</label>
	<input id="' . $this->get_field_id('default_height') . '" name="' . $this->get_field_name('default_height') . '" type="text" value="' . esc_attr($instance['default_height']) . '" size="5" />
</p>

<p><label for="' . $this->get_field_id('image_prefix') . '">' . __('Image prefix') . ':</label>
	<input class="widefat" id="' . $this->get_field_id('image_prefix') . '" name="' . $this->get_field_name('image_prefix') . '" type="text" value="' . esc_attr($instance['image_prefix']) . '" />
</p>

<p><label for="' . $this->get_field_id('source') . '">' . __('Source') . ':</label>
	<textarea class="widefat" id="' . $this->get_field_id('source') . '" name="' . $this->get_field_name('source') . '">' . esc_textarea($instance['source']) . '</textarea>
</p>

<p><label for="' . $this->get_field_id('hpp') . '">' . __('Hide primary panel') . ':</label>
	&nbsp; <input id="' . $this->get_field_id('hpp') . '" name="' . $this->get_field_name('hpp') . '" type="radio" value="1" ' . (!empty($instance['hpp']) ? ' checked="checked"' : '') . '/> ' . __('Yes') . '
	&nbsp; <input id="' . $this->get_field_id('hpp') . '" name="' . $this->get_field_name('hpp') . '" type="radio" value="0" ' . (empty($instance['hpp']) ? ' checked="checked"' : '') . '/> ' . __('No') . '
</p>
';

?>