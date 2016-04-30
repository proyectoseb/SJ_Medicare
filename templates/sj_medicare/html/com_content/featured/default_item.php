<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;?>
<?php
// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canEdit = $this->item->params->get('access-edit');

?>
<?php if ($this->item->state == 0) : ?>
    <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
<?php endif; ?>
<div class="wrap-article">
    <div class="article-img">
    <?php //intro images ?>
    <?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
    <figure class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image" >
    <?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
    </figure>
     <?php if ($params->get('show_readmore') && $this->item->readmore) :
        if ($params->get('access-view')) :
            $link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
        else :
            $menu = JFactory::getApplication()->getMenu();
            $active = $menu->getActive();
            $itemId = $active->id;
            $link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
            $returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
            $link = new JURI($link1);
            $link->setVar('return', base64_encode($returnURL));
        endif; ?>

        <a class="button" href="<?php echo $link; ?>">

        <?php if (!$params->get('access-view')) :
            echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
        elseif ($readmore = $this->item->alternative_readmore) :
            echo $readmore;
            if ($params->get('show_readmore_title', 0) != 0) :
            echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
            endif;
        elseif ($params->get('show_readmore_title', 0) == 0) :
            echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
        else :
            echo JText::_('COM_CONTENT_READ_MORE');
            echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
        endif; ?>


        </a>

    <?php endif; ?>
    </div>    
    <div class="article-text">
        <?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>
        <?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>

       

    <?php if (!$params->get('show_intro')) : ?>
            <?php echo $this->item->event->afterDisplayTitle; ?>
    <?php endif; ?>

    <?php echo $this->item->event->beforeDisplayContent; ?>

    <?php if ($params->get('show_intro')) : ?>
        <div class="article-intro">
            <?php echo limit_text($this->item->introtext, 30); ?>
        </div>
    <?php endif; ?>
         <aside class="article-aside">
            <div class="time">
           <?php echo JText::sprintf( JHtml::_('date', $this->item->created, JText::_('M d, Y'))); ?>.
       </div>
            
            <?php // Todo Not that elegant would be nice to group the params ?>
            <?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date') || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author')); ?>

            <?php if ($useDefList) : ?>
                <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
            <?php endif; ?>
        </aside>
    </div>
</div>    

<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
