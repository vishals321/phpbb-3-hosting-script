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
	private function error($error_message = "",$query = "No Query Executed")
	{
		require_once('includes/functions/error.php');
		$error_message = mysql_error();
		$error = new error();
		$error->output('MySQL-Error',$error_message,$query);
	}
/* Open database connection */
	public function connect($host, $database, $username, $password, $new_connection = NULL)
	{
		$connection = @mysql_connect($host, $username, $password, true);
		if(is_resource($connection) == false)
		{
			$this->error(false, 'Want to connect to '.$host.' with user '.$username);
		}
		else
		{
			if(@mysql_select_db($database, $connection) == false)
			{
				$this->error(false, 'While trying to connect to '.$database);
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
				$this->error(false,$query);
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
}
?>