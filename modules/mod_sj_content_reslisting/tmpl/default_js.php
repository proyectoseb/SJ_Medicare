<?php
/**
 * @package Sj Content Responsive Listing
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;
?>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($){
	;(function(element){
		var $respl = $(element);
		var $container = $('.respl-items', $respl);
		var $respl_loading =  $('.respl-loading',$container );
		$(window).load(function(){
			$('.respl-loader',  $respl).addClass('first-load');		
	    	$('.item-image img.respl-nophoto', $respl).parent().parent().addClass('respl-nophoto');
			$('.respl-item', $respl).each(function(){
				$(this).addClass('first-load');
				var $that = $(this);
					 setTimeout(function(){
						$respl_loading.remove();
						$that.removeClass('first-load');
						$('.respl-loader',  $respl).removeClass('first-load');
					},100);
			});
		}); 

		var sortdef = $('.sort-inner', $respl).attr('data-curr_value');
		var filterdef = $('.respl-cat', $respl).filter('.sel').children().attr('data-rl_value');
		 $('.sort-curr',$respl).attr('data-filter_value',filterdef);
		 $('.sort-curr',$respl).html($('.respl-cat', $respl).filter('.sel').children().html());
	// add randomish size classes
		$container.imagesLoaded( function(){
			$container.isotope({
				containerStyle: {
		    					position: 'relative',
		    	    			height: 'auto',
		    	    			overflow: 'visible'
		    	    		  },
				itemSelector : '.respl-item',
				filter: filterdef,
		      	sortBy : sortdef,
		      	layoutMode: 'fitRows',
				sortAscending: <?php echo ($params->get('article_ordering_direction') == 'ASC')?'true':'false'; ?>,
		      	getSortData : {
					id : function ( $elem ) {
						return parseInt( $elem.attr('data-id'), 10 )
		        	},
		        	title : function ( $elem ) {
			        	return $elem.attr('data-title');
		        	},
		        	hits : function( $elem ) {
				    	return parseInt( $elem.attr('data-hits'), 10 );
				    },
			        created : function( $elem ) {
				        return  parseInt( $elem.attr('data-created'), 10 );
				    },
				    modified : function( $elem ) {
				        return  parseInt( $elem.attr('data-modified'), 10 );
				    },
				    ordering : function( $elem ) {
				        return  parseInt( $elem.attr('data-ordering'), 10 );
				    },
					publishUp : function( $elem ) {
				        return parseInt( $elem.attr('data-publishUp'));
				    },
					publishDown : function( $elem ) {
				        return parseInt( $elem.attr('data-publishDown'));
				    }

		      	}
			});
		 

		if ( $.browser.msie  && parseInt($.browser.version, 10) <= 8){
			//nood
		}else{
			$(window).resize(function() {
				$container.isotope('reLayout');
			});
	    }
		
		if($('.respl-cat', $respl).css('float') == 'none'){
			$('.respl-cats-wrap', $respl).on('mouseover',function(){
				$('.respl-cats', $respl).removeAttr('style');
				_opTionSets();
			 });
		}
	    
		if($('.sort-select', $respl)){
			$('.sort-wrap', $respl).on('mouseover',function(){
				$('.sort-select', $respl).removeAttr('style');
				_opTionSets();
			});
		}
		_opTionSets();
		function _opTionSets(){
			var $optionSets = $('.respl-header .respl-option', $respl),
				$optionLinks = $optionSets.find('a');
				$optionLinks.each(function(){
				$(this).click(function(){
					var $this = $(this);
					var $optionSet = $this.parents('.respl-option');
			  
					$this.parent().addClass('sel').siblings().removeClass('sel');
					
					if($this.parents('.respl-categories')){
						$('.sort-curr',$this.parents('.respl-categories')).html($this.html());
						$('.sort-curr',$this.parents('.respl-categories')).attr('data-filter_value',$this.attr('data-rl_value'));
					}
					if($('.respl-cat', $respl).css('float') == 'none'){
						if($('.respl-cats', $respl).css('display') == 'block'){
							$('.respl-cats', $respl).css('display','none');
						}
					}else{
						 $('.respl-cats', $respl).removeAttr('style');
					}
					$('.sort-select', $respl).css('display','none');
					
					if($this.parents('.respl-sort')){
					   $('.sort-inner',$this.parents('.respl-sort')).attr('data-curr',$this.html());
					   $('.sort-inner',$this.parents('.respl-sort')).attr('data-curr_value',$this.attr('data-rl_value'));
					}
				
					
					// make option object dynamically, i.e. { filter: '.my-filter-class' }
					var options = {},
						key =$optionSet.attr('data-option-key'),
						value = $this.attr('data-rl_value');
					// parse 'false' as false boolean
					value = value === 'false' ? false : value;
					options[ key ] = value;
					if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
					  // changes in layout modes need extra logic
					  changeLayoutMode( $this, options )
					} else {
					  // otherwise, apply new options
					  $container.isotope( options );
					}
				
				return false ;
			   });
			});
		}
	      // change layout
	      function changeLayoutMode( $link, options ) {
	          if(options.layoutMode == 'straightDown'){
	        	 $('.item-image img.respl-nophoto', $respl).parent().parent().addClass('respl-nophoto');
				 $('.respl-wrap',$respl).css('overflow','hidden');
	        	 $('.respl-items', $respl).removeClass('grid').addClass('list');
	        	 $container.isotope('reLayout');
	         }else{
	          	 $('.item-image img.respl-nophoto', $respl).parent().parent().removeClass('respl-nophoto');
				 $('.respl-wrap',$respl).removeAttr('style');
	        	 $('.respl-items', $respl).removeClass('list').addClass('grid');
	        	 $container.isotope('reLayout');
	         }
	      }

	   });
	   
	   		var updateCount = function(){
	   			$('.respl-loader', $respl).removeClass('loading');
	   			var countitem = $('.respl-item',$respl).length;
				var currents = $('.respl-item', $respl), countall = currents.length;
				if($('li.respl-cat a').attr('data-count') === undefined){
					//nood
				}else{
					$('[data-count]', $respl).each(function(){
						var $this = $(this), data = $this.data();
						if (data.rl_value){
							if (data.rl_value == '*'){
								$this.attr('data-count', countall);
							} else {
								$this.attr('data-count', currents.filter(data.rl_value).length);
							}
						}
					});
				}
				$('.loader-image',  $respl).css({display:'none'});
				$('a.respl-button',$respl).attr('data-rl_start', countitem);
				var rl_total = $('a.respl-button', $respl).attr('data-rl_total');
				var rl_load = $('a.respl-button', $respl).attr('data-rl_load');
				var rl_allready = $('a.respl-button', $respl).attr('data-rl_allready');
				if(countitem < rl_total){
					$('.load-number', $respl).attr('data-total', (rl_total - countitem));
	     				if((rl_total - countitem)< rl_load ){
	     					$('.load-number',  $respl).attr('data-more', (rl_total - countitem));
	     			}
				}
				if(countitem == rl_total){
					$('.respl-loader',  $respl).addClass('loaded');
					$('.loader-image',  $respl).css({display:'none'});
					$('.loader-label',  $respl).html(rl_allready);
					$('.respl-loader',  $respl ).removeClass('loading');
				}
	   		};
	   		
	   		
			$('.respl-loader', $respl).click(function(){
				var $that = this;
				if ($('.respl-loader', $respl ).hasClass('loaded') || $('.respl-loader', $respl).hasClass('loading')){
					return false;
				}else{
					$('.respl-loader', $respl).addClass('loading');
					$('.loader-image',  $respl).css({display:'inline-block'});
					var rl_start = $('a.respl-button', $respl).attr('data-rl_start');
					var rl_moduleid = $('a.respl-button', $respl).attr('data-rl_moduleid');
					var rl_ajaxurl = $('a.respl-button', $respl).attr('data-rl_ajaxurl');
					$.ajax({
						type: 'POST',
						url: rl_ajaxurl,
						data:{
							sj_module_id: rl_moduleid,
							is_ajax: 1,
							ajax_reslisting_start: rl_start
						},
						success: function(data){
							if($(data.items_markup).length > 0){
								var $newItems = $(data.items_markup).removeClass('first-load');
								$('.item-image img.respl-nophoto', $newItems).parent().parent().addClass('respl-nophoto');
								$newItems.imagesLoaded( function(){
									$container.isotope('insert',$newItems).isotope( 'reLayout');
									updateCount();
								});
							}
						}, dataType:'json'
						
					});
				}
				return false;
	      });
	
	})('#<?php echo $tag_id; ?>');
});

//]]>
</script>