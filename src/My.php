<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Dotclear\Module\MyPlugin;

/**
 * @brief   noodles My helper.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class My extends MyPlugin
{
    /**
     * Default image name.
     *
     * @var     string  IMAGE
     */
    public const IMAGE = 'default-avatar.png';

    protected static function checkCustomContext(int $context): ?bool
    {
        return match ($context) {
            // Add content admin perm to backend
            self::MANAGE, self::MENU => App::task()->checkContext('BACKEND')
                && App::auth()->check(App::auth()->makePermissions([
                    App::auth()::PERMISSION_CONTENT_ADMIN,
                ]), App::blog()->id()),

            default => null,
        };
    }
}
