<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "cache");
if(isset($_POST['submit']))
{
	function delete($file)
	{ 
		chmod($file,0777); 
		if(is_dir($file))
		{ 
			$handle = opendir($file); 
			while($filename = readdir($handle))
			{ 
				if ($filename != "." && $filename != "..")
				{ 
				delete($file."/".$filename); 
			} 
		} 
		closedir($handle); 
		}
		else
		{ 
			unlink($file); 
		} 
	}
	$sql = $database->query("SELECT `domain` FROM `hosting_forums` ORDER BY `creation_time` DESC", $root_connection);
	while($row = mysql_fetch_array($sql)) 
	{ 			
		$folder = "phpBB3/cache/{$row['domain']}";
		delete($folder);
	}
	$message = true;
}
/* Set up template vars */
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'MESSAGE' => $message,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/cache.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>