<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: stats.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Include the required files */
require_once('./includes/global.php');
/* Read the Data of the Stats out of the database */
$stats = $database->query("SELECT * FROM `hosting_stats`;", $root_connection);
$row_stats = mysql_fetch_array($stats);

/* Start the cron if the datas are to old */
$time = $row_stats['time'];
if(time() - $time > 43200)
{
	$renew = $true;
	require_once("./includes/cron/stats-cron.php");
}
$time_d = date("d.m.Y H:i",$time);
/* Load language */
$lang = $language->language_select($database, $root_connection, "stats");
/* Set up template vars */
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'TIME' => $time_d,
		'FORUMS' => $row_stats["forums"],
		'CAT' => $row_stats["cat"],
		'MEMBERS' => $row_stats["members"],
		'TOPICS' => $row_stats["topics"],
		'ANSWERS' => $row_stats["answers"],
		'GROUPS' => $row_stats["groups"],
		'RANKS' => $row_stats["ranks"],
		);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'overall_header.html',
	'footer' => 'overall_footer.html',
    'body' => 'stats.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>