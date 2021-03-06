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
if (!defined('DC_RC_PATH')) {
    return null;
}

$__autoload['noodlesImg']          = dirname(__FILE__) . '/inc/lib.noodles.img.php';
$__autoload['noodlesLibImagePath'] = dirname(__FILE__) . '/inc/lib.image.path.php';

$core->blog->settings->addNamespace('noodles');

$core->url->register(
    'noodlesmodule',
    'noodles',
    '^noodles/(.+)$',
    ['urlNoodles', 'noodles']
);
$core->url->register(
    'noodlesservice',
    'noodle',
    '^noodle/$',
    ['urlNoodles', 'service']
);
$core->url->register(
    'noodlescss',
    'noodles.css',
    '^noodles\.css',
    ['urlNoodles', 'css']
);
$core->url->register(
    'noodlesjs',
    'noodles.js',
    '^noodles\.js',
    ['urlNoodles', 'js']
);