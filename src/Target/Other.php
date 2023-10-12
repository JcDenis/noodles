<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles\Target;

use Dotclear\App;
use Dotclear\Database\MetaRecord;
use Dotclear\Plugin\noodles\{
    Image,
    My,
    Targets,
    Target
};

/**
 * @brief   noodles target complete rendreing.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Other
{
    public static function publicPosts(Target $target): void
    {
        if (!$target->active()) {
            return;
        }
        $bhv = $target->place() == 'prepend' || $target->place() == 'before' ?
            'publicEntryBeforeContent' : 'publicEntryAfterContent';

        App::behavior()->addBehavior($bhv, self::publicEntryContent(...));
    }

    public static function publicEntryContent(): void
    {
        $targets = Targets::instance();
        $target  = $targets->get('posts');

        if (is_null($target)
            || !isset(App::frontend()->ctx)
            || !App::blog()->isDefined()
            || App::frontend()->ctx->__get('current_tpl') != 'post.html'
        ) {
            return;
        }

        $m = App::frontend()->ctx->__get('posts');
        if (!($m instanceof MetaRecord)) {
            return;
        }
        $m = $m->__call('getAuthorEmail', [false]);
        if (!is_string($m)) {
            return;
        }

        echo
        '<img class="noodles-posts" style="width:' . $target->size() . 'px;height:' . $target->size() . 'px;' . $target->css() . '"' .
        'src="http://www.gravatar.com/avatar/' . md5($m) .
        '?s=' . $target->size() . '&amp;r=' . $target->rating() . '&amp;d=' . ($targets->local ? urlencode((string) Image::getUrl()) : '') . '" alt="" />';
    }

    public static function publicComments(Target $target): void
    {
        if (!$target->active()) {
            return;
        }

        $bhv = $target->place() == 'prepend' || $target->place() == 'before' ?
            'publicCommentBeforeContent' : 'publicCommentAfterContent';

        App::behavior()->addBehavior($bhv, self::publicCommentContent(...));
    }

    public static function publicCommentContent(): void
    {
        $targets = Targets::instance();
        $target  = $targets->get('comments');

        if (is_null($target)
            || !isset(App::frontend()->ctx)
            || !App::blog()->isDefined()
            || App::frontend()->ctx->__get('current_tpl') != 'post.html'
        ) {
            return;
        }

        $m = App::frontend()->ctx->__get('comments');
        if (!($m instanceof MetaRecord)) {
            return;
        }
        $m = $m->__call('getEmail', [false]);
        if (!is_string($m)) {
            return;
        }

        echo
        '<img class="noodles-comments" style="width:' . $target->size() . 'px;height:' . $target->size() . 'px;' . $target->css() . '"' .
        'src="http://www.gravatar.com/avatar/' . md5($m) .
        '?s=' . $target->size() . '&amp;r=' . $target->rating() . '&amp;d=' . ($targets->local ? urlencode((string) Image::getUrl()) : '') . '" alt="" />';
    }
}
