<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "cat");
/* Switch Case */
switch($_GET['case'])
{
	case 'new':
		$case = 'new';
	break;
	case 'del':
		if(isset($_GET['id']))
		{
			$id = mysql_escape_string($_GET['id']);
			$database->query("DELETE FROM `hosting_cat` WHERE `id` = {$id};", $root_connection);
			$error['DEL'] = true;
		}
	break;
	case 'new-go':
		if(isset($_POST['submit']))
		{
			$headline = mysql_escape_string($_POST['headline']);
			$error['NO_ERROR'] = true;
			$database->query("INSERT INTO `hosting_cat` (`headline`) VALUES ('{$headline}');", $root_connection);
		}
	break;
	case 'edit-go':
		if(isset($_POST['submit']))
		{
			$id = mysql_escape_string($_POST['id']);
			$headline = mysql_escape_string($_POST['headline']);
			$error['NO_ERROR'] = true;
			$database->query("UPDATE `hosting_cat` SET `headline` = '{$headline}' WHERE `id` = {$id};", $root_connection);
		}
	break;
	case 'edit':
		$id = mysql_escape_string($_GET['id']);
		$out = $database->query("SELECT * FROM `hosting_cat` WHERE `id` = '{$id}';", $root_connection);
		$sql = $database->total_rows($out);
		if($sql == 1)
		{
			$out_i = mysql_fetch_array($out);
			$case = 'edit';
		}
		else
		{
			$error['THERE'] = true;
		}	
	break;
}
/*Normal output*/
$sql = $database->query("SELECT * FROM `hosting_cat` ORDER BY `headline` ASC;", $root_connection);
while ($row = mysql_fetch_array($sql))
{
	$template->assign_block_vars('cat', array('ID' => $row['id'], 'HEADLINE' => $row['headline']));
}
/* Set up template vars */
if(isset($error))
{
	$template->assign_block_vars('error', $error);
	$error_da = true;
}
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'CASE' => $case,
		'ERROR' => $error_da,
		'HEADLINE' => $out_i['headline'],
		'ID' => $id,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/cat.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>