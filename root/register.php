<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: register.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Include the required files */
require_once('./includes/global.php');
require_once('./includes/functions/recaptchalib.php');
/* Load language */
$lang = $language->language_select($database, $root_connection, "register");
/* Check writeable */
if(!is_writable('phpBB3/images/avatars/upload/'))
{
	$writeable .= $lang[1]['L_AVATAR_NOT_WRITEABLE'];
}
if(!is_writable('phpBB3/files/'))
{
	$writeable .= $lang[1]['L_FILES_NOT_WRITEABLE'];
}
if(!is_writable('phpBB3/cache/'))
{
	$writeable .= $lang[1]['L_CACHE_NOT_WRITEABLE'];
}
if(!is_writable('phpBB3/images/logos/'))
{
	$writeable .= $lang[1]['L_LOGO_NOT_WRITEABLE'];
}
if(!isset($writeable))
{
	if(isset($_POST['SUBMIT']))
	{
		/* read out the private recaptcha key */
		$recaptcha_private = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 're_captcha_private';", $root_connection);
		$recaptcha_private = mysql_fetch_array($recaptcha_private);
		$recaptcha_private = $recaptcha_private['config_value'];
		$resp = recaptcha_check_answer($recaptcha_private,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
		
		/* get the required inputs */
		$adress = mysql_escape_string($_POST["ADRESS"]);
		$title = mysql_escape_string($_POST["TITLE"]);
		$domain = strtolower(mysql_escape_string($_POST["DOMAIN"]));
		$user_name = mysql_escape_string($_POST["USER_NAME"]);
		$password = mysql_escape_string($_POST["PASSWORD"]);
		$password_w = mysql_escape_string($_POST["PASSWORD_W"]);
		$where = mysql_escape_string($_POST["WHERE"]);
		$agb_read = mysql_escape_string($_POST["AGB_READ"]);
		$desc = mysql_escape_string($_POST["DESC"]);
		$cat = mysql_escape_string($_POST["CATEGORY"]);
		
		/* Check the inputs */
		if (!$resp->is_valid) {
			$error['RECAPTCHA'] = $lang[1]['L_ERROR_CAPTCHA'];
		}
		if (!preg_match("/^[0-9a-zA-Z_.-]+@[0-9a-z.-]+\.[a-z]{2,6}$/", $adress)) {
			$error['ADRESS'] = $lang[1]['L_ERROR_ADRESS'];
		}
		if (empty($user_name)) {
			$error['USER_NAME'] = $lang[1]['L_ERROR_USER_NAME'];
		}
		if (empty($password)) {
			$error['PASSWORD'] = $lang[1]['L_ERROR_PASSWORD'];
		}
		if (empty($password_w)) {
			$error['PASSWORD_W'] = $lang[1]['L_ERROR_PASSWORD_W'];
		}
		if($password != $password_w) {
			$error['PASSWORD_W_H'] = $lang[1]['L_ERROR_PASSWORD_W_H'];
		}
		if ($agb_read != "agb_read") {
			$error["AGB"] = $lang[1]['L_ERROR_AGB_READ'];
		}
		if (empty($domain)) {
			$error["DOMAIN"] = $lang[1]['L_ERROR_DOMAIN'];
		}
		if(!preg_match('/^[a-z0-9]+$/', $domain))
		{
			$error["DOMAIN_SONDERZEICHEN"] = $lang[1]['L_ERROR_DOMAIN_SONDERZEICHEN'];
		}
		if (empty($cat)) {
			$error["CAT"] = $lang[1]['L_ERROR_CAT'];
		}
		$checkdomain = $database->query("SELECT domain FROM `hosting_forums` WHERE `domain` = '{$domain}';", $root_connection); 
		if ($database->total_rows($checkdomain)!=0)
		{ 
			 $error["THERE"] = $lang[1]['L_ERROR_THERE'];
		}
		if($domain == "hosting")
		{
			$error["THERE"] = $lang[1]['L_ERROR_THERE'];
		}
		/* Load config vars */
		$gzip = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'gzip';", $root_connection);
		$gzip = mysql_fetch_array($gzip);
		$gzip = $gzip['config_value'];
		
		$avatar_filesize = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'avatar_filesize';", $root_connection);
		$avatar_filesize = mysql_fetch_array($avatar_filesize);
		$avatar_filesize = $avatar_filesize['config_value'];
		
		$attachment_quota = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'attachment_quota';", $root_connection);
		$attachment_quota = mysql_fetch_array($attachment_quota);
		$attachment_quota = $attachment_quota['config_value'];
		/* Read out the IP of the user */
		$ip = $_SERVER["REMOTE_ADDR"];
		/* When there is no error */
		if(!isset($error))
		{
			$work = true;
			$install_lang = $language->language_select_install($database, $root_connection);
			require_once "includes/install/install.php";
			$phpbb_install_queries = str_replace("<# phpBB #>", $domain, $phpbb_install_queries);
			$install_connection = $database->sel_database($root_connection);
			for ($i = 0; $i < count($phpbb_install_queries); $i++)
			{
				$database->query($phpbb_install_queries[$i], $install_connection['res']);
			}
			$database->query("INSERT INTO `hosting_forums` (`domain` , `database_id`,`title` ,`description` ,`start_e_mail` , `password`,`creation_time` ,`category` ,`owner_name` ,`where` ,`ip` ,`active`) VALUES ('{$domain}','{$install_connection['id']}','{$title}', '{$desc}', '{$adress}', '".md5($password)."', '".time()."', '{$cat}', '{$user_name}', '{$where}', '{$ip}', '1');", $root_connection);
			$var = array('MAIL' => $adress, 'DOMAIN' => $domain,'USER' => $user_name, 'PASSWORD' => $password);
			
			$avatar_path = "phpBB3/images/avatars/upload/".$domain;
			$files_path = "phpBB3/files/".$domain;
			$cache_path = "phpBB3/cache/".$domain;
			mkdir($avatar_path, 0777);
			mkdir($files_path, 0777);
			mkdir($cache_path, 0777);
			copy("phpBB3/images/spacer.gif", "phpBB3/images/logos/".$domain.".gif");
			$mail_lang = $language->language_mail($database, $root_connection, "register");
			$mail_lang['message_text'] = str_replace('<# site_title #>', $site_title, $mail_lang['message_text']);
			$mail_lang['message_text'] = str_replace('<# domain #>', $domain, $mail_lang['message_text']);
			$mail_lang['message_text'] = str_replace('<# user #>', $user_name, $mail_lang['message_text']);
			$mail_lang['message_text'] = str_replace('<# password #>', $password, $mail_lang['message_text']);
			/* Set up the mail header */
			$from = "From: www.".$site_title." <noreply@".$site_title.">\n";
			$from .= "Reply-To: noreply@".$site_title."\n";
			$from .= "MIME-Version: 1.0\n";
			$from .= "Content-Type: text/html; charset=utf-8\n";
			$from .= "X-Priority: 1\n";
			$from .= "X-MSMail-Priority: High\n";
			$from .= "X-Mailer: php\n";
			/* Send mail */
			@mail($adress, $mail_lang['topic'], $mail_lang['message_text'], $from);
		}	
	}
		/* Load the recaptcha public key */
		$recaptcha_public = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 're_captcha_public';", $root_connection);
		$recaptcha_public = mysql_fetch_array($recaptcha_public);
		$recaptcha_public = $recaptcha_public['config_value'];
		$recaptcha_get_html = recaptcha_get_html($recaptcha_public );
		$lang[1]['L_REGISTER_DESC'] = str_replace("<# VERSION #>", $phpbb_version, $lang[1]['L_REGISTER_DESC']);
		$sql = $database->query("SELECT `id`,`headline` FROM `hosting_cat` ORDER BY `headline` ASC;", $root_connection);
		while($res = mysql_fetch_assoc($sql))
		{
			$template->assign_block_vars('cat', array('ID' => $res['id'], 'HEADLINE' => $res['headline']));
		}
	/* Set up template vars */
	if(isset($error))
	{
		$template->assign_block_vars('error', $error);
		$var = true;
	}
	if(isset($work))
	{
		$template->assign_block_vars('var', $var);
		$var1 = true;
	}
	$template->assign_block_vars('post', $_POST);
	$content = array(
			'TITLE' => $site_title,
			'META_KEYWORDS' => $meta_keywords,
			'META_DESCRIPTION' => $meta_description,
			'RECAPTCHA_GET_HTML' => $recaptcha_get_html,
			'VAR1' => $var1,
			'VAR' => $var,
			);
}
else
{
	$writeable .= $lang[1]['L_PLEASE_SET_CHMOD'];
	$content = array(
		'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'NOT_WRITEABLE' => true,
		'L_NOT_WRITEABLE' => $writeable,
	);
}
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'overall_header.html',
	'footer' => 'overall_footer.html',
    'body' => 'register.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>