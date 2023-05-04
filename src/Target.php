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

/**
 * Target definition.
 */
class Target
{
    /** @var   bool    The noodle activation */
    private bool $active = false;

    /** @var    string  The noodle rating */
    private string $rating = 'g';

    /** @var    int     The noodle size */
    private int $size = 16;

    /** @var    string The noodle css */
    private string $css = '';

    /** @var    string  The noodle target */
    private string $target = '';

    /** @var    string  The noodle place */
    private string $place = 'prepend';

    /**
     * Constructor sets main properties.
     *
     * @param   string  $id             The noodle ID
     * @param   string  $name           The noodle name
     * @param   mixed   $js_callback    The js callback
     * @param   mixed   $php_callback   The php callback
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly mixed $js_callback = null,
        public readonly mixed $php_callback = null
    ) {
    }

    /**
     * Export settings in order to save it to blog settings.
     *
     * @return  array<string,mixed>     The settings
     */
    public function exportSettings(): array
    {
        return get_object_vars($this);
    }

    /**
     * Import noodle settings from blog settings.
     *
     * @param   array<string,mixed>     $settings   The settings
     *
     * @return  bool    True on success
     */
    public function importSettings(array $settings): bool
    {
        if ($this->id !== ($settings['id'] ?? null)) {
            return false;
        }

        $this->setActive($settings['active'] ?? $this->active());
        $this->setRating($settings['rating'] ?? $this->rating());
        $this->setSize($settings['size'] ?? $this->size());
        $this->setCss($settings['css'] ?? $this->css());
        $this->settarget($settings['target'] ?? $this->target());
        $this->setPlace($settings['place'] ?? $this->place());

        return true;
    }

    public function jsCallback(string $content = ''): string
    {
        if (is_callable($this->js_callback)) {
            $res = call_user_func($this->js_callback, $this, $content);

            return is_string($res) ? $res : '';
        }

        return '';
    }

    public function hasJsCallback(): bool
    {
        return !empty($this->js_callback);
    }

    public function phpCallback(): void
    {
        if (is_callable($this->php_callback)) {
            call_user_func($this->php_callback, $this);
        }
    }

    public function hasPhpCallback(): bool
    {
        return !empty($this->php_callback);
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function setActive(mixed $value): Target
    {
        $this->active = !empty($value);

        return $this;
    }

    public function rating(): string
    {
        return $this->rating;
    }

    public function setRating(mixed $value): Target
    {
        if (is_string($value) && in_array($value, ['g', 'pg', 'r', 'x'])) {
            $this->rating = $value;
        }

        return $this;
    }

    public function size(): int
    {
        return (int) $this->size;
    }

    public function setSize(mixed $value): Target
    {
        if (is_numeric($value) && in_array((int) $value, [16, 24, 32, 48, 56, 64, 92, 128, 256])) {
            $this->size = (int) $value;
        }

        return $this;
    }

    public function css(): string
    {
        return $this->css;
    }

    public function setCss(mixed $value): Target
    {
        if (is_string($value)) {
            $this->css = $value;
        }

        return $this;
    }

    public function target(): string
    {
        return $this->target;
    }

    public function setTarget(mixed $value): Target
    {
        if (is_string($value)) {
            $this->target = $value;
        }

        return $this;
    }

    public function place(): string
    {
        return $this->place;
    }

    public function setPlace(mixed $value): Target
    {
        if (is_string($value) && in_array($value, ['append', 'prepend', 'before', 'after'])) {
            $this->place = $value;
        }

        return $this;
    }
}
