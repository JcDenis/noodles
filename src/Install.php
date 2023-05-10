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
declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use dcCore;
use dcNsProcess;
use Exception;

class Install extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && is_string(dcCore::app()->plugins->moduleInfo(My::id(), 'version'))
            && dcCore::app()->newVersion(My::id(), dcCore::app()->plugins->moduleInfo(My::id(), 'version'));

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        try {
            $s = dcCore::app()->blog?->settings->get(My::id());
            if (is_null($s)) {
                return false;
            }
            # Set module settings
            $s->put('active', false, 'boolean', 'enable module', false, true);
            $s->put('api', 'http://www.gravatar.com/', 'string', 'external API', false, true);
            $s->put('local', false, 'boolean', 'use local image', false, true);
            $s->put('settings', '[]', 'string', 'noodles settings', false, true);

            return true;
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());

            return false;
        }
    }
}
