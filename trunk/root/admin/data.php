<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "data");
switch($_GET['case'])
{
	case 'edit':
		if(isset($_GET['domain']))
		{
			$domain = mysql_escape_string($_GET['domain']);
		}
		elseif(isset($_POST['domain']))
		{
			$domain = mysql_escape_string($_POST['domain']);
		}
		else
		{
			$message['NO_DOMAIN'] = true;
		}
		
		$checkdomain = $database->query("SELECT * FROM `hosting_forums` WHERE `domain` = '{$domain}';", $root_connection); 
		if ($database->total_rows($checkdomain)!=1)
		{ 
			 $message["NO_FORUM"] = true;
		}
		if(!isset($message))
		{
			$case = 'edit';
			$out = mysql_fetch_array($checkdomain);
			$template->assign_block_vars('info', array('TITLE' => $out['title'],'DESCRIPTION' => $out['description'], 'EMAIL' => $out['start_e_mail'],'WHERE'=>$out['where'],'DOMAIN'=>$out['domain'],'ID' => $out['id'],));
			$sql = $database->query("SELECT `id`,`headline` FROM `hosting_cat` ORDER BY `id` ASC;", $root_connection);
			while($res = mysql_fetch_assoc($sql))
			{
				if($res['id'] == $out['category'])
				{
					$selected = true;
				}
				else
				{
					$selected = false;
				}
				$template->assign_block_vars('cat', array('HEADLINE' => $res['headline'],'ID' => $res['id'],'SELECTED' => $selected));
			}
		}
	break;
	case 'edit-go':
		$title = mysql_escape_string($_POST["title"]);
		$password = mysql_escape_string($_POST["password"]);
		$password_w = mysql_escape_string($_POST["password_w"]);
		$where = mysql_escape_string($_POST["where"]);
		$desc = mysql_escape_string($_POST["desc"]);
		$cat = mysql_escape_string($_POST["category"]);
		$adress = mysql_escape_string($_POST["adress"]);
		$id = mysql_escape_string($_POST["id"]);
				
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
		if(isset($error))
		{
			$domain = mysql_escape_string($_POST['domain']);
			$checkdomain = $database->query("SELECT * FROM `hosting_forums` WHERE `domain` = '{$domain}';", $root_connection); 
			$out = mysql_fetch_array($checkdomain);
			$template->assign_block_vars('info', array('TITLE' => $out['title'],'DESCRIPTION' => $out['description'], 'EMAIL' => $out['start_e_mail'],'WHERE'=>$out['where'],'DOMAIN'=>$out['domain'],'ID' => $out['id'],));
			$sql = $database->query("SELECT `id`,`headline` FROM `hosting_cat` ORDER BY `id` ASC;", $root_connection);
			while($res = mysql_fetch_assoc($sql))
			{
				if($res['id'] == $out['category'])
				{
					$selected = true;
				}
				else
				{
					$selected = false;
				}
				$template->assign_block_vars('cat', array('HEADLINE' => $res['headline'],'ID' => $res['id'],'SELECTED' => $selected));
			}
			$case = 'edit';
		}
		if(!isset($error))
		{
			if(empty($password))
			{
				if($database->query("UPDATE `hosting_forums` SET `title` = '{$title}',`description` = '{$desc}', `start_e_mail` = '{$adress}',`category` = '{$cat}',`where` = '{$where}' WHERE `id` = '{$id}';",$root_connection))
				{
					$message['UPDATE'] = true;
				}
			}
			else
			{
				if($database->query("UPDATE `hosting_forums` SET `title` = '{$title}',`description` = '{$desc}', `start_e_mail` = '{$adress}',`category` = '{$cat}',`where` = '{$where}', `password` = '".md5($password)."' WHERE `id` = '{$id}';",$root_connection))
				{
					$message['UPDATE'] = true;
				}
			}
		}
	break;
	case 'config':
		if(isset($_GET['domain']))
		{
			$domain = mysql_escape_string($_GET['domain']);
			$case = 'config';
		}
		elseif(isset($_POST['domain']))
		{
			$domain = mysql_escape_string($_POST['domain']);
			$case = 'config';
		}
		else
		{
			$message['NO_DOMAIN'] = true;
		}
		$forum_info = mysql_fetch_array($database->query("SELECT `database_id` FROM `hosting_forums` WHERE `domain` = '{$domain}';", $root_connection));
		$query = $database->query("SELECT `server`, `database`, `user`, `password` FROM `hosting_database` WHERE `database_id` = '{$forum_info['database_id']}';", $root_connection);
		$answer = mysql_fetch_array($query);
		$connection = $database->connect($answer['server'],$answer['database'],$answer['user'],$answer['password'], 1);
		$sql = $database->query("SELECT `config_name`, `config_value` FROM `{$domain}_config` ORDER BY `config_name` ASC;", $connection);
		while($row = mysql_fetch_array($sql))
		{
			$template->assign_block_vars('config', array('CONFIG_NAME' => $row['config_name'], 'CONFIG_VALUE' => $row['config_value']));
		}
	break;
	case 'config-go':
		if(isset($_GET['domain']))
		{
			$domain = mysql_escape_string($_GET['domain']);
		}
		elseif(isset($_POST['domain']))
		{
			$domain = mysql_escape_string($_POST['domain']);
		}
		else
		{
			$message['NO_DOMAIN'] = true;
		}
		$forum_info = mysql_fetch_array($database->query("SELECT `database_id` FROM `hosting_forums` WHERE `domain` = '{$domain}';", $root_connection));
		$query = $database->query("SELECT `server`, `database`, `user`, `password` FROM `hosting_database` WHERE `database_id` = '{$forum_info['database_id']}';", $root_connection);
		$answer = mysql_fetch_array($query);
		$connection = $database->connect($answer['server'],$answer['database'],$answer['user'],$answer['password'], 1);
		$post = $_POST;
		$config_keys = array_keys($post);
		for ($i = 0; $i < count($post); $i++)
		{
			if ($config_keys[$i] != "domain" and $config_keys[$i] != "submit")
			{
				$database->query("UPDATE `{$domain}_config` SET `config_value` = '{$post[$config_keys[$i]]}' WHERE `config_name` = '{$config_keys[$i]}';", $connection);
			}
		}
		$message['UPDATE_CONFIG'] = true;
	break;
}/*templatete engine start */
$content1 = array(
		'site_title' => $site_title,
		'message' => $message,
		'row' => $out,
		'result' => $result,
		'count' => $count,
		'error' => $error,
		'config' => $config,
		);
/* Set up template vars */
if(isset($error))
{
	$template->assign_block_vars('error', $error);
	$error_da = true;
}
if(isset($message))
{
	$template->assign_block_vars('message', $message);
	$message_da = true;
}
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'CASE' => $case,
		'ERROR' => $error_da,
		'MESSAGE' => $message_da,
		'DOMAIN' => $domain,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/data.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>