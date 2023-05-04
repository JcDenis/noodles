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

namespace Dotclear\Plugin\noodles;

class Combo
{
    /**
     * @return 	array<string,string>
     */
    public static function api(): array
    {
        return [
            'gravatar'   => 'http://www.gravatar.com/',
            'libravatar' => 'http://cdn.libravatar.org/',
        ];
    }

    /**
     * @return 	array<string,int>
     */
    public static function local(): array
    {
        return [
            __('API')   => 0,
            __('Local') => 1,
        ];
    }

    /**
     * @return 	array<string,string>
     */
    public static function place(): array
    {
        return [
            __('Begin')  => 'prepend',
            __('End')    => 'append',
            __('Before') => 'before',
            __('After')  => 'after',
        ];
    }

    /**
     * @return 	array<string,string>
     */
    public static function rating(): array
    {
        return [
            'G'  => 'g',
            'PG' => 'pg',
            'R'  => 'r',
            'X'  => 'x',
        ];
    }

    /**
     * @return 	array<string,int>
     */
    public static function size(): array
    {
        return [
            '16px'  => 16,
            '24px'  => 24,
            '32px'  => 32,
            '48px'  => 48,
            '56px'  => 56,
            '64px'  => 64,
            '92px'  => 92,
            '128px' => 128,
            '256px' => 256,
        ];
    }
}
