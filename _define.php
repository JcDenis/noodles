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
    '1.2.4',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'settings'    => ['self' => ''],
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-03-02T17:30:25+00:00',
    ]
);
