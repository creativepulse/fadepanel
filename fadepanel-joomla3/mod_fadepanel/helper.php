<?php

/**
 * fadepanel
 *
 * @version 1.1
 * @author Creative Pulse
 * @copyright Creative Pulse 2009-2013
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */

defined('_JEXEC') or die('Restricted access');


class ModFadepanel {

    function instance_id($new = false) {
        static $num = 0;

        if ($new) {
            $num++;
        }

        return $num;
    }

    function json_esc($input, $esc_lt_gt = true) {
        $result = '';
        $input = (string) $input;
        $conv = array( "'" => "'", '"' => '"', '&' => '&', '\\' => '\\', "\n" => 'n', "\r" => 'r', "\t" => 't', "\b" => 'b', "\f" => 'f' );

        if ($esc_lt_gt) {
            $conv = array_merge($conv, array( '<' => 'u003C', '>' => 'u003E' ));
        }

        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            $result .= isset($conv[$input[$i]]) ? '\\' . $conv[$input[$i]] : $input[$i];
        }

        return $result;
    }

    function def_int($input, $default = 0) {
        $input = trim($input);
        return $input == '' || !ctype_digit($input) ? $default : (int) $input;
    }

    function def_float($input, $default = 0.0) {
        $input = trim($input);
        return !preg_match('/^[0-9]+(\.[0-9]*)?$/', $input) ? $default : (float) $input;
    }

    function load_main_js() {
        $document = JFactory::getDocument();

        $document->addScript('modules/mod_fadepanel/fadepanel.js');

        $document->addScriptDeclaration('
function fadepanel_init() {
    for (var k in document["mod_fadepanel_conf"]) {
        if (document["mod_fadepanel_conf"].hasOwnProperty(k)) {
            var conf = document["mod_fadepanel_conf"][k];
            document[conf.iname] = new Fadepanel(conf.iname, conf.wdg1_id, conf.wdg1_move_id, conf.wdg2_id, conf.wdg2_move_id, conf.interval, conf.step, conf.href, conf.hpp);
        }
    }
}
if (window.addEventListener) {
    window.addEventListener("load", fadepanel_init, false);
}
else if (window.attachEvent) {
    window.attachEvent("onload", fadepanel_init);
}
');
    }

    function replace($m1) {

        $instance_id = $this->instance_id(true);
        if ($instance_id == 1) {
            $this->load_main_js();
        }


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
document["mod_fadepanel_conf"]["inst_' . $instance_id . '"] = {
    iname: "fadepanel_' . $instance_id . '",
    wdg1_id: "fadepanel_' . $instance_id . '_panel1",
    wdg1_move_id: "' . $this->json_esc($move1_id) . '",
    wdg2_id: "fadepanel_' . $instance_id . '_panel2",
    wdg2_move_id: "' . $this->json_esc($move2_id) . '",
    interval: ' . $this->interval . ',
    step: ' . $this->step . ',
    href: "' . $this->json_esc(@$par['href']) . '",
    hpp: ' . ($this->hpp ? 'true' : 'false') . '
}
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
            $width = $this->def_int($par['width1']);
        }

        if (isset($par['width2'])) {
            $i = $this->def_int($par['width2']);
            if ($i > $width) {
                $width = $i;
            }
        }

        if (isset($par['width'])) {
            $i = $this->def_int($par['width']);
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
            $height = $this->def_int($par['height1']);
        }

        if (isset($par['height2'])) {
            $i = $this->def_int($par['height2']);
            if ($i > $height) {
                $height = $i;
            }
        }

        if (isset($par['height'])) {
            $i = $this->def_int($par['height']);
            if ($i > $height) {
                $height = $i;
            }
        }

        if ($height == 0 && $this->default_height > 0) {
            $height = $this->default_height;
        }


        // open output

        $result = '<div';

        if (!empty($par['id'])) {
            $result .= ' id="' . $par['id'] . '"';
        }

        if ($width > 0 || $height > 0) {
            $result .= ' style="' . ($width == 0 ? '' : 'width:' . $width . 'px; ') . ($height == 0 ? '' : 'height:' . $height . 'px; ') . 'overflow:hidden;"';
        }

        $result .= '>';


        // output mouse-out image

        $result .= '<' . ($move1_id == '' ? 'img' : 'div') . ' id="fadepanel_' . $this->instance_id() . '_panel1" style="display:block"';

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
            $result .= ' ' . $k . '="' . $v . '"';
        }


        $result .= ' />';


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

        $result .= '<' . ($move2_id == '' ? 'img' : 'div') . ' id="fadepanel_' . $this->instance_id() . '_panel2" style="display:none;' . ($height == 0 ? '' : ' position:relative; top:-' . $height . 'px;') . '"';

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
            $result .= ' ' . $k . '="' . $v . '"';
        }

        $result .= ' />';


        // close output

        $result .= '</div>';


        return $result;
    }

    function prepare(&$params) {
        $this->image_prefix = $params->get('image_prefix');
        $this->interval = $this->def_int($params->get('interval'), 20);
        $this->step = $this->def_float($params->get('step'), 3);
        $this->default_width = $this->def_int($params->get('default_width'), '');
        $this->default_height = $this->def_int($params->get('default_height'), '');
        $this->source = $params->get('source');
        $this->hpp = $params->get('hpp') == '1'; // hpp = hide primary panel

        $this->js_code = '';
        $this->html_code = preg_replace_callback('~<\s*fadepanel\s([^>]*)>~i', array(&$this, 'replace'), $this->source);

        $this->js_code = '

<script type="text/javascript">
// <![CDATA[
if (!document["mod_fadepanel_conf"]) {
    document["mod_fadepanel_conf"] = { }
}
' . $this->js_code . '
// ]]>
</script>

';
    }
}

?>