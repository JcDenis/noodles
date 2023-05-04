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

namespace Dotclear\Plugin\noodles\Target;

use dcCore;
use Dotclear\Plugin\noodles\Target;

/**
 * Target rendering adapt to plugin Widgets.
 */
class Widgets
{
    public static function lastcomments(Target $target, string $content = ''): string
    {
        if (is_null(dcCore::app()->blog)) {
            return '';
        }
        $ok = preg_match('@\#c([0-9]+)$@', urldecode($content), $m);
        if (!$ok || !$m[1]) {
            return '';
        }
        $rs = dcCore::app()->blog->getComments(['no_content' => 1, 'comment_id' => $m[1], 'limit' => 1]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = $rs->f('comment_email');

        return is_string($res) ? $res : '';
    }
}
