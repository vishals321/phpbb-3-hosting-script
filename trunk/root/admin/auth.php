<?php
chdir("./../"); 
session_start();
$logged_in_admin = $_SESSION['logged_in_admin'];
require_once('includes/config.php');
require_once('includes/functions/database.php');
$database = new database();
$root_connection = $database->connect($host, $database_name, $user, $password);

/* Load title, description and so on */
$title = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'site_title';", $root_connection);
$title = mysql_fetch_array($title);
$site_title = $title['config_value'];
unset($database);
if($logged_in_admin == false)
{
	header('Location: login.php');
}
$admin_id = $_SESSION['id'];
$user_name = $_SESSION['admin_name'];
require_once('includes/global.php');
?>