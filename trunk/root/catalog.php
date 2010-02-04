<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: catalog.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Include the required files */
require_once('./includes/global.php');
/* Load language */
$lang = $language->language_select($database, $root_connection, "catalog");
/* Switch cases */
switch($_GET['action'])
{
	case cat:
		/* Get category */
		$cat = mysql_escape_string($_GET['cat']);
		$sql = $database->query("SELECT domain,description,title FROM `hosting_forums` WHERE `category` = '{$cat}' AND `active` = '1' ORDER BY creation_time ASC;", $root_connection);
		while($res = mysql_fetch_assoc($sql))
		{
			$template->assign_block_vars('result', array(
				'DOMAIN' => $res['domain'],
				'DESCRIPTION' => $res['description'],
				'TITLE' => $res['title'],
			));
			$var = true;
		}
		/* Select title of category */
		$info = $database->query("SELECT headline FROM `hosting_cat` WHERE `id` = '{$cat}';", $root_connection);
		$cat_name = mysql_fetch_assoc($info);
		/* Template engine start */
		$content = array(
				'TITLE' => $site_title,
				'META_KEYWORD' => $meta_keywords,
				'META_DESCRIPTION' => $meta_description,
				'CAT_NAME' => $cat_name['headline'],
				'VAR' => $var,
				);
		/* Output */
		$template->assign_vars(array_merge($lang[0], $lang[1], $content));
		/* Template engine start */
		$template->set_filenames(array(
			'header' => 'overall_header.html',
			'footer' => 'overall_footer.html',
			'body' => 'catalog_cat.html'
		));
	break;
	default:
		$info = $database->query("SELECT id,headline FROM `hosting_cat` ORDER BY headline ASC;", $root_connection);
		$i = 1;
		while($res = mysql_fetch_assoc($info))
		{
			$id = $res['id'];
			$sql = $database->total_rows($database->query("SELECT id FROM `hosting_forums` WHERE `category` = '{$res['id']}' AND `active` = '1';", $root_connection));	
			$template->assign_block_vars('result', array(
				'HEADLINE' => $res['headline'],
				'ID' => $id,
				'FORUMS' => $sql,
			));
		}
		/* Template engine start */
		$content = array(
				'TITLE' => $site_title,
				'META_KEYWORDS' => $meta_keywords,
				'META_DESCRIPTION' => $meta_description,
				);
		/* Output */
		$template->assign_vars(array_merge($lang[0], $lang[1], $content));
		/* Template engine start */
		$template->set_filenames(array(
			'header' => 'overall_header.html',
			'footer' => 'overall_footer.html',
			'body' => 'catalog.html'
		));
		break;
}
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>