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
use dcUtils;

class Frontend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_RC_PATH');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        $targets = Targets::instance();

        if (!$targets->active) {
            return false;
        }

        dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), My::path() . '/default-templates');

        foreach ($targets->dump() as $target) {
            if ($target->active() && $target->hasPhpCallback()) {
                $target->phpCallback();
            }
        }

        dcCore::app()->addBehavior('publicHeadContent', function (): void {
            if (is_null(dcCore::app()->blog)) {
                return;
            }

            echo
                dcUtils::cssLoad(dcCore::app()->blog->url . dcCore::app()->url->getURLFor('noodles_css')) .
                dcUtils::jsLoad(dcCore::app()->blog->url . dcCore::app()->url->getBase('noodles_file') . '/js/jquery.noodles.js') .
                dcUtils::jsLoad(dcCore::app()->blog->url . dcCore::app()->url->getURLFor('noodles_js'));
        });

        return true;
    }
}
