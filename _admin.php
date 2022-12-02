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
    return;
}

dcCore::app()->menu[dcAdmin::MENU_PLUGINS]->addItem(
    __('Noodles'),
    dcCore::app()->adminurl->get('admin.plugin.noodles'),
    dcPage::getPF('noodles/icon.png'),
    preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.noodles')) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]), dcCore::app()->blog->id)
);
