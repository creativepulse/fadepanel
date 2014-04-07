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


if (!class_exists('CpWidget_FadePanel')) {

	class CpWidget_FadePanel {

		public static function get_model() {
			static $instance = null;
			if ($instance === null) {
				$instance = new CpWidget_FadePanel();
			}
			return $instance;
		}

		public function view_id($new = false) {
			static $num = 0;

			if ($new) {
				$num++;
			}

			return $num;
		}

		public function load_libraries($name) {
			static $libraries = array();
			$result = !isset($libraries[$name]);
			$libraries[$name] = true;
			return $result;
		}

		public function json_esc($input, $esc_html = true) {
			$result = '';
			if (!is_string($input)) {
				$input = (string) $input;
			}

			$conv = array("\x08" => '\\b', "\t" => '\\t', "\n" => '\\n', "\f" => '\\f', "\r" => '\\r', '"' => '\\"', "'" => "\\'", '\\' => '\\\\');
			if ($esc_html) {
				$conv['<'] = '\\u003C';
				$conv['>'] = '\\u003E';
			}

			for ($i = 0, $len = strlen($input); $i < $len; $i++) {
				if (isset($conv[$input[$i]])) {
					$result .= $conv[$input[$i]];
				}
				else if ($input[$i] < ' ') {
					$result .= sprintf('\\u%04x', ord($input[$i]));
				}
				else {
					$result .= $input[$i];
				}
			}

			return $result;
		}

		function replace($m1) {
			$instance_id = $this->view_id(true);


			// load tag parameters

			preg_match_all('~([a-z0-9_-]+)\s*=\s*"([^"]*)"~i', $m1[1], $m2);

			$par = array();
			for ($i = 0, $len = count($m2[0]); $i < $len; $i++) {
				$par[strtolower($m2[1][$i])] = $m2[2][$i];
			}


			// set javascript

			$move1_id = trim(@$par['move1_id']);
			$move2_id = trim(@$par['move2_id']);

			$this->js_code .= '
document.CpWdgJs_FadePanel_db.push({
	wdg1_id: "fadepanel_' . $instance_id . '_panel1",
	wdg1_move_id: "' . $this->json_esc($move1_id) . '",
	wdg2_id: "fadepanel_' . $instance_id . '_panel2",
	wdg2_move_id: "' . $this->json_esc($move2_id) . '",
	interval: ' . $this->interval . ',
	step: ' . $this->step . ',
	href: "' . $this->json_esc(@$par['href']) . '",
	hpp: ' . ($this->hpp ? 'true' : 'false') . '
});
';

			if (isset($par['move1_id'])) {
				unset($par['move1_id']);
			}

			if (isset($par['move2_id'])) {
				unset($par['move2_id']);
			}


			// find container width

			$width = 0;

			if (isset($par['width1'])) {
				$width = intval($par['width1']);
			}

			if (isset($par['width2'])) {
				$i = intval($par['width2']);
				if ($i > $width) {
					$width = $i;
				}
			}

			if (isset($par['width'])) {
				$i = intval($par['width']);
				if ($i > $width) {
					$width = $i;
				}
			}

			if ($width == 0 && $this->default_width > 0) {
				$width = $this->default_width;
			}


			// find container height

			$height = 0;

			if (isset($par['height1'])) {
				$height = intval($par['height1']);
			}

			if (isset($par['height2'])) {
				$i = intval($par['height2']);
				if ($i > $height) {
					$height = $i;
				}
			}

			if (isset($par['height'])) {
				$i = intval($par['height']);
				if ($i > $height) {
					$height = $i;
				}
			}

			if ($height == 0 && $this->default_height > 0) {
				$height = $this->default_height;
			}


			// open output

			$html = '<div';

			if (!empty($par['id'])) {
				$html .= ' id="' . $par['id'] . '"';
			}

			if ($width > 0 || $height > 0) {
				$html .= ' style="' . ($width == 0 ? '' : 'width:' . $width . 'px; ') . ($height == 0 ? '' : 'height:' . $height . 'px; ') . 'overflow:hidden;"';
			}

			$html .= '>';


			// output mouse-out image

			$html .= '<' . ($move1_id == '' ? 'img' : 'div') . ' id="fadepanel_' . $this->view_id() . '_panel1" style="display:block"';

			$attr = array();
			foreach ($par as $k => $v) {
				if ($k == 'id' || $k == 'id1') {
					continue;
				}

				$c = substr($k, -1);
				if ($c == '1') {
					$attr[substr($k, 0, -1)] = $v;
				}
				else if ($c == '2') {
					continue;
				}
				else if (!isset($attr[substr($k, 0, -1)])) {
					$attr[$k] = $v;
				}
			}

			if (isset($attr['img']) && !isset($attr['src'])) {
				$attr['src'] = $attr['img'];
				unset($attr['img']);
			}

			if (isset($attr['src'])) {
				$attr['src'] = $this->image_prefix . $attr['src'];
			}

			foreach ($attr as $k => $v) {
				$html .= ' ' . $k . '="' . $v . '"';
			}


			$html .= ' />';


			// output mouse-over image

			if (isset($par['height1'])) {
				$height = $par['height1'];
			}
			else if (isset($par['height'])) {
				$height = $par['height'];
			}
			else {
				$height = 0;
			}

			$html .= '<' . ($move2_id == '' ? 'img' : 'div') . ' id="fadepanel_' . $this->view_id() . '_panel2" style="display:none;' . ($height == 0 ? '' : ' position:relative; top:-' . $height . 'px;') . '"';

			$attr = array();
			foreach ($par as $k => $v) {
				if ($k == 'id' || $k == 'id2') {
					continue;
				}

				$c = substr($k, -1);
				if ($c == '1') {
					continue;
				}
				else if ($c == '2') {
					$attr[substr($k, 0, -1)] = $v;
				}
				else if (!isset($attr[substr($k, 0, -1)])) {
					$attr[$k] = $v;
				}
			}

			if (isset($attr['img']) && !isset($attr['src'])) {
				$attr['src'] = $attr['img'];
				unset($attr['img']);
			}

			if (isset($attr['src'])) {
				$attr['src'] = $this->image_prefix . $attr['src'];
			}

			foreach ($attr as $k => $v) {
				$html .= ' ' . $k . '="' . $v . '"';
			}

			$html .= ' />';


			// close output

			$html .= '</div>' . "\n";


			echo $html;
		}


		public function prepare($params) {
			$this->image_prefix = trim(@$params['image_prefix']);
			$this->interval = isset($params['interval']) ? intval($params['interval']) : 20;
			$this->step = isset($params['step']) ? intval($params['step']) : 3;
			$this->default_width = intval($params['default_width']);
			$this->default_height = intval($params['default_height']);
			$this->source = trim(@$params['source']);
			$this->hpp = !empty($params['hpp']); // hpp = hide primary panel

			$this->js_code = '';

			if ($this->load_libraries('*')) {
				echo
'<script type="text/javascript" src="' . plugins_url('/js/fadepanel.js', dirname(__FILE__)) . '"></script>
';

				$this->js_code =
'document.CpWdgJs_FadePanel_db = [];
';
			}

			$this->html_code = preg_replace_callback('~<\s*fadepanel\s([^>]*)>~i', array(&$this, 'replace'), $this->source);

			if ($this->js_code != '') {
				echo
'<script type="text/javascript">
' . $this->js_code . '
</script>
';
			}
		}
	}

}


// process instance

echo $args['before_widget'];
echo empty($instance['title']) ? '' : $args['before_title'] . $instance['title'] . $args['after_title'] . "\n";

$widget = CpWidget_FadePanel::get_model();
$widget->prepare($instance);

echo $args['after_widget'];

?>