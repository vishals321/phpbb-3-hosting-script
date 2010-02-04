<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: includes/functions/language.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
class language{
	public function language_select($database, $root_connection, $file)
	{
		$language = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'default_language';", $root_connection);
		$row = mysql_fetch_array($language);
		$contents = $this->create($file, $row['config_value']);
		return $contents;
	}
	private function create($file, $language_var) {
		if(file_exists('language/'.$language_var.'/'. $file .'.php')) {
			include 'language/'.$language_var.'/all.php';
			if(md5($main['LINK_FOOTER']) != 'da7af66d97fa213fffe7a994fbbfdd68')
			{
				exit();
			}
			include 'language/'.$language_var.'/'. $file .'.php';
			return array($main, $language);
		}
		else
		{
			echo "Datei existiert nicht";
		}
	}
	public function language_select_install($database, $root_connection)
	{
		$language = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'default_language';", $root_connection);
		$row = mysql_fetch_array($language);
		include 'language/'.$row['config_value'].'/install.php';
		return $install_lang;
	}
	public function language_mail($database, $root_connection, $file)
	{
		$language = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'default_language';", $root_connection);
		$row = mysql_fetch_array($language);
		include 'language/'.$row['config_value'].'/mail/'. $file .'.php';
		return $mail_lang;
	}
	
	public function admin_language_select($database, $root_connection, $file)
	{
		$language = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'default_language';", $root_connection);
		$row = mysql_fetch_array($language);
		$contents = $this->admin_create($file, $row['config_value']);
		return $contents;
	}
	private function admin_create($file, $language_var) {
		if(file_exists('language/'.$language_var.'/admin/'. $file .'.php')) {
			include 'language/'.$language_var.'/admin/'. $file .'.php';
			include 'language/'.$language_var.'/admin/all.php';
			return array($main, $language);
		}
		else
		{
			echo "Datei existiert nicht";
		}
	}
}
?>