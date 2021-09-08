<?php
/**
 * @brief noodles, a  for Dotclear 2
 * 
 * @package Dotclear
 * @subpackage \u
 * 
 * @author JC Denis
 * 
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('DC_RC_PATH')){return;}

global $__autoload, $core;

$__autoload['noodlesImg'] = dirname(__FILE__).'/inc/lib.noodles.img.php';
$__autoload['noodlesLibImagePath'] = dirname(__FILE__).'/inc/lib.image.path.php';

$core->blog->settings->addNamespace('noodles');

$core->url->register('noodlesmodule','noodles','^noodles/(.+)$',
    array('urlNoodles','noodles'));
$core->url->register('noodlesservice','noodle','^noodle/$',
    array('urlNoodles','service'));