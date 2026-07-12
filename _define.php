<?php
/**
 * @file
 * @brief       The plugin noodles definition
 * @ingroup     noodles
 *
 * @defgroup    noodles Plugin noodles.
 *
 * Add users gravatars everywhere.
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

$id = 'noodles';

$this->registerModule(
    'Noodles',
    'Add users gravatars everywhere',
    'Jean-Christian Denis and contributors',
    '1.3',
    [
        'requires'    => [['core', '2.39']],
        'permissions' => 'My',
        'settings'    => ['self' => ''],
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $id . '/master/dcstore.xml',
        'date'        => '2025-09-13T15:33:52+00:00',
    ]
);
