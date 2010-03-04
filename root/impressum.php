<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: impressum.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Include the required files */
require_once('./includes/global.php');
/* Read the Data of the Impressum out of the database */
$owner = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_owner';", $root_connection);
$owner = mysql_fetch_array($owner);
$site_owner = $owner['config_value'];

$street = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_street';", $root_connection);
$street = mysql_fetch_array($street);
$site_street = $street['config_value'];

$city = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_city';", $root_connection);
$city = mysql_fetch_array($city);
$site_city = $city['config_value'];

$fon = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_fon';", $root_connection);
$fon = mysql_fetch_array($fon);
$site_fon = $fon['config_value'];

$icq = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_icq';", $root_connection);
$icq = mysql_fetch_array($icq);
$site_icq = $icq['config_value'];

$contact = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_contact';", $root_connection);
$contact = mysql_fetch_array($contact);
$site_contact = $contact['config_value'];
$site_contact = str_replace("@", "&#64",$site_contact);

/* Load language */
$lang = $language->language_select($database, $root_connection, "impressum");
/* Template engine start */
$content = array(
	'TITLE' => $site_title,
	'META_KEYWORDS' => $meta_keywords,
	'META_DESCRIPTION' => $meta_description,
	'SITE_OWNER' => $site_owner,
	'SITE_STREET' => $site_street,
	'SITE_CITY' => $site_city,
	'SITE_FON' => $site_fon,
	'SITE_ICQ' => $site_icq,
	'SITE_CONTACT' => $site_contact,
);
/* Output */
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
	'header' => 'overall_header.html',
	'footer' => 'overall_footer.html',
	'body' => 'impressum.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>