<?php
/**
 * @brief noodles, a plugin for Dotclear 2
 * 
 * @package Dotclear
 * @subpackage Plugin
 * 
 * @author Jean-Christian Denis and contributors
 * 
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('DC_CONTEXT_ADMIN')){return;}

# Admin menu
$_menu['Plugins']->addItem(
    __('Noodles'),
    'plugin.php?p=noodles','index.php?pf=noodles/icon.png',
    preg_match('/plugin.php\?p=noodles(&.*)?$/',$_SERVER['REQUEST_URI']),
    $core->auth->check('admin',$core->blog->id)
);