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

if (!isset($this) || !is_object($this) || !method_exists($this, 'registerModule') || !isset($this->id) || !is_string($this->id)) {
    return;
}

$this->registerModule(
    'Noodles',
    'Add users gravatars everywhere',
    'Jean-Christian Denis and contributors',
    '1.4',
    [
        'requires'    => [['core', '2.39']],
        'permissions' => 'My',
        'settings'    => ['self' => ''],
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2026-08-12T21:14:41+00:00',
    ]
);
