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
use Dotclear\Database\MetaRecord;
use Dotclear\Plugin\noodles\{
    Image,
    My,
    Targets,
    Target
};

/**
 * Target rendering adapt to plugin authorMode.
 */
class AuthorMode
{
    public static function authors(Target $target, string $content = ''): string
    {
        $ok = preg_match('@\/([^\/]*?)$@', $content, $m);
        if (!$ok || !$m[1]) {
            return '';
        }
        $rs = dcCore::app()->getUser($m[1]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = $rs->f('user_email');

        return is_string($res) ? $res : '';
    }

    public static function author(Target $target): void
    {
        if ($target->active()) {
            dcCore::app()->addBehavior('publicHeadContent', [self::class, 'publicHeadContent']);
        }
    }

    public static function publicHeadContent(): void
    {
        $targets = Targets::instance();
        $target  = $targets->get('author');

        if (is_null($target)
            || is_null(dcCore::app()->ctx)
            || is_null(dcCore::app()->blog)
            || dcCore::app()->ctx->__get('current_tpl') != 'author.html'
        ) {
            return;
        }

        $u = dcCore::app()->ctx->__get('users');
        if (!($u instanceof MetaRecord)) {
            return;
        }
        $u = $u->f('user_id');
        if (!is_string($u)) {
            return;
        }
        $u = dcCore::app()->getUser($u)->f('user_email');
        if (!is_string($u)) {
            $u = '';
        }
        $d = $targets->local ? urlencode((string) Image::getUrl()) : '';

        echo
        '<script type="text/javascript">' . "\n" .
        "//<![CDATA[\n" .
        "$(function(){if(!document.getElementById){return;}\n" .
        "$('" . $target->target() . "')." . $target->place() . "('" .
        '<img class="noodles-comments" style="width:' . $target->size() . 'px;height:' . $target->size() . 'px;' . $target->css() . '"' .
            'src="http://www.gravatar.com/avatar/' . md5($u) .
            '?s=' . $target->size() . '&amp;r=' . $target->rating() . '&amp;d=' . $d . '" alt="" />' .
        "');});" .
        "\n//]]>\n" .
        "</script>\n";
    }
}
