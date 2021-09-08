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

if (!defined('DC_CONTEXT_ADMIN')){return;}

$this->addUserAction(
    /* type */ 'settings',
    /* action */ 'delete_all',
    /* ns */ 'noodles',
    /* description */ __('delete all settings')
);

$this->addUserAction(
    /* type */ 'plugins',
    /* action */ 'delete',
    /* ns */ 'noodles',
    /* description */ __('delete plugin files')
);

$this->addDirectAction(
    /* type */ 'settings',
    /* action */ 'delete_all',
    /* ns */ 'noodles',
    /* description */ sprintf(__('delete all %s settings'),'noodles')
);

$this->addDirectAction(
    /* type */ 'plugins',
    /* action */ 'delete',
    /* ns */ 'noodles',
    /* description */ sprintf(__('delete %s plugin files'),'noodles')
);