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
if (!defined('DC_RC_PATH')) {
    return null;
}

require dirname(__FILE__) . '/class.noodles.php';

global $__default_noodles;
$__default_noodles = new noodles();

# Posts (by public behavior)
$__default_noodles
    ->add('posts', __('Entries'), '', ['othersNoodles', 'publicPosts'])
    ->size(48)
    ->css('float:right;margin:4px;');

# Comments (by public behavior)
$__default_noodles
    ->add('comments', __('Comments'), '', ['othersNoodles', 'publicComments'])
    ->active(true)
    ->size(48)
    ->css('float:left;margin:4px;');

# Block with post title link (like homepage posts)
$__default_noodles
    ->add('titlesposts', __('Entries titles'), ['genericNoodles', 'postURL'])
    ->target('.post-title a')
    ->css('margin-right:2px;');

if ($core->plugins->moduleExists('widgets')) {
    # Widget Selected entries
    $__default_noodles
        ->add('bestof', __('Selected entries'), ['genericNoodles', 'postURL'])
        ->target('.selected li a')
        ->css('margin-right:2px;');

    # Widget Last entries
    $__default_noodles
        ->add('lastposts', __('Last entries'), ['genericNoodles', 'postURL'])
        ->target('.lastposts li a')
        ->css('margin-right:2px;');

    # Widget Last comments
    $__default_noodles
        ->add('lastcomments', __('Last comments'), ['widgetsNoodles', 'lastcomments'])
        ->active(true)
        ->target('.lastcomments li a')
        ->css('margin-right:2px;');
}

# Plugin auhtorMode
if ($core->plugins->moduleExists('authorMode')
    && $core->blog->settings->authormode->authormode_active
) {
    $__default_noodles
        ->add('authorswidget', __('Authors widget'), ['authormodeNoodles', 'authors'])
        ->target('#authors ul li a')
        ->css('margin-right:2px;');

    $__default_noodles
        ->add('author', __('Author'), '', ['authormodeNoodles', 'author'])
        ->active(true)
        ->size(48)
        ->target('.dc-author #content-info h2')
        ->css('clear:left; float:left;margin-right:2px;');

    $__default_noodles
        ->add('authors', __('Authors'), ['authormodeNoodles', 'authors'])
        ->active(true)
        ->size(32)
        ->target('.dc-authors .author-info h2 a')
        ->css('clear:left; float:left; margin:4px;');
}

# Plugin rateIt
if ($core->plugins->moduleExists('rateIt')
    && $core->blog->settings->rateit->rateit_active
) {
    $__default_noodles
        ->add('rateitpostsrank', __('Top rated entries'), ['genericNoodles', 'postURL'])
        ->target('.rateitpostsrank.rateittypepost ul li a') // Only "post" type
        ->css('margin-right:2px;');
}

# Plugin lastpostsExtend
if ($core->plugins->moduleExists('lastpostsExtend')) {
    $__default_noodles
        ->add('lastpostsextend', __('Last entries (extend)'), ['genericNoodles', 'postURL'])
        ->target('.lastpostsextend ul li a')
        ->css('margin-right:2px;');
}

# --BEHAVIOR-- initDefaultNoodles
$core->callBehavior('initDefaultNoodles', $__default_noodles);