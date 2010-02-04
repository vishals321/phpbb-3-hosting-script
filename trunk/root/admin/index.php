<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "index");

/* Load SQL */
$site = $_GET['site'];
if(empty($site) or !is_numeric($site))
{
	$start = 0;
	$end = 15;
}
else
{
	$start = $site*15;
	$end = $start+15;
}
if(isset($_GET['delete']))
{
	$forum_domain = mysql_escape_string($_GET['delete']);
	$sql = $database->total_rows($database->query("SELECT domain FROM `hosting_forums` WHERE `domain` = '{$forum_domain}';", $root_connection));
	if($sql == 0)
	{
		$no_forum = true;
	}
	else
	{
		require_once('includes/functions/delete.php');
		$delete = new delete();
		$del = $delete->delete_forum($forum_domain, $database, $root_connection);
	}
}
else
{
	$sql = $database->query("SELECT * FROM `hosting_forums` ORDER BY `creation_time` DESC LIMIT {$start}, {$end};", $root_connection);
	if ($database->total_rows($sql) < 1)
	{
		$error = true;
	}
	else
	{
		while ($row = mysql_fetch_array($sql))
		{	
			//Build up a connection to the database where the forum is located
			$database_info = mysql_fetch_array($database->query("SELECT * FROM `hosting_database` WHERE `database_id` = '{$row['database_id']}';", $root_connection));
			$new_connection = $database->connect($database_info['server'],$database_info['database'],$database_info['user'],$database_info['password'], 1);
			unset($database_info);
			
			$last_post = mysql_fetch_array($database->query("SELECT post_time FROM `{$row['domain']}_posts` ORDER BY `post_id` DESC LIMIT 1;", $new_connection));
			$answers = $database->total_rows($database->query("SELECT post_time FROM `{$row['domain']}_posts`;", $new_connection));
			$topics = $database->total_rows($database->query("SELECT * FROM `{$row['domain']}_topics`;", $new_connection));
			$members = 	$database->total_rows($database->query("SELECT * FROM `{$row['domain']}_users`;",  $new_connection)) - $database->total_rows($database->query("SELECT * FROM `{$row['domain']}_bots`;",  $new_connection)) - 1;	
			$domain = $row['domain'];
			$date_created = date("d.m.Y H:i", $row['creation_time']);
			$day_without_post = floor((mktime() - $last_post['post_time']) / 86400);
			$title = $row['title'];
			$desc = $row['description'];
			$info_array = array(
				"LAST_POST" 		=> $last_post,
				"ANSWERS" 			=> $answers,
				"TOPICS" 			=> $topics,
				"MEMBERS"			=> $members,
				"DOMAIN"			=> $domain,
				"DATE_CREATED"		=> $date_created,
				"DAY_WITHOUT_POST"	=> $day_without_post,
				"TITLE"				=> $title,
				"DESC"				=> $desc,
				);
			$template->assign_block_vars('info', $info_array);
		}
		$database->close($new_connection);
		$sites = $database->total_rows($database->query("SELECT id FROM `hosting_forums`;", $root_connection));
		$how_many_sites = $sutes/15;
		$site_links = " <a href=\"\">0</a> ";
		for($a=0; $a < $how_many_sites; $a++)
		   {
			$b = $a+1;
			if($seite == $b)
				{
					$site_links .= " <b>{$b}</b> ";
				}
			else
				{
					$site_links .= " <a href=\"index.php?site={$b}\">{$b}</a> ";
				}
		   }
	}
}
/* Set up template vars */
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'ERROR' => $error,
		'SITE_LINKS' => $site_links,
		'DEL' => $del,
		'NO_FORUM' => $no_forum,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/index.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>