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
$this->registerModule(
    'Noodles',
    'Add users gravatars everywhere',
    'Jean-Christian Denis and contributors',
    '0.8',
    [
        'requires'    => [['core', '2.19']],
        'permissions' => 'admin',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/noodles',
        'details'     => 'http://plugins.dotaddict.org/dc2/details/noodles',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/noodles/master/dcstore.xml',
        'settings'    => [
            'self' => ''
        ]
    ]
);