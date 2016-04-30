<?php
/**
 * @package Sj Content Responsive Listing
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

$com_path = JPATH_SITE.'/components/com_content/';
require_once $com_path.'router.php';
require_once $com_path.'helpers/route.php';
if(!class_exists('JModelLegacy')){
	class JModelLegacy extends JModel{
	}
}
JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
require_once dirname(__FILE__).'/helper_base.php';

class ContentResponsiveListingHelper extends ContentResponsiveListingBaseHelper {
		protected static $helper = null;
		public static $total = null;
		protected $categories = null;
		protected $children = array();
		protected $items = null;
		protected $params = null;
		
		public static function getList($params , $module){
			$helper = self::getInstance();
			return $helper->_getList($params, $module);
		}
		
		protected function _getList($params, $module){
			$list = array();
			$retur = array();
			$source_category = $params->get('catid');
			self::$total = $this->getTotal($source_category,$params);
			$catids  = $this->getCategories($source_category, $params);
			if(!empty($catids)){
				$categories = $this->getCategoriesIn($catids,$params);
				include_once JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php';
				foreach($categories as $cat){
					$category = $this->getCategory($cat, $params);
					$category->count = 0;
					$list[$category->id] = $category;
				}
				
				$retur['categories'] = $list;
				
				$items = $this->getCategoryItems($catids, $params);
				if(!empty($items)) {
					include_once JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php';
					foreach($items as $key => $item){
						$category = $this->getCategory($item->catid,$params);
						$category = $list[$item->catid];
						if(isset($category->count)){
							$category->count ++;
						}else{
							$category->count = 1;
						}
						$item->catslug = $item->catid ? $item->catid .':'.$category->alias : $item->catid;
						$item->catlink = JRoute::_( ContentHelperRoute::getCategoryRoute($item->catslug) );
						$item->category_title = $category->title;
						if(class_exists('JHelperTags')){
							$item->tags = new JHelperTags;
							$item->tags->getItemTags('com_content.article', $item->id);
						}else{
							$item->tags = '';
						}
						$author = JFactory::getUser($item->created_by);
						$item->author = $author->name;
						$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));
						if ($params->get('item_description_display', '1') == '1')
						{
							$item->text = $item->introtext.' '.$item->fulltext;
						}
						elseif ($item->fulltext)
						{
							$item->text = $item->fulltext;
						}
						else  {
							$item->text = $item->introtext;
						}
						
						$item->text = self::_cleanText($item->text);
					}
				}
				
				if ($params->get('tab_all_display', 1)){
					$all = new stdClass();
					$all->id = '*';
					$all->count = count($items);
					$all->title = JText::_('ALL_LABEL');
				
					array_unshift($retur['categories'], $all);
				}
				// default select
				$catidpreload = $params->get('catid_preload');
				$selected = false;
				foreach ($retur['categories'] as $cat){
					if ( $cat->id == $catidpreload && $cat->count > 0 ){
						$cat->sel = 'sel';
						$selected = true;
					}
				}
				// first tab is active
				if (!$selected){
					foreach ($retur['categories'] as $cat){
						if ($cat->count > 0){
							$cat->sel = 'sel';
							break;
						}
					}
				}
				
				$retur['items'] = $items;
				//var_dump($retur['categories']);
				return $retur;
			}
		}
	
		public function getItems($ids = null){
			
			$items = array();
			if ( is_string($ids) ){
				$ids = explode(',', $ids);
			}
			if ( is_array($ids) ){
				array_map('intval', $ids);
				$ids = array_unique($ids);
				$missing = array();
		
				foreach ($ids as $id) {
					if (!isset($this->items[$id])){
						$missing[$id] = $id;
					}
				}
					
				empty($missing) OR $this->getItemsFromDb($missing, false);
				foreach ($ids as $id){
					if (isset($this->items[$id])){
						$items[$id] = $this->items[$id];
					}
				}
			}
			return $items;
		}
		
		
		public function getCategory($cid=0,$params){
			$categories = $this->getCategoriesFromDb();
			if ( isset($categories[$cid]) ){
				return $categories[$cid];
			}
			return false;
		}
		
		public function getCategories($cids = null,$params){
			$category_ids = null;
			if (isset($cids) && !empty($cids)){
				$category_ids = $cids;
				if (is_string($category_ids)){
					$category_ids = explode(',', $category_ids);
				}
				if (!is_array($category_ids)){
					$category_ids = array($category_ids);
				}
				
				if ( !is_array($category_ids) ){
					settype($category_ids, 'array');
				}
				
				$categories = $this->getCategoriesFromDb();
				
				foreach ($category_ids as $i => $cid) {
					if (!isset($categories[$cid])){
						unset($category_ids[$i]);
					}
				}
			}
		
			return $category_ids;
		}
		
		public function getCategoryItems($cid, $params){
			$cid = $this->getCategories($cid,$params);
			$item_ids = $this->getItemsIn($cid, $params);
			return $this->getItems($item_ids, $params);
		}
		
		public function getTotal($ids,$params){
			$ids = $this->getCategories($ids,$params);
			if (!is_array($ids)){
				$ids = array((int)$ids);
			}
			if(!empty($ids)){
				$db = JFactory::getDbo();
				$query = "SELECT count(count_table.id) as countitems  FROM #__content AS count_table WHERE count_table.catid IN (" . implode(',', $ids)  . ") AND count_table.state IN(1) {$this->_itemFilter($params,"count_table")}
				 " . ($this->_getContentAccessFilter('count_table') ? 'AND '.$this->_getContentAccessFilter('count_table') : '') ."";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$countitems = 0;
				if ( !is_null($rows) ){
					foreach($rows as $item){
						$countitems = $item->countitems;
					}
				}
				return $countitems;
			}
		}
		
		
		public function getItemsFromDb($ids, $overload = false){
			if (!is_array($ids)){
				$ids = array((int)$ids);
			}
			if(!empty($ids)){
				$db = JFactory::getDbo();
				$query = "SELECT *  FROM #__content AS e WHERE e.id IN (" . implode(',', $ids)  . ") GROUP BY e.id;";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$item_count = 0;
				if ( !is_null($rows) ){
					foreach($rows as $item){
						if ($overload || !isset($this->items[$item->id])){
							$this->items[$item->id] = $item;
							$item_count++;
						}
					}
				}
			
				return $item_count;
			}
		}
		
		public function getItemsIn($cids, $params){
			$order_by = $params->get('source_order_by');
			$itemsOrdering_display = $params->get('itemsOrdering_display');
			if(!empty($itemsOrdering_display) && !in_array($order_by,$itemsOrdering_display)){
				$order_by = $itemsOrdering_display[0];
			}
				
			$db = JFactory::getDbo();
			$date = JFactory::getDate();
			$fn = (method_exists($date, 'toSql'))?'toSql':'toMySQL';
			
			$now = $date->$fn();  
			
			$nulldate = $db->getNullDate();
		
			if (isset($cids) && is_array($cids)){
				$category_filter_set = implode(',', $cids);
			}
			
			if(!empty($category_filter_set)){
				$query = "
				SELECT *
				FROM #__content as e
				WHERE
				e.state IN(1)
				AND e.catid IN ($category_filter_set)
				" . ($this->_getContentAccessFilter() ? 'AND '.$this->_getContentAccessFilter() : '') ."
				AND (e.publish_up   = {$db->quote($nulldate)} OR e.publish_up   <= {$db->quote($now)})
				AND (e.publish_down = {$db->quote($nulldate)} OR e.publish_down >= {$db->quote($now)})
				AND e.language IN ({$db->quote(JFactory::getLanguage()->getTag())} , {$db->quote('*')})
				{$this->_itemFilter($params)}
				 GROUP BY e.id  ";
				$query .=  ($order_by != 'random')?" ORDER BY {$this->_itemOrders($params)} ":"";
				$query .= 	$this->_queryLimit($params);
				$db->setQuery($query);
				$ids = $db->loadColumn();
				//echo str_replace('#_','jos', $query);die;
				if(($order_by == 'random')){
					shuffle($ids);
				}
			return $ids;
			}
		}
		
		public function getCategoriesIn($cids,$params){
			if (isset($cids) && is_array($cids)){
				$category_filter_set = implode(',', $cids);
			}
			if(!empty($category_filter_set)){
				$db = JFactory::getDbo();
				$query = "
				SELECT *
				FROM #__categories AS e
				WHERE
				e.published IN (1)
				AND e.id IN ($category_filter_set)
				AND e.extension = 'com_content'
				AND e.parent_id > 0
				" . ($this->_getContentAccessFilter() ? 'AND '.$this->_getContentAccessFilter() : '') . " -- Access condition
						AND e.language IN ({$db->quote(JFactory::getLanguage()->getTag())} , {$db->quote('*')})
						GROUP BY e.id
						ORDER BY {$this->_catOrders($params)}
						";
						$db->setQuery($query);
						$ids = $db->loadColumn();
				return $ids;
			}
		}
		
		public function getCategoriesFromDb(){
			if (is_null($this->categories)){
				$db = JFactory::getDbo();
				$query = "
				SELECT *
				FROM #__categories AS e
				WHERE
				e.published IN (1)
				AND e.extension = 'com_content'
				AND e.parent_id > 0
				" . ($this->_getContentAccessFilter() ? 'AND '.$this->_getContentAccessFilter() : '') . " -- Access condition
						AND e.language IN ({$db->quote(JFactory::getLanguage()->getTag())} , {$db->quote('*')})
						GROUP BY e.id
						ORDER BY e.lft
						";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				if ( !is_null($rows) ){
					$_categories = array();
					foreach($rows as $i=> $category){
						$_categories[$category->id] = &$rows[$i];
					}
					$this->categories = $_categories;
					$this->buildCategoriesTree();
				}else{
					$this->categories = array();
				}
			}
			return $this->categories;
		}
		
		protected function buildCategoriesTree(){
			$categories = $this->getCategoriesFromDb();
			if ( count($categories) ){
				foreach ($categories as $cid => $category){
					$cid = $category->id;
					$pid = $category->parent_id;
					if (isset($categories[$pid])){
						if (!isset($this->children[$pid])){
							$this->children[$pid] = array();
						}
						$this->children[$pid][$cid] = $cid;
					}
				}
				return 1;
			}
			return 0;
		}
		
		
		protected function _getContentAccessFilter($alias='e'){
			$condition = false;
			$app  = JFactory::getApplication();
			$params = $app->getParams();
			if ($params instanceof JRegistry && !$params->get('show_noauth', 0)){
				$user = JFactory::getUser();
				$condition = $alias . '.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';
			}
			return $condition;
		}
		
		protected function _itemFilter($params, $alias='e'){
			$join_filter="";
			$show_front = $params->get('show_front');
			if ( isset($show_front) ){
				// frontpage filter.
				switch ($show_front){
					default:
					case 'show':
						$join_filter = "";
						break;
					case 'hide':
						$join_filter = "AND $alias.featured=0";
						break;
					case 'only':
						$join_filter = "AND $alias.featured=1";
						break;
				}
			}
			return $join_filter;
		}
		
		protected function _catOrders($params, $alias='e'){
			// set order by default
			$cat_order_by = "$alias.title";
			$source_order_by = $params->get('category_ordering');
			if ( isset($source_order_by) ){
				switch ($source_order_by){
					default:
					case 'title':
						$cat_order_by = "$alias.title";
						break;
					case 'lft':
						$cat_order_by = "$alias.lft ASC";
						break;
					case 'random':
						$cat_order_by = "rand()";
						break;
				}
			}
			return $cat_order_by;
		}
		
		
		protected function _itemOrders($params, $alias='e'){
			// set order by default
			$item_order_by = "";
			$order_by = $params->get('source_order_by');
			$itemsOrdering_display = $params->get('itemsOrdering_display');
			if(!empty($itemsOrdering_display) && !in_array($order_by,$itemsOrdering_display)){
				$order_by = $itemsOrdering_display[0];
			}
			
			$order_dir = $params->get('article_ordering_direction');
			if ( isset($order_by) ){
				$string_order_by = trim($order_by);
				switch ($string_order_by){
					default:
					case 'id':
						$item_order_by = "$alias.id";
						break;
					case 'title':
						$item_order_by = "$alias.title";
						break;
					case 'ordering':
						$item_order_by = "$alias.ordering";
					break;
					case 'mostview':
					case 'hits':
						$item_order_by = "$alias.hits ";
						break;
					case 'recently_add':
					case 'created':
						$item_order_by = "$alias.created ";
						break;
					case 'recently_mod':
					case 'modified':
						$item_order_by = "$alias.modified ";
						break;
					case 'publish_up':
						$item_order_by = "$alias.publish_up ";
						break;
					case 'publish_down':
						$item_order_by = "$alias.publish_down ";
						break;	
					// case 'random':
						// $item_order_by = "$alias.ordering";
						// break;
				}
			}
			return $item_order_by.' '.$order_dir;
		}
		
		protected function _queryLimit($params){
			$app = JFactory::getApplication();
			$appParams = $app->getParams();
			$source_limit = '';
			$limitation = $params->get('source_limit');
			if (isset($limitation) && (int)$limitation>=0){
				
				$source_limit_total = (int)$limitation;
				$source_limit = 'LIMIT '. $app->input->getInt('ajax_reslisting_start',0).','. $source_limit_total;
			}
			return $source_limit;
		}
		
		public static function getInstance(){
			if(is_null(self::$helper)){
				$classname = __CLASS__;
				self::$helper = new $classname;
			}
			return self::$helper;
		}
}
