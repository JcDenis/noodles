<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief   noodles prepend class.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Prepend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // public URL for noodles files
        App::url()->register(
            'noodles_file',
            'noodles',
            '^noodles/(.+)$',
            UrlHandler::file(...)
        );
        // public URL for noodles service
        App::url()->register(
            'noodles_service',
            'noodle',
            '^noodle/$',
            UrlHandler::service(...)
        );
        // public URL for targets CSS contents
        App::url()->register(
            'noodles_css',
            'noodles.css',
            '^noodles\.css',
            UrlHandler::css(...)
        );
        // public URL for targets JS contents
        App::url()->register(
            'noodles_js',
            'noodles.js',
            '^noodles\.js',
            UrlHandler::js(...)
        );

        return true;
    }
}
