<?php
/**
 *
 * @category        modules
 * @package         code
 * @author          WebsiteBaker Project
 * @copyright       Website Baker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: modify.php 67 2017-03-03 22:14:28Z manu $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb2.10/tags/WB-2.10.0/wb/modules/code/modify.php $
 * @lastmodified    $Date: 2017-03-03 23:14:28 +0100 (Fr, 03. Mrz 2017) $
 *
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(defined('WB_PATH') == false) { die('Illegale file access /'.basename(__DIR__).'/'.basename(__FILE__).''); }
/* -------------------------------------------------------- */
// check if module language file exists for the language set by the user (e.g. DE, EN)
$sAddonName = basename(__DIR__);
require(WB_PATH .'/modules/'.$sAddonName.'/languages/EN.php');
if(file_exists(WB_PATH .'/modules/'.$sAddonName.'/languages/'.LANGUAGE .'.php')) {
    require(WB_PATH .'/modules/'.$sAddonName.'/languages/'.LANGUAGE .'.php');
}
$sModulName = basename(__DIR__);
if( !$admin->get_permission($sModulName,'module' ) ) {
      die($MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES']);
}
require(WB_PATH . '/include/editarea/wb_wrapper_edit_area.php');

// Setup template object
$template = new Template(WB_PATH.'/modules/'.$sAddonName);
$template->set_file('page', 'htt/modify.htt');
$template->set_block('page', 'main_block', 'main');

// Get page content

$query = "SELECT content FROM `".TABLE_PREFIX."mod_code` WHERE `section_id` = '$section_id'";
$get_content = $database->query($query);
$content = $get_content->fetchRow(MYSQLI_ASSOC);
$content = htmlspecialchars($content['content']);

// Insert vars
$template->set_var(
    array(
        'ADDON_NAME'            => $sAddonName,
        'PAGE_ID'               => $page_id,
        'SECTION_ID'            => $section_id,
        'REGISTER_EDIT_AREA'    => (function_exists('registerEditArea') ? registerEditArea('content'.$section_id, 'php', false) : ''),
        'WB_URL'                => WB_URL,
        'CONTENT'               => $content,
        'TEXT_SAVE'             => $TEXT['SAVE'],
        'TEXT_BACK'             => $TEXT['BACK'],
        'TEXT_CANCEL'           => $TEXT['CANCEL'],
        'SECTION'               => $section_id,
        'FTAN'                  => $admin->getFTAN()
    )
);

// Parse template object
$template->set_unknowns('keep');
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page', false);
