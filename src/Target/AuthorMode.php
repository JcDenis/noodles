<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles\Target;

use Dotclear\App;
use Dotclear\Database\MetaRecord;
use Dotclear\Plugin\noodles\Image;
use Dotclear\Plugin\noodles\My;
use Dotclear\Plugin\noodles\Targets;
use Dotclear\Plugin\noodles\Target;

/**
 * @brief   noodles target rendreing for plugin authorMode.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class AuthorMode
{
    public static function authors(Target $target, string $content = ''): string
    {
        $ok = preg_match('@\/([^\/]*?)$@', $content, $m);
        if (!$ok || !$m[1]) {
            return '';
        }
        $rs = App::users()->getUser($m[1]);
        if ($rs->isEmpty()) {
            return '';
        }

        $res = $rs->f('user_email');

        return is_string($res) ? $res : '';
    }

    public static function author(Target $target): void
    {
        if ($target->active()) {
            App::behavior()->addBehavior('publicHeadContent', self::publicHeadContent(...));
        }
    }

    public static function publicHeadContent(): void
    {
        $targets = Targets::instance();
        $target  = $targets->get('author');

        if (is_null($target)
            || !App::blog()->isDefined()
            || App::frontend()->context()->__get('current_tpl') != 'author.html'
        ) {
            return;
        }

        $u = App::frontend()->context()->__get('users');
        if (!($u instanceof MetaRecord)) {
            return;
        }
        $u = $u->f('user_id');
        if (!is_string($u)) {
            return;
        }
        $u = App::users()->getUser($u)->f('user_email');
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
