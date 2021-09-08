<?php
/**
 * @brief noodles, a  for Dotclear 2
 * 
 * @package Dotclear
 * @subpackage \u
 * 
 * @author JC Denis
 * 
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('DC_CONTEXT_ADMIN')){return;}

$new_version = $core->plugins->moduleInfo('noodles','version');
$old_version = $core->getVersion('noodles');

if (version_compare($old_version,$new_version,'>=')) return;

try
{
    if (version_compare(DC_VERSION,'2.2-beta','<'))
    {
        throw new Exception('noodles requires Dotclear 2.2');
    }

    $core->blog->settings->addNamespace('noodles');

    $core->blog->settings->noodles->put('noodles_active',false,'boolean','Enable extension',false,true);

    $core->setVersion('noodles',$new_version);

    return true;
}
catch (Exception $e)
{
    $core->error->add($e->getMessage());
}
return false;