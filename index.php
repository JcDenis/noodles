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
if (!defined('DC_CONTEXT_ADMIN')) {
    return null;
}

dcPage::check(dcAuth::PERMISSION_CONTENT_ADMIN);

include __DIR__ . '/inc/_default_noodles.php';

$s = dcCore::app()->blog->settings->noodles;

$__noodles = noodles::decode($s->noodles_object);
if ($__noodles->isEmpty()) {
    $__noodles = $__default_noodles;
} else {
    $default_noodles_array = $__default_noodles->noodles();
    foreach ($default_noodles_array as $id => $noodle) {
        if ($__noodles->exists($id)) {
            continue;
        }
        $__noodles->set($id, $noodle);
    }
}
$public_path = path::real(dcCore::app()->blog->public_path);
if (!is_dir($public_path) || !is_writable($public_path)) {
    $public_path = false;
}
$default_images = files::scandir(__DIR__ . '/default-templates/img/');
if (!is_array($default_images)) {
    $default_images = [];
}
$default_image = $s->noodles_image;

$combo_api = [
    'gravatar'   => 'http://www.gravatar.com/',
    'libravatar' => 'http://cdn.libravatar.org/',
];

$combo_place = [
    __('Begin')  => 'prepend',
    __('End')    => 'append',
    __('Before') => 'before',
    __('After')  => 'after',
];
$combo_rating = [
    'G'  => 'g',
    'PG' => 'pg',
    'R'  => 'r',
    'X'  => 'x',
];
$combo_size = [
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

if (!empty($_POST['save'])) {
    try {
        $public_file = $public_path . '/noodles-default-image.png';
        $s->put('noodles_active', !empty($_POST['noodles_active']), 'boolean');
        $s->put('noodles_api', $_POST['noodles_api'], 'string');

        // user upload image
        if ($_POST['noodles_image'] == 'user' && !empty($public_path)) {
            if (2 == $_FILES['noodlesuserfile']['error']) {
                throw new Exception(__('Maximum file size exceeded'));
            }
            if (0 != $_FILES['noodlesuserfile']['error']) {
                throw new Exception(__('Something went wrong while download file'));
            }
            if (!in_array($_FILES['noodlesuserfile']['type'], ['image/png', 'image/x-png'])) {
                throw new Exception(__('Image must be in png format'));
            }
            if (move_uploaded_file($_FILES['noodlesuserfile']['tmp_name'], $public_file)) {
                $s->put('noodles_image', 1, 'boolean');
            } else {
                throw new Exception(__('Failed to save image'));
            }

        // Default gravatar.com avatar
        } elseif ($_POST['noodles_image'] == 'gravatar.com') {
            $s->put('noodles_image', 0, 'boolean');

        // existsing noodles image on blog
        } elseif ($_POST['noodles_image'] == 'existing') {
            $s->put('noodles_image', 1, 'boolean');

        // noodles image
        } elseif (preg_match('/^gravatar-[0-9]+.png$/', $_POST['noodles_image']) && !empty($public_path)) {
            $source = dirname(__FILE__) . '/default-templates/img/' . $_POST['noodles_image'];
            if (!file_exists($source)) {
                throw new Exception(__('Something went wrong while search file'));
            }
            if (files::putContent($public_file, file_get_contents($source))) {
                $s->put('noodles_image', 1, 'boolean');
            }

            // Default gravatar.com avatar
        } else { //if ($_POST['noodles_image'] == 'gravatar.com') {
            $s->put('noodles_image', 0, 'boolean');
        }

        // behaviors
        foreach ($_POST['noodle'] as $id => $bloc) {
            $__noodles->get($id)
                ->set('active', !empty($bloc['active']))
                ->set('rating', $bloc['rating'] ?? 'g')
                ->set('size', $bloc['size'] ?? '16')
                ->set('css', $bloc['css'] ?? '')
                ->set('target', $bloc['target'] ?? '')
                ->set('place', $bloc['place'] ?? 'prepend')
            ;
        }
        $s->put('noodles_object', $__noodles->encode(), 'string');

        dcCore::app()->blog->triggerBlog();
        dcAdminNotices::addSuccessNotice(__('Configuration successfully updated'));
        dcCore::app()->adminurl->redirect('admin.plugin.noodles');
    } catch (Exception $e) {
        dcCore::app()->error->add($e->getMessage());
    }
}

echo '<html><head><title>' . __('Noodles') . '</title></head><body>' .
dcPage::breadcrumb([
    html::escapeHTML(dcCore::app()->blog->name) => '',
    __('Noodles')                               => '',
    __('Plugin configuration')                  => '',
]) .
dcPage::notices() . '

<form id="module_config" action="' .
    dcCore::app()->adminurl->get('admin.plugin.noodles') .
'" method="post" enctype="multipart/form-data">
<h3>' . sprintf(__('Configure "%s"'), __('Noodles')) . '</h3>
<div class="fieldset"><h4>' . __('Activation') . '</h4>
<p><label for="noodles_active">' .
form::checkbox('noodles_active', 1, $s->noodles_active) .
__('Enable plugin noodles on this blog') . '</label></p>
<p><label for="noodles_api" class="classic">' . __('API:') . ' </label>' .
form::combo('noodles_api', $combo_api, $s->noodles_api) . '</p>
</div>
<div class="fieldset"><h4>' . __('Avatar') . '</h4>
<p>' . __('Select default avatar to display on unknown users.') . '</p>';

if (!empty($public_path)) {
    echo '<div class="one-box">';
    sort($default_images);
    $i = 0;
    foreach ($default_images as $f) {
        if (!preg_match('/gravatar-[0-9]+.png/', $f)) {
            continue;
        }
        $i++;
        $sz    = getimagesize(dirname(__FILE__) . '/default-templates/img/' . $f);
        $sz[2] = files::size(filesize(dirname(__FILE__) . '/default-templates/img/' . $f));

        echo '
        <div class="fieldset box">
        <p>' . form::radio(['noodles_image', 'noodles_image_' . $i], $f) . '
        <label class="classic" for="noodles_image_' . $i . '">' . basename($f) . '</label></p>
        <div class="two-box"><div class="box">
        <p><img src="' . dcPage::getPF('noodles/default-templates/img/' . $f) . '" alt="" /></p>
        </div><div class="box">
        <p>' . $sz[0] . 'x' . $sz[1] . '<br />' . $sz[2] . '</p>
        </div></div>
        </div>';
    }
    echo '</div>';
}

echo '<div class="one-box">';

if (null !== ($default_image_path = noodlesLibImagePath::getPath('noodles'))) {
    $sz    = getimagesize($default_image_path);
    $sz[2] = files::size(filesize($default_image_path));

    echo '
    <div class="fieldset box">
    <p>' . form::radio(['noodles_image', 'public_image'], 'existing', !empty($default_image)) . '
    <label class="classic" for="public_image">' . __('Blog default image') . '</label></p>
    <div class="two-box"><div class="box">
    <p><img src="' . noodlesLibImagePath::getUrl('noodles') . '?' . rand() . '" alt="" /></p>
    </div><div class="box">
    <p>' . $sz[0] . 'x' . $sz[1] . '<br />' . $sz[2] . '</p>
    </div></div>
    </div>';
}

if (!empty($public_path)) {
    echo '
    <div class="fieldset box">
    <p>' . form::radio(['noodles_image', 'upload_image'], 'user') . '
    <label class="classic" for="upload_image">' . __('Upload a new avatar') . '</label></p>
    <p>' . form::hidden(['MAX_FILE_SIZE'], 30000) . '
    <input type="file" name="noodlesuserfile" /> 
    <p class="form-note">' . __('Image must be in "png" format and have a maximum file size of 30Ko') . '</p>
    </div>';
}
echo '
<div class="fieldset box">
<p>' . form::radio(['noodles_image', 'com_image'], 'gravatar.com', empty($default_image)) . '
<label class="classic">' . __('API default image') . '</label></p>
</div>';

if (empty($public_path)) {
    echo '<p class="info">' . __('Public directory is not writable, you can not use custom gravatar.') . '</p>';
}

echo '
</div></div>
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

foreach ($__noodles->noodles() as $noodle) {
    echo '
    <tr class="line">
    <td>' . form::checkbox(['noodle[' . $noodle->id() . '][active]', 'ck_' . $noodle->id()], 1, $noodle->active) . '</td>
    <td class="nowrap" scope="row"><label for="ck_' . $noodle->id() . '">' . $noodle->name() . '</label></td>
    <td>' . form::combo(['noodle[' . $noodle->id() . '][size]'], $combo_size, $noodle->size) . '</td>
    <td>' . form::combo(['noodle[' . $noodle->id() . '][rating]'], $combo_rating, $noodle->rating) . '</td>
    <td>' . (
        $noodle->hasPhpCallback() ?
        '<img alt="ok" src="images/check-on.png" />' :
        '<img alt="nok" src="images/check-off.png" />'
    ) . '</td>
    <td><img alt="ok" src="images/check-on.png" /></td>
    <td>' . form::field(['noodle[' . $noodle->id() . '][target]'], 20, 255, $noodle->target) . '</td>
    <td>' . form::combo(['noodle[' . $noodle->id() . '][place]'], $combo_place, $noodle->place) . '</td>
    <td>' . form::field(['noodle[' . $noodle->id() . '][css]'], 50, 255, $noodle->css) . '</td>
    <td> .noodles-' . $noodle->id() . '{}</td>
    </tr>';
}
echo '
</tbody></table></div>
<p class="form-note">' . __('Target and Place are for javascript.') . '</p>
</div>

<p class="clear">
<input type="submit" value="' . __('Save') . ' (s)" accesskey="s" name="save" /> ' .
dcCore::app()->formNonce() . '</p>
</form>

</body></html>';
