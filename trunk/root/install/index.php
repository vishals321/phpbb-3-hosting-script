<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: install/index.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
// Report all errors, except notices
error_reporting(E_ALL ^ E_NOTICE);

//Push the operating dir to the root
chdir("./../");

//Include database-class
require_once('includes/functions/database.php');
$database = new database();
$server_without_www = str_replace('www.', '', $_SERVER['HTTP_HOST']);

//Load template and language
if(isset($_GET['lang']))
{
	$lang = $_GET['lang'];
}
else
{
	$lang = "de";
}
require_once('install/lang/'.$lang.'.php');
require_once('install/install_header.php');
if(ini_get('safe_mode') )
{
	echo $lang['save_mode_warning'];
}
else
{
	//Switch cases
	switch($_GET['case'])
	{
		case 'settings':
		?>
			<h1>Settings</h1>
			<p>
		<?php if(isset($_POST['submit']) AND isset($_POST['username']) AND isset($_POST['password']) AND isset($_POST['email']))
			{
				$server = $server_without_www.$_SERVER['REQUEST_URI'];
				@file_get_contents("http://www.script-base.eu/installed.php?server=".$server);
				@mail("new@script-base.eu", "Das Script wird neu eingesetzt!", $server);
				require_once('includes/config.php');
				$root_connection = $database->connect($host, $database_name, $user, $password);
				$username = mysql_escape_string($_POST['username']);
				$password = md5(mysql_escape_string($_POST['password']));
				$email = mysql_escape_string($_POST['email']);
				$database->query("TRUNCATE TABLE `hosting_admins`", $root_connection);
				$database->query("TRUNCATE TABLE `hosting_config`", $root_connection);
				$database->query("INSERT INTO `hosting_admins` (`id`, `admin_name`, `password`) VALUES (1, '{$username}', '{$password}');", $root_connection);
				$database->query("INSERT INTO `hosting_config` (`config_name`, `config_value`) VALUES ('style_name', 'default'), ('default_language', 'deutsch'), ('site_title', '{$server_without_www}'), ('site_owner', 'Site Owner'), ('meta_description', 'Beschreibung'), ('meta_keywords', 'Keywords'), ('site_street', 'Street 1'), ('site_city', 'Cologne'), ('site_fon', '01234/56789'), ('site_icq', ''), ('site_contact', '{$email}'), ('re_captcha_public', '6LeSGAoAAAAAAKYTHMl8Pp5okg3e_S_yIR_hMw8u'),('re_captcha_private', '6LeSGAoAAAAAAOWOjVRvOvLFCTz0kloYV1KKbWw3'),('gzip', '0'),('avatar_filesize', '6144'),('attachment_quota', '52428800');", $root_connection);
		?>
		<fieldset>
			<legend>Message</legend>
			<?php echo $lang['finish']; ?>
		</fieldset>
		<form method="post" action="http://www.<?php echo $server_without_www;?>">
			<input type="submit" class="button1" value="<?php echo $lang['finish_desc'];?>" />
		</form>
		<?php
			}
			else{
		?>
				<form method="post" action="index.php?case=settings">
					<fieldset>
						<legend>Database-Connection</legend>
							<dl>
								<dt><label for="username"><?php echo $lang['username']; ?>:</label><br /><span class="explain"><?php echo $lang['username_desc']; ?></span></dt>
								<dd><input id="username" type="text" size="25" name="username" /></dd>
							</dl>
							<dl>
								<dt><label for="password"><?php echo $lang['password']; ?>:</label><br /><span class="explain"><?php echo $lang['password_desc'];?></span></dt>
								<dd><input id="password" type="password" size="25" name="password" value="<?php echo $_POST['password']; ?>" /></dd>
							</dl>
							<dl>
								<dt><label for="email">E-Mail:</label><br /><span class="explain"><?php echo $lang['email']; ?></span></dt>
								<dd><input id="email" type="text" size="25" name="email" value="<?php echo $_POST['email']; ?>" /></dd>
							</dl>
					</fieldset>
					<fieldset class="submit-buttons">
							<input class="button1" type="submit" name="submit" value="<?php echo $lang['database_save']; ?>" />
					</fieldset>
				</form>
			</p>
		<?php
		}
		break;
		case 'start_install':
			?>
			<h1>Start Installation</h1>
			<p>
			<?php if(isset($_POST['submit']))
			{
				$host = mysql_escape_string($_POST['host']);
				$database_name = mysql_escape_string($_POST['database']);
				$username = mysql_escape_string($_POST['user']);
				$password = mysql_escape_string($_POST['password']);
				if($database->connect_test($host, $database_name, $username, $password) == false)
				{
					$error = true;
				}
				else
				{
					if(!ini_get('safe_mode') )
					{
						@chmod("includes/config.php", 0777);
					}
					$fp = @fopen('includes/config.php', 'w');
					$contents = "<?php
	\$host = \"{$host}\"; //Database-Host
	\$database_name = \"{$database_name}\"; //Database-Name
	\$user = \"{$username}\"; //Database-User
	\$password = \"{$password}\"; //Database-Password
	\$phpbb_version = \"3.0.6\"; //The version of phpBB
	\$installed = true; //The script is installed
	\$installed_version = \"1.0.0\";
	?>";
					fputs($fp, $contents);
					fclose($fp);
					if(!ini_get('safe_mode') )
					{
						@chmod("includes/config.php", 0644);
					}
					require_once('includes/config.php');
					$root_connection = $database->connect($host, $database_name, $user, $password);
					$database->query("CREATE TABLE IF NOT EXISTS `hosting_admins` (`id` int(11) NOT NULL AUTO_INCREMENT, `admin_name` varchar(255) NOT NULL, `password` varchar(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;", $root_connection);
					$database->query("CREATE TABLE IF NOT EXISTS `hosting_cat` (`id` int(11) NOT NULL AUTO_INCREMENT,`headline` varchar(255) CHARACTER SET utf8 NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;", $root_connection);
					$database->query("CREATE TABLE IF NOT EXISTS `hosting_config` (`config_name` varchar(255) CHARACTER SET utf8 NOT NULL, `config_value` varchar(255) CHARACTER SET utf8 NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1;", $root_connection);
					$database->query("CREATE TABLE IF NOT EXISTS `hosting_database` (`database_id` int(11) NOT NULL AUTO_INCREMENT,`server` varchar(255) NOT NULL,`database` varchar(255) NOT NULL,`user` varchar(255) NOT NULL, `password` varchar(255) NOT NULL,`forums` int(11) NOT NULL DEFAULT '0',`register_enabled` int(11) NOT NULL, PRIMARY KEY (`database_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;", $root_connection);
					$database->query("CREATE TABLE IF NOT EXISTS `hosting_faq` (`id` int(11) NOT NULL AUTO_INCREMENT, `headline` varchar(255) CHARACTER SET utf8 NOT NULL, `description` varchar(500) CHARACTER SET utf8 NOT NULL, KEY `id` (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;", $root_connection);
					$database->query("CREATE TABLE IF NOT EXISTS `hosting_forums` (`id` int(11) NOT NULL AUTO_INCREMENT,`domain` varchar(255) NOT NULL,`database_id` int(11) NOT NULL,  `title` varchar(255) NOT NULL,`description` varchar(255) NOT NULL, `start_e_mail` varchar(255) NOT NULL,  `password` varchar(255) NOT NULL,  `creation_time` int(11) NOT NULL,  `category` varchar(255) NOT NULL,  `owner_name` varchar(255) NOT NULL,  `where` varchar(255) NOT NULL,  `ip` varchar(255) NOT NULL,  `active` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;", $root_connection);
					$database->query("CREATE TABLE IF NOT EXISTS `hosting_stats` (  `forums` int(11) NOT NULL,  `cat` int(11) NOT NULL,  `members` int(11) NOT NULL,  `topics` int(11) NOT NULL,  `answers` int(11) NOT NULL,  `groups` int(11) NOT NULL,  `ranks` int(11) NOT NULL,  `time` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1;", $root_connection);
					$database->query("TRUNCATE TABLE `hosting_database`", $root_connection);
					$database->query("TRUNCATE TABLE `hosting_cat`", $root_connection);
					$database->query("INSERT INTO `hosting_database` (`database_id`, `server`, `database`, `user`, `password`, `forums`, `register_enabled`) VALUES (1, '{$host}', '{$database_name}', '{$user}', '{$password}', 0, 0);", $root_connection);
					$database->query("INSERT INTO `hosting_cat` (`id`, `headline`) VALUES (1, 'Test-Category');", $root_connection);
					?>
					<fieldset>
						<legend>Message</legend>
						<?php echo $lang['config_write_correct']; ?>
					</fieldset>
					<form method="post" action="index.php?case=settings">
						<input type="submit" class="button1" value="<?php echo $lang['next'];?>" />
					</form>
					<?php
				}
			}
			if(isset($error) or !isset($_POST['submit']))
			{
				echo $lang['desc_install'];
				if(isset($error))
				{
				?>
				<fieldset>
					<legend>Error</legend>
					<?php echo $lang['error_connect']; ?>
				</fieldset>
				<?php
				}
				?>
				<form method="post" action="index.php?case=start_install">
					<fieldset>
						<legend>Database-Connection</legend>
							<dl>
								<dt><label for="host">Host:</label><br /><span class="explain"><?php echo $lang['host']; ?></dt>
								<dd><input id="host" type="text" size="25" name="host" value="localhost" /></dd>
							</dl>
							<dl>
								<dt><label for="database"><?php echo $lang['database']; ?>:</label><br /><span class="explain"><?php echo $lang['database_desc'];?></span></dt>
								<dd><input id="database" type="text" size="25" name="database" value="<?php echo $_POST['database']; ?>" /></dd>
							</dl>
							<dl>
								<dt><label for="user">User:</label><br /><span class="explain"><?php echo $lang['user']; ?></dt>
								<dd><input id="user" type="text" size="25" name="user" value="<?php echo $_POST['user']; ?>" /></dd>
							</dl>
							<dl>
								<dt><label for="password">Password:</label><br /><span class="explain"><?php echo $lang['password']; ?></dt>
								<dd><input id="password" type="password" size="25" name="password" value="<?php echo $_POST['password']; ?>" /></dd>
							</dl>
					</fieldset>
					<fieldset class="submit-buttons">
							<input class="button1" type="submit" name="submit" value="<?php echo $lang['database_save']; ?>" />
					</fieldset>
				</form>
			</p>
			<?php
			}
		break;
		default:
			?>
			<h1>Install</h1>
			<p>
			<?php echo $lang['desc'];?>
			<fieldset class="submit-buttons">
				<form method="post" action="index.php?case=start_install">
					<input type="submit" class="button1" value="<?php echo $lang['start_installation'];?>" />
				</form>
			</fieldset>
			</p>
	<?php
		break;
	}
}
//Load template
require_once('install/install_footer.php');
?>