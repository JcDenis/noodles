<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief   noodles frontend class.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $targets = Targets::instance();

        if (!$targets->active) {
            return false;
        }

        App::frontend()->template()->appendPath(My::path() . '/default-templates');

        foreach ($targets->dump() as $target) {
            if ($target->active() && $target->hasPhpCallback()) {
                $target->phpCallback();
            }
        }

        App::behavior()->addBehavior('publicHeadContent', function (): void {
            if (!App::blog()->isDefined()) {
                return;
            }

            echo
                App::plugins()->cssLoad(App::blog()->url() . App::url()->getURLFor('noodles_css')) .
                App::plugins()->jsLoad(App::blog()->url() . App::url()->getBase('noodles_file') . '/js/jquery.noodles.js') .
                App::plugins()->jsLoad(App::blog()->url() . App::url()->getURLFor('noodles_js'));
        });

        return true;
    }
}
