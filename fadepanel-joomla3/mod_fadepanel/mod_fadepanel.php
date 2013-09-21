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

require_once(dirname(__FILE__) . '/helper.php');

$mod = new ModFadepanel();
$mod->prepare($params);

require(JModuleHelper::getLayoutPath('mod_fadepanel'));

?>