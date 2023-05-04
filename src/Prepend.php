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

class Prepend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::phpCompliant();

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        // public URL for noodles files
        dcCore::app()->url->register(
            'noodles_file',
            'noodles',
            '^noodles/(.+)$',
            [UrlHandler::class, 'file']
        );
        // public URL for noodles service
        dcCore::app()->url->register(
            'noodles_service',
            'noodle',
            '^noodle/$',
            [UrlHandler::class, 'service']
        );
        // public URL for targets CSS contents
        dcCore::app()->url->register(
            'noodles_css',
            'noodles.css',
            '^noodles\.css',
            [UrlHandler::class, 'css']
        );
        // public URL for targets JS contents
        dcCore::app()->url->register(
            'noodles_js',
            'noodles.js',
            '^noodles\.js',
            [UrlHandler::class, 'js']
        );

        return true;
    }
}
