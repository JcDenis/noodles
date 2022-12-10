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
if (!defined('DC_CONTEXT_ADMIN')) {
    return null;
}

$mod_conf = [
    [
        'noodles_active',
        false,
        'boolean',
        'Enable extension',
    ],
    [
        'noodles_api',
        'http://www.gravatar.com/',
        'string',
        'external API to use',
    ],
    [
        'noodles_image',
        '',
        'string',
        'Image filename',
    ],
    [
        'noodles_object',
        '',
        'string',
        'Noodles behaviors',
    ],
];

# -- Nothing to change below --
try {
    # Check module version
    if (!dcCore::app()->newVersion(
        basename(__DIR__), 
        dcCore::app()->plugins->moduleInfo(basename(__DIR__), 'version')
    )) {
        return null;
    }
    # Set module settings
    dcCore::app()->blog->settings->addNamespace(basename(__DIR__));
    foreach ($mod_conf as $v) {
        dcCore::app()->blog->settings->__get(basename(__DIR__))->put(
            $v[0],
            $v[1],
            $v[2],
            $v[3],
            false,
            true
        );
    }

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());

    return false;
}