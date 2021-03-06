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

if (!$core->blog->settings->noodles->noodles_active) {
    return null;
}

include dirname(__FILE__) . '/inc/_default_noodles.php';
require_once dirname(__FILE__) . '/inc/_noodles_functions.php';

$core->addBehavior('publicHeadContent', ['publicNoodles', 'publicHeadContent']);

$core->tpl->setPath($core->tpl->getPath(), dirname(__FILE__) . '/default-templates');

global $__noodles;
$__noodles = noodles::decode($core->blog->settings->noodles->noodles_object);

if ($__noodles->isEmpty()) {
    $__noodles = $__default_noodles;
}

//$GLOBALS['__noodles'] =& $__noodles;

foreach ($__noodles->noodles() as $noodle) {
    if ($noodle->active && $noodle->hasPhpCallback()) {
        $noodle->phpCallback($core);
    }
}

class publicNoodles
{
    public static function publicHeadContent($core)
    {
        echo
            dcUtils::cssLoad($core->blog->url . $core->url->getURLFor('noodlescss')) .
            dcUtils::jsLoad($core->blog->url . $core->url->getBase('noodlesmodule') . '/js/jquery.noodles.js') .
            dcUtils::jsLoad($core->blog->url . $core->url->getURLFor('noodlesjs'));
    }
}

class urlNoodles extends dcUrlHandlers
{
    public static function css($args)
    {
        global $core, $__noodles;

        $css = '';
        foreach ($__noodles->noodles() as $noodle) {
            if (!$noodle->active || !$noodle->hasJsCallback()) {
                continue;
            }
            $css .= '.noodles-' . $noodle->id() . '{' . $noodle->css . '}' . "\n";
        }

        header('Content-Type: text/css; charset=UTF-8');

        echo $css;

        exit;
    }

    public static function js($args)
    {
        global $core, $__noodles;

        $targets = [];
        foreach ($__noodles->noodles() as $noodle) {
            if (!$noodle->active || !$noodle->hasJsCallback()) {
                continue;
            }
            $targets[] = '$(\'' . html::escapeJS($noodle->target) . '\').noodles({' .
                '  imgId:\'' . html::escapeJS($noodle->id()) . '\',' .
                '  imgPlace:\'' . html::escapeJS($noodle->place) . '\'' .
                '});';
        }

        header('Content-Type: text/javascript; charset=UTF-8');

        echo
        "\$(function(){if(!document.getElementById){return;} \n" .
        "\$.fn.noodles.defaults.service_url = '" . html::escapeJS($core->blog->url . $core->url->getBase('noodlesservice') . '/') . "'; \n" .
        "\$.fn.noodles.defaults.service_func = '" . html::escapeJS('getNoodle') . "'; \n" .
        implode("\n", $targets) .
        "})\n";

        exit;
    }

    public static function service($args)
    {
        global $core;

        header('Content-Type: text/xml; charset=UTF-8');

        $rsp = new xmlTag('rsp');

        $i = !empty($_POST['noodleId']) ? $_POST['noodleId'] : null;
        $c = !empty($_POST['noodleContent']) ? $_POST['noodleContent'] : null;

        if (!$core->blog->settings->noodles->noodles_active) {
            $rsp->status = 'failed';
            $rsp->message(__('noodles is disabled on this blog'));
            echo $rsp->toXML(1);

            return false;
        }
        if ($i === null || $c === null) {
            $rsp->status = 'failed';
            $rsp->message(__('noodles failed because of missing informations'));
            echo $rsp->toXML(1);

            return false;
        }

        try {
            $__noodles = noodles::decode($core->blog->settings->noodles->noodles_object);

            if ($__noodles->isEmpty()) {
                $__noodles = $GLOBALS['__default_noodles'];
            }
        } catch (Excetpion $e) {
            $rsp->status = 'failed';
            $rsp->message(__('Failed to load default noodles'));
            echo $rsp->toXML(1);

            return false;
        }

        if (!$__noodles->exists($i)) {
            $rsp->status = 'failed';
            $rsp->message(__('Failed to load noodle'));
            echo $rsp->toXML(1);

            return false;
        }

        $m = $__noodles->get($i)->jsCallback($__noodles->get($i), $c);
        $s = $__noodles->get($i)->size;
        $r = $__noodles->get($i)->rating;
        $d = $core->blog->settings->noodles->noodles_image ?
            urlencode(noodlesLibImagePath::getUrl($core, 'noodles')) : '';

        $u = $core->blog->settings->noodles->noodles_api;
        if (empty($u)) {
            $u = 'http://www.gravatar.com/';
        }
        if (!$m) {
            $m = 'nobody@nowhere.tld';
        }
        if (!$s) {
            $s = 32;
        }
        if (!$r) {
            $r = 'g';
        }

        $m        = md5(strtolower(trim($m)));
        $im       = new xmlTag('noodle');
        $im->size = $s;
        $im->src  = sprintf('%savatar/%s?s=%s&amp;r=%s&amp;d=%s', $u, $m, $s, $r, $d);
        $rsp->insertNode($im);

        $rsp->status = 'ok';
        echo $rsp->toXML(1);
        exit;
    }

    public static function noodles($args)
    {
        global $core;

        if (!$core->blog->settings->noodles->noodles_active) {
            self::p404();

            return;
        }

        if (!preg_match('#^(.*?)$#', $args, $m)) {
            self::p404();

            return;
        }

        $f = $m[1];

        if (!($f = self::searchTplFiles($f))) {
            self::p404();

            return;
        }

        $allowed_types = ['png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'swf'];
        if (!in_array(files::getExtension($f), $allowed_types)) {
            self::p404();

            return;
        }
        $type = files::getMimeType($f);

        header('Content-Type: ' . $type . '; charset=UTF-8');
        header('Content-Length: ' . filesize($f));

        if ($type != 'text/css' || $core->blog->settings->system->url_scan == 'path_info') {
            readfile($f);
        } else {
            echo preg_replace(
                '#url\((?!(http:)|/)#',
                'url(' . $core->blog->url . $core->url->getBase('noodlesmodule') . '/',
                file_get_contents($f)
            );
        }
        exit;
    }

    # Search noodles files like JS, CSS in default-templates subdirectories
    private static function searchTplFiles($file)
    {
        if (strstr($file, '..') !== false) {
            return false;
        }
        $paths = $GLOBALS['core']->tpl->getPath();

        foreach ($paths as $path) {
            if (preg_match('/tpl(\/|)$/', $path)) {
                $path = path::real($path . '/..');
            }
            if (file_exists($path . '/' . $file)) {
                return $path . '/' . $file;
            }
        }

        return false;
    }
}