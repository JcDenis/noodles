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

class noodlesLibImagePath
{
    public static $version = '1.1';

    public static function getArray($m = '')
    {
        if (!dcCore::app()->plugins->moduleExists($m)
            || !dcCore::app()->url->getBase($m . 'module')
        ) {
            return [
                'theme'  => ['dir' => null, 'url' => null],
                'public' => ['dir' => null, 'url' => null],
                'module' => ['dir' => null, 'url' => null],
            ];
        }

        return [
            'theme' => [
                'dir' => path::real(dcCore::app()->blog->themes_path . '/' . dcCore::app()->blog->settings->system->theme . '/img') . '/' . $m . '-default-image.png',
                'url' => dcCore::app()->blog->settings->system->themes_url . dcCore::app()->blog->settings->system->theme . '/img/' . $m . '-default-image.png',
            ],
            'public' => [
                'dir' => path::real(dcCore::app()->blog->public_path) . '/' . $m . '-default-image.png',
                'url' => dcCore::app()->blog->host . path::clean(dcCore::app()->blog->settings->system->public_url) . '/' . $m . '-default-image.png',
            ],
            'module' => [
                'dir' => path::real(dcCore::app()->plugins->moduleRoot($m) . '/default-templates/img') . '/' . $m . '-default-image.png',
                'url' => dcCore::app()->blog->url . dcCore::app()->url->getBase($m . 'module') . '/img/' . $m . '-default-image.png',
            ],
        ];
    }

    public static function getUrl($m = '')
    {
        $files = self::getArray($m);
        foreach ($files as $k => $file) {
            if (file_exists($files[$k]['dir'])) {
                return $files[$k]['url'];
            }
        }

        return null;
    }

    public static function getPath($m = '')
    {
        $files = self::getArray($m);
        foreach ($files as $k => $file) {
            if (file_exists($files[$k]['dir'])) {
                return $files[$k]['dir'];
            }
        }

        return null;
    }

    public static function getSize($m = '')
    {
        if (!($img = self::getPath($m))) {
            return ['w' => 16, 'h' => 16];
        }
        $info = getimagesize($img);

        return ['w' => $info[0], 'h' => floor($info[1] / 3)];
    }
}
