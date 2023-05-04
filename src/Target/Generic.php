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
 * Target generic rendering.
 */
class Generic
{
    public static function postURL(Target $target, string $content = ''): string
    {
        if (is_null(dcCore::app()->blog)) {
            return '';
        }
        $types = dcCore::app()->getPostTypes();
        $reg   = '@^' . str_replace('%s', '(.*?)', preg_quote(dcCore::app()->blog->url . $types['post']['public_url'])) . '$@';
        $ok    = preg_match($reg, $content, $m);
        if (!$ok || !$m[1]) {
            return '';
        }
        $rs = dcCore::app()->blog->getPosts(['no_content' => 1, 'post_url' => urldecode($m[1]), 'limit' => 1]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = $rs->f('user_email');

        return is_string($res) ? $res : '';
    }
}
