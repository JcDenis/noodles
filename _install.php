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

$new_version = $core->plugins->moduleInfo('noodles', 'version');
$old_version = $core->getVersion('noodles');

if (version_compare($old_version, $new_version, '>=')) {
    return null;
}

try {
    $core->blog->settings->addNamespace('noodles');
    $core->blog->settings->noodles->put(
        'noodles_active',
        false,
        'boolean',
        'Enable extension',
        false,
        true
    );
    $core->blog->settings->noodles->put(
        'noodles_api',
        'http://www.gravatar.com/',
        'string',
        'external API to use',
        false,
        true
    );
    $core->blog->settings->noodles->put(
        'noodles_image',
        '',
        'string',
        'Image filename',
        false,
        true
    );
    $core->blog->settings->noodles->put(
        'noodles_object',
        '',
        'string',
        'Noodles behaviors',
        false,
        true
    );
    $core->setVersion('noodles', $new_version);

    return true;
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}

return false;