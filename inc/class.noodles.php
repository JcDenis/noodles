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

class noodles
{
    private $noodles = [];

    public static function decode($s)
    {
        $o = @unserialize(base64_decode($s));
        if ($o instanceof noodles) {
            return $o;
        }

        return new self();
    }

    public function encode()
    {
        return base64_encode(serialize($this));
    }

    public function add($id, $name, $js_callback, $php_callback = null)
    {
        $this->noodles[$id] = new noodle($id, $name, $js_callback, $php_callback);

        return $this->noodles[$id];
    }

    public function get($id)
    {
        return $this->noodles[$id] ?? null;
    }

    public function __get($id)
    {
        return $this->get($id);
    }

    public function set($id, $noodle)
    {
        return $this->noodles[$id] = $noodle;
    }

    public function __set($id, $noodle)
    {
        return $this->set($id, $noodle);
    }

    public function exists($id)
    {
        return isset($this->noodles[$id]);
    }

    public function isEmpty()
    {
        return !count($this->noodles);
    }

    public function noodles()
    {
        return $this->noodles;
    }
}

class noodle
{
    private $id;
    private $name;
    private $js_callback;
    private $php_callback;
    private $settings = [
        'active' => 0,
        'rating' => 'g',
        'size'   => 16,
        'target' => '',
        'place'  => 'prepend'
    ];

    public function __construct($id, $name, $js_callback, $php_callback = null)
    {
        $this->id           = $id;
        $this->name         = $name;
        $this->js_callback  = $js_callback;
        $this->php_callback = $php_callback;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function jsCallback($g, $content = '')
    {
        if (!is_callable($this->js_callback)) {
            return null;
        }

        return call_user_func($this->js_callback, $g, $content);
    }

    public function hasJsCallback()
    {
        return !empty($this->js_callback);
    }

    public function phpCallback($core)
    {
        if (!is_callable($this->php_callback)) {
            return null;
        }

        return call_user_func($this->php_callback, $core, $this);
    }

    public function hasPhpCallback()
    {
        return !empty($this->php_callback);
    }

    public function set($type, $value)
    {
        switch ($type) {
            case 'active':
                $this->settings['active'] = abs((int) $value);

            break;

            case 'rating':
                $this->settings['rating'] = in_array($value, ['g', 'pg', 'r', 'x']) ? $value : 'g';

            break;

            case 'size':
                $this->settings['size'] = in_array($value, [16, 24, 32, 48, 56, 64, 92, 128, 256]) ? $value : 16;

            break;

            case 'css':
                $this->settings['css'] = (string) $value;

            break;

            case 'target':
                $this->settings['target'] = (string) $value;

            break;

            case 'place':
                $this->settings['place'] = in_array($value, ['append', 'prepend', 'before', 'after']) ? $value : 'prepend';

            break;
        }

        return $this;
    }

    public function active($value)
    {
        return $this->set('active', $value);
    }

    public function rating($value)
    {
        return $this->set('rating', $value);
    }

    public function size($value)
    {
        return $this->set('size', $value);
    }

    public function css($value)
    {
        return $this->set('css', $value);
    }

    public function target($value)
    {
        return $this->set('target', $value);
    }

    public function place($value)
    {
        return $this->set('place', $value);
    }

    public function __set($type, $value)
    {
        $this->set($type, $value);
    }

    public function get($type)
    {
        return $this->settings[$type] ?? null;
    }

    public function __get($type)
    {
        return $this->get($type);
    }
}