<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = $displayData->params;
$canEdit = $displayData->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.framework');
?>

    <?php if ($params->get('show_title') || $displayData->state == 0 || ($params->get('show_author') && !empty($displayData->author ))) : ?>
        <header class="article-header">

            <?php if ($params->get('show_title')) : ?>
                <h3>
                    <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                        <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->slug, $displayData->catid)); ?>">
                        <?php echo limit_text($this->escape($displayData->title),6); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($displayData->title); ?>
                    <?php endif; ?>
                </h3>
            <?php endif; ?>

            <?php if ($displayData->state == 0) : ?>
                <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
            <?php endif; ?>
        </header>
    <?php endif; ?>