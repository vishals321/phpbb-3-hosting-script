<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: agb.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Include the required files */
require_once('./includes/global.php');
/* Load language */
$lang = $language->language_select($database, $root_connection, "agb");
/* Replace <# SEITE #> with $site_title */
$lang[1]['L_AGB_CONTENT'] = str_replace("<# SEITE #>", $site_title, $lang[1]['L_AGB_CONTENT']);
/* Set up template vars */
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'overall_header.html',
	'footer' => 'overall_footer.html',
    'body' => 'agb.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>