<?php
/**
 * @package        Joomla.Site
 * @copyright    Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//get copyright
$app = JFactory::getApplication();
$date        = JFactory::getDate();
$template = $app->getTemplate(true);
$params = $template->params;
$cur_year    = $date->format('Y');
$ytcopyright = $params->get('ytcopyright' );
$ytcopyright = str_replace('{year}', $cur_year, $ytcopyright);


//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>

<html  lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <link href='https://fonts.googleapis.com/css?family=Dosis:400,700|Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $this->baseurl.'/templates/'.$this->template; ?>/css/error.css" type="text/css" />
</head>
<body>
<div class="container">
   <!-- <div class="logo">
        <a href="index.php">
        <img class="img_logo" src="<?php //echo JURI::base() . 'templates/' . JFactory::getApplication()->getTemplate();?>/images/styling/magenta/logo.png" alt="" />
        </a>
    </div> -->
     <div class="content">
         <div class="content-left">404</div>
         <div class="content-right">
             <span class="error">err0r</span>
       <span class="text-404"><?php echo JText::_('TEXT_404'); ?></span>
        <a class="btn" href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>">
            <?php echo JText::_('TEXT_404_RETURN_HOMEPAGE'); ?>
             </a></div>
    </div>
    <div id="techinfo">
        <span>
            <?php if ($this->debug) :
                echo $this->renderBacktrace();
            endif; ?>
        </span>
    </div>
</div>
<!-- <footer class="block-copyright" >
                <div class="copyright"><?php echo $ytcopyright;?></div>
                <div class="designby">Designed by <a target="_blank" title="Visit SmartAddons!" href="http://www.smartaddons.com/">SmartAddons.Com</a></div>
                <div class="powered-by"><a href="http://www.joomla.org">Joomla!</a> is Free Software released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html">GNU General Public License.</a></div>
            </footer> -->



</body>
</html>
