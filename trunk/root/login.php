<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: login.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
/* Set up the root connection */
require_once('includes/config.php');
require_once('includes/functions/database.php');
$database = new database();
$root_connection = $database->connect($host, $database_name, $user, $password);

/* Switch cases */
switch($_GET['case'])
{
	case 'logout':
			/* Log the user out */
		    session_start();
			session_destroy();
			header('Location: user.php');
	break;
	default:
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
			
			$checkdata = $database->query("SELECT `id`,`domain`, `password` FROM `hosting_forums` WHERE `domain` = '{$username}';", $root_connection);
			$data = mysql_fetch_array($checkdata);	
			
			if ($database->total_rows($checkdata)==0)
			{ 
				 $error['THERE'] = true;
			}
			
			if(md5($password) != $data['password'])
			{
				$error['PASSWORD_W'] = true;
			}
			
			session_start();
			if(!isset($error))
			{
			   $_SESSION['logged_in'] = true;
			   $_SESSION['id'] = $data['id'];
			   $_SESSION['domain'] = $data['domain'];
			   header('Location: user.php');
			   exit;
			}
			else
			{
				$_SESSION['error'] = $error;
				$_SESSION['logged_in'] = false;
				header('Location: user.php');
				exit;			
			}
		}
	break;
}
?>