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

use dcCore;

/**
 * This module definitions.
 */
class My
{
    /** @var    string  Default image name */
    public const IMAGE = 'default-avatar.png';

    /**
     * This module id.
     */
    public static function id(): string
    {
        return basename(dirname(__DIR__));
    }

    /**
     * This module name.
     */
    public static function name(): string
    {
        $name = dcCore::app()->plugins->moduleInfo(self::id(), 'name');

        return __(is_string($name) ? $name : self::id());
    }

    /**
     * This module path.
     */
    public static function path(): string
    {
        return dirname(__DIR__);
    }
}
