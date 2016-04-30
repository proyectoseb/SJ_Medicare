<?php
/**
 * @package Sj Content Responsive Listing
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

if(isset($list['items'])) {
ImageHelper::setDefault($params);
foreach($list['items']  as $item){  ?>
	<div class="respl-item first-load  category-<?php echo $item->catid ?>"  
		data-modified="<?php echo strtotime($item->modified) ?>" 
		data-created="<?php echo strtotime($item->created) ?>" 
		data-publishUp="<?php echo strtotime($item->publish_up); ?>" 
		data-publishDown="<?php echo ($item->publish_down != '')?strtotime($item->publish_down):0; ?>" 
		data-ordering="<?php echo $item->ordering; ?>" 
		data-hits="<?php echo $item->hits; ?>" 
		data-title="<?php echo mb_strtolower($item->title); ?>" 
		data-id="<?php echo $item->id; ?>" 
		data-catid="<?php echo $item->catid ?>">
		<div class="item-inner">
			<?php $img = ContentResponsiveListingHelper::getAImage($item, $params);  ?>
                <div class="item-image cf">
				<?php if($img && (@GetImageSize($img['src']) || file_exists($img['src']))) {?>
					<a class="item-img" href="<?php echo $item->link ?>" <?php echo ContentResponsiveListingHelper::parseTarget($params->get('link_target','_self'))?> title="<?php echo $item->title?>" >
						<img src="<?php echo ContentResponsiveListingHelper::imageSrc($img); ?>" title="<?php echo $item->title; ?>"	  alt="<?php  echo $item->title; ?>" />
					</a>
				<?php } ?>
                <?php if($params->get('item_readmore_display', 1) == 1){?>
                <div class="more-readmore">
                    <a href="<?php echo $item->link ?>" <?php echo ContentResponsiveListingHelper::parseTarget($params->get('link_target','_self'))?> title="<?php echo $item->title?>" >
                        <?php echo JText::_('READ_MORE_GRID_LABEL') ?>
                    </a>
                </div>
				</div>
                <?php }?>
				<div class="item-content">
						<?php if($params->get('item_title_display',1) == 1){?>
						<div class="more-title">
							<a href="<?php echo $item->link ?>" <?php echo ContentResponsiveListingHelper::parseTarget($params->get('link_target','_self'))?> title="<?php echo $item->title?>" >
								<?php echo ContentResponsiveListingHelper::truncate($item->title, $params->get('item_title_max_characters',25)); ?>
							 </a>
							
						</div>
						<?php }?>
						<?php if($params->get('item_hits_display', 1) == 1){?>
						<div class="more-read" data-read="<?php echo JText::_('READ_LABEL')?>" data-times="<?php echo ((int)$item->hits>1)?JText::_('TIMES_LABEL'):JText::_('TIME_LABEL')?>">&nbsp;<?php echo $item->hits ?>&nbsp;</div>
						<?php }?>
						<?php if($params->get('item_cat_display', 1) == 1){?>
						<div class="more-public" data-value="<?php echo JText::_('PUBLISHED_LABEL')?>">&nbsp;
							<a href="<?php echo $item->catlink ?>" <?php echo ContentResponsiveListingHelper::parseTarget($params->get('link_target','_self'))?> title="<?php echo $item->category_title?>" >
								<?php echo $item->category_title ?>
							</a>
						</div>
						<?php }?>
						<?php if($params->get('item_description_display', 1) == 1 && ContentResponsiveListingHelper::_trimEncode($item->text) != '') {?>
						<div class="more-content">
							<?php
								echo ContentResponsiveListingHelper::truncate($item->text, (int)$params->get('item_des_maxlength_layout_grid'));
							?>
					 	</div>
					 	<?php } 
						if($params->get('item_tags_display') == 1 && $item->tags != '' && !empty($item->tags->itemTags) ) {	?>
						<div class="more-tags">
							<span class="more-tag-icon"></span>
							  <?php $item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
							  <?php echo $item->tagLayout->render($item->tags->itemTags); ?>
						</div>
					 	<?php }
						if($params->get('item_created_display',1) == 1) {?>
					 	<div class="more-post" data-value="<?php echo JText::_('POST_LABEL')?>"><?php echo  JHTML::_('date', $item->created,JText::_('DATE_FORMAT_LC3')) ?></div>
					 	<?php }?>
				 	</div>
		</div>
	</div>
<?php } 
} ?>
