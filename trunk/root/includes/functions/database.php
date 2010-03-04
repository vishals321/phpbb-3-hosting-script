<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: includes/functions/database.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
class database
{
/* Open database connection */
	public function connect($host, $database, $username, $password, $new_connection = NULL)
	{
		$connection = @mysql_connect($host, $username, $password, true);
		if (is_resource($connection) == false)
		{
			$this->error();
		}
		else
		{
			if(@mysql_select_db($database, $connection) == false)
			{
				$this->error();
			}
			else
			{
				if(is_resource($this->main_connect) == false) {
					$this->main_connect = $connection;
				}
				else
				{
					if($new_connection != NULL)
					{
						if (is_array($this->alt_connect) == true)
						{
							$this->alt_connect = array();
						}
						$this->alt_connect[$new_connection] = $connection;
						}
					}
				}		
		return $connection;
		}
	}
/* Query the database */
	public function query($query, $connection)
		{
			$this->query_result = mysql_query($query, $connection);

			if ($this->query_result == false) {
				$this->error($query);
			} else {
				return $this->query_result;
			}
		}
/* Get row-count of result */
	public function total_rows($query_id)
	{
		return mysql_num_rows($query_id);
	}
/* Close all database connections */	
	public function close($connection)
	{
		if(mysql_close($connection))
		{
			return true;
		}
		else
		{
			return false;
		}			
	}
/* Select Database for forums */
	public function sel_database($root_connection)
	{
		$query = $this->query("SELECT `database_id`, `server`, `database`, `user`, `password` FROM `hosting_database` WHERE `register_enabled` = '0' ORDER BY forums;", $root_connection);
		$answer = mysql_fetch_array($query);
		$resource = $this->connect($answer['server'],$answer['database'],$answer['user'],$answer['password'], 1);
		$this->query("UPDATE `hosting_database` SET `forums` = `forums`+1 WHERE `database_id` ='{$answer['database_id']}';", $root_connection);
		return(array("res" => $resource, "id" => $answer['database_id']));
	}
/* Test database connection */	
	public function connect_test($host, $database, $username, $password)
	{
		$connection = @mysql_connect($host, $username, $password, true);
		if (is_resource($connection) == false)
		{
			return false;
		}
		elseif(@mysql_select_db($database, $connection) == false)
		{
			return false;
		}
		else
		{
			$this->close($connection);
			return true;
		}
	}
/* If is error while handle with the database*/
	public function error($query = "No Query Executed")
		{
			$error_message = mysql_error();
			$error_html = "\t\t\t<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
			<html>
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
					<title>MySQL Fehler</title>
					<style type=\"text/css\">
					    	* { font-size: 100%; margin: 0; padding: 0; }
						body { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 75%; margin: 10px; background: #FFFFFF; color: #000000; }
						a:link, a:visited { text-decoration: none; color: #005fa9; background-color: transparent; }
						a:active, a:hover { text-decoration: underline; }						
						textarea { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; border: 1px dashed #000000; background: #FFFFFF; padding: 5px; background: #f4f4f4; }
					</style>
				</head>
				<body>
					<p>
						<b>MySQL Fehler</b>
						<br /><br />
						Ein MySQL Fehler trat auf. 
						Bitte kopiere die nachfolgende Fehler-Meldung und sende uns diese, wenn möglich, über unser <a href=\"contact.php\">Kontakt-Formular</a>.
						<br /><br />
						<textarea readonly=\"readonly\" rows=\"15\" cols=\"40\" style=\"width:500px;\">Aktuelle Zeit: ".date("F j, Y, g:i:s a")."\nIP Addresse: {$_SERVER['REMOTE_ADDR']}\rFehler: {$error_message}\nQuery Executed: {$query}</textarea>
					</p>		
				</body>
			</html>";
			exit($error_html);
			return;
		}
}
?>