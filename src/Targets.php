<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Exception;

/**
 * @brief   noodles targets stack.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
final class Targets
{
    /**
     * The noodles instance.
     *
     * @var     Targets     $instance
     */
    private static Targets $instance;

    /**
     * The activation.
     *
     * @var     bool    $active
     */
    public readonly bool $active;

    /**
     * The API URL.
     *
     * @var     string  $api
     */
    public readonly string $api;

    /**
     * Use local image.
     *
     * @var     bool    $local
     */
    public readonly bool $local;

    /**
     * The noodles stack.
     *
     * @var     array<string,Target>    $targets
     */
    private array $targets = [];

    /**
     * Constructor load noodles from behaviors.
     */
    protected function __construct()
    {
        // try to read main settings
        $active = $api = $local = null;
        $s      = My::settings();
        $active = $s->get('active');
        $api    = $s->get('api');
        $local  = $s->get('local');

        // set main settings
        $this->active = is_bool($active) ? $active : false;
        $this->api    = is_string($api) ? $api : 'http://www.gravatar.com/';
        $this->local  = is_bool($local) ? $local : false;

        // add noodles
        App::behavior()->callBehavior('TargetsConstruct', $this);

        // add default noodles
        $this->registerDefault();

        // set noodles settings
        $this->import();
    }

    protected function __clone()
    {
        throw new Exception('nope');
    }

    public function __wakeup()
    {
        throw new Exception('nope');
    }

    /**
     * Get singleton instance.
     *
     * @return  Targets     Targets instance
     */
    public static function instance(): Targets
    {
        if (!isset(self::$instance)) {
            self::$instance = new Targets();
        }

        return self::$instance;
    }

    /**
     * Import noodles settings from blog settings.
     */
    public function import(): void
    {
        $s = My::settings()->get('settings');
        if (!is_string($s)) {
            return;
        }

        $targets = json_decode($s, true);
        if (!is_array($targets)) {
            return;
        }

        foreach ($targets as $id => $settings) {
            if (is_array($settings) && isset($this->targets[$id])) {
                $this->targets[$id]->importSettings($settings);
            }
        }
    }

    /**
     * Export noodles settings to blog settings.
     */
    public function export(): void
    {
        $targets = [];
        foreach ($this->targets as $target) {
            $targets[$target->id] = $target->exportSettings();
        }

        My::settings()->put('settings', json_encode($targets), 'string');
    }

    /**
     * Get a noodle.
     *
     * @param   string  $id     The noodle ID
     *
     * @return null|Target  The noodle instance
     */
    public function get(string $id): ?Target
    {
        return $this->targets[$id] ?? null;
    }

    /**
     * Add a noodle to the stack.
     *
     * @param   Target  $target     The noodle instance
     *
     * @return  Targets     The noodles stack
     */
    public function set(Target $target): Targets
    {
        $this->targets[$target->id] = $target;

        return $this;
    }

    /**
     * Check if a noodle exists.
     *
     * @param   string  $id     The noodle ID
     *
     * @return  bool    True on exists
     */
    public function exists(string $id): bool
    {
        return isset($this->targets[$id]);
    }

    /**
     * Get all noodles.
     *
     * @return  array<string,Target>    The noodles stack
     */
    public function dump(): array
    {
        return $this->targets;
    }

    /**
     * Add default noodles to the stack.
     */
    private function registerDefault(): void
    {
        if (!App::blog()->isDefined()) {
            return;
        }

        # Posts (by public behavior)
        $this->set(
            (new Target(
                id:           'posts',
                name:         __('Entries'),
                php_callback: Target\Other::publicPosts(...)
            ))
            ->setSize(48)
            ->setCss('float:right;margin:4px;')
        );

        # Comments (by public behavior)
        $this->set(
            (new Target(
                id:           'comments',
                name:         __('Comments'),
                php_callback: Target\Other::publicComments(...)
            ))
            ->setActive(true)
            ->setSize(48)
            ->setCss('float:left;margin:4px;')
        );

        # Block with post title link (like homepage posts)
        $this->set(
            (new Target(
                id:          'titlesposts',
                name:        __('Entries titles'),
                js_callback: Target\Generic::postURL(...)
            ))
            ->setTarget('.post-title a')
            ->setCss('margin-right:2px;')
        );

        if (App::plugins()->moduleExists('widgets')) {
            # Widget Selected entries
            $this->set(
                (new Target(
                    id:          'bestof',
                    name:        __('Selected entries'),
                    js_callback: Target\Generic::postURL(...)
                ))
                ->setTarget('.selected li a')
                ->setCss('margin-right:2px;')
            );

            # Widget Last entries
            $this->set(
                (new Target(
                    id:          'lastposts',
                    name:        __('Last entries'),
                    js_callback: Target\Generic::postURL(...)
                ))
                ->setTarget('.lastposts li a')
                ->setCss('margin-right:2px;')
            );

            # Widget Last comments
            $this->set(
                (new Target(
                    id:          'lastcomments',
                    name:        __('Last comments'),
                    js_callback: Target\Widgets::lastcomments(...)
                ))
                ->setActive(true)
                ->setTarget('.lastcomments li a')
                ->setCss('margin-right:2px;')
            );
        }

        # Plugin auhtorMode
        if (App::plugins()->moduleExists('authorMode')
            && App::blog()->settings()->get('authormode')->get('authormode_active')
        ) {
            $this->set(
                (new Target(
                    id:          'authorswidget',
                    name:        __('Authors widget'),
                    js_callback: Target\AuthorMode::authors(...)
                ))
                ->setTarget('#authors ul li a')
                ->setCss('margin-right:2px;')
            );

            $this->set(
                (new Target(
                    id:           'author',
                    name:         __('Author'),
                    php_callback: Target\AuthorMode::author(...)
                ))
                ->setActive(true)
                ->setSize(48)
                ->setTarget('.dc-author #content-info h2')
                ->setCss('clear:left; float:left;margin-right:2px;')
            );

            $this->set(
                (new Target(
                    id:          'authors',
                    name:        __('Authors'),
                    js_callback: Target\AuthorMode::authors(...)
                ))
                ->setActive(true)
                ->setSize(32)
                ->setTarget('.dc-authors .author-info h2 a')
                ->setCss('clear:left; float:left; margin:4px;')
            );
        }

        # Plugin rateIt
        if (App::plugins()->moduleExists('rateIt')
            && App::blog()->settings()->get('rateit')->get('rateit_active')
        ) {
            $this->set(
                (new Target(
                    id:          'rateitpostsrank',
                    name:         __('Top rated entries'),
                    js_callback: Target\Generic::postURL(...)
                ))
                ->setTarget('.rateitpostsrank.rateittypepost ul li a') // Only "post" type
                ->setCss('margin-right:2px;')
            );
        }

        # Plugin lastpostsExtend
        if (App::plugins()->moduleExists('lastpostsExtend')) {
            $this->set(
                (new Target(
                    id:          'lastpostsextend',
                    name:        __('Last entries (extend)'),
                    js_callback: Target\Generic::postURL(...)
                ))
                ->setTarget('.lastpostsextend ul li a')
                ->setCss('margin-right:2px;')
            );
        }
    }
}
