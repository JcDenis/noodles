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
use dcNsProcess;
use dcPage;
use Exception;

use form;

class Manage extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && My::phpCompliant()
            && !is_null(dcCore::app()->auth) && !is_null(dcCore::app()->blog)
            && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
                dcCore::app()->auth::PERMISSION_CONTENT_ADMIN,
            ]), dcCore::app()->blog->id);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        // nullsafe check
        if (is_null(dcCore::app()->blog) || is_null(dcCore::app()->adminurl)) {
            return false;
        }

        if (empty($_POST['save'])) {
            return true;
        }

        $s       = dcCore::app()->blog->settings->get(My::id());
        $targets = Targets::instance();

        try {
            $s->put('active', !empty($_POST['noodles_active']), 'boolean');
            $s->put('api', $_POST['noodles_api'], 'string');
            $s->put('local', !empty($_POST['noodles_local']), 'boolean');

            // behaviors
            foreach ($_POST['noodle'] as $id => $bloc) {
                $target = $targets->get($id);
                if (is_null($target)) {
                    continue;
                }

                $target
                    ->setActive(!empty($bloc['active']))
                    ->setRating($bloc['rating'] ?? 'g')
                    ->setSize($bloc['size'] ?? '16')
                    ->setCss($bloc['css'] ?? '')
                    ->setTarget($bloc['target'] ?? '')
                    ->setPlace($bloc['place'] ?? 'prepend')
                ;
            }
            $targets->export();

            dcCore::app()->blog->triggerBlog();
            dcPage::addSuccessNotice(__('Configuration successfully updated'));
            dcCore::app()->adminurl->redirect('admin.plugin.noodles');
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }

    public static function render(): void
    {
        if (!static::$init) {
            return;
        }

        // nullsafe check
        if (is_null(dcCore::app()->blog) || is_null(dcCore::app()->adminurl)) {
            return;
        }

        $targets = Targets::instance();
        $path    = Image::getPath();
        if (is_null($path)) {
            $note = __('Local default avatar is not reachable!');
        } else {
            $note = '<a href="' . Image::getURL() . '">' . __('See local default avatar.') . '</a>';
        }

        dcPage::openModule(My::name());

        echo
        dcPage::breadcrumb([
            __('Plugin') => '',
            My::name()   => '',
        ]) .
        dcPage::notices() . '

        <form id="module_config" action="' .
            dcCore::app()->adminurl->get('admin.plugin.' . My::id()) .
        '" method="post" enctype="multipart/form-data">
        <h3>' . sprintf(__('Configure "%s"'), __('Noodles')) . '</h3>
        <div class="fieldset"><h4>' . __('Settings') . '</h4>
        <p><label for="noodles_active">' .
        form::checkbox('noodles_active', 1, $targets->active) .
        __('Enable plugin noodles on this blog') . '</label></p>
        <p class="field"><label for="noodles_api" class="classic">' . __('Image API:') . ' </label>' .
        form::combo('noodles_api', Combo::api(), $targets->api) . '</p>
        <p class="field"><label for="noodles_local" class="classic">' . __('Default image:') . ' </label>' .
        form::combo('noodles_local', Combo::local(), $targets->local) . '</p>
        <p class="form-note">' . $note . '</p>
        <p class="form-note">' . sprintf(__('You can add your own default avatar by adding file "%s" in media manager.'), 'img/' . My::IMAGE) . '</p>
        </div>

        <div class="fieldset"><h4>' . __('Behaviors') . '</h4>
        <div class="table-outer">
        <table><caption class="hidden">' . __('Behaviors list') . '</caption><tbody><tr>
        <th colspan="2" class="first">' . __('Search area') . '</th>
        <th scope="col">' . __('Size') . '</th>
        <th scope="col">' . __('Rating') . '</th>
        <th scope="col">' . __('PHP') . '</th>
        <th scope="col">' . __('JS') . '</th>
        <th scope="col">' . __('Target') . '</th>
        <th scope="col">' . __('Place') . '</th>
        <th colspan="2" scope="col">' . __('Adjust avatar CSS') . '</th>
        </tr>';

        foreach ($targets->dump() as $target) {
            echo '
            <tr class="line">
            <td>' . form::checkbox(['noodle[' . $target->id . '][active]', 'ck_' . $target->id], 1, $target->active()) . '</td>
            <td class="nowrap" scope="row"><label for="ck_' . $target->id . '">' . $target->name . '</label></td>
            <td>' . form::combo(['noodle[' . $target->id . '][size]'], Combo::size(), $target->size()) . '</td>
            <td>' . form::combo(['noodle[' . $target->id . '][rating]'], Combo::rating(), $target->rating()) . '</td>
            <td>' . (
                $target->hasPhpCallback() ?
                '<img alt="ok" src="images/check-on.png" />' :
                '<img alt="nok" src="images/check-off.png" />'
            ) . '</td>
            <td><img alt="ok" src="images/check-on.png" /></td>
            <td>' . form::field(['noodle[' . $target->id . '][target]'], 20, 255, $target->target()) . '</td>
            <td>' . form::combo(['noodle[' . $target->id . '][place]'], Combo::place(), $target->place()) . '</td>
            <td>' . form::field(['noodle[' . $target->id . '][css]'], 50, 255, $target->css()) . '</td>
            <td> .noodles-' . $target->id . '{}</td>
            </tr>';
        }
        echo '
        </tbody></table></div>
        <p class="form-note">' . __('Target and Place are for javascript.') . '</p>
        </div>

        <p class="clear">
        <input type="submit" value="' . __('Save') . ' (s)" accesskey="s" name="save" /> ' .
        dcCore::app()->formNonce() . '</p>
        </form>';

        dcPage::closeModule();
    }
}
