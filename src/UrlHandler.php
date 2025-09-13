<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Dotclear\Helper\File\Files;
use Dotclear\Helper\File\Path;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Html\XmlTag;
use Exception;

/**
 * @brief   noodles URL handler.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class UrlHandler
{
    public static function css(?string $args): void
    {
        $css = '';
        foreach (Targets::instance()->dump() as $target) {
            if (!$target->active() || !$target->hasJsCallback()) {
                continue;
            }
            $css .= '.noodles-' . $target->id . '{' . $target->css() . '}' . "\n";
        }

        header('Content-Type: text/css; charset=UTF-8');

        echo $css;

        exit;
    }

    public static function js(?string $args): void
    {
        if (!App::blog()->isDefined()) {
            App::url()::p404();
        }

        $targets = [];
        foreach (Targets::instance()->dump() as $target) {
            if (!$target->active() || !$target->hasJsCallback()) {
                continue;
            }
            $targets[] = '$(\'' . Html::escapeJS($target->target()) . '\').noodles({' .
                '  imgId:\'' . Html::escapeJS($target->id) . '\',' .
                '  imgPlace:\'' . Html::escapeJS($target->place()) . '\'' .
                '});';
        }

        header('Content-Type: text/javascript; charset=UTF-8');

        echo
        "\$(function(){if(!document.getElementById){return;} \n" .
        "\$.fn.noodles.defaults.service_url = '" . Html::escapeJS(App::blog()->url() . App::url()->getBase('noodles_service') . '/') . "'; \n" .
        "\$.fn.noodles.defaults.service_func = '" . Html::escapeJS('getNoodle') . "'; \n" .
        implode("\n", $targets) .
        "})\n";

        exit;
    }

    public static function service(string $args): void
    {
        if (!App::blog()->isDefined()) {
            App::url()::p404();
        }

        header('Content-Type: text/xml; charset=UTF-8');

        $targets = Targets::instance();
        $rsp     = new XmlTag('rsp');

        $i = !empty($_POST['noodleId']) ? $_POST['noodleId'] : null;
        $c = !empty($_POST['noodleContent']) ? $_POST['noodleContent'] : null;

        if (!$targets->active) {
            $rsp->insertAttr('status', 'failed');
            $rsp->insertNode(new XmlTag('message', __('noodles is disabled on this blog')));
            echo $rsp->toXML(true);

            return;
        }
        if ($i === null || $c === null) {
            $rsp->insertAttr('status', 'failed');
            $rsp->insertNode(new XmlTag('message', __('noodles failed because of missing informations')));
            echo $rsp->toXML(true);

            return;
        }

        try {
            $target = $targets->get($i);
        } catch (Exception $e) {
            $rsp->insertAttr('status', 'failed');
            $rsp->insertNode(new XmlTag('message', __('Failed to load default noodles')));
            echo $rsp->toXML(true);

            return;
        }

        if (is_null($target)) {
            $rsp->insertAttr('status', 'failed');
            $rsp->insertNode(new XmlTag('message', __('Failed to load noodle')));
            echo $rsp->toXML(true);

            return;
        }

        $m = $target->jsCallback($c);
        $s = $target->size();
        $r = $target->rating();
        $d = $targets->local ? urlencode((string) Image::getUrl()) : '';
        $u = $targets->api;

        if (!$m) {
            $m = 'nobody@nowhere.tld';
        }
        if (!$s) {
            $s = 32;
        }
        if (!$r) {
            $r = 'g';
        }

        $m  = (string) md5(strtolower(trim($m)));
        $im = new XmlTag('noodle');
        $im->insertAttr('size', $s);
        $im->insertAttr('src', sprintf('%savatar/%s?s=%s&amp;r=%s&amp;d=%s', $u, $m, $s, $r, $d));

        $rsp->insertNode($im);
        $rsp->insertAttr('status', 'ok');

        echo $rsp->toXML(true);
        exit;
    }

    public static function file(string $args): void
    {
        if (!App::blog()->isDefined()
            || !Targets::instance()->active
            || str_contains('..', $args)
            || !preg_match('#^([^\?]*)#', $args, $m)
        ) {
            App::url()::p404();
        }

        $f = self::searchTplFiles($m[1]);
        if (empty($f)) {
            App::url()::p404();
        }

        $allowed_types = ['png', 'jpg', 'jpeg', 'gif', 'css', 'js', 'swf'];
        if (!in_array(Files::getExtension($f), $allowed_types)) {
            App::url()::p404();
        }
        $type = Files::getMimeType($f);

        header('Content-Type: ' . $type . '; charset=UTF-8');
        header('Content-Length: ' . filesize($f));

        if ($type != 'text/css' || App::blog()->settings()->get('system')->get('url_scan') == 'path_info') {
            readfile($f);
        } else {
            echo preg_replace(
                '#url\((?!(http:)|/)#',
                'url(' . App::blog()->url() . App::url()->getBase('noodles_file') . '/',
                (string) file_get_contents($f)
            );
        }
        exit;
    }

    # Search noodles files like JS, CSS in default-templates subdirectories
    private static function searchTplFiles(string $file): string
    {
        if (str_contains($file, '..')) {
            return '';
        }
        $paths = App::frontend()->template()->getPath();

        foreach ($paths as $path) {
            if (preg_match('/tpl(\/|)$/', $path)) {
                $path = Path::real($path . '/..');
            }
            if (file_exists($path . '/' . $file)) {
                return $path . '/' . $file;
            }
        }

        return '';
    }
}
