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
		$lang = $this->new_system($database,$root_connection);
		$contents = $this->create($file, $lang);
		return $contents;
	}

	public function install()
	{
		$allowed_langs = $this->check_available_languages();
		$lang = $this->lang_getfrombrowser($allowed_langs, false, null, false);
		return $lang;
	}
	
	public function language_select_install($database, $root_connection)
	{
		$lang = $this->new_system($database,$root_connection);
		include 'language/'.$lang.'/install.php';
		return $install_lang;
	}
	public function language_mail($database, $root_connection, $file)
	{
		$lang = $this->new_system($database,$root_connection);
		include 'language/'.$lang.'/mail/'. $file .'.php';
		return $mail_lang;
	}
	
	public function admin_language_select($database, $root_connection, $file)
	{
		$lang = $this->new_system($database,$root_connection);
		$contents = $this->create($file, $lang, $folder = 'admin');
		return $contents;
	}
	
	private function create($file, $language_name, $folder = "") {
		$all = 'all';
		if(!empty($folder))
		{
			$file = $folder.'/'.$file;
			$all = $folder.'/'.$all;
		}
		if(file_exists('language/'.$language_name.'/'. $file .'.php')) {
			require_once 'language/'.$language_name.'/'.$all.'.php';
			if(md5($main['LINK_FOOTER']) != 'da7af66d97fa213fffe7a994fbbfdd68')
			{
				exit();
			}
			require_once 'language/'.$language_name.'/'. $file .'.php';
			return array($main, $language);
		}
		else
		{
			$this->error('Could not find required language files:
			language/'.$language_name.'/'. $file .'.php');
		}
	}

	private function new_system($database,$root_connection,$install = false)
	{
		if($install == false)
		{
			$language = $database->query("SELECT config_value FROM `hosting_config` WHERE `config_name` = 'default_language';", $root_connection);
			$row = mysql_fetch_array($language);
		}
		$allowed_langs = $this->check_available_languages();
		$lang = $this->lang_getfrombrowser($allowed_langs, $row['config_value'], null, false);
		return $lang;
	}

	public function check_available_languages()
	{
		$dir = "./language/";
		if ($handle = opendir($dir))
		{
		   while (false !== ($file = readdir($handle))) {
			   if ($file != "." && $file != ".."&& $file != ".svn")
			   {
					if(is_dir($dir.$file))
					{
						$name = substr($file,0,2);
						$available[] = $name;
					}
			   }
		   }
		   closedir($handle);
		}
		return $available;
	}
	
	// By http://aktuell.de.selfhtml.org/artikel/php/httpsprache/
	private function lang_getfrombrowser($allowed_languages, $default_language, $lang_variable = null, $strict_mode = true)
	{
		if ($lang_variable === null)
		{
			if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$lang_variable = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			}
			else
			{
				$lang_variable = '';
			}
		}
		if (empty($lang_variable))
		{
			return $default_language;
		}
		$accepted_languages = preg_split('/,\s*/', $lang_variable); 
		$current_lang = $default_language;
		$current_q = 0;
		foreach ($accepted_languages as $accepted_language)
		{
			$res = preg_match ('/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i', $accepted_language, $matches);
			if (!$res)
			{
				continue;
			}
			$lang_code = explode ('-', $matches[1]);
			if (isset($matches[2]))
			{
				$lang_quality = (float)$matches[2];
			}
			else
			{
				$lang_quality = 1.0;
			}
			while (count ($lang_code))
			{
				if (in_array(strtolower (join ('-', $lang_code)), $allowed_languages))
				{
					if ($lang_quality > $current_q)
					{
						$current_lang = strtolower (join ('-', $lang_code));
						$current_q = $lang_quality;
						break;
					}
				}
				if ($strict_mode)
				{
					break;
				}
				array_pop($lang_code);
			}
		}
		return $current_lang;
	}
	
	private function error($error_message = "",$query = "No Query Executed")
	{
		require_once('includes/functions/error.php');
		$error = new error();
		$error->output('Language-System Failure',$error_message,$query);
	}
}
?>