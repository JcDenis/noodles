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

$this->registerModule(
    'Noodles',
    'Add users gravatars everywhere',
    'Jean-Christian Denis and contributors',
    '1.2.3',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'settings'    => ['self' => ''],
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/issues',
        'details'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository'  => 'https://github.com/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
    ]
);
