<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles\Target;

use Dotclear\App;
use Dotclear\Plugin\noodles\Target;

/**
 * @brief   noodles target generic rendering.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Generic
{
    public static function postURL(Target $target, string $content = ''): string
    {
        if (!App::blog()->isDefined()) {
            return '';
        }
        $reg = '@^' . str_replace('%s', '(.*?)', preg_quote(App::blog()->url() . App::postTypes()->get('post')->public_url)) . '$@';
        $ok  = preg_match($reg, $content, $m);
        if (!$ok || !$m[1]) {
            return '';
        }
        $rs = App::blog()->getPosts(['no_content' => 1, 'post_url' => urldecode($m[1]), 'limit' => 1]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = $rs->f('user_email');

        return is_string($res) ? $res : '';
    }
}
