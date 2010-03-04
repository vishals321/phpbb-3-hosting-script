<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "database");

/* Switch Case */
switch($_GET['case'])
{
	case 'new':
		if(isset($_POST['submit']))
		{
			$server = mysql_escape_string($_POST['SERVER']);
			$database_name = mysql_escape_string($_POST['DATABASE']);
			$user = mysql_escape_string($_POST['USER']);
			$password = mysql_escape_string($_POST['PASSWORD']);
			$register_enabled = mysql_escape_string($_POST['REGISTER']);
			if($database->connect_test($server, $database_name, $user, $password) == false)
			{
				$error['NO_CONNECT'] =  true;
			}
			else
			{
				$error['NO_ERROR'] = true;
				$database->query("INSERT INTO `hosting_database` (`server` ,`database` ,`user` ,`password` ,`register_enabled`) VALUES ('{$server}', '{$database_name}', '{$user}', '{$password}', '{$register_enabled}');", $root_connection);
			}
		}
		$template->assign_block_vars('post', $_POST);
		$case = 'new';
	break;
	case 'edit':
		if(isset($_POST['submit']))
		{
			$id = mysql_escape_string($_POST['id']);
			$server = mysql_escape_string($_POST['server']);
			$database_name = mysql_escape_string($_POST['database']);
			$user = mysql_escape_string($_POST['user']);
			$password = mysql_escape_string($_POST['password']);
			$register_enabled = mysql_escape_string($_POST['register']);
			if($database->connect_test($server, $database_name, $user, $password) == false)
			{
				$error['NO_CONNECT'] =  true;
			}
			else
			{
				$error['NO_ERROR'] = true;
				$database->query("UPDATE `hosting_database` SET `server` = '{$server}', `database` = '{$database_name}', `user` = '{$user}', `password` = '{$password}', `register_enabled` = '{$register_enabled}' WHERE `database_id` = {$id};", $root_connection);
			}
		}
		else
		{
			$id = mysql_escape_string($_GET['id']);
		}
		$case = 'edit';
		$row = mysql_fetch_array($database->query("SELECT * FROM `hosting_database` WHERE `database_id` = '{$id}';", $root_connection));
		$template->assign_block_vars('database', array('DATABASE_ID'=>$row['database_id'],'SERVER'=>$row['server'],'DATABASE'=>$row['database'],'USER'=>$row['user'],'FORUMS'=>$row['forums'],'REGISTER_ENABLED'=>$row['register_enabled'],'PASSWORD'=>$row['password']));
	break;
	default:
		$sql = $database->query("SELECT * FROM `hosting_database` ORDER BY `database_id` ASC;", $root_connection);
		$i = 0;
		while ($row = mysql_fetch_array($sql))
		{
			$template->assign_block_vars('database', array('DATABASE_ID'=>$row['database_id'],'SERVER'=>$row['server'],'DATABASE'=>$row['database'],'USER'=>$row['user'],'FORUMS'=>$row['forums'],'REGISTER_ENABLED'=>$row['register_enabled']));
		}
	break;
}
/* Set up template vars */
if(isset($error))
{
	$template->assign_block_vars('error', $error);
	$error_da = true;
}
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'CASE' => $case,
		'ERROR' => $error_da,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/database.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');

?>