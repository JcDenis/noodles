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
if (!class_exists('dcCore') || is_null(dcCore::app()->auth)) {
    return null;
}

$this->registerModule(
    'Noodles',
    'Add users gravatars everywhere',
    'Jean-Christian Denis and contributors',
    '1.0.1',
    [
        'requires' => [
            ['php', '8.1'],
            ['core', '2.26'],
        ],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcCore::app()->auth::PERMISSION_CONTENT_ADMIN,
        ]),
        'type'       => 'plugin',
        'support'    => 'https://github.com/JcDenis/' . basename(__DIR__),
        'details'    => 'http://plugins.dotaddict.org/dc2/details/' . basename(__DIR__),
        'repository' => 'https://raw.githubusercontent.com/JcDenis/' . basename(__DIR__) . '/master/dcstore.xml',
        'settings'   => [
            'self' => '',
        ],
    ]
);
