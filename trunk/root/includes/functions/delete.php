<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: includes/functions/delete.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
class delete
{
	public function delete_forum($forum_domain, $database, $root_connection)
	{
		$avatar_path = "phpBB3/images/avatars/upload/".$forum_domain;
		$files_path = "phpBB3/files/".$forum_domain;
		$cache_path = "phpBB3/cache/".$forum_domain;
		$this->recursive_readdir($avatar_path);
		$this->recursive_readdir($files_path);
		$this->recursive_readdir($cache_path);
		@unlink("phpBB3/configs/".$forum_domain.".php");
		@unlink("phpBB3/images/logos/".$forum_domain.".gif");

		$database_id = mysql_fetch_array($database->query("SELECT `database_id` FROM `hosting_forums` WHERE `domain` = '{$forum_domain}';", $root_connection));	
		$database_info = mysql_fetch_array($database->query("SELECT `server`, `database`, `user`, `password` FROM `hosting_database` WHERE `database_id` = '{$database_id['database_id']}';", $root_connection));
		$database->query("UPDATE `hosting_database` SET `forums` = `forums`-1 WHERE `database_id` ='{$database_id['database_id']}';", $root_connection);
		unset($database_id);
		
		$new_connection = $database->connect($database_info['server'],$database_info['database'],$database_info['user'],$database_info['password'], 1);
		require_once "includes/install/delete.php";
		$phpbb_install_queries = str_replace("<# phpBB #>", $forum_domain, $phpbb_install_queries);
		for ($i = 0; $i < count($phpbb_install_queries); $i++)
		{
			$database->query($phpbb_install_queries[$i], $new_connection);
		}
		
		$database->query("DELETE FROM `hosting_forums` WHERE `domain` = '{$forum_domain}';", $root_connection);
		$database->close($new_connection);
		return true;
	}
	
	public function recursive_readdir($path)
	{
		$handle = @opendir($path);
		while (($file = @readdir($handle)) !== false)
		{
			if ($file != '.' && $file != '..')
			{
				$filepath = $path . '/' . $file;
				if (is_dir($filepath))
				{
					@rmdir($filepath);
					@recursive_readdir($filepath);
				}
				else
				{
					@unlink($filepath);
				}
			}
		}
		@closedir($handle);
		@rmdir($path);
	}

}
?>