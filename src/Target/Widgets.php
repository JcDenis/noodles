<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles\Target;

use Dotclear\App;
use Dotclear\Plugin\noodles\Target;

/**
 * @brief   noodles target rendreing for plugin widgets.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Widgets
{
    public static function lastcomments(Target $target, string $content = ''): string
    {
        if (!App::blog()->isDefined()) {
            return '';
        }
        $ok = preg_match('@\#c([0-9]+)$@', urldecode($content), $m);
        if (!$ok || !$m[1]) {
            return '';
        }
        $rs = App::blog()->getComments(['no_content' => 1, 'comment_id' => $m[1], 'limit' => 1]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = $rs->f('comment_email');

        return is_string($res) ? $res : '';
    }
}
