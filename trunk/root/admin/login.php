<?php
session_start();
chdir("./../"); 
/* Set up the root connection */
require_once('includes/config.php');
require_once('includes/functions/database.php');
$database = new database();
$root_connection = $database->connect($host, $database_name, $user, $password);

switch($_GET['case'])
{
	case 'logout':
			session_destroy();
			header('Location: login.php');
	break;
	case 'login':
		if(isset($_POST['submit']))
		{	
			if(empty($_POST['login_name']))
			{
				$error['LOGIN_NAME'] = true;
			}
			else
			{
				$username = $_POST['login_name'];
			}
			
			if(empty($_POST['password']))
			{
				$error['PASSWORD'] = true;
			}
			else
			{
				$password = $_POST['password'];
			}
			
			$checkdata = $database->query("SELECT `id`,`admin_name`, `password` FROM `hosting_admins` WHERE `admin_name` = '{$username}';", $root_connection);
			$data = mysql_fetch_array($checkdata);	
			
			if ($database->total_rows($checkdata)==0)
			{ 
				 $error['THERE'] = true;
			}
			
			if(md5($password) != $data['password'])
			{
				$error['PASSWORD_W'] = true;
			}
			
			// Benutzername und Passwort werden überprüft
			if(!isset($error))
			{
			   $_SESSION['logged_in_admin'] = true;
			   $_SESSION['id'] = $data['id'];
			   $_SESSION['admin_name'] = $data['admin_name'];
			   header('Location: index.php');
			   exit;
			}
			else
			{
				$_SESSION['error'] = $error;
				$_SESSION['logged_in_admin'] = false;
				header('Location: login.php');
				exit;			
			}
		}
	break;
	default:
		if($_SESSION['logged_in_admin'] == true)
		{
			header('Location: login.php?case=logout');
		}
		$error = $_SESSION['error'];
		if(isset($error))
		{
			session_unset();
		}
		/* Set up the root connection */
		unset($database);
		require_once('includes/global.php');
		/* Load language */
		$lang = $language->admin_language_select($database, $root_connection, "login");

		/* Set up template vars */
		if(isset($error))
		{
			$template->assign_block_vars('error', $error);
			$var = true;
		}
		$content = array(
				'TITLE' => $site_title,
				'META_KEYWORDS' => $meta_keywords,
				'META_DESCRIPTION' => $meta_description,
				'VAR' => $var,
		);
		$template->assign_vars(array_merge($lang[0], $lang[1], $content));
		/* Template engine start */
		$template->set_filenames(array(
			'header' => 'admin/overall_header.html',
			'footer' => 'admin/overall_footer.html',
			'body' => 'admin/login.html'
		));
		/* Ouput the page */
		$template->display('header');
		$template->display('body');
		$template->display('footer');
	break;
}
?>