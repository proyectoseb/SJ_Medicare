<?php
/**
 * @package Sj Content Responsive Listing
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

if(!class_exists('plgSystemPlg_Sj_Content_ResListing_Ajax')){
	echo '<p style="margin:15px 0;"><b>'.JText::_('WARNING_NOT_INSTALL_PLUGIN').'</b></p>';
	return ;
}

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once dirname(__FILE__).'/core/helper.php';

$layout = $params->get('layout', 'default');
// $cacheid = md5(serialize(array ($layout, $module->id)));
// $cacheparams = new stdClass;
// $cacheparams->cachemode = 'id';
// $cacheparams->class = 'ContentResponsiveListingHelper';
// $cacheparams->method = 'getList';
// $cacheparams->methodparams = array($params, $module);
// $cacheparams->modeparams = $cacheid;
//$list = JModuleHelper::moduleCache ($module, $params, $cacheparams);
$list = ContentResponsiveListingHelper::getList($params , $module);
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
if($is_ajax){
	$sj_module_id	= JRequest::getVar('sj_module_id', null);
	if($sj_module_id == $module->id){
		$result = new stdClass();
		ob_start();
		require  JModuleHelper::getLayoutPath($module->module, $layout.'_items');
		$buffer = ob_get_contents();
		$result->items_markup = preg_replace(
				array(
						'/ {2,}/',
						'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
				),
				array(
						' ',
						''
				),
				$buffer
		);
		ob_end_clean();
		echo json_encode($result);
	}
}else{
	require JModuleHelper::getLayoutPath($module->module, $layout);
	require JModuleHelper::getLayoutPath($module->module, $layout.'_js');
}
