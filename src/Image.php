<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Dotclear\Helper\File\Path;

/**
 * @brief   noodles image helper.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Image
{
    /**
     * The current class major version.
     *
     * @var     string  VERSION
     */
    public const VERSION = '2';

    /**
     * @return  array<string, array{dir: null|string, url: null|string}>    The Image possible paths
     */
    public static function getArray(): array
    {
        if (!App::blog()->isDefined() || !App::url()->getBase('noodles_file')) {
            return [
                'theme'  => ['dir' => null, 'url' => null],
                'public' => ['dir' => null, 'url' => null],
                'module' => ['dir' => null, 'url' => null],
            ];
        }

        $public_url = App::blog()->settings()->get('system')->get('public_url');
        if (!is_string($public_url)) {
            $public_url = '';
        }

        return [
            'theme' => [
                'dir' => Path::real(App::blog()->themesPath() . '/' . App::blog()->settings()->get('system')->get('theme') . '/img') . '/' . My::IMAGE,
                'url' => App::blog()->settings()->get('system')->get('themes_url') . App::blog()->settings()->get('system')->get('theme') . '/img/' . My::IMAGE,
            ],
            'public' => [
                'dir' => Path::real(App::blog()->publicPath()) . '/' . My::IMAGE,
                'url' => App::blog()->host() . Path::clean($public_url) . '/' . My::IMAGE,
            ],
            'module' => [
                'dir' => Path::real(My::path() . '/default-templates/img') . '/' . My::IMAGE,
                'url' => App::blog()->url() . App::url()->getBase('noodles_file') . '/img/' . My::IMAGE,
            ],
        ];
    }

    /**
     * @return  null|string  The image URL
     */
    public static function getUrl(): ?string
    {
        $files = self::getArray();
        foreach ($files as $k => $file) {
            if (is_string($files[$k]['dir']) && file_exists($files[$k]['dir'])) {
                return $files[$k]['url'];
            }
        }

        return null;
    }

    /**
     * @return  null|string  The image path
     */
    public static function getPath(): ?string
    {
        $files = self::getArray();
        foreach ($files as $k => $file) {
            if (is_string($files[$k]['dir']) && file_exists($files[$k]['dir'])) {
                return $files[$k]['dir'];
            }
        }

        return null;
    }
}
