<?php
/**
 * @package Sj Content Slick Slider
 * @version 2.5.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

//JHtml::stylesheet('modules/'.$module->module.'/assets/css/sj-slickslider.css');
//JHtml::stylesheet('modules/'.$module->module.'/assets/css/slickslider-font-color.css');

if (!defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1") {
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-2.1.4.min.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
if (!defined ('OWL_CAROUSEL')) {
            JHtml::stylesheet('modules/'.$module->module.'/assets/css/owl.carousel.css');
    JHtml::script('modules/'.$module->module.'/assets/js/owl.carousel.js');
            define( 'OWL_CAROUSEL', 1 );
        }
    
ImageHelper::setDefault($params);

$pag_position = $params->get('button_position' , 'conner-bl');
$pag_type = in_array($params->get('button_theme', 'num'), array('num', 'number')) ? 'type-num' : 'type-dot';
$slick_slider_background = $params->get('theme','theme1')=='theme1' ? 'bg-style1' : 'bg-style2';

$nav = $params->get('navs') == 1 ? "true" : "false";
$dots = $params->get('dots') == 1 ? "true" : "false";
$margin = (int)$params->get('margin') ? (int)$params->get('margin') : '5';
$startSlide = (int)$params->get('slideBy') ? (int)$params->get('slideBy') : '1';
if ($startSlide < 1 || $startSlide > count($list)){
	$startSlide = 1;
}
$autoplay_timeout = (int)$params->get('autoplay_timeout') ? (int)$params->get('autoplay_timeout') : '5000';
$autoplay_speed = (int)$params->get('autoplay_speed') ? (int)$params->get('autoplay_speed') : '2000';
$startPosition = (int)$params->get('startPosition') ? (int)$params->get('startPosition') : '0';
$dotsSpeed = (int)$params->get('dotsSpeed') ? (int)$params->get('dotsSpeed') : '500';
$navSpeed = (int)$params->get('navSpeed') ? (int)$params->get('navSpeed') : '500';

?>
<?php if ($params->get('pretext') != ''){ ?>
<div class="text-block">
	<?php echo $params->get('pretext'); ?>
</div>
<?php }?>
<div id="sj-slickslider<?php echo $module->id; ?>" class="sj-slickslider <?php echo 'slickslider-'.$params->get('item_image_position'); ?>" >
	<!-- Carousel items -->
    <div class="slickslider-items <?php echo $slick_slider_background?>">
    	<?php
    	$i=0;
    	foreach ($list as $item){
			$i++; $active = ($i == 1) ? ' active' : ''; ?>
    	<div class="slickslider-item row clearfix <?php echo $active; ?> ">
    		<?php $img = ContentSlickSliderHelper::getAImage($item, $params); ?>
    		
    		<?php if ($img): ?>
    		<div class="item-image col-md-5 col-sm-7 col-xs-6">
    			<div class="item-image-inner">
					<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo ContentSlickSliderHelper::parseTarget($params->get('item_link_target')); ?> >
		            	<?php
	    				echo ContentSlickSliderHelper::imageTag($img);
	    				?>
					</a>
				</div>
			</div>
			<?php endif; ?>
			
			<div class="item-content col-md-7 col-sm-5 col-xs-6 <?php if(!$img){echo 'no-images';} ?>">
				<div class="item-content-inner">
					<?php
					if( (int)$params->get('item_title_display', 1)){ ?>
					<div class="item-title">
						<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo ContentSlickSliderHelper::parseTarget($params->get('item_link_target')); ?> >
							<?php echo ContentSlickSliderHelper::truncate($item->title, $params->get('item_title_max_characs',25)); ?>
						</a>
					</div>
					<?php } // title display 
					if( (int)$params->get('item_desc_display', 1)){ ?>
					<div class="item-description">
						<?php echo $item->displayIntrotext; ?>
					</div>
					<?php } 
					$tags = '';
					if($params->get('item_tags_display') == 1 && $item->tags != '' && !empty($item->tags->itemTags) ) {	
						$item->tagLayout = new JLayoutFile('joomla.content.tags');
						$tags = $item->tagLayout->render($item->tags->itemTags); 
					}	
					if($tags != ''){?>
					<div class="item-tags">
						<?php echo  $tags; ?>
					</div>
					<?php }
					if( (int)$params->get('item_readmore_display', 1) ){ ?>
					<div class="item-readmore">
						<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo ContentSlickSliderHelper::parseTarget($params->get('item_link_target')); ?> >
							<?php echo $params->get('item_readmore_text', 'Read more'); ?>
						</a>
					</div>
					<?php } // readmore display ?>
				</div>
			</div>
			
    	</div>
    	<?php } ?>
    </div>
</div>

<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function ($) {
		;(function (element) {
			var element = '#sj-slickslider<?php echo $module->id; ?>';
			var $element = $(element),
				$extraslider = $(".slickslider-items", $element);

			$extraslider.on("initialized.owl.carousel", function () {
				var $item = $(".owl-item", $element);
				$item.css({"opacity": 1, "filter": "alpha(opacity = 100)"});

				var $slickslider_items = $('.slickslider-items',$element);
				$(".owl-dots", $slickslider_items).insertAfter($(".owl-prev", $slickslider_items));
				var $owl_controls_custom = $('.owl-controls-custom', $element);
				$owl_controls_custom.find('.owl-nav').addClass('<?php echo $pag_type;?>');
			});

			$extraslider.owlCarousel({
				autoplay: <?php echo $params->get('autoplay'); ?>,
				autoplayHoverPause: <?php echo $params->get('pausehover'); ?>,
				autoplayTimeout: <?php echo $autoplay_timeout; ?>,
				autoplaySpeed: <?php echo $autoplay_speed; ?>,
				startPosition: <?php echo ($startSlide -1); ?>,
				mouseDrag: <?php echo $params->get('mousedrag');?>,
				autoWidth: false,
				responsive: {
					0: 	{ items: 1} ,
					480: { items: 1},
					768: { items: 1},
					992: { items: 1},
					1200: { items: 1}
				},
				dotClass: "owl-dot",
				dotsClass: "owl-dots",
				dots: <?php echo $dots; ?>,
				dotsSpeed:<?php echo $dotsSpeed; ?>,
				nav: <?php echo $nav; ?>,
				loop: true,
				navSpeed: <?php echo $navSpeed; ?>,
				navText: ["", ""],
				navClass: ["owl-prev", "owl-next"],
				autoHeight:true,
				controlsClass: 'owl-controls-custom <?php echo $pag_position ?> <?php echo $slick_slider_background; ?>'
			});
			var dots = <?php echo $dots; ?>;
			var nav = <?php echo $nav; ?>;
			if(dots == 0 && nav == 0){
				$('.owl-controls-custom', $element).css('display', 'none');
			}
			// page
			$extraslider.on('resize.owl.carousel resized.owl.carousel',function(){
				resizePage();
			});
			function resizePage(){
				var this_dots = $('.owl-controls-custom .type-num',$element).find('.owl-dot', $element);
				var this_dot = this_dots.length;
				for(var k = 0; k < this_dot; k++ ){
					$($element).find(this_dots[k], $element).find('span').html('').append(k+1);
				}
			}
			resizePage();
			// end page

		})("#sj-slickslider<?php echo $module->id ; ?>");
	});
	//]]>
</script>

<?php if ($params->get('posttext') != ''){ ?>
<div class="text-block">
	<?php echo $params->get('posttext'); ?>
</div>
<?php } ?>

