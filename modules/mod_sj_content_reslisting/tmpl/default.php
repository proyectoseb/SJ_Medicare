<?php
/**
 * @package Sj Content Responsive Listing
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

JHtml::stylesheet('modules/'.$module->module.'/assets/css/isotope.css');
JHtml::stylesheet('modules/'.$module->module.'/assets/css/sj-reslisting.css');
if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}

JHtml::script('modules/'.$module->module.'/assets/js/jquery.isotope.js');

$instance	= rand().time();
$tag_id = 'sj_responsive_listing_'.rand().time();
?>
<!--[if lt IE 9]><div id="<?php echo $tag_id; ?>" class="sj-responsive-listing msie lt-ie9"><![endif]-->
<!--[if IE 9]><div id="<?php echo $tag_id; ?>" class="sj-responsive-listing msie"><![endif]-->
<!--[if gt IE 9]><!--><div id="<?php echo $tag_id; ?>" class="sj-responsive-listing"><!--<![endif]-->
   <?php if($params->get('pretext')!= null){ ?>
		<div class="respl-pretext">
			<?php echo $params->get('pretext');?>
		</div>
   <?php }
	if(!empty($list)){ ?>
	<div class="respl-wrap cf">
		<div class="respl-header">
			<?php $maxwidth = ($params->get('sort_byform_display',1)== 0 && $params->get('layout_select_display',1) == 0 )?'max-width:100%':'';?>
			<div class="respl-categories" data-label="<?php echo JText::_('CATEOGRY_LABEL') ?>" style="<?php echo $maxwidth ?>"  >
				<div class="respl-cats-wrap respl-group"  >
					<div class="cats-curr respl-btn">
						<span class="sort-curr" data-filter_value=""><?php echo JText::_('ALL_LABEL')?></span>
						<span class="sort-arrow respl-arrow"></span>
					</div>
					<ul class="respl-cats respl-dropdown-menu respl-option" data-option-key="filter">
						<?php foreach($list['categories'] as $items){?>
							<li class="respl-cat <?php echo (isset($items->sel))?$items->sel:''; ?>" data-value="<?php echo $items->id ?>">
								<a href="#<?php echo $tag_id; ?>" data-rl_value="<?php echo ($items->id == '*')?'*':'.category-'.$items->id?>" class="<?php echo ($params->get('count_items_display',0) == 0)?'respl-count':''; ?>" data-count="<?php echo $items->count ?>">
									<?php echo ($items->title == JText::_('ALL_LABEL'))?JText::_('ALL_LABEL'):ContentResponsiveListingHelper::truncate($items->title, $params->get('tal_max_characters')) ?>
								</a>
							</li>
						<?php }?>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<div class="respl-sort-view" >
				<?php if($params->get('sort_byform_display',1) == 1){
					$value_curr ='';
					$data_curr =  '';
					$select_sort =   trim($params->get('source_order_by','title'));
					switch($select_sort){
						case 'id':
							 $value_curr = 'id';
							 $data_curr = JText::_('ID_LABEL');
							 break;	
						case 'title':
							 $value_curr = 'title';
							 $data_curr = JText::_('TITLE_LABEL');
							 break;
						case 'hits':
							$value_curr = 'hits';
							$data_curr = JText::_('MOST_VIEWS_LABEL');
							break;
						case 'created':
							$value_curr = 'created';
							$data_curr = JText::_('RECENTLY_ADDED_LABEL');
							break;
						case 'modified':
							$value_curr = 'modified';
							$data_curr = JText::_('RECENTLY_MODIFIED_LABEL');
							break;
						case 'ordering':
							$value_curr = 'ordering';
							$data_curr = JText::_('ORDERING_LABEL');
							break;
						case 'publish_up':
							$value_curr = 'publish_up';
							$data_curr = JText::_('STARTPUBLISHING_LABEL');
							break;
						case 'publish_down':
							$value_curr = 'publish_down';
							$data_curr = JText::_('FINISHPUBLISHING_LABEL');
							break;	
						case 'random':
							$value_curr = 'random';
							$data_curr = JText::_('RANDOM_LABEL');
							break;
						default:
							$value_curr = 'title';
							$data_curr = JText::_('TITLE_LABEL');
							break;
					}
					
					$oderbys  = $params->get('itemsOrdering_display');
					if(!empty($oderbys)) {
							$value_first = $value_curr;
							$data_first = $data_curr;
						if(in_array($value_curr,$oderbys)) {
							 $value_first = $value_curr;
							 $data_first = $data_curr;
						} else {
							$value_first = $oderbys[0];
							if($oderbys[0] == 'id'){
								$data_first = JText::_('ID_LABEL');
							}elseif($oderbys[0] == 'title'){
								$data_first = JText::_('TITLE_LABEL');
							}else if($oderbys[0] == 'hits' ){
								$data_first = JText::_('MOST_VIEWS_LABEL');
							}else if($oderbys[0] == 'created' ){
								$data_first = JText::_('RECENTLY_ADDED_LABEL');
							}else if($oderbys[0] == 'modified' ){
								$data_first = JText::_('RECENTLY_MODIFIED_LABEL');
							}else if($oderbys[0] == 'ordering' ){
								$data_first = JText::_('ORDERING_LABEL');
							}else if($oderbys[0] == 'publish_up' ){
								$data_first = JText::_('STARTPUBLISHING_LABEL');
							}else if($oderbys[0] == 'publish_down' ){
								$data_first = JText::_('FINISHPUBLISHING_LABEL');
							}else if($oderbys[0] == 'random' ){
								$data_first = JText::_('RANDOM_LABEL');
							}
						}
					
						?>
							<div class="respl-sort" data-label="<?php echo JText::_('SORT_BY_LABEL')?>" >
								<div class="sort-wrap respl-group">
									<div class="sort-inner respl-btn "  data-curr_value="<?php echo  $value_first; ?>" data-curr="<?php echo $data_first ?>">
										<span class="sort-arrow respl-arrow"></span>
									</div>
									<ul class="sort-select respl-dropdown-menu respl-option" data-option-key="sortBy">
										<?php foreach($oderbys as $key => $oder){
											if($oder == 'id') { ?>
												<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="id"><?php echo JText::_('ID_LABEL')?></a></li>
											<?php } 
											elseif($oder == 'title') { ?>
												<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="title"><?php echo JText::_('TITLE_LABEL')?></a></li>
											<?php } 
											elseif($oder == 'hits') {
											?>
												<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="hits"><?php echo JText::_('MOST_VIEWS_LABEL')?></a></li>
											<?php } 
											elseif($oder == 'created') {
											?>
												<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="created"><?php echo JText::_('RECENTLY_ADDED_LABEL')?></a></li>
											<?php }
											elseif($oder == 'modified') {
											?>
												<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="modified"><?php echo JText::_('RECENTLY_MODIFIED_LABEL')?></a></li>
											<?php }
											elseif($oder == 'ordering') { ?>
												<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="ordering"><?php echo JText::_('ORDERING_LABEL')?></a></li>
											<?php }
											elseif($oder == 'publish_up') { 
											?>
												<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="publish_up"><?php echo JText::_('STARTPUBLISHING_LABEL')?></a></li>
											<?php }
											elseif($oder == 'publish_down') { 
											?>
											<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="publish_down"><?php echo JText::_('FINISHPUBLISHING_LABEL')?></a></li>
											<?php }
											elseif($oder == 'random') { 
											?>
											<li ><a href="#<?php echo $tag_id; ?>" data-rl_value="random"><?php echo JText::_('RANDOM_LABEL')?></a></li>
											<?php }
										}	?>	
									</ul>
								</div>
							</div>
				<?php }
				}?>
				<?php if($params->get('layout_select_display',1) == 1) {?>
				<ul class="respl-view respl-option" data-label="<?php echo JText::_('VIEW_LABEL')?>" data-option-key="layoutMode">
					<li class="view-grid <?php echo ($params->get('default_view') == 'grid')?'sel':''?>">
						<a href="#<?php echo $tag_id; ?>" data-rl_value="fitRows">
						</a>
					</li>
					<li class="view-list <?php echo ($params->get('default_view') == 'list')?'sel':''?>">
						<a href="#<?php echo $tag_id; ?>" data-rl_value="straightDown">
						</a>
					</li>
				</ul>
				<?php }?>
			</div>
		</div>
		
		<?php $class_respl= 'respl01-'.$params->get('nb-column1',6).' respl02-'.$params->get('nb-column2',4).' respl03-'.$params->get('nb-column3',2).' respl04-'.$params->get('nb-column4',1) ?>
		<div class="respl-items <?php echo $class_respl?> <?php echo ($params->get('default_view') == 'grid')?'grid':'list'?> cf  module-<?php echo $module->id?>">
			<div class="respl-loading"></div>
			<?php require JModuleHelper::getLayoutPath($module->module, $layout.'_items'); ?>
		</div>
		
		<?php
			$classloaded = ($params->get('source_limit', 2) >= ContentResponsiveListingHelper::$total || $params->get('source_limit', 2)== 0 )?'loaded':'';
		?>
		
		<div class="respl-loader first-load respl-btn <?php echo $classloaded?>" >
			<a class="respl-button" href="#<?php echo $tag_id; ?>"  data-rl_allready="<?php echo JText::_('ALL_READY_LABEL');?>" data-rl_start="<?php echo $params->get('source_limit', 2)?>" data-rl_ajaxurl="<?php echo (string)JURI::getInstance(); ?>" data-rl_load="<?php echo $params->get('source_limit', 2)?>" data-rl_total="<?php echo ContentResponsiveListingHelper::$total ?>" data-rl_moduleid="<?php echo $module->id; ?>">
				<?php if (!$classloaded){?>
				<span class="loader-image"></span>
				<?php } ?>
				<span class="loader-label" >
					<?php if ($classloaded){
						echo JText::_('ALL_READY_LABEL');
					} else { ?>
					<?php echo JText::_('LOAD_MORE_LABEL')?> (<span class="load-number" data-more="<?php echo (ContentResponsiveListingHelper::$total - $params->get('source_limit', 2) > $params->get('source_limit', 2))?$params->get('source_limit', 2):ContentResponsiveListingHelper::$total - $params->get('source_limit', 2); ?>" data-total="<?php echo ContentResponsiveListingHelper::$total - $params->get('source_limit', 2)?>">/</span>)
					<?php } ?>
				</span>
			</a>
		</div>
		<div class="clear"></div>
	</div>
	<?php } else {
		echo '<div class="no-item">'.JText::_('WARNING_MASSAGE').'</div>';
	}
	if($params->get('posttext')!= ''){ ?>
		<div class="respl-posttext">
			<?php echo $params->get('posttext');?>
		</div>
    <?php }?>
</div>