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

$new_version = dcCore::app()->plugins->moduleInfo('noodles', 'version');
$old_version = dcCore::app()->getVersion('noodles');

if (version_compare($old_version, $new_version, '>=')) {
    return null;
}

try {
    dcCore::app()->blog->settings->addNamespace('noodles');
    dcCore::app()->blog->settings->noodles->put(
        'noodles_active',
        false,
        'boolean',
        'Enable extension',
        false,
        true
    );
    dcCore::app()->blog->settings->noodles->put(
        'noodles_api',
        'http://www.gravatar.com/',
        'string',
        'external API to use',
        false,
        true
    );
    dcCore::app()->blog->settings->noodles->put(
        'noodles_image',
        '',
        'string',
        'Image filename',
        false,
        true
    );
    dcCore::app()->blog->settings->noodles->put(
        'noodles_object',
        '',
        'string',
        'Noodles behaviors',
        false,
        true
    );
    dcCore::app()->setVersion('noodles', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
