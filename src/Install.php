<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Dotclear\Core\Process;
use Exception;

/**
 * @brief   noodles install class.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            $s = My::settings();
            $s->put('active', false, 'boolean', 'enable module', false, true);
            $s->put('api', 'http://www.gravatar.com/', 'string', 'external API', false, true);
            $s->put('local', false, 'boolean', 'use local image', false, true);
            $s->put('settings', '[]', 'string', 'noodles settings', false, true);

            return true;
        } catch (Exception $e) {
            App::error()->add($e->getMessage());

            return false;
        }
    }
}
