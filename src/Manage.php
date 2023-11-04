<?php

declare(strict_types=1);

namespace Dotclear\Plugin\noodles;

use Dotclear\App;
use Dotclear\Core\Backend\{
    Notices,
    Page
};
use Dotclear\Core\Process;
use Dotclear\Helper\Html\Form\{
    Checkbox,
    Div,
    Input,
    Label,
    Note,
    Para,
    Select,
    Submit,
    Text
};
use Exception;

/**
 * @brief   noodles manage class.
 * @ingroup noodles
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Manage extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // nullsafe check
        if (!App::blog()->isDefined()) {
            return false;
        }

        if (empty($_POST['save'])) {
            return true;
        }

        $s       = My::settings();
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

            App::blog()->triggerBlog();
            Notices::addSuccessNotice(__('Configuration successfully updated'));
            My::redirect();
        } catch (Exception $e) {
            App::error()->add($e->getMessage());
        }

        return true;
    }

    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        // nullsafe check
        if (!App::blog()->isDefined()) {
            return;
        }

        $targets = Targets::instance();
        $path    = Image::getPath();
        if (is_null($path)) {
            $note = __('Local default avatar is not reachable!');
        } else {
            $note = '<a href="' . Image::getURL() . '">' . __('See local default avatar.') . '</a>';
        }

        Page::openModule(My::name());

        echo
        Page::breadcrumb([
            __('Plugin') => '',
            My::name()   => '',
        ]) .
        Notices::getNotices() . '

        <form id="module_config" action="' . My::manageUrl() . '" method="post" enctype="multipart/form-data">
        <h3>' . sprintf(__('Configure "%s"'), __('Noodles')) . '</h3>' .
        (new Div())
            ->class('fieldset')
            ->items([
                (new Text('h4', __('Settings'))),
                (new Para())
                    ->items([
                        (new Checkbox('noodles_active', $targets->active))
                            ->value(1),
                        (new Label(__('Enable plugin noodles on this blog'), Label::OUTSIDE_LABEL_AFTER))
                            ->class('classic')
                            ->for('noodles_active'),
                    ]),
                (new Para())->class('field')
                    ->items([
                        (new Label(__('Image API:'), Label::OUTSIDE_LABEL_BEFORE))
                            ->class('classic')
                            ->for('noodles_api'),
                        (new Select('noodles_api'))
                            ->default($targets->api)
                            ->items(Combo::api()),
                    ]),
                (new Para())->class('field')
                    ->items([
                        (new Label(__('Default image:'), Label::OUTSIDE_LABEL_BEFORE))
                            ->class('classic')
                            ->for('noodles_local'),
                        (new Select('noodles_local'))
                            ->default((string) (int) $targets->local)
                            ->items(Combo::local()),
                    ]),
                (new Note(''))->class('form-note')->text($note),
                (new Note(''))->class('form-note')->text(sprintf(__('You can add your own default avatar by adding file "%s" in media manager.'), 'img/' . My::IMAGE)),
            ])
            ->render() . '

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
            <td>' . (new Checkbox(['noodle[' . $target->id . '][active]', 'ck_' . $target->id], $target->active()))->value(1)->render() . '</td>
            <td class="nowrap" scope="row"><label for="ck_' . $target->id . '">' . $target->name . '</label></td>
            <td>' . (new Select(['noodle[' . $target->id . '][size]']))->items(Combo::size())->default((string) $target->size())->render() . '</td>
            <td>' . (new Select(['noodle[' . $target->id . '][rating]']))->items(Combo::rating())->default($target->rating())->render() . '</td>
            <td>' . (
                $target->hasPhpCallback() ?
                '<img alt="ok" src="images/check-on.png" />' :
                '<img alt="nok" src="images/check-off.png" />'
            ) . '</td>
            <td><img alt="ok" src="images/check-on.png" /></td>
            <td>' . (new Input(['noodle[' . $target->id . '][target]']))->size(20)->maxlength(255)->value($target->target())->render() . '</td>
            <td>' . (new Select(['noodle[' . $target->id . '][place]']))->items(Combo::place())->default($target->place())->render() . '</td>
            <td>' . (new Input(['noodle[' . $target->id . '][css]']))->size(20)->maxlength(255)->value($target->css())->render() . '</td>
            <td> .noodles-' . $target->id . '{}</td>
            </tr>';
        }
        echo '
        </tbody></table></div>
        <p class="form-note">' . __('Target and Place are for javascript.') . '</p>
        </div>' .

        (new Para())
            ->class('clear')
            ->items([
                (new Submit('save', __('Save') . ' (s)'))->accesskey('s'),
                ... My::hiddenFields(),
            ])
            ->render() . '
        </form>';

        Page::closeModule();
    }
}
