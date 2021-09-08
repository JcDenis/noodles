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

class noodlesLibImagePath
{
    public static $version = '1.1';

    public static function getArray($core,$m='')
    {
        if (!$core->plugins->moduleExists($m)
         || !$core->url->getBase($m.'module')) {
            return array(
                'theme'=>array('dir'=>null,'url'=>null),
                'public'=>array('dir'=>null,'url'=>null),
                'module'=>array('dir'=>null,'url'=>null),
            );
        }

        return array(
            'theme' => array(
                'dir' => $core->blog->themes_path.'/'.$core->blog->settings->system->theme.'/img/'.$m.'-default-image.png',
                'url' => $core->blog->settings->system->themes_url.$core->blog->settings->system->theme.'/img/'.$m.'-default-image.png'
            ),
            'public' => array(
                'dir' => $core->blog->public_path.'/'.$m.'-default-image.png',
                'url' => $core->blog->host.path::clean($core->blog->settings->system->public_url).'/'.$m.'-default-image.png'
            ),
            'module' => array(
                'dir' => $core->plugins->moduleRoot($m).'/default-templates/img/'.$m.'-default-image.png',
                'url' => $core->blog->url.$core->url->getBase($m.'module').'/img/'.$m.'-default-image.png'
            )
        );
    }

    public static function getUrl($core,$m='')
    {
        $files = self::getArray($core,$m);
        foreach($files as $k => $file) {
            if (file_exists($files[$k]['dir']))
                return $files[$k]['url'];
        }
        return null;
    }

    public static function getPath($core,$m='')
    {
        $files = self::getArray($core,$m);
        foreach($files as $k => $file) {
            if (file_exists($files[$k]['dir']))
                return $files[$k]['dir'];
        }
        return null;
    }

    public static function getSize($core,$m='')
    {
        if (!($img = self::getPath($core,$m)))
            return array('w'=>16,'h'=>16);
        else {
            $info = getimagesize($img);
            return array('w'=>$info[0],'h'=>floor($info[1] /3));
        }
    }
}