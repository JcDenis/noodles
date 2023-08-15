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

$this->registerModule(
    'Noodles',
    'Add users gravatars everywhere',
    'Jean-Christian Denis and contributors',
    '1.1',
    [
        'requires' => [
            ['php', '8.1'],
            ['core', '2.27'],
        ],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcCore::app()->auth::PERMISSION_CONTENT_ADMIN,
        ]),
        'settings' => [
            'self' => '',
        ],
        'type'       => 'plugin',
        'support'    => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/issues',
        'details'    => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository' => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
    ]
);
