<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: contact.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Include the required files */
require_once('./includes/global.php');
require_once('./includes/functions/recaptchalib.php');
/* Set up data for recaptcha usage */
$recaptcha_public = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 're_captcha_public';", $root_connection);
$recaptcha_public = mysql_fetch_array($recaptcha_public);
$recaptcha_public = $recaptcha_public['config_value'];
$recaptcha_get_html = recaptcha_get_html($recaptcha_public );

/* Load language */
$lang = $language->language_select($database, $root_connection, "contact");
/* Send */
if(isset($_POST['SUBMIT']))
{
	/* Read out the recaptcha private key */
	$recaptcha_private = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 're_captcha_private';", $root_connection);
	$recaptcha_private = mysql_fetch_array($recaptcha_private);
	$recaptcha_private = $recaptcha_private['config_value'];
	$resp = recaptcha_check_answer($recaptcha_private,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);

	/* Get required variables */
	$sender = stripslashes($_POST["SENDER"]);
	$adress = stripslashes($_POST["ADRESS"]);
	$topic = stripslashes($_POST["TOPIC"]);
	$inhalt = stripslashes($_POST["INHALT"]);
	
	/* Check the data to send*/
	if (!$resp->is_valid) {
		$error['RECAPTCHA'] = $lang[1]['L_ERROR_CAPTCHA'];
	}
	if (!preg_match("/^[0-9a-zA-Z_.-]+@[0-9a-z.-]+\.[a-z]{2,6}$/", $adress)) {
		$error['ADRESS'] = $lang[1]['L_ERROR_ADRESS'];
	}
	if (empty($sender)) {
		$error['SENDER'] = $lang[1]['L_ERROR_SENDER'];
	}
	if (empty ($topic)) {
		$error['TOPIC'] = $lang[1]['L_ERROR_TOPIC'];
	}
	if (empty ($inhalt)) {
		$error["INHALT"] = $lang[1]['L_ERROR_INHALT'];
	}
	/* Run the script when there is no error */
	if(!isset($error))
	{	
		/* get the e-mail adress of the admin */
		$contact = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_contact';", $root_connection);
		$contact = mysql_fetch_array($contact);
		$site_contact = $contact['config_value'];
		
		/* Contents of the e-mail */
		$subject 	= $lang[1]['subject'].$topic;
		$body    	= $lang[1]['text'].$inhalt."</i>";

		/* Set up the mail header */
		$from = "From: {$sender} <{$adress}>\n";
		$from .= "Reply-To: {$adress}\n";
		$from .= "MIME-Version: 1.0\n";
		$from .= "Content-Type: text/html; charset=utf-8\n";
		$from .= "X-Priority: 1\n";
		$from .= "X-MSMail-Priority: High\n";
		$from .= "X-Mailer: php\n";

		/* Send mail */
		if(!@mail($site_contact, $subject, $body, $from))
		{
			$error['MESSAGE_SEND'] = $lang[1]['L_ERROR_SEND'];
		}
		else
		{
			$error['MESSAGE_SEND'] = $lang[1]['L_GOOD_SEND'];
		}
	}
}
/* Set up template vars */
if(isset($error))
{
	$template->assign_block_vars('error', $error);
	$var = true;
}
$template->assign_block_vars('post', $_POST);
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'RECAPTCHA_GET_HTML' => $recaptcha_get_html,
		'VAR' => $var,
		);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'overall_header.html',
	'footer' => 'overall_footer.html',
    'body' => 'contact.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>