<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\Core\Process;

/**
 * @brief   noodles backend class.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Backend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        My::addBackendMenuItem();

        return true;
    }
}
