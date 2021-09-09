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

    public static function getArray($core, $m = '')
    {
        if (!$core->plugins->moduleExists($m)
            || !$core->url->getBase($m . 'module')
        ) {
            return [
                'theme' => ['dir' => null, 'url' => null],
                'public' => ['dir' => null, 'url' => null],
                'module' => ['dir' => null, 'url' => null],
            ];
        }

        return [
            'theme' => [
                'dir' => path::real($core->blog->themes_path . '/' . $core->blog->settings->system->theme . '/img') . '/' . $m . '-default-image.png',
                'url' => $core->blog->settings->system->themes_url . $core->blog->settings->system->theme . '/img/' . $m . '-default-image.png'
            ],
            'public' => [
                'dir' => path::real($core->blog->public_path) . '/' . $m . '-default-image.png',
                'url' => $core->blog->host . path::clean($core->blog->settings->system->public_url) . '/' . $m . '-default-image.png'
            ],
            'module' => [
                'dir' => path::real($core->plugins->moduleRoot($m) . '/default-templates/img') . '/' . $m . '-default-image.png',
                'url' => $core->blog->url . $core->url->getBase($m . 'module') . '/img/' . $m . '-default-image.png'
            ]
        ];
    }

    public static function getUrl($core, $m = '')
    {
        $files = self::getArray($core, $m);
        foreach($files as $k => $file) {
            if (file_exists($files[$k]['dir']))
                return $files[$k]['url'];
        }
        return null;
    }

    public static function getPath($core, $m = '')
    {
        $files = self::getArray($core, $m);
        foreach($files as $k => $file) {
            if (file_exists($files[$k]['dir']))
                return $files[$k]['dir'];
        }
        return null;
    }

    public static function getSize($core, $m = '')
    {
        if (!($img = self::getPath($core, $m)))
            return ['w' => 16, 'h' => 16];
        else {
            $info = getimagesize($img);
            return ['w' => $info[0], 'h' => floor($info[1] /3)];
        }
    }
}