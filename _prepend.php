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

Clearbricks::lib()->autoload(['noodlesImg' => __DIR__ . '/inc/lib.noodles.img.php']);
Clearbricks::lib()->autoload(['noodlesLibImagePath' => __DIR__ . '/inc/lib.image.path.php']);

dcCore::app()->blog->settings->addNamespace('noodles');

dcCore::app()->url->register(
    'noodlesmodule',
    'noodles',
    '^noodles/(.+)$',
    ['urlNoodles', 'noodles']
);
dcCore::app()->url->register(
    'noodlesservice',
    'noodle',
    '^noodle/$',
    ['urlNoodles', 'service']
);
dcCore::app()->url->register(
    'noodlescss',
    'noodles.css',
    '^noodles\.css',
    ['urlNoodles', 'css']
);
dcCore::app()->url->register(
    'noodlesjs',
    'noodles.js',
    '^noodles\.js',
    ['urlNoodles', 'js']
);
