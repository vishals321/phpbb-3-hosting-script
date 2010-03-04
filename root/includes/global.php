<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: includes/global.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Set up the root connection */
header('Content-Type: text/html; charset=utf-8', true);
require_once('includes/config.php');
require_once('includes/functions/database.php');
$database = new database();
$root_connection = $database->connect($host, $database_name, $user, $password);
unset($user);
/* Load title, description and so on */
$title = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_title';", $root_connection);
$title = mysql_fetch_array($title);
$site_title = $title['config_value'];

$keywords = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'meta_keywords';", $root_connection);
$keywords = mysql_fetch_array($keywords);
$meta_keywords = $keywords['config_value'];

$description = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'meta_description';", $root_connection);
$description = mysql_fetch_array($description);
$meta_description = $description['config_value'];
/* Load template class */
require_once('includes/functions/template.php');
$template = new Template();
$style_name = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'style_name';", $root_connection);
$style_name = mysql_fetch_array($style_name);
$style_name = $style_name['config_value'];
$template->set_custom_template('template/'.$style_name, 'default'); //this is important as it states where the template files are located
/* Load language class */
require_once('includes/functions/language.php');
$language = new language();
?>