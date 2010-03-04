<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: user.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
session_start();
/* Include the required files */
require_once('./includes/global.php');
$error = $_SESSION['error'];
if(isset($error))
{
	session_unset();
	$logged_in = false;
	$id = false;
	$domain = false;
}
else
{
	$logged_in = $_SESSION['logged_in'];
	$id = $_SESSION['id'];
	$domain = $_SESSION['domain'];
}
/* Load language */
$lang = $language->language_select($database, $root_connection, "user");
if(isset($logged_in) and !isset($error))
{
	if(isset($_POST['submit']))
	{

		if($_POST['delete'] == true)
		{
			require_once('includes/functions/delete.php');
			$forum_domain = $domain;
			$delete = new delete();
			$error['DEL'] = $delete->delete_forum($forum_domain, $database, $root_connection);
			$logged_in = false;
			session_unset();
		}
		else
		{
			$title = mysql_escape_string($_POST["title"]);
			$password = mysql_escape_string($_POST["password"]);
			$password_w = mysql_escape_string($_POST["password_w"]);
			$where = mysql_escape_string($_POST["where"]);
			$desc = mysql_escape_string($_POST["desc"]);
			$cat = mysql_escape_string($_POST["category"]);
			$adress = mysql_escape_string($_POST["adress"]);
			
			if (!preg_match("/^[0-9a-zA-Z_.-]+@[0-9a-z.-]+\.[a-z]{2,6}$/", $adress))
			{
				$error['ADRESS'] = true;
			}
			
			if (empty($cat)) {
				$error["CAT"] = true;
			}
			
			if($password != $password_w) {
				$error['PASSWORD_W_H'] = true;
			}
			
			if(!isset($error))
			{
				if(empty($password))
				{
					if($database->query("UPDATE `hosting_forums` SET `title` = '{$title}',`description` = '{$desc}', `start_e_mail` = '{$adress}',`category` = '{$cat}',`where` = '{$where}' WHERE `id` = '{$id}';",$root_connection))
					{
						$error['update'] = $lang[1]['update'];
					}
				}
				else
				{
					if($database->query("UPDATE `hosting_forums` SET `title` = '{$title}',`description` = '{$desc}', `start_e_mail` = '{$adress}',`category` = '{$cat}',`where` = '{$where}', `password` = '".md5($password)."' WHERE `id` = '{$id}';",$root_connection))
					{
						$error['update'] = $lang[1]['update'];
					}
				}
			}
		}
	}
	$user_link = "Logout";
	$user_data = "login.php?case=logout";
	$data = mysql_fetch_array($database->query("SELECT * FROM `hosting_forums` WHERE `id` = '{$id}';", $root_connection));
	$sql = $database->query("SELECT `id`,`headline` FROM `hosting_cat` ORDER BY `headline` ASC;", $root_connection);
	$i = 1;
	while($res = mysql_fetch_array($sql))
	{
		if($res['id'] == $data['category'])
		{
			$selected = true;
		}
		else
		{
			$selected = false;
		}
		$template->assign_block_vars('cat', array('ID' => $res['id'], 'HEADLINE' => $res['headline'], 'SELECTED' => $selected));
	}
}
else
{
	$user_link = "Login";
	$user_data = "user.php";
}
if(isset($error))
{
	$template->assign_block_vars('error', $error);
}
if(isset($data))
{
	$template->assign_block_vars('data', array('TITLE' => $data['title'],'DESCRIPTION' => $data['description'],'MAIL' => $data['start_e_mail'], 'WHERE' => $data['where'],));
}

/* Set up template vars */
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'LOGGED_IN' => $logged_in,
		'LINK_USER' => $user_link,
		'LINK_USER_DATA' => $user_data,
		);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'overall_header.html',
	'footer' => 'overall_footer.html',
    'body' => 'user.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>