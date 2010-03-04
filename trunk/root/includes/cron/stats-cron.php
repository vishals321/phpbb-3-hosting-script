<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: includes/cron/stats-cron.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
if($renew = true)
{
	require_once('includes/global.php');
	$var = (int) 0;
	$var1 = (int) 0;
	$var2 = (int) 0;
	$var4 = (int) 0;
	$var5 = (int) 0;
	$var6 = (int) 0;
	$zeit = time();
	$leeren1 = $database->query("TRUNCATE hosting_stats;", $root_connection);
	$sql = $database->query("SELECT `domain`,`database_id` FROM `hosting_forums` ORDER BY `database_id`;", $root_connection);
	if ($database->total_rows($sql) < 1)
	{
		echo "error";
	}
	else
	{
		$database_id = -1;
		while ($row = mysql_fetch_array($sql))
		{
			if($row['database_id'] != $database_id)
			{
				$database_info = mysql_fetch_array($database->query("SELECT `server`,`database`,`user`,`password` FROM `hosting_database` WHERE `database_id` = '{$row['database_id']}';", $root_connection));
				$new_connection = $database->connect($database_info['server'], $database_info['database'],$database_info['user'],$database_info['password'], 1);
			}
			$database_id = $row['database_id'];
			$members = $database->total_rows($database->query("SELECT user_id FROM `{$row['domain']}_users`;", $new_connection)) - $database->total_rows($database->query("SELECT bot_id FROM `{$row['domain']}_bots`;", $new_connection))-1;
			$var = $var + $members;

			$themen = $database->total_rows($database->query("SELECT topic_id FROM `{$row['domain']}_topics`;", $new_connection));
			$var1 = $var1 + $themen;

			$beitraege = $database->total_rows($database->query("SELECT post_id FROM `{$row['domain']}_posts`;", $new_connection));
			$var2 = $var2 + $beitraege;

			$gruppen = $database->total_rows($database->query("SELECT group_id FROM `{$row['domain']}_groups`;", $new_connection))-6;
			$var3 = $var3 + $gruppen;

			$rang = $database->total_rows($database->query("SELECT rank_id FROM `{$row['domain']}_ranks`;", $new_connection));
			$var4 = $var4 + $rang;

			$foren = $database->total_rows($database->query("SELECT forum_id FROM `{$row['domain']}_forums`;", $new_connection));
			$var5 = $var5 + $foren;

			$var6 = $var6 +1;

			$beforthreemonth = time()-7776030;
			$database->query("DELETE FROM `{$row['domain']}_sessions` WHERE `session_start` < '".$beforthreemonth."';", $new_connection);
			$database->query("DELETE FROM `{$row['domain']}_sessions_keys` WHERE `last_login` < '".$beforthreemonth."';", $new_connection);
		}
		$database->query("INSERT INTO hosting_stats (`forums`,`cat`,`members`,`topics`,`answers`,`groups`,`ranks`,`time`) VALUES ('$var6','$var5','$var','$var1','$var2','$var3','$var4', '$zeit');", $root_connection);
		$database->close($new_connection);
	}
}
?>